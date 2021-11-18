<?php
	ob_start();
	session_start();

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
	
	if(!$_SESSION['autCliente']){
		header('Location: index');	
	  }else{
		
		// CONFIRMAR NIVEL DE ACESSO DA SENHA
		//$userId = $_SESSION['autCliente']['id'];
		//$leitura = read('cliente',"WHERE id = '$userId'");
		//header('Location '.URL.'');
	}
	
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">  <title>Clean Ambiental | Painel do Cliente</title>
     <?php require_once("../admin/inc/funcoes.php");?>
  </head>
  
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

       <?php require_once('inc/header.php'); ?>
       
        <?php require_once('inc/menu.php'); ?>
            <?php
                if(empty($_GET['execute'])){
                    require('inc/home.php');	
                }elseif(file_exists($_GET['execute'].'.php')){
                    require($_GET['execute'].'.php');
                }else{
                    echo '<div class="content">
                            <div class="msgError">
                                Erro 404
                            </div><!--/msg-->
                          </div>';		
                }
            ?>
      <?php require_once('inc/footer.php'); ?>

    </div><!-- ./wrapper -->

  </body>

<?php 
	ob_end_flush();
?>
</html>