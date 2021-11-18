<?php 
	ob_start();
	session_start();
	require_once('../../config/crud.php');
	require_once('../../config/funcoes.php');
	if(!empty($_SESSION['autUser'])){
		header('Location: painel.php');
	 };
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
	<title>Área do Cliente</title>
 	<meta name="language" content="pt-br"> 
	<meta name="robots" content="INDEX,FOLLOW">
    <link rel="stylesheet" type="text/css" href="../../admin/css/style.css">
    <link href='http://fonts.googleapis.com/css?family=Terminal+Dosis' rel='stylesheet' type='text/css' />
    <link rel="icon" type="image/png" href="images/favico.png" />
</head>

<body>
	<div class="box">
    
    	<div class="header">
            <div class="logo">
                <img src="<?php setaurl();?>/site/images/header-logo.png" alt=""/>
            </div><!--/logo-->
             
            <div class="slogan">
                <h1>SUPORTE AO CLIENTE</h1>
            </div><!--/slogan--> 
                   
        </div><!--/header-->
        
       
        <div class="login-cliente">

        <h1>Compra Finalizada com Sucesso!</h1>
        
        <h1>C&oacute;digo da compra conosco: <?=$_GET['cod']?></h1>
        <h1>Transa&ccedil;&atilde;o PagSeguro: <?=$_GET['transaction_id']?></h1>
        
 
       <a href="<?php setaurl();?>"><button type="button" class="btn2" >Voltar ao Site</button></a>
       
    </div><!--/login-->
        
    </div><!--/box-->
    
</body>

<?php 
	ob_end_flush();
?>
</html>