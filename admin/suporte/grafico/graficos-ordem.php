 

 <div class="content-wrapper">
    <section class="content-header">
          <h1>Ordem de Servço </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel</li>
          </ol>
     </section>

     <?php

	 
	 	$ordemTotal = conta( 'contrato_ordem', "WHERE id AND Month(data)='$mesano[0]' 
											  AND Year(data)='$mesano[1]'" );
		$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND Month(data)='$mesano[0]' 
											  AND Year(data)='$mesano[1]' AND status='12'");
		$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND Month(data)='$mesano[0]' 
											  AND Year(data)='$mesano[1]' AND status='13'" );
		$ordemCancelada = conta( 'contrato_ordem', "WHERE id AND Month(data)='$mesano[0]' 
											  AND Year(data)='$mesano[1]' AND status='15'" );
		 
		$ordemBaixada = $ordemRealizada+$ordemCancelada;

	 ?>

<section class="content">
  <div class="row">
   <div class="col-md-12">  

     
     	<div class="box-header">
      	 <h3 class="box-title"><small> Mes  : <?php echo $mesano[0].'/'.$mesano[1];?></small></h3>
		</div> <!-- /.box-header -->
 		
  		<div class="box-header"> 
         <div class="info-box bg-blue">
                <span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Ordem de Serviço</span>
                  <span class="info-box-number"><?php echo $ordemTotal;?></span>
                  <div class="progress">
                    <?php
					 $percentual=($ordemBaixada/$ordemTotal)*100;
					 $percentual=intval($percentual);
					?>	
                    <div class="progress-bar" style="width: <?php echo $percentual;?>%"></div>
                  </div>
                  <span class="progress-description">
                     <?php echo 'Coletadas '.$percentual. '%  || Realizadas : '. $ordemRealizada . '   || Em Aberto  : '. $ordemEmaberto;?>
                  </span>
           </div><!-- /.info-box bg-red -->
 		</div> <!-- /.box-header -->
 		 
 		 
  		 </div><!-- /.row-->  
  	 </div><!-- /.row-->     
  </div><!-- /.row-->        
</section><!-- /.content -->


<section class="content">
  <div class="row">
   <div class="col-md-12">  


  <?php
				
				$data = date("Y-m-d");
				$d0 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t0=$ordemTotal;
				$r0=$ordemRealizada;
	   
	   
	            $data = date("Y-m-d", strtotime("-1 day"));
				$d1 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t1=$ordemTotal;
				$r1=$ordemRealizada;
	   
	
				$data = date("Y-m-d", strtotime("-2 day"));
				$d2 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t2=$ordemTotal;
				$r2=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-3 day"));
				$d3 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t3=$ordemTotal;
				$r3=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-4 day"));
				$d4 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t4=$ordemTotal;
				$r4=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-5 day"));
				$d5 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t5=$ordemTotal;
				$r5=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-6 day"));
				$d6 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t6=$ordemTotal;
				$r6=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-7 day"));
				$d7 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t7=$ordemTotal;
				$r7=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-8 day"));
				$d8 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t8=$ordemTotal;
				$r8=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-9 day"));
				$d9 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t9=$ordemTotal;
				$r9=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-10 day"));
				$d10 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t10=$ordemTotal;
				$r10=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-11 day"));
				$d11 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t11=$ordemTotal;
				$r11=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-12 day"));
				$d12 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t12=$ordemTotal;
				$r12=$ordemRealizada;

				$data = date("Y-m-d", strtotime("-13 day"));
				$d13 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t13=$ordemTotal;
				$r13=$ordemRealizada;
				
				$data = date("Y-m-d", strtotime("-14 day"));
				$d14 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t14=$ordemTotal;
				$r14=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-15 day"));
				$d15 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t15=$ordemTotal;
				$r15=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-16 day"));
				$d16 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t16=$ordemTotal;
				$r16=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-17 day"));
				$d17 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t17=$ordemTotal;
				$r17=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-18 day"));
				$d18 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t18=$ordemTotal;
				$r18=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-19 day"));
				$d19 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t19=$ordemTotal;
				$r19=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-20 day"));
				$d20 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t20=$ordemTotal;
				$r20=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-21 day"));
				$d21 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t21=$ordemTotal;
				$r21=$ordemRealizada;
				
				$data = date("Y-m-d", strtotime("-22 day"));
				$d22 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t22=$ordemTotal;
				$r22=$ordemRealizada;
		
				$data = date("Y-m-d", strtotime("-23 day"));
				$d23 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t23=$ordemTotal;
				$r23=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-24 day"));
				$d24 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t24=$ordemTotal;
				$r24=$ordemRealizada;
 
 				$data = date("Y-m-d", strtotime("-25 day"));
				$d25 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t25=$ordemTotal;
				$r25=$ordemRealizada;
 
 				$data = date("Y-m-d", strtotime("-26 day"));
				$d26 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t26=$ordemTotal;
				$r26=$ordemRealizada;
 
 				$data = date("Y-m-d", strtotime("-27 day"));
				$d27 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t27=$ordemTotal;
				$r27=$ordemRealizada;
 
 				$data = date("Y-m-d", strtotime("-28 day"));
				$d28 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t28=$ordemTotal;
				$r28=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-29 day"));
				$d29 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t29=$ordemTotal;
				$r29=$ordemRealizada;
	
				$data = date("Y-m-d", strtotime("-30 day"));
				$d30 = date('d',strtotime($data));
				$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$data' " );
				$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$data' AND status='13' " );
				$t30=$ordemTotal;
				$r30=$ordemRealizada;
 
 
 

				
		?>	

 		<script type="text/javascript">
				 google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Dia',  'Ordem', 'Realzadas'],
					  ['<?php echo $d30;?>', <?php echo $t30;?>, <?php echo $r30;?>],
					  ['<?php echo $d29;?>', <?php echo $t29;?>, <?php echo $r29;?>],
					  ['<?php echo $d28;?>', <?php echo $t28;?>, <?php echo $r28;?>],
					  ['<?php echo $d27;?>', <?php echo $t27;?>, <?php echo $r27;?>],
					  ['<?php echo $d26;?>', <?php echo $t26;?>, <?php echo $r26;?>],
					  ['<?php echo $d25;?>', <?php echo $t25;?>, <?php echo $r25;?>],
					  ['<?php echo $d24;?>', <?php echo $t24;?>, <?php echo $r24;?>],
					  ['<?php echo $d23;?>', <?php echo $t23;?>, <?php echo $r23;?>],
					  ['<?php echo $d22;?>', <?php echo $t22;?>, <?php echo $r22;?>],
					  ['<?php echo $d21;?>', <?php echo $t21;?>, <?php echo $r21;?>],
					  ['<?php echo $d20;?>', <?php echo $t20;?>, <?php echo $r20;?>],
					  ['<?php echo $d19;?>', <?php echo $t19;?>, <?php echo $r19;?>],
					  ['<?php echo $d18;?>', <?php echo $t18;?>, <?php echo $r18;?>],
					  ['<?php echo $d17;?>', <?php echo $t17;?>, <?php echo $r17;?>],
					  ['<?php echo $d16;?>', <?php echo $t16;?>, <?php echo $r16;?>],
					  ['<?php echo $d15;?>', <?php echo $t15;?>, <?php echo $r15;?>],
					  ['<?php echo $d14;?>', <?php echo $t14;?>, <?php echo $r14;?>],
					  ['<?php echo $d13;?>', <?php echo $t13;?>, <?php echo $r13;?>],
					  ['<?php echo $d12;?>', <?php echo $t12;?>, <?php echo $r12;?>],
					  ['<?php echo $d11;?>', <?php echo $t11;?>, <?php echo $r11;?>],
					  ['<?php echo $d10;?>', <?php echo $t10;?>, <?php echo $r10;?>],
					  ['<?php echo $d9;?>', <?php echo $t9;?>, <?php echo $r9;?>],
					  ['<?php echo $d8;?>', <?php echo $t8;?>, <?php echo $r8;?>],
					  ['<?php echo $d7;?>', <?php echo $t7;?>, <?php echo $r7;?>],
					  ['<?php echo $d6;?>', <?php echo $t6;?>, <?php echo $r6;?>],
					  ['<?php echo $d5;?>', <?php echo $t5;?>, <?php echo $r5;?>],
					  ['<?php echo $d4;?>', <?php echo $t4;?>, <?php echo $r4;?>],
					  ['<?php echo $d3;?>', <?php echo $t3;?>, <?php echo $r3;?>],
					  ['<?php echo $d2;?>', <?php echo $t2;?>, <?php echo $r2;?>],
					  ['<?php echo $d1;?>', <?php echo $t1;?>, <?php echo $r1;?>],
					  ['<?php echo $d0;?>', <?php echo $t0;?>, <?php echo $r0;?>]
					]);
					var options = {
					  title: 'Ordem Serviços',
					  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
					  hAxis: {title: 'Dia', titleTextStyle: {color: 'red'}}
					};
					var chart = new google.visualization.ColumnChart(document.getElementById('orcamentototal'));
					chart.draw(data, options);
				  }
			</script>
			  	            
			<div class="col-md-12">   
				  <div class="box">
					<div class="box-header">
						 <div id="orcamentototal"></div><!--/chat do google-->
					</div><!-- /.box-header -->
				</div><!-- /.box  -->
			</div><!-- /.col-md-6"  -->
              
  	 </div><!-- /.row-->     
  </div><!-- /.row-->        
