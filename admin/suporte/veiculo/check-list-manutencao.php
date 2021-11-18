<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if(!isset($_SESSION['dataEmissao1'])){
		$dataEmissao1 =date('Y/m/d');
		$dataEmissao2 =date('Y/m/d');
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
	}else{
		$dataEmissao1=$_SESSION['dataEmissao1'];
		$dataEmissao2=$_SESSION['dataEmissao2'];
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$rotaId = $_POST['rota'];
		$veiculoId = $_POST['veiculo'];
		$aterroId = $_POST['aterro'];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
		$_SESSION['rotaId']=$rotaId;
		$_SESSION['veiculoId']=$veiculoId;
		$_SESSION['aterroId']=$aterroId;
		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-liberacao-excel.php' );
	}


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$rotaId = $_POST['rota'];
		$veiculoId = $_POST['veiculo'];
		$aterroId = $_POST['aterro'];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
		$_SESSION['rotaId']=$rotaId;
		$_SESSION['veiculoId']=$veiculoId;
		$_SESSION['aterroId']=$aterroId;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-liberacao-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-pesagem-excel' ] ) ) {
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-pesagem-excel.php' );
	}


			
	if(isset($_POST['pesquisar_numero'])){
		$liberacaoId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($liberacaoId)){
			echo '<div class="alert alert-warning">Número de recebimento Inválido!</div><br />';
		 }else{
			header('Location: painel.php?execute=suporte/veiculo/liberacao-editar&liberacaoBaixar='.$liberacaoId.'');
		}
	}
	

		
	if(isset($_POST['pesquisar'])){
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$rotaId = $_POST['rota'];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
	}

	$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' ORDER BY saida DESC");

	$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' ORDER BY saida DESC");

	if(!empty($rotaId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' ORDER BY saida");
	}
	if(!empty($rotaId) && !empty($veiculoId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' ORDER BY saida DESC");
	}
	if(!empty($veiculoId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND veiculo='$veiculoId' ORDER BY saida DESC");
	}
	 

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Check List - Manutenção</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Check List - Manutenção</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
       
    	
    	 <div class="box-header">
               <div class="row">
                   
                 <div class="col-xs-10 col-md-12 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                             <div class="form-group pull-left">
                                  <input type="date" name="data1" value="<?php echo date('Y-m-d',strtotime($dataEmissao1)) ?>" class="form-control input-sm" >
						    </div>
                             <div class="form-group pull-left">
                                  <input type="date" name="data2" value="<?php echo date('Y-m-d',strtotime($dataEmissao2)) ?>" class="form-control input-sm" >
							</div>
                           
                             <div class="form-group pull-left">
								<select name="rota" class="form-control input-sm">
									<option value="">Rota</option>
									<?php 
										$readBanco = read('contrato_rota',"WHERE id ORDER BY nome ASC");
										if(!$readBanco){
											echo '<option value="">Não temos Bancos no momento</option>';	
										}else{
											foreach($readBanco as $mae):
											   if($rotaId == $mae['id']){
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
					<td align="center">Id</td>
					<td align="center">Veiculo</td>
					<td align="center">Placa</td>
					<td align="center">Saida</td>
					<td align="center">Rota</td>
					<td align="center">Motorista</td>
					<td align="center">Itens</td>
 					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
			$teste=0;
		
			if($mostra['farol_alto_dir_saida']=='0'){
				$teste+=1;
			}
			if($mostra['farol_alto_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['farol_alto_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['farol_alto_esq_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['farol_baixo_dir_saida']=='0'){
				$teste+=1;
			}
			if($mostra['farol_baixo_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['farol_baixo_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['farol_baixo_esq_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['lanterna_dir_saida']=='0'){
				$teste+=1;
			}
			if($mostra['lanterna_esq_saida']=='0'){
				$teste+=1;;
			}
			if($mostra['lanterna_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['lanterna_esq_chegada']=='0'){
				$teste+=1;
			}
			
			
			if($mostra['pisca_dir_saida']=='0'){
				$teste+=1;
			}
			if($mostra['pisca_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['pisca_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['pisca_esq_chegada']=='0'){
				$teste+=1;
			}
		
		
			if($mostra['luz_freio_dir_saida']=='0'){
				$teste+=1;;
			}
			if($mostra['luz_freio_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['luz_freio_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['luz_freio_esq_chegada']=='0'){
				$teste+=1;
			}
		
		
			if($mostra['strobo_dir_saida']=='0'){
				$teste+=1;;
			}
			if($mostra['strobo_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['strobo_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['strobo_esq_chegada']=='0'){
				$teste+=1;
			}
		
		
			if($mostra['limpador_dir_saida']=='0'){
				$teste+=1;;
			}
			if($mostra['limpador_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['limpador_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['limpador_esq_chegada']=='0'){
				$teste+=1;
			}
		   	
			if($mostra['pneu_dir_saida']=='0'){
				$teste+=1;;
			}
			if($mostra['pneu_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['pneu_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['pneu_esq_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['retrovisor_dir_saida']=='0'){
				$teste+=1;;
			}
			if($mostra['retrovisor_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['retrovisor_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['retrovisor_esq_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['parachoque_dir_saida']=='0'){
				$teste+=1;;
			}
			if($mostra['parachoque_esq_saida']=='0'){
				$teste+=1;
			}
			if($mostra['parachoque_dir_chegada']=='0'){
				$teste+=1;
			}
			if($mostra['parachoque_esq_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['embreagem_saida']=='0'){
				$teste+=1;
			}
			if($mostra['embreagem_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['mola_saida']=='0'){
				$teste+=1;
			}
			if($mostra['mola_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['maquinario_saida']=='0'){
				$teste+=1;
			}
			if($mostra['maquinario_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['lifter_240_saida']=='0'){
				$teste+=1;
			}
			if($mostra['lifter_240_chegada']=='0'){
				$teste+=1;
			}
		   
			
			if($mostra['lifter_12_saida']=='0'){
				$teste+=1;
			}
			if($mostra['lifter_12_chegada']=='0'){
				$teste+=1;
			}
			
			if($mostra['cilindro_saida']=='0'){
				$teste+=1;
			}
			if($mostra['cilindro_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['mangueira_saida']=='0'){
				$teste+=1;
			}
			if($mostra['mangueira_chegada']=='0'){
				$teste+=1;
			}
 	
			if($mostra['valvula_saida']=='0'){
				$teste+=1;
			}
			if($mostra['valvula_chegada']=='0'){
				$teste+=1;
			}
			
			if($mostra['comando_dianteiro_saida']=='0'){
				$teste+=1;
			}
			if($mostra['comando_dianteiro_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['comando_traseiro_saida']=='0'){
				$teste+=1;
			}
			if($mostra['comando_traseiro_chegada']=='0'){
				$teste+=1;
			}
		
 		 	
			if($mostra['bomba_saida']=='0'){
				$teste+=1;
			}
			if($mostra['bomba_chegada']=='0'){
				$teste+=1;
			}
		
			if($mostra['reservatorio_saida']=='0'){
				$teste+=1;
			}
			if($mostra['reservatorio_chegada']=='0'){
				$teste+=1;
			}
  
			if($mostra['caixa_saida']=='0'){
				$teste+=1;
			}
			if($mostra['caixa_chegada']=='0'){
				$teste+=1;
			}
			
		
			// 5 - EQUIPAMENTO DE SEGURANÇA
 			if($mostra['limpeza_cabine_saida']=='0'){
				//$teste+=1;
			}
			if($mostra['limpeza_cabine_chegada']=='0'){
				//$teste+=1;
			}
 			
			if($mostra['fita_saida']=='0'){
				//$teste+=1;
			}
			if($mostra['fita_chegada']=='0'){
				//$teste+=1;
			}
 	
			if($mostra['vassoura_saida']=='0'){
				//$teste+=1;
			}
			if($mostra['vassoura_chegada']=='0'){
				//$teste+=1;
			}
			
			if($mostra['pa_saida']=='0'){
				//$teste+=1;
			}
			if($mostra['pa_chegada']=='0'){
				//$teste+=1;
			}
		
			if($mostra['freio_saida']=='0'){
				$teste+=1;
			}
			if($mostra['freio_chegada']=='0'){
				$teste+=1;
			}
 	
 			if($mostra['triangulo_saida']=='0'){
				//$teste+=1;
			}
			if($mostra['triangulo_chegada']=='0'){
				//$teste+=1;
			}
		
			if($mostra['extintor_saida']=='0'){
				//$teste+=1;
			}
			if($mostra['extintor_chegada']=='0'){
				//$teste+=1;
			}
 			
			if($mostra['tacografo_saida']=='0'){
				//$teste+=1;
			}
			if($mostra['tacografo_chegada']=='0'){
				//$teste+=1;
			}
 	 
			if($teste>0){
				echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
				$veiculoId = $mostra['id_veiculo'];
				$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
				if(!$veiculo){
					echo '<td align="center">-</td>';
					echo '<td align="center">-</td>';
					}else{
					echo '<td>'.substr($veiculo['modelo'],0,10).'</td>';
					echo '<td>'.$veiculo['placa'].'</td>';
				}
			
				echo '<td>'.converteData($mostra['saida']).'</td>';
				 
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				if(!$rota){
					echo '<td align="center">-</td>';
					}else{
					echo '<td>'.$rota['nome'].'</td>';
				}
				
				$motoristaId = $mostra['motorista'];
				$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
				if(!$motorista){
					echo '<td align="center">-</td>';
					}else{
					echo '<td>'.$motorista['nome'].'</td>';
				}
				
				
				echo '<td>'.$teste.'</td>';

				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/liberacao-editar&liberacaoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
			 
	
				echo '</tr>';
			}
	 
		 	
		
		 endforeach;
		
		 echo '<tfoot>';
         			echo '<tr>';
                	echo '<td colspan="17">' . 'Total de Registros : ' .  $total . '</td>';
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