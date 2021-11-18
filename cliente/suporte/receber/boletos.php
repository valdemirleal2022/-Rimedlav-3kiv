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

		$email = $cliente['email'];

 
?><head>
    <meta charset="iso-8859-1">
</head>
    
    

   
<div class="content-wrapper">
  <section class="content-header">
          <h1>Boletos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Contrato</li>
            <li class="active">Boletos</li>
          </ol>
  </section>

  <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
            <div class="box-header">

       		<div class="box-tools">
           </div><!-- /box-tools-->
       </div><!-- /.box-header -->

    <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">


	<?php 

	$leitura = read('cliente',"WHERE id AND email='$email' ORDER BY id ASC");
	if($leitura){
		foreach($leitura as $mostra):
		
			$clienteId = $mostra['id'];

			$leituraReceber = read('receber',"WHERE id_cliente ='$clienteId'");
			if($leituraReceber){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Cliente</td>
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
				
					echo '<td>'.substr($mostra['nome'],0,35).'</td>';
				
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
					}else{
						 echo '<td align="center"> - </td>';
					}
				
					echo '<td align="center">
										<a href="../cliente/painel2.php?execute=suporte/contrato/extrato-cliente-resumido&boletoId='.$receber['id'].'" target="_blank">
											<img src="../admin/ico/extrato.png" alt="Extrato" title="Extrato Interno"  />
										</a>
										</td>';	
				
				
					if(empty($receber['link'])){
						echo '<td align="center">-</td>';
					}else{
						 echo '<td align="center">
							<a href="'.$receber['link'] .'" target="_blank">
								<img src="../admin/ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />           </a>
						  </td>';
					}


				echo '</tr>';
	
		 endforeach;
		
		 echo '</table>';

		}
		
	 endforeach; // fim do cliente
		
	}  // fim do cliente
				
	?>
    
	 </div><!-- /.box-body table-responsive -->
	  </div><!-- /.col-md-12 scrool -->
 	  </div><!-- /.box-body table-responsive data-spy='scroll' -->
  
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
