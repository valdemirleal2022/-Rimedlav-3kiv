<?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-reposicao-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$veiculoId = $_POST['veiculo'];
		$_SESSION['data1']=$data1;
		$_SESSION['data2']=$data2;
		$_SESSION['veiculo']=$veiculoId;

		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-reposicao-excel.php' );
	}

	$leitura = read('veiculo_combustivel_reposicao',"WHERE id AND data>='$data1' AND data<='$data2'  
						ORDER BY data DESC"); 

	$total = conta('veiculo_combustivel_reposicao',"WHERE id AND data>='$data1' AND data<='$data2'  
						ORDER BY data DESC"); 

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];
	
?> 

<div class="content-wrapper">
	
  <section class="content-header">
	  
       <h1>Reposição</h1>
	  
       <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Reposição</li>
       </ol>
	  
 </section>
 
<section class="content">

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">

					<div class="box-header">
						<a href="painel.php?execute=suporte/veiculo/combustivel-reposicao-editar" class="btnnovo">
						  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
						    Nova Reposição
						 </a>
					
					</div>
					<!-- /.box-header -->
					
				 <div class="box-header">
             	  <div class="row">
            
					<div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
    
                        
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
                  
                </div><!-- /row-->  
         </div><!-- /box-header-->   
					
					
      <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">

		<?php 
 
 
			if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Combustível</td>
					<td align="center">Data</td>
					<td align="center">Quantidade</td>
					<td align="center">Valor Unitário</td>
					<td align="center">Valor Total</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$combustivelId = $mostra['id_combustivel'];
				$veiculoCombustivel = mostra('veiculo_combustivel',"WHERE id ='$combustivelId'");
				
				echo '<td>'.$veiculoCombustivel['nome'].'</td>';
				
				echo '<td>'.converteData($mostra['data']).'</td>';
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				
				echo '<td align="right">'.converteValor($mostra['valor_unitario']).'</td>';
				
				$total=$mostra['quantidade']*$mostra['valor_unitario'];
				
				echo '<td align="right">'.converteValor($total).'</td>';

				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/combustivel-reposicao-editar&reposicaoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir"  />
              			</a>
						</td>';
			echo '</tr>';
		
		 endforeach;

		echo '<tfoot>';
         			echo '<tr>';
                	echo '<td colspan="14">' . 'Total de Registros : ' .  $total . '</td>';
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
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->