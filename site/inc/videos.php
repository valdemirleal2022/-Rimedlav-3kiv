 <div class="col-md-6">
 
		<section class="video">
        
             <h1 class="section-heading text-highlight"><span class="line">Passo a Passo</span></h1>
             <div class="carousel-controls">
                  <a class="prev" href="#videos-carousel" data-slide="prev"><i class="fa fa-caret-left"></i></a>
                  <a class="next" href="#videos-carousel" data-slide="next"><i class="fa fa-caret-right"></i></a>
              </div><!--//carousel-controls-->
              
              <div class="section-content">    
              
                   <div id="videos-carousel" class="videos-carousel carousel slide">
                   
                       <div class="carousel-inner">
                       
                       
                        <?php
			    $active=0;
				$num=0;
				$readPosts = read('video',"WHERE id ORDER BY RAND() LIMIT 0,6");
				if($readPosts){
				  foreach($readPosts as $resPost):
						if($active==0){
							echo '<div class="item active">';
							$active=1;
						}else{
							echo '<div class="item">';
						}
						echo '<iframe class="video-iframe" src="//www.youtube.com/embed/' . $resPost['nome'] .'?rel=0&amp;wmode=transparent" frameborder="0" allowfullscreen=""></iframe>';
                          
						echo '<p>'.resumos($resPost['descricao'],$palavras = '140'). '</p>';
				        echo ' </div>';
			      endforeach;
			    }
			  ?>
                       
                    
                       </div><!--//carousel-inner-->
                       
                     </div><!--//videos-carousel-->                            
                      
                  </div><!--//section-content-->
         </section><!--//video-->
 </div>
                
