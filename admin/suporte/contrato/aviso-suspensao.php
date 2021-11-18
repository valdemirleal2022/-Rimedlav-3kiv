
<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
 
	$data1 = $_SESSION['inicio'];
	$data2 = $_SESSION['fim'];
 
	$leitura = read('contrato_baixa',"WHERE data>='$data1' AND data<='$data2' AND tipo ='2' AND falta_pagamento='1' ORDER BY data ASC");

	$contador=0;

	if($leitura){
	  foreach($leitura as $contratoBaixa):

				$contratoId = $contratoBaixa['id_contrato'];

				$contrato = mostra('contrato',"WHERE id = '$contratoId'");

				$clienteId = $contrato['id_cliente'];
				$cliente = mostra('cliente',"WHERE id = '$clienteId'");
		 
				$assunto = "Clean - COBRANCA EM ABERTO " . $cliente['nome'];
				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

				$msg .= "Consta em nosso sistema parcela(s) referente(s) aos serviços prestados pelo grupo Clean/Padrao Ambiental à sua empresa, por isso nosso sistema efetuou a suspensão automática das coletas não gerando mais Ordem de Serviço. <br/>";
				$msg .="Regularize seus débitos e reative o seu contrato. <br /><br />";

				$msg .= "Cliente : " . $cliente['nome'] . "<br />";
				$msg .= "Contrato Numero : " . $contrato['id'] . "<br /><br />";


				$msg .= "Entretanto, na eventualidade da nota fiscal já estar quitada, favor desconsiderar esta cobrança enviando o comprovante do pagamento neste mesmo e-mail, ou entrar em contato no telefone 3104-2992/ 3104-2993 <br /><br />";

				$msg .= "Após 60 dias de vencido o(s) título(s) será(o) encaminhado(s) automaticamente ao Cartório/Protesto.<br /><br />";

				$msg .= "Mensagem enviada automaticamente pelo sistema!<br /><br />";
				$msg .=  "</font>";

				//$cliente['nome']='welllington';
				//$cliente['email_financeiro']='wpcsistema@gmail.com';

				$atendimento='atendimento@cleanambiental.com.br ';
				enviaEmail($assunto,$msg,$atendimento,SITENOME,$cliente['email_financeiro'], $cliente['nome']);

				$contador++;	
	 
	endforeach;
}

if($contador<>0){
	$_SESSION['retorna'] = '<div class="alert alert-success">Suas ' .$contador. ' mensagens foram enviadas com sucesso !</div>';
		
}else{
		
	$_SESSION['retorna'] = '<div class="alert alert-warning">Nenhuma Nenhuma!</div>'; 
 
}

header("Location: ".$_SESSION['url']);

?>


