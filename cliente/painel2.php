<?php
	ob_start();
	session_start();

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
	
	
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">  <title>Clean Ambiental | Painel do Cliente</title>
    
</head>

<body>

    <div class="box">
    
        <div class="mae"> 
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
             
        </div><!--/mãe-->   
    </div><!--/box-->
</body>
<?php 
	ob_end_flush();
?>
</html>