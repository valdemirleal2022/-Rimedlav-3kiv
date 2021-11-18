<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}

	$data1 = converteData1();
	$data2 = converteData2();

	$total = conta('estoque_equipamento_fabricacao',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2'");
	$leitura = read('estoque_equipamento_fabricacao',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-equipamento-fabricacao-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		header( 'Location: ../admin/suporte/relatorio/relatorio-equipamento-fabricacao-excel.php' );
	}


	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$tipo=strip_tags(trim(mysql_real_escape_string($_POST['tipo'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['tipo']=$tipo;
		
		$total = conta('estoque_equipamento_fabricacao',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2'");
		$leitura = read('estoque_equipamento_fabricacao',"WHERE id AND data_solicitacao>='$data1' 
					 AND data_solicitacao<='$data2' ORDER BY data_solicitacao DESC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>Fabricação de Container</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Equipamento</a>
			</li>
			<li class="active">Fabricação</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					
					 <div class="box-header">
            <a href="painel.php?execute=suporte/estoque/equipamento-fabricacao-editar" class="btnnovo">
			  <img src="ico/novo.png"   title="Criar Novo"  />
			 </a>
    	</div><!-- /.box-header -->
    	

					
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
					<td align="center">Qua</td>
					<td align="center">Solicitação</td>
					<td align="center">Fab Inicio</td>
					<td align="center">Fab Termino</td>
					<td align="center">Status</td>
					<td align="center">Pin Inicio</td>
					<td align="center">Pin Término</td>
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
 
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.converteData($mostra['data_solicitacao']).'</td>';
				echo '<td align="right">'.date('d/m/Y H:i:s',strtotime($mostra['fabricacao_inicio'])).'</td>';
				echo '<td align="right">'.date('d/m/Y H:i:s',strtotime($mostra['fabricacao_termino'])).'</td>';
				
				if($mostra['fabricacao_status']=='0'){
					echo '<td>Aguardando</td>';
				}elseif($mostra['fabricacao_status']=='1'){
					echo '<td>Fabriicando</td>';
				}elseif($mostra['fabricacao_status']=='2'){
					echo '<td>Concluido</td>';
				}
				
				echo '<td align="right">'.date('d/m/Y H:i:s',strtotime($mostra['pintura_inicio'])).'</td>';
				echo '<td align="right">'.date('d/m/Y H:i:s',strtotime($mostra['pintura_termino'])).'</td>';
 	 	
				if($mostra['pintura_status']=='0'){
					echo '<td>Aguardando</td>';
				}elseif($mostra['pintura_status']=='1'){
					echo '<td>Pintando</td>';
				}elseif($mostra['pintura_status']=='2'){
					echo '<td>Concluido</td>';
				}
		
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/estoque/equipamento-fabricacao-editar&fabricacaoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/estoque/equipamento-fabricacao-editar&fabricacaoBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" />
              			</a>
				      </td>';
				
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/equipamento-fabricacao-editar&fabricacaoDeletar='.$mostra['id'].'">
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