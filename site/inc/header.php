<head>
    <meta charset="iso-8859-1">
 </head>
 
<header class="header">  
       
    <div class="top-bar">
            
        <div class="container">   
                           
                    <ul class="social-icons col-md-6 col-sm-6 col-xs-12 hidden-xs">
                        <!--<li><a href="#" ><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#" ><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#" ><i class="fa fa-youtube"></i></a></li>
                        <li><a href="#" ><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="#" ><i class="fa fa-google-plus"></i></a></li>         
                        <li class="row-end"><a href="#" ><i class="fa fa-rss"></i></a></li>-->             
                    </ul><!--//social-icons-->
                    
                     <?php 
                        if(isset($_POST['buscar'])){
           					$email = mysql_real_escape_string($_POST['email']);
 							//setcookie('usuario', $email);
//							$valor = $_COOKIE['usuario'];
                            header('Location: '.URL.'/cliente');
                        }else{
							//$email = $_COOKIE['usuario'];
						}
                    ?>
                    
                     <form name="search" action="" method="post" class="pull-right search-form" role="search">
                      	<div class="form-group">
                        <input type="email" name="email" value="<?php echo $email ?>" placeholder="Email do Cliente para Login" class="form-control">
                        </div>
                        <input type="submit" name="buscar" value="Login" class="btn btn-theme">
                    </form>
         
                </div>      
            </div><!--//to-bar-->
            
            <div class="header-main container">
                <h1 class="logo col-md-4 col-sm-4">
                    <a href="<?php setaurl();?>" title="Título | Home">
                	<img src="<?php setaurl();?>/site/images/header-logo.png" 
                    alt="Logo <?php echo SITENOME?>" title="<?php echo SITENOME?> | Home">
                </a>
                </h1><!--//logo-->           
                <div class="info col-md-8 col-sm-8">
                    <ul class="menu-top navbar-right hidden-xs">
                      <!--  <li class="divider"><a href="<?php setaurl();?>">Home</a></li>
                        <li class="divider"><a href="<?php echo URL;?>/faq">Duvidas</a></li>
                        <li><a href="<?php echo URL;?>/fale-conosco">Contato</a></li>-->
                    </ul><!--//menu-top-->
                    <br />
                    <div class="contact pull-right">
                        <p class="phone"><i class="fa fa-phone-square"></i>
			
						<?php 
                         $empresa = mostra('empresa');
                         echo $empresa['telefone'];
                        ?>
							
						 <p class="phone"><i class="fa fa-whatsapp"></i>
						<?php 
                         $empresa = mostra('empresa');
                         echo $empresa['celular'];
                        ?>		 
							 
                    	 </p> 
                         <p class="email"><i class="fa fa-envelope"></i><?php 
                         $empresa = mostra('empresa');
                         echo $empresa['email'];
                        ?></p>
                    </div><!--//contact-->
                    
            </div><!--//info-->
            
       </div><!--//header-main-->
       
 </header><!--//header-->