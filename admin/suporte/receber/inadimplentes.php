<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}


	$contratoTipo ='';

	$dataHoje = date("Y-m-d", strtotime("-3 day"));
	$leitura = read('receber',"WHERE vencimento<='$dataHoje' AND status<>'Baixado' ORDER BY id_contrato ASC, vencimento ASC");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$contratoTipoId = $_POST['contrato_tipo'];
		$statusId = $_POST[ 'status' ];
		$_SESSION['status']=$statusId;
		$_SESSION['contratoTipo']=$contratoTipoId;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receita-inadimplentes-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$contratoTipoId = $_POST['contrato_tipo'];
		$statusId = $_POST[ 'status' ];
		$_SESSION['status']=$statusId;
		$_SESSION['contratoTipo']=$contratoTipoId;
		header( 'Location: ../admin/suporte/relatorio/relatorio-receita-inadimplentes-excel.php' );
	}


	if(isset($_POST['pesquisar'])){
		$contratoTipoId = $_POST['contrato_tipo'];
		$statusId = $_POST[ 'status' ];
	}

	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Inadimplentes</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Receita</a>
     	<li class="active">Inadimplentes</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
		
       <div class="box-header"> 
            <?php echo $_SESSION['retorna'];
				unset($_SESSION['retorna']);
			?> 
       </div><!-- /box-header-->    

    	<div class="box-header">
               <div class="row">
                   <div class="col-md-12">  
                   
                    <div class="col-xs-10 col-md-7 pull-right">
						
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                                            
                           
						 <div class="form-group pull-left">
							  <select name="status" class="form-control input-sm" >
									<option value="">Selecione Status</option>
									<?php 
										$readContrato = read('contrato_status',"WHERE id AND tipo='1'");
										if(!$readContrato){
												echo '<option value="">Nao temos status no momento</option>';	
											}else{
												foreach($readContrato as $mae):
												   if($statusId == $mae['id']){
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
								<select name="contrato_tipo" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('contrato_tipo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($contratoTipoId == $mae['id']){
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
                            <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                         </div>  <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                         </div>   <!-- /.input-group -->
                     </form> 
                  </div><!-- /col-xs-4-->
		 
		
    		</div><!-- /col-xs-10 col-md-7 pull-right-->   
	     </div><!-- /row-->	 
      </div><!-- /box-header-->
       
    <div class="box-body table-responsive">
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  

	<?php 
			
	$totalListado = 0;
	$totalAtivo = 0;
	$totalSuspenso = 0;	
	$totalCancelado = 0;
	$totalContratoJuridico = 0;
			
	$valorListado = 0;
	$valorAtivo = 0;
	$valorSuspenso = 0;	
	$valorCancelado = 0;
	$valorContratoJuridico = 0;
	
	$totalJuridico = 0;
	$totalProtesto = 0;	
	$totalSerasa = 0;
	$totalEmAberto = 0;
			
	$valorJuridico = 0;
	$valorProtesto = 0;	
	$valorSerasa = 0;
	$valorEmAberto = 0;
			
	$totalSemStatus = 0;
	$valorSemStatus = 0;
			
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Contrato</td>
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Tipo</td>
					<td align="center">Valor</td>
					<td align="center">Vencimento</td>
					<td align="center">FormPag/Banco</td>
					<td align="center">Status</td>
					<td align="center">Situação</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
	foreach($leitura as $mostra):
		
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		$listar='NAO';
 		
 		 if($mostra['status']=='Em Aberto'){
 			$listar='SIM';
 		}
		 if($mostra['status']=='Juridico'){
 			$listar='SIM';
 		}

		if($mostra['serasa']=='1'){
		  $listar='SIM';
		}
		if($mostra['juridico']=='1'){
		  $listar='SIM';
		}
		if($mostra['protesto']=='1'){
		  $listar='SIM';
		}
		

		if(!empty($statusId) AND $listar=='SIM'){
			$listar='NAO';
			if($contrato['status']==$statusId){
				$listar='SIM';
			}
		}
		
		if(!empty($contratoTipoId) AND $listar=='SIM'){
			$listar='NAO';
			if($contrato['tipo']==$contratoTipoId){
				$listar='SIM';
			}
		}
		
		if($listar=='SIM'){
	
		 	echo '<tr>';
			
				$totalListado = $totalListado+1;
				$valorListado = $valorListado+$mostra['valor'];
				
				echo '<td align="center">'.$mostra['id_contrato'].'</td>';	
				echo '<td align="center">'.$mostra['id'].'</td>';
			
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
			
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
				if($cliente['tipo']==4){
					echo '<td>'.substr($cliente['nome'],0,12).' <img src="ico/premium.png"/></td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,12).'</td>';
				}
			
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
			
				$TipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$TipoId'");
				echo '<td align="center">'.$contratoTipo['nome'].'</td>';
			
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';

				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';
			
				if($mostra['status']=='Em Aberto'){
					$totalEmAberto = $totalEmAberto+1;
					$valorEmAberto = $valorEmAberto+$mostra['valor'];
				}

				if($mostra['serasa']=='1'){
				  $totalSerasa = $totalSerasa+1;
				  $valorSerasa = $valorSerasa+$mostra['valor'];
				}
			
				if($mostra['juridico']=='1'){
				  $totalJuridico = $totalJuridico+1;
				  $valorJuridico = $valorJuridico+$mostra['valor'];
				}
			
				if($mostra['protesto']=='1'){
				  $totalProtesto = $totalProtesto+1;
				  $valorProtesto = $valorProtesto+$mostra['valor'];
				}
					
				// 5 Contrato Ativo 
				// 6 Contrato Suspensos
				// 9 Contrato Cancelado
				// 10 Ação JUDICIAL
			
				if($contrato['status']==5) {
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											 title="Contrato Ativo" />  </td>';
					$totalAtivo = $totalAtivo+1;
				  	$valorAtivo = $valorAtivo+$mostra['valor'];
					
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
					$totalSuspenso = $totalSuspenso+1;
				  	$valorSuspenso = $valorSuspenso+$mostra['valor'];
					
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" 
										 title="Contrato Cancelado" /> </td>';
					$totalCancelado = $totalCancelado+1;
				  	$valorCancelado = $valorCancelado+$mostra['valor'];
					
				}elseif($contrato['status']==10){
					echo '<td align="center"><img src="ico/juridico.png" 
										 title="Contrato no Juridico" /> </td>';
					$totalContratoJuridico = $totalContratoJuridico+1;
				  	$valorContratoJuridico = $valorContratoJuridico+$mostra['valor'];
					
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
					$totalSemStatus = $totalSemStatus+1;
					$valorSemStatus = $valorSemStatus+$mostra['valor'];
				}

				echo '<td align="center">'.$mostra['status'].'</td>';
			

 				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-baixar&receberNumero='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" />
              			</a>
				      </td>';
		
				if( $mostra[ 'enviar_boleto_correio' ]<>'1'){
					echo '<td align="center">
								<a href="painel.php?execute=suporte/receber/receber-editar&receberEnviar='.$mostra['id'].'">
									<img src="ico/email.png" alt="Aviso" title="Aviso-Email" />
								</a>
								</td>';
				}else{
						echo '<td align="center">
							<a href="#">
								<img src="ico/correios.png" alt="Enviar por Correio" title="Enviar por Correio" />
							</a>
							</td>';
				}
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Visualizar" title="Contrato Visualizar"  />
							</a>
						  </td>';	
		
			echo '</tr>';
			
		}
		
		 endforeach;

		 echo '<tfoot>';
                      
		
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Serasa : ' .  $totalSerasa . ' - Valor Total : ' .  converteValor($valorSerasa) . ' </td>';
                        echo '</tr>';
                       
     					echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Juridico : ' .  $totalJuridico . ' - Valor Total : ' .  converteValor($valorJuridico) . ' </td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Protesto : ' .  $totalProtesto . ' - Valor Total : ' .  converteValor($valorProtesto) . ' </td>';
                        echo '</tr>';
		
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Em Aberto : ' .  $totalEmAberto . ' - Valor Total : ' .  converteValor($valorEmAberto) . ' </td>';
                        echo '</tr>';
		
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Listado : ' .  $totalListado . ' - Valor Total : ' .  converteValor($valorListado) . ' </td>';
                        echo '</tr>';
		
		
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Contrato Ativo : ' .  $totalAtivo . ' - Valor Total : ' .  converteValor($valorAtivo) . ' </td>';
                        echo '</tr>';
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Contrato Suspenso : ' .  $totalSuspenso . ' - Valor Total : ' .  converteValor($valorSuspenso) . ' </td>';
                        echo '</tr>';
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Contrato Cancelado : ' .  $totalCancelado . ' - Valor Total : ' .  converteValor($valorCancelado) . ' </td>';
                        echo '</tr>';
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Contrato Juridico : ' .  $totalContratoJuridico . ' - Valor Total : ' .  converteValor($valorContratoJuridico) . ' </td>';
                        echo '</tr>';
						echo '<td colspan="15" align="right">' . 'Total sem Status : ' .  $totalSemStatus . ' - Valor Total : ' .  converteValor($ValorSemStatus) . ' </td>';
                        echo '</tr>';
		
						$total=$totalAtivo+$totalSuspenso+$totalCancelado+$totalContratoJuridico+$totalSemStatus;
						$valor_total=$valorAtivo+$valorSuspenso+$valorCancelado+$valorContratoJuridico+$ValorSemStatus;
		
						echo '<tr>';
                        echo '<td colspan="15" align="right">' . 'Total Listado : ' .  $total . ' - Valor Total : ' .  converteValor($valor_total) . ' </td>';
                       	echo '<tr>';
		
  
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
     
     
       </div><!-- /.box  -->
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->