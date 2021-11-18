<div class="col-md-3">
    <section class="events">
        <h1 class="section-heading text-highlight"><span class="line">Eventos</span></h1>
         <div class="section-content">
          <?php
				$readEventos = read('eventos',"WHERE id ORDER BY data DESC LIMIT 0,4");
				if($readEventos){
				  foreach($readEventos as $resEventos):
				  echo '<a href="'.URL.'/eventos/'.'">';
						echo '<div class="event-item">';
							echo '<p class="date-label">';
								echo '<span class="month">'.converteMes($resEventos['data']).'</span>';
								echo '<span class="date-number">'.converteDia($resEventos['data']).'</span>';
								echo '<span class="date-number">'.converteAno($resEventos['data']).'</span>';
							echo '</p>';
							echo '<div class="details">';
								echo '<h2 class="title">'.resumos($resEventos['titulo'],$palavras = '20').'</h2>';
								echo '<p class="time"><i class="fa fa-clock-o"></i>'.($resEventos['hora']).'</p>';
								echo '<p class="location"><i class="fa fa-map-marker"></i>'.$resEventos['local'].'</p>';
							echo '</div>';
						echo '</div>';
					echo '</a>';
				endforeach;
				}
			?>
 

            </a>
           </div><!--//section-content-->

    </section><!--//events-->
</div><!--//col-md-3-->
                
 