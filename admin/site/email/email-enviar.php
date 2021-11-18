<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'],'1')){
			echo '<h1>Erro, você não tem permissão para acessar essa página!</h1>';	
			header('Location: painel.php');		
		}	
	}
	
	$email_mkt = mostra('email_mkt',"WHERE id");
	if(!$reademail){
		header('Location: painel.php?execute=suporte/error');	
	}
	foreach($reademail as $email_mkt);
	
 ?>

<div class='content'>

<?php 
	
	 $leitura = read('email',"WHERE id ORDER BY data ASC LIMIT 200");
	 
		$contador=0;
		$contadorEmail=0;
		
		foreach($leitura as $email):
			$contador++;
			if( $contador ==10){
				$contador = 0;
				sleep(2);
			}

			
		$link1="http://www.toyamadedetizadora.com.br/orcamento-dedetizacao";
		$link2="http://www.toyamadedetizadora.com.br/admin/painel2.php?execute=suporte/email/email-cancelar&clienteId=".$id;
		$assunto = $email_mkt['titulo'];
		$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#444'>";
		$msg .="<img src='http://www.toyamadedetizadora.com.br/site/images/header-logo.png'> <br />";
		$msg .= stripslashes($email_mkt['descricao']).  "<br /><br />";
		$msg .= "<a href=" . $link1 . ">ORÇAMENTO GRÁTIS.</a><br /> ";
		$msg .= "<a href=" . $link2 . ">Cancele o recebimento de novos e-mails, clicando aqui.</a> ";
		$msg .=  "</font>";
		
		$emailEnvio='contato@toyamadedetizadora.com.br';
 		enviaEmail($assunto,$msg,$emailEnvio,SITENOME,$email['email'],$email['nome']);
 		$id = $email['id'];
		$cad['data']= date('Y/m/d H:i:s');
		$cad['status']='OK';
 		update('email',$cad,"id = '$id'");	
		unset($cad);
		
			
	 endforeach;		

	header('Location: painel.php?execute=suporte/email/emails');
	?>
      
</div><!--/content-->
