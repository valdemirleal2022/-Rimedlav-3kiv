<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autConsultor']['id'])){
			header('Location: painel.php');	
		}	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

	$consultorId=$_SESSION['autConsultor']['id'];

?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Orçamento Cancelado</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Orçamentos</a></li>
           <li><a href="#">Cancelados</a></li>
         </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
   
        <div class="box-body table-responsive">
       	 
                  
	<?php 
  
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '30';
	$inicio = ($pag * $maximo) - $maximo;
			
	
	$valor_total = soma('cadastro_visita',"WHERE id AND status='17' AND consultor='$consultorId'",'orc_valor');
 	$total = conta('cadastro_visita',"WHERE id AND status='17' AND consultor='$consultorId'");
	
	$leitura = read('cadastro_visita',"WHERE id AND status='17' AND consultor='$consultorId' ORDER BY orc_solicitacao DESC LIMIT $inicio,$maximo");
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Solicitação</td>
					<td align="center">Orçamento/Hora</td>
					<td align="center">Indicação</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Vendedor</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			

				echo '<td>'.substr($mostra['nome'],0,15).'</td>';
				echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_solicitacao']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_data']).'/'.$mostra['orc_hora'].'</td>';
	 
				$indicacaoId = $mostra['indicacao'];
				$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
				echo '<td>'.$indicacao['nome'].'</td>';
				
				echo '<td>'.substr($mostra['orc_residuo'],0,15).'</td>';
				
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.$consultor['nome'].'</td>';
				
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>';
				
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoVisualizar='.$mostra['id'].'">
					<img src="../admin/ico/visualizar.png" title="Visualizar" />
					</a>
					</td>';
	
				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
					echo '</tr>';
						   
					echo '<tr>';
					echo '<td colspan="10">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
					echo '</tr>';
			 echo '</tfoot>';

		 echo '</table>';
		 $link = 'painel.php?execute=suporte/orcamento/orcamento-cancelados&pag=';
		 pag('cadastro_visita',"WHERE status='17' ORDER BY orc_solicitacao DESC", $maximo, $link, $pag);
	
	  }   
	?>
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