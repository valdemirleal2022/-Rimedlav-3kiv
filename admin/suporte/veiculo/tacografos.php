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
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-tacografo-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;

		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-tacografo-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;
	}

	 
	$leitura = read('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2'ORDER BY data_troca ASC"); 

	$total = conta('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' ORDER BY data_troca DESC"); 
		
	
	if(!empty($veiculoId)){
		$leitura = read('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' AND id_veiculo='$veiculoId' ORDER BY data_troca ASC");
		
		$total = conta('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' AND id_veiculo='$veiculoId' ORDER BY data_troca ASC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Tacógrafos</h1>
       <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Tacógrafos</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
         <div class="box-header">
            <a href="painel.php?execute=suporte/veiculo/tacografo-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
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
				 	<td align="center">Véiculo</td>
					<td align="center">Placa</td>
					<td align="center">Data Troca</td>
					<td align="center">Data Prevista</td>
					<td align="center">Observacao</td>
					<td align="center">Status</td>
				 
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$veiculoId = $mostra['id_veiculo'];
		
				$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
				echo '<td>'.$veiculo['modelo'].'</td>';
				echo '<td>'.$veiculo['placa'].'</td>';
		
				echo '<td align="center">'.converteData($mostra['data_troca']).'</td>';
		
				echo '<td align="center">'.converteData($mostra['data_prevista']).'</td>';
			
				echo '<td>'.$mostra['observacao'].'</td>';
		
				if($mostra['status']==1){
                      echo '<td align="center">Realizada</td>';
                  }else{
                      echo '<td align="center">-</td>';
                 }
		
				
		
				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/tacografo-editar&tacografoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png"  title="Editar" />
              			</a>
				      </td>';

				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/tacografo-editar&tacografoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png"  title="Excluir"  />
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