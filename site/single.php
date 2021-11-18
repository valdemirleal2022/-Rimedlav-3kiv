<?php 
	$noticiasurl = mysql_real_escape_string($url[1]);
	$readnoticias = read('noticias',"WHERE url = '$noticiasurl'");
	if(!$readnoticias){
		header('Location: '.URL.'/404');	
	}else{
		foreach($readnoticias as $not);
		top($not['id']);
		$categoria=$not['categoria'];
        $categoriaId=$not['id'];
	}
	
	echo '<meta name="description" content="'.$not['titulo'].' - '.$not['pre'].'" />';
?>


<div class="content container">

      <div class="page-wrapper">
                <header class="page-heading clearfix">
                     <h1 class="heading-title pull-left"><?php echo $not['titulo'];?></h1>
                    <div class="breadcrumbs pull-right">
                        <ul class="breadcrumbs-list">
                            <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                            <li><a href="<?php echo URL.'/categoria/'.$not['categoria']?>"><?php echo $not['categoria'];?></a><i class="fa fa-angle-right"></i></li>
                            <li class="current"><?php echo $not['titulo'];?></li>
                        </ul>
                    </div><!--//breadcrumbs-->
                </header> 
                
                   <div class="page-content">
                
                    <div class="row page-row">
                    
                        <div class="course-wrapper col-md-8 col-sm-7"> 
                        
                          <article class="course-item">
                           <div class="page-row">
                                <?php echo '<img class="img-responsive" src="'.URL.'/uploads/noticias/'.$not['fotopost'].'"  alt="Imagem de '.$not['titulo'].'" title="'.$not['titulo'].'">';?>
                           </div><!--page-row-->
                           <div class="page-row">
                           	<h3 class="title"><?php echo $not['pre'];?></h3>
                           </div><!--page-row-->
      					 <div class="page-row">
                 			<p> <?php echo stripslashes($not['descricao']);?></p>
				 <?php 
                 if(!empty($not['video_id'])){
                     echo '<h4 class="title">'.$not['video_titulo'].'</h4>';
                     echo '<object width="600" height="300"><param name="movie" value="http://www.youtube.com/v/' . $not['video_id'] .'"></param><embed src="http://www.youtube.com/v/' . $not['video_id'] .'" type="application/x-shockwave-flash" width="380" height="320"></embed>';
                     echo '</object>';
                 }
                 ?>   
   				</div><!--page-row-->
             
             
      
    <!--	<div class="row page-row" >   
  		 	<div id="fb-root"></div>
		 	 <script>
			  (function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
			   }
			   (document, 'script', 'facebook-jssdk'));
			</script>
     
             <div class="fb-like" data-href="<?php echo $_SERVER['REQUEST_URI']?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true">
             </div> 
         </div><!--row page-row-->
        
         <div class="row page-row" >  
              <div class="alert alert-info">
                   <strong>Tags : </strong> <?php echo $not['tags'];?>
              </div>  
          </div>
     
          
   		   <div class="row page-row" >              
      			<h3 class="title">Veja Também</h3>           
				<?php 
               	 $readArt = read('noticias',"WHERE id<>'$categoriaId' AND categoria='$categoria' LIMIT 0,6");               	 if($readArt){
                     foreach($readArt as $catnot):
					  	echo '<div class="col-md-4 col-sm-3 col-xs-12 text-center">';
						echo ' <div class="album-cover">';
					    echo '<a href="'.URL.'/single/'.$catnot['url'].'" title="'.$catnot['titulo'].'" class="cat">';
						echo '<img  class="img-responsive" src="'.URL.'/uploads/noticias/'.$catnot['fotopost'].'";
						alt="'.$catnot['titulo'].'" title="'.$catnot['titulo'].'">	';
					   	echo '<div class="desc">';
								echo '<h2>'.resumos($catnot['titulo'], $palavras = '50').'</h2>';
								echo '<p>'.resumos($catnot['pre'], $palavras = '200').'</p>';
							echo '</div>';
                        echo '</a>';
						echo '</div>';
					    echo '</div>';
                     endforeach;	
                	 }
					?>
               </div>   <!--//row page-row-->
                                              
                </div><!--//course-wrapper-->
                 <?php require("site/inc/sidebar-tab.php");?>
               </div><!--//page-row-->
           </div><!--//page-content-->
         </div><!--//page--> 
      </div><!--//content-->
 </div><!--//wrapper-->
        