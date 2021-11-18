<?php

	if(isset($_POST['data1'])){
		$data1 = $_POST['data1'];
		$data2 = $_POST['data2'];
	}else{
		$data1 = date( "Y/m/d" );
		$data2 = date( "Y/m/d" );
	}

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Rentabilidade por Rota</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Rentabilidade</a></li>
            <li>Rota</a></li>
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
				$totatOrdem=0;
				$totatRealizada=0;
				$valorTotal=0;
				$peso=0;
			
				$leituraOrdem = read('contrato_ordem',"WHERE rota='$rotaId' AND data>='$data1' AND data<='$data2'");
			
				if($leituraOrdem){
					foreach($leituraOrdem as $ordem):
					
						$tipoColetaId = $ordem['tipo_coleta1'];
						$contratoId = $ordem['id_contrato'];
    					$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

						$contratoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$data1' AND vencimento>='$data1' AND id_contrato='$contratoId'  AND tipo_coleta='$tipoColetaId'" );

						$valor=$ordem['quantidade1']*$contratoColeta['valor_unitario'];
					
						if($ordem['status']=='13'){
							$totalRealizada=$totalRealizada+1;
							$valorTotal=$valorTotal+$valor;
						}
					
						$totatOrdem=$totatOrdem+1;
				
						$pesoMedio = $coleta['peso_medio']*$contratoColeta['quantidade'];
						
						$peso = $peso + $pesoMedio; 
					
					endforeach;
				}
			
				$pesagem = 0;
				$kilometragem = 0;
			
				$leituraVeiculoLiberacao = read('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' 
											AND rota ='$rotaId'");
				if($leituraVeiculoLiberacao){
					foreach($leituraVeiculoLiberacao as $veiculoLiberacao):
						$pesagem = $pesagem + $veiculoLiberacao['pesagem'];
						$kilometragem = $kilometragem  + ($veiculoLiberacao['km_chegada']-$veiculoLiberacao['km_saida']);
					endforeach;
				}
		
	 ?> 
            
				<div class="col-md-3">
				  <div class="box box-widget widget-user-2">
					<div class="widget-user-header bg-green">

					  <h3 class="widget-user-username"><?php echo $nome; ?></h3>
					</div>
					<div class="box-footer no-padding">
				  
					  <ul class="nav nav-stacked">
					  
						 <li><a href="#">Total Ordens<span class="pull-right badge bg-blue">
						 <?php echo $totalRealizada ?>
						</span></a></li>
						
						<li><a href="#">Valor <span class="pull-right badge bg-yellow">
						R$ <?php echo converteValor($valorTotal) ?>
						</span></a></li>
						  
						 <li><a href="#">Total Km <span class="pull-right badge bg-green">
						 <?php echo $kilometragem ?>
						</span></a></li>
						
						<li><a href="#">Total Peso<span class="pull-right badge bg-green">
						 <?php echo $pesagem ?>
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
