<?php 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1=$_POST['inico'];
		$data2=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-equipamentos-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1=$_POST['inico'];
		$data2=$_POST['fim'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-equipamentos-excel.php' );
	}

	
 	$leitura = read('estoque_equipamento',"WHERE id ORDER BY nome ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Estoque Equipamento</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Cadastro</a></li>
         <li class="active">Equipamento</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
			 <div class="box-header">
				<a href="painel.php?execute=suporte/estoque/equipamento-editar" class="btnnovo">
				  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				  <smal> Novo Equipamento </smal>
				 </a>
			</div><!-- /.box-header -->

     
            <div class="box-header">           
                    <!--PESQUISA DE RELATORIO-->
                    <div class="col-xs-10 col-md-2 pull-right">
                        <form name="form-pesquisa" method="post" class="form-inline" role="form">
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
					<td align="center">Equipamento</td>
					<td align="center">Estoque</td>
					<td align="center">Estoque Mínimo</td>
					<td align="center">Valor Unitário</td>
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td align="right">'.$mostra['estoque'].'</td>';
				echo '<td align="right">'.$mostra['estoque_minimo'].'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_unitario']).'</td>';

				echo '<td align="center">
					<a href="painel.php?execute=suporte/estoque/equipamento-editar&equipamentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/equipamento-editar&equipamentoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		
		 endforeach;
		
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