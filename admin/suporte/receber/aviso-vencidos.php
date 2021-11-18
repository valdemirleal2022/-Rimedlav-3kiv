
<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	//$data1 = date("Y-m-d", strtotime("-3 day"));
	//$data2 = date("Y-m-d", strtotime("-360 day"));


	$data1 = $_SESSION['inicio'];
	$data2 = $_SESSION['fim'];

	$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2'
								AND status='Em Aberto' ORDER BY vencimento DESC");
	
	$contador=0;

	if($leitura){
	  foreach($leitura as $receber):
		
		$receberId = $receber['id'];
		$contratoId = $receber['id_contrato'];
		$clienteId = $receber['id_cliente'];
		
		$contrato = mostra('cliente',"WHERE id = '$contratoId'");
		
		$cliente = mostra('cliente',"WHERE id = '$clienteId'");

		$enviarAviso='SIM';
		
		//verifica se é somente por correio
		if($receber['enviar_boleto_correio']=='1'){
			$enviarAviso='NAO';
		}
		
		//verifica se não é para enviar email
		if($cliente['nao_enviar_email']=='1'){
			$enviarAviso='NAO';
		}
		

		if(!empty($receber['refaturamento_vencimento'])){
				//verifica se não é para enviar email
			if($receber['refaturamento_vencimento']> date("Y-m-d") ){
					$enviarAviso='NAO';
			}
		 }
 		
		
	
		//if(substr($receber['interacao'],0,10) == date("Y-m-d") ){
		//	$enviarAviso ='NAO';
		//}
		
		if($receber['juridico']=='1'){
		  $enviarAviso='NAO';
		}
		
		
		if($enviarAviso=='SIM'){
			
			$linkBoleto =  "https://www.cleansistemas.com.br/cliente/painel2.php?execute=boleto/emitir-boleto&boletoId=" . $receberId;
 

			$linkNota =$receber['link'];
			$linkExtrato = "https://www.cleansistemas.com.br/cliente/painel2.php?execute=suporte/contrato/extrato-cliente-resumido&boletoId=" . $receberId;

			$valor= $receber['valor'] + $receber['juros'] - $receber['desconto'];

			$assunto = "Clean - COBRANCA EM ABERTO " . $cliente['nome'];
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";

			$msg .= "Consta em nosso sistema parcela(s) referente aos serviços prestados pela Clean Ambiental a sua empresa, caso o pagamento nao seja realizado em ate 30 dias da data de recebimento desta, informamos que nosso sistema encaminhara automaticamente o titulo ao Serasa. Caso este titulo ja esteja quitado favor desconsiderar esta cobrança e entrar em contato via telefone ou nos enviar o comprovante: <br /><br />";

			$msg .= "Cliente : " . $cliente['nome'] . "<br />";
			$msg .= "Email : " . $cliente['email_financeiro'] . "<br />";
			$msg .= "Senha : " . $cliente['senha'] . "<br />";
			$msg .= "Nosso Numero : " . $receber['id'] . "<br />";
			$msg .= "Data de Vencimento : " . converteData($receber['vencimento']) . "<br />";
			$msg .= "Valor R$  : " . converteValor($receber['valor']) . "<br />";

			$msg .= "Caso deseje visualizar rapidamente o boleto, a nota fiscal eletrônica ou o extrato, por gentileza clique nos links abaixo ou acesse o nosso site <br /><br />";

			//$msg .= "<a href=" . $linkBoleto . ">Gerar Boleto</a> <br />";
			//$msg .= "<a href=" . $linkExtrato . ">Gerar Extrato </a> <br /><br />";
		
			if(!empty($receber['nota'])){
			 //  $msg .= "<a href=" . $linkNota . ">Gerar NFe</a> <br /><br />";
			}
			
			$msg .= "Clicar no Link ou copiar para gerar o BOLETO  ". $linkBoleto . "<br /><br />";
			$msg .= "Clicar no Link ou copiar para gerar o EXTRATO ". $linkExtrato . "<br /><br />";
			
			if(!empty($receber['nota'])){
			  $msg .= "Clicar no Link ou copiar para gerar a NFE ". $linkNota . "<br /><br />";
			}
			
			$msg .= "APÓS 60 DIAS DE VENCIDO, O TÍTULO SERÁ ENCAMINHADO AUTOMATICAMENTE AO CARTÓRIO/PROTESTO. <br /><br />";

			$msg .= "Estamos também disponíveis no telefone 3104-2992 <br />";
			
			$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
			$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
			$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";
		
			
			$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
			$msg .=  "</font>";

			$atendimento='atendimento@cleanambiental.com.br ';
			enviaEmail($assunto,$msg,$atendimento,SITENOME,$cliente['email_financeiro'], $cliente['nome']);
			
			$cad['consultor']= $contrato['consultor'];
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['usuario']	=  $_SESSION['autUser']['nome'];
			update('receber',$cad,"id = '$receberId'");
			
			
			// INTERAÇÃO
			$interacao='Aviso de Cobrança enviado por email n. '.$receberId;
			interacao($interacao,$contratoId);
			
			$contador++;	
			
		}
		
	endforeach;
}

if($contador<>0){
	$_SESSION['retorna'] = '<div class="alert alert-success">Suas ' .$contador. ' mensagens foram enviadas com sucesso !</div>';
}else{
	$_SESSION['retorna'] = '<div class="alert alert-warning">Nenhuma mensagem enviada!</div>'; 
}

if( !isset ( $_SESSION['inicio'] ) ){
	$_SESSION['retorna'] = '<div class="alert alert-warning">Selecione Data!</div>'; 
}

header("Location: ".$_SESSION['url']);

?>


