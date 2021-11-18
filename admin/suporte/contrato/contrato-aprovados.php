<?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-aprovados-pdf");';
		echo '</script>';
	}

	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-aprovados-excel.php');
	}

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
	}


	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='4'",'valor_mensal');

	$total = conta('contrato',"WHERE id AND tipo='2' AND status='4'");

	$leitura = read('contrato',"WHERE id AND tipo='2' AND status='4' ORDER BY aprovacao ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contrato Aprovados</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contrato</li>
           <li>Aprovados</li>
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
                        	 <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>   
                       </div><!-- /.input-group -->  
                        <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>  
                        </div><!-- /.input-group -->
                          <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>  
                        </div><!-- /.input-group -->                              
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
					<td align="center">Bairro</td>
					<td align="center">Consultor</td>
					<td align="center">Tipo Contrato</td>
					<td align="center">Valor</td>
					<td align="center">Aprovacao</td>
					<td align="center">Inicio</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';
		
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td>'.$consultor['nome'].'</td>';
		
				$contratoTipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
		
				echo '<td align="right">'.(converteValor($mostra['valor_mensal'])).'</td>';
		
				echo '<td align="center">'.converteData($mostra['aprovacao']).'</td>';
				echo '<td align="center">'.converteData($mostra['inicio']).'</td>';

				echo '<td align="center">
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$cliente['id'].'">
								<img src="ico/editar-cliente.png" alt="Editar Cliente" title="Editar Cliente" />
							</a>
						  </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoAtivar='.$mostra['id'].'">
			  				<img src="ico/aprovado.png" alt="Editar" title="Ativar Contrato" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
              			</a>
				      </td>';
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
							<img src="ico/mapa.png" alt="Mapa" title="Mapa" class="tip" />
              			</a>
						</td>';
		
				$pdf='../../wpc/uploads/contratos/'.$mostra['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../../wpc/uploads/contratos/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" alt="Contrato" title="Contrato" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}
		
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="13">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="13">' . 'Valor Total R$ : ' . number_format($valor_total,2,',','.') . '</td>';
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
  
<section class="content">

<div class="row">
    <div class="col-md-12">  

       <?php 
			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_consultor',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostra):
					$vendedor= $mostra['nome'];
					$Id = $mostra['id'];
					$total = soma('contrato',"WHERE tipo='2' AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' AND consultor='$Id'",'valor_mensal');
					if($total<>0){
						$nome[$contador] = $vendedor;
						$valor[$contador] = $total; 
						$contador++;
					}
				endforeach;
			}
	  ?>
     
      <script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Consultor', 'Mes Atual'],
				  	  <?php
						$contador=0;
						for($x=0; $x< count($nome); $x++){
							echo "[ '".$nome[$contador]."'," .$valor[$contador]. "],";
							$contador++;
					 	}; 
					?>
				]);
				var options = {
				  is3D: true,
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				  title: 'Iniciando por Consultor'
				};
				var chart = new google.visualization.PieChart(document.getElementById('vendedor'));
				chart.draw(data, options);
			  }
			</script>
              
         <div class="col-md-6">   
          <div class="box">
                <div class="box-header">
                     <div id="vendedor"></div><!--/chat do google-->
                </div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	</div><!-- /.col-md-6"  -->
        
<!--      INDICAÇÃO-->
                
         <?php 
			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_indicacao',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostra):
					$indicacao= $mostra['nome'];
					$Id = $mostra['id'];
					$total = soma('contrato',"WHERE tipo='2' AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' AND indicacao='$Id'",'valor_mensal');
					if($total<>0){
						$nome[$contador] = $indicacao;
						$valor[$contador] = $total; 
						$contador++;
					}
				endforeach;
			}
	  ?>
     
      <script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Indicação', 'Mes Atual'],
				  	  <?php
						$contador=0;
						for($x=0; $x< count($nome); $x++){
							echo "[ '".$nome[$contador]."'," .$valor[$contador]. "],";
							$contador++;
					 	}; 
					?>
				]);
				var options = {
				  is3D: true,
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				  title: 'Iniciando por Indicacao'
				};
				var chart = new google.visualization.PieChart(document.getElementById('indicacao'));
				chart.draw(data, options);
			  }
			</script>
              
         <div class="col-md-6">   
          <div class="box">
                <div class="box-header">
                     <div id="indicacao"></div><!--/chat do google-->
                </div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	</div><!-- /.col-md-6"  -->
        
        
        
        
 <!--   TIPO -->
                
         <?php 
			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_tipo',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostra):
					$tipo= $mostra['nome'];
					$Id = $mostra['id'];
					$total = soma('contrato',"WHERE tipo='2' AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' AND contrato_tipo='$Id'",'valor_mensal');
					if($total<>0){
						$nome[$contador] = $tipo;
						$valor[$contador] = $total; 
						$contador++;
					}
				endforeach;
			}
	  ?>
     
      <script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Indicação', 'Mes Atual'],
				  	  <?php
						$contador=0;
						for($x=0; $x< count($nome); $x++){
							echo "[ '".$nome[$contador]."'," .$valor[$contador]. "],";
							$contador++;
					 	}; 
					?>
				]);
				var options = {
				  is3D: true,
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				  title: 'Iniciando por Tipo'
				};
				var chart = new google.visualization.PieChart(document.getElementById('tipo'));
				chart.draw(data, options);
			  }
			</script>
              
         <div class="col-md-6">   
          <div class="box">
                <div class="box-header">
                     <div id="tipo"></div><!--/chat do google-->
                </div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	</div><!-- /.col-md-6"  -->
         
      
       
 </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 </section><!-- /.content -->

</div><!-- /.content-wrapper -->