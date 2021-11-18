	
 <?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];


?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Manual do Usuário</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Usuário</li>
           <li>Manual</li>
         </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

     	<div class="box-header">	 
         
          </div><!-- /box-header-->   
       

			<div class="box-body table-responsive">
			 <div class="faq-wrapper col-md-12 col-sm-7">                         
              <div class="panel-group" id="accordion">
                      
             <?php
				$num='0';
				$readFaq = read('manual_usuario',"WHERE id ORDER BY ordem ASC");
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
					
              
                        echo stripslashes($faq['resposta']).'</br>';
					
						if($faq['fotopost'] != '' && file_exists('../uploads/manuais/'.$faq['fotopost'])){
							echo '<img class="img-responsive" src="../uploads/manuais/'.$faq['fotopost'].'"/>';
						}
						echo '</div><!--//panel-body-->';
                        echo '</div><!--//panel-colapse-->';
                        echo '</div><!--//panel-->';
                     endforeach;
			      }
			  ?>   
              
            </div><!--//panel-group-->                                                
         </div><!--//faq-wrapper-->
	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
   
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->
