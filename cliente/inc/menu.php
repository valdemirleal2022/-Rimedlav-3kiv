<head>
    <meta charset="iso-8859-1">
</head>
    
     
      <aside class="main-sidebar">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image">
              	<?php 
				if(!empty($_SESSION['autCliente']['logo'])){
				  echo '<img src="'.URL.'/uploads/clientes/'.$_SESSION['autCliente']['logo'].'" class="img-circle"/>';
				}else{
				  echo '<img src="'.URL.'/site/images/autor.png" class="img-circle"/>';
				}
				?>
            </div>
            <div class="pull-left info">
              <p>
               <?php 
				echo substr($_SESSION['autCliente']['contato'],0,18);
				$clienteId=$_SESSION['autCliente']['id'];
				 $email=$_SESSION['autCliente']['email'];
			   ?>
              </p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          
          <form action="painel.php?execute=suporte/cliente/clientes" method="post" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="pesquisa" class="form-control" placeholder="Localizar">
              <span class="input-group-btn">
                <button type="submit" name="nome" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>

          <ul class="sidebar-menu">
            <li class="header">Menu</li>
     
            <li class="treeview">
              
               <a href="#">
				<i class="fa fa-file-text"></i>
				</i> <span>Contratos</span> <i class="fa fa-angle-left pull-right"></i>
					 <?php 
					  $contratos= conta('cliente',"WHERE id AND email='$email'");
					  if($contratos>0){
						 echo '<small class="label pull-right bg-green">'. $contratos .'</small>';  
					  }
					?>
				</a>
				  <ul class="treeview-menu">
							<li>
					<a href="painel.php?execute=inc/home">
					 <span class="label label-primary pull-right"> <?php echo $contratos;?></span>
					<i class="fa fa-circle-o text-aqua"></i>
							Visualizar
					</a>
					</li>
				   </ul>
            </li>
            
             <li class="treeview">
              
               <a href="#">
				<i class="fa fa-file-text"></i>
				</i> <span>Ordem de Serviço</span> <i class="fa fa-angle-left pull-right"></i>
					 <?php 
					  $contratos= conta('contrato_ordem',"WHERE id AND id_cliente='$clienteId' AND status='13' AND nao_coletada<>'1'");
					  if($contratos>0){
						 echo '<small class="label pull-right bg-green">'. $contratos .'</small>';  
					  }
					?>
				</a>
				  <ul class="treeview-menu">
				   <?php $ordem = conta('contrato_ordem',"WHERE id_cliente='$clienteId' AND status='13' AND nao_coletada<>'1'");?>
					<li>
					<a href="painel.php?execute=suporte/ordem/ordem-emitidas">
					 <span class="label label-primary pull-right"> <?php echo $ordem;?></span>
					<i class="fa fa-circle-o text-aqua"></i>
							Visualizar
					</a>
					</li>
				   </ul>
            </li>
            
      
<!--    ATENDIMENTOS -->    
              
            <li class="treeview">
             
               <a href="#">
				<i class="fa fa-file-text"></i>
				</i> <span>Atendimento</span> <i class="fa fa-angle-left pull-right"></i>
					 <?php 
					  $atendimento= conta('pedido',"WHERE id AND id_cliente='$clienteId' AND cliente_solicitou='1'");
					  if($atendimento>0){
						 echo '<small class="label pull-right bg-green">'. $atendimento .'</small>';  
					  }
					?>
			   </a>
             
              <ul class="treeview-menu">
                <?php $pedido = conta('pedido',"WHERE id_cliente='$clienteId' AND cliente_solicitou='1'"); ?>
                <li><a href="painel.php?execute=suporte/atendimento/pedidos">
                 <span class="label label-primary pull-right"> <?php echo $pedido;?></span>
                <i class="fa fa-circle-o text-aqua"></i>
                    Visualizar
                </a>
                </li>
              </ul>
            </li>
            
 <!-- BOLETOS -->    
            
            <li class="treeview">
             
               <a href="#">
				<i class="fa fa-file-text"></i>
				</i> <span>Boletos</span> <i class="fa fa-angle-left pull-right"></i>
					 <?php 
					  $boletos= conta('receber',"WHERE id AND id_cliente='$clienteId'");
					  if($boletos>0){
						 echo '<small class="label pull-right bg-green">'. $boletos .'</small>';  
					  }
					?>
			   </a>
             
              <ul class="treeview-menu">
               <?php $boletos = conta('receber',"WHERE id_cliente='$clienteId'");?>
                <li>
                <a href="painel.php?execute=suporte/receber/boletos">
                 <span class="label label-primary pull-right"> <?php echo $boletos;?></span>
                <i class="fa fa-circle-o text-aqua"></i>
                	Visualizar
                </a>
                </li>
               </ul>
            </li>

            <!--<li class="treeview">
             
               <a href="#">
					<i class="fa fa-user"></i>
					</i> <span>Clientes</span> <i class="fa fa-angle-left pull-right"></i>
				  </a>
              
              <ul class="treeview-menu">
              <li><a href="painel.php?execute=suporte/cliente/cliente-editar"><i class="fa fa-circle-o"></i>Atualizar</a></li>

              </ul>
            </li>-->
 
             <li>
              <a href="logoff.php">
                 <i class="fa fa-sign-out"></i></i> <span>Sair</span>
              </a>
            </li>
           
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      