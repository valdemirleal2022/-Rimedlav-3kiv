<head>
    <meta charset="iso-8859-1">
 </head>
 
  
<section class="news">

 	<h1 class="section-heading text-highlight"><span class="line">Destaques</span></h1> 
  
    <div class="carousel-controls">
          <a class="prev" href="#news-carousel" data-slide="prev"><i class="fa fa-caret-left"></i></a>
           <a class="next" href="#news-carousel" data-slide="next"><i class="fa fa-caret-right"></i></a>
    </div><!--//carousel-controls--> 
    
    <div class="section-content clearfix">
    
       <div id="news-carousel" class="news-carousel carousel slide">
       
          <div class="carousel-inner">
          
<!--             <div class="item active"> -->  

            <?php
			    $active=0;
				$num=0;
				$readPosts = read('noticias',"WHERE id AND status = '1' AND destaques = '1' ORDER BY RAND() LIMIT 0,6");
				if($readPosts){
				  foreach($readPosts as $resPost):
				 		$num=$num+1;
						if($active==0){
							echo '<div class="item active">';
							$active=1;
						}
						if($active==2){
							echo '<div class="item">';
							$active=3;
						}
						echo '<div class="col-md-4 news-item">';
						
						echo '<a href="'.URL.'/single/'.$resPost['url'].'" title="'.$resPost['titulo'].'"
										 target="_parent">';
						echo '<img  class="img-responsive" src="'.URL.'/uploads/noticias/'.$resPost['fotopost'].'";
									alt="'.$resPost['titulo'].'" title="'.$resPost['titulo'].'">	';
						echo '<h2 class="title">'.resumos($resPost['titulo'],$palavras = '90').'</h2>';
						echo '<p>'.resumos($resPost['pre'],$palavras = '140'). '</p>';
					    echo '</a>';
				        echo ' </div>';
						if($num==3){
						   echo '</div>';
						  $active=2;
						  $num=0;
						}
			      endforeach;
			    }
			  ?>
       
        </div><!--//carousel-inner-->
     </div><!--//news-carousel-->  
    </div><!--//section-content-->   
 </section><!--//news-->
 