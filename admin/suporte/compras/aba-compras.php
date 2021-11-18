<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

//	if(!isset($_SESSION['aba'])){
//		$_SESSION['aba']='1';
//	}

?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>Compras</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Estoque</a>
			</li>
			<li class="active">Comprados</li>
		</ol>
	</section>

<section class="content">
	
 <div class="row">
   <div class="col-md-12">
	<div class="box box-default">
					
					
	  <div id="abas">

		<div class="nav-tabs-custom">

			<ul class="nav nav-tabs">
				
				<li class="<?php echo ($_SESSION['aba']=='1' ? " active " : " " );?>"><a href="#aba-1" data-toggle="tab">Solicitações</a>
				</li>
				
				<li class="<?php echo ($_SESSION['aba']=='2' ? " active " : " " );?>"><a href="#aba-2" data-toggle="tab">Cotações</a>
				</li>
				
				<li class="<?php echo ($_SESSION['aba']=='3' ? " active " : " " );?>"><a href="#aba-3" data-toggle="tab">Autorizações</a>
				</li>
				
				<li class="<?php echo ($_SESSION['aba']=='4' ? " active " : " " );?>"><a href="#aba-4" data-toggle="tab">Autorizados</a>
				</li>
			
				<li class="<?php echo ($_SESSION['aba']=='5' ? " active " : " " );?>"><a href="#aba-5" data-toggle="tab">Comprados</a>
				</li>
			 
				<li class="<?php echo ($_SESSION['aba']=='6' ? " active " : " " );?>"><a href="#aba-6" data-toggle="tab">Recebidos</a>
				</li>
				
			</ul>

	
	<!-- /..ABAS-->
	<div class="tab-content"> 
				
		<!-- /.Solicitações -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='1' ? " active " : " " );?>" id="aba-1">
			
				 <div class="box-header with-border">
					 <h3 class="box-title">Solicitações</h3>
				 </div><!-- /.box-header -->

				 <div class="box-header">
					<a href="painel.php?execute=suporte/compras/solicitacao-editar" class="btnnovo">
						 <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
							 Nova Solicitação
					 </a>
				 </div><!-- /.box-header -->


				 <div class="box-body table-responsive">
				   <div class="box-body table-responsive data-spy='scroll'">
					 <div class="col-md-12 scrool"> 

						<?php 
						 
						$total = conta('estoque_compras',"WHERE id AND status='1'");
						$leitura = read('estoque_compras',"WHERE id AND status='1' ORDER BY data_solicitacao DESC, id DESC");

						if($leitura){
							echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Solicitação</td>
								<td align="center">Tipo</td>
								<td align="center">Solicitante</td>
								<td align="center">Itens</td>
								<td align="center">Status</td>
								<td align="center" colspan="4">Gerenciar</td>
							</tr>';
						foreach($leitura as $mostra):

						echo '<tr>';

							echo '<td align="center">'.$mostra['id'].'</td>';
							echo '<td align="center">'.converteData($mostra['data_solicitacao']).'</td>';

							$materialId = $mostra['id_material'];
							$material = mostra('estoque_material_tipo',"WHERE id ='$materialId'");
							echo '<td>'.$material['nome'].'</td>';

							echo '<td align="right">'.$mostra['solicitante'].'</td>';	

							$solicitacaoId = $mostra['id'];
							$item = conta('estoque_compras_material',"WHERE id_compras ='$solicitacaoId'");
							echo '<td align="right">'.$item.'</td>';	
							
							$statusId = $mostra['status'];
							$status = mostra('estoque_compra_status',"WHERE id ='$statusId'");
							echo '<td>'.$status['nome'].'</td>';
						

							echo '<td align="center">
									<a href="painel.php?execute=suporte/compras/solicitacao-editar&solicitacaoEditar='.$mostra['id'].'">
										<img src="ico/editar.png" alt="Editar" title="Editar" />
									</a>
								</td>';
					
							echo '<td align="center">
									<a href="painel.php?execute=suporte/compras/solicitacao-editar&solicitacaoDeletar='.$mostra['id'].'">
										<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
									</a>
								</td>';
							
					 
						echo '</tr>';

					endforeach;

					echo '<tfoot>';
							echo '<tr>';
								echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
							echo '</tr>';
					echo '</tfoot>';

				echo '</table>';
			
				}
						 
				?>	
					<div class="box-footer">
						<?php echo $_SESSION['cadastro'];
						unset($_SESSION['cadastro']);
						?>
				    </div><!-- /.box-footer-->
						 
	      		</div><!--/col-md-12 scrool-->   
			  </div><!-- /.box-body table-responsive data-spy='scroll -->
 	  	   </div><!-- /.box-body table-responsive -->
			
		</div><!--tab-pane-1-->
  			
			
