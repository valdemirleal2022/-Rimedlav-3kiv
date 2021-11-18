<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}


	$data1 = converteData1();
	$data2 = converteData2();

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-compra-requisicao-pdf");';
		echo '</script>';
	}

	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-compra-requisicao-excel.php');
	}

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
	}


	$total = conta('estoque_material_requisicao',"WHERE id AND data>='$data1' 
											  AND data<='$data2'");
	$leitura = read('estoque_material_requisicao',"WHERE id AND data>='$data1' 
											  AND data<='$data2' ORDER BY data ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>


<div class="content-wrapper">
	<section class="content-header">
		<h1>Requisições</h1>
		<ol class="breadcrumb">
			<li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a>
			</li>
			<li><a href="#">Material</a>
			</li>
			<li class="active">Requisições</li>
		</ol>
	</section>

	<section class="content">
		
	  <div class="row">
		<div class="col-md-12">
		 <div class="box box-default">
					
					  
         <div class="box-header">
            <a href="painel.php?execute=suporte/compras/requisicao-editar" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				<small>Nova Requisição</small>
			 </a>
    	</div><!-- /.box-header -->

			<div class="box-header">	    
                    <div class="col-xs-10 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                       <div class="form-group pull-left">
                            <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        <div class="form-group pull-left">
                            <input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        
                       <div class="form-group pull-left">
                        	 <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>   
                       </div><!-- /.input-group -->  
                        <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>  
                        </div><!-- /.input-group -->
                          <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>  
                        </div><!-- /.input-group -->                              
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
					<td align="center">Id</td>
					<td align="center">Area</td>
					<td align="center">Solicitante</td>
					<td align="center">Data</td>
					<td align="center">Material</td>
					<td align="center">Q Solicitada</td>
					<td align="center">Q Liberada</td>
					<td align="center">Estoque</td>
					<td align="center">Status</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td align="center">'.$mostra['area'].'</td>';
				
				$solicitanteId = $mostra['solicitante'];
				$solicitante = mostra('usuarios',"WHERE id ='$solicitanteId'");
				echo '<td>'.$solicitante['nome'].'</td>';
	 
				echo '<td align="center">'.converteData($mostra['data']).'</td>';
				
				$materialId = $mostra['id_material'];
				$estoque = mostra('estoque_material',"WHERE id ='$materialId'");
				
				echo '<td>'.$estoque['nome'].'</td>';
			 	echo '<td align="center">'.$mostra['quantidade'].'</td>';
				echo '<td align="center">'.$mostra['quantidade_liberada'].'</td>';
				
				if($mostra['status']<>"Baixado"){
					if($estoque['estoque']>$mostra['quantidade']){
						echo '<td align="center"><span class="badge bg-blue">'.$estoque['estoque'].'</span></td>';

					}else{
						echo '<td align="center"><span class="badge bg-red">'.$estoque['estoque'].'</span></td>';
					}
				}else{
					echo '<td align="center">-</td>';
				}
			   
				echo '<td align="right">'.$mostra['status'].'</td>';
				
				if($mostra['status']<>"Baixado" ){
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/requisicao-editar&requisicaoEditar='.$mostra['id'].'">
							<img src="ico/editar.png" title="Editar" />
              			</a>
					</td>';
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/requisicao-editar&requisicaoBaixar='.$mostra['id'].'">
							<img src="ico/baixar.png"  title="Baixar" />
              			</a>
					</td>';
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/requisicao-editar&requisicaoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png"  title="Excluir" />
              			</a>
					</td>';
				
				}else{
					echo '<td align="center">-</td>';
					echo '<td align="center">-</td>';
						echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/requisicao-editar&requisicaoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png"  title="Excluir" />
              			</a>
					</td>';
				}
					
				
						
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