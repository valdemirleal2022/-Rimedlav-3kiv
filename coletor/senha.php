<?php 
	ob_start();
	session_start();
	require_once('../config/crud.php');
	require_once('../config/funcoes.php');
?>

<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
	<title>Recuperação de Senha</title>
 	<meta name="language" content="pt-br"> 
	<meta name="robots" content="INDEX,FOLLOW">
    <link rel="stylesheet" type="text/css" href="../admin_bak/css/style.css">
    <link href='http://fonts.googleapis.com/css?family=Terminal+Dosis' rel='stylesheet' type='text/css' />
    <link rel="icon" type="image/png" href="../psicologo/images/favico.png" />
</head>

<body>
	<div class="box">
    
    	<div class="header">
        	<div class="logo"> <a href="../index.php"><img src="../site/images/header-logo.png"  alt=""/></a>
            </div><!--/logo-->
            
            <div class="slogan">
            	<h1>SUPORTE AO PSICOLOGO</h1>
            </div><!--/slogan-->
            
        </div><!--/header-->
        
       
        <div class="login-cliente">
        
           <h1>ESQUECEU SUA SENHA ?</h1>
           <p>Digite seu e-mail no campo abaixo e depois clique no botão "Continuar". Sua senha será enviada para o e-mail informado.</p>
            
        
        <?php 
			if(isset($_POST['senha'])){
				$email = mysql_real_escape_string($_POST['email']);
				if(!$email || !email($email)){
					echo '<div class="alert alert-warning">Desculpe o e-mail é invalido !</div><br />';	
				  }else{
					$leitura = read('servico_tecnico',"WHERE email = '$email'");
					if($leitura){
						foreach($leitura as $tecnico);	
						$assunto  = "Recuperação de Senha : " . $tecnico['nome'];
			   			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
						$msg .= "Cliente : " . $tecnico['nome'] . "<br />";
						$msg .= "E-mail : " . $tecnico['email'] . "<br />";
						$msg .= "Senha : " . $tecnico['senha'] . "<br />";
						$msg .=  "</font>";
						// $assunto,$mensagem,$remetente,$nomeremetente,$destino,$nomedestino,$reply = NULL, $replyname = NULL
						enviaEmail($assunto,$msg,MAILUSER,SITENOME,$tecnico['email'], $tecnico['nome']);
						echo '<div class="alert alert-warning">Sua senha foi enviada com sucesso!</div>';
					  }else{
						echo '<div class="alert alert-warning">O e-mail Não Cadastrado!</div>';
					}
					
				}

			}
		
		?>
        

        <form name="senha" action="" method="post">
        	<legend>Recuperação de Senha</legend>
        	<label>
            	<span>E-mail:</span>
                <input type="text" class="radius" name="email" value="<?php if($email) echo $email;?>" />
            </label>
            <input type="submit" value="Continuar" name="senha" class="btn" />
            <a href="../psicologo/index.php"><button type="button" class="btn2" >Voltar</button></a>
        </form>
      
       
  
        
        </div><!--/login-->
        
    </div><!--/box-->
    
</body>

<?php 
	ob_end_flush();
?>
</html>