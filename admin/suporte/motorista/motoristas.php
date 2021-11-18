<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$dataEmissao1 = converteData1();
	$dataEmissao2 = converteData2();

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$motorista = $_POST['motorista'];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
		$_SESSION['motorista']=$motorista;
		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-motorista-excel.php' );
		
	}

	if ( isset( $_POST[ 'relatorio-total-excel' ] ) ) {
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$motorista = $_POST['motorista'];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
		$_SESSION['parcial']=$parcial;
		header( 'Location: ../admin/suporte/relatorio/relatorio-veiculo-motorista-total-excel.php' );
	}


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$motorista = $_POST['motorista'];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;
		$_SESSION['motorista']=$motorista;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-motorista-pdf");';
		echo '</script>';
		
	}

	if ( isset( $_POST[ 'relatorio-total-pdf' ] ) ) {
		
		$dataEmissao1 = $_POST[ 'data1' ];
		$dataEmissao2 = $_POST[ 'data2' ];
		$_SESSION['dataEmissao1']=$dataEmissao1;
		$_SESSION['dataEmissao2']=$dataEmissao2;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-veiculo-motorista-total-pdf");';
		echo '</script>';
	}


	if (isset($_POST['parcial'])) {
		$parcial = "checked='CHECKED'";
		$_SESSION['parcial']="checked='CHECKED'";
		if(empty($_POST['parcial'])){
			$parcial= "";
			$_SESSION['parcial']="";
		}
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
		
?>

<div class="content-wrapper">
  <section class="content-header">
	  
       <h1>Motorista/Coletor</h1>
	  
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Veículo</a></li>
         <li class="active">Motorista/Coletor</li>
       </ol>
	  
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		 
			 <div class="box-header">
				 
				 	  <a href="painel.php?execute=suporte/veiculo/motorista-editar" class="btnnovo">
					  <img src="ico/novo.png" title="Criar Novo" class="tip" />
						<small>Novo Motorista</small>
					 </a>
				 
			</div><!-- /.box-header -->
 
		  <div class="box-header">
               <div class="row">
				   
				
                   
                 <div class="col-xs-10 col-md-9 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                             <div class="form-group pull-left">
                                  <input type="date" name="data1" value="<?php echo date('Y-m-d',strtotime($dataEmissao1)) ?>" class="form-control input-sm" >
						    </div>
                             <div class="form-group pull-left">
                                  <input type="date" name="data2" value="<?php echo date('Y-m-d',strtotime($dataEmissao2)) ?>" class="form-control input-sm" >
							</div>
                           
                             <div class="form-group pull-left">
								<select name="motorista" class="form-control input-sm">
									<option value="">Motorista/Coletor</option>
									<?php 
										$readBanco = read('veiculo_motorista',"WHERE id ORDER BY nome ASC");
										if(!$readBanco){
											echo '<option value="">Não temos Bancos no momento</option>';	
										}else{
											foreach($readBanco as $mae):
											   if($motorista == $mae['id']){
													echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
												 }else{
													echo '<option value="'.$mae['id'].'">'.$mae['nome'].'-'.$mae['tipo'].'</option>';
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-total-pdf" title="Relatório Total PDF"><i class="fa fa-file-pdf-o"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
						 
						 	<div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-total-excel" title="Relatório Sintético Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
						 
                    </form> 
                  </div><!-- /col-xs-10 col-md-12 pull-right-->
				   
				   
				<div class="col-xs-10 col-md-2 pull-right">
				  <form name="formPesquisa" method="post" class="form-inline " role="form">
                      <input type="checkbox"  name="parcial" <?PHP echo $parcial; ?>  class="minimal"  onclick="this.form.submit()"/>
                        <!--  <input type="checkbox" onclick="this.form.submit()"/>-->
                       <small> Parcial</small>
                     </form>
            	  </div><!-- /col-xs-6 col-md-5 pull-right--> 
            	  
	        </div><!-- /row-->   
       </div><!-- /box-header-->   
                    
     
     <div class="box-body table-responsive">
     	<div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
    
    
        
	<?php 
	 
		$total = conta('veiculo_motorista',"WHERE id");
		$leitura = read('veiculo_motorista',"WHERE id ORDER BY nome");

		if ($_SESSION['parcial']=="1") {
			$total = conta('veiculo_motorista',"WHERE id AND parcial='1'");
			$leitura = read('veiculo_motorista',"WHERE id AND parcial='1' ORDER BY nome");
		}
				
		if($leitura){
				echo '<table class="table table-hover">	
				
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td align="center">Tipo</td>
					<td align="center">Saldo</td>
					<td align="center">Habilitação</td>
					<td align="center">Vencimento</td>
					<td align="center">Parcial</td>
					<td>Status</td>
					
					<td align="center" colspan="3">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
		
				if($mostra['tipo']=='1'){
					echo '<td>Motorista</td>';
				}elseif($mostra['tipo']=='2'){
					echo '<td>Ajudante</td>';
				}else{
					echo '<td>-</td>';
				}
		
				echo '<td>'.$mostra['saldo'].'</td>';
		
				echo '<td>'.$mostra['habilitacao'].'</td>';
				echo '<td>'.converteData($mostra['vencimento']).'</td>';
		 
				if($mostra['parcial']=='1'){
					echo '<td>Parcial</td>';
				}else{
					echo '<td>-</td>';
				}
				
				$statusId = $mostra['status'];
				$status = mostra('funcionario_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>'; 
				
			
				echo '<td align="center">
						<a href="painel.php?execute=suporte/motorista/motorista-editar&motoristaEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/motorista/motorista-editar&motoristaDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir" />
              			</a>
						</td>';
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/motorista/motorista-editar&motoristaVisualizar='.$mostra['id'].'">
							<img src="ico/servico.png" title="Negligencia" />
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

	      </div><!--/col-md-12 scrool-->   
			</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->