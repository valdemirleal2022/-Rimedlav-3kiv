<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1=$_POST['inico'];
		$data2=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculos-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1=$_POST['inico'];
		$data2=$_POST['fim'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-realizadas-excel.php' );
	}

	
 	$leitura = read('veiculo',"WHERE id ORDER BY modelo ASC, placa DESC");
	$total = conta('veiculo',"WHERE id ORDER BY modelo ASC, placa DESC");

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Veiculos</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Cadastro</a></li>
         <li class="active">Veiculos</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
			 <div class="box-header">
				<a href="painel.php?execute=suporte/veiculo/veiculo-editar" class="btnnovo">
				  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				  <smal> Novo Veiculos </smal>
				 </a>
			</div><!-- /.box-header -->

     
            <div class="box-header">           
                    <!--PESQUISA DE RELATORIO-->
                    <div class="col-xs-10 col-md-5 pull-right">
                        <form name="form-pesquisa" method="post" class="form-inline" role="form">
                           
                           <div class="form-group pull-left">
                            <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
							</div><!-- /.input-group -->
						  
							<div class="form-group pull-left">
							<input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
							</div><!-- /.input-group -->
                           
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                            
                        </form>
                    </div>
                    <!-- /col-xs-10 col-md-5 pull-right-->
           </div> <!-- /box-header-->

  
     <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
            
	<?php 

	if($leitura){
		
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Modelo</td>
					<td align="center">Placa</td>
					<td align="center">Status</td>
					<td>Liberação</td>
					<td>Manutenção</td>
					<td>Abastecimento</td>
					<td>Pesagem</td>
					<td>Tarcógrafo</td>
					<td>Lavagem</td>
				
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['modelo'].'</td>';
				echo '<td>'.$mostra['placa'].'</td>';
		
				if($mostra['status']==1){
                        echo '<td align="center">Ativo</td>';
                    }else{
                        echo '<td align="center">Inativo</td>';
                }
				
				$veiculoId = $mostra['id'];
				
				$valor_total = conta('veiculo_liberacao',"WHERE id_veiculo='$veiculoId'");
				echo '<td align="right">'.$valor_total.'</td>';
		
				$valor_total = soma('veiculo_manutencao',"WHERE id_veiculo='$veiculoId'",'valor');
				echo '<td align="right">'.converteValor($valor_total).'</td>';
				
				$valor_total = soma('veiculo_abastecimento',"WHERE data>='$data1' AND data<='$data2' AND id_veiculo='$veiculoId'",'valor');
				echo '<td align="right">'.converteValor($valor_total).'</td>';
		
				$pesagem = soma('veiculo_liberacao',"WHERE saida>='$data1' AND saida<='$data2' AND id_veiculo='$veiculoId'",'pesagem');
				echo '<td align="right">'.converteValor($pesagem).'</td>';

				$tacografo = mostra('veiculo_tacografo',"WHERE id_veiculo='$veiculoId' ORDER BY data_prevista ASC");
				echo '<td align="right">'.converteData($tacografo['data_prevista']).'</td>';
		
				$lavagem= mostra('veiculo_lavagem',"WHERE  id AND id_veiculo='$veiculoId' ORDER BY data ASC");
				echo '<td align="right">'.converteData($lavagem['data']).'</td>';
				
				echo '<td align="center">
				<a href="painel.php?execute=suporte/veiculo/veiculo-editar&veiculoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/veiculo/veiculo-editar&veiculoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
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