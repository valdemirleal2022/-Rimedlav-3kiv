 <?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();

	$total = conta('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
												AND data_solicitacao<='$data2'");
	$leitura = read('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		if($_POST[ 'ordem' ]=='2') {
			$_SESSION['ordem']=$_POST['ordem'];	
		}
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-solicitacao-cancelamento-pdf");';
		echo '</script>';
	}

	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		if($_POST[ 'ordem' ]=='2') {
			$_SESSION['ordem']=$_POST['ordem'];	
		}
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-solicitacao-cancelamento-excel.php');
	}

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];

		$total = conta('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
												AND data_solicitacao<='$data2'");
		$leitura = read('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
		
		if ( isset( $_POST[ 'ordem' ] ) ) {
			
			if($_POST[ 'ordem' ]=='2') {
			    $ordem=2;
				$leitura = read('contrato_cancelamento',"WHERE id AND data_encerramento>='$data1' 
					 AND data_encerramento<='$data2' ORDER BY data_encerramento ASC");
				$total = conta('contrato_cancelamento',"WHERE id AND data_solicitacao>='$data1' 
												AND data_solicitacao<='$data2'");
			}
		}
		
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
	
  <section class="content-header">
       <h1>Solicitação de Cancelamento</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Solicitação</li>
           <li><a href="#">Cancelamentos</a></li>
         </ol>
 </section>
 
<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

       <div class="box-header">	 
                
                 <div class="col-xs-10 col-md-7 pull-right">
					 
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
                        <div class="form-group pull-left">
                            <input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
						 <div class="form-group pull-left">

							 <select name="ordem" class="form-control input-sm"" >
								 <option <?php if($ordem == '1') echo' selected="selected"';?> value="1">Solicitação</option>
								 <option <?php if($ordem == '2') echo' selected="selected"';?> value="2">Encerramento</option>
							  </select>

						</div>
					 
                        <div class="form-group pull-left">
							<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
							<i class="fa fa-search"></i></button>
						</div><!-- /.input-group -->

						<div class="form-group pull-left">
							<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF">
							<i class="fa fa-file-pdf-o"></i></button>
						</div>  <!-- /.input-group -->

						<div class="form-group pull-left">
							<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel">
							<i class="fa fa-file-excel-o"></i></button>
						</div>   <!-- /.input-group --> 
						 
                    </form>  
                     
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
                  
          </div><!-- /box-header-->   

    <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
   
	<?php 

	if($leitura){
		
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Consultor</td>
					<td align="center">Coleta</td>
					<td align="center">Solicitação</td>
					<td align="center">Encerramento</td>
					<td align="center">Vl Rescisório</td>
					<td align="center">Motivo</td>
					<td align="center">Status</td>
					<td align="center">Rec</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			
				echo '<td>'.substr($cliente['nome'],0,12).'</td>';
		
				$consultorId = $contrato['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td align="left">'.substr($consultor['nome'],0,10).'</td>';
		 
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				$tipoColetaId = $contratoColeta['tipo_coleta'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.substr($coleta['nome'],0,12).'</td>';
		 
				echo '<td>'.converteData($mostra['data_solicitacao']).'</td>';
				echo '<td>'.converteData($mostra['data_encerramento']).'</td>';
		
		echo '<td align="right">'.converteValor($mostra['valor_rescisorio']).'</td>';
		
				$tipoId = $mostra['motivo'];
				$tipo = mostra('contrato_cancelamento_motivo',"WHERE id ='$tipoId'");
				echo '<td align="left">'.substr($tipo['nome'],0,16).'</td>';
												
				echo '<td>'.$mostra['status'].'</td>';
					
				if ($mostra['recuperada']==1){
						echo '<td align="left">Sim</td>';
					}elseif ($mostra['recuperada']==2){
						echo '<td align="left">Não</td>';
					}else{
						echo '<td align="left">-</td>';
				}
			
				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											alt="Contrato Ativo" title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											alt="Contrato Suspenso" title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==7){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" 
										alt="Contrato Cancelado" title="Contrato Rescindido" /> </td>';	
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" 
										alt="Contrato Cancelado" title="Contrato Cancelado" /> </td>';
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}
						 
				echo '<td align="center">
                                <a href="painel.php?execute=suporte/contrato/contrato-cancelamento&contratoCancelamento='.$mostra['id'].'">
                                    <img src="../admin/ico/editar.png" title="Editar"  />
                                  </a>
                                 </td>';
		
			echo '<td align="center">
                                <a href="painel.php?execute=suporte/contrato/contrato-cancelamento&contratoCancelamento='.$mostra['id'].'">
                                    <img src="../admin/ico/email.png" title="Enviar"  />
                                  </a>
                                 </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/relatorio/cancelamento&cancelamentoId='.$mostra['id'].'" target="_blank">
							<img src="ico/extrato.png" title="Cancelamento"  />
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