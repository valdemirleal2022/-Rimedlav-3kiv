
 <?php 
	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if(isset($_POST['relatorio-pdf'])){
		$contratoTipo = $_POST['contrato_tipo'];
		$_SESSION['contratoTipo']= $_POST['contrato_tipo'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-email-contrato-ativos-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		$contratoTipo = $_POST['contrato_tipo'];
		$_SESSION['contratoTipo']= $_POST['contrato_tipo'];
	    header('Location: ../admin/suporte/relatorio/relatorio-email-contrato-ativos-excel.php');
	}

	 
	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$contratoTipo = $_POST['contrato_tipo'];
	}


	$total = conta('contrato',"WHERE id AND tipo='2' AND status='5'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' ORDER BY inicio ASC");

	if ( !empty($contratoTipo)) {
		$total = conta('contrato',"WHERE id AND tipo='2' AND contrato_tipo='$contratoTipo' AND status='5'");
		$leitura = read('contrato',"WHERE id AND tipo='2' AND contrato_tipo='$contratoTipo' AND status='5' ORDER BY inicio ASC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Contratos Email Ativos</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contratos</li>
           <li>Ativos</li>
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
								<select name="contrato_tipo" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('contrato_tipo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($contratoTipo == $mae['id']){
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
					<td align="center">Nome</td>
					<td align="center">Email</td>
					<td align="center">Email - Financeiro</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td align="left">'.substr($cliente['email'],0,30).'</td>';
				echo '<td align="left">'.substr($cliente['email_financeiro'],0,30).'</td>';
		
				$contratoTipoId = $mostra['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
				
		
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
		 
		
			 echo '<tfoot>';
							echo '<tr>';
							echo '<td colspan="12">' . 'Total de registros : ' .  $total . '</td>';
							echo '</tr>';
			 echo '</tfoot>';
	
		
		 echo '</table>';
		

		}
	?>
    
     
		  </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
       
        <div class="box-footer">
				<?php echo $_SESSION['cadastro'];
				unset($_SESSION['cadastro']);
				?> 
	  </div><!-- /.box-footer-->
     
     </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

  
</div><!-- /.content-wrapper -->