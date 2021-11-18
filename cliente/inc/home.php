
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

 ?><head> <meta charset="iso-8859-1"></head>


<div class="content-wrapper">

  <section class="content-header">
          <h1>Contratos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Painel</li>
            <li class="active">Contratos</li>
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
           
     	<div class="box-body">    
               	
				 <?php 
	 
 	$leitura = read('cliente',"WHERE id AND email='$email' ORDER BY id ASC");
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Cliente</td>
					<td align="center">Endereço</td>
					<td align="center">Bairro</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Consultor</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
  
		
				$clienteId = $mostra['id'];

				$leituraContrato = read('contrato',"WHERE id_cliente ='$clienteId'");
				if($leituraContrato){
					foreach($leituraContrato as $contrato):
					echo '<tr>';
					
					echo '<td>'.$contrato['id'].'</td>';
					
					echo '<td>'.substr($mostra['nome'],0,35).'</td>';
					
					echo '<td>'.$mostra['endereco'].','.$mostra['numero'].' '.$mostra['complemento'].'</td>';
					
					echo '<td>'.substr($mostra['bairro'],0,20).'</td>';
		
					$contrato_tipoId = $contrato['contrato_tipo'];
					$tipo = mostra('contrato_tipo',"WHERE id ='$contrato_tipoId'");
					echo '<td align="center">'.$tipo['nome'].'</td>';

					$consultorId = $contrato['consultor'];
					$consultor= mostra('contrato_consultor',"WHERE id ='$consultorId'");
					echo '<td align="center">'.$consultor['nome'].'</td>';

					$statusId = $contrato['status'];
					$status = mostra('contrato_status',"WHERE id ='$statusId'");
					echo '<td align="center">'.$status['nome'].'</td>';
					
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contrato['id'].'">
								<img src="../admin/ico/visualizar.png" alt="Visualizar" title="Visualizar" class="tip" />
							</a>
						  </td>';	

					echo '</tr>';
					
				 endforeach;
					
				}
					
		 endforeach;
		 
		 echo '</table>';
		}
	?>
           
           </div><!-- /.box-body -->
 
    </div><!-- /.box-default -->
    
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
