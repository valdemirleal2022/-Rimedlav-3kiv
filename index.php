<?php 
	ob_start();
	session_start();
	require("config/config.php");
	require("config/crud.php");
	require("config/funcoes.php");
	contavisitas('600');
?>

<!doctype html>
<html>
<head>
    <title>Clean Ambiental Coleta de Resíduos</title>
    <!-- Meta -->
    <meta charset="iso-iso-utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name=keywords content="Clean Ambiental Coleta de Resíduos">
    <meta name=description content="Clean Ambiental Coleta de Resíduos">
    <meta name=author content="Copyright © 2017, WPC SISTEMA">
	<meta name="url" content="https://wwww.cleanambiental.com.br"> 
	<meta name="language" content="pt-br"> 
	<meta name="robots" content="INDEX,FOLLOW">
	<?php require("site/inc/func_head.php");?>
</head>

<body class="home-page">

    <div class="wrapper">
    
     	<?php require("site/inc/header.php");?> 
    
   		<?php require("site/inc/menu.php");?>
    
     	<div class="content container">
        
    	 	<?php pagehome();?>
            
       </div><!--//content-->
       
    </div><!--//wrapper-->
    
     <?php require("site/inc/footer.php");?> 
     

</body><!--//home-page-->

<?php 
ob_end_flush();
?>
</html>