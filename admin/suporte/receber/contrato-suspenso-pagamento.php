
 <?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if(!isset($_SESSION['inicio'])){
		$data1 = date( "Y/m/d");
		$data2 = date( "Y/m/d");
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
	}else{
		$data1=$_SESSION['inicio'];
		$data2=$_SESSION['fim'];
	}

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-suspenso-pagamento-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-suspenso-pagamento-excel.php');
	}


	$total = conta('contrato_baixa',"WHERE id AND falta_pagamento='1'");
	$leitura = read('contrato_baixa',"WHERE id AND falta_pagamento='1' ORDER BY data DESC");

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];

		$total = conta('contrato_baixa',"WHERE id AND falta_pagamento='1' 
								AND data>='$data1' AND data<='$data2'");
		$leitura = read('contrato_baixa',"WHERE id AND falta_pagamento='1' 
								AND data>='$data1'AND data<='$data2' ORDER BY data ASC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contratos Suspensos (Falta Pagamento) </h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Contratos</li>
           <li><a href="painel.php?execute=suporte/receber/contrato-suspenso-pagamento">Suspensos(6)</a></li>
         </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

    <div class="box-header">	 
                  
           
                    <div class="col-xs-10 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        <div class="form-group pull-left">
                            <input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        
                       	 <div class="form-group pull-left">
								<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
								 <i class="fa fa-search"></i></button>
							 </div><!-- /.input-group -->

							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->                         
                    </form>  
                     
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
                  
          </div><!-- /box-header-->   
       

    <div class="box-body table-responsive">
      <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
 
	<?php 
 
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Valor Mensal</td>
					<td align="center">Data</td>
					<td align="center">Motivo</td>
					<td align="center">Status</td>
					<td align="center">Vencimento</td>
					<td align="center">Pagamento</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id = '$contratoId'");
				$clienteId = $mostra['id_cliente'];
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,30).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';
		
				$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND id_contrato='$contratoId' ORDER BY vencimento DESC" );
				if($tipoColeta){
					echo '<td align="right">'.(converteValor($tipoColeta['valor_mensal'])).'</td>';
				}else{
					 echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}
		
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'.substr($mostra['motivo'],0,20).'</td>';
		
				if($contrato['status']==5) {
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											 title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==7){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
										 title="Contrato Rescindo" /> </td>';
				}elseif($contrato['status']==10){
					echo '<td align="center"><img src="ico/juridico.png" 
										 title="Contrato no Juridico" /> </td>';
				}elseif($contrato['status']==19){
					echo '<td align="center"><img src="ico/contrato-suspenso-temporario.png" 
											 title="Contrato Suspenso Temporario" /> </td>';
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}
				
				$dataSuspensao = $mostra['data'];
				$receber = mostra( 'receber', "WHERE id_contrato='$contratoId' AND vencimento<'$dataSuspensao' ORDER BY vencimento ASC" );
		 		echo '<td>'.converteData($receber['vencimento']).'</td>';
				echo '<td>'.converteData($receber['pagamento']).'</td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contrato['id'].'">
							<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
          echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
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