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
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-manutencao-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;

		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-manutencao-excel.php' );
	}
		
	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$tipoManutencao = $_POST['tipoManutencao'];
		$tipoConserto = $_POST['tipoConserto'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;
	}


	$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao DESC");
	
	$total = conta('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao DESC");

	if(!empty($veiculoId)){
		$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_veiculo='$veiculoId' ORDER BY data_solicitacao DESC");
		$total = conta('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_veiculo='$veiculoId' ORDER BY data_solicitacao DESC");
	}

	if(!empty($tipoManutencao)){
		$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND manutencao='$tipoManutencao' ORDER BY data_solicitacao DESC");
		$total = conta('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND manutencao='$tipoManutencao' ORDER BY data_solicitacao DESC");
	}

	if(!empty($tipoConserto)){
		$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND tipo1='$veiculoId' ORDER BY data_solicitacao DESC");
		$total = conta('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND tipo1='$tipoConserto' ORDER BY data_solicitacao DESC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Manutenção</h1>
       <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Manutenção</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
         <div class="box-header">
            <a href="painel.php?execute=suporte/veiculo/manutencao-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Nova Manutenção</small>
			 </a>
    	</div><!-- /.box-header -->
    	
    
    	 <div class="box-header">
               <div class="row">
                   
                 <div class="col-xs-12 col-md-9 pull-right">
					 
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
						 
							<select name="tipoManutencao" class="form-control input-sm" >
								<option value="">Selecione Preventiva/Corretiva</option>
								<option <?php if($tipoManutencao == '1') echo' selected="selected"';?> value="1">Preventiva </option>
								<option <?php if($tipoManutencao == '2') echo' selected="selected"';?> value="2">Corretiva</option>
								<option <?php if($tipoManutencao == '3') echo' selected="selected"';?> value="3">Socorro</option>
								<option <?php if($tipoManutencao == '4') echo' selected="selected"';?> value="4">Diversos</option>
								<option <?php if($tipoManutencao == '5') echo' selected="selected"';?> value="5">Acidente</option>

							 </select>
						</div>  
						 
				  <div class="form-group pull-left">
                 <select name="tipoConserto" class="form-control input-sm">
                    <option value="">Selecione o Tipo </option>
                    <?php 
                        $readConta = read('veiculo_manutencao_tipo',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($tipoConserto == $mae['id']){
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
				
	$totalManutencao=0;

	if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Veiculo</td>
					<td align="center">Placa</td>
					<td align="center">Tipo</td>
					<td align="center">Data</td>
					<td align="center">Motorista</td>
					<td align="center">Turno</td>
					<td align="center">Man</td>
					<td align="center">Con</td>
					<td align="center">Pen</td>
					<td align="center">Tempo</td>
					<td align="center">Status</td>
					<td align="center">Vl Manutenção</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
				$veiculoId = $mostra['id_veiculo'];
				$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
				if(!$veiculo){
					echo '<td align="center">-</td>';
					echo '<td align="center">-</td>';
					}else{
					echo '<td>'.$veiculo['modelo'].'</td>';
					echo '<td>'.$veiculo['placa'].'</td>';
				}
		
				if($mostra['manutencao']=='1'){
					echo '<td align="center">Preventiva</td>';
				}elseif($mostra['manutencao']=='2'){
					echo '<td align="center">Corretiva</td>';
				}elseif($mostra['manutencao']=='3'){
					echo '<td align="center">Socorro</td>';
				}elseif($mostra['manutencao']=='4'){
					echo '<td align="center">Diversos</td>';
				}else{
					echo '<td align="center">-</td>';
				}
		
				
				echo '<td>'.converteData($mostra['data_solicitacao']).' | '.$mostra['hora_solicitacao'].'</td>';
		
				$motoristaId = $mostra['id_motorista'];
				$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
				if(!$motorista){
					echo '<td align="center">-</td>';
					}else{
					echo '<td>'.substr($motorista['nome'],0,15).'</td>';
				}

				if($mostra['turno']==1){
                        echo '<td align="center">1º</td>';
				 }elseif($mostra['turno']==2){
                        echo '<td align="center">2º</td>';
                 }elseif($mostra['turno']==3){
                        echo '<td align="center">3º</td>';
				 }else{
					 echo '<td align="center">-</td>';
				}
				
				$manutencao=0;
				$concluida=0;
		
				if(!empty($mostra['descricao1'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao2'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao3'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao4'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao5'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao6'])){
					$manutencao=$manutencao+1;
				}
		
				if($mostra['status1']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status2']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status3']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status4']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status5']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status6']=='2'){
					$concluida=$concluida+1;
				}
		
				echo '<td>'.$manutencao.'</td>';
				echo '<td>'.$concluida.'</td>';
		
				$pendencias=$manutencao-$concluida;
				echo '<td>'.$pendencias.'</td>';
	
				
				$ano1 = substr($mostra['inicio1'],0,4 );
				$ano2 = substr($mostra['termino1'],0,4 );
		
				if(!empty($ano1) && !empty($ano2) ){
					if($ano1==$ano2 ){
						$HoraEntrada = new DateTime($mostra['inicio1']);
						$HoraSaida  = new DateTime($mostra['termino1']);
						$diffHoras = $HoraSaida->diff($HoraEntrada)->format('%H:%I:%S');
						echo '<td>'. $diffHoras .'</td>';
					}else{
						echo '<td>Data Invalida</td>';
				
					}
					
				}else{
					echo '<td>-</td>';
				 	
				}
				
			
				
				
				if($pendencias=='0'){
					echo '<td>Concluida</td>';
				}else{
					echo '<td>Em Manutenção</td>';
				}
		

				$manutencaoId = $mostra['id'];
		
				$valorManutencao=0;
		
				$leituraPecas = read('estoque_material_retirada',"WHERE id AND id_manutencao='$manutencaoId' ORDER BY id ASC");
				foreach($leituraPecas as $pecas):
					$materialId = $pecas['id_material'];
					$material = mostra('estoque_material',"WHERE id ='$materialId'");
				
					$valorManutencao=$valorManutencao+$pecas['quantidade']*$material['valor_unitario'];
				 endforeach;
		

  
				echo '<td align="right">'.converteValor($valorManutencao).'</td>';
		
				
		
				$totalManutencao = $totalManutencao + $valorManutencao;
		
				if($mostra['manutencao']<>'3'){
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/veiculo/manutencao-editar&manutencaoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';

				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/manutencao-editar&manutencaoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir"  />
              			</a>
						</td>';
				}
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/relatorio/solicitacao-manutencao&manutencao='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png"  title="Imprimir"  />
						</a>
					 </td>';	

			echo '</tr>';
		
		 endforeach;
		
		 echo '<tfoot>';
         		echo '<tr>';
                	echo '<td colspan="15">' . 'Valor Total : ' .  converteValor($totalManutencao). '</td>';
                echo '</tr>';
		
				echo '<tr>';
                	echo '<td colspan="15">' . 'Total de Registros : ' .  $total . '</td>';
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