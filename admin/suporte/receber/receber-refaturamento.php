<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$leitura = read('receber',"WHERE refaturar='1' AND refaturamento_autorizacao='0'");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Refaturamento</h1>
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

	       
   <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">

	<?php 
			
	$valor_total = 0;
	$total = 0;
		
	if($leitura){
		
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Vl Atual</td>
					<td align="center">Vl Refaturar</td>
					<td align="center">Faturamento</td>
					<td align="center">Motivo</td>
					<td align="center">Autorização</td>
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
				//echo '<td>'.substr($contrato['controle'],0,6).'</td>';
		
				$receberId=$mostra['id'];
				$cad['consultor']= $contrato['consultor'];
				update('receber',$cad,"id = '$receberId'");
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
			
				if($cliente['tipo']==4){
					echo '<td>'.substr($cliente['nome'],0,20).' <img src="ico/premium.png"/></td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				}
			
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
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