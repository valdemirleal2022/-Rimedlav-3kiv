<?php
	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="iso-8859-1">
	<title>Clean | Painel</title>
	<meta name="robots" content="INDEX,FOLLOW">
    <link rel="stylesheet" type="text/css" href="../admin/css/style.css">
    
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