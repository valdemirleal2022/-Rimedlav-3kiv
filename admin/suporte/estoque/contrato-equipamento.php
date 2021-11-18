<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	
$total = conta('estoque_equipamento_retirada',"WHERE id AND status='Baixado' AND tipo<>'3'");
$leitura = read('estoque_equipamento_retirada',"WHERE id AND status='Baixado' AND tipo<>'3' ORDER BY id_contrato ASC");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-equipamento-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		header( 'Location: ../admin/suporte/relatorio/relatorio-contrato-equipamento-excel.php' );
	}

	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>Contrato com Equipamento</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Contrato</a>
			</li>
			<li class="active">Equipamento</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">

					
				 <div class="box-header">
             	  <div class="row">
            
					<div class="col-xs-10 col-md-6 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">

						 
							 <div class="form-group pull-left">
									<select name="tipo" class="form-control input-sm">
										<option value="">Selecione</option>
									  <option <?php if($tipo == '1') echo' selected="selected"';?> value="1">Troca</option>
									  <option <?php if($tipo == '2') echo' selected="selected"';?> value="2">Entrega</option>
									  <option <?php if($tipo == '3') echo' selected="selected"';?> value="3">Retirada</option>
									</select>
							   </div> 

                           <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                           </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
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
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Tipo Coleta</td>
					<td align="center">Min</td>
					<td align="center">Equipamento</td>
					<td align="center">Qt</td>
					<td align="center">Entrega</td>
					<td align="center">Tipo</td>
					<td align="center">Status</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				$equipamentoId = $mostra['id_equipamento'];
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
				
				echo '<td align="center">'.$mostra['id_contrato'].'</td>';
				
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';

				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				$tipoColetaId = $contratoColeta['tipo_coleta'];
				$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
				echo '<td>'.$coleta['nome'].'</td>';
				echo '<td>'.$contratoColeta['quantidade'].'</td>';
				
				$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
				echo '<td>'.$equipamento['nome'].'</td>';

				echo '<td align="right">'.$mostra['quantidade'].'</td>';

				echo '<td align="right">'.converteData($mostra['data_entrega']).'</td>';
				
				if($mostra['tipo'] == '1'){
					echo '<td>Troca</td>';
				}elseif($mostra['tipo'] == '2'){
					echo '<td>Entrega</td>';
				}elseif($mostra['tipo'] == '3'){
					echo '<td>Retirada</td>';
				}else{
					echo '<td>-</td>';
				} 
				
				echo '<td align="center">'.$mostra['status'].'</td>';
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId['id'].'">
			  				<img src="ico/visualizar.png" title="Visualizar Contrato" />
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

	    </div><!--/box-body table-responsive-->   
	  </div><!-- /.col-md-12 scrool -->
 	  </div><!-- /.box-body table-responsive -->
 	  	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->