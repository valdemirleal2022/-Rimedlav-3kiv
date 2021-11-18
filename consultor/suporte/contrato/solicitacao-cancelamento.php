
 <?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autConsultor']['id'])){
			header('Location: painel.php');	
		}	
	}

	$consultorId=$_SESSION['autConsultor']['id'];

	$total = conta('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
												AND data_solicitacao<='$data2'");
	$leitura = read('contrato_cancelamento',"WHERE id AND status='Aguardando' AND id_consultor='$consultorId' ORDER BY data_solicitacao ASC");


	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Cancelamentos </h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Contratos</li>
           <li><a href="#">Cancelamentos</a></li>
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
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Solicitação</td>
					<td align="center">Encerramento</td>
					<td align="center">Motivo</td>
					<td align="center">Consultor</td>
					<td align="center">Status</td>
					<td align="center">Recuperada</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
		
						$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			
				echo '<td>'.substr($cliente['nome'],0,23).'</td>';
		 
				echo '<td>'.converteData($mostra['data_solicitacao']).'</td>';
				echo '<td>'.converteData($mostra['data_encerramento']).'</td>';
		
				$tipoId = $mostra['motivo'];
					
				$tipo = mostra('contrato_cancelamento_motivo',"WHERE id ='$tipoId'");
				echo '<td align="left">'.substr($tipo['nome'],0,32).'</td>';
		
				echo '<td>'.$mostra['id_consultor'].'</td>';
												
				echo '<td>'.$mostra['status'].'</td>';
					
							if ($mostra['recuperada']==1){
								echo '<td align="left">Sim</td>';
							}elseif ($mostra['recuperada']==2){
								echo '<td align="left">Não</td>';
							}else{
								echo '<td align="left">-</td>';
							}
						 
							echo '<td align="center">
                                <a href="painel.php?execute=suporte/contrato/contrato-cancelamento&contratoCancelamento='.$mostra['id'].'">
                                    <img src="../admin/ico/editar.png" alt="Editar" title="Editar"  />
                                  </a>
                                 </td>';
	
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
          echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
          echo '<tr>';
         echo '</tfoot>';
		 
	 echo '</table>';
	
	}

		?>	
		 <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
       </div><!-- /.box-footer-->

	      </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
 	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->