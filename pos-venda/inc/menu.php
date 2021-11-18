<head>
    <meta charset="iso-8859-1">
</head>
     
      <aside class="main-sidebar">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image">
              	<?php 
				if(!empty($_SESSION['autpos_venda']['fotoperfil'])){
				  echo '<img src="'.URL.'/uploads/pos_vendas/'.$_SESSION['autpos_venda']['fotoperfil'].'" class="img-circle"/>';
				}else{
				  echo '<img src="'.URL.'/site/images/autor.png" class="img-circle"/>';
				}
				?>
            </div>
            <div class="pull-left info">
              <p>
               <?php echo $_SESSION['autpos_venda']['nome']; 
				  $pos_vendaId=$_SESSION['autpos_venda']['id'];
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
						<i class="fa fa-align-justify"></i>
						</i> <span>Contratos</span> <i class="fa fa-angle-left pull-right"></i>
				  
						 <?php 
				  
							$suspensos= conta('contrato',"WHERE id AND status='6' AND pos_venda='$pos_vendaId'");
				  			$cancelamento= conta('contrato_cancelamento',"WHERE id AND status='Aguardando' AND pos_venda='$pos_vendaId'");
				  			$suspensos=$suspensos+$cancelamento;
				  
						  	if($suspensos>0){
							  echo '<small class="label pull-right bg-red">'. $suspensos .'</small>'; 
						    }
				  
						?>
					 </a>
       
                    <ul class="treeview-menu">
 
					   <!-- 6 Contrato Suspensos-->
                        <?php
						
						$data1 = converteData1();
						$data2 = converteData2();
						$suspensos = conta('contrato',"WHERE tipo='2' AND inicio>='$data1' 
											  AND inicio<='$data2' AND pos_venda='$pos_vendaId'");
						?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-iniciando">
							 <span class="label  pull-right bg-orange"> <?php echo $suspensos;?></span>
							<i class="fa fa-circle-o"></i>
							Iniciando
							</a>
                        </li>
						
						  <!-- 6 Contrato Suspensos-->
                        <?php $suspensos = conta('contrato',"WHERE id AND status='6' AND pos_venda='$pos_vendaId'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-suspensos">
							 <span class="label  pull-right bg-orange"> <?php echo $suspensos;?></span>
							<i class="fa fa-circle-o"></i>
							Suspensos
							</a>
                        </li>
                        
                        
                       		<!-- 6 Contrato Suspensos-->
                        <?php $cancelamento = conta('contrato_cancelamento',"WHERE id AND status='Aguardando' AND pos_venda='$pos_vendaId'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/solicitacao-cancelamento">
							 <span class="label  pull-right bg-orange"> <?php echo $cancelamento;?></span>
							<i class="fa fa-circle-o"></i>
							Cancelamentos
							</a>
                        </li>
						
						   <!-- 7 	Contrato Rescindido-->
                        <?php $rescisao = conta('contrato',"WHERE id AND status='7' AND pos_venda='$pos_vendaId'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-rescindidos">
							 <span class="label label-primary pull-right bg-orange"> <?php echo $rescisao;?></span>
							<i class="fa fa-circle-o"></i>
							Rescindidos
							</a>
                        </li>
						
						<!-- 7 	Contrato adtivos-->
                        <?php $aditivo = conta('contrato_aditivo',"WHERE id AND pos_venda='$pos_vendaId' AND solicitacao_pos_venda='1'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-aditivo-autorizados">
							 <span class="label label-primary pull-right bg-orange"> <?php echo $aditivo;?></span>
							<i class="fa fa-circle-o"></i>
							Aditivos
							</a>
                        </li>
						
						<!-- 7 	Contrato adtivos-->
                        <?php $atendimento = conta('contrato_atendimento_pos_venda',"WHERE id AND pos_venda='$pos_vendaId'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/atendimento/atendimentos">
							 <span class="label label-primary pull-right bg-orange"> <?php echo $atendimento;?></span>
							<i class="fa fa-circle-o"></i>
							Atendimento
							</a>
                        </li>
         
         
                    </ul>
                </li>


 		<li class="treeview">
                   
                    <a href="#">
						<i class="fa fa-money"></i>
						</i> <span>Vencidos</span> <i class="fa fa-angle-left pull-right"></i>
						
					 </a>
       
                    <ul class="treeview-menu">

       					<!-- 6 Contrato Suspensos-->
                      
                        <li>
                            <a href="painel.php?execute=suporte/receber/receber-vencidos">
							 <span class="label  pull-right bg-orange"></span>
							<i class="fa fa-circle-o"></i>
							Vencidos
							</a>
                        </li>
						
						 <li>
                            <a href="painel.php?execute=suporte/receber/receber-protestados">
							 <span class="label  pull-right bg-orange"></span>
							<i class="fa fa-circle-o"></i>
							Protesto
							</a>
                        </li>
                        
                        
						  <li>
                            <a href="painel.php?execute=suporte/receber/receber-serasa">
							 <span class="label  pull-right bg-orange"></span>
							<i class="fa fa-circle-o"></i>
							Serasa
							</a>
                        </li>
         
                    </ul>
                </li>


            
             <li>
              <a href="painel.php?execute=suporte/mapas/ondeestou">
                 <i class="fa fa-map-marker"></i></i> <span>Onde Estou ?</span>
              </a>
            </li>
            
             <li>
              <a href="painel.php?execute=suporte/agenda/agenda-lembrete">
                 <i class="fa fa-check"></i></i> <span>Post-it</span>
              </a>
            </li>
         
             <li>
              <a href="logoff.php">
                 <i class="fa fa-sign-out"></i></i> <span>Sair</span>
              </a>
            </li>
           
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      