<head>
    <meta charset="iso-8859-1">
</head>
      
 
      <aside class="main-sidebar">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image">
              	<?php 
				if(!empty($_SESSION['autConsultor']['fotoperfil'])){
				  echo '<img src="'.URL.'/uploads/consultores/'.$_SESSION['autConsultor']['fotoperfil'].'" class="img-circle"/>';
				}else{
				  echo '<img src="'.URL.'/site/images/autor.png" class="img-circle"/>';
				}
				?>
            </div>
            <div class="pull-left info">
              <p>
               <?php $consultorId=$_SESSION['autConsultor']['id']; ?>
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
            <i class="fa fa-building"></i>
               </i> <span>Visitas</span> <i class="fa fa-angle-left pull-right"></i>
                <?php 
					$visita= conta('cadastro_visita',"WHERE id AND status='0' AND consultor='$consultorId'");
					if($visita<>'0'){
						echo '<small class="label pull-right bg-red">'. $visita .'</small>';  
					}
				?>
              </a>
              
           <ul class="treeview-menu">
                 <?php  $visita= conta('cadastro_visita',"WHERE id AND status='0' AND consultor='$consultorId'");?>
				<li>
				<a href="painel.php?execute=suporte/visita/visitas">
                 <span class="label label-primary pull-right"> <?php echo $visita;?></span>
                 <i class="fa fa-circle-o text-aqua"></i>
                 Visitas</a>
				</li>

				<?php $dataHoje = date("Y-m-d");
				$total = conta('cadastro_visita_negociacao',"WHERE interacao>='$dataHoje' AND consultor='$consultorId'");?>

				<li>
				<a href="painel.php?execute=suporte/visita/visita-negociacao">
                 <span class="label label-primary pull-right"> <?php echo $total;?></span>
                 <i class="fa fa-circle-o text-aqua"></i>
                 Contatos</a>
				</li>
				
				<?php $dataHoje = date("Y-m-d");
					$total = conta('cadastro_visita_negociacao',"WHERE retorno<='$dataHoje' AND consultor='$consultorId' AND status='1'");?>
				<li>
				<a href="painel.php?execute=suporte/visita/visita-negociacao-retorno">
                 <span class="label label-primary pull-right"> <?php echo $total;?></span>
                 <i class="fa fa-circle-o text-aqua"></i>
                 Retorno</a>
				</li>
                 
                  <?php 	
				$cancelados = conta('cadastro_visita',"WHERE id AND status='18' AND consultor='$consultorId'");
				?>
				
                <li>
                    <a href="painel.php?execute=suporte/visita/visita-cancelados">
                    <span class="label label-danger pull-right"> <?php echo $cancelados;?></span>
               		<i class="fa fa-circle-o"></i>
                    Canceladas
                    </a>
               </li>
                  
                                  
                  </ul>
    		</li>
           
             <li class="treeview">
              <a href="#">
               <i class="fa fa-files-o"></i>
               </i> <span>Orçamentos</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
              
              
				<?php 	
				$roteiro = conta('cadastro_visita',"WHERE id AND status='2' AND consultor='$consultorId'");
				?>
                
               <li>
               <a href="painel.php?execute=suporte/mapas/orcamento-roteiro">
                <span class="label label-danger pull-right"> <?php echo $roteiro;?></span>
               	<i class="fa fa-circle-o"></i>
                 Roteiro
               </a>
                </li>
                
				<?php 	
				$followup = conta('cadastro_visita',"WHERE id AND status='3' AND consultor='$consultorId'");
				?>
				
                <li>
                    <a href="painel.php?execute=suporte/orcamento/followup">
                    <span class="label label-danger pull-right"> <?php echo $followup;?></span>
               		<i class="fa fa-circle-o"></i>
                    Followup
                    </a>
               </li>
               
               <?php 	
				$orcamentos = conta('cadastro_visita',"WHERE id AND status<>'0' AND consultor='$consultorId'");
				?>
               
                <li>
                    <a href="painel.php?execute=suporte/orcamento/orcamentos">
                    <span class="label label-danger pull-right"> <?php echo $orcamentos;?></span>
               		<i class="fa fa-circle-o"></i>
                    Orçamentos
                    </a>
               </li>
				  
				 <?php 	
				$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND consultor='$consultorId'");
				?>
               
                <li>
                    <a href="painel.php?execute=suporte/orcamento/orcamento-aprovados">
                    <span class="label label-danger pull-right"> <?php echo $aprovados;?></span>
               		<i class="fa fa-circle-o"></i>
                    Aprovados
                    </a>
               </li>
               
               
               <?php 	
				$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND consultor='$consultorId'");
				?>
				
                <li>
                    <a href="painel.php?execute=suporte/orcamento/orcamento-cancelados">
                    <span class="label label-danger pull-right"> <?php echo $cancelados;?></span>
               		<i class="fa fa-circle-o"></i>
                    Cancelados
                    </a>
               </li>
                
              </ul>
            </li>
            
            
              <li class="treeview">
                   
                    <a href="#">
						<i class="fa fa-align-justify"></i>
						</i> <span>Contratos</span> <i class="fa fa-angle-left pull-right"></i>
						 <?php 
						 $suspensos= conta('contrato',"WHERE id AND status='6' AND consultor='$consultorId'");
				  
				  		$cancelamento= conta('contrato_cancelamento',"WHERE id AND status='Aguardando' AND id_consultor='$consultorId'");
				  		$suspensos=$suspensos+$cancelamento;
				  
						  if($suspensos>0){
							  echo '<small class="label pull-right bg-red">'. $suspensos .'</small>';  
						   }
						?>
					 </a>
       
                    <ul class="treeview-menu">

                        
       						<!-- 6 Contrato Suspensos-->
                        <?php $suspensos = conta('contrato',"WHERE id AND status='6' AND consultor='$consultorId'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-suspensos">
							 <span class="label  pull-right bg-orange"> <?php echo $suspensos;?></span>
							<i class="fa fa-circle-o"></i>
							Suspensos
							</a>
                        </li>
                        
                        
                       	<!-- 6 Contrato Suspensos-->
                        <?php $cancelamento = conta('contrato_cancelamento',"WHERE id AND status='Aguardando' AND id_consultor='$consultorId'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/solicitacao-cancelamento">
							 <span class="label  pull-right bg-orange"> <?php echo $cancelamento;?></span>
							<i class="fa fa-circle-o"></i>
							Cancelamentos
							</a>
                        </li>
						
						   
                        <!-- 7 	Contrato Rescindido-->
                        <?php $rescisao = conta('contrato',"WHERE id AND status='7' AND consultor='$consultorId'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-rescindidos">
							 <span class="label label-primary pull-right bg-orange"> <?php echo $rescisao;?></span>
							<i class="fa fa-circle-o"></i>
							Rescindidos
							</a>
                        </li>
						
						<!-- 7 	Contrato adtivos-->
                        <?php $aditivo = conta('contrato_aditivo',"WHERE id AND consultor='$consultorId' AND solicitacao_consultor='1'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-aditivo-autorizados">
							 <span class="label label-primary pull-right bg-orange"> <?php echo $aditivo;?></span>
							<i class="fa fa-circle-o"></i>
							Aditivos
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

      