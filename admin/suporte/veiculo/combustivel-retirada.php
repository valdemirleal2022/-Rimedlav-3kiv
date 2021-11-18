<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	
	$veiculo = '';

 	if (!isset( $_SESSION[ 'dataInicio' ] ) ) {
		
		$dataInicio = converteData1();
		$dataFinal = converteData2();
		$_SESSION[ 'dataInicio' ] = $dataInicio;
		$_SESSION[ 'dataFinal' ] = $dataFinal ;
		
	} else {
		
		$dataInicio = $_SESSION[ 'dataInicio' ];
		$dataFinal = $_SESSION[ 'dataInicio' ];
		
	}


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$dataInicio = $_POST[ 'inicio' ];
		$dataFinal = $_POST[ 'fim' ];
		$veiculo = $_POST['veiculo'];
		
		$_SESSION['dataInicio']=$dataInicio;
		$_SESSION['dataFinal']=$dataFinal;
		$_SESSION['veiculo']=$veiculo;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-combustivel-retirada-pdf");';
		echo '</script>';
		
	}

	if ( isset( $_POST[ 'relatorio-excel-geral' ] ) ) {
		
		$dataInicio = $_POST[ 'inicio' ];
		$dataFinal = $_POST[ 'fim' ];
		$veiculo = $_POST['veiculo'];
		$_SESSION['dataInicio']=$dataInicio;
		$_SESSION['dataFinal']=$dataFinal;
		$_SESSION['veiculo']=$veiculo;

		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-combustivel-retirada-excel.php' );
	}

	if ( isset( $_POST[ 'relatorio-excel-media' ] ) ) {
		
		$dataInicio = $_POST[ 'inicio' ];
		$dataFinal = $_POST[ 'fim' ];
		$veiculo = $_POST['veiculo'];
		$_SESSION['dataInicio']=$dataInicio;
		$_SESSION['dataFinal']=$dataFinal;
		$_SESSION['veiculo']=$veiculo;

		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-combustivel-media-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		
		$dataInicio = $_POST[ 'inicio' ];
		$dataFinal = $_POST[ 'fim' ];
		$veiculo = $_POST['veiculo'];
		$_SESSION['dataInicio']=$dataInicio;
		$_SESSION['dataFinal']=$dataFinal;
		$_SESSION['veiculo']=$veiculo;
		
	}

	$leitura = read('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' ORDER BY data ASC"); 
	$total = conta('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' 
						ORDER BY data ASC"); 

	if(!empty($veiculo)){
		
		$leitura = read('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='dataFinal' AND id_veiculo='$veiculo' ORDER BY data ASC"); 
		$total = conta('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='dataFinal' AND id_veiculo='$veiculo' ORDER BY data ASC"); 
		
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];
	
?> 
 
<div class="content-wrapper">
	
  <section class="content-header">
	  
       <h1>Abastecimento Interno</h1>
	  
       <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Abastecimento</li>
       </ol>
	  
 </section>
 
<section class="content">
	
	<section class="content">
	  <div class="box box-default">
			<div class="box-body table-responsive">
				
			<?php 
			$leitura2 = read('veiculo_combustivel',"WHERE id ORDER BY nome ASC");
			if($leitura){
				
				echo '<table class="table table-hover">	
				
					<tr class="set">
					
					<td align="center">ID</td>
					<td align="center">Combustível</td>
					<td align="center">Estoque</td>
					<td align="center">Estoque Mínimo</td>
					<td align="center">Valor Unitário</td>
					
				</tr>';
				
			foreach($leitura2 as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td align="right">'.$mostra['estoque'].'</td>';
				echo '<td align="right">'.$mostra['estoque_minimo'].'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_unitario']).'</td>';
				
			echo '</tr>';
		
		 endforeach;
		 
		
		echo '</table>';
 
		
		}
	?>
    
	 
	 </div><!-- /.box-body table-responsive data-spy='scroll -->
 </div><!-- /.box-body table-responsive data-spy='scroll -->
 
 </section><!-- /.content -->
	
<div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
         <div class="box-header">
            <a href="painel.php?execute=suporte/veiculo/combustivel-retirada-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Novo Abastecimento</small>
			 </a>
    	</div><!-- /.box-header -->
    	
    
    	 <div class="box-header">
			 
               <div class="row">
                   
                 <div class="col-xs-10 col-md-7 pull-right">
					 
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
						 
                            <div class="form-group pull-left">
                                  <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($dataInicio)) ?>" class="form-control input-sm" >
						    </div>
						 
                            <div class="form-group pull-left">
                                  <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($dataFinal)) ?>" class="form-control input-sm" >
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
												   if($veiculo == $mae['id']){
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel-geral" title="Relatório Excel Geral"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
						 
						    <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel-media" title="Relatório Excel Média"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                    </form> 
					 
                  </div><!-- /col-xs-10 col-md-12 pull-right-->
				   
	        </div><!-- /row-->   
			 
       </div><!-- /box-header-->   
                    

     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">

		<?php 
		 
			$media='';	
			$km='';	
				
			if($leitura){
				
				echo '<table class="table table-hover">	
				
					<tr class="set">
					
					<td align="center">Id</td>
					<td align="center">Veiculo</td>
					<td align="center">Combustível 1</td>
					<td align="center">Quantidade 1</td>
					<td align="center">Combustível 2</td>
					<td align="center">Quantidade 2</td>
					<td align="center">Km</td>
					<td align="center">Data</td>
					<td align="center">Km Percorrido</td>
					<td align="center">Média</td>
					<td align="center" colspan="4">Gerenciar</td>
					
				</tr>';
				
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$combustivelId = $mostra['id_combustivel'];
				$veiculoId = $mostra['id_veiculo'];
	
				$veiculoMostra = mostra('veiculo',"WHERE id ='$veiculoId'");
				echo '<td>'.$veiculoMostra['placa'].'</td>';
				
				$veiculoCombustivel = mostra('veiculo_combustivel',"WHERE id ='$combustivelId'");
				echo '<td>'.$veiculoCombustivel['nome'].'</td>';
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				
				$combustivelId = $mostra['id_combustivel2'];
				$veiculoId = $mostra['id_veiculo'];
	
				$veiculoCombustivel = mostra('veiculo_combustivel',"WHERE id ='$combustivelId'");
				echo '<td>'.$veiculoCombustivel['nome'].'</td>';
				echo '<td align="right">'.$mostra['quantidade2'].'</td>';
	
				echo '<td align="right">'.$mostra['km'].'</td>';
				echo '<td align="right">'.converteData($mostra['data']).'</td>';

				echo '<td align="right">'.$mostra['km_percorrido'].'</td>';
				echo '<td align="right">'.$mostra['media'].'</td>';
				 
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/combustivel-retirada-editar&retiradaEditar='.$mostra['id'].'">
							<img src="ico/editar.png"   title="Editar" />
              			</a>
						</td>';
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/combustivel-retirada-editar&retiradaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png"   title="Excluir" />
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

	  </div><!-- /.box-body table-responsive -->
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 
</section><!-- /.content -->


  
</div><!-- /.content-wrapper -->