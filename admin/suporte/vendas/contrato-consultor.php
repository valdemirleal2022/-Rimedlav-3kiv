
 <?php 
	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}


	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['consultor']=$_POST['consultor'];
		$consultor = $_POST['consultor'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-consultor-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		$_SESSION['consultor']=$_POST['consultor'];
		$consultor = $_POST['consultor'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-consultor-excel.php');
	}

	
	if ( isset( $_POST[ 'pesquisar' ] ) ) {

		$consultor = $_POST['consultor'];
		$_SESSION['consultor']=$_POST['consultor'];
	
		$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status<>'9' AND consultor='$consultor' AND status<>'9'",'valor_mensal');
		$total = conta('contrato',"WHERE id AND tipo='2' AND consultor='$consultor' AND status<>'9'");
		$leitura = read('contrato',"WHERE id AND tipo='2' AND consultor='$consultor' AND status<>'9'  ORDER BY inicio ASC");

	}



	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Contratos por Consultor</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contratos</li>
           <li>Consultor</li>
         </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

     	<div class="box-header">	 
                  
			
                    <div class="col-xs-10 col-md-4 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                                   
                        <div class="form-group pull-left">
								<select name="consultor" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($consultor == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
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
					<td align="center">Tipo de Contrato</td>
					<td align="center">Coleta</td>
					<td align="center">Valor Mensal</td>
					<td align="center">Aprovação</td>
					<td align="center">Inicio</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.substr($mostra['controle'],0,6).'</td>';
			
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
		
				$contratoTipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
				
				$contratoId = $mostra['id'];
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				$tipoColetaId = $contratoColeta['tipo_coleta'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$coleta['nome'].'</td>';
		
				echo '<td align="right">'.(converteValor($mostra['valor_mensal'])).'</td>';
				echo '<td align="center">'.converteData($mostra['aprovacao']).'</td>';
				echo '<td align="center">'.converteData($mostra['inicio']).'</td>';

	
				if($mostra['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" title="Contrato Ativo"/></td>';
				}elseif($mostra['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" title="Contrato Suspenso"/></td>';
				}elseif($mostra['status']==7){
					echo '<td align="center"><img src="ico/contrato-protestado.png" title="Contrato Prorestado"/></td>';
				}elseif($mostra['status']==8){
					echo '<td align="center"><img src="ico/contrato-protestado.png" title="Contrato Serasa"/></td>';
				}
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar Contrato" class="tip" />
              			</a>
				      </td>';

			echo '</tr>';
		 endforeach;
		 
		if(!isset($_POST['pesquisar_numero'])){
			 echo '<tfoot>';
							echo '<tr>';
							echo '<td colspan="12">' . 'Total de registros : ' .  $total . '</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td colspan="12">' . 'Valor Total R$ : ' . number_format($valor_total,2,',','.') . '</td>';
							echo '</tr>';

			 echo '</tfoot>';
		 }
		
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