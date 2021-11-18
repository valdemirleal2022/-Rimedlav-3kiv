<?php 
	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
	if(!empty($_SESSION['autCliente'])){
		header('Location: painel.php');
	 };
?>

<!DOCTYPE html>
<html>
  <head>
     <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Clean Ambiental | Suporte ao Cliente</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php require_once("../admin/inc/funcoes.php");?>
  </head>
  
  <body class="hold-transition login-page">
    <div class="login-box">
     <div class="register-logo">
         <a href="index.php">
        <img src="../site/images/header-logo.png" alt="logo" title="painel logo" />
         </a>
      </div>
       <div class="register-logo">
        <a href="index.php">Clean Ambiental | Suporte ao Cliente</a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Acesso aos boletos e coletas.</p>
        
        <?php 
                if(isset($_POST['login'])){
                    $email = mysql_real_escape_string($_POST['email']);
                    $senha = mysql_real_escape_string($_POST['senha']);
                    if(!$email || !email($email)){
                       echo '<div class="alert alert-warning">Desculpe o e-mail é invalido !</div><br />';
                    }else{
                        $autEmail = $email;
                        $autSenha = $senha;	
                        $leitura = read('cliente',"WHERE email = '$autEmail'");
                        if($leitura){
                            foreach($leitura as $autCliente);	
                            if($autSenha == $autCliente['senha']){
                                $_SESSION['autCliente'] = $autCliente;	
                                header('Location: '.$_SERVER['PHP_SELF'].'');
                            }else{
                                echo '<div class="alert alert-warning">Sua senha está errada!</div><br />';	
                            }
                        }else{
                           echo '<div class="alert alert-warning">O e-mail Não Cadastrado!</div>';
                        }
                    }
                }
            ?>
            
            <?php 
                if(isset($_POST['cadastro'])){
                    $email = mysql_real_escape_string($_POST['email']);
                    if(!$email || !email($email)){
                            echo '<div class="msgError">Desculpe o e-mail é invalido !</div><br />';
                      }else{
                        $autEmail = $email;
                        $leitura = read('cliente',"WHERE email = '$autEmail'");
                        if($leitura){
                            echo '<div class="msgError">E-mail já Cadastrado - Clique em Esqueci Minha Senha !</div><br />';	
	                      }else{
                               header('Location: cliente-novo.php');
                        }
                    }
                }
             ?>
             
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?php if($email) echo $email;?>">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="senha" class="form-control" placeholder="Senha"  value="<?php if($senha) echo $senha;?>">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
			 
			  
            <div class="col-xs-12">
              <input type="submit" value="Logar-se" name="login" class="btn btn-success btn-block btn-flat" />
            </div><!-- /.col -->
			  
			  <br>
			   <br>
			  
             <div class="col-xs-12">
               <a href="recuperar-senha.php" class="btn btn-primary btn-block btn-flat">Esqueceu sua senha ?</a>
            </div><!-- /.col -->
			   <br>
			   <br>
			  
			 <div class="col-xs-12">
               <a href="recuperar-email.php" class="btn btn-primary btn-block btn-flat">Esqueceu seu Email ?</a>
            </div><!-- /.col -->
			   <br>
			   <br>
			  
          </div>
        </form>
        
        <br>
        
        <!--<div class="social-auth-links text-center">
        <a href="#" class="btn btn-primary btn-block btn-flat">Ainda Não Sou Cadastrado</a><br><br>
        </div>-->


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

     
  </body>
  
<?php 
	ob_end_flush();
?>
</html>