<!-- /.Cotações -->
	  <div class="tab-pane <?php echo ($_SESSION['aba']=='2' ? " active " : " " );?>" id="aba-2">
			
			 <div class="box-header with-border">
                 <h3 class="box-title">Cotações</h3>
             </div><!-- /.box-header -->
			
			 <div class="box-body table-responsive">
       		   <div class="box-body table-responsive data-spy='scroll'">
     	 		 <div class="col-md-12 scrool"> 

				 <?php 
		
					$total = conta('estoque_compras',"WHERE status='2'");
					$leitura = read('estoque_compras',"WHERE status='2' ORDER BY data_solicitacao DESC");

					if($leitura){
						echo '<table class="table table-hover">	
							<tr class="set">
							<td align="center">Id</td>
							<td align="center">Solicitação</td>
							<td align="center">Tipo</td>
							<td align="center">Solicitante</td>
							<td align="center">Itens</td>
							<td align="center">Forn 1</td>
							<td align="center">Forn 2</td>
							<td align="center">Forn 3</td>
							<td align="center">Autorização</td>
							<td align="center">Status</td>
							<td align="center" colspan="4">Gerenciar</td>
						</tr>';
					foreach($leitura as $mostra):

					echo '<tr>';

						echo '<td align="center">'.$mostra['id'].'</td>';
						echo '<td align="center">'.converteData($mostra['data_solicitacao']).'</td>';

						$materialId = $mostra['id_material'];
						$material = mostra('estoque_material_tipo',"WHERE id ='$materialId'");
						echo '<td>'.$material['nome'].'</td>';

						echo '<td align="right">'.$mostra['solicitante'].'</td>';	

						$solicitacaoId = $mostra['id'];
						$item = conta('estoque_compras_material',"WHERE id_compras ='$solicitacaoId'");
						echo '<td align="right">'.$item.'</td>';
					
						$cotacaoId = $mostra['id'];
						
						$leituraCotacao = read('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId' 
						ORDER BY id ASC");

						$totalGeral1=0;
						$totalGeral2=0;
						$totalGeral3=0;

						if($leituraCotacao){
							foreach($leituraCotacao as $mostraCotacao):
									$total1=$mostraCotacao['quantidade']*$mostraCotacao['valor1'];
									$total2=$mostraCotacao['quantidade']*$mostraCotacao['valor2'];
									$total3=$mostraCotacao['quantidade']*$mostraCotacao['valor3'];

									$totalGeral1+=$total1;
									$totalGeral2+=$total2;
									$totalGeral3+=$total3;
							
							 endforeach;
							
						}
						
						echo '<td align="right">'.converteValor($totalGeral1).'</td>';
						echo '<td align="right">'.converteValor($totalGeral2).'</td>';
						echo '<td align="right">'.converteValor($totalGeral3).'</td>';

						if($mostra['autorizar']=='0'){
							echo '<td align="right">-</td>';
						}elseif($mostra['autorizar']=='1' ||$mostra['compra_autorizacao']=='0'  ){
							echo '<td align="right">Solicitada</td>';	
						}elseif($mostra['autorizar']=='1' ||$mostra['compra_autorizacao']=='1'  ){
							echo '<td align="right">Autorizado</td>';
						}elseif($mostra['autorizar']=='1' ||$mostra['compra_autorizacao']=='2'  ){
							echo '<td align="right">Não Autorizado</td>';
						}else{
							echo '<td align="right">-</td>';
						}

						$statusId = $mostra['status'];
						$status = mostra('estoque_compra_status',"WHERE id ='$statusId'");
						echo '<td>'.$status['nome'].'</td>';
						
						
						echo '<td align="center">
							<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$mostra['id'].'">
								<img src="ico/editar.png" title="Editar" />
							</a>
							</td>';


						echo '<td align="center">
								<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoDeletar='.$mostra['id'].'">
									<img src="ico/excluir.png"  title="Excluir" />
								</a>
							</td>';

					 
					echo '</tr>';

				 endforeach;

				  echo '<tfoot>';
							echo '<tr>';
							echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
						echo '</tr>';
				  echo '</tfoot>';

				echo '</table>';

				}

				?>	
					<div class="box-footer">
						<?php echo $_SESSION['cadastro'];
						unset($_SESSION['cadastro']);
						?>
				    </div><!-- /.box-footer-->
	      
				   </div><!--/col-md-12 scrool-->   
			  </div><!-- /.box-body table-responsive data-spy='scroll -->
 	  	   </div><!-- /.box-body table-responsive -->
		  
		</div><!--tab-pane-1-->
			
<!-- /.Autorizações -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='3' ? " active " : " " );?>" id="aba-3">
			
			 <div class="box-header with-border">
                 <h3 class="box-title">Autorizações</h3>
             </div><!-- /.box-header -->
			
			   <div class="box-body table-responsive">
      			  <div class="box-body table-responsive data-spy='scroll'">
     				 <div class="col-md-12 scrool"> 

						<?php 
						 
						 $total = conta('estoque_compras',"WHERE status='3'");
						 
						 $leitura = read('estoque_compras',"WHERE status='3' ORDER BY data_solicitacao DESC");


						if($leitura){
							echo '<table class="table table-hover">	
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Solicitação</td>
								<td align="center">Tipo</td>
								<td align="center">Solicitante</td>
								<td align="center">Itens</td>
								<td align="center">Status</td>
								<td align="center" colspan="4">Gerenciar</td>
							</tr>';
						foreach($leitura as $mostra):

						echo '<tr>';

							echo '<td align="center">'.$mostra['id'].'</td>';
							echo '<td align="center">'.converteData($mostra['data_solicitacao']).'</td>';

							$materialId = $mostra['id_material'];
							$material = mostra('estoque_material_tipo',"WHERE id ='$materialId'");
							echo '<td>'.$material['nome'].'</td>';

							echo '<td align="right">'.$mostra['solicitante'].'</td>';	

							$solicitacaoId = $mostra['id'];
							$item = conta('estoque_compras_material',"WHERE id_compras ='$solicitacaoId'");
							echo '<td align="right">'.$item.'</td>';	
							
							$statusId = $mostra['status'];
							$status = mostra('estoque_compra_status',"WHERE id ='$statusId'");
							echo '<td>'.$status['nome'].'</td>';
							
						 
							  if($_SESSION['autUser']['nivel']==5){	//Gerencial 
 
								echo '<td align="center">
										<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$mostra['id'].'">
											<img src="ico/baixar.png" title="Autorizar" />
										</a>
									</td>';
 
								  
							  }else{
								 
								  echo '<td align="center">
										-
									</td>';
							  }

						echo '</tr>';

					 endforeach;

					  echo '<tfoot>';
								echo '<tr>';
								echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
							echo '</tr>';
					  echo '</tfoot>';

					echo '</table>';

					}

					?>	
						  
					<div class="box-footer">
						<?php echo $_SESSION['cadastro'];
							unset($_SESSION['cadastro']);
						?>
				    </div><!-- /.box-footer-->
						 
	      		</div><!--/col-md-12 scrool-->   
			  </div><!-- /.box-body table-responsive data-spy='scroll -->
				   
 	  	   </div><!-- /.box-body table-responsive -->
			
		</div><!--tab-pane-1-->
			
			
		<!-- /.Autorizados -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='4' ? " active " : " " );?>" id="aba-4">
			
			 <div class="box-header with-border">
                 <h3 class="box-title">Autorizados</h3>
             </div><!-- /.box-header --> 
			
			   <div class="box-body table-responsive">
      		     <div class="box-body table-responsive data-spy='scroll'">
     	 		   <div class="col-md-12 scrool"> 

							<?php 
					 
					 		$total = conta('estoque_compras',"WHERE status='4'");
							$leitura = read('estoque_compras',"WHERE  status='4' ORDER BY data_solicitacao DESC");

							if($leitura){
								echo '<table class="table table-hover">	
									<tr class="set">
									<td align="center">Id</td>
									<td align="center">Solicitação</td>
									<td align="center">Tipo</td>
									<td align="center">Solicitante</td>
									<td align="center">Itens</td>
									<td align="center">Status</td>
									<td align="center" colspan="4">Gerenciar</td>
								</tr>';
							foreach($leitura as $mostra):

							echo '<tr>';

								echo '<td align="center">'.$mostra['id'].'</td>';
								echo '<td align="center">'.converteData($mostra['data_solicitacao']).'</td>';

								$materialId = $mostra['id_material'];
								$material = mostra('estoque_material_tipo',"WHERE id ='$materialId'");
								echo '<td>'.$material['nome'].'</td>';

								echo '<td align="right">'.$mostra['solicitante'].'</td>';	

								$solicitacaoId = $mostra['id'];
								$item = conta('estoque_compras_material',"WHERE id_compras ='$solicitacaoId'");
								echo '<td align="right">'.$item.'</td>';	
								
								$statusId = $mostra['status'];
								$status = mostra('estoque_compra_status',"WHERE id ='$statusId'");
								echo '<td>'.$status['nome'].'</td>';
							

								echo '<td align="center">
										<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$mostra['id'].'">
											<img src="ico/baixar.png" title="Comprado" />
										</a>
									</td>';


								echo '<td align="center">
										<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoDeletar='.$mostra['id'].'">
											<img src="ico/excluir.png"  title="Excluir" />
										</a>
									</td>';


							echo '</tr>';

						 endforeach;

						  echo '<tfoot>';
									echo '<tr>';
									echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
								echo '</tr>';
						  echo '</tfoot>';

						echo '</table>';

						}

					?>	
					   
					  <div class="box-footer">
						<?php echo $_SESSION['cadastro'];
							unset($_SESSION['cadastro']);
						?>
				    </div><!-- /.box-footer-->
					   
	      		</div><!--/col-md-12 scrool-->   
			  </div><!-- /.box-body table-responsive data-spy='scroll -->
 	  	   </div><!-- /.box-body table-responsive -->
			
		</div><!--tab-pane-1-->
			
			
	<!-- /.Comprados -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='5' ? " active " : " " );?>" id="aba-5">
			
			<div class="box-header with-border">
                 <h3 class="box-title">Comprados</h3>
            </div><!-- /.box-header -->
			
			 <div class="box-body table-responsive">
       			<div class="box-body table-responsive data-spy='scroll'">
     	 		  <div class="col-md-12 scrool"> 
					  
						<?php 
					  
					  	$total = conta('estoque_compras',"WHERE status='5'");
						$leitura = read('estoque_compras',"WHERE status='5' ORDER BY data_solicitacao DESC");
					  
						if($leitura){
								echo '<table class="table table-hover">	
									<tr class="set">
									<td align="center">Id</td>
									<td align="center">Solicitação</td>
									<td align="center">Tipo</td>
									<td align="center">Solicitante</td>
									<td align="center">Itens</td>
									<td align="center">Status</td>
									<td align="center" colspan="4">Gerenciar</td>
								</tr>';
							foreach($leitura as $mostra):

							echo '<tr>';

								echo '<td align="center">'.$mostra['id'].'</td>';
								echo '<td align="center">'.converteData($mostra['data_solicitacao']).'</td>';

								$materialId = $mostra['id_material'];
								$material = mostra('estoque_material_tipo',"WHERE id ='$materialId'");
								echo '<td>'.$material['nome'].'</td>';

								echo '<td align="right">'.$mostra['solicitante'].'</td>';	

								$solicitacaoId = $mostra['id'];
								$item = conta('estoque_compras_material',"WHERE id_compras ='$solicitacaoId'");
								echo '<td align="right">'.$item.'</td>';	
							
								$statusId = $mostra['status'];
								$status = mostra('estoque_compra_status',"WHERE id ='$statusId'");
								echo '<td>'.$status['nome'].'</td>';

								echo '<td align="center">
										<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$mostra['id'].'">
											<img src="ico/baixar.png" title="Recebido" />
										</a>
									</td>';


								echo '<td align="center">
										<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoDeletar='.$mostra['id'].'">
											<img src="ico/excluir.png"  title="Excluir" />
										</a>
									</td>';


							echo '</tr>';

						 endforeach;

						  echo '<tfoot>';
									echo '<tr>';
									echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
								echo '</tr>';
						  echo '</tfoot>';

						echo '</table>';
						}

					 ?>	
					  <div class="box-footer">
						<?php echo $_SESSION['cadastro'];
						unset($_SESSION['cadastro']);
						?>
				    </div><!-- /.box-footer-->
					  
	      		</div><!--/col-md-12 scrool-->   
			  </div><!-- /.box-body table-responsive data-spy='scroll -->
 	  	   </div><!-- /.box-body table-responsive -->
			
		 </div><!--tab-pane-1-->
 
	<!-- /.recebidos -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='6' ? " active " : " " );?>" id="aba-6">
			
			<div class="box-header with-border">
                 <h3 class="box-title">Recebidos</h3>
            </div><!-- /.box-header -->
			
			 <div class="box-body table-responsive">
       			<div class="box-body table-responsive data-spy='scroll'">
     	 		  <div class="col-md-12 scrool"> 
					  
						<?php 
					  
					  	$total = conta('estoque_compras',"WHERE status='6'");
						$leitura = read('estoque_compras',"WHERE status='6' ORDER BY data_recebimento DESC");
					  
						if($leitura){
								echo '<table class="table table-hover">	
									<tr class="set">
									<td align="center">Id</td>
									<td align="center">Solicitação</td>
									<td align="center">Tipo</td>
									<td align="center">Solicitante</td>
									<td align="center">Itens</td>
									<td align="center">Status</td>
									<td align="center" colspan="4">Gerenciar</td>
								</tr>';
							foreach($leitura as $mostra):

							echo '<tr>';

								echo '<td align="center">'.$mostra['id'].'</td>';
								echo '<td align="center">'.converteData($mostra['data_solicitacao']).'</td>';

								$materialId = $mostra['id_material'];
								$material = mostra('estoque_material_tipo',"WHERE id ='$materialId'");
								echo '<td>'.$material['nome'].'</td>';

								echo '<td align="right">'.$mostra['solicitante'].'</td>';	

								$solicitacaoId = $mostra['id'];
								$item = conta('estoque_compras_material',"WHERE id_compras ='$solicitacaoId'");
								echo '<td align="right">'.$item.'</td>';	
						 
								$statusId = $mostra['status'];
								$status = mostra('estoque_compra_status',"WHERE id ='$statusId'");
								echo '<td>'.$status['nome'].'</td>';

								echo '<td align="center">
										<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoEditar='.$mostra['id'].'">
											<img src="ico/baixar.png" title="Recebido" />
										</a>
									</td>';


								echo '<td align="center">
										<a href="painel.php?execute=suporte/compras/cotacao-editar&cotacaoDeletar='.$mostra['id'].'">
											<img src="ico/excluir.png"  title="Excluir" />
										</a>
									</td>';


							echo '</tr>';

						 endforeach;

						  echo '<tfoot>';
									echo '<tr>';
									echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
								echo '</tr>';
						  echo '</tfoot>';

						echo '</table>';
						}

					 ?>	
					  <div class="box-footer">
						<?php echo $_SESSION['cadastro'];
						unset($_SESSION['cadastro']);
						?>
				    </div><!-- /.box-footer-->
					  
	      		</div><!--/col-md-12 scrool-->   
			  </div><!-- /.box-body table-responsive data-spy='scroll -->
 	  	   </div><!-- /.box-body table-responsive -->
			
		 </div><!--tab-pane-1-->
			
		</div><!-- /.tab-content-->
	   </div><!-- /.nav-tabs-custom-->
	 </div><!--/abas-->

    </div><!-- /.box -->
   </div><!-- /.col-md-12 -->
  </div><!-- /.row -->
	
 </section><!-- /.content -->
	
</div><!-- /.content-wrapper -->