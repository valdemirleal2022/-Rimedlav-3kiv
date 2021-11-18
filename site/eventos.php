<head>
    <meta charset="iso-8859-1">
 </head>
 
 
 <div class="content container">

      <div class="page-wrapper">
      
               <header class="page-heading clearfix">
                     <h1 class="heading-title pull-left">Eventos</h1>
                    <div class="breadcrumbs pull-right">
                        <ul class="breadcrumbs-list">
                            <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                            <li><a href="<?php echo URL.'/clientes/'?>">Clientes</a><i class="fa fa-angle-right"></i></li>
                            <li class="current"><?php echo $not['titulo'];?></li>
                        </ul>
                    </div><!--//breadcrumbs-->
                </header> 
                
       <div class="page-content">
       
            <div class="row page-row">
            
               <div class="events-wrapper col-md-8 col-sm-7">  
                        
                         <?php
				$readEventos = read('eventos',"WHERE id ORDER BY data DESC LIMIT 0,4");
				if($readEventos){
				  foreach($readEventos as $resEventos):
						echo '<article class="events-item page-row has-divider clearfix">';
						echo ' <div class="album-cover">';
							echo '<img class="img-responsive" src="'.URL.'/uploads/eventos/'.$resEventos['fotopost'].'";
							alt="'.$resEventos['titulo'].'" title="'.$resEventos['titulo'].'">	';
							echo '</a>';
						echo '</div>';
						echo '<div class="date-label-wrapper col-md-1 col-sm-2">';
							echo '<p class="date-label">';
								echo '<span class="month">'.converteMes($resEventos['data']).'</span>';
								echo '<span class="date-number">'.converteDia($resEventos['data']).'</span>';
								echo '<span class="date-number">'.converteAno($resEventos['data']).'</span>';
							echo '</p>';
						echo '</div><!--//date-label-wrapper-->';
						echo ' <div class="details col-md-11 col-sm-10">';
								echo '<h3 class="title">'.resumos($resEventos['titulo'],$palavras = '30').'</h3>';
								echo ' <p class="meta"><span class="time"><i class="fa fa-clock-o"></i>'.($resEventos['hora']).' </span>';
								echo ' <span class="location"><i class="fa fa-map-marker"></i>'.$resEventos['local'].'</span></p> ';
								echo '<p class="desc">'.$resEventos['descricao'].'</p>';
							echo '</div><!--//details-->';
						echo '</article><!--//events-item-->';
				endforeach;
				}
			?>                        
                   </div><!--//events-wrapper-->
                 <?php require("site/inc/sidebar-tab.php");?>
               </div><!--//row page-row-->
           </div><!--//page-content-->
         </div><!--//page--> 
      </div><!--//content-->
 </div><!--//wrapper-->
     