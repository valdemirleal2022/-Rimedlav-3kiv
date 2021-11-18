<head>
    <meta charset="iso-8859-1">
</head>


 <?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}


	if(isset($_POST['relatorio-pdf'])){

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-locacao-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){

	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-locacao-excel.php');
	}


	$total = conta('contrato',"WHERE id AND cobrar_locacao='1'");
	$leitura = read('contrato',"WHERE id AND cobrar_locacao='1' ORDER BY inicio DESC");



	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Contratos com Locação</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contratos</li>
           <li>Locação</li>
         </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

     	<div class="box-header">	
			
                   
          </div><!-- /box-header-->  
	  
	  <div class="box-header">	
		  
                    <div class="col-xs-10 col-md-4 pull-right">
						
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
            
                        
							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->     
						 
						 
                    </form>  
                     
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
			
					
                  
          </div><!-- /box-header-->   
       

  
    <div class="box-body table-responsive">
    	<div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
   
	<?php 

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center">Coleta</td>
					<td align="center">Quant</td>
					<td align="center">Valor Mensal</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';

				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.substr($mostra['controle'],0,6).'</td>';
			
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';
		
				$contratoTipoId = $mostra['contrato_tipo'];
				$monstraContratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$monstraContratoTipo['nome'].'</td>';
				
				$contratoId = $mostra['id'];
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				$tipoColetaId = $contratoColeta['tipo_coleta'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$coleta['nome'].'</td>';
				echo '<td>'.$contratoColeta['quantidade'].'</td>';
		
				echo '<td align="right">'.(converteValor($mostra['valor_mensal'])).'</td>';
			 
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" Contrato" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar Contrato" class="tip" />
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