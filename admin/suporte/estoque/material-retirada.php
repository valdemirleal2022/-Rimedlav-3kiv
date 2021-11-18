<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$data1 = converteData1();
	$data2 = converteData2();

 
	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-material-retirada-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		header( 'Location: ../admin/suporte/relatorio/relatorio-material-retirada-excel.php' );
	}


	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$materialRetirado=strip_tags(trim(mysql_real_escape_string($_POST['materialRetirado'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['materialRetirado']=$materialRetirado;
		
	}


	$total = conta('estoque_material_retirada',"WHERE id AND data>='$data1' 
					 AND data<='$data2'");
	$leitura = read('estoque_material_retirada',"WHERE id AND data>='$data1' 
					 AND data<='$data2' ORDER BY data DESC");

	if(!empty($materialRetirado)){
		
			$total = conta('estoque_material_retirada',"WHERE id_material='$materialRetirado' AND data>='$data1' 
					 AND data<='$data2'");
		
			$leitura = read('estoque_material_retirada',"WHERE id_material='$materialRetirado' AND data>='$data1' 
					 AND data<='$data2' ORDER BY data DESC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>Material Retirada</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Material</a>
			</li>
			<li class="active">Retirada</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					
				<div class="box-header">
				
				</div><!-- /.box-header -->

					
				 <div class="box-header">
             	  <div class="row">
            
					<div class="col-xs-12col-md-12 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
						 
						 <div class="form-group pull-left">
					  
				   
						 
					   <div class="form-group pull-left">		  
						<select name="materialRetirado" class="form-control input-sm">
							<option value="">Selecione o Material</option>
							<?php 
							$readContrato = read('estoque_material',"WHERE id ORDER BY nome ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($materialRetirado == $mae['id']){
										echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
									}else{
										echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
									}
									endforeach;	
								}
							?> 
							    </select>
					 	</div> 
                       
                            <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
    
                        
                           <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                           </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
                </div><!-- /row-->  
         </div><!-- /box-header-->   
    
		<div class="box-body table-responsive">

			<?php 

			if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Material</td>
					<td align="center">Tipo</td>
					<td align="center">Quant</td>
					<td align="center">Data</td>
					<td align="center">Observação</td>
					<td align="center">Veículo</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$tipoId = $mostra['id_tipo'];

				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				echo '<td>'.$material['nome'].'</td>';
				
				$materialTipo = mostra('estoque_material_tipo',"WHERE id ='$tipoId'");
				echo '<td>'.$materialTipo['nome'].'</td>';

				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="center">'.converteData($mostra['data']).'</td>';

				echo '<td align="left">'.substr($mostra['observacao'],0,25).'</td>';
				
				
				if(!empty( $mostra['id_manutencao'])){
					$manutencaoId = $mostra['id_manutencao'];
					$manutencao = mostra('veiculo_manutencao',"WHERE id ='$manutencaoId'");
					$veiculoId = $manutencao['id_veiculo'];
					$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
					echo '<td>'.$veiculo['placa'].'</td>';
				}else{
					echo '<td>-</td>';
				}
			 
				
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-retirada-editar&retiradaDeletar='.$mostra['id'].'">
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
				</div>
						<!-- /.box-footer-->

					</div>
					<!-- /.box-body table-responsive -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col-md-12 -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->

</div> <!-- /.content-wrapper -->