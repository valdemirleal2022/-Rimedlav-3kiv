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
		 
			//grafico de pizza 
        
            $mes = date('m/Y');
	        $mesano = explode('/',$mes);	
			$nome1='';$nome2='';$nome3='';$nome4='';$nome5='';$nome6='';$nome7='';$nome8='';$nome9='';$nome10='';$nome11='';
			$total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;$total8=0;$total9=0;$total10=0;$total11=0;
			$leitura = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
			if($leitura){
				foreach($leitura as $mostraInd):
					$nome= $mostraInd['nome'];
					$Id = $mostraInd['id'];
					$total = soma('contrato',"WHERE tipo='2' AND indicacao='$Id'",'valor_mensal');
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
				   title: 'Contratos por Indicação'
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
					$total = soma('contrato',"WHERE tipo='2' AND consultor='$Id'",'valor_mensal');
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
				  title: 'Contratos por Vendedor'
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
		//grafico de pizza - tipo de residuos
		$nome1='';$nome2='';$nome3='';$nome4='';$nome5='';$nome6='';$nome7='';$nome8='';$nome9='';$nome10='';$nome11='';
         $total1=0;$total2=0;$total3=0;$total4=0;$total5=0;$total6=0;$total7=0;$total8=0;$total9=0;$total10=0;$total11=0;
			$leitura = read('contrato_tipo',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraVend):
				
                    $nome= $mostraVend['nome'];
					$Id = $mostraVend['id'];
                
					$total = soma('contrato',"WHERE tipo='2' AND contrato_tipo='$Id'",'valor_mensal');
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
				  title: 'Contratos por Tipo'
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
					$leituraContrato = read('contrato',"WHERE tipo='2'");
                    if($leituraContrato){
                        foreach($leituraContrato as $mostraContrato):
                            $ClienteId = $mostraContrato['id_cliente'];
                            $valor=$mostraContrato['valor_mensal'];
                            $cliente= mostra('cliente',"WHERE id='$ClienteId' AND classificacao='$classId'");
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
				   title: 'Contatos por Classificacao'
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