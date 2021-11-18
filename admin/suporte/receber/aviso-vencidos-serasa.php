
<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$readreceber = read('receber',"WHERE serasa='1'");
	if($leitura){
	  foreach($leitura as $receber):
		
		$receberId = $receber['id'];
		$clienteId = $receber['id_cliente'];
		$cliente = mostra('cliente',"WHERE id = '$clienteId'");
		
		$enviarAviso='SIM';
		
		//if(empty($receber['enviar_boleto_correio']=='1')){
//			$enviarAviso='NAO';
//		}
//		if(empty($cliente['nao_enviar_email']=='1')){
//			$enviarAviso='NAO';
//		}
//		
//		$enviaEmail=0;
//		$email=substr($cliente['email_financeiro'],0,2);
//		if(is_numeric($email)){
//          $enviarAviso='NAO';
//		 }elseif(!email($cliente['email_financeiro'])){
//		  $enviarAviso='NAO';
//		}
		
		if($enviarAviso=='SIM'){
			
		
			$assunto = "Clean Ambiental - Cliente no SERASA " . $cliente['nome'];
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
			$msg .="<img src='https://www.cleanambiental.com.br/site/images/header-logo.png'> <br /><br />";

			$msg .= "Consta em nosso sistema parcela(s) referente aos serviços prestados pela Clean Ambiental a sua empresa.
			Informamos ainda que se titulo está no SERASA. Caso este titulo ja esteja quitado favor desconsiderar esta cobrança e entrar em contato via telefone ou nos enviar o comprovante: <br />";

			$msg .= "Cliente : " . $cliente['nome'] . "<br />";
			$msg .= "Email : " . $cliente['email'] . "<br />";
			$msg .= "Senha : " . $cliente['senha'] . "<br />";
			$msg .= "Cliente : " . $cliente['nome'] . "<br />";
			$msg .= "Nosso Numero : " . $receber['id'] . "<br />";
			$msg .= "Data de Vencimento : " . converteData($receber['vencimento']) . "<br />";
			$msg .= "Valor R$  : " . $receber['valor'] . "<br />";

			$msg .= "<a href=" . $linkBoleto . ">Gerar Boleto</a> <br />";
			if(!empty($receber['nota'])){
				 $msg .= "<a href=" . $linkNota . ">Gerar NFe</a> <br /><br />";
			}
			$msg .= "<a href=" . $linkExtrato . ">Gerar Extrato </a> <br /><br />";

			$msg .= "Estamos também disponíveis no telefone 3104-2992 | 99871-0334 WhatsApp <br /><br />";
			$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
			$msg .=  "</font>";

			$administrativo='administrativo@cleanambiental.com.br';
	
			enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email_financeiro'], $cliente['nome']);
			

			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['usuario']	=  $_SESSION['autUser']['nome'];
			update('receber',$cad,"id = '$receberId'");
			
		}
		
	endforeach;
		
	$_SESSION['cadastro'] = '<div class="alert alert-success">Suas mensagens foram enviadas com sucesso!</div>';
		
}else{
		
	$_SESSION['cadastro'] = '<div class="alert alert-danger">Nenhuma mensagem enviada!</div>'; 
}

header("Location: ".$_SESSION['url']);

?>

