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
 
?>
   
     
  <div class="content-wrapper">
  <section class="content-header">
          <h1>Ordem de Serviço</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Cliente</li>
            <li class="active">Ordem de Serviço</li>
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
			$leituraOrdem = read('contrato_ordem',"WHERE id AND id_cliente='$clienteId' AND status='13' AND nao_coletada<>'1' ORDER BY data DESC, hora ASC");
	
			if($leituraOrdem){
					
				echo '<table class="table table-hover">	
							<tr class="set">
							<td align="center">ID</td>
							<td align="center">Nome</td>
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
					echo '<td>'.substr($mostra['nome'],0,35).'</td>';
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
