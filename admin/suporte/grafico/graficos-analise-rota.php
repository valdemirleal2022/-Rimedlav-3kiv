<?php

	if(isset($_POST['data1'])){
		$data1 = $_POST['data1'];
	}else{
		$data1 = date( "Y/m/d" );
	}
	if(isset($_POST['data2'])){
		$data2 = $_POST['data2'];
	}else{
		$data2 = date( "Y/m/d" );
	}

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Análise por Rota</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Análise</a></li>
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
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
				   
				   		 <div class="form-group pull-left">
                            <input name="data2" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
				   
			 			<div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                   
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
 
       </div><!-- /box-header-->   

   
    <div class="box-body table-responsive">

		<?php 
  		$leitura = read(' contrato_rota',"WHERE id ORDER BY nome ASC");
		if($leitura){
			foreach($leitura as $mostra):
			
				$rotaId=$mostra['id'];
				$nome = substr($mostra['nome'],0,15) ;
				$totalOrdem=0;
				$totalRealizada=0;
				$valorTotal=0;
				$peso1=0;
				$peso2=0;
			
				$leituraOrdem = read('contrato_ordem',"WHERE rota='$rotaId' AND data>='$data1' AND data<='$data2'");
			
				if($leituraOrdem){
					foreach($leituraOrdem as $ordem):
					
						$tipoColetaId = $ordem['tipo_coleta1'];
						$contratoId = $ordem['id_contrato'];
    					$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

						$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$data1' AND vencimento>='$data2' AND id_contrato='$contratoId'  AND tipo_coleta='$tipoColetaId'" );

						$valor=$ordem['quantidade1']*$contratoColeta['valor_unitario'];
					
						if($ordem['status']=='13'){
							$totalRealizada=$totalRealizada+1;
							$valorTotal=$valorTotal+$valor;
						}
					
						$totalOrdem=$totalOrdem+1;
						
						//QUANTIDADE PREVISTA
						$pesoMedio1 = $coleta['peso_medio']*$contratoColeta['quantidade'];
					
						//QUANTIDADE REALIZADA
						$pesoMedio2 = $coleta['peso_medio']*$ordem['quantidade1'];
						
						$peso1 = $peso1 + $pesoMedio1; 
						$peso2 = $peso2 + $pesoMedio2; 
					
					endforeach;
				}
			
				$pesagem= soma('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' AND rota ='$rotaId'",'pesagem');
			
				$horas= soma('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' AND rota ='$rotaId'",'horas_trabalhadas');
			 
			
				
            ?> 
            
				<div class="col-md-3">
				  <div class="box box-widget widget-user-2">
					<div class="widget-user-header bg-green">

					  <h3 class="widget-user-username"><?php echo $nome; ?></h3>
					</div>
					<div class="box-footer no-padding">
				  
					  <ul class="nav nav-stacked">
					  
						<li><a href="#">Ordem Total<span class="pull-right badge bg-blue">
						 <?php echo $totalOrdem ?>
						</span></a></li>
						  
						 <li><a href="#">Ordem Realizadas<span class="pull-right badge bg-blue">
						 <?php echo $totalRealizada ?>
						</span></a></li>
						
						<li><a href="#">Valor <span class="pull-right badge bg-yellow">
						R$ <?php echo converteValor($valorTotal) ?>
						</span></a></li>
						
						<li><a href="#">Peso Previsto<span class="pull-right badge bg-green">
						 <?php echo $peso1 ?>
						</span></a></li>
						  
						<li><a href="#">Peso Realizada<span class="pull-right badge bg-green">
						 <?php echo $peso2 ?>
						</span></a></li>
						  
						  
						<li><a href="#">Pesagem Aterro<span class="pull-right badge bg-green">
						 <?php echo $pesagem ?>
						</span></a></li>
						  
						 <li><a href="#">Horas Trabalahadas<span class="pull-right badge bg-green">
						 <?php
				
							$hora = (int) ($horas/60);
							$minutos = $horas - ($hora*60) ;
							$totalHoras = $hora.'h ' . $minutos.'m';		
				
							echo $totalHoras ?>
						</span></a></li>

					  </ul>
					</div>
				  </div><!-- /.widget-user -->
				</div><!-- /.col -->

     <?php 
		endforeach;
		}
	  ?> 

 	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->
