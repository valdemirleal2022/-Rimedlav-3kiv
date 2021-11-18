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
          <h1>Tela Bloqueada</h1>
          <ol class="breadcrumb">
            <li><a href="vendas/painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Painel</a></li>
            <li class="active">Tela Bloqueada</li>
          </ol>
  </section>

   <section class="content">
   
       <div class="hold-transition lockscreen">
     
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
      <div class="lockscreen-logo">
       <a href="vendas/painel.php"><b>Clean</b>Ambiental</a>
      </div>
      <!-- User name -->
      
      <div class="lockscreen-name">    
       <?php 
		echo $_SESSION['autUser']['nome'];
		 ?></div>

      <!-- START LOCK SCREEN ITEM -->
      <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
          <?php 
				echo '<img src="'.URL.'/uploads/usuarios/'.$_SESSION['autUser']['fotoperfil'].'" class="img-circle"/>';
				?>
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials">
          <div class="input-group">
            <input type="password" class="form-control" placeholder="password">
            <div class="input-group-btn">
              <button type="submit" name="login" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
            </div>
          </div>
        </form><!-- /.lockscreen credentials -->

      </div><!-- /.lockscreen-item -->
     	<br>
     	<br><br>
     	
      
        </div>
      </div>
    </div><!-- /.center -->
     
     </div><!-- /.center -->
      
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

