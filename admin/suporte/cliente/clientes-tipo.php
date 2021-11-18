<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}

	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$clienteTipo = $_POST[ 'cliente_tipo' ];
		$clienteClassificacao = $_POST[ 'cliente_classificacao' ];
		
		$_SESSION['clienteTipo']=$clienteTipo;
		$_SESSION['clienteClassificacao']=$clienteClassificacao;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-cliente-tipo-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$clienteTipo = $_POST[ 'cliente_tipo' ];
		$clienteClassificacao = $_POST[ 'cliente_classificacao' ];
		$_SESSION['clienteTipo']=$clienteTipo;
		$_SESSION['clienteClassificacao']=$clienteClassificacao;
		header( 'Location: ../admin/suporte/relatorio/relatorio-cliente-tipo-excel.php' );
		
	}


	$total =0;

	if(isset($_POST['pesquisar'])){
		$clienteTipo = $_POST[ 'cliente_tipo' ];
		$clienteClassificacao = $_POST[ 'cliente_classificacao' ];

	}

	if(!empty($clienteTipo)){
			$leitura = read('cliente',"WHERE id AND tipo='$clienteTipo' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND tipo='$clienteTipo'");
	}
	if(!empty($clienteClassificacao)){
			$leitura = read('cliente',"WHERE id AND classificacao='$clienteClassificacao' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND classificacao='$clienteClassificacao'");
	}
	if(!empty($clienteClassificacao) and !empty($clienteTipo) ){
			$leitura = read('cliente',"WHERE id AND tipo='$clienteTipo' AND classificacao='$clienteClassificacao' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND tipo='$clienteTipo' AND classificacao='$clienteClassificacao'");
	}
 	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?><head>
    <meta charset="iso-8859-1">
</head>




<div class="content-wrapper">
  <section class="content-header">
          <h1>Clientes Tipo/Classificacao</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Clientes</li>
            <li class="active">Cadastro</li>
          </ol>
  </section>
	
 <section class="content">
	 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
           	 <div class="box-header">	
                    <div class="col-xs-10 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
            
                         <div class="form-group pull-left">
								<select name="cliente_tipo" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('cliente_tipo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($clienteTipo == $mae['id']){
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
								<select name="cliente_classificacao" class="form-control input-sm">
									<option value="">Selecione o Classificacao</option>
									<?php 
										$readContrato = read('cliente_classificacao',"WHERE id ORDER BY id ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($clienteClassificacao == $mae['id']){
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
       <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  

	<?php 

	if($leitura){
			echo '<table class="table table-hover">
				<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					
					<td align="center">Tipo</td>
					<td align="center">Classificacao</td>
					<td align="center">Coleta</td>
					
					<td align="center">Unitário</td>
					<td align="center">Mensal</td>
					<td align="center">Vencimento</td>
					
					<td align="center">Percentual</td>
					
					<td align="center">Unitario</td>
					<td align="center">Mensal</td>
					
					<td align="center">Inicio</td>
					<td align="center">Vencimento</td>

					<td align="center">Status</td>

					<td align="center" colspan="6">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	
				echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$clienteId=$mostra['id'];
				echo '<td>'.substr($mostra['nome'],0,30).'</td>';

				$tipoId=$mostra['tipo'];
				$classificacaoId=$mostra['classificacao'];
				$classificacao = mostra('cliente_classificacao',"WHERE id='$classificacaoId'");
				$tipo = mostra('cliente_tipo',"WHERE id ='$tipoId'");
		
				echo '<td>'.$tipo['nome'].'</td>';
				echo '<td>'.$classificacao['nome'].'</td>';
		
				$clinteId = $mostra['id'];
		 
				$contrato = mostra('contrato',"WHERE id_cliente ='$clinteId'");
				$contratoId = $contrato['id'];
		
			 	$coleta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId' ORDER BY vencimento ASC");
		
				$coletaId = $coleta['id'];
				$quantidade = $coleta['quantidade'];
				$tipoColeta = $coleta['tipo_coleta'];
		
                $coletaTipoNome = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta'");
				echo '<td>'.$coletaTipoNome['nome'].'</td>';
					 
				echo '<td align="right">'.(converteValor($coleta['valor_unitario'])).'</td>';
				echo '<td align="right">'.(converteValor($coleta['valor_mensal'])).'</td>';
				echo '<td align="center">'.converteData($coleta['vencimento']).'</td>';
				
				$percentual = 21;
				$valorUnitario = $coleta['valor_unitario'];
				$valorUnitario = $valorUnitario + ($valorUnitario*$percentual)/100;

				$valorExtra = $coleta['valor_extra'];
				$valorExtra = $valorExtra + ($valorExtra*$percentual)/100;

				$valorMensal =$coleta['valor_mensal'];
				$valorMensal = $valorMensal + ($valorMensal*$percentual)/100;
		
				echo '<td align="right">'.(converteValor($percentual)).'</td>';
				echo '<td align="right">'.(converteValor($valorUnitario)).'</td>';
				echo '<td align="right">'.(converteValor($valorMensal)).'</td>';
		
				$inicio= '2020-02-18';
				$vencimento= '2021-02-18';
		
				echo '<td align="right">'.converteData($inicio).'</td>';
				echo '<td align="right">'.converteData($vencimento).'</td>';

		
				if ($coleta['inicio']<>$inicio and $coleta['vencimento']<>$vencimento) {
					
					$alt[ 'vencimento' ] = $inicio;
				//	update('contrato_coleta',$alt,"id = '$coletaId'");	
					
					$cad[ 'id_cliente' ] = $clienteId;
					$cad[ 'id_contrato' ] = $contratoId;
					$cad[ 'tipo_coleta' ] = $tipoColeta;
					$cad[ 'quantidade' ] = $quantidade;
					$cad[ 'valor_unitario' ] = $valorUnitario;
					$cad[ 'valor_extra' ] = $valorExtra;
					$cad[ 'valor_mensal' ] = $valorMensal;
					$cad[ 'percentual' ] = $percentual;
					$cad[ 'inicio' ] = $inicio;
					$cad[ 'vencimento' ] = $vencimento;
					$cad[ 'interacao' ] = date( 'Y/m/d H:i:s' );
				//	create( 'contrato_coleta', $cad );
					
					//break;

				}
		
				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" title="Contrato Ativo"/></td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" title="Contrato Suspenso"/></td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" title="Contrato Cancelado"/></td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
					echo '<td align="center">
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$mostra['id'].'">
								<img src="ico/editar-cliente.png" alt="Editar Cliente" title="Editar Cliente" />
							</a>
						  </td>';
		
		
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-cliente&clienteId='.$mostra['id'].'">
							<img src="ico/agenda.png" alt="Contrato Cronograma" title="Contrato Cronograma"  />
							</a>
						  </td>';
	
					
					echo '</tr>';
	
		
			 endforeach;
	
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="14">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
        
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