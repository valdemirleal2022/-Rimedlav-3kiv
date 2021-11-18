<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autConsultor']['id'])){
			header('Location: painel.php');	
		}	
	}

	$consultorId=$_SESSION['autConsultor']['id'];
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
      
  <section class="content-header">
     <h1>FollowUp </h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Orcamento</a>
     	<li class="active">FollowUp</li>
     </ol>
 </section>

<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
   
  
    <div class="box-body table-responsive">

	<?php 
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '50';
	$inicio = ($pag * $maximo) - $maximo;
	
	$valor_total = soma('cadastro_visita',"WHERE id AND status='3' AND consultor='$consultorId'",'orc_valor');
	$total = conta('cadastro_visita',"WHERE id AND status='3' AND consultor='$consultorId'");

	$leitura = read('cadastro_visita',"WHERE id AND status='3' AND consultor='$consultorId' 
				ORDER BY orc_data DESC, orc_hora ASC LIMIT $inicio,$maximo");
	if($leitura){
			 echo '<table class="table table-hover">	
					<tr class="set">
					<td>Id</td>
					<td>Nome</td>
					<td>Telefone</td>
					<td align="center">Consultor</td>
					<td align="center">Orçamento</td>
					<td align="center">Valor</td>
					<td align="center">Interaçao</td>
					<td align="center" colspan="6">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td align="left">'.substr($mostra['nome'],0,30).'</td>';
				echo '<td align="left">'.substr($mostra['telefone'],0,15).'</td>';
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td align="left">'.$consultor['nome'].'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['orc_data'])).'</td>';
				echo '<td align="right">'.(converteValor($mostra['orc_valor'])).'</td>';
		
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';

				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
			  				<img src="../admin/ico/editar.png" alt="Editar" title="Editar Orçamento" class="tip" />
              			</a>
				      </td>';
		
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEnviar='.$mostra['id'].'">
							<img src="../admin/ico/email.png" alt="Enviar Proposta" title="Enviar Proposta" class="tip" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="../cliente/painel2.php?execute=suporte/orcamento/imprimir-proposta&orcamentoId='.$mostra['id'].'" target="_blank">
							<img src="../admin/ico/imprimir.png" alt="imprimir Proposta" title="Imprimir Proposta" class="tip" />
              			</a>
						</td>';
		
			
			//	echo '<td align="center">
//						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoCancelar='.$mostra['id'].'">
//							<img src="../admin/ico/baixar.png" alt="Cancelar" title="Cancelar Orçamento"  />
//              			</a>
//						</td>';
		
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoAprovar='.$mostra['id'].'">
							<img src="../admin/ico/aprovado.png" alt="Aprovar Contrato" title="Aprovar Contrato" class="tip" />
              			</a>
						</td>';

		
			echo '</tr>';
			
		 endforeach;
		 
		  echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="13">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="13">' . 'Valor Tototal R$ : ' . number_format($valor_total,2,',','.') . '</td>';
                        echo '</tr>';
  
           echo '</tfoot>';
		 
		echo '</table>';
		 
		$link = 'painel.php?execute=suporte/orcamento/followup&pag=';
	     
		 pag('cadastro_visita',"WHERE id AND status='3' AND consultor='$consultorId' ORDER BY 
		 		orc_data DESC, orc_hora ASC", $maximo, $link, $pag);
		}
	?>
     
  		<div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?> 
       </div> <!-- /.box-footer -->
       
     </div> <!-- /.box-body table-responsive -->
       
  </div> <!-- /.col-md-12 -->
  </div> <!-- /.box box-default -->
 </div> <!-- /. row -->
 
</section> <!-- /.content -->

 </div> <!-- /.content-wrapper-->

 