<?php 
	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
	if(!empty($_SESSION['autRota'])){
		header('Location: painel.php');
	 };
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Clean Ambiental | Painel do Coletor</title>
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
        <a href="index.php">Coletor</a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Voc&ecirc; est&aacute; tentando acessar um conte&uacute;do exclusivo. Por favor, informe seus dados de acesso.</p>
        
			<?php 
                if(isset($_POST['login'])){
                    $email = mysql_real_escape_string($_POST['email']);
                    $senha = mysql_real_escape_string($_POST['senha']);
                    if(!$email || !email($email)){
                            echo '<div class="alert alert-warning">Desculpe o e-mail é invalido !</div><br />';	
                    }elseif(strlen($senha) < 5 || strlen($senha) > 10){
                            echo ' <div class="alert alert-warning">Desculpe a senha deve ter entre 5 a 10 caracteres!</div><br />';	
                    }else{
                        $autEmail = $email;
                        $autSenha = $senha;	
                        $leitura = read('contrato_rota',"WHERE email = '$autEmail'");
                        if($leitura){
                            foreach($leitura as $autRota);	
                            if($autSenha == $autRota['senha']){
                                $_SESSION['autRota'] = $autRota;	
								
								 // atualizada geolizacao
									 $usuarioId= $autRota['id'] ;
									// $cad['latitude']=$latitude ;
									// $cad['longitude']=$longitude;
									 //update('contrato_rota', $cad, "id = '$usuarioId'");
									
									 // registro login e geolizacao
									 $usu['id_rota']= $autRota['id'] ;
									// $usu['latitude']=$latitude ;
									 //$usu['longitude']=$longitude;
									 $usu['data']=date('Y/m/d H:i:s');
									 create('usuarios_login',$usu);
								
                                header('Location: '.$_SERVER['PHP_SELF'].'');
                            }else{
                                echo '<div class="alert alert-warning">Sua senha está errada !</div><br />';	
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
                            echo '<div class="alert alert-warning">Desculpe o e-mail é invalido !</div><br />';
                      }else{
                        $autEmail = $email;
                        $leitura = read('servico_tecnico',"WHERE email = '$autEmail'");
                        if($leitura){
                            echo '<div class="alert alert-warning">E-mail já Cadastrado - Clique em Esqueci Minha Senha !</div><br />';	
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
              <input type="submit" value="Logar-se" name="login" class="btn btn-primary btn-block btn-flat" />
            </div><!-- /.col -->
      <!--       <div class="col-xs-8">
               <a href="senha.php" class="btn btn-primary btn-block btn-flat">Esqueceu sua senha ?</a>
            </div><!-- /.col --> 
          </div>
        </form>
        
        <br>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

     
  </body>
  
<?php 
	ob_end_flush();
?>
</html>
