<?php 

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}
	
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
	
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-acompanhamento-visitas-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$consultorId = $_POST['consultor'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-acompanhamento-visitas-excel.php' );
	}


   	$data1 = date("Y-m-d", strtotime("-30 day"));
	$data2 = date("Y-m-d");
   
	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];

		if(!empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId' ORDER BY interacao DESC");
		}

	}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Visitas</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Visitas</a></li>
         </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
   
        <div class="box-body table-responsive">

           	<div class="box-header">       
                  <div class="col-xs-10 col-md-7 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                        
                           <div class="form-group pull-left">
								<select name="consultor" class="form-control input-sm">
									<option value="">Selecione o Consultor</option>
									<?php 
										$readContrato = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($consultorId == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
													}
											endforeach;	
										}
									?> 
							    </select>
						 </div> 
                       
                     
                        <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
        </div> <!-- /.box-header -->
        
  
   		<?php 
			
  		$leitura = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
		if($leitura){
			foreach($leitura as $mostra):
			
				$consultorId=$mostra['id'];
				$foto='../uploads/consultores/'.$mostra['fotoperfil'];
				$nome = substr($mostra['nome'],0,9) ;
			
				if(empty($mostra['fotoperfil'])){
					$foto=URL.'/site/images/autor.png';
				}
				
				
			$visitas = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$orcamento = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			
			$propostas = conta('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2'  AND consultor='$consultorId'");
				
			$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
			$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
				
            ?> 
            
				<div class="col-md-3">
				  <div class="box box-widget widget-user-2">
					<div class="widget-user-header bg-green">

					  <div class="widget-user-image">
						<img class="img-circle" src="<?php echo $foto ?>" alt="Foto">
					  </div>

					  <h5 class="widget-user-desc"><?php echo $nome; ?></h5>
					  <h6 class="widget-user-desc">Consultor</h6>
					</div><!-- /.widget-user-header bg-aqua-->
					
					<div class="box-footer no-padding">
				  
					  <ul class="nav nav-stacked">
					  
						<li><a href="#">Visitas<span class="pull-right badge bg-blue">
						 <?php echo $visitas ?>
						</span></a></li>
						
						<li><a href="#">Orçamentos<span class="pull-right badge bg-yellow">
						<?php echo $orcamento ?>
						</span></a></li>
						
						<li><a href="#">Proposta <span class="pull-right badge bg-green">
						<?php echo  $propostas ?>
						</span></a></li>
						
						<li><a href="#">Aprovados<span class="pull-right badge bg-blue">
						 <?php echo $aprovados ?>
						</span></a></li>
						  
						<li><a href="#">Cancelados<span class="pull-right badge bg-red">
						 <?php echo $cancelados ?>
						</span></a></li>
					  	
					  	 <?php $total=$visitas+$orcamento+$propostas+$aprovados+$cancelados?>
					  	 
					   <li><a href="#">Total<span class="pull-right badge bg-green">
						 <?php echo $total ?>
						</span></a></li>

					  </ul>
					  
					</div><!-- /.box-footer no-padding-->
					
				  </div><!-- /.box box-widget widget-user-2 -->
				</div><!-- /.col-md-3 -->

     <?php 
		endforeach;
		}
	  ?> 
	  
</div><!-- /.box-body table-responsive -->
    
        <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?> 
       </div><!-- /.box-footer-->
       
    </div><!-- /.box box-default -->
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
					$total = conta('cadastro_visita',"WHERE id AND consultor='$Id'");
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
				  ['Consultor', 'Visitas'],
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
				  title: 'Visitas por Consultor'
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
					$total = conta('cadastro_visita',"WHERE id AND indicacao='$Id'");
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
				  title: 'Visitas por Indicação'
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
			$visitas = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2'");
			$orcamento = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2'");
			$propostas = conta('cadastro_visita',"WHERE id AND status='3' AND data>='$data1' AND data<='$data2'");
			$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND data>='$data1' AND data<='$data2'");
			$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' AND data<='$data2'");
	  ?>
     
      <script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Status', 'Visitas'],
				  [ 'visitas', <?php echo $visitas;?>],
				  [ 'orcamento',<?php echo $orcamento;?>],
				  [ 'propostas',<?php echo $propostas;?>],
				  [ 'aprovados',<?php echo $aprovados;?>],
				  [ 'cancelados',<?php echo $cancelados;?>] 
				]);
				var options = {
				  is3D: true,
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				  title: 'Status das Visitas'
				};
				var chart = new google.visualization.PieChart(document.getElementById('status'));
				chart.draw(data, options);
			  }
			</script>
              
         <div class="col-md-6">   
          <div class="box">
                <div class="box-header">
                     <div id="status"></div><!--/chat do google-->
                </div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	</div><!-- /.col-md-6"  -->

   </div><!-- /.row-->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
