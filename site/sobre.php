<div class="content container">
      <div class="page-wrapper">
         <header class="page-heading clearfix">
             <h1 class="heading-title pull-left">Quem Somos</h1>
             <div class="breadcrumbs pull-right">
               <ul class="breadcrumbs-list">
                   <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                   <li><a href="<?php echo URL.'/sobre/'?>">Quem Somos</a><i class="fa fa-angle-right"></i></li>
                </ul>
              </div><!--//breadcrumbs-->
         </header> 
                
   <div class="page-content">
       <div class="row page-row">
                  <div class="terms-wrapper col-md-8 col-sm-7">
                  
                            <div class="page-row">
                                   
               		            <?php 
                				$empresa = mostra('empresa');
             				    ?>
                                <p>
                               <?php  echo $empresa['sobre']; ?></p>
                                </p>
                                              
                              </div>
                              
                        </div><!--//terms-wrapper col-md-8 col-sm-7-->
                      
                 <?php require("site/inc/sidebar-tab.php");?>
               </div><!--//page-row-->
           </div><!--//page-content-->
         </div><!--//page--> 
      </div><!--//content-->
 </div><!--//wrapper-->
     

        
     