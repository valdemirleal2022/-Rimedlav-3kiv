<head>
    <meta charset="iso-8859-1">
</head>

       <header class="main-header">

        <!-- Logo -->
        <a href="../../index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>C</b>Ambiental</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Clean</b> Sistemas</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Clean</span>
          </a>
          
          
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <!-- Mensagens-->
              <li class="dropdown messages-menu">
              
              	<?php
						//$emailEnviados = mostra('email_contador',"WHERE id");
				?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success"><?php echo $emailEnviados['contador'];?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Mensagens</li>
                  <li>
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <!--<div class="pull-left">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                          </div>-->
                          <h4>
                            Data | Hora
                          </h4>
                          <p> 
                          <small><i class="fa fa-clock-o"></i><?php echo date('d/m/Y H:i:s',strtotime($emailEnviados['data']));?></small>
						</p>
                        </a>
                      </li>
                       
                    
                      
                      <li>
                        <a href="#">
                          <div class="pull-left">
<!--                            <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
-->                          </div>
                          <h4>
						    <?php echo $emailEnviados['contador'];?>
                            Mensagens Enviadas 
                          </h4>
                        <!--  <p>Why not buy a new awesome theme?</p>-->
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="painel.php?execute=suporte/interacao/manual">Manual do Usuário</a></li>
                </ul>
              </li>
              
              
              <!-- NOTIFICAÇÕES -->
              
              <?php
				

				 if($_SESSION['autUser']['nivel']==5){	//Gerencial 
					 
	              	 $refaturamento = conta('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='0'");
					 
					 $dispensa = conta('receber',"WHERE dispensa='1' AND dispensa_autorizacao<>'1'");
					 
					  $desconto = conta('receber',"WHERE desconto_autorizar='1' AND desconto_autorizacao='0'");
					 
					 $compras = conta('estoque_compras',"WHERE id AND status='Aguardando'");
					 
					 $contrato = conta('cadastro_visita',"WHERE  aprovacao_diretoria='3'");
					 
					  $rota = conta('contrato_rota',"WHERE id AND autorizacao_gerencial='0'");
					 
				 }

    			?>
						  
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning"><?php echo $refaturamento+$dispensa+$compras;?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Solicitações</li>
                  <li>
                     <ul class="menu">
				
					    <?php
						  if($_SESSION['autUser']['nivel']==5){	//Gerencial 
						?>
						 
						  <li>
							<a href="painel.php?execute=suporte/receber/receber-refaturamento">
							  <i class="fa fa-warning text-yellow"></i><?php echo $refaturamento;?> Refaturar
							</a>
						  </li>
						 
						   <li>
							<a href="painel.php?execute=suporte/receber/receber-dispensa">
							  <i class="fa fa-warning text-yellow"></i><?php echo $dispensa;?> Dispensar
							</a>
						  </li>
						 
						    <li>
							<a href="painel.php?execute=suporte/receber/receber-desconto">
							  <i class="fa fa-warning text-yellow"></i><?php echo $desconto;?> Desconto
							</a>
						  </li>
						 
						   <li>
							<a href="painel.php?execute=suporte/compras/autorizacoes">
							  <i class="fa fa-warning text-yellow"></i><?php echo $compras;?> Autorizações de Compra
							</a>
						  </li>
						 
						   <li>
							<a href="painel.php?execute=suporte/orcamento/autorizacoes-diretoria">
							  <i class="fa fa-warning text-yellow"></i><?php echo $contrato;?> Autorizações de Contrato
							</a>
						  </li>
						 
						  <li>
							<a href="painel.php?execute=suporte/cadastro/rotas-autorizacao">
							  <i class="fa fa-warning text-yellow"></i><?php echo $rota;?> Nova Rotas
							</a>
						  </li>
			
				 		<?php  
						}
						?>
						 
                    </ul>
                  </li>
                </ul>
              </li>
              
              <!-- Tarefas-->
              
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <?php
					    $data1 = date("Y-m-d");
					//    $agenda = conta('agenda',"WHERE retorno<='$data1' AND status='1'"); 
						//$agendaNegociacao = conta('agenda_negociacao',"WHERE retorno<='$data1' AND status='1'"); 
						$agendaTotal=$agenda+$agendaNegociacao ;
						if($agendaTotal<>0){
						  echo '<span class="label label-danger">'. $agendaTotal.'</span>';
					    }
    			  ?>
                </a>
                
                 
                     
                <ul class="dropdown-menu">
                  <li class="header">Contato Agendado</li>
                  <li>
                    <ul class="menu">
                      <li>
                         <a href="painel.php?execute=suporte/agenda/agenda">
                          <i class="fa fa-warning text-yellow"></i> <?php echo $agenda;?> Agenda(s)                        </a>
                      </li>
                      
                      <li>
                         <a href="painel.php?execute=suporte/agenda/agenda-negociacao-retorno">
                          <i class="fa fa-warning text-yellow"></i> <?php echo $agendaNegociacao;?> Negociações(s)                        </a>
                      </li>
                    </ul>
                  </li>
                 
                </ul>
              </li>
              
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php 
					
					 if(!empty($_SESSION['autUser']['fotoperfil'])){
						   echo '<img src="'.URL.'/uploads/usuarios/'.$_SESSION['autUser']		['fotoperfil'].'" class="user-image"/>';
						  }else{
							echo '<img src="'.URL.'/site/images/autor.png" class="user-image"/>';
					 }	
					
				//	 $usuarioId=$_SESSION['autUser']['id'];
				//	 $usuario= mostra('usuarios',"WHERE id AND id='$usuarioId'");
					
					
// 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro
// 5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado  
					
					 if($_SESSION['autUser']['nivel']==1){
						 $nivelUsuario='Operacional';
					 }
					if($_SESSION['autUser']['nivel']==2){
						 $nivelUsuario='Comercial';
					 }
					 if($_SESSION['autUser']['nivel']==3){
						 $nivelUsuario='Faturamento';
					 }
					 if($_SESSION['autUser']['nivel']==4){
						 $nivelUsuario='Financeiro';
					 }
					 if($_SESSION['autUser']['nivel']==5){
						 $nivelUsuario='Gerencial';
					 }
					 if($_SESSION['autUser']['nivel']==6){ // 6 - Manifesto 
						 $nivelUsuario='Manifesto';
					 }
					 if($_SESSION['autUser']['nivel']==7){ // 7 - DP/RH
						 $nivelUsuario='DP/RH';
					 }
					 if($_SESSION['autUser']['nivel']==8){ //8 - Vendas
						 $nivelUsuario='Vendas';
					 }
					 if($_SESSION['autUser']['nivel']==9){ // 9 - Manutenção / Almoxarifado  
						 $nivelUsuario='Manutenção';
					 }
					
				?>
                  <span class="hidden-xs"> <?php echo $nivelUsuario;?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <?php 

					  
					  if(!empty($_SESSION['autUser']['fotoperfil'])){
						   echo '<img src="'.URL.'/uploads/usuarios/'.$_SESSION['autUser']		['fotoperfil'].'" class="img-circle"/>';
						  }else{
							echo '<img src="'.URL.'/site/images/autor.png" class="img-circle"/>';
					 }	
			
					?>
                    <p>
                     <?php echo  $nivelUsuario;?>
                         <small>Painel Administrativo</small>
                    </p>
                  </li>

                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="painel.php?execute=suporte/interacao/manual-usuario" class="btn btn-default btn-flat">Manual</a>
                    </div>
                    <div class="pull-right">
                      <a href="painel.php?execute=suporte/interacao/interacoes-usuario" class="btn btn-default btn-flat">Interação</a>
                    </div>
                  </li>
                </ul>
              </li>
 
           
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      