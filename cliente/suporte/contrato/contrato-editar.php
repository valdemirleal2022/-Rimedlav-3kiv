<?php 
		
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autCliente']['id'])){
			header('Location: painel.php');	
		}	
	}
	 	
		$clienteId = $_SESSION['autCliente']['id'];
		$readCliente = read('cliente',"WHERE id = '$clienteId'");
		if(!$readCliente){
				header('Location: painel.php?execute=suporte/naoEncontrado');
		}
		foreach($readCliente as $cliente);

		if(!empty($_GET['contratoVisualizar'])){
			$contratoId = $_GET['contratoVisualizar'];
		}

		if(!empty($contratoId)){
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readContrato as $edit);
			$contratoTipoId = $edit['contrato_tipo'];
			$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
			
			$clienteId =  $edit['id_cliente'];
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
					header('Location: painel.php?execute=suporte/naoEncontrado');
			}
			foreach($readCliente as $cliente);

		}


		$_SESSION['aba']='1';
	
?>

<div class="content-wrapper">
     <section class="content-header">
         <h1>Contrato</h1>
         <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="#">Contrato</a></li>
             <li class="active">Visualizar</li>
          </ol>
      </section>
      
	<section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $cliente['nome']; ?><br></small></h3> </br>
 
				  <h3 class="box-title"><small><?php echo $cliente['endereco'].','.$cliente['numero'].' '.$cliente['complemento']; ?></small></h3></br>
	      	 
				  <h3 class="box-title"><small><?php echo $contratoTipo['nome']; ?></small></h3>
	      	</div><!-- /.box-header -->
		  
	<div class="box-body table-responsive">
	
	
	<div id="abas">

		<div class="nav-tabs-custom">

			<ul class="nav nav-tabs">
				<li class="<?php echo ($_SESSION['aba']=='1' ? " active " : " " );?>"><a href="#aba-1" data-toggle="tab">Ordem de Serviço</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='2' ? " active " : " " );?>"><a href="#aba-2" data-toggle="tab">Recebimento</a>
				</li>
				<li class="<?php echo ($_SESSION['aba']=='3' ? " active " : " " );?>"><a href="#aba-3" data-toggle="tab">Atendimento</a>
				</li>

			</ul>
			
			
	<!-- /.ABAS -->

	<!-- /.CONTRATO -->
	<div class="tab-content"> 
				
		<!-- /.ABAS 01 -->
		<div class="tab-pane <?php echo ($_SESSION['aba']=='1' ? " active " : " " );?>" id="aba-1">
			<!-- /.ORDEM DE SERVIÇO -->
		
			<div class="box-header with-border">
                  <h3 class="box-title">Ordem de Serviço</h3>
             </div><!-- /.box-header -->
        
      
          <div class="box-body table-responsive">  
           <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
         
			<?php 
					
			$leituraOrdem = read('contrato_ordem',"WHERE id AND id_contrato='$contratoId' AND status='13' AND nao_coletada<>'1' ORDER BY data DESC, hora ASC");
	
			if($leituraOrdem){
					
				echo '<table class="table table-hover">	
							<tr class="set">
							<td align="center">ID</td>
							<td align="center">Resíduo</td>
							<td align="center">Coleta</td>
							<td align="center">Quantidade</td>
							<td align="center">Data</td>
							<td align="center">Hora Rota</td>
							<td align="center">Status</td>
							<td align="center" colspan="5">Gerenciar</td>
						</tr>';
				foreach($leituraOrdem as $ordem):
	 			
				echo '<tr>';
				
					echo '<td align="center">'.$ordem['id'].'</td>';

					$tipoColetaId = $ordem['tipo_coleta1'];
					$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

					$residuoId = $coleta['residuo'];
					$residuo = mostra('contrato_tipo_residuo',"WHERE id ='$residuoId'");

					echo '<td align="left">'.$residuo['nome'].'</td>';
					echo '<td align="left">'.$coleta['nome'].'</td>';
					echo '<td align="center">'.$ordem['quantidade1'].'</td>';
					echo '<td>'.converteData($ordem['data']).'</td>';
					echo '<td>'.$ordem['hora'].'</td>';

					$statusId = $ordem['status'];
					$status = mostra('contrato_status',"WHERE id ='$statusId'");
					echo '<td>'.$status['nome'].'</td>';

						// imprimir ordem
					echo '<td align="center">
							<a href="painel.php?execute=suporte/ordem/ordem-servico&ordemImprimir='.$ordem['id'].'" target="_blank">
								<img src="../admin/ico/imprimir.png" alt="Imprimir" title="Imprimir"  />
							</a>
						 </td>';	

				echo '</tr>';
			 endforeach;
		
		 echo '</table>';

		}
				?>
      		 
      		        </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->

		</div><!-- /.tab-pane3 -->
			
			
		<!-- /.RECIBIMENTO -->
				
		<div class="tab-pane <?php echo ($_SESSION['aba']=='2' ? " active " : " " );?>" id="aba-2">
			 
			 
			<div class="box-header with-border">
                  <h3 class="box-title">Recebimento</h3>
            </div><!-- /.box-header -->

		  	<div class="box-body table-responsive">  
          	 <div class="box-body table-responsive data-spy='scroll'">
     			<div class="col-md-12 scrool"> 
			  
		 	<?php 
			 $leituraReceber = read('receber',"WHERE id_contrato='$contratoId'");
			if($leituraReceber){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Valor</td>
					<td align="center">Emissão</td>
					<td align="center">Vencimento</td>
					<td align="center">Pagamento</td>
					<td align="center">Status</td>
					<td align="center">FormPag/Banco</td>
					<td align="center">Nota</td>
					<td align="center" colspan="3">Boleto</td>
				</tr>';
				foreach($leituraReceber as $receber):
	 			
					echo '<tr>';
				
				echo '<td align="right">'.converteValor($receber['valor']).'</td>';
					echo '<td align="center">'.converteData($receber['emissao']).'</td>';
					echo '<td align="center">'.converteData($receber['vencimento']).'</td>';
					if($receber['status']<>'Em Aberto'){
					   echo '<td align="center">'.converteData($receber['pagamento']).'</td>';
					  }else{
						echo '<td align="center">-</td>';  
					}

					echo '<td align="center">'.$receber['status'].'</td>';
					$bancoId=$receber['banco'];
					$banco = mostra('banco',"WHERE id ='$bancoId'");
					$formpagId=$receber['formpag'];
					$formapag = mostra('formpag',"WHERE id ='$formpagId'");
					echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';

					echo '<td align="center">'.$receber['nota'].'</td>';

					if($receber['status']=='Em Aberto'){

						 echo '<td align="center">
							<a href="painel2.php?execute=boleto/emitir-boleto&boletoId='.$receber['id'].'" target="_blank">
								<img src="../admin/ico/imprimir.png" alt="Imprimir Boleto" title="Imprimir Boleto" class="tip" />
							</a>
						  </td>';

						   if(empty($receber['link'])){
						echo '<td align="center">-</td>';
					}else{
						 echo '<td align="center">
							<a href="'.$receber['link'] .'" target="_blank">
								<img src="../admin/ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
						  </td>';
					}


					  }else{
						 echo '<td align="center"> - </td>';
						 echo '<td align="center"> - </td>';
					echo '</tr>';

					}

				echo '</tr>';
	
		 endforeach;
		
		 echo '</table>';

		}

               ?>   
               
                </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive --> 
       		 </div><!--/box-body table-responsive-->
               

		</div><!-- /.tab-pane4 -->	

	
