
 <?php 

	if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autpos_venda']['id'])){
				header('Location: painel.php');	
			}	
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];

	// 6 Contrato Suspensos
	
	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='6' AND pos_venda='$pos_vendaId'",'valor_mensal');
	$total = conta('contrato',"WHERE id AND tipo='2' AND status='6' AND pos_venda='$pos_vendaId'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND status='6' AND pos_venda='$pos_vendaId' ORDER BY data_suspensao DESC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contratos Suspensos </h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Contratos</li>
           <li><a href="#">Suspensos(6)</a></li>
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
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">A partir</td>
					<td align="center">Motivo</td>
					<td align="center">Interação</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
		
				$contratoId = $mostra['id'];
				$clienteId = $mostra['id_cliente'];
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';
	 			$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='2' ORDER BY interacao ASC");
		
				echo '<td>'.converteData($mostra['data_suspensao']).'</td>';
				echo '<td>'.substr($contratoBaixa['motivo'],0,20).'</td>';
		
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($contratoBaixa['interacao'])).'</td>';

			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
          echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td colspan="10">'.'Valor Total R$ : '.number_format($valor_total,2,',','.').'</td>';
          echo '</tr>';
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