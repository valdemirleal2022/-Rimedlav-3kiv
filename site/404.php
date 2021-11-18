<div class="content container">
  <div class="page-wrapper">
        <header class="page-heading clearfix">
        <h1 class="heading-title pull-left">404 - Opps ERRO! :</h1>
         <div class="breadcrumbs pull-right">
              <ul class="breadcrumbs-list">
                <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                <li><a href="<?php echo URL.'/404/'?>">Erro 404</a><i class="fa fa-angle-right"></i></li>
                <li class="current"><?php echo $not['titulo'];?></li>
              </ul>
            </div><!--//breadcrumbs-->
         </header> 
                
         <div class="page-content">
                
           <div class="row page-row">
                    
               <div class="course-wrapper col-md-8 col-sm-7"> 
    
    <img src="<?php setaurl();?>/site/images/404.png" alt="404" title="Desculpe a página que você procura não existe ou foi removida">
    
    <h2 class="titulo">Desculpe a p&aacute;gina que voc&ecirc; procura n&atilde;o existe ou foi removida !</h2>
            
  </div><!--//course-wrapper-->
                 <?php require("site/inc/sidebar-tab.php");?>
               </div><!--//page-row-->
           </div><!--//page-content-->
         </div><!--//page--> 
      </div><!--//content-->
 </div><!--//wrapper-->