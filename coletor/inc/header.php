<head>
    <meta charset="iso-8859-1">
</head>
     
       <header class="main-header">

        <!-- Logo -->
        <a href="../../index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>C</b>Clean</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>CLEAN</b> Ambiental</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            
            
              <!-- Mensagens-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">5</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Mensagens</li>
                  <li>
                    <ul class="menu">
                      <li>
                        <a href="#">
                        
                      </li>
                       
                    
                      
                      <li>
                       
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
              
              
              <!-- NOTIFICAÇÕES -->
	  
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Notificações</li>
                  <li>
                     <ul class="menu">
                     
                    </ul>
                  </li>
                </ul>
              </li>
              
              <!-- Tarefas-->
              
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                 
                
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Contato Agendado</li>
                  <li>
                   
                  </li>
                 
                </ul>
              </li>
              
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php 
				//echo '<img src="'.URL.'/uploads/rotas/'.$_SESSION['autRota']['fotoperfil'].'" class="user-image"/>';
				?>
                  <span class="hidden-xs"> <?php echo $_SESSION['autRota']['nome'];?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <?php 
				echo '<img src="'.URL.'/uploads/rotas/'.$_SESSION['autRota']['fotoperfil'].'" class="img-circle"/>';
				?>
                    <p>
                     <?php echo $_SESSION['autRota']['nome'].' -' . $_SESSION['autRota']['cargo'];?>
                         <small>Painel Rota</small>
                    </p>
                  </li>
                    <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="painel.php?execute=suporte/usuario/usuarios" class="btn btn-default btn-flat">Perfil</a>
                    </div>
                    <div class="pull-right">
                      <a href="painel.php?execute=suporte/usuario/equipe" class="btn btn-default btn-flat">Equipe</a>
                    </div>
                  </li>
                </ul>
              </li>
 
           
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      
      
       