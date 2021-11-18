<?php

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');

	$retorno = array();
	$ordemId = $_POST["ordemId"];
	
	//$ordemId='2694317';
	$retorno['retorno'] = "NO";

	$readordem = read('contrato_ordem',"WHERE id = '$ordemId'");
	if($readordem){
		foreach($readordem as $edit);
		
			$contratoId = $edit['id_contrato'];
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
		
			$cad['hora_coleta'] = date('H:i:s');
			$cad['quantidade1'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
			$cad['nao_coletada'] = strip_tags(trim(mysql_real_escape_string($_POST['naoColetada'])));
			$cad['observacao'] = strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
			$motivoNaoColetado = strip_tags(trim(mysql_real_escape_string($_POST['motivo'])));
			$cad['status'] = '13';
		
			$cad['qrcode'] = 0;
			if(isset($_POST['qrCode'])){
				$cad['qrcode'] = strip_tags(trim(mysql_real_escape_string($_POST['qrCode'])));	
			}
		
		
 			$cad['interacao']= date('Y/m/d H:i:s');
			 
			$clienteId = $edit['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
		
			$leitura = read('ordem_motivo_naocoletado',"WHERE nome = '$motivoNaoColetado'");
            if($leitura){
                 foreach($leitura as $monstra);
					$cad['motivo_nao_coletado'] = $monstra['id'];
			}
		
			if(!empty($_FILES['foto']['tmp_name'])){
				$imagem = $_FILES['foto'];
				$pasta  = '../uploads/fotos/';
				$tmp    = $imagem['tmp_name'];
				$ext    = substr($imagem['name'],-3);
				$nome   = md5(time()).'.'.$ext;
				$cad['foto'] = $nome;
				uploadImg($tmp, $nome, '350', $pasta);	
			}


			update('contrato_ordem',$cad,"id = '$ordemId'");
			$interacao='Ordem Realizada (Rota) n. '.$ordemId;
			interacao($interacao,$contratoId);

			if($edit['status']<>'13'){
					//ATUALIZADO SALDO CONTRATO;
				if($contrato['saldo_etiqueta_minimo']>0){
					$con['saldo_etiqueta'] = $contrato['saldo_etiqueta'] - $cad['quantidade1'];
					update('contrato',$con,"id = '$contratoId'");	
				}

			}

				$enviarAviso='SIM';

				if($cliente['nao_enviar_email']=='1'){
					$enviarAviso='NAO';
				}

				if($cad['nao_coletada']=='1'){
					//$enviarAviso='NAO';
				}

				if($cad['quantidade1']<='0'){
					$enviarAviso='ZERADA';
				}

				if(empty($cad['quantidade1'])){
					$enviarAviso='ZERADA';
				}

				if(is_numeric(substr($cliente['email'],0,2))){
					$enviarAviso='NAO';
				}
		
				
				if($edit['status']=='13'){
					 $enviarAviso='NAO';
				}
				if($edit['mensagem_baixa']!=null){
					 $enviarAviso='NAO';
				}
		
		 
				if($enviarAviso=='SIM'){
				
				 	$tipoColetaId = $edit['tipo_coleta1'];
					$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					$assunto = "Clean - COLETA REALIZADA " . $cliente['nome'];
					$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
					$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

					$msg .= "Prezado Cliente, <br /><br />";
					$msg .= "A Clean Ambiental informa que a coleta foi realizada com sucesso <br /><br />";


					$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'].' -'.$cliente['bairro'];

					$msg .= "Contrato N : " . substr($contrato['controle'],0,6) . "<br />";
					$msg .= "Data Inicio do Contrato : " . converteData($contrato['inicio']) . "<br />";
					$msg .= "Cliente : " . $cliente['nome'] . "<br />";
					$msg .= "Endereco : " . $endereco . "<br />";
					$msg .= "Ordem Numero : " . $edit['id'] . "<br />";
					$msg .= "Data da Coleta : " . converteData($edit['data']) . "<br />";
					$msg .= "Tipo de Coleta : " . $coleta['nome'] . "<br />";
					$msg .= "Quantidade  : " . $cad['quantidade1'] . "<br /><br />";
					$msg .= "Não Coletado  : " . $cad['nao_coletada'] . "<br /><br />";
					if($cad['qrcode']!=0){
						$msg .= "Código QR  : Coleta Autenticada<br /><br />";
					}
				
					$msg .= "Estamos também disponíveis no telefone 3104-2992  <br /><br />";

					$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
					$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
					$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";

					$msg .= "Caso haja divergência na quantidade coletada, estamos a disposição no prazo de 48 horas para contestação, caso não ocorra contato, será considerada a quantidade coletada correta para cobrança no próximo faturamento. <br /><br />";

					$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
					$msg .=  "</font>";
					
				 
					//$cliente['email']="wellington@wpcsistema.com.br";
					//$cliente['nome']="wellington";
						
					$administrativo='atendimento@cleanambiental.com.br';	
					enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email'], $cliente['nome']);

					$cad2['mensagem_baixa']= date('Y/m/d H:i:s');
					update('contrato_ordem',$cad2,"id = '$ordemId'");	
				} 

				if($enviarAviso=='ZERADA'){

				 	$tipoColetaId = $edit['tipo_coleta1'];
					$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					$assunto = "Clean - URGENTE – COLETA SEM RESÍDUO " . $cliente['nome'];
					$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
					$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

					$msg .= "Prezado Cliente, <br /><br />";
					$msg .= "Identificamos na realização da coleta de hoje, que não houve entrega de resíduo. Caso haja divergência na informação, favor entrar em contato conosco. <br /><br />";


					$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'].' -'.$cliente['bairro'];

					$msg .= "Contrato N : " . substr($contrato['controle'],0,6) . "<br />";
					$msg .= "Data Inicio do Contrato : " . converteData($contrato['inicio']) . "<br />";
					$msg .= "Cliente : " . $cliente['nome'] . "<br />";
					$msg .= "Endereco : " . $endereco . "<br />";
					$msg .= "Ordem Numero : " . $edit['id'] . "<br />";
					$msg .= "Data da Coleta : " . converteData($edit['data']) . "<br />";
					$msg .= "Tipo de Coleta : " . $coleta['nome'] . "<br />";
					$msg .= "Quantidade  : " . $cad['quantidade1'] . "<br /><br />";
					$msg .= "Não Coletado  : " . $cad['nao_coletada'] . "<br /><br />";
					if($cad['qrcode']!=0){
						$msg .= "Código QR  : Coleta Autenticada<br /><br />";
					}
				
					$msg .= "Estamos também disponíveis no telefone 3104-2992  <br /><br />";

					$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
					$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
					$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";
 

					$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
					$msg .=  "</font>";
					
				 
					//$cliente['email']="wellington@wpcsistema.com.br";
					//$cliente['nome']="wellington";
						
					$administrativo='atendimento@cleanambiental.com.br';	
					enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email'], $cliente['nome']);

					$cad2['mensagem_baixa']= date('Y/m/d H:i:s');
					update('contrato_ordem',$cad2,"id = '$ordemId'");	
			} 
		
			$retorno['retorno'] = "YES";
			echo json_encode($retorno);

	}else{
		
		$retorno['retorno'] = "NO";		
		
		
	}

	
?>