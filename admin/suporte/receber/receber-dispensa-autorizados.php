<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}


	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$_SESSION['contratoTipo'] = $_POST['contrato_tipo'];
		echo '<script type="text/javascript">';
	echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receita-dispensa-autorizado-pdf");';
		echo '</script>';
	}

	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$_SESSION['contratoTipo'] = $_POST['contrato_tipo'];
	    header('Location: ../admin/suporte/relatorio/relatorio-receita-dispensa-autorizado-excel.php');
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if(isset($_POST['pesquisar'])){
		$contratoTipo = $_POST['contrato_tipo'];
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	}
	
	$leitura = read('receber',"WHERE dispensa='1' AND dispensa_autorizacao='1' AND dispensa_data>='$data1' AND dispensa_data<='$data2' ORDER BY dispensa_data ASC");


	if (!empty($contratoTipo)) {
		$leitura = read('receber',"WHERE dispensa='1' AND dispensa_autorizacao='1' AND dispensa_data>='$data1' AND dispensa_data<='$data2' AND contrato_tipo='$contratoTipo'
		ORDER BY dispensa_data ASC");
	}

	

	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Dispensa de Credito - Autorizados</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Recebimento</a>
     	<li class="active">Dispensa de Credito </li>
     </ol>
 </section>

<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
		 <div class="box-header">
             <div class="row">
          	  
             	   <div class="col-xs-10 col-md-7 pull-right">
                      <form name="form-pesquisa" method="post" class="form-inline " role="form">
						  
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
                        	 <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>   
                       </div><!-- /.input-group -->  
                        <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>  
                        </div><!-- /.input-group -->
                          <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>  
                        </div><!-- /.input-group -->                              
                    </form> 
              </div><!-- /col-xs-6 col-md-4 pull-right-->
				 
   		 </div><!-- /row-->	 
    </div><!-- /box-header-->
         
	       
  <div class="box-body table-responsive">
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
    
	<?php 
			
			
	$total = 0;
	$valor_total = 0;

	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Vencimento</td>
					<td align="center">Dispensa</td>
					<td align="center">Motivo</td>
					<td align="center">FormPag/Banco</td>
					<td align="center">Situação</td>
					<td align="center">Status</td>
					<td align="center">Autorização</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
				$valor_total = $valor_total + $mostra['valor'];
				$total = $total+1;
		
				echo '<td align="center">'.$mostra['id'].'</td>';
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
				echo '<td>'.substr($cliente['nome'],0,15).'</td>';
	
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['dispensa_data']).'</td>';
		
		
				$motivoId=$mostra['dispensa_motivo'];
				$motivo = mostra('motivo_dispensa',"WHERE id ='$motivoId'");
				echo '<td align="center">'.$motivo['nome'] .'</td>';
								
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.substr($banco['nome'],0,10). "|".substr($formapag['nome'],0,10).'</td>';
		
		
				echo '<td align="center">'.$mostra['status'].'</td>';
				
				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											alt="Contrato Ativo" title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											alt="Contrato Suspenso" title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" 
										alt="Contrato Cancelad" title="Contrato Cancelado" /> </td>';
				}else{
					echo '<td align="center">-</td>';
				}
				
				if($mostra['dispensa_autorizacao']=='1'){
					echo '<td align="center">Autorizado</td>';
				}elseif($mostra['dispensa_autorizacao']=='2'){
					echo '<td align="center">Não Autorizado</td>';
				}elseif($mostra['dispensa_autorizacao']=='0'){
						echo '<td align="center">Aguardando</td>';
				}

 				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/receber/receber-baixar&receberNumero='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
		
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png"  title="Contrato Visualizar"  />
							</a>
						  </td>';	
		
			echo '</tr>';
		 endforeach;
		 
			 
		
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="15">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="15">' . 'Total Valor R$ : ' . number_format($valor_total,2,',','.') . '</td>';
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