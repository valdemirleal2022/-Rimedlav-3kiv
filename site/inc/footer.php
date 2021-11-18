<?php 
	if(isset($_POST['cadastra-email'])){
		$cad['email'] = mysql_real_escape_string($_POST['email']);
	
		if(in_array('',$cad)){
			echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
		}else{
			$cad['data']= date('Y/m/d H:i:s');
		create('email',$cad);
		unset($cad);
		header('Location: '.URL.setaurl());
	  }
	}
?><head>
    <meta charset="iso-8859-1">
</head>


<footer class="footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                <div class="footer-col col-md-3 col-sm-4 about">
                    <div class="footer-col-inner">
                      <h3>Menu</h3>
                        <ul>
                       <?php 
                        $readcat = read('categorias',"WHERE id ORDER BY id ASC");
                        if($readcat){
                            foreach($readcat as $cat):
								echo '<li><a href="'.URL.'/categoria/'.$cat['url'].'"><i class="fa fa-caret-right"></i>'.$cat['nome'].'</a></li>';
                            endforeach;	
                        }
						?>
                         </ul>
                    </div><!--//footer-col-inner-->
                </div><!--//foooter-col-->
                <div class="footer-col col-md-5 col-sm-8 newsletter">
                    <div class="footer-col-inner">
                       <!-- <h3>Assine nossa Newsletter</h3>
                        <p>Assine nossa newsletter e recebar noticias semanalmente.</p>
                        
                         <form  action=""  method="post" enctype="multipart/form-data" class="subscribe-form">
                            <div class="form-group">
                                <input type="email" class="form-control" name='email' placeholder="Seu email" />
                            </div>
                            <input class="btn btn-theme btn-subscribe" name="cadastra-email" type="submit" value="Inscrição">
                        </form>-->
                        
                    </div><!--//footer-col-inner-->
                </div><!--//foooter-col--> 
                
                <div class="footer-col col-md-4 col-sm-12 contact">
                    <div class="footer-col-inner">
                        <h3>Contato</h3>
                        <div class="row">
                            <p class="adr clearfix col-md-12 col-sm-4">
                                <i class="fa fa-map-marker pull-left"></i>        
                                <span class="adr-group pull-left">    
                              	 <?php 
                        			 $empresa = mostra('empresa');
                       				 echo '<span class="street-address">'.$empresa['nome'].'</span><br>';
									 echo '<span class="region">'.$empresa['endereco'].'</span><br>';
									 echo '<span class="postal-code">'.$empresa['cep'].'</span><br>';
									 echo '<span class="country-name">'.$empresa['cidade'].'</span><br>';
                        		 ?>
                                </span>
                            </p>
                            
                <p class="tel col-md-12 col-sm-4"><i class="fa fa-phone"></i><?php  echo $empresa['telefone']; ?></p>
				  <p class="tel col-md-12 col-sm-4"><i class="fa fa-whatsapp"></i><?php  echo $empresa['celular']; ?></p>
                <p class="tel col-md-12 col-sm-4"><i class="fa fa-envelope"></i><?php  echo $empresa['email']; ?></p>
           
                        </div> 
                    </div><!--//footer-col-inner-->            
                </div><!--//foooter-col-->   
                </div>   
            </div>        
        </div><!--//footer-content-->
        
         <div class="bottom-bar">
            <div class="container">
                <div class="row">
                    <small class="copyright col-md-6 col-sm-12 col-xs-12">Copyright @ 2017 
                    	 <?php echo $empresa['nome']; ?>
                         | By <a href="http://www.wpcsistema.com.br">WPC WebSite</a>
                    </small>
                     <ul class="social pull-right col-md-6 col-sm-12 col-xs-12">
                        <li><a href="<?php echo URL;?>/admin" target="_blank"><i class="fa fa-user"></i> Painel</a></li>
                        <li><a href="<?php echo URL;?>/consultor" target="_blank"><i class="fa fa-user"></i> Consultor</a></li>
						 <li><a href="<?php echo URL;?>/pos-venda" target="_blank"><i class="fa fa-user"></i>Pos-Venda</a></li>
                        <li><a href="<?php echo URL;?>/coletor" target="_blank"><i class="fa fa-user"></i> Coletor</a></li>
                    </ul><!--//social-->
                </div><!--//row-->
            </div><!--//container-->
        </div><!--//bottom-bar-->
        
    </footer><!--//footer-->
    
    
 