<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$total = conta('estoque_compras',"WHERE status='Aguardando'");
	$leitura = read('estoque_compras',"WHERE status='Aguardando' ORDER BY data_solicitacao DESC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>Compras - Autorização de Compra</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Compras</a>
			</li>
			<li class="active">Autorizar</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					
			

					 <div class="box-body table-responsive">
					   <div class="box-body table-responsive data-spy='scroll'">
						  <div class="col-md-12 scrool"> 

							<?php 

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
								echo '<td align="right">'.$mostra['status'].'</td>';

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
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->