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
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receita-refaturado-pdf");';
		echo '</script>';
	}

	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$_SESSION['contratoTipo'] = $_POST['contrato_tipo'];
	    header('Location: ../admin/suporte/relatorio/relatorio-receita-refaturado-excel.php');
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if(isset($_POST['pesquisar'])){
		$contratoTipo = $_POST['contrato_tipo'];
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	}
	
	$leitura = read('receber',"WHERE refaturar='1' AND refaturamento_autorizacao<>'0' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' ORDER BY refaturamento_data ASC");

	if (!empty($contratoTipo)) {
		$leitura = read('receber',"WHERE refaturar='1' AND refaturamento_autorizacao<>'0' AND refaturamento_data>='$data1' AND refaturamento_data<='$data2' AND contrato_tipo='$contratoTipo'
		ORDER BY refaturamento_data ASC");
	}


	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Refaturamento - Autorizados</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Recebimento</a>
     	<li class="active">Refaturar</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		 
		<div class="box-header">
          <div class="row">  
          	  <div class="col-xs-10 col-md-12 pull-left">
          	  
          	  
             	   <div class="col-xs-10 col-md-8 pull-right">
                     
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

      </div><!-- /col-md-12-->
    </div><!-- /row-->   
   </div><!-- /box-header-->    

	       
  <div class="box-body table-responsive">
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  

	<?php 
			
	$valor_total = 0;
	$total = 0;
		
	if($leitura){
		
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Tipo Contrato</td>
					<td align="center">Vl Atual</td>
					<td align="center">Vl Refaturar</td>
					<td align="center">Faturado</td>
					<td align="center">Motivo</td>
					<td align="center">Autorização</td>
					<td align="center">Refaturado</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
		
	foreach($leitura as $mostra):
			
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	
			$valor_total = $valor_total + $mostra['valor'];
			$total = $total+1;
	 
		 	echo '<tr>';
		
				echo '<td align="center">'.$mostra['id'].'</td>';
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			
				if($cliente['tipo']==4){
					echo '<td>'.substr($cliente['nome'],0,17).' <img src="ico/premium.png"/></td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,17).'</td>';
				}
		
				$contratoTipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
			
				echo '<td align="right">'.converteValor($mostra['valor_anterior']).'</td>';
				echo '<td align="right">'.converteValor($mostra['refaturamento_valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['refaturamento_data']).'</td>';
			
				$motivoId=$mostra['refaturamento_motivo'];
				$motivo = mostra('motivo_refaturamento',"WHERE id ='$motivoId'");
		
				echo '<td align="center">'.$motivo['nome'].'</td>';
				
				if($mostra['refaturamento_autorizacao']=='1'){
					echo '<td align="center">Autorizado</td>';
				}elseif($mostra['refaturamento_autorizacao']=='2'){
					echo '<td align="center">Não Autorizado</td>';
				}elseif($mostra['refaturamento_autorizacao']=='0'){
						echo '<td align="center">Aguardando</td>';
				}
		
		
				if($mostra['refaturado']=='1'){
					echo '<td align="center">Sim</td>';
				}else{
					echo '<td align="center">Não</td>';
				}
			
				
					
				
 				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';

				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Visualizar" title="Contrato Visualizar"  />
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