<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php');		
		}
	}
	 	
	$clienteId = $_SESSION['autCliente']['id'];
	$readCliente = read('cliente',"WHERE id = '$clienteId'");
	if(!$readCliente){
			header('Location: painel.php?execute=suporte/naoEncontrado');
	}
	foreach($readCliente as $cliente);

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>
    


<div class="content-wrapper">
  <section class="content-header">
          <h1>Atendimentos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Atendimentos</li>
            <li class="active">Atendimentos</li>
          </ol>
  </section>

  <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
            <div class="box-header">
			   <a href="painel.php?execute=suporte/atendimento/pedido-novo" class="btnnovo"><img src="../admin/ico/novo.png" alt="Novo" title="Novo" class="tip" />Novo Atendimento
		 </a>
       		<div class="box-tools">
           </div><!-- /box-tools-->
       </div><!-- /.box-header -->

    <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive"> </div><!-- /.box-body table-responsive -->
	  </div><!-- /.col-md-12 scrool -->
 	  </div><!-- /.box-body table-responsive data-spy='scroll' -->
  
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
     
	<?php 
		
	$leitura = read('pedido',"WHERE id_cliente = '$clienteId' AND cliente_solicitou='1' ORDER BY id DESC");
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
    
  </div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
 