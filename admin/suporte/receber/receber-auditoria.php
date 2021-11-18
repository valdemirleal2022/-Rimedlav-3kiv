<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

 
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");
	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	}

		
	$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' ORDER BY pagamento ASC");
	$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' ORDER BY pagamento ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
 
?>

<div class="content-wrapper">

  <section class="content-header">
     <h1>Receita - Auditoria</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Contas a Receber</a>
     	<li class="active">Auditoria</li>
     </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     		
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
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
			 </div><!-- /row-->  
         </div><!-- /box-header-->   
    

    <div class="box-body table-responsive">
     
 	<?php 

    if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
			 
					<td align="center">Nome</td>
					<td align="center">Tipo</td>
					<td align="center">Consultor</td>
					<td align="center">Valor</td>
					<td align="center">Emissão</td>
					<td align="center">Vencimento</td>
					<td align="center">Pagamento</td>
					<td align="center">Nota</td>
					<td align="center">Banco/Pag</td>
					
					<td colspan="8" align="center">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
		
				$receberId = $mostra['id'];
				$clienteId = $mostra['id_cliente'];
				$contratoId = $mostra['id_contrato'];
				
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				$contratoTipoId = $contrato['contrato_tipo'];
		
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
				$listar='Nao';
				if($contratoTipoId=='18'){
					if($mostra['vencimento']==$mostra['pagamento']){
						$listar='Sim';
					}
				}
		
		
			if($listar=='Sim'){
				
				echo '<tr>';
		
				if($cliente['tipo']==4){
					echo '<td>'.substr($cliente['nome'],0,18).' <img src="ico/premium.png"/></td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				}
				
				$contratoTipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
				
				if($contratoTipoId=='18'){
					//baixa automatica
					$cad['status']= 'Em Aberto';
					$cad['pagamento'] = null;
			 		//update('receber',$cad,"id = '$receberId'");	
				}

		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				$consultorId = $contrato['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td>'.substr($consultor['nome'],0,10).'</td>';
				
				 
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td>'.converteData($mostra['emissao']).'</td>';
				echo '<td>'.converteData($mostra['vencimento']).'</td>';
				echo '<td>'.converteData($mostra['pagamento']).'</td>';
				echo '<td>'.$mostra['nota'].'</td>';
				
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td>'.$banco['nome']. "|".substr($formapag['nome'],0,10).'</td>';
			 

				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/receber/receber-baixar&receberNumero='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';

				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/receber/receber-editar&receberEnviar='.$mostra['id'].'">
							<img src="ico/email.png" alt="Aviso" title="Aviso-Email" class="tip" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="../cliente/painel2.php?execute=boleto/emitir-boleto&boletoId='.$mostra['id'].'" target="_blank">
							<img src="ico/boleto.png" alt="Boleto" title="Boleto" class="tip" />
              			</a>
						</td>';		
						
				if(empty($mostra['link'])){
					echo '<td align="center">-</td>';
				}else{
					 echo '<td align="center">
						<a href="'.$mostra['link'] .'" target="_blank">
							<img src="ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
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
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
           echo '</tr>';
		
         echo '</tfoot>';
		 
		 echo '</table>';
 

		} //FIM DO $LEITURA
		
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