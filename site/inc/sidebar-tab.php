<head>
    <meta charset="iso-8859-1">
</head>
    
<aside class="page-sidebar col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-1">     
  
 
               
           <section class="widget">
           	  <h3 class="title">Contato</h3>
              <?php 
                $empresa = mostra('empresa');
              ?>
              
              <p class="tel"><i class="fa fa-phone"></i> <?php  echo $empresa['telefone']; ?></p>
              <p class="email"><i class="fa fa-envelope"></i><a href="#"><?php  echo $empresa['email']; ?></a></p>
                
           </section><!--//widget-->  
        

</aside><!--/aside-->


  