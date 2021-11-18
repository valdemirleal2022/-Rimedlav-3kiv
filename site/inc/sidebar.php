 <div class="col-md-3">
         <section class="links">
               <h1 class="section-heading text-highlight"><span class="line">Top 10</span></h1>
                   <div class="section-content">
                	<?php 
						$readtop = read('noticias',"WHERE id ORDER BY visitas DESC LIMIT 10");
						if($readtop){
							foreach($readtop as $top):
								$topnumero++;
								echo '<p>';
									echo '<a href="'.URL.'/single/'.$top['url'].'" title="'.$top['titulo'].'">';
										echo '<i class="fa fa-caret-right"></i>';
											echo ''.resumos($top['titulo'], $palavras = '35').'';
									echo '</a>';
								echo '</p>';
							endforeach;	
						}
					?>
         </div><!--//section-content-->
     </section><!--//links-->
</div><!--//col-md-3-->
