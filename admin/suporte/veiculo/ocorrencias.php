<?php 

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}
	
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$tipo = $_POST[ 'tipo' ];
		$rota = $_POST[ 'rota' ];
		$veiculo = $_POST[ 'veiculo' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['veiculo']=$veiculo;
		$_SESSION['tipo']=$tipo;
		$_SESSION['rota']=$rota;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-rota-ocorrencia-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$tipo = $_POST[ 'tipo' ];
		$rota = $_POST[ 'rota' ];
		$veiculo = $_POST[ 'veiculo' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['veiculo']=$veiculo;
		$_SESSION['tipo']=$tipo;
		$_SESSION['rota']=$rota;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-rota-ocorrencia-excel.php' );
	}


	$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC");

	$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC, hora DESC");

	
	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$tipo = $_POST[ 'tipo' ];
		$rota = $_POST[ 'rota' ];
		$veiculo = $_POST[ 'veiculo' ];

		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
		
	}


	if(!empty($tipo)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}
		

	if(!empty($veiculo)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculo' ORDER BY data ASC");
	}

	if(!empty($rota)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_rota='$rota'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_rota='$rota' ORDER BY data ASC");
	}
		
	if(!empty($tipo) && !empty($veiculo ) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}

	if(!empty($rota) && !empty($veiculo ) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_rota='$rota'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_rota='$rota' ORDER BY data ASC");
	}

	if(!empty($rota) && !empty($veiculo) && !empty($tipo) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_rota='$rota' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_rota='$rota' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}


	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Ocorrências</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Rota</a>
            <li class="active">Ocorrências</li>
          </ol>
 </section>
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
       <div class="box-header">
            <a href="painel.php?execute=suporte/veiculo/ocorrencia-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Nova Ocorrência</small>
			 </a>
    	</div><!-- /.box-header -->
		 
		 	<div class="box-header">       
                  <div class="col-xs-12 col-md-12 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
						 
						  <div class="form-group pull-left">
								<select name="tipo" class="form-control input-sm">
									<option value="">Tipo</option>
									<?php 
										$readContrato = read('rota_ocorrencia_tipo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($tipo == $mae['id']){
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
								<select name="rota" class="form-control input-sm">
									<option value="">Rota</option>
									<?php 
										$readContrato = read('contrato_rota',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($rota == $mae['id']){
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
									<select name="veiculo" class="form-control input-sm">
										<option value="">Veículo</option>
										<?php 
											$readConta = read('veiculo',"WHERE id ORDER BY modelo ASC");
											if(!$readConta){
												echo '<option value="">Não temos veiculos no momento</option>';	
											}else{
												foreach($readConta as $mae):
												   if($veiculoId == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['modelo'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['modelo'].' | '.$mae['placa'].'</option>';
													}
												endforeach;	
											}
										?> 
									</select>
								 </div> 
						
                                         
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
                  
        </div> <!-- /.box-header -->
       
     
        
    <div class="box-body table-responsive">
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  

     
 <?php

	if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Rota</td>
					<td align="center">Tipo Ocorrência</td>
					<td align="center">Veículo</td>
					<td align="center">Data</td>
					<td align="center">Hora</td>
					<td align="center">Interação</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
	
				$rotaId = $mostra['id_rota'];
				$contratoRota = mostra('contrato_rota',"WHERE id ='$rotaId'");
		
				echo '<td>'.$contratoRota['nome'].'</td>';
		
				$ocorrenciaId = $mostra['id_ocorrencia'];
				$contratoOcorrencia = mostra('rota_ocorrencia_tipo',"WHERE id ='$ocorrenciaId'");
		
				echo '<td>'.$contratoOcorrencia['nome'].'</td>';
		
				$veiculoId = $mostra['id_veiculo'];
				$contratoVeiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
		
				echo '<td>'.$contratoVeiculo['modelo'].'|'.$contratoVeiculo['placa'].'</td>';
		
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'. $mostra['hora'] .'</td>';
		
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';

				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/ocorrencia-editar&ocorrenciaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar"  />
              			</a>
				      </td>';
				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/ocorrencia-editar&ocorrenciaDeletar='.$mostra['id'].'">
			  				<img src="ico/excluir.png" alt="Deletar" title="Deletar" />
              			</a>
				      </td>';
					
			echo '</tr>';
		 endforeach;
		echo '<tfoot>';
                        echo '<tr>';
                            echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
                        echo '</tr>';
                     echo '</tfoot>';
        
                 echo '</table>'; 

                }
           ?>

     </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
       
      <div class="box-footer">
		<?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
		?> 
	  </div><!-- /.box-footer-->
     
     </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
