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
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-patrimonio-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1=$_POST['inico'];
		$data2=$_POST['fim'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-patrimonio-excel.php' );
	}

	$leitura = read('estoque_patrimonio',"WHERE id ORDER BY codigo ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
	
	  <section class="content-header">
		  
		   <h1>Controle Patrimonial</h1>
		  
		   <ol class="breadcrumb">
			 <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
			 <li><a href="#">Cadastro</a></li>
			 <li class="active">Controle Patrimonial</li>
		   </ol>
		  
	 </section>
 
<section class="content">
	
<div class="row">
    <div class="col-md-12">  
     <div class="box box-default">
        
			 <div class="box-header">
				<a href="painel.php?execute=suporte/estoque/patrimonio-editar" class="btnnovo">
				  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				  <smal> Novo patrimonio </smal>
				 </a>
			 </div><!-- /.box-header -->

      
  
     <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
            
	<?php 

	if($leitura){
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Codigo</td>
					<td align="center">Item</td>
					<td align="center">Descrição</td>
					<td align="center">Departamento</td>
					<td align="center">Usuário</td>
					<td align="center">Valor</td>
					<td align="center">Data da compra</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['codigo'].'</td>';
				echo '<td>'.substr($mostra['item'],0,40).'</td>';
				echo '<td>'.substr($mostra['descricao'],0,40).'</td>';
				echo '<td>'.substr($mostra['departamento'],0,40).'</td>';
				echo '<td>'.substr($mostra['usuario'],0,40).'</td>';
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
		 		echo '<td align="right">'.converteData($mostra['data']).'</td>';
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/estoque/patrimonio-editar&patrimonioEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar"  />
              			</a>
				      </td>';
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/patrimonio-editar&patrimonioDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
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