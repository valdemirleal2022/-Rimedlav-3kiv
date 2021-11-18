<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>


<?php 
	
$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$contratoTipo=$_SESSION['contratoTipo'];

$contador=0;

$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' ORDER BY emissao ASC");

if (!empty($contratoTipo)) {
	$leitura = read('receber',"WHERE emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo'  ORDER BY emissao ASC");
}

    if($leitura){
	  foreach($leitura as $receber):
		
		$receberId = $receber['id'];
		$contratoId = $receber['id_contrato'];
		$clienteId = $receber['id_cliente'];
		
		$cliente = mostra('cliente',"WHERE id = '$clienteId'");

		$enviarAviso='SIM';
		
		if(!empty($receber['imprimir'])){
			$enviarAviso ='NAO';
		}
		
 		if($receber['enviar_boleto_correio']=='1'){
 			$enviarAviso ='NAO';
 		}
		
 		if($cliente['nao_enviar_email']=='1'){
			$enviarAviso ='NAO';
		}
		
		if(is_numeric(substr($cliente['email_financeiro'],0,2))){
			$enviarAviso ='NAO';
		}
 
		
		if($enviarAviso=='SIM'){
					
			$linkBoleto ="https://www.cleansistemas.com.br/cliente/painel2.php?execute=boleto/emitir-boleto&boletoId=" . $receberId .' ';
	
			$linkNota =$receber['link'].' ';
			
			$linkExtrato =  "https://www.cleansistemas.com.br/cliente/painel2.php?execute=suporte/contrato/extrato-cliente-resumido&boletoId=" . $receberId .' ';
		 	
			$valor= $receber['valor'] + $receber['juros'] - $receber['desconto'];
			
			$assunto = "Clean - BOLETO, NF E EXTRATO" . $cliente['nome'];
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";
			
			$msg .= "Prezado(a) Cliente,  <br /><br />";
			$msg .= "O envio do faturamento é realizado automaticamente através do nosso sistema, no qual disponibilizamos um link com nota fiscal, boleto e extrato. Neste anexo estamos enviando um arquivo em PDF de fácil visualização do seu boleto e da sua Nota Fiscal devidamente autorizados.<br /><br />";

			$msg .= "Cliente : " . $cliente['nome'] . "<br />";
			$msg .= "Email : " . $cliente['email_financeiro'] . "<br />";
			$msg .= "Nosso Numero : " . $receber['id'] . "<br />";
			$msg .= "Data de Vencimento : " . converteData($receber['vencimento']) . "<br />";
			$msg .= "Valor R$  : " . converteValor($valor) . "<br /><br />";
	
			$msg .= "Mensagem enviada automaticamente pelo sistema !!! (A) <br /><br />";
			
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
			
			$msg .= "Informamos também  que disponibilizamos através do portal do atendimento ao cliente:<br /><br />";
			$msg .= "1.	www.cleanambiental.com.br  <br />";
	 		$msg .= "2.	No canto inferior direito clicar em LOGIN <br />";
			$msg .= "3.	Clicar em ESQUECEU SUA SENHA? <br />";
			$msg .= "4.	No passo seguinte informar o seu e-mail cadastrado em nossos sistemas e clicar em ENVIAR <br /> <br />";
			
			$msg .= "Automaticamente, será enviado para seu e-mail uma nova senha de acesso aos dados do seu cadastro, tais como: Nota fiscal e boleto.  <br />";
			$msg .= "Salientamos ainda, que o prazo para discordância da nota fiscal são de 48 horas a partir deste recebimento. <br /><br />";
			$msg .= "Obs. Após 60 dias de vencido o(s) título(s) será(o) encaminhado(s) automaticamente ao Cartório/Protesto. <br /><br />";
			
			$msg .= "Estamos também disponíveis no telefone 3104-2992  <br /><br />";
			
			$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
			$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
			$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";
	 			
			
			$msg .=  "</font>";
		
			$atendimento='atendimento@cleanambiental.com.br ';
			enviaEmail($assunto,$msg,$atendimento,SITENOME,$cliente['email_financeiro'], $cliente['nome']);
	

			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['usuario']	=  $_SESSION['autUser']['nome'];
			update('receber',$cad,"id = '$receberId'");
			
			// INTERAÇÃO
			$interacao='Link do boleto enviado por email n.'.$receberId;
			interacao($interacao,$contratoId);
			
			$contador++;
		
		}
		
		
	endforeach;
		
	$_SESSION['retorna'] = '<div class="alert alert-success">Suas ' .$contador. ' mensagens foram enviadas com sucesso !</div>';
		
}else{
		
	$_SESSION['retorna'] = '<div class="alert alert-danger">Nenhuma mensagem enviada!</div>'; 
}


header("Location: ".$_SESSION['url']);

?>

