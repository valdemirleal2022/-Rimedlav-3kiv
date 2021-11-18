<?php

	if(isset($_POST['pesquisar'])){
		$rotaRoteiro = $_POST['rota'];
	}

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Pesagem por Rota</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Pesagem</a></li>
            <li>Rotas</a></li>
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
                            <select name="rota" class="form-control input-sm">
                                <option value="">Selecione Rota</option>
                                <?php 
                                    $readBanco = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$readBanco){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readBanco as $mae):
                                           if($rotaRoteiro == $mae['id']){
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
                   
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
 
       </div><!-- /box-header-->   

   
    <div class="box-body table-responsive">

  <?php
				
				$data = date("Y-m-d");
				$d0 = date('d',strtotime($data));
	   			$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t0=$pesagem;
	   
	   
	            $data = date("Y-m-d", strtotime("-1 day"));
				$d1 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t1=$pesagem;
		   
	
				$data = date("Y-m-d", strtotime("-2 day"));
				$d2 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t2=$pesagem;
	
				$data = date("Y-m-d", strtotime("-3 day"));
				$d3 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t3=$pesagem;
	
				$data = date("Y-m-d", strtotime("-4 day"));
				$d4 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t4=$pesagem;
	
				$data = date("Y-m-d", strtotime("-5 day"));
				$d5 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t5=$pesagem;
	
				$data = date("Y-m-d", strtotime("-6 day"));
				$d6 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t6=$pesagem;
	
				$data = date("Y-m-d", strtotime("-7 day"));
				$d7 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t7=$pesagem;
	
				$data = date("Y-m-d", strtotime("-8 day"));
				$d8 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t8=$pesagem;
	
				$data = date("Y-m-d", strtotime("-9 day"));
				$d9 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t9=$pesagem;
	
				$data = date("Y-m-d", strtotime("-10 day"));
				$d10 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t10=$pesagem;
	
				$data = date("Y-m-d", strtotime("-10 day"));
				$d10 = date('d',strtotime($data));
				$pesagem = soma('veiculo_liberacao',"WHERE id AND saida='$data' AND rota='$rotaRoteiro'",'pesagem');
				$t10=$pesagem;
	
				$data = date("Y-m-d", strtotime("-11 day"));
				$d11 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t11=$pesagem;
	   
	   			$data = date("Y-m-d", strtotime("-12 day"));
				$d12 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t12=$pesagem;

				$data = date("Y-m-d", strtotime("-13 day"));
				$d13 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t13=$pesagem;
				
				$data = date("Y-m-d", strtotime("-14 day"));
				$d14 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t14=$pesagem;
	
				$data = date("Y-m-d", strtotime("-15 day"));
				$d15 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t15=$pesagem;
	
				$data = date("Y-m-d", strtotime("-16 day"));
				$d16 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t16=$pesagem;
	
				$data = date("Y-m-d", strtotime("-17 day"));
				$d17 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t17=$pesagem;
	
				$data = date("Y-m-d", strtotime("-18 day"));
				$d18 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t18=$pesagem;
	   
				$data = date("Y-m-d", strtotime("-19 day"));
				$d19 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t19=$pesagem;
	
				$data = date("Y-m-d", strtotime("-20 day"));
				$d20 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t20=$pesagem;
	
				$data = date("Y-m-d", strtotime("-21 day"));
				$d21 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t21=$pesagem;
				
				$data = date("Y-m-d", strtotime("-22 day"));
				$d22 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t22=$pesagem;
		
				$data = date("Y-m-d", strtotime("-23 day"));
				$d23 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t23=$pesagem;
	
				$data = date("Y-m-d", strtotime("-24 day"));
				$d24 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t24=$pesagem;
 
 				$data = date("Y-m-d", strtotime("-25 day"));
				$d25 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t25=$pesagem;
 
 				$data = date("Y-m-d", strtotime("-26 day"));
				$d26 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t26=$pesagem;
 
 				$data = date("Y-m-d", strtotime("-27 day"));
				$d27 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t27=$pesagem;
 
 				$data = date("Y-m-d", strtotime("-28 day"));
				$d28 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t28=$pesagem;
	
				$data = date("Y-m-d", strtotime("-29 day"));
				$d29 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t29=$pesagem;
	
				$data = date("Y-m-d", strtotime("-30 day"));
				$d30 = date('d',strtotime($data));
				$pesagem = conta( 'veiculo_liberacao', "WHERE id AND saida='$data' AND rota='$rotaRoteiro'" );
				$t30=$pesagem;
 
				
		?>	

 		<script type="text/javascript">
				 google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Dia',  'Pesagem'],
					  ['<?php echo $d30;?>', <?php echo $t30;?>],
					  ['<?php echo $d29;?>', <?php echo $t29;?>],
					  ['<?php echo $d28;?>', <?php echo $t28;?>],
					  ['<?php echo $d27;?>', <?php echo $t27;?>],
					  ['<?php echo $d26;?>', <?php echo $t26;?>],
					  ['<?php echo $d25;?>', <?php echo $t25;?>],
					  ['<?php echo $d24;?>', <?php echo $t24;?>],
					  ['<?php echo $d23;?>', <?php echo $t23;?>],
					  ['<?php echo $d22;?>', <?php echo $t22;?>],
					  ['<?php echo $d21;?>', <?php echo $t21;?>],
					  ['<?php echo $d20;?>', <?php echo $t20;?>],
					  ['<?php echo $d19;?>', <?php echo $t19;?>],
					  ['<?php echo $d18;?>', <?php echo $t18;?>],
					  ['<?php echo $d17;?>', <?php echo $t17;?>],
					  ['<?php echo $d16;?>', <?php echo $t16;?>],
					  ['<?php echo $d15;?>', <?php echo $t15;?>],
					  ['<?php echo $d14;?>', <?php echo $t14;?>],
					  ['<?php echo $d13;?>', <?php echo $t13;?>],
					  ['<?php echo $d12;?>', <?php echo $t12;?>],
					  ['<?php echo $d11;?>', <?php echo $t11;?>],
					  ['<?php echo $d10;?>', <?php echo $t10;?>],
					  ['<?php echo $d9;?>', <?php echo $t9;?>],
					  ['<?php echo $d8;?>', <?php echo $t8;?>],
					  ['<?php echo $d7;?>', <?php echo $t7;?>],
					  ['<?php echo $d6;?>', <?php echo $t6;?>],
					  ['<?php echo $d5;?>', <?php echo $t5;?>],
					  ['<?php echo $d4;?>', <?php echo $t4;?>],
					  ['<?php echo $d3;?>', <?php echo $t3;?>],
					  ['<?php echo $d2;?>', <?php echo $t2;?>],
					  ['<?php echo $d1;?>', <?php echo $t1;?>],
					  ['<?php echo $d0;?>', <?php echo $t0;?>]
					]);
					var options = {
					  title: 'Pesagem',
					  legend:{position: 'right', textStyle: {color: 'black', fontSize: 8}},
					  hAxis: {title: 'Dia', titleTextStyle: {color: 'red'}}
					};
					var chart = new google.visualization.ColumnChart(document.getElementById('pesagem'));
					chart.draw(data, options);
				  }
			</script>
			  	            
			<div class="col-md-12">   
				  <div class="box">
					<div class="box-header">
						 <div id="pesagem"></div><!--/chat do google-->
					</div><!-- /.box-header -->
				</div><!-- /.box  -->
			</div><!-- /.col-md-6"  -->


  	 </div><!-- /.row-->     
  </div><!-- /.row-->        
</section><!-- /.content -->

</div><!-- /.content-wrapper -->