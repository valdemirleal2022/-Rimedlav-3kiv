<div class="content container">
      <div class="page-wrapper">
         <header class="page-heading clearfix">
             <h1 class="heading-title pull-left">Perguntas Mais Frequentes</h1>
             <div class="breadcrumbs pull-right">
               <ul class="breadcrumbs-list">
                   <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                   <li><a href="<?php echo URL.'/faq/'?>">FAQ</a><i class="fa fa-angle-right"></i></li>
                </ul>
              </div><!--//breadcrumbs-->
         </header> 
                
   <div class="page-content">
       <div class="row page-row">
          
           <div class="faq-wrapper col-md-8 col-sm-7">                         
              <div class="panel-group" id="accordion">
                      
             <?php
				$num='0';
				$readFaq = read('faq',"WHERE id ORDER BY id ASC");
				if($readFaq){
					foreach($readFaq as $faq):
						$num++;
						echo '<div class="panel panel-default">';
                        echo '<div class="panel-heading">';
                        echo '<h4 class="panel-title">';
                        echo '<a data-toggle="collapse" data-parent="#accordion" class="collapsed" href="#'.$num.'">';
                        echo stripslashes($faq['pergunta']);
						echo '</a>';
                        echo '</h4>';
                        echo '</div><!--//pane-heading-->';
						echo '<div id="'.$num.'" class="panel-collapse collapse">';
                        echo '<div class="panel-body">';
                        echo stripslashes($faq['resposta']);;
						echo '</div><!--//panel-body-->';
                        echo '</div><!--//panel-colapse-->';
                        echo '</div><!--//panel-->';
                     endforeach;
			      }
			  ?>   
              
            </div><!--//panel-group-->                                                
         </div><!--//faq-wrapper-->
                      
                 <?php require("site/inc/sidebar-tab.php");?>
               </div><!--//page-row-->
           </div><!--//page-content-->
         </div><!--//page--> 
      </div><!--//content-->
 </div><!--//wrapper-->
     

        
     