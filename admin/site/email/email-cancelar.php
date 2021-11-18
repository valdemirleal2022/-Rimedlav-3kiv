<?php 

		
		$id = $_GET['clienteId'];
		$read=read('email',"WHERE id = '$id'");
		if(!$read){
			header("Location: http://www.toyamadedetizadora.com.br/email-cancelado");
		}
		$cli=mostra('email',"WHERE id = '$id'");
		
		$cad['data']= date('Y/m/d H:i:s');
		$cad['status']= '2';
		update('email',$cad,"id = '$id'");	
 ?>

<div class='content'>


	<?php 

				$readDeleta = read('email',"WHERE id = ' $id'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
	 

					$assunto  = "Envio de email Cancelado pelo cliente";
					$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#08a57f'>";
					$msg .="<img src='http://www.toyamadedetizadora.com.br/site/images/header-logo.png'> <br /><br />";
					$msg .= "Email Cancelado pelo cliente<br /><br />";
	                $msg .= "Nome : " . $cli['nome'] . "<br />";
                    $msg .= "Email : " . $cli['email'] . "<br />";
                    $msg .= "Data : " . date('d/m/Y H:i') . "<br /><br />";
                    $msg .=  "</font>";
					
					$emailEnvio='contato@toyamadedetizadora.com.br';
					enviaEmail($assunto,$msg,$cli['email'],$cli['nome'],$emailEnvio,SITENOME);
					enviaEmail($assunto,$msg,$emailEnvio,SITENOME,$cli['email'],$cli['nome']);
					
					
					delete('email',"id = ' $id'");

					header("Location: http://www.toyamadedetizadora.com.br/email-cancelado");
				}
				
				

	
	 ?>
    
</div><!--/content-->