</section><!-- /.content -->
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
      
       <?php 
			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
			if($leitura){
				foreach($leitura as $mostra):
					$descricao= $mostra['nome'];
					$Id = $mostra['id'];
					$total = conta('contrato_ordem',"WHERE id AND Month(data)='$mesano[0]' 
											  AND Year(data)='$mesano[1]' AND tipo_coleta1='$Id'");
					if($total<>0){
						$nome[$contador] = $descricao;
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
				  title: 'Tipo de Coleta'
				};
				var chart = new google.visualization.PieChart(document.getElementById('coleta'));
				chart.draw(data, options);
			  }
			</script>
              
         <div class="col-md-6">   
          <div class="box">
                <div class="box-header">
                     <div id="coleta"></div><!--/chat do google-->
                </div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	</div><!-- /.col-md-6"  -->
        
		<?php 
			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
			if($leitura){
				foreach($leitura as $mostra):
					$descricao = $mostra['nome'];
					$Id = $mostra['id'];
					$total = conta('contrato_ordem',"WHERE id AND Month(data)='$mesano[0]' 
											  AND Year(data)='$mesano[1]' AND rota='$Id'");

					if($total<>0){
						$nome[$contador] = $descricao;
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
				  title: 'Rotas'
				};
				var chart = new google.visualization.PieChart(document.getElementById('rota'));
				chart.draw(data, options);
			  }
			</script>
              
         <div class="col-md-6">   
          <div class="box">
                <div class="box-header">
                     <div id="rota"></div><!--/chat do google-->
                </div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	</div><!-- /.col-md-6"  -->
      

  	 </div><!-- /.row-->     
  </div><!-- /.row-->        
</section><!-- /.content -->

</div><!-- /.content-wrapper -->