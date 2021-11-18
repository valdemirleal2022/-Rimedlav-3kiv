<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];


	if(!isset($_SESSION['inicio'])){
		$data1 = converteData1();
		$data2 = converteData2();
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
	}else{
		$data1=$_SESSION['inicio'];
		$data2=$_SESSION['fim'];
	}



	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
	
		$consultorId = $_POST['consultor'];
		$_SESSION['consultor']=$consultorId;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-visitas-canceladas-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$consultorId = $_POST['consultor'];
		$_SESSION['consultor']=$consultorId;

		header( 'Location: ../admin/suporte/relatorio/relatorio-visitas-canceladas-excel.php' );
	}

 	$total = conta('cadastro_visita',"WHERE id AND status='17' AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY orc_data ASC");
	$leitura = read('cadastro_visita',"WHERE id AND status='17' AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY orc_data ASC");

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
	 	
		$total = conta('cadastro_visita',"WHERE id AND status='17' AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY data ASC");
		$leitura = read('cadastro_visita',"WHERE id AND status='17' AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY data ASC");
		
		if(!empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId' ORDER BY interacao ASC");
		}

	}

	
?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Orçamento Cancelado</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Orçamentos</a></li>
           <li><a href="#">Cancelados</a></li>
         </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		 	<div class="box-header">
        	    <div class="col-xs-8 col-md-9 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
						 
						 
                         	<div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
						 
						 
                           <div class="form-group pull-left">
								<select name="consultor" class="form-control input-sm">
									<option value="">Consultor</option>
									<?php 
										$readContrato = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($consultorId == $mae['id']){
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
             </div>
        <!-- /.box-header -->
   
  <div class="box-body table-responsive">
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			
        <?php 
				
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Solicitação</td>
					<td align="center">Orçamento/Hora</td>
					<td align="center">Indicação</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Vendedor</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			

				echo '<td>'.substr($mostra['nome'],0,15).'</td>';
				echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_solicitacao']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_data']).'/'.$mostra['orc_hora'].'</td>';
	 
				$indicacaoId = $mostra['indicacao'];
				$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
				echo '<td>'.$indicacao['nome'].'</td>';
				
				echo '<td>'.substr($mostra['orc_residuo'],0,15).'</td>';
				
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.$consultor['nome'].'</td>';
				
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>';
				
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
					<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar" />
					</a>
					</td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Deletar" title="Deletar"  />
              			</a>
						</td>';
				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
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