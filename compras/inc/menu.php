<head>
    <meta charset="iso-8859-1">
</head>
     
      <aside class="main-sidebar">
      
        <section class="sidebar">
        
          <div class="user-panel">
            <div class="pull-left image">
              	<?php 
				if(!empty($_SESSION['autRota']['logo'])){
				  echo '<img src="'.URL.'/uploads/rotas/'.$_SESSION['autRota']['logo'].'" class="img-circle"/>';
				}else{
				  echo '<img src="'.URL.'/site/images/autor.png" class="img-circle"/>';
				}
				?>
            </div>
            <div class="pull-left info">
              <p>
               <?php 
				echo $_SESSION['autRota']['nome'];
			   ?>
              </p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          
          <form action="#" method="post" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="pesquisa" class="form-control" placeholder="Localizar">
              <span class="input-group-btn">
                <button type="submit" name="nome" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>

          <ul class="sidebar-menu">
            <li class="header">Menu</li>

             
           
          </ul>
          
        </section>  <!-- /.sidebar -->
        
      </aside>
