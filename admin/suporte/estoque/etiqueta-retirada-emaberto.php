<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = date( "Y/m/d" );
	$data2 = date( "Y/m/d" );

	$total = conta('estoque_etiqueta_retirada',"WHERE id AND status='Em aberto'");
	$leitura = read('estoque_etiqueta_retirada',"WHERE id AND status='Em aberto' ORDER BY data_entrega ASC");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-etiqueta-retirada-emaberto-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		header( 'Location: ../admin/suporte/relatorio/relatorio-etiqueta-retirada-emaberto-excel.php' );
	}


	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
		$total = conta('estoque_etiqueta_retirada',"WHERE id AND status='Em aberto' AND 	data_entrega>='$data1' AND data_entrega<='$data2'");
		
		$leitura = read('estoque_etiqueta_retirada',"WHERE id AND status='Em aberto' AND data_entrega>='$data1' AND data_entrega<='$data2' ORDER BY data_entrega ASC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
	
	<section class="content-header">
		
		<h1>Etiqueta Retirada (Em Aberto)</h1>
		
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Etiqueta</a>
			</li>
			<li class="active">Retirada</li>
		</ol>
		
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">

					
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

			
			if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Etiqueta</td>
					<td align="center">Nome</td>
					<td align="center">Quantidade</td>
					<td align="center">Solicitação</td>
					<td align="center">Entrega</td>
					<td align="center">Observação</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$etiquetaId = $mostra['id_etiqueta'];
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
				
				$etiqueta = mostra('estoque_etiqueta',"WHERE id ='$etiquetaId'");
				echo '<td>'.$etiqueta['nome'].'</td>';
				
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,30).'</td>';

				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.converteData($mostra['data_solicitacao']).'</td>';
				echo '<td align="right">'.converteData($mostra['data_entrega']).'</td>';
				echo '<td>'.substr($mostra['observacao'],0,30).'</td>';
				echo '<td align="center">'.$mostra['status'].'</td>';
				
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/estoque/etiqueta-retirada-editar&retiradaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/estoque/etiqueta-retirada-editar&retiradaBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" />
              			</a>
				      </td>';
				
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/etiqueta-retirada-editar&retiradaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
              			</a>
						</td>';
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/relatorio/ficha-solicitacao-etiqueta-pdf&retiradaId='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png" alt="Imprimir" title="Imprimir" />
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