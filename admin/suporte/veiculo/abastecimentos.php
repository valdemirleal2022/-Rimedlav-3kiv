<?php 

	 if(function_exists(ProtUser)){
		 
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
		 
	 }

	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-abastecimento-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;

		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-abastecimento-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;
	}

	 
	$leitura = read('veiculo_abastecimento',"WHERE id AND data>='$data1' AND data<='$data2'  
						ORDER BY data DESC"); 

	$total = conta('veiculo_abastecimento',"WHERE id AND data>='$data1' AND data<='$data2'  
						ORDER BY data DESC"); 
		
	
	if(!empty($veiculoId)){
		$leitura = read('veiculo_abastecimento',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculoId' ORDER BY data DESC");
		
		$total = conta('veiculo_abastecimento',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculoId' ORDER BY data DESC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Abastecimento</h1>
       <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Abastecimento</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
         <div class="box-header">
            <a href="painel.php?execute=suporte/veiculo/abastecimento-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Novo Abastecimento</small>
			 </a>
    	</div><!-- /.box-header -->
    	
    
    	 <div class="box-header">
               <div class="row">
                   
                 <div class="col-xs-10 col-md-7 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                             <div class="form-group pull-left">
                                  <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
						    </div>
                             <div class="form-group pull-left">
                                  <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
							</div>
                     
                           
                           <div class="form-group pull-left">
									<select name="veiculo" class="form-control input-sm">
										<option value="">Veículo</option>
										<?php 
											$readConta = read('veiculo',"WHERE id ORDER BY placa ASC");
											if(!$readConta){
												echo '<option value="">Não temos veiculos no momento</option>';	
											}else{
												foreach($readConta as $mae):
												   if($veiculoId == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['placa'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['placa'].'</option>';
													}
												endforeach;	
											}
										?> 
									</select>
								 </div> 
                   
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                    </form> 
                  </div><!-- /col-xs-10 col-md-12 pull-right-->
	        </div><!-- /row-->   
       </div><!-- /box-header-->   
                    
                
   
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">

	<?php 

	if($leitura){
		
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
				 
					<td align="center">Placa</td>
					<td align="center">Data</td>
					
					<td align="center">Motorista</td>
					<td align="center">Combustível</td>
					<td align="center">Quantidade</td>
					
					<td align="center">Valor</td>
					<td align="center">Km</td>
					
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$veiculoId = $mostra['id_veiculo'];
		
				$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
				if(!$veiculo){
					echo '<td align="center">-</td>';
				}else{
					echo '<td>'.$veiculo['placa'].'</td>';
				}
				
				echo '<td>'.converteData($mostra['data']).'</td>';
		
				$motoristaId = $mostra['id_motorista'];
				$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
				echo '<td>'.$motorista['nome'].'</td>';
		
				$tipoId = $mostra['tipo_combustivel'];
				$tipo= mostra('veiculo_tipo_combustivel',"WHERE id ='$tipoId'");
				echo '<td>'.$tipo['nome'].'</td>';
			
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';

				echo '<td align="right">'.$mostra['km'].'</td>';
		
				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/abastecimento-editar&abastecimentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';

				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/abastecimento-editar&abastecimentoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir"  />
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