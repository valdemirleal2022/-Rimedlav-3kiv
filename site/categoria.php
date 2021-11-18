<?php 
	$caturl = mysql_real_escape_string($url[1]);
	$readcat = read('categorias',"WHERE url = '$caturl'");
	if(!$readcat){
		header('Location: '.URL.'/404');	
	}else{
		foreach($readcat as $cat);
	}
?>

<div class="content container">

    <div class="page-wrapper">
    
       <header class="page-heading clearfix">
           <h1 class="heading-title pull-left"><?php echo $cat['nome'];?></h1>
		</header>
        
        <div class="page-content">   
         
           <div class="page-row">
        
        <?php 
				$readArt = read('noticias',"WHERE categoria = '$cat[nome]' AND status = '1' ORDER BY id ASC"); 
				if($readArt){
					foreach($readArt as $catnot):
					
						echo '<div class="col-md-3 col-sm-3 col-xs-12 text-center">';
					
						 echo ' <div class="album-cover">';

						   echo '<a href="'.URL.'/single/'.$catnot['url'].'" title="'.$catnot['titulo'].'" class="cat">';
							echo '<img class="img-responsive" src="'.URL.'/uploads/noticias/'.$catnot['fotopost'].'";
							alt="'.$catnot['titulo'].'" title="'.$catnot['titulo'].'">	';
							echo '</a>';
					
							echo '<div class="desc">';
								echo '<h1>'.resumos($catnot['titulo'], $palavras = '50').'</h1>';
								echo '<p>'.resumos($catnot['pre'], $palavras = '200').'</p>';
							echo '</div>';
					
						 echo ' </div>';
					
						echo ' </div>';
					endforeach;	
				
				}
		?>
     			 </div><!--//page-row-->
                    
                </div><!--//page-content-->
            </div><!--//page--> 
        </div><!--//content-->
    </div><!--//wrapper-->
    
 