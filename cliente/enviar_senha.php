<?php 
	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
	if(!empty($_SESSION['autCliente'])){
		header('Location: painel.php');
	 };
?>

<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
	<title>Painel Administrativo</title>
 	<meta name="language" content="pt-br"> 
	<meta name="robots" content="INDEX,FOLLOW">
   <link rel="stylesheet" type="text/css" href="../admin/css/style.css">
    <link rel="icon" type="image/png" href="ico/favico.png" />
</head>

<body>
		<div class="box">
        
        	 <a href="<?php URL?>" title="Ver o site" class="back" target="_blank"><?php SITENOME?></a>
        
			<h1>Login de Usuário</h1>
            
			<h2>Acesso <span>restrito</span> a usu&aacute;rios cadastrados</h2>
            

			<div class="content">
            <?php 
						
							if(isset($_POST['enviar_senha'])){
								$email = mysql_real_escape_string($_POST['email']);
								if(!$email || !email($email)){
									echo '<div class="msgError">Desculpe o e-mail é invalido !</div><br />';	
								  }else{
									$leitura = read('cliente',"WHERE email = '$email'");
									if($leitura){
										foreach($leitura as $cliente);	
										$assunto  = "Recuperação de Senha : " . $cliente['nome'];
										$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
										$msg .= "Cliente : " . $cliente['nome'] . "<br />";
										$msg .= "E-mail : " . $cliente['email'] . "<br />";
										$msg .= "Senha : " . $cliente['senha'] . "<br />";
										$msg .=  "</font>";
										enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'], $cliente['nome']);
										echo '<div class="msgAlerta">Sua senha foi enviada com sucesso!</div>';
									  }else{
										echo '<div class="msgAlerta">O e-mail Não Cadastrado!</div>';
									}
								}
							}
						
                        ?>
            
				<div id="form_box" class="form_box">
   					<form class="senha " name="senha" action="" method="post">
						<h3>Recuperação de Senha</h3>
							<label>
                            <span>E-mail:</span>
							<input name="email" type="text" value="<?php if($email) echo $email;?>" size="40"  />
                            </label>
                            
						<div class="bottom">
							<input type="submit" value="Enviar Senha" name="enviar_senha" />
                            <a href="registro.php" class="linkform">Ainda não tem cadastro ? Cadastre-se</a>
							<div class="clear"></div>
						</div>
					</form>
				</div>
				<div class="clear"></div>
			</div>
		 
		</div>
        
         
		 
    </body>
</html>