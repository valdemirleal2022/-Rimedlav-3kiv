<head>
    <meta charset="iso-8859-1">
</head>

 <div class="content-wrapper">
     
        <section class="content-header">
          <h1>
            Previsão Mensal
            <small>
            
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

 <section class="content">
  <div class="row">
   <div class="col-md-12">  

      <?php  
				$variavel = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']; 

				$dataatual = date('m/Y');
				$ano = date('Y');
					
				$contrato = soma('contrato',"WHERE tipo='2' AND Year(contrato)='$ano' AND situacao<>'4'" ,'valor');
				$contrato = soma('contrato',"WHERE tipo='5' AND 	Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$venda_anual = $contrato+$contrato
				 
				 
		?>
 
       <h1>
       <small>
           Vendas Anual  : <?php echo $ano.' - R$   '.converteValor($venda_anual);?> 
           Serviços : <?php echo ' - R$   '.converteValor($contrato);?>
           Contratos : <?php echo ' - R$   '.converteValor($contrato);?>
       </small>
       </h1>
       
       
       <h2>
       <small>
         Gráficos Refente aos Serviços : <?php echo ' - R$   '.converteValor($contrato);?>
         </small>
        </h2>
      
 
  
 		<?php 
			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_consultor',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraVend):
					$nome_vendedor= $mostraVend['nome'];
					$Id = $mostraVend['id'];
					$total= soma('contrato',"WHERE tipo='2' AND Year(contrato)='$ano' AND situacao<>'4' AND consultor='$Id'",'valor');
					if($total<>0){
						$nome[$contador] = $nome_vendedor;
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
				  ['Vendedor', 'Mes Atual'],
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
    	</div><!-- /.col-md-6"  -->
        
        
		 <?php
			$contador=0;
			$nome = array();
			$valor = array();
			$leitura = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
			if($leitura){
				foreach($leitura as $mostraInd):
					$nome_indicacao= $mostraInd['nome'];
					$Id = $mostraInd['id'];
					$total = soma('contrato',"WHERE tipo='2' AND Year(contrato)='$ano' AND indicacao='$Id'
											  AND situacao<>'4'",'valor');
					if($total<>0){
						$nome[$contador] = $nome_indicacao;
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
				  ['Vendas', 'Mes Atual'],
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
    	</div><!-- /.col-md-6"  -->
                 
            <?php 
	 
			$contador=0;
			$nome = array();
			$valor = array();
			
			$leitura = read('cliente_classificacao',"WHERE id ORDER BY id ASC");
			if($leitura){
				foreach($leitura as $mostraClass):
					$classificacao= $mostraClass['nome'];
					$classId = $mostraClass['id'];
					$total=0;
					$leitura = read('contrato',"WHERE tipo='2' AND Year(contrato)='$ano' AND situacao<>'4'");
					foreach($leitura as $mostracontrato):
						$ClienteId = $mostracontrato['id_cliente'];
						$valor=$mostracontrato['valor'];
						$cliente= read('cliente',"WHERE id='$ClienteId' AND classificacao='$classId'");
						if($cliente){
							$total=$total+$valor;
						}
					endforeach;
					
				 	if($total<>0){
						$nome[$contador] = $classificacao;
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
    	</div><!-- /.col-md-6"  -->
    
    
        <?php
				// VENDAS ULTIMOS 03 MESES
				
				$jan = '01';
				$s01 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$jan' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c01 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$jan' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o01 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$jan' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v01=$s01+$c01;
				
				$fev = '02';
				$s02 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$fev' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c02 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$fev' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o02 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$fev' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v02=$s02+$c02;

				$mar = '03';
				$s03 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$mar' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c03 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$mar' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o03 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$mar' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v03=$s03+$c03;
				
				$abr = '04';
				$s04 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$abr' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c04 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$abr' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o04 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$abr' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v04=$s04+$c04;

				$mai = '05';
				$s05 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$mai' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c05 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$mai' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o05 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$mai' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v05=$s05+$c05;
				
				$jun = '06';
				$s06 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$jun' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c06 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$jun' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o06 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$jun' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v06=$s06+$c06;
				
				$jul = '07';
				$s07 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$jul' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c07 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$jul' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o07 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$jul' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v07=$s07+$c07;
				
				$ago = '08';
				$s08 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$ago' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c08 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$ago' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o08 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$ago' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v08=$s08+$c08;
				
				$set = '09';
				$s09 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$set' AND  
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c09 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$set' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o09 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$set' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v09=$s09+$c09;
				
				$out = '10';
				$s10 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$out' AND  
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c10 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$out' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o10 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$out' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v10=$s10+$c10;
				
				
				$nov = '11';
				$s11 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$nov' AND  
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c11 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$nov' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o11 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$nov' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v11=$s11+$c11;
				
				
				$dez = '12';
				$s12 = soma('contrato',"WHERE tipo='2' AND Month(contrato)='$dez' AND  
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$c12 = soma('contrato',"WHERE tipo='5' AND Month(contrato)='$dez' AND 
									Year(contrato)='$ano' AND situacao<>'4'",'valor');
				$o12 = soma('contrato',"WHERE tipo='1' AND Month(orc_data)='$dez' AND 
									Year(orc_data)='$ano'",'orc_valor');
				$v12=$s12+$c12;
				
				
		?>	

 		<script type="text/javascript">
				 google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Mes',  'Vendas', 'contratos', 'Contrato'],
					  ['<?php echo $jan;?>', <?php echo $v01;?>, <?php echo $s01;?>, <?php echo $c01;?>],
					  ['<?php echo $fev;?>', <?php echo $v02;?>, <?php echo $s02;?>, <?php echo $c02;?>],
					  ['<?php echo $mar;?>', <?php echo $v03;?>, <?php echo $s03;?>, <?php echo $c03;?>],
					  ['<?php echo $abr;?>', <?php echo $v04;?>, <?php echo $s04;?>, <?php echo $c04;?>],
					  ['<?php echo $mai;?>', <?php echo $v05;?>, <?php echo $s05;?>, <?php echo $c05;?>],
					  ['<?php echo $jun;?>', <?php echo $v06;?>, <?php echo $s06;?>, <?php echo $c06;?>],
					  ['<?php echo $jul;?>', <?php echo $v07;?>, <?php echo $s07;?>, <?php echo $c07;?>],
					  ['<?php echo $ago;?>', <?php echo $v08;?>, <?php echo $s08;?>, <?php echo $c08;?>],
					  ['<?php echo $set;?>', <?php echo $v09;?>, <?php echo $s09;?>, <?php echo $c09;?>],
					  ['<?php echo $out;?>', <?php echo $v10;?>, <?php echo $s10;?>, <?php echo $c10;?>],
					  ['<?php echo $nov;?>', <?php echo $v11;?>, <?php echo $s11;?>, <?php echo $c11;?>],
					  ['<?php echo $dez;?>', <?php echo $v12;?>, <?php echo $s12;?>, <?php echo $c12;?>]
					]);
					var options = {
					  title: 'Orçamento - Vendas - Serviços - Contratos:',
					  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
					  hAxis: {title: 'Mes', titleTextStyle: {color: 'red'}}
					};
					var chart = new google.visualization.ColumnChart(document.getElementById('vendasAnual'));
					chart.draw(data, options);
				  }
			</script>
  	            
        <div class="col-md-6">   
          <div class="box">
         	<div class="box-header">
        		 <div id="vendasAnual"></div><!--/chat do google-->
          	</div><!-- /.box-header -->
    	</div><!-- /.box  -->
    </div><!-- /.col-md-6"  -->
           
        <?php

				// Receita & Despesas
				$jan = '01';
				$r01 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$jan' AND 
							       Year(pagamento)='$ano'",'valor');
				$d01 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$jan' AND 
							       Year(pagamento)='$ano'",'valor');

				$fev = '02';
				$r02 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$fev' AND 
							       Year(pagamento)='$ano'",'valor');
				$d02 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$fev' AND 
							       Year(pagamento)='$ano'",'valor');

				$mar = '03';
				$r03 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$mar' AND 
							       Year(pagamento)='$ano'",'valor');
				$d03 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$mar' AND 
							       Year(pagamento)='$ano'",'valor');
				$abr = '04';
				$r04 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$abr' AND 
							       Year(pagamento)='$ano'",'valor');
				$d04 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$abr' AND 
							       Year(pagamento)='$ano'",'valor');
								   
				$mai = '05';
				$r05 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$mai' AND 
							       Year(pagamento)='$ano'",'valor');
				$d05 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$mai' AND 
							       Year(pagamento)='$ano'",'valor');
								   
				$jun = '06';
				$r06 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$jun' AND 
							       Year(pagamento)='$ano'",'valor');
				$d06 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$jun' AND 
							       Year(pagamento)='$ano'",'valor');
								   
				$jul = '07';
				$r07 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$jul' AND 
							       Year(pagamento)='$ano'",'valor');
				$d07 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$jul' AND 
							       Year(pagamento)='$ano'",'valor');
								   
				$ago = '08';
				$r08 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$ago' AND 
							       Year(pagamento)='$ano'",'valor');
				$d08 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$ago' AND 
							       Year(pagamento)='$ano'",'valor');
								   
				$set = '09';
				$r09 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$set' AND 
							       Year(pagamento)='$ano'",'valor');
				$d09 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$set' AND 
							       Year(pagamento)='$ano'",'valor');
								   
				$out = '10';
				$r10 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$out' AND 
							       Year(pagamento)='$ano'",'valor');
				$d10 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$out' AND 
							       Year(pagamento)='$ano'",'valor');
				
				$nov = '11';
				$r11 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$nov' AND 
							       Year(pagamento)='$ano'",'valor');
				$d11 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$nov' AND 
							       Year(pagamento)='$ano'",'valor');
								   
				$dez = '12';
				$r12 = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$dez' AND 
							       Year(pagamento)='$ano'",'valor');
				$d12 = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$dez' AND 
							       Year(pagamento)='$ano'",'valor');
 		 
				?>
                
 	<script type="text/javascript">
			 google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Mes',  'Receitas', 'Despesas'],
				  ['<?php echo $jan;?>', <?php echo $r01;?>, <?php echo $d01;?>],
				  ['<?php echo $fev;?>', <?php echo $r02;?>, <?php echo $d02;?>],
				  ['<?php echo $mar;?>', <?php echo $r03;?>, <?php echo $d03;?>],
				  ['<?php echo $abr;?>', <?php echo $r04;?>, <?php echo $d04;?>],
				  ['<?php echo $mai;?>', <?php echo $r05;?>, <?php echo $d05;?>],
				  ['<?php echo $jun;?>', <?php echo $r06;?>, <?php echo $d06;?>],
				  ['<?php echo $jul;?>', <?php echo $r07;?>, <?php echo $d07;?>],
				  ['<?php echo $ago;?>', <?php echo $r08;?>, <?php echo $d08;?>],
				  ['<?php echo $set;?>', <?php echo $r09;?>, <?php echo $d09;?>],
				  ['<?php echo $our;?>', <?php echo $r10;?>, <?php echo $d10;?>],
				  ['<?php echo $nov;?>', <?php echo $r11;?>, <?php echo $d11;?>],
				  ['<?php echo $dez;?>', <?php echo $r12;?>, <?php echo $d12;?>]
				]);
				var options = {
				  title: 'Receita  & Despesas & Atrasados:',
				  hAxis: {title: 'Mes', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('receita'));
				chart.draw(data, options);
			  }
	</script>
   	
     <div class="col-md-6">   
          <div class="box">
         	<div class="box-header">
        		 <div id="receita"></div><!--/chat do google-->
          	</div><!-- /.box-header -->
    	</div><!-- /.box  -->
    </div><!-- /.col-md-6"  -->
       
      
		<?php 
		
	 		$contador=0;
			$nome = array();
			$valor = array();
			$total_geral=0;
			
			$leitura = read('pagar_conta',"WHERE id ORDER BY nome ASC");
			
			if($leitura){
				foreach($leitura as $mostraInd):
					$descricao= $mostraInd['nome'];
					$Id = $mostraInd['id'];
					$total = soma('pagar',"WHERE Year(pagamento)='$ano' AND 
									status<>'Em Aberto' AND 
									id_conta='$Id'",'valor');
					if($total<>0){
						$nome[$contador] = $descricao;
						$valor[$contador] = $total; 
						$contador++;
					}
					
					$total_geral=$total_geral+$total;
				endforeach;
			}
  
		?>
    
       	<script type="text/javascript">
			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Despesas', 'Mes Atual'],
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
				  legend:{position: 'right', textStyle: {color: 'black', fontSize: 5}},
				  title: 'Despesas por Conta'
				};
				var chart=new google.visualization.PieChart(document.getElementById('contas'));
				chart.draw(data, options);
			}
		</script>
        
         <h2>
         <small>
       	Total de Despesas  : <?php echo $ano.' - R$   '.converteValor($total_geral);?> 
        </small>

       </h2>
       
       <div class="col-md-6">   
          <div class="box">
         	<div class="box-header">
        		 <div id="contas"></div><!--/chat do google-->
          	</div><!-- /.box-header -->
    	</div><!-- /.box  -->
       </div><!-- /.col-md-6"  -->
 </div><!-- /.box  -->

  
  </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->
       