<!-- /.Atendimento -->
				
		<div class="tab-pane <?php echo ($_SESSION['aba']=='3' ? " active " : " " );?>" id="aba-3">
		
		 	<div class="box-header">
			   	<a href="painel.php?execute=suporte/atendimento/pedido-novo" class="btnnovo"><img src="../admin/ico/novo.png" alt="Novo" title="Novo" class="tip" />Novo Atendimento
				 </a>
          	</div><!-- /.box-header -->
		
			<?php 
    
				$leitura = read('pedido',"WHERE id_contrato='$contratoId' AND cliente_solicitou='1' ORDER BY id DESC");
				if($leitura){
						echo '<table class="table table-hover">
								<tr class="set">
								<td align="center">ID</td>
								<td>Usuário</td>
								<td>Dt Solicitação</td>
								<td>Solicitação</td>
								<td align="center">Status</td>
								<td>Dt da Solução</td>
								<td>Solução</td>
								<td align="center" colspan="5">Gerenciar</td>
							</tr>';
					foreach($leitura as $mostra):
						echo '<tr>';
							echo '<td>'.$mostra['id'].'</td>';
							echo '<td>'.substr($mostra['usuario'],0,15).'</td>';
							echo '<td>'.date('d/m/Y',strtotime($mostra['data_solicitacao'])).'</td>';
							echo '<td>'.$mostra['solicitacao'].'</td>';
							echo '<td>'.$mostra['status'].'</td>';
							if($mostra['status']<>'Aguardando'){
							   echo '<td>'.date('d/m/Y',strtotime($mostra['data_solucao'])).'</td>';
							  }else{
								echo '<td align="center">-</td>';  
							}

							echo '<td>'.$mostra['solucao'].'</td>';
							echo '<td align="center">
							<a href="painel.php?execute=suporte/atendimento/pedido-editar&suporteEditar='.$mostra['id'].'">
										<img src="../admin/ico/visualizar.png" alt="Visualizar" title="Visualizar" class="tip" />
									</a>
								  </td>';

						echo '</tr>';
					 endforeach;
					 echo '</table>';

					}
				  ?>   
                   
                 </div><!--/col-md-12 scrool-->   
				</div><!-- /.box-body table-responsive -->
       		 </div><!--/box-body table-responsive-->

		</div><!--/aba-9-->
	  </div><!--/nav-tabs-custom-->
	 </div> 	<!--/abas-->
	</div><!--/nav-tabs-custom-->
	
</div>
<!--/box box-default-->


</section><!-- /.content -->	
</div><!-- /.content-wrapper -->
           