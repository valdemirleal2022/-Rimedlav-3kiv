
 <?php 

	if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autpos_venda']['id'])){
				header('Location: painel.php');	
			}	
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];

	$data1 = date("Y-m-d");
	$data2 =  date("Y-m-d");

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-cancelados-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-cancelados-excel.php');
	}


	// 9 Contrato Cancelado
	
	$valor_total = soma('contrato',"WHERE id AND status='9'",'valor_mensal');
	$total = conta('contrato',"WHERE id AND status='9'");
	$leitura = read('contrato',"WHERE id AND status='9' ORDER BY data_cancelamento DESC");

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
		$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='9' AND data_cancelamento>='$data1' 
											  AND data_cancelamento<='$data2' AND status='9'",'valor_mensal');
		$total = conta('contrato',"WHERE id AND tipo='2' AND data_cancelamento>='$data1' 
												AND data_cancelamento<='$data2' AND status='9'");
		$leitura = read('contrato',"WHERE id AND tipo='2' AND data_cancelamento>='$data1' 
											  AND data_cancelamento<='$data2' AND status='9' 
											  ORDER BY data_cancelamento ASC");
	}


	if(isset($_POST['pesquisar_numero'])){
		$contratoId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(!empty($contratoId)){
			$leitura=read('contrato',"WHERE id AND (id LIKE '%$contratoId%' OR
						controle LIKE '%$contratoId%')");  
		}
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contratos Cancelado</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Contratos</li>
           <li><a href="#">Cancelados</a></li>
         </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

    <div class="box-header">	 
                     <div class="col-xs-6 col-md-4 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="numero">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
                     
           
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
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relat�rio PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relat�rio Excel"><i class="fa fa-file-excel-o"></i></button>
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
					<td align="center">A partir</td>
					<td align="center">Motivo</td>
					<td align="center">Intera��o</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
		
				$contratoId = $mostra['id'];
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';
		
				echo '<td>'.converteData($mostra['data_cancelamento']).'</td>';
		
				$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND tipo='2' ORDER BY interacao ASC");
				echo '<td>'.substr($contratoBaixa['motivo'],0,20).'</td>';
		
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($contratoBaixa['interacao'])).'</td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/agenda.png" alt="Editar" title="Cronograma Contrato"  />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
							<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar" />
              			</a>
						</td>';
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