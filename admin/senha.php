<?php 
	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>CLEAN AMBIENTAL COLETA DE RESIDUOS | Painel Administrativo</title>
    <?php require_once("inc/funcoes.php");?>
  </head>
  
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="index.php">Painel Administrativo
         <img src="../site/images/header-logo.png" alt="logo" title="painel logo" />
         </a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Digite o email cadastro em nosso sistema no campo abaixo e depois clique no botão "Continuar". Sua senha será enviada automaticamente</p>
         
        
        <?php 
			if(isset($_POST['senha'])){
				$email = mysql_real_escape_string($_POST['email']);
				if(!$email || !email($email)){
					echo '<div class="alert alert-warning">Desculpe o e-mail é invalido !</div><br />';	
				  }else{
					$leitura = read('usuarios',"WHERE email = '$email'");
					if($leitura){
						foreach($leitura as $cliente);	
						$assunto  = "Recuperação de Senha : " . $cliente['nome'];
			   			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
						$msg .= "Cliente : " . $cliente['nome'] . "<br />";
						$msg .= "E-mail : " . $cliente['email'] . "<br />";
						$msg .= "Senha : " . $cliente['senha'] . "<br />";
						$msg .=  "</font>";
						// $assunto,$mensagem,$remetente,$nomeremetente,$destino,$nomedestino,$reply = NULL, $replyname = NULL
						enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'], $cliente['nome']);
						echo '<div class="alert alert-info">Sua senha foi enviada com sucesso!</div>';
					  }else{
						echo '<div class="alert alert-warning">O e-mail Não é cadastrado!</div>';
					}
					
				}

			}
		
		?>
        

        <form name="senha" action="" method="post">
        	<legend>Recuperação de Senha</legend>
          <div class="form-group has-feedback">
           <input type="email" name="email" class="form-control" placeholder="Email" value="<?php if($email) echo $email;?>">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
           <div class="row">
            
            <div class="col-xs-4">
                <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>

              <input type="submit" value="Continuar" name="senha" class="btn btn-primary btn-block btn-flat" />
            </div><!-- /.col -->
          </div>
        </form>
  
        
      <div class="row">

        <div class="social-auth-links text-center">
        <a href="cliente/cliente-novo.php" class="text-center">Cadastre-se</a>
        </div>


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

     
  </body>
  
<?php 
	ob_end_flush();
?>
</html>
