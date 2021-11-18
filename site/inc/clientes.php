
<div class="col-md-12">

 <section class="awards">
 
   <h1 class="section-heading text-highlight"><span class="line">Clientes</span></h1>
   
    <div class="carousel-controls">
         <a class="prev" href="#awards-carousel" data-slide="prev"><i class="fa fa-caret-left"></i></a>
          <a class="next" href="#awards-carousel" data-slide="next"><i class="fa fa-caret-right"></i></a>
    </div><!--//carousel-controls-->

    <div id="awards-carousel" class="awards-carousel carousel slide">
    
    <div class="carousel-inner">
     
       
	<?php
		$readCliente = read('cliente',"WHERE id AND status = '1' ORDER BY id_sistema ASC, nome ASC");
		if($readCliente){
			
			$active=0;
			$num=0;
			foreach($readCliente as $cliente):
			    $num=$num+1;
				if($active==0){
					echo '<div class="item active">';
					echo '<ul class="logos">';
					$active=1;
				}
				if($active==2){
					echo '<div class="item">';
					echo '<ul class="logos">';
					$active=3;
				}
				echo '<li class="col-md-2 col-sm-2 col-xs-4">';
				if(!empty($cliente['logo'])){
					echo '<img class="img-responsive img-circle" src="'.URL.'/uploads/clientes/'.$cliente['logo'].'">';
				 }else{
					echo '<img class="img-responsive img-circle" src="'.URL.'/site/images/autor-logo.png">';
				}
				echo '<p>'.resumos(ucwords(strtolower($cliente['nome'])),$palavras = '22').'</p>';
				echo '</li>';
				
				if($num==6){
					echo '</ul>';
					echo '</div>';
					$active=2;
					$num=0;
				}
				
			endforeach;
			
			
		}
	?>
     
        
     <!--<div class="item">
      <ul class="logos">
        
      <?php
//		$readCliente = read('cliente',"WHERE id AND status = '1' ORDER BY id_sistema ASC, nome ASC LIMIT 6,6");
//		if($readCliente){
//			foreach($readCliente as $cliente):
//				echo '<li class="col-md-2 col-sm-2 col-xs-4">';
//				if(!empty($cliente['logo'])){
//					echo '<img class="img-responsive img-circle" src="'.URL.'/uploads/clientes/'.$cliente['logo'].'">';
//					}else{
//					  echo '<img class="img-responsive img-circle" src="'.URL.'/site/images/autor-logo.png">';
//					}
//				    echo '<p>'.resumos(ucwords(strtolower($cliente['nome'])),$palavras = '22').'</p>';
//			        echo ' </li>';
//		  endforeach;
//		}
 	?>
            
        </ul><!--//slides-->
  <!--    </div><!--//item----> 
      
  </div><!--//carousel-inner-->
 

  </div>
       
</section>

</div>


 
    
 