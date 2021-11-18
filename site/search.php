<?php 
	$pesquisa = mysql_real_escape_string($url[1]);
	$pesquisa = str_replace('-',' ',$pesquisa);
?>

<div class="content container">
      <div class="page-wrapper">
         <header class="page-heading clearfix">
             <h1 class="heading-title pull-left">Pesquisa</h1>
             <div class="breadcrumbs pull-right">
               <ul class="breadcrumbs-list">
                   <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                   <li><a href="<?php echo URL.'/search/'?>">Pesquisa</a><i class="fa fa-angle-right"></i></li>
                </ul>
              </div><!--//breadcrumbs-->
         </header> 
                
   <div class="page-content">
   
       <div class="row page-row">
       
            <div class="terms-wrapper col-md-8 col-sm-7">
       
            <h2 class="title">Você pesquisou por: <strong> <?php echo strtoupper($pesquisa);?></strong></h2>

            	<?php 
					$readPesquisa = read('noticias',
					"WHERE status = '1' AND (titulo LIKE '%$pesquisa%' OR descricao LIKE '%$pesquisa%')
					ORDER BY data DESC");
					
					echo '<h1 class="title">Sua pesquisa retornou '.count($readPesquisa).' resultados</h1>';
					if(count($readPesquisa) <=0){
						echo '<h2 class="title">Desculpe, sua pesquisa não retornou nenhum resultado!</h2>';	
					}else{
						foreach($readPesquisa as $search):
							echo '<h3 class="title">';
								echo '<i class="fa fa-caret-right"></i> <a href="'.URL.'/single/'.$search['url'].'">'.$search['titulo'].'</a>';
							echo '</h3>';
						endforeach;	
					}
				?>	
                
                </div><!--//terms-wrapper col-md-8 col-sm-7-->
                        
  				<?php require("site/inc/sidebar-tab.php");?>
                 
              </div><!--//page-row-->
              
           </div><!--//page-content-->
           
         </div><!--//page--> 
         
      </div><!--//content-->
      
 </div><!--//wrapper-->      