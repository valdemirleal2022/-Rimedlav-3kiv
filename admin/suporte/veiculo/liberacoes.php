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
		$aterroId = $_POST['aterro'];
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
	if(!empty($rotaId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND aterro='$aterroId' ORDER BY saida DESC");
	}

	if(!empty($rotaId) and !empty($veiculoId) and !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida DESC");
	}
	if(!empty($veiculoId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida DESC");
	}
	if(!empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND aterro='$aterroId' ORDER BY saida DESC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Liberações</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Liberações</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
         <div class="box-header">
            <a href="painel.php?execute=suporte/veiculo/liberacao-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Nova Liberação</small>
			 </a>
    	</div><!-- /.box-header -->
    	
    	<div class="box-header">    
                   <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="Numero">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit" title="Pequisar"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
          </div><!-- /box-header-->   
		 
		   <div class="box-header">
               <div class="row">
                   
                 <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                             <div class="form-group pull-left">
                                  <input type="date" name="data1" value="<?php echo date('Y-m-d',strtotime($dataEmissao1)) ?>" class="form-control input-sm" >
						    </div>
                             <div class="form-group pull-left">
                                  <input type="date" name="data2" value="<?php echo date('Y-m-d',strtotime($dataEmissao2)) ?>" class="form-control input-sm" >
							</div>
 
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pesagem-excel" title="Relatório Pesagem Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                    </form> 
                  </div><!-- /col-xs-10 col-md-12 pull-right-->
	        </div><!-- /row-->   
       </div><!-- /box-header-->   
    	
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
									<select name="aterro" class="form-control input-sm">
										<option value="">Aterro</option>
										<?php 
											$readConta = read('aterro',"WHERE id ORDER BY id ASC");
											if(!$readConta){
												echo '<option value="">Não temos aterro no momento</option>';	
											}else{
												foreach($readConta as $mae):
												   if($aterroId == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.substr($mae['nome'],0,30).'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.substr($mae['nome'],0,30).'</option>';
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
					<td align="center">Veiculo</td>
					<td align="center">Placa</td>
					<td align="center">Saida</td>
					<td align="center">Hora</td>
					<td align="center">Retorno</td>
					<td align="center">Hora</td>
					<td align="center">Peso</td>
					<td align="center">Rota</td>
					<td align="center">Saida</td>
					<td align="center">Chegada</td>
					<td align="center">H Trab</td>
					<td align="center">Status</td>
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
					echo '<td>'.substr($veiculo['modelo'],0,10).'</td>';
					echo '<td>'.$veiculo['placa'].'</td>';
				}
			
				echo '<td>'.converteData($mostra['saida']).'</td>';
				echo '<td>'.$mostra['saida_hora'].'</td>';
		
				echo '<td>'.converteData($mostra['chegada']).'</td>';
				echo '<td>'.$mostra['chegada_hora'].'</td>';
				
				echo '<td align="right">'.$mostra['pesagem'].'</td>';
		
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				if(!$rota){
					echo '<td align="center">-</td>';
					}else{
					echo '<td>'.$rota['nome'].'</td>';
				}

				echo '<td align="right">'.$mostra['km_saida'].'</td>';
				echo '<td align="right">'.$mostra['km_chegada'].'</td>';
		
				if(!empty($mostra['chegada_hora'])){
					
					$horas = (int) ($mostra['horas_trabalhadas']/60);
					$minutos = $mostra['horas_trabalhadas']- ($horas*60) ;
					$totalHoras = $horas.'h ' . $minutos.'m';

					echo '<td align="right">'.$totalHoras.'</td>';
				}else{
					echo '<td align="right">-</td>';
				}
		
				echo '<td>'.$mostra['status'].'</td>';
		
				
				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/liberacao-editar&liberacaoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/liberacao-editar&liberacaoBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png"  title="Baixar"  />
              			</a>
				      </td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/liberacao-editar&liberacaoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/veiculo/liberacao&liberacaoImprimir='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png" title="imprimir" />
              			</a>
						</td>';
	
			echo '</tr>';
		
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