 <div class="content container">
 
      <div class="page-wrapper">
            
          <header class="page-heading clearfix">
                     <h1 class="heading-title pull-left">Galeria</h1>
                    <div class="breadcrumbs pull-right">
                        <ul class="breadcrumbs-list">
                            <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                            <li><a href="<?php echo URL.'/galeria/'?>">Galeria</a><i class="fa fa-angle-right"></i></li                        ></ul>
                    </div><!--//breadcrumbs-->
          </header> 
                
          <div class="page-content">   
            
<!--             <div class="row page-row"> 
               <a class="prettyphoto col-md-3 col-sm-3 col-xs-6" rel="prettyPhoto[gallery]" title="Lorem ipsum dolor sit amet" href="site/images/gallery/gallery-1.jpg"><img class="img-responsive img-thumbnail" src="site/images/gallery/gallery-thumb-1.jpg" alt="" /></a>
             
       -->
              
     <?php 
		$readGaleria = read('galeria',"WHERE id ORDER BY data DESC"); 
		if($readGaleria){
			foreach($readGaleria as $galeria):
			
			    echo '<a class="prettyphoto col-md-3 col-sm-3 col-xs-6" rel="prettyPhoto[gallery]" title='.resumos($galeria['descricao'], $palavras = '200').' href="'.URL.'/uploads/galeria/'.$galeria['foto'].'"><img class="img-responsive img-thumbnail" src="'.URL.'/uploads/galeria/'.$galeria['foto'].'" alt="" />';
	echo '<p> <small>'.resumos($galeria['descricao'], $palavras = '200').'</small></p>';
 				echo '</a>';
			endforeach;	
			}
		?>
     			  </div><!--//page-row-->
                </div><!--//page-content--> 
            </div><!--//page--> 
        </div><!--//content-->
    </div><!--//wrapper-->
 