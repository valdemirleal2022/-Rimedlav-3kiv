<div class="content container">
      <div class="page-wrapper">
         <header class="page-heading clearfix">
             <h1 class="heading-title pull-left">Contrato</h1>
             <div class="breadcrumbs pull-right">
               <ul class="breadcrumbs-list">
                   <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                   <li><a href="<?php echo URL.'/contrato/'?>">Contrato</a><i class="fa fa-angle-right"></i></li>
                </ul>
              </div><!--//breadcrumbs-->
         </header> 
                
   <div class="page-content">
       <div class="row page-row">
                  <div class="terms-wrapper col-md-8 col-sm-7">
                  
                            <div class="page-row">
                                <h4 class="text-highlight">Contrato</h4>
                                
               		            <?php 
                				$empresa = mostra('empresa');
             				    ?>
                                <p>
                               <?php  echo $empresa['contrato']; ?></p>
                                </p>
                                              
                              </div>
                              
                        </div><!--//page-row-->
                      
                 <?php require("site/inc/sidebar-tab.php");?>
               </div><!--//page-row-->
           </div><!--//page-content-->
         </div><!--//page--> 
      </div><!--//content-->
 </div><!--//wrapper-->
     

        
     