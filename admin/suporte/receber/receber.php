<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	unset($_SESSION['inicio']);
	unset($_SESSION['fim']);

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '20';
	$inicio = ($pag * $maximo) - $maximo;

	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");
	
    $data = date("Y-m-d", strtotime("-2 day"));
    $leitura = read('receber',"WHERE vencimento>'$data' AND status='Em Aberto' ORDER BY vencimento 
				ASC LIMIT $inicio,$maximo");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$contratoTipo = $_POST['contrato_tipo'];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['contratoTipo']=$contratoTipo;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receita-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$contratoTipo = $_POST['contrato_tipo'];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['contratoTipo']=$contratoTipo;
		header( 'Location: ../admin/suporte/relatorio/relatorio-receita-excel.php' );
	}

	
	if(isset($_POST['pesquisar_nota'])){
		$notaId=strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
		header('Location: painel.php?execute=suporte/receber/receber-editar&receberNota='.$notaId.'');
	}

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$contratoTipo = $_POST['contrato_tipo'];

	}
		
	$total = conta('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' ORDER BY vencimento ASC");
	$valor_total = soma('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' ORDER BY vencimento ASC",'valor');
		
	$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' ORDER BY vencimento ASC");
		
	if(!empty($contratoTipo)){
			$total = conta('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado'  AND contrato_tipo='$contratoTipo'");
			$valor_total = soma('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status<>'Baixado' AND contrato_tipo='$contratoTipo'",'valor');
			$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND contrato_tipo='$contratoTipo' AND status<>'Baixado' ORDER BY vencimento ASC");
	}

	if(isset($_POST['pesquisar_numero'])){
		$receberId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$leitura = read('receber',"WHERE id='$receberId'");
		$total = conta('receber',"WHERE id='$receberId'");
		$valor_total = soma('receber',"WHERE id='$receberId'",'valor');
	}

	if(isset($_POST['pesquisar_contrato'])){
		$contratoId=strip_tags(trim(mysql_real_escape_string($_POST['contrato'])));
		$_SESSION['contratoId']=$contratoId;
	
	}

	if(isset($_SESSION['contratoId'])){
			$leitura = read('receber',"WHERE id_contrato='$contratoId' AND status='Em Aberto'");
		$total = conta('receber',"WHERE id_contrato='$contratoId' AND status='Em Aberto'");
		$valor_total = soma('receber',"WHERE id_contrato='$contratoId' AND status='Em Aberto'",'valor');
		
		
	}
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	
	$mes = date('m/Y');
	$mesano = explode('/',$mes);
?>

<div class="content-wrapper">

  <section class="content-header">
	  
     <h1>Receita</h1>
	  
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Contas a Receber</a>
     	<li class="active">Receita</li>
     </ol>

 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     		        
			 <div class="box-header"> 
				<div class="col-xs-10 col-md-2 pull-left">
					<a href="painel.php?execute=suporte/receber/boleto-retorno" class="btnnovo">
						<img src="ico/upload.png" alt="Retorno" title="Retorno" class="tip" />
						<small>Ler Retorno</small>
					</a> 
				</div><!-- /col-xs-4-->   
			 </div><!-- /box-header-->
     
             <div class="box-header">
               <div class="row">
       				 <div class="col-md-12">  
						 
                     <div class="col-xs-6 col-md-2 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="nota" class="form-control input-sm" placeholder="Nota">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_nota" type="submit"><i class="fa fa-search"></i></button>                            
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-xs-6-->
                  
                   <div class="col-xs-6 col-md-2 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="Boleto">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
						 
						 
					 <div class="col-xs-6 col-md-2 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="contrato" class="form-control input-sm" placeholder="Id Contrato">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_contrato" type="submit"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
                   
                                            
                  <div class="col-xs-10 col-md-7 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                        
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
                  
             </div><!-- /row-->  
			 </div><!-- /row-->  
         </div><!-- /box-header-->   
 
    <div class="box-body table-responsive">
     
 	<?php 

    if($leitura){
		
			echo '<table class="table table-hover">
			
					<tr class="set">
			 		<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Tipo</td>
					<td align="center">Consultor</td>
					<td align="center">Valor Liquido</td>
					<td align="center">Vencimento</td>
					<td align="center">Prog Pag</td>
					<td align="center">Nota</td>
					<td align="center">Banco/Pag</td>
					<td align="center">B</td>
					<td align="center">Rem</td>
					<td align="center">R</td>   
					
					<td colspan="8" align="center">Gerenciar</td>
					
				</tr>';
		
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			 
				$receberId = $mostra['id'];
				$clienteId = $mostra['id_cliente'];
				echo '<td>'.$mostra['id'].'</td>';
				
				$contratoId = $mostra['id_contrato'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
				if($cliente['tipo']==4){
					echo '<td>'.substr($cliente['nome'],0,18).' <img src="ico/premium.png"/></td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				}
				
				$contratoTipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
				
				if($contratoTipoId>'18'){
					//baixa automatica
					$cad['status']= 'Baixado';
					$cad['pagamento'] = $mostra['vencimento'];
			 		//update('receber',$cad,"id = '$receberId'");	
				}

		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				$consultorId = $contrato['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td>'.substr($consultor['nome'],0,10).'</td>';
				
				$valorLiquido = $mostra['valor']+$mostra['juros']-$mostra['desconto'];
				echo '<td align="right">'.converteValor($valorLiquido).'</td>';
		
		
				echo '<td>'.converteData($mostra['vencimento']).'</td>';
				if(!empty($mostra['refaturamento_vencimento']) ){
					echo '<td align="center">'.converteData($mostra['refaturamento_vencimento']).'</td>';
				}else{
					echo '<td align="center">-</td>';
				}
		
				echo '<td>'.$mostra['nota'].'</td>';
				
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td>'.$banco['nome']. "|".substr($formapag['nome'],0,10).'</td>';
				if(empty($mostra['imprimir'])){
					echo '<td align="center">-</td>';
				}else{
					echo '<td align="center">*</td>';
				}
		
				if(!empty($mostra['remessa']) ){
					echo '<td align="center">'.substr($mostra['remessa'],0,2).'</td>';
					echo '<td align="center">'.substr($mostra['retorno'],0,1).'</td>';
				}else{
					echo '<td align="center">-</td>';
					echo '<td align="center">-</td>';
				}

				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/receber/receber-baixar&receberNumero='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';

				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/receber/receber-editar&receberEnviar='.$mostra['id'].'">
							<img src="ico/email.png" alt="Aviso" title="Aviso-Email" class="tip" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="../cliente/painel2.php?execute=boleto/emitir-boleto&boletoId='.$mostra['id'].'" target="_blank">
							<img src="ico/boleto.png" alt="Boleto" title="Boleto" class="tip" />
              			</a>
						</td>';		
						
				if(empty($mostra['link'])){
					echo '<td align="center">-</td>';
				}else{
					 echo '<td align="center">
						<a href="'.$mostra['link'] .'" target="_blank">
							<img src="ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
				      </td>';
				}
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Visualizar" title="Contrato Visualizar"  />
							</a>
						  </td>';	

			echo '</tr>';
		 endforeach;

		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Valor Total : ' .  converteValor($valor_total) . '</td>';
          echo '</tr>';
		 echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
         echo '</tfoot>';
		 
		 echo '</table>';

			if(isset($_POST['pesquisar'])){
				$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND status='Em Aberto' ORDER BY vencimento ASC");
				if(isset($contratoTipo)){
					$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND contrato_tipo='$contratoTipo' AND status='Em Aberto' ORDER BY vencimento ASC");
				}
			}else{
				 $link = 'painel.php?execute=suporte/receber/receber&pag=';
				pag('receber',"WHERE vencimento>='$data1' AND status='Em Aberto'", $maximo, $link, $pag);
			}

		} //FIM DO $LEITURA
		
		?>
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

			<?php
                $mes = date('m/Y');
                $mesano = explode('/',$mes);
                $receitas = soma('receber',"WHERE Month(vencimento)='$mesano[0]' AND 
                                        Year(vencimento)='$mesano[1]'",'valor');
                $receitas_aberta = soma('receber',"WHERE Month(vencimento)='$mesano[0]' AND 
                                        Year(vencimento)='$mesano[1]' AND 
                                        status='Em Aberto'",'valor');
                $receitas_paga = $receitas-	$receitas_aberta;	
			?>

            <section>
            <div class="row">   
              <div class="col-md-12"> 
                <div class="col-md-8">   
                     <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Receita - <?php echo $mes;?></span>
                              <span class="info-box-number"><?php echo converteValor($receitas);?></span>
                              <div class="progress">
                              <?php
                                 $percentual=($receitas_paga/$receitas)*100;
                                 $percentual=intval($percentual);
                                ?>	
                                <div class="progress-bar" style="width: <?php echo $percentual;?>%"></div>
                              </div>
                              <span class="progress-description">
                                 <?php echo $percentual. '%  Pagos R$ '. converteValor($receitas_paga) . '   || Saldo a Receber R$ '. converteValor($receitas_aberta) ;?>
                              </span>
                            </div><!-- /.info-box-content -->
                      </div><!-- /.info-box -->
                  </div> <!-- /.col-md-6 -->
                   </div> <!-- /.col-md-12 ---->
                 </div> <!-- /.row ---->
            </section><!-- /.content -->

</div><!-- /.content-wrapper -->