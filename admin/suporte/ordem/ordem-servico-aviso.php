<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'],'1')){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}

		echo '<head>';
		echo '<meta charset="iso-8859-1">';
		echo ' </head>';
 
	    $dataroteiro=$_SESSION['dataroteiro'];

	  	$leitura = read('contrato_ordem',"WHERE id AND status='12' AND data='$dataroteiro' ORDER BY data DESC, hora ASC");


		if(!$leitura){
			 header('Location: painel.php?execute=suporte/error');
		}
  
		foreach($leitura as $mostra):
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");

				$tipo_coletaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipo_coletaId'");

                $residuoId = $coleta['residuo'];
                $residuo = mostra('contrato_tipo_residuo',"WHERE id ='$residuoId'");


			// email	
		  	$assunto  = "Confirmação da Coleta : " . $cliente['nome'];
			$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#08a57f'>";
			$msg .="<img src='http://www.cleansistemas.com.br/site/images/header-logo.png'> <br /><br />";
			$msg .= "Confirmamos nossa coleta conforme segue os dados abaixo.<br /><br />";
		
			$msg .= "Ordem de Serviço N&deg; : " . $mostra['id'] . "<br />";
			$msg .= "Nome : " . $cliente['nome'] . "<br />";
			$msg .= "Email : " . $cliente['email'] . "<br />";
			$msg .= "Coleta : " . $coleta['nome'] . "<br />";
			$msg .= "Resíduo: " . $residuo['nome'] . "<br /><br />";
			$msg .= "Data do Serviço : " . converteData($mostra['data']) . "<br />";
			$msg .= "Hora do Serviço : " . $mostra['hora'] . "<br /><br />";
	
			$msg .= "Data : " . date('d/m/Y'). "<br /><br />";
			$msg .= "Caso tenha algum imprevisto e a coleta não posso ser executado, favor avisar nossa empresa !<br />";	
			$msg .=  "</font>";
			

			$operacional='Clean Ambiental';
			//$operacionalEmail='contato@wpccoletaderesiduos.com.br';
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_ordem',$cad,"id = '$ordemId'");	

 			enviaEmail($assunto,$msg,$operacionalEmail,$operacional,$cliente['email'],$cliente['nome']);
			enviaEmail($assunto,$msg,$cliente['email'],$cliente['nome'],$operacionalEmail,$operacional);


	 endforeach;
	 
	 header("Location: ".$_SESSION['url']);
?>

