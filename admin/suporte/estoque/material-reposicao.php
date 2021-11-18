<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];
	
?><head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Material Reposição</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Material</a>
			</li>
			<li class="active">Reposição</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">

					<div class="box-header">
					
					</div>
					<!-- /.box-header -->
					
				 <div class="box-header">
             	  <div class="row">
            
					<div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
                </div><!-- /row-->  
         </div><!-- /box-header-->   
    
		<div class="box-body table-responsive">

			<?php 

			$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
			$maximo = '30';
			$inicio = ($pag * $maximo) - $maximo;

			$total = conta('estoque_material_reposicao',"WHERE id");
			$leitura = read('estoque_material_reposicao',"WHERE id ORDER BY data DESC LIMIT $inicio,$maximo");
			if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">material</td>
					<td align="center">Data</td>
					<td align="center">Saldo Anterior</td>
					<td align="center">Quantidade</td>
					<td align="center">Saldo Atual</td>
					<td align="center">Valor Unitário</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				
				echo '<td>'.$material['nome'].'</td>';
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td align="right">'.$mostra['saldo_anterior'].'</td>';
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				$estoque=$mostra['saldo_anterior']+$mostra['quantidade'];
				echo '<td align="right">'.$estoque.'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_unitario']).'</td>';
	
				
				echo '<td align="center">
				<a href="painel.php?execute=suporte/estoque/material-reposicao-editar&reposicaoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-reposicao-editar&reposicaoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
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
		
		$link = 'painel.php?execute=suporte/estoque/material-reposicao&pag=';
	     pag('estoque_material_reposicao',"WHERE id ORDER BY data DESC", $maximo, $link, $pag);
		
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