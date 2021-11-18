<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			echo '<h1>Erro, voc� n�o tem permiss�o para acessar essa p�gina!</h1>';	
			header('Location: painel.php');		
		}	
	}
?>
 
<div class="content-wrapper">

  <section class="content-header">
          <h1>404</h1>
          <ol class="breadcrumb">
            <li><a href="vendas/painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li class="active">404</li>
          </ol>
  </section>

   <section class="content">
   
     <div class="error-page">
        <h2 class="headline text-yellow">404</h2>
        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! P�gina n�o Encontrada</h3>
          <p>
            P�gina inexistente <a href="vendas/painel.php">Retorne ao Painel</a>
           </p>
         </div><!-- /.error-content -->
      </div><!-- /.error-page -->
      
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

