 <?php $self = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
 $ativo='';

 
?>
 
        <nav class="main-nav" role="navigation">
        
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Clean Ambiental</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button><!--//nav-toggle-->
                </div><!--//navbar-header-->   
                         
              <div class="navbar-collapse collapse" id="navbar-collapse">
                 <ul class="nav navbar-nav">
                     <li class="<?php echo $ativo;?> nav-item">
                     <a href="<?php setaurl();?>" title="<?php echo SITENOME?>">Home</a>
                     </li>
                     
                     
                     <?php 
                        $readpaginas = read('paginas',"WHERE id ORDER BY nome ASC");
                        if($readpaginas){
                            foreach($readpaginas as $pg):
                                echo '<li class="nav-item"><a href="'.URL.'/paginas/'.$pg['url'].'">
                                          '.$pg['nome'].'</a></li>';
                            endforeach;	
                        }else;
                    ?>
              
                     
                    <?php 
                        $readcat = read('categorias',"WHERE id ORDER BY id ASC");
                        if($readcat){
                            foreach($readcat as $cat):
							   if(URL.'/categoria/'.$cat['url']==$self){
								  echo '<li class="active nav-item"><a href="'.URL.'/categoria/'.$cat['url'].'">
                                          '.$cat['nome'].'</a></li>';
							   }else{
								  echo '<li class="nav-item"><a href="'.URL.'/categoria/'.$cat['url'].'">
                                          '.$cat['nome'].'</a></li>';  
							   }
                            endforeach;	
                        }
                    ?>
					 
				   <li class="nav-item">
               <!--     <a href="<?php echo URL;?>/orcamento-coleta">Orçamento</a>-->
                   </li>
                 
                  <li class="nav-item">
         <!--           <a href="<?php echo URL;?>/fale-conosco">Contato</a>-->
                  </li>
                    
                 
                    
                    </ul><!--//nav-->
                    
                </div><!--//navabr-collapse-->
                
            </div><!--//container-->
            
        </nav><!--//main-nav-->