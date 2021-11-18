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
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-material-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1=$_POST['inico'];
		$data2=$_POST['fim'];
		header( 'Location: ../admin/suporte/relatorio/relatorio-material-excel.php' );
	}
	

	$leitura = read('estoque_material',"WHERE id ORDER BY codigo ASC");
	
	if(isset($_POST['pesquisar'])){
		$materialTipoId = $_POST['materialTipo'];
		$leitura = read('estoque_material',"WHERE id AND id_tipo='$materialTipoId' ORDER BY codigo ASC");
	}
	
	if(isset($_POST['pesquisar_nome'])){
		$pesquisa=strip_tags(trim(mysql_real_escape_string($_POST['pesquisa'])));
		if(!empty($pesquisa)){
			$leitura =read('estoque_material',"WHERE id AND (nome LIKE '%$pesquisa%') 
													ORDER BY nome ASC");  
		}
	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Estoque Material</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Cadastro</a></li>
         <li class="active">Material</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
			 <div class="box-header">
				<a href="painel.php?execute=suporte/estoque/material-editar" class="btnnovo">
				  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				  <smal> Novo Material </smal>
				 </a>
			</div><!-- /.box-header -->
		 
		 

     
           <div class="box-header">   
			   
			      <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="pesquisa" class="form-control input-sm" placeholder="nome">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_nome" type="submit"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
			   
                  <div class="col-xs-10 col-md-5 pull-right">
					  
					  <div class="form-group pull-left">
					  
					   <form name="form-pesquisa" method="post" class="form-inline" role="form">
					  
						<select name="materialTipo" class="form-control input-sm">
							<option value="">Selecione o Tipo</option>
							<?php 
							$readContrato = read('estoque_material_tipo',"WHERE id ORDER BY codigo ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($materialTipoId == $mae['id']){
										echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].' - '.$mae['nome'].'</option>';
									}else{
										echo '<option value="'.$mae['id'].'">'.$mae['codigo'].' -  '.$mae['nome'].'</option>';
									}
									endforeach;	
								}
							?> 
							    </select>
					 	</div> 
                        
                        <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
						  
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
					<td align="center">Codigo</td>
					<td align="center">Material</td>
					<td align="center">Estoque</td>
					<td align="center">Mínimo</td>
					<td align="center">Unidade</td>
					<td align="center">Vl Unitário</td>
					<td align="center">Tipo</td>
					<td align="center">Localização</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['codigo'].'</td>';
				echo '<td>'.substr($mostra['nome'],0,40).'</td>';
		
				if($mostra['estoque']<=$mostra['estoque_minimo']){
					echo '<td align="center"><span class="badge bg-red">'. $mostra['estoque'] .'</span></td>';
				}else{
					echo '<td align="center"><span class="badge bg-green">'.$mostra['estoque'] .'</span></td>';
				}
		
				echo '<td align="right">'.$mostra['estoque_minimo'].'</td>';
				echo '<td align="right">'.$mostra['unidade'].'</td>';
		
				echo '<td align="right">'.converteValor($mostra['valor_unitario']).'</td>';
		
				$tipoId = $mostra['id_tipo'];
				$estoqueMaterial = mostra('estoque_material_tipo',"WHERE id ='$tipoId '");
				echo '<td>'.$estoqueMaterial['nome'].'</td>';
		
				echo '<td>'.substr($mostra['localizacao'],0,30).'</td>';
		
				if($mostra['status']==1){
                        echo '<td align="center">Ativo</td>';
                    }else{
                        echo '<td align="center">Inativo</td>';
                }
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/estoque/material-editar&materialEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar"  />
              			</a>
				      </td>';
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-editar&materialDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
              			</a>
						</td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-retirada-editar&retiradaBaixar='.$mostra['id'].'">
							<img src="ico/baixar.png" alt="Retirada" title="Retirada"  />
              			</a>
						</td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-reposicao-editar&reposicaoNova='.$mostra['id'].'">
							<img src="ico/inicio.png" alt="Reposicão" title="Reposicão"  />
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