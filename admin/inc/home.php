<head>
    <meta charset="iso-8859-1">
</head>
       
 <div class="content-wrapper">
    <section class="content-header">
          <h1>Vendas Mensal <small>
             <?php
			 $mes = date('m/Y');
			 $mesano = explode('/',$mes);
			 echo $mes;				   
			?> 
          </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel</li>
          </ol>
        </section>


              <?php  
			
				$variavel = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']; 
						
				$tres = date('m/Y',strtotime('-3months'));
				$dois = date('m/Y',strtotime('-2months'));
				$um = date('m/Y',strtotime('-1months'));
				$atual = date('m/Y');
		
				$mes = date('m/Y');
				$mesano = explode('/',$mes);	
					
				$contrato00 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$mesano[0]' AND 
									Year(contrato)='$mesano[1]' AND situacao<>'4'" ,'valor');
				$contrato00 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$mesano[0]' AND 									Year(contrato)='$mesano[1]' AND situacao<>'4'",'valor');
				$orcamento00 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$mesano[0]'
									 AND Year(orc_data)='$mesano[1]'",'orc_valor');
				$venda00=$contrato00+$contrato00;

				$data = date("Y-m-d");
				$dvenda0 = date('d',strtotime($data));
				$tvenda0 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda0){
					$tvenda0=0;
							
				}

				$data = date("Y-m-d", strtotime("-1 day"));
				$dvenda1 = date('d',strtotime($data));
				$tvenda1 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda1){
					$tvenda1=0;
					
				}
				
				$data = date("Y-m-d", strtotime("-2 day"));
				$dvenda2 = date('d',strtotime($data));
				$tvenda2 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda2){
					$tvenda2=0;
					
				}
				
				$data = date("Y-m-d", strtotime("-3 day"));
				$dvenda3 = date('d',strtotime($data));
				$tvenda3 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda3){
					$tvenda3=0;
					
				}
				
				$data = date("Y-m-d", strtotime("-4 day"));
				$dvenda4 = date('d',strtotime($data));
				$tvenda4 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda4){
					$tvenda4=0;
					
				}
				
				$data = date("Y-m-d", strtotime("-5 day"));
				$dvenda5 = date('d',strtotime($data));
				$tvenda5 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda5){
					$tvenda5=0;
					
				}
				
				$data = date("Y-m-d", strtotime("-6 day"));
				$dvenda6 = date('d',strtotime($data));
				$tvenda6 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda6){
					$tvenda6=0;
					
				}
				
				$data = date("Y-m-d", strtotime("-7 day"));
				$dvenda7 = date('d',strtotime($data));
				$tvenda7 = soma('contrato',"WHERE tipo='2' AND emissao='$data'",'valor');
				if(!$tvenda7){
					$tvenda7=0;
					
				}
				$metadiaria='4000';

		?>
        
  <section class="content">
  <div class="row">
   <div class="col-md-12">  
 
       Vendas Mensal  : <?php echo $mesano[0].'/'.$mesano[1] .' - R$   '.converteValor($venda00);?>
      

 		<script type="text/javascript">
			 google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				 ['Mes',  'Meta', 'Vendas'],
				  ['<?php echo $dvenda7;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda7 ;?>], 
				  ['<?php echo $dvenda6;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda6 ;?>],
				  ['<?php echo $dvenda5;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda5 ;?>],
				  ['<?php echo $dvenda4;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda4 ;?>],
				  ['<?php echo $dvenda3;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda3 ;?>],
				  ['<?php echo $dvenda2;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda2 ;?>],
				  ['<?php echo $dvenda1;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda1 ;?>],
				  ['<?php echo $dvenda0;?>', <?php echo $metadiaria ;?>, <?php echo $tvenda0 ;?>],
				]);
				var options = {
				  title: 'Vendas Fechadas R$ : <?php echo converteValor($contrato00);?>',
				  hAxis: {title: 'Dia', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('diaria'));
				chart.draw(data, options);
			  }
		</script>
        
  		<div class="row">
            <div class="col-md-12">
              <div class="box">
                    <div class="box-header">
                        <div id="diaria"></div><!--/chat do google-->
                    </div><!-- /.box-header -->
                </div><!-- /.box  -->
            </div>
		 </div>
         </div>        
 	 </div>
 </section>
<section class="content">

	<div class="row">
      
           <?php 
				 
			//grafico de pizza 
		 
			$nome1='';$nome2='';$nome3='';$nome4='';$nome5='';$nome6='';$nome7='';$nome8='';$nome9='';$nome10='';$nome11='';
			$total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;$total8=0;$total9=0;$total10=0;$total11=0;
			$leitura = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
			if($leitura){
				foreach($leitura as $mostraInd):
					$nome= $mostraInd['nome'];
					$Id = $mostraInd['id'];
					$total = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$mesano[0]' 
											  AND Year(contrato)='$mesano[1]' AND indicacao='$Id'",'valor');
					if($total<>0){
						if(empty($nome1)){
							  $nome1=$nome;
							  $total1=$total;
						}elseif(empty($nome2)){ 
							  $nome2=$nome;
							  $total2=$total;
						}elseif(empty($nome3)){ 
							  $nome3=$nome;
							  $total3=$total;
						}elseif(empty($nome4)){ 
							  $nome4=$nome;	
							  $total4=$total;
						 }elseif(empty($nome5)){ 
							  $nome5=$nome;	
							  $total5=$total;
						 }elseif(empty($nome6)){ 
							  $nome6=$nome;
							  $total6=$total;
						 }elseif(empty($nome7)){ 
							  $nome7=$nome;
							  $total7=$total;
						 }elseif(!isset($nome8)){ 
							  $nome8=$nome;
							  $total8=$total;
						 }elseif(empty($nome9)){ 
							  $nome9=$nome;
							  $total9=$total;
						  }elseif(empty($nome10)){ 
							  $nome10=$nome;
							  $total10=$total;
						  }elseif(empty($nome11)){ 
							  $nome11=$nome;
							  $total11=$total;
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
				  ['Vendas', 'Mes Atual'],
				  	  <?php if($total1<>0){ ; 
					 			echo "[ '". $nome1 ."'," . $total1. "],";
					         } ; 
					  	    if($total2<>0){ ; 
					 			echo "[ '". $nome2 ."'," . $total2. "],";
					         } ;
					  		 if($total3<>0){ ; 
					 			echo "[ '". $nome3 ."'," . $total3. "],";
					         } ;
					        if($total4<>0){ ; 
					 			echo "[ '". $nome4 ."'," . $total4. "],";
					         } ;
					  		 if($total5<>0){ ; 
					 			echo "[ '". $nome5 ."'," . $total5. "],";
					         } ;
							 if($total6<>0){ ; 
					 			echo "[ '". $nome6 ."'," . $total6. "],";
					         } ;
							 if($total7<>0){ ; 
					 			echo "[ '". $nome7 ."'," . $total7. "],";
					         } ;
							  if($total8<>0){ ; 
					 			echo "[ '". $nome8 ."'," . $total8. "],";
					         } ;
							 if($total9<>0){ ; 
					 			echo "[ '". $nome9 ."'," . $total9. "],";
					         } ;
							 if($total10<>0){ ; 
					 			echo "[ '". $nome10 ."'," . $total10. "],";
					         } ;
							 if($total10<>0){ ; 
					 			echo "[ '". $nome10 ."'," . $total10. "],";
					         } ;
							 if($total11<>0){ ; 
					 			echo "[ '". $nome11 ."'," . $total11. "],";
					         } ;
						?>
				]);
				var options = {
				   is3D: true,
				   legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				   title: 'Vendas por Indicaçao'
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
    	 </div><!-- /.col-md-6  -->
        
		
       
 		<?php 
			//grafico de pizza - vendedores
			$nome1='';$nome2='';$nome3='';$nome4='';$nome5='';$nome6='';$nome7='';$nome8='';$nome9='';$nome10='';$nome11='';
			$total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;$total8=0;$total9=0;$total10=0;$total11=0;
			$leitura = read('contrato_consultor',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraVend):
					$nome= $mostraVend['nome'];
					$Id = $mostraVend['id'];
					$total = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$mesano[0]' 
											  AND Year(contrato)='$mesano[1]' AND consultor='$Id'",'valor');
					if($total<>0){
						if(empty($nome1)){
							  $nome1=$nome;
							  $total1=$total;
						}elseif(empty($nome2)){ 
							  $nome2=$nome;
							  $total2=$total;
						}elseif(empty($nome3)){ 
							  $nome3=$nome;
							  $total3=$total;
						}elseif(empty($nome4)){ 
							  $nome4=$nome;	
							  $total4=$total;
						 }elseif(empty($nome5)){ 
							  $nome5=$nome;	
							  $total5=$total;
						 }elseif(empty($nome6)){ 
							  $nome6=$nome;
							  $total6=$total;
						 }elseif(empty($nome7)){ 
							  $nome7=$nome;
							  $total7=$total;
						 }elseif(empty($nome8)){ 
							  $nome8=$nome;
							  $total8=$total;
						 }elseif(empty($nome9)){ 
							  $nome9=$nome;
							  $total9=$total;
						  }elseif(empty($nome10)){ 
							  $nome10=$nome;
							  $total10=$total;
						  }elseif(empty($nome11)){ 
							  $nome11=$nome;
							  $total11=$total;
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
				  ['Vendedor', 'Mes Atual'],
				  	  <?php if($total1<>0){ ; 
					 			echo "[ '". $nome1 ."'," . $total1. "],";
					         } ; 
					  	    if($total2<>0){ ; 
					 			echo "[ '". $nome2 ."'," . $total2. "],";
					         } ;
					  		 if($total3<>0){ ; 
					 			echo "[ '". $nome3 ."'," . $total3. "],";
					         } ;
					        if($total4<>0){ ; 
					 			echo "[ '". $nome4 ."'," . $total4. "],";
					         } ;
					  		 if($total5<>0){ ; 
					 			echo "[ '". $nome5 ."'," . $total5. "],";
					         } ;
							 if($total6<>0){ ; 
					 			echo "[ '". $nome6 ."'," . $total6. "],";
					         } ;
							 if($total7<>0){ ; 
					 			echo "[ '". $nome7 ."'," . $total7. "],";
					         } ;
							 if($total8<>0){ ; 
					 			echo "[ '". $nome8 ."'," . $total8. "],";
					         } ;
						?>
				]);
				var options = {
				  is3D: true,
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				  title: 'Vendas por Vendedor'
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
    	 </div><!-- /.col-md-6  -->
		
       
            
            <?php 
			//grafico de pizza - insetos
			$nome1='';$nome2='';$nome3='';$nome4='';$nome5='';$nome6='';$nome7='';$nome8='';$nome9='';$nome10='';
			$total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;$total8=0;$total9=0;$total10=0;
			$leitura = read('contrato_contrato',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraInseto):
					$nome= $mostraInseto['nome'];
					$insetoId = $mostraInseto['id'];
					$total=0;
					$leitura = read('contrato',"WHERE tipo='2' AND Month(contrato)='$mesano[0]' 
												AND  Year(contrato)='$mesano[1]'");
					foreach($leitura as $mostracontrato):
						$contratoId = $mostracontrato['id'];
						$totalIns= soma('contrato_inseto',"WHERE id_inseto='$insetoId' AND id_contrato='$contratoId'",'valor');
						$total=$total+$totalIns;
					endforeach;
				 	 if($total<>0){
						if(empty($nome1)){
							  $nome1=$nome;
							  $total1=$total;
						}elseif(empty($nome2)){ 
							  $nome2=$nome;
							  $total2=$total;
						}elseif(empty($nome3)){ 
							  $nome3=$nome;
							  $total3=$total;
						}elseif(empty($nome4)){ 
							  $nome4=$nome;	
							  $total4=$total;
						 }elseif(empty($nome5)){ 
							  $nome5=$nome;	
							  $total5=$total;
						 }elseif(empty($nome6)){ 
							  $nome6=$nome;
							  $total6=$total;
						 }elseif(empty($nome7)){ 
							  $nome7=$nome;
							  $total7=$total;
						 }elseif(empty($nome8)){ 
							  $nome8=$nome;
							  $total8=$total;
						 }elseif(empty($nome9)){ 
							  $nome9=$nome;
							  $total9=$total;
						  }elseif(empty($nome10)){ 
							  $nome10=$nome;
							  $total10=$total;
						  }elseif(empty($nome11)){ 
							  $nome11=$nome;
							  $total11=$total;
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
				  ['Insetos', 'Mes Atual'],
				  	  <?php if($total1<>0){ ; 
					 			echo "[ '". $nome1 ."'," . $total1. "],";
					         } ; 
					  	    if($total2<>0){ ; 
					 			echo "[ '". $nome2 ."'," . $total2. "],";
					         } ;
					  		 if($total3<>0){ ; 
					 			echo "[ '". $nome3 ."'," . $total3. "],";
					         } ;
					        if($total4<>0){ ; 
					 			echo "[ '". $nome4 ."'," . $total4. "],";
					         } ;
					  		 if($total5<>0){ ; 
					 			echo "[ '". $nome5 ."'," . $total5. "],";
					         } ;
							 if($total6<>0){ ; 
					 			echo "[ '". $nome6 ."'," . $total6. "],";
					         } ;
							 if($total7<>0){ ; 
					 			echo "[ '". $nome7 ."'," . $total7. "],";
					         } ;
							 if($total8<>0){ ; 
					 			echo "[ '". $nome8 ."'," . $total8. "],";
					         } ;
						?>
				]);
				var options = {
				   is3D: true,
				   legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				   title: 'Vendas por Inseto'
				};
				var chart = new google.visualization.PieChart(document.getElementById('inseto'));
				chart.draw(data, options);
			  }
			</script>
            
            
            <div class="col-md-6">   
          	<div class="box">
         		<div class="box-header">
        		  <div id="inseto"></div><!--/chat do google-->
          		</div><!-- /.box-header -->
    		</div><!-- /.box  -->
    	 </div><!-- /.col-md-6  -->
      
            
          <?php 
			//grafico de pizza - classificacao
			$nome1='';$nome2='';$nome3='';$nome4='';$nome5='';$nome6='';$nome7='';$nome8='';$nome9='';$nome10='';
			$total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;$total8=0;$total9=0;$total10=0;
			$leitura = read('cliente_classificacao',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraClass):
					$nome= $mostraClass['nome'];
					$classId = $mostraClass['id'];
					$total=0;
					$leitura = read('contrato',"WHERE tipo='2' AND Month(contrato)='$mesano[0]' 
												AND  Year(contrato)='$mesano[1]'");
					foreach($leitura as $mostracontrato):
						$ClienteId = $mostracontrato['id_cliente'];
						$valor=$mostracontrato['valor'];
						$cliente= read('cliente',"WHERE id='$ClienteId' AND classificacao='$classId'",'valor');
						if($cliente){
							$total=$total+$valor;
						}
					endforeach;
				 	 if($total<>0){
						if(empty($nome1)){
							  $nome1=$nome;
							  $total1=$total;
						}elseif(empty($nome2)){ 
							  $nome2=$nome;
							  $total2=$total;
						}elseif(empty($nome3)){ 
							  $nome3=$nome;
							  $total3=$total;
						}elseif(empty($nome4)){ 
							  $nome4=$nome;	
							  $total4=$total;
						 }elseif(empty($nome5)){ 
							  $nome5=$nome;	
							  $total5=$total;
						 }elseif(empty($nome6)){ 
							  $nome6=$nome;
							  $total6=$total;
						 }elseif(empty($nome7)){ 
							  $nome7=$nome;
							  $total7=$total;
						 }elseif(empty($nome8)){ 
							  $nome8=$nome;
							  $total8=$total;
						 }elseif(empty($nome9)){ 
							  $nome9=$nome;
							  $total9=$total;
						  }elseif(empty($nome10)){ 
							  $nome10=$nome;
							  $total10=$total;
						  }elseif(empty($nome11)){ 
							  $nome11=$nome;
							  $total11=$total;
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
				  	  <?php if($total1<>0){ ; 
					 			echo "[ '". $nome1 ."'," . $total1. "],";
					         } ; 
					  	    if($total2<>0){ ; 
					 			echo "[ '". $nome2 ."'," . $total2. "],";
					         } ;
					  		 if($total3<>0){ ; 
					 			echo "[ '". $nome3 ."'," . $total3. "],";
					         } ;
					        if($total4<>0){ ; 
					 			echo "[ '". $nome4 ."'," . $total4. "],";
					         } ;
					  		 if($total5<>0){ ; 
					 			echo "[ '". $nome5 ."'," . $total5. "],";
					         } ;
							 if($total6<>0){ ; 
					 			echo "[ '". $nome6 ."'," . $total6. "],";
					         } ;
							 if($total7<>0){ ; 
					 			echo "[ '". $nome7 ."'," . $total7. "],";
					         } ;
							 if($total8<>0){ ; 
					 			echo "[ '". $nome8 ."'," . $total8. "],";
					         } ;
						?>
				]);
				var options = {
				   is3D: true,
				   legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
				   title: 'Vendas por Classificacao'
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