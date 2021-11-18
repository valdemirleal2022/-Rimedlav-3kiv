<head>
    <meta charset="iso-8859-1">
</head>
    
       
 <div class="content-wrapper">
    <section class="content-header">
          <h1>Contratos Ativos <small>
          </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel</li>
          </ol>
        </section>

        
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
					$total = soma('contrato',"WHERE tipo='2' AND consultor='$Id'",'valor_mensal');
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
				  title: 'Contrato por Consultor'
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
					$total = soma('contrato',"WHERE tipo='2' AND indicacao='$Id'",'valor_mensal');
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
				  title: 'Contrato por Indicação'
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
					$total = soma('contrato',"WHERE tipo='2' AND contrato_tipo='$Id'",'valor_mensal');
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
				  title: 'Contrato por Indicação'
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
         
      
      
 <?php 
		
		$contador=0;
		$nome = array();
		$valor = array();
		
        $leitura = read('cliente_classificacao',"WHERE id ORDER BY id ASC");
		if($leitura){
				foreach($leitura as $mostraClass):
			
					$classNome= $mostraClass['nome'];
					$classId = $mostraClass['id'];
					$total=0;
			
				    $leituraCliente= read('cliente',"WHERE id");
			
					if($leituraCliente){
						
                        foreach($leituraCliente as $cliente):
							if($cliente['classificacao']==$classId){
								$ClienteId=$cliente['id'];
								$contrato= mostra('contrato',"WHERE id='$ClienteId'");
								if($contrato){
									$total=$total+$contrato['valor_mensal'];
								}
							}
                        endforeach;
						
                        if($total<>0){
							$nome[$contador] = $classNome;
							$valor[$contador] = $total; 
							$contador++;
						}
						
					}
			
				endforeach;
			
			   }
		?>
    
     	<script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Classificacao', 'Mes Atual'],
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
				   title: 'Contratos por Classificacao'
				};
				var chart = new google.visualization.PieChart(document.getElementById('classificacao'));
				chart.draw(data, options);
			  }
			</script>
         
   	   <div class="col-md-6">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="classificacao"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
      </div><!-- /.col-md-6  -->
      
      
       
    
       
   </div><!-- /.row-->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->