<head>
    <meta charset="iso-8859-1">
 </head>
 
   
      <div id="promo-slider" class="slider flexslider">
            <ul class="slides">
                
                	<?php 
		$readSlide = read('noticias',"WHERE id AND status = '1' AND destaques = '1' ORDER BY RAND()");
		if($readSlide){
			foreach($readSlide as $resSlide):
			    echo '<li>';
				echo '<a href="'.URL.'/single/'.$resSlide['url'].'" title="'.$resSlide['titulo'].'" target="_parent">';
				echo '<img class="img-responsive" src="'.URL.'/uploads/noticias/'.$resSlide['fotopost'].'" 
					  alt="'.$resSlide['titulo'].'" title="'.$resSlide['titulo'].'">';
				echo '<p class="flex-caption">';
				echo '<span class="main" >'.resumos($resSlide['titulo'],$palavras='80').'</span>';
				echo '<br />';
				echo '<span class="secondary clearfix" >'.$resSlide['pre'].'</span>';
				echo '<p />';
				echo '</a>';
				echo '</li>';
			endforeach;	
		}
	?>
                 
                </ul><!--//slides-->
     </div><!--//flexslider-->
            
           
        
  <section class="promo box box-dark">        
                <div class="col-md-8">
                <h1 class="section-heading">Bem Vindo</h1>
                    <p>Que bom que você nos encontrou! Estamos prontos para oferecer um serviço com a qualidade que seu estabelecimento merece. Navegue pelo nosso site e entenda melhor sobre a nossa coleta de resíduos. Aproveite e solicite um orçamento. Juntos por um Rio mais Clean. </p>   
                </div>  
               <!-- <div class="col-md-4">
                    <a class="btn btn-cta" href="<?php echo URL;?>/orcamento-coleta"><i class="fa fa-play-circle"></i>Solicitar Orçamento</a>  
                </div>-->
      </section><!--//promo--> 