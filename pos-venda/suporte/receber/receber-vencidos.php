<?php 

	if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autpos_venda']['id'])){
				header('Location: painel.php');	
			}	
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

	$data1 = date("Y-m-d", strtotime("-1 day"));
	$leitura = read('receber',"WHERE vencimento<='$data1' AND status='Em Aberto' ORDER BY id_cliente ASC, vencimento DESC");

	if(isset($_POST['pesquisar_nome'])){
		$pesquisa=strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		if(!empty($pesquisa)){
			$cliente =mostra('cliente',"WHERE id AND (
													nome LIKE '%$pesquisa%' OR
													nome_fantasia LIKE '%$pesquisa%' OR
													endereco LIKE '%$pesquisa%' OR 
													email LIKE '%$pesquisa%' OR 
													telefone LIKE '%$pesquisa%' OR
													celular LIKE '%$pesquisa%' OR
													contato LIKE '%$pesquisa%' OR
													cnpj LIKE '%$pesquisa%' OR
													cpf LIKE '%$pesquisa%'
													) 
													ORDER BY nome ASC");  
		}
		$clienteId = $cliente['id'];
		
		$leitura = read('receber',"WHERE vencimento<='$data1' AND status='Em Aberto' AND id_cliente='$clienteId' ORDER BY id_cliente ASC, vencimento DESC");
		
	}

?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Vencidos</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Receita</a>
     	<li class="active">Vencidos</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
		 <div class="box-header">
             <div class="row">
				 
		
                     <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="nome" class="form-control input-sm" placeholder="Cliente">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_nome" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->


		    </div><!-- /row-->	 
         </div><!-- /box-header-->

    <div class="box-body table-responsive">
		
	<?php 
	
	$valor_total=0;
	$total = 0;
		
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Contrato</td>
					<td align="center">Nome</td>
					<td align="center">Bairro</td>
					<td align="center">Telefone</td>
					<td align="center">Valor</td>
					<td align="center">Vencimento</td>
					<td align="center">FormPag/Banco</td>
					<td align="center">Status</td>
					<td>BI</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
	foreach($leitura as $mostra):
			
			$contratoId = $mostra['id_contrato'];
			$contrato = mostra('contrato',"WHERE id ='$contratoId'");

			$listar='SIM';

			if($contrato['pos_venda']<>$pos_vendaId) {
			  $listar='NAO';
			}
		
			if($mostra['protesto']=='1') {
			  $listar='NAO';
			}
		
			if($listar=='SIM') {
				
				$valor_total+=$mostra['valor'];
				$total++;
	  
		 	echo '<tr>';
				//echo '<td align="center">'.$mostra['id'].'</td>';
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				echo '<td>'.substr($mostra['id'],0,6).'</td>';
			 
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td>'.substr($cliente['bairro'],0,15).'</td>';
				echo '<td>'.substr($cliente['telefone'],0,15).'</td>';
	
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';

				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';

				if($contrato['status']==5){
					echo '<td align="center"><img src="../admin/ico/contrato-ativo.png" 
											alt="Contrato Ativo" title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="../admin/ico/contrato-suspenso.png" 
											alt="Contrato Suspenso" title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="../admin/ico/contrato-cancelado.png" 
										alt="Contrato Cancelad" title="Contrato Cancelado" /> </td>';
				}else{
					echo '<td align="center">!</td>';
				}
		 
				if(empty($mostra['imprimir'])){
					echo '<td align="center">-</td>';
				}else{
					echo '<td align="center">*</td>';
				}
	
						
				if( $mostra[ 'enviar_boleto_correio' ]<>'1'){
						echo '<td align="center">
									<a href="painel.php?execute=suporte/receber/receber-editar&receberEnviar='.$mostra['id'].'">
										<img src="../admin/ico/email.png" alt="Aviso" title="Aviso-Email" />
									</a>
									</td>';
					}else{
								echo '<td align="center">
									<a href="#">
										<img src="../admin/ico/correios.png" alt="Enviar por Correio" title="Enviar por Correio" />
									</a>
									</td>';
				}

				echo '<td align="center">
							<a href="../cliente/painel2.php?execute=boleto/emitir-boleto&boletoId='.$mostra['id'].'" target="_blank">
								<img src="../admin/ico/boleto.png" alt="Boleto" title="Boleto" class="tip" />
							</a>
							</td>';	
	
					if(empty($mostra['link'])){
									echo '<td align="center">-</td>';
								}else{
									 echo '<td align="center">
										<a href="'.$mostra['link'] .'" target="_blank">
											<img src="../admin/ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
									  </td>';
								}
								 
					echo '<td align="center">
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$mostra['id_cliente'].'">
								<img src="../admin/ico/visualizar.png" alt="Visualizar Cliente" title="Visualizar Cliente" />
							</a>
						  </td>';
		

			echo '</tr>';
			
	     }
		
		 endforeach;
		 
			 
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="14">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="14">' . 'Total Valor R$ : ' . converteValor($valor_total) . '</td>';
                        echo '</tr>';
  
          echo '</tfoot>';
		 
		 
		 echo '</table>';

		}
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

  
</div><!-- /.content-wrapper -->