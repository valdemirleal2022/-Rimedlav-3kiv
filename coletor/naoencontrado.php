<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'],'1')){
			echo '<h1>Erro, você não tem permissão para acessar essa página!</h1>';	
			header('Location: painel.php');		
		}	
	}
?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Registro Não Encontrado</h1>
          <ol class="breadcrumb">
            <li><a href="vendas/painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Site</a></li>
            <li class="active">Não Encontrado</li>
          </ol>
  </section>

   <section class="content">
   
     <div class="error-page">
        <h2 class="headline text-yellow">Not Found</h2>
        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Registro Não Encontrado.</h3>
          <p>
            <a href="vendas/painel.php">Retorne ao Painel</a>
           </p>
         </div><!-- /.error-content -->
      </div><!-- /.error-page -->
      
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->
