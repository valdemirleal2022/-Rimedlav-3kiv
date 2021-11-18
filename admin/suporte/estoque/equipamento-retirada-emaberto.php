<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$data1 = date( "Y/m/d" );
	$data2 = date( "Y/m/d" );

	$total = conta('estoque_equipamento_retirada',"WHERE id AND status='Em aberto'");
	$leitura = read('estoque_equipamento_retirada',"WHERE id AND status='Em aberto' ORDER BY data_entrega ASC");


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-equipamento-retirada-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		header( 'Location: ../admin/suporte/relatorio/relatorio-equipamento-retirada-excel.php' );
	}


	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		
		$total = conta('estoque_equipamento_retirada',"WHERE id AND data_entrega>='$data1' 
					 AND data_entrega<='$data2' AND status='Em aberto'");
		$leitura = read('estoque_equipamento_retirada',"WHERE id AND data_entrega>='$data1' 
					 AND data_entrega<='$data2' AND status='Em aberto' ORDER BY data_entrega DESC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>Equipamento Em Aberto</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Equipamento</a>
			</li>
			<li class="active">Retirada</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">

					
				 <div class="box-header">
             	  <div class="row">
            
					<div class="col-xs-10 col-md-7 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
						 
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
					<td align="center">Equipamento</td>
					<td align="center">Nome</td>
					<td align="center">Qua</td>
					<td align="center">Solicitação</td>
					<td align="center">Entrega</td>
					<td align="center">Tipo</td>
					<td align="center">Obs</td>
					<td align="center">Status</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$equipamentoId = $mostra['id_equipamento'];
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
				
				$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
				echo '<td>'.$equipamento['nome'].'</td>';
				
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';

				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.converteData($mostra['data_solicitacao']).'</td>';
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
				
				echo '<td>'.substr($mostra['observacao'],0,8).'</td>';
				
				echo '<td align="center">'.$mostra['status'].'</td>';
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/estoque/equipamento-retirada-editar&retiradaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/estoque/equipamento-retirada-editar&retiradaBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" />
              			</a>
				      </td>';
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/equipamento-retirada-editar&retiradaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
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