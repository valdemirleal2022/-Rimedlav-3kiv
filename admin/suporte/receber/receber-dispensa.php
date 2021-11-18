<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$leitura = read('receber',"WHERE dispensa='1' AND dispensa_autorizacao<>'1'");
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Dispensa de Crédito</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Recebimento</a>
     	<li class="active">Dispensa de Crédito</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

	       
 <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
    
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
					<td align="center">Nota</td>
					<td align="center">FormPag/Banco</td>
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
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
	
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['dispensa_data']).'</td>';
		
				$motivoId=$mostra['dispensa_motivo'];
				$motivo = mostra('motivo_dispensa',"WHERE id ='$motivoId'");
				echo '<td align="center">'.$motivo['nome'] .'</td>';
		
				echo '<td align="center">'.$mostra['nota'].'</td>';
		 						
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';
				
				if($contrato['status']==5) {
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											 title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==7){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
										 title="Contrato Rescindo" /> </td>';
				}elseif($contrato['status']==19){
					echo '<td align="center"><img src="ico/contrato-suspenso-temporario.png" 
											 title="Contrato Suspenso Temporario" /> </td>';
				}elseif($contrato['status']==10){
					echo '<td align="center"><img src="ico/juridico.png" 
										 title="Contrato no Juridico" /> </td>';
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
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