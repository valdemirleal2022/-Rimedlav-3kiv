 <?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if(isset($_POST['relatorio-pdf'])){
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
		$contratoTipo = $_POST['contrato_tipo'];
		$contratoCobranca = $_POST['contrato_cobranca'];
		$_SESSION['contratoTipo']=$_POST['contratoTipo'];
		$_SESSION['contratoCobranca']=$_POST['contratoCobranca'];

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-ativos-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
		$contratoTipo = $_POST['contrato_tipo'];
		$contratoCobranca = $_POST['contrato_cobranca'];
		$_SESSION['contratoTipo']=$_POST['contratoTipo'];
		$_SESSION['contratoCobranca']=$_POST['contratoCobranca'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-ativos-excel.php');
	}


	if(isset($_POST['relatorio-comlurb-excel'])){
	    header('Location: ../admin/suporte/relatorio/relatorio-comlurb-excel.php');
	}

	if(isset($_POST['relatorio-contrato-ativos-total-excel'])){
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-ativos-total-excel.php');
	}

	if(isset($_POST['relatorio-contrato-total-excel'])){
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-total-excel.php');
	}

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '30';
	$inicio2 = ($pag * $maximo) - $maximo;
	
	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5'",'valor_mensal');
	$total = conta('contrato',"WHERE id AND tipo='2' AND status='5'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' ORDER BY inicio DESC LIMIT $inicio2,$maximo");

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$contratoTipo = $_POST['contrato_tipo'];
		$contratoCobranca = $_POST['contrato_cobranca'];

		$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND inicio>='$data1' 
											  AND inicio<='$data2' AND status='5'",'valor_mensal');
		$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' 
												AND inicio<='$data2' AND status='5'");
		$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' 
											  AND inicio<='$data2' AND status='5' 
											  ORDER BY inicio ASC");
	}

	if(!empty($contratoTipo)){
			$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND inicio>='$data1'  AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5'",'valor_mensal');
			
			$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5'");
			
			$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5' ORDER BY inicio ASC");
			
	}
		
	if(!empty($contratoCobranca)){
			$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND inicio>='$data1'  AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5'",'valor_mensal');
			
			$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5'");
			
			$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5' ORDER BY inicio ASC");
	}

	if(isset($_POST['pesquisar_numero'])){
		$contratoId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(!empty($contratoId)){
			$leitura=read('contrato',"WHERE id AND (id LIKE '%$contratoId%' OR
						controle LIKE '%$contratoId%')");  
		}
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Contratos Ativos</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contratos</li>
           <li>Ativos</li>
         </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

     	<div class="box-header">	 
                     <div class="col-xs-6 col-md-2 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="numero">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
			
			
				<div class="col-xs-6 col-md-2 pull-right">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                 <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-contrato-ativos-total-excel" title="Relatório Excel">Relatório Total</button></i></button>
								</div>   <!-- /.input-group -->     
                             </div><!-- /input-group-->
                         </form> 
                </div><!-- /col-md-3-->

	   			<div class="col-xs-6 col-md-2 pull-right">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                 <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-comlurb-excel" title="Relatório Excel">Relatório Comlurb</button></i></button>
								</div>   <!-- /.input-group -->     
                             </div><!-- /input-group-->
                         </form> 
                </div><!-- /col-md-3-->
	 
	 			<div class="col-xs-6 col-md-2 pull-right">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                 <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-contrato-total-excel" title="Relatório Excel">Relatório Total Classificacao/Tempo</button></i></button>
								</div>   <!-- /.input-group -->     
                             </div><!-- /input-group-->
                         </form> 
                </div><!-- /col-md-3-->
    
          </div><!-- /box-header-->  
	  
	  <div class="box-header">	
                    <div class="col-xs-10 col-md-9 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
            
                        <div class="form-group pull-left">
                           <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                       <div class="form-group pull-left">
                            <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                       </div><!-- /.input-group -->
                       
                        <div class="form-group pull-left">
								<select name="contrato_tipo" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
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
						 
						  <div class="form-group pull-left">
								<select name="contrato_cobranca" class="form-control input-sm">
									<option value="">Selecione o Cobrança</option>
									<?php 
										$readContrato = read('contrato_cobranca',"WHERE id ORDER BY id ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($contratoCobranca == $mae['id']){
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
								<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
								 <i class="fa fa-search"></i></button>
							 </div><!-- /.input-group -->

							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->     
						 
						 
                    </form>  
                     
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
			
					
                  
          </div><!-- /box-header-->   
       

    <div class="box-body table-responsive">
   
	<?php 

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td align="center">Bairro</td>>
					<td align="center">Cidade</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center">Coleta</td>
					<td align="center">Valor Mensal</td>
					<td align="center">Aprovação</td>
					<td align="center">Inicio</td>
					<td align="center">Feriado</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';

				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.substr($mostra['controle'],0,6).'</td>';
			
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';
				echo '<td align="left">'.substr($cliente['cidade'],0,15).'</td>';
		
				$contratoTipoId = $mostra['contrato_tipo'];
				$monstraContratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$monstraContratoTipo['nome'].'</td>';
	
				$contratoId = $mostra['id'];
		
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				$tipoColetaId = $contratoColeta['tipo_coleta'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$coleta['nome'].'</td>';
		
				echo '<td align="right">'.(converteValor($mostra['valor_mensal'])).'</td>';
				echo '<td align="center">'.converteData($mostra['aprovacao']).'</td>';
				echo '<td align="center">'.converteData($mostra['inicio']).'</td>';
		
				if($mostra['coletar_feriado']=='1'){
				 $con['coletar_feriado']='0';
				 update('contrato',$con,"id = '$contratoId'");
				//	break;
				}
		
				echo '<td>'.$mostra['coletar_feriado'].'</td>';

				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" Contrato" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar Contrato" class="tip" />
              			</a>
				      </td>';
		
				
				$pdf='../../wpc/uploads/contratos/'.$mostra['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../../wpc/uploads/contratos/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" alt="Contrato" title="Contrato" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}

			echo '</tr>';
		
		 endforeach;
		 
		if(!isset($_POST['pesquisar_numero'])){
			 echo '<tfoot>';
							echo '<tr>';
							echo '<td colspan="12">' . 'Total de registros : ' .  $total . '</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td colspan="12">' . 'Valor Total R$ : ' . number_format($valor_total,2,',','.') . '</td>';
							echo '</tr>';

			 echo '</tfoot>';
		 }
		
		 echo '</table>';
		
		 $link = 'painel.php?execute=suporte/contrato/contrato-ativos&pag=';
		
			if (!isset( $_POST[ 'pesquisar' ] ) ) {
				pag('contrato',"WHERE id AND tipo='2' AND status='5' ORDER BY inicio DESC", $maximo, $link, $pag);
			}
		
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
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->
