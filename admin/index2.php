<?php 

	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');

	if(!empty($_SESSION['autUser'])){
		header('Location: painel.php');
	 };
 
/* Informa o nível dos erros que serão exibidos */
 error_reporting(E_ALL);
 
//* Habilita a exibição de erros */
 ini_set("display_errors", 1);
?>


<!DOCTYPE html>
<html>
  <head>
	  
    <head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CLEAN AMBIENTAL COLETA DE RESIDUOS| Painel Administrativo</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>CLEAN AMBIENTAL | Painel Administrativo</title>
    <?php require_once("inc/funcoes.php");?>
  </head>
  
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="../index.php">Painel Administrativo
         <img src="../site/images/header-logo.png" alt="logo" title="painel logo" />
         </a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Você está tentando acessar um conteúdo exclusivo. Por favor, informe seus dados de acesso.</p>
        
         <?php 
			if(isset($_POST['login'])){
				
				$email = mysql_real_escape_string($_POST['email']);
				$senha = mysql_real_escape_string($_POST['senha']);
				$latitude = mysql_real_escape_string($_POST['latitude']);
				$longitude = mysql_real_escape_string($_POST['longitude']);
				
				if(!$email || !email($email)){
						echo '<div class="alert alert-warning">Desculpe o e-mail é invalido !</div><br />';	
				}elseif(strlen($senha) < 5 || strlen($senha) > 10){
				 		echo ' <div class="alert alert-warning">Desculpe a senha deve ter entre 5 a 10 caracteres!</div><br />';	
				}else{
					$autEmail = $email;
					$autSenha = $senha;	
					$leitura = read('usuarios',"WHERE email = '$autEmail'");
					if($leitura){
						foreach($leitura as $autUser);	
						if($autEmail == $autUser['email'] && $autSenha == $autUser['senha']){
							
							if($autUser['status'] == '1'){ // verificado status - ativo ou inativo
								
								if($autUser['nivel'] > '0'){ // verificado nivel - 1 a 9
									
									$_SESSION['autUser'] = $autUser; // criar usuario	

										if(!isset($latitude )){
											$latitude='';
											$longitude='';
										}
									
							
									
									  // atualizada geolizacao
									  $usuarioId= $autUser['id'] ;
									  $cad['latitude']=$latitude ;
									  $cad['longitude']=$longitude;
									
									  update('usuarios', $cad, "id = '$usuarioId'");
									
										print_r($cad);
									
									 // registro login e geolizacao
									 $usu['id_usuario']= $autUser['id'] ;
									$usu['latitude']=$latitude ;
									 $usu['longitude']=$longitude;
									 $usu['data']=date('Y/m/d H:i:s');
									// create('usuarios_login',$usu);
									
									 header('Location: '.$_SERVER['PHP_SELF'].'');
									
								  }else{
									echo '<div class="alert alert-warning">Seu nível está errado!
											</div><br />';
								}
						 }else{
							echo '<div class="alert alert-warning">Seu usuário está inativo !
											</div><br />';
							}	
						}else{
							echo '<div class="alert alert-warning">Sua senha está errada!
											</div><br />';	
						}
					}else{
						echo '<div class="alert alert-warning">O e-mail não cadastrado!</div>';
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
          
          <input name="latitude" id="latitude" type="hidden" />
          <input name="longitude" id="longitude" type="hidden" />
            	
          <div class="row">
            <div class="col-xs-4">
              <input type="submit" value="Logar-se" name="login" class="btn btn-primary btn-block btn-flat" />
            </div><!-- /.col -->
          </div>
        </form>
        
        <div class="social-auth-links text-center">
         <a href="senha.php">Esqueceu sua senha ???</a><br>
<!--        <a href="cliente/cliente-novo.php" class="text-center">Cadastre-se</a>
-->        </div>


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

     
  </body>
  
<?php 
	  
	ob_end_flush();
 require_once("inc/geolocalizacao.php");
?>
	
	
</html>

