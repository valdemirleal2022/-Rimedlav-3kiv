<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			echo '<h1>Erro, você não tem permissão para acessar essa página!</h1>';	
			header('Location: painel.php');		
		}	
	}
?>
 
<div class="content-wrapper">

  <section class="content-header">
          <h1>Bloqueio</h1>
          <ol class="breadcrumb">
            <li><a href="vendas/painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Painel</a></li>
            <li class="active">Bloqueio</li>
          </ol>
  </section>

   <section class="content">
   
     <div class="error-page">
        <h2 class="headline text-yellow">Acesso Negado</h2>
        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Pagina Bloqueada.</h3>
          <p>
            Desculpe ! Você não tem permissão para acessar está página!  
           </p>
         </div><!-- /.error-content -->
      </div><!-- /.error-page -->
      
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

