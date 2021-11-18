<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
				   
	$valor_total = soma('cadastro_visita',"WHERE id AND status='4' AND iniciando='1'",'orc_valor');
 	$total = conta('cadastro_visita',"WHERE id AND status='4' AND iniciando='1'");
	
	$leitura = read('cadastro_visita',"WHERE id AND status='4' AND iniciando='1' ORDER BY orc_aprovacao ASC");

?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Aprovados</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="painel.php?execute=suporte/orcamento/orcamento">Orçamentos</a></li>
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
  
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Orçamento</td>
					<td align="center">Aprovação</td>
					<td align="center">Indicação</td>
					<td align="center">Consultor</td>
					<td align="center">Tipo de Resíduo</td>
		 
					<td align="center">Status</td>
					<td align="center">Usuario</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			

				echo '<td>'.substr($mostra['nome'],0,15).'</td>';
		
				echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				 
				echo '<td align="center">'.converteData($mostra['orc_data']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_aprovacao']).'</td>';
	 
				$indicacaoId = $mostra['indicacao'];
				$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
				echo '<td>'.$indicacao['nome'].'</td>';
		
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.substr($consultor['nome'],0,12).'</td>';
		
				echo '<td>'.substr($mostra['orc_residuo'],0,15).'</td>';
				
					
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>';
		 
				echo '<td>'.$mostra['usuario'].'</td>';
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
					<img src="ico/editar.png" alt="Editar" title="Editar" />
					</a>
					</td>';
	
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&contratoIniciando='.$mostra['id'].'">
							<img src="../admin/ico/aprovado.png"  title="Iniciar Contrato" />
              			</a>
						</td>';

				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
					echo '</tr>';
						   
					echo '<tr>';
					echo '<td colspan="11">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
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
       
    </div><!-- /.box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->