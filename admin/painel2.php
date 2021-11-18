<?php


	//header('Cache-Control: no cache'); //no cache
    //session_cache_limiter('private_no_expire'); // works

	ob_start();
	session_start();

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
	
	if(!$_SESSION['autUser']){
		
		header('Location: index.php');	
		
	  }else{
		
		$userId = $_SESSION['autUser']['id'];
		$leitura = read('usuarios',"WHERE id = '$userId'");
		if($leitura){
			foreach($leitura as $autUser);
				if($autUser['nivel'] < '1'){
					header('Location: index.php');	
				}
		}else{
		   	header('Location: index.php');	
		}	
	}

?>

<!DOCTYPE html>

<html>
  <head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>CLEAN AMBIENTAL COLETA DE RESIDUOS  2 | Painel Administrativo</title>
    <?php require_once("inc/funcoes.php");?>
     
</head>

  
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
 		
       <?php require_once('inc/header.php'); ?>
		
       
        <?php require_once('inc/menu2.php'); ?>

  		 	<?php
				
                if(empty($_GET['execute'])){
					 // tela de abertura
                     require('suporte/agenda/agenda-lembrete.php');	
					
                }elseif(file_exists($_GET['execute'].'.php')){
                    require($_GET['execute'].'.php');
                }else{
                     require('suporte/404.php');		
                }
            ?>

     <?php require_once('inc/footer.php'); ?>

    </div><!-- ./wrapper -->

  </body>

<?php 
	ob_end_flush();
?>
</html>