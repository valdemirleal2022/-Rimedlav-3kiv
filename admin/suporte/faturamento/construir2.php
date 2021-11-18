<head>
    <meta charset="iso-8859-1">
</head>

<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$dataExtrato = $_POST['data'];
		$contratoTipo = $_POST['contrato_tipo'];
		$status = $_POST['status'];
		
		$dia = numeroDia($dataExtrato);
		$mesFaturamento = numeroMes($dataExtrato);
		$_SESSION['dia']=$dia;
		$_SESSION['dataExtrato']=$dataExtrato;
		$_SESSION['contratoTipo']=$contratoTipo;
		$_SESSION['status']=$status;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-construir-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$dataExtrato = $_POST['data'];
		$contratoTipo = $_POST['contrato_tipo'];
		$status = $_POST['status'];
		
		$dia = numeroDia($dataExtrato);
		$mesFaturamento = numeroMes($dataExtrato);
		$_SESSION['dia']=$dia;
		$_SESSION['dataExtrato']=$dataExtrato;
		$_SESSION['contratoTipo']=$contratoTipo;
		$_SESSION['status']=$status;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-construir-excel.php' );
		
	}


	if(!isset($_SESSION['dataExtrato'])){
		
		$dataExtrato = date( "Y/m/d");
		$dia = numeroDia($dataExtrato);
		$mesFaturamento = numeroMes($dataExtrato);
		$_SESSION['dataExtrato']=$dataExtrato;
		
	}else{
		
		$dataExtrato=$_SESSION['dataExtrato'];
		$dia = numeroDia($_SESSION['dataExtrato']);
		$mesFaturamento = numeroMes($dataExtrato);
		$contratoTipo=$_SESSION['contratoTipo'];
		$status=$_SESSION['status'];
		$bancoExtrato=$_SESSION['bancoExtrato'];
		
	}

	if(isset($_POST['pesquisar-dia'])){
		
		$dataExtrato = $_POST['data'];
		$contratoTipo = $_POST['contrato_tipo'];
		$status = $_POST['status'];
		
		$dia = numeroDia($dataExtrato);
		$mesFaturamento = numeroMes($dataExtrato);
		$_SESSION['dia']=$dia;
		$_SESSION['dataExtrato']=$dataExtrato;
		$_SESSION['contratoTipo']=$contratoTipo;
		$_SESSION['status']=$status;
		
	}
	
	if(isset($_POST['relatorio-extrato'])){
		
		$dataExtrato = $_POST['data'];
		$contratoTipo = $_POST['contrato_tipo'];
		$status = $_POST['status'];
		
		$dia = numeroDia($dataExtrato);
		$mesFaturamento = numeroMes($dataExtrato);
		$_SESSION['dia']=$dia;
		$_SESSION['dataExtrato']=$dataExtrato;
		$_SESSION['contratoTipo']=$contratoTipo;
		$_SESSION['status']=$status;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/faturamento/extrato-cliente-total-pdf");';
		echo '</script>';
	}

	if(isset($_POST['relatorio-extrato-resumido'])){
		
		$dataExtrato = $_POST['data'];
		$contratoTipo = $_POST['contrato_tipo'];
		$status = $_POST['status'];
		
		$dia = numeroDia($dataExtrato);
		$mesFaturamento = numeroMes($dataExtrato);
		$_SESSION['dia']=$dia;
		$_SESSION['dataExtrato']=$dataExtrato;
		$_SESSION['contratoTipo']=$contratoTipo;
		$_SESSION['status']=$status;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/faturamento/extrato-cliente-total-resumido-pdf");';
		echo '</script>';
	}

	if(isset($_POST['construir'])){
		
		$bancoExtrato = $_POST['banco'];
		$_SESSION['bancoExtrato']=$bancoExtrato;
		
		if(!empty($bancoExtrato)){
			
			if(!empty($status)){
				require_once( "construir-faturamento.php" );
			 }else{
				echo '<script>alert("Selecione o Status")</script>';
			 }
			
		 }else{
			echo '<script>alert("Selecione o Banco")</script>';
		}
	}

	$pag = ( empty( $_GET[ 'pag' ] ) ? '1' : $_GET[ 'pag' ] );
	$maximo = '150';
	$inicio = ( $pag * $maximo ) - $maximo;

	// 9 - Contrato Cancelado
	$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND status<>'9' 
					AND inicio<'$dataExtrato'");
	$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status<>'9' 
					AND inicio<'$dataExtrato' ORDER BY controle ASC LIMIT $inicio,$maximo" );
	
	
	
	// FEVEREIRO
	if($mesFaturamento=='02' AND $dia=='28'){
		$total = conta('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
						AND inicio<'$dataExtrato'");
		$leitura = read('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
						AND inicio<'$dataExtrato' ORDER BY controle ASC LIMIT $inicio,$maximo" );
	
	}

	if(!empty($status)){
		
		$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato'");
		$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato'
					ORDER BY controle ASC LIMIT $inicio,$maximo" );
	
		
		// FEVEREIRO
		if($mesFaturamento=='02' AND $dia=='28'){
			$total = conta('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'");
			$leitura = read('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'
					ORDER BY controle ASC LIMIT $inicio,$maximo" );
	
		}
		
		// 5 Contrato Ativo 
		// 6 Contrato Suspensos
		// 9 Contrato Cancelado
		// 10 Ação JUDICIAL
		// 19 Contrato Suspenso Temporario
		
		if($status=='6'){
			
			$dataSuspensao = date("Y-m-d", strtotime("-90 day"));
			
			$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
						AND inicio<'$dataExtrato' AND data_suspensao>'$dataSuspensao'");
			$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
						AND inicio<'$dataExtrato' AND data_suspensao>'$dataSuspensao'
						ORDER BY controle ASC LIMIT $inicio,$maximo" );
	
		}
		
		
			
	}

	if(!empty($contratoTipo)){
		
		$total = conta('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'");
		$leitura = read('contrato',"WHERE id AND dia_fechamento='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'
					ORDER BY controle ASC LIMIT $inicio,$maximo" );
	
		
		// FEVEREIRO
		if($mesFaturamento=='02' AND $dia=='28'){
			$total = conta('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'");
			$leitura = read('contrato',"WHERE id AND dia_fechamento>='$dia' AND status='$status' 
					AND inicio<'$dataExtrato' AND contrato_tipo='$contratoTipo'
					ORDER BY controle ASC LIMIT $inicio,$maximo" );
	
		}
	}


	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Faturamento</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="painel.php?execute=suporte/faturamento/construir-faturamento">Faturamento</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

            <div class="box-header">	  
             	 <div class="col-xs-6 col-md-12 pull-left">
                     
                      <form name="form-pesquisa" method="post" class="form-inline " role="form">
                       
                            <div class="form-group pull-left-sm">
								<select name="contrato_tipo" class="form-control input-sm">
									<option value="">Selecione Tipo</option>
									<?php 
										$readContrato = read('contrato_tipo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($contratoTipo == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
													}
											endforeach;	
										}
									?> 
							 	</select>
							</div> 
						  
					  <div class="form-group pull-left-sm">
								 <select name="status" class="form-control input-sm">
								  <option value="">Selecione Status</option>
								  <option <?php if($status == '5') echo' selected="selected"';?> value="5">Ativos</option>
								  <option <?php if($status == '6') echo' selected="selected"';?> value="6">Suspensos</option>
								 <option <?php if($status == '7') echo' selected="selected"';?>  value="7">Rescindidos</option>
								  <option <?php if($status == '9') echo' selected="selected"';?> value="9">Cancelados</option>
								 </select>
						</div> 
                       
                        <div class="form-group pull-left-sm">
                                  <input type="date" name="data" value="<?php echo date('Y-m-d',strtotime($dataExtrato)) ?>" class="form-control input-sm" >
						</div>
            	         
             	        <div class="form-group pull-left-sm">
                                    <button class="btn btn-sm btn-default" name="pesquisar-dia" type="submit"><i class="fa fa-search" title="Pesquisar" title="Pesquisar"></i></button>                   
                        </div><!-- /.input-group -->
                          
                        <div class="form-group pull-left-sm">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                        </div>  <!-- /.input-group -->
                            
                        <div class="form-group pull-left-sm">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                        </div>   <!-- /.input-group -->
                            
                                                  
                       <div class="form-group pull-left-sm">
                         <button class="btn btn-sm btn-success pull-right" title="Extra Detalhado" type="submit" name="relatorio-extrato">Extrato Detalhado</button>
                      </div><!-- /.input-group -->
                      
                        <div class="form-group pull-left-sm">
                         <button class="btn btn-sm btn-success pull-right" title="Extra Resumido" type="submit" name="relatorio-extrato-resumido">Extrato Resumido</button>
                      </div><!-- /.input-group -->
                      
                            
                     </form> 
              </div><!-- /col-xs-6 col-md-5 pull-right-->
		</div><!-- /.col-xs-10 col-md-4 pull-right--> 
  
     <div class="col-xs-6 col-md-12 pull-left">
          	 <div class="col-xs-10 col-md-4 pull-right">
                <form name="form-pesquisa-banco" method="post" class="form-inline" role="form">
               
                  	    <div class="form-group pull-right-sm">
                            <select name="banco" class="form-control input-sm">
                                <option value="">Selecione Banco</option>
                                <?php 
                                    $readBanco = read('banco',"WHERE id");
                                    if(!$readBanco){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readBanco as $mae):
                                           if($bancoExtrato == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
								</select>
							 </div>
                  	  
                   	  <div class="form-group pull-right-sm">
                         <button class="btn btn-sm btn-warning pull-right" type="submit"  title="Construir Faturamento" name="construir"> Construir Faturamento</button>
                      </div><!-- /.input-group -->

                 </form> 
           	</div><!-- /.col-xs-10 col-md-4 pull-right--> 
	</div><!-- /.col-xs-10 col-md-4 pull-right--> 
    
    <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
	<?php 
		
	$totalFaturar=0;
	$totalFaturado=0;	

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Número</td>
					<td>Nome</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center">Valor Mensal</td>
					<td align="center">Fech</td>
					<td align="center">Venc</td>
					<td align="center">F</td>
					<td align="center">Emissao</td>
					<td align="center">Vencimento</td>
					<td align="center">Faturado</td>
					<td align="center">S</td>
					<td align="center">Suspensão</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td align="left">'.$mostra['id'].'</td>';
				echo '<td>'.substr($mostra['controle'],0,6).'</td>';
		
				$contratoId = $mostra['id'];
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
		
				$tipoId = $mostra['contrato_tipo'];
				$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
				echo '<td>'.$tipo['nome'].'</td>';
		
				$fechamento = $mostra['dia_fechamento'];
				$vencimento = $mostra['dia_vencimento'];

				$mes = date( 'm', strtotime( $dataExtrato ) );
				$ano = date( 'Y', strtotime( $dataExtrato ) );

				$faturamento =  date( "Y-m-d",mktime(0,0,0,$mes,$fechamento,$ano));
				$vencimento = date( "Y-m-d",mktime(0,0,0,$mes,$vencimento,$ano));

				$data1 = date("Y-m-d", strtotime("$faturamento -1 month"));
				$data2 = date("Y-m-d", strtotime("$faturamento -1 days"));
		
				$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND vencimento>='$data2' AND id_contrato='$contratoId'" );
				if($tipoColeta){
					echo '<td align="right">'.(converteValor($tipoColeta['valor_mensal'])).'</td>';
				}else{
					 echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}

				$totalFaturar=$totalFaturar+$tipoColeta['valor_mensal'];
		
				echo '<td align="center">'.$mostra['dia_fechamento'].'</td>';
				echo '<td align="center">'.$mostra['dia_vencimento'].'</td>';
		
				if($mostra['nao_construir_faturamento']=='1'){
					echo '<td align="center">N</td>';
				}else{
					echo '<td align="center">S</td>';
				}
		
				$receber = mostra('receber',"WHERE id_cliente='$clienteId' AND emissao='$dataExtrato'");
				echo '<td align="center">'.converteData($receber['emissao']).'</td>';
				echo '<td align="center">'.converteData($receber['vencimento']).'</td>';
				echo '<td align="center">'.converteValor($receber['valor']).'</td>';
		
				$totalFaturado=$totalFaturado+$receber['valor'];
		
				// 5 Contrato Ativo 
				// 6 Contrato Suspensos
				// 9 Contrato Cancelado
				// 10 Ação JUDICIAL
				// 19 Contrato Suspenso Temporario
			
				if($mostra['status']==5) {
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											 title="Contrato Ativo" />  </td>';
				}elseif($mostra['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($mostra['status']==7){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($mostra['status']==9){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
										 title="Contrato Rescindo" /> </td>';
				}elseif($mostra['status']==19){
					echo '<td align="center"><img src="ico/contrato-suspenso-temporario.png" 
											 title="Contrato Suspenso Temporario" /> </td>';
				}elseif($mostra['status']==10){
					echo '<td align="center"><img src="ico/juridico.png" 
										 title="Contrato no Juridico" /> </td>';
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}
		
				$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato = '$contratoId' AND tipo='8'");
				if($mostra['status']==6){
					$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato = '$contratoId' AND tipo='2'");
				}
		
				if($contratoBaixa){
					echo '<td align="right">'.converteData($contratoBaixa['data']).'</td>';
				}else{
					echo '<td align="right">-</td>';
				}

				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/agenda.png" alt="Editar" title="Cronograma Contrato" />
              			</a>
				      </td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/faturamento/extrato-cliente&contratoId='.$mostra['id'].'" target="_blank">
							<img src="ico/extrato.png"  title="Extrato"  />
              			</a>
						</td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/faturamento/extrato-cliente-resumido&contratoId='.$mostra['id'].'" target="_blank">
							<img src="ico/extrato.png"  title="Extrato Cliente"  />
              			</a>
						</td>';
		
		    	echo '<td align="center">
			  			<a href="painel.php?execute=suporte/faturamento/extrato-cliente-mes-anterior&contratoId='.$mostra['id'].'" target="_blank">
							<img src="ico/extrato2.png"  title="Extrato Mes Anterior"  />
              			</a>
						</td>';

			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">'.'Valor a Faturar R$ : '.converteValor($totalFaturar).'</td>';
          echo '</tr>';
		
		 echo '<tr>';
             echo '<td colspan="17">'.'Valor Faturado R$ : '.converteValor($totalFaturado).'</td>';
          echo '</tr>';
		
		echo '<tr>';
             echo '<td colspan="17">'.'Total de registros :  '.$total.'</td>';
          echo '</tr>';
         echo '</tfoot>';
		 
		 echo '</table>';

		 if(empty($contratoTipo) ){
				$link = 'painel.php?execute=suporte/faturamento/construir&pag=';		
				pag('contrato',"WHERE id AND dia_fechamento='$dia' AND status<>'9' AND status='$status' AND inicio<'$dataExtrato' ORDER BY contrato_tipo ASC, controle ASC", $maximo, $link, $pag);
			}else{
				
				$link = 'painel.php?execute=suporte/faturamento/construir&pag=';		
				pag('contrato',"WHERE id AND dia_fechamento='$dia' AND status<>'9' AND status='$status' AND inicio<'$dataExtrato'  AND contrato_tipo='$contratoTipo' ORDER BY contrato_tipo ASC, controle ASC", $maximo, $link, $pag);
			
			}
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