<div class="posts">
        <h1>Últimas Postagens</h1>   
            <ul>
            	<?php
					$readPosts = read('noticias',"WHERE id AND status = '1' ORDER BY data DESC LIMIT 0,8");
					if($readPosts){
						foreach($readPosts as $resPost):
							echo '<li>';
								echo '<a href="'.URL.'/single/'.$resPost['url'].'" title="'.$resPost['titulo'].'" target="_parent">';
									echo '<img src="'.URL.'/config/tim.php?src='.URL.'/uploads/noticias/'.$resPost['fotopost'].'&w=210&h=70&q=100&zc=1&a=c"
									      alt="'.$resPost['titulo'].'" title="'.$resPost['titulo'].'">	';
								echo '<h2>'.resumos($resPost['titulo'],$palavras = '150').'</h2>';
							echo '<h3>'.resumos($resPost['descricao'],$palavras = '160'). ' ... </h3>';
							echo '<p>Postado em : ' .date('d/m/Y',strtotime($resPost['data'])). '</p>';	
							echo '</a>';
							
							echo '</li>';
						endforeach;
					}else;
				 ?>
            </ul>
         

</div><!--/post-->

