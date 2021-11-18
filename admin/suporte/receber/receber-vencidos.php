<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	if (!isset($numero1)) {
		$numero1='3';
		$data1 = date("Y-m-d", strtotime("-360 day"));
		$data2 = date("Y-m-d", strtotime("-$numero1 day"));
	}else{
		$data1 = date("Y-m-d", strtotime("-360 day"));
		$data2 = date("Y-m-d", strtotime("-$numero1 day"));
	}


  	$data1 = date("Y-m-d", strtotime("-3 day"));
	$leitura = read('receber',"WHERE vencimento<='$data1' AND status='Em Aberto' ORDER BY vencimento DESC");


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$statusId = $_POST[ 'status' ];
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['status']=$statusId;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receita-vencidas-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$statusId = $_POST[ 'status' ];
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['status']=$statusId;
		header( 'Location: ../admin/suporte/relatorio/relatorio-receita-vencidas-excel.php' );
	}


	if(isset($_POST['pesquisar_numero'])){
		$receberId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		header('Location: painel.php?execute=suporte/receber/receber-baixar&receberNumero='.$receberId.'');
	}
	
	if(isset($_POST['pesquisar_nota'])){
		$notaId=strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
		header('Location: painel.php?execute=suporte/receber/receber-editar&receberNota='.$notaId.'');
	}

	if(isset($_POST['pesquisar'])){
		
		$numero1 = $_POST[ 'numero1' ];
		$contratoTipo = $_POST['contrato_tipo'];
		$statusId = $_POST[ 'status' ];
		
		$data1 = date("Y-m-d", strtotime("-360 day"));
		$data2 = date("Y-m-d", strtotime("-$numero1 day"));

		$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2'
								AND status='Em Aberto' ORDER BY vencimento ASC");
		
		if(!empty($contratoTipo)){
			$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2' AND contrato_tipo='$contratoTipo' AND status='Em Aberto' ORDER BY vencimento ASC");
		}
	}

	if(isset($_POST['pesquisar-2'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$statusId = $_POST[ 'status' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;

		$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2'
								AND status='Em Aberto' ORDER BY vencimento ASC");
	}


	if(isset($_SESSION['inicio'])){
		
		$data1 = $_SESSION['inicio'];
		$data2 = $_SESSION['fim'];
		
		$leitura = read('receber',"WHERE vencimento>='$data1' AND vencimento<='$data2'
								AND status='Em Aberto' ORDER BY vencimento ASC");
	}

	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Vencidos</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Site</a>
     	<li class="active">Vencidos</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
		 <div class="box-header">
             <div class="row">
				 
                 <div class="col-xs-10 col-md-5 pull-left">
					<div class="col-xs-10 col-md-5 pull-left">
						<a href="painel.php?execute=suporte/receber/aviso-vencidos" class="btnnovo">
						<img src="ico/email.png"  title="Aviso de Débito" />
						<small>Aviso de Débito</small>
						</a>
					</div><!-- /col-md-12-->
             	 </div><!-- /col-md-12-->
				 
				 
				     <div class="col-xs-6 col-md-6 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="Boleto">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->

		    </div><!-- /row-->	 
         </div><!-- /box-header-->
         
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
								 <input type="number" name="numero1" class="form-control input-sm" value="<?php echo $numero1 ?>" >
						   </div>   <!-- /.input-group -->
                           
                           
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                     </form> 
                  </div><!-- /col-xs-4-->
                  
          
                  </div><!-- /row-->    
               </div><!-- /row-->   
           </div><!-- /box-header-->   
		 
		 
		 
		 <div class="box-header">
             <div class="row">
		   		 <div class="col-xs-10 col-md-7 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
						 
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar-2" title="Pesquisar"><i class="fa fa-search"></i></button>
                           </div>  <!-- /.input-group -->
   
                         <div class="form-group pull-left">
                            <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                         </div>  <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                         </div>   <!-- /.input-group -->
                            
                    </form> 
	  
    		</div><!-- /col-xs-10 col-md-7 pull-right-->   
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
					<td align="center">Valor</td>
					<td align="center">Vencimento</td>
					<td align="center">Prog Pag</td>
					<td align="center">FormPag/Banco</td>
					<td align="center">Status</td>
					<td>E</td>
					<td>BI</td>
					<td align="center">Cobrança</td>
					<td align="center">Solucao</td>
					<td align="center">T</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
	foreach($leitura as $mostra):
			
		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		
		// filtra por status
		
		$listar='SIM';
		if(!empty($statusId)){
			if($contrato['status']==$statusId){
			   $listar='SIM';
			}else{
			   $listar='NAO';
			}
		}
		
		if($listar=='SIM'){
			
			$valor_total = $valor_total + $mostra['valor'];
			$total = $total+1;
	 
		 	echo '<tr>';
			
				echo '<td align="center">'.$mostra['id'].'</td>';
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				 
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
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
				
				if(!empty($mostra['refaturamento_vencimento']) ){
					echo '<td align="center">'.converteData($mostra['refaturamento_vencimento']).'</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
 				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';

				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											alt="Contrato Ativo" title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											alt="Contrato Suspenso" title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" 
										alt="Contrato Cancelado" title="Contrato Cancelado" /> </td>';
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}
			
				if($cliente['nao_enviar_email']=='1'){
					echo '<td align="center">N</td>';
				}else{
				  if( $mostra[ 'enviar_boleto_correio' ]<>'1'){
					echo '<td align="center">S</td>';
				  }else{
					 echo '<td align="center">N</td>';
				  }
				}

				if(empty($mostra['imprimir'])){
					echo '<td align="center">-</td>';
				}else{
					echo '<td align="center">*</td>';
				}
	
			$totalNegociacao = conta('receber_negociacao',"WHERE id_receber ='$receberId'");
			$negociacao = mostra('receber_negociacao',"WHERE id_receber ='$receberId' ORDER BY peso ASC, data ASC");
			if($negociacao){
				echo '<td align="center">'.converteData($negociacao['data']).'</td>';
				$solucaoId = $negociacao['id_solucao'];
				$solucao = mostra('recebe_negociacao_solucao',"WHERE id ='$solucaoId '");
 
				echo '<td align="center">'.$solucao['nome'].'</td>';
				echo '<td align="center">'.$totalNegociacao.'</td>';

			}else{
				echo '<td align="center">-</td>';
				echo '<td align="center">-</td>';
				echo '<td align="center">-</td>';
			}

			//	echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';

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

          <?php  
				$mes = date('m/Y',strtotime('-1months'));
				$mesano = explode('/',$mes);
 			
			 	// Receita & Despesas
			    // 90 dias
				$mes = date('m/Y',strtotime('-3months'));
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas90=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas90=$valor_total;
				}
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos90=0;
				if($atraso){
					$valor_total = '0';
					foreach($atraso as $mostraatraso):
						$valor_total=$valor_total+$mostraatraso['valor'];
					endforeach;
					$atrasos90=$valor_total;
				}
				// Atrasados
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas90=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas90=$valor_total;
				}
	 
				// 60 dias
				// Receitas
				$mes = date('m/Y',strtotime('-2months'));
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas60=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas60=$valor_total;

				}
	 
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos60=0;
				if($atraso){
					$valor_total = '0';
					foreach($atraso as $mostraatraso):
						$valor_total=$valor_total+$mostraatraso['valor'];
					endforeach;
					$atrasos60=$valor_total;
				}
	 
				// Despesas
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas60=0; 
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas60=$valor_total;
				}
				
				// 30 dias
				// Receitas
				$mes = date('m/Y',strtotime('-1months'));
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas30=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas30=$valor_total;
				}
	 
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos30=0;
				if($atraso){
					$valor_total = '0';
					foreach($atraso as $mostraatraso):
						$valor_total=$valor_total+$mostraatraso['valor'];
					endforeach;
					$atrasos30=$valor_total;
				}
	 
				// Despesas
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas30=0; 
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas30=$valor_total;
				}
	 
				// atual
				$mes = date('m/Y');
				$mesano = explode('/',$mes);
				$receita = read('receber',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$receitas00=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receitas00=$valor_total;
				}
	 
				// Atrasados
				$atraso = read('receber',"WHERE Month(vencimento)='$mesano[0]' AND Year(vencimento)='$mesano[1]' AND status='Em Aberto'");
				$atrasos00=0;
				if($atraso){
					$valor_total = '0';
					$data=date('Y-m-d');
					foreach($atraso as $mostraatraso):
						if($mostraatraso['vencimento']<$data){
							$valor_total=$valor_total+$mostraatraso['valor'];
						}
					endforeach;
					$atrasos00=$valor_total;
				}
	 
				// Despesas
				$despesa = read('pagar',"WHERE Month(pagamento)='$mesano[0]' AND Year(pagamento)='$mesano[1]'");
				$despesas00=0; 
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$despesas00=$valor_total;
				}

				
				// Previsao - Receita & Despesas
			    // 90 dias
				//Receber
				$mes90 = date('m/Y',strtotime('+3months'));
				$mesano90 = explode('/',$mes90);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano90[0]' AND Year(vencimento)='$mesano90[1]'");
				$receber90=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber90=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano90[0]' AND Year(vencimento)='$mesano90[1]'");
				$pagar90=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar90=$valor_total;
				}
				// 60 dias
				// Receitas
				$mes60 = date('m/Y',strtotime('+2months'));
				$mesano60 = explode('/',$mes60);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano60[0]' AND Year(vencimento)='$mesano60[1]'");
				$receber60=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber60=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano60[0]' AND Year(vencimento)='$mesano90[1]'");
				$pagar60=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar60=$valor_total;
				}
				
				// 30 dias
				// Receitas
				$mes30 = date('m/Y',strtotime('+1months'));
				$mesano30 = explode('/',$mes30);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano30[0]' AND Year(vencimento)='$mesano30[1]'");
				$receber30=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber30=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano30[0]' AND Year(vencimento)='$mesano30[1]'");
				$pagar30=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar30=$valor_total;
				}
				// atual
				// Receitas
				$mes00 = date('m/Y');
				$mesano00 = explode('/',$mes00);
				$receita = read('receber',"WHERE Month(vencimento)='$mesano00[0]' AND Year(vencimento)='$mesano00[1]' AND status<>'Baixado'");
				$receber00=0;
				if($receita){
					$valor_total = '0';
					foreach($receita as $mostrareceita):
						$valor_total=$valor_total+$mostrareceita['valor'];
					endforeach;
					$receber00=$valor_total;
				}
				// Pagar
				$despesa = read('pagar',"WHERE Month(vencimento)='$mesano00[0]' AND Year(vencimento)='$mesano00[1]' AND status<>'Baixado'");
				$pagar00=0;
				if($despesa){
					$valor_total = '0';
					foreach($despesa as $mostradespesa):
						$valor_total=$valor_total+$mostradespesa['valor'];
					endforeach;
					$pagar00=$valor_total;
				}
				
				
				// tres de visitas e pageviews do site
				$tres = date('m/Y',strtotime('-3months'));
				$tresEx = explode('/',$tres);
				$lerTres = read('visitas',"WHERE mes = '$tresEx[0]' AND ano = '$tresEx[1]'");
				if($lerTres){
					foreach($lerTres as $resTres);
					$visitasT = $resTres['visitas'];
					$pagesT = $resTres['pageviews'];
				}else{
					$visitasT = '0';
					$pagesT = '0'; 	
				}
				// dois de visitas e pageviews do site
				$dois = date('m/Y',strtotime('-2months'));
				$doisEx = explode('/',$dois);
				$lerDois = read('visitas',"WHERE mes = '$doisEx[0]' AND ano = '$doisEx[1]'");
				if($lerDois){
					foreach($lerDois as $resDois);
					$visitasD = $resDois['visitas'];
					$pagesD = $resDois['pageviews'];
				}else{
					$visitasD = '0';
					$pagesD = '0'; 	
				}
				// um de visitas e pageviews do site
				$um = date('m/Y',strtotime('-1months'));
				$umEx = explode('/',$um);
				$lerUm = read('visitas',"WHERE mes = '$umEx[0]' AND ano = '$umEx[1]'");
				if($lerUm){
					foreach($lerUm as $resUm);
					$visitasU = $resUm['visitas'];
					$pagesU = $resUm['pageviews'];
				}else{
					$visitasU = '0';
					$pagesU = '0'; 	
				}
				// atual de visitas e pageviews do site
				$atual = date('m/Y');
				$atualEx = explode('/',$atual);
				$lerAtual = read('visitas',"WHERE mes = '$atualEx[0]' AND ano = '$atualEx[1]'");
				if($lerAtual){
					foreach($lerAtual as $resAtual);
					$visitas = $resAtual['visitas'];
					$pages = $resAtual['pageviews'];
				}else{
					$visitas = '0';
					$pages = '0'; 	
				}
		
			?>	

            
           
 			<script type="text/javascript">
			 google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Mes',  'Faturamento', 'Despesas', 'Vencidos'],
				  ['<?php echo $tres;?>', <?php echo $receitas90;?>, <?php echo $despesas90;?>,<?php echo $atrasos90;?>],
				  ['<?php echo $dois;?>', <?php echo $receitas60;?>, <?php echo $despesas60;?>, <?php echo $atrasos60;?>],
				  ['<?php echo $um;?>',  <?php echo $receitas30;?>, <?php echo $despesas30;?>, <?php echo $atrasos30;?>,],
				  ['<?php echo $atual;?>', <?php echo $receitas00;?>, <?php echo $despesas00;?>, <?php echo $atrasos00;?>,]
				]);
				var options = {
				  title: 'Previsao de Receita & Despesas:',hAxis: {title: 'Mes', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('previsao'));
				chart.draw(data, options);
			  }
			</script>
            
 <section class="content">
    <div class="row">
        <div class="col-md-6">
          <div class="box">
                <div class="box-header">
                    <div id="previsao"></div><!--/chat do google-->
                </div><!-- /.box-header -->
            </div><!-- /.box  -->
        </div>
 
       </div><!-- /.box  -->
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->