<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>

<?php 

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}
	
	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$consultorId = $_POST['consultor'];
		$indicacaoId = $_POST['indicacao'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		$_SESSION['indicacao']=$indicacaoId;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-orcamentos-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$consultorId = $_POST['consultor'];
		$indicacaoId = $_POST['indicacao'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		$_SESSION['indicacao']=$indicacaoId;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-orcamentos-excel.php' );
	}
  

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
		$indicacaoId = $_POST['indicacao'];

	}

	
	$leitura = read( 'cadastro_visita', "WHERE id AND status<>'0'AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY orc_data ASC" );
    $total = conta( 'cadastro_visita', "WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2'" );
	$valor_total = soma('cadastro_visita',"WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2'",'orc_valor');
	
	
		if(!empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status<>'0  AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId' ORDER BY orc_data ASC");
		}
		
		if(!empty($indicacaoId)){
			$total = conta('cadastro_visita',"WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2' AND indicacao='$indicacaoId'");
			$leitura = read('cadastro_visita',"WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2' AND indicacao='$indicacaoId' ORDER BY orc_data ASC");
		}
		
		if(!empty($indicacaoId) AND !empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status<>'0' AND orc_data>='$data1' AND orc_data<='$data2' AND indicacao='$indicacaoId' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status<>'0'  AND orc_data>='$data1' AND orc_data<='$data2' AND indicacao='$indicacaoId' AND consultor='$consultorId' ORDER BY orc_data ASC");
		}

	if(isset($_POST['nome'])){
		$pesquisa=strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		if(!empty($pesquisa)){
			$leitura =read('cadastro_visita',"WHERE id AND (
													nome LIKE '%$pesquisa%' OR
													nome_fantasia LIKE '%$pesquisa%' OR
													endereco LIKE '%$pesquisa%' OR 
													email LIKE '%$pesquisa%' OR 
													telefone LIKE '%$pesquisa%' OR
													celular LIKE '%$pesquisa%' OR
													contato LIKE '%$pesquisa%' OR
													cnpj LIKE '%$pesquisa%' OR
													cpf LIKE '%$pesquisa%'
													) 
													ORDER BY nome ASC");  
		}
	}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

		// 0 - visita
		// 1 - solicitacoes
		// 2 - orcamento

?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Orçamento</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Orçamento</a></li>
         </ol>
 </section>
 
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
		 <div class="box-header">
             <div class="row">
       	 
			 <div class="box-header">
			  
			    <div class="col-xs-6 col-md-3 pull-left">
				  <a href="painel.php?execute=suporte/orcamento/orcamento-editar" class="btnnovo">
					  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" /></a>
				  <small>Novo Orçamento</small>
				  
				  </div><!-- /col-md-3-->
	
           	    <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="nome" class="form-control input-sm" placeholder="Nome">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar" type="submit"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
			 </div><!-- /col-md-3-->
          	
           	<div class="box-header">       
                  <div class="col-xs-10 col-md-9 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                       	<div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
						 
                        
                           <div class="form-group pull-left">
								<select name="consultor" class="form-control input-sm">
									<option value="">Selecione o Consultor</option>
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
						<select name="indicacao" class="form-control input-sm">
							<option value="">Selecione o Indicação</option>
							<?php 
							$readContrato = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($indicacaoId == $mae['id']){
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
					<td align="center">Telefone</td>
					<td align="center">Celular</td>
					<td align="center">Valor</td>
					<td align="center">Solicitação</td>
					<td align="center">Orçamento</td>
					<td align="center">Indicação</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Vendedor</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
		
				$visitaId=$mostra['id'];
				$data = date("Y-m-d", strtotime("-30 day"));

	//			if($mostra['orc_data']<$data){
//					if($mostra['orc_data']<$data){
//						$cad['interacao']= date('Y/m/d H:i:s');
//						$cad['status'] = 17;
//						$cad['motivo_cancelamento']= 'Cancelamento automático';
//						update('cadastro_visita',$cad,"id = '$visitaId'");	
//					}
//				}
//			

				echo '<td>'. $mostra['id'] .'</td>';
			   echo '<td>'.substr($mostra['nome'],0,20).'</td>';
				echo '<td>'.substr($mostra['telefone'],0,8).'</td>';
				echo '<td>'.substr($mostra['celular'],0,8).'</td>';
		
				echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['data']).'</td>';
				echo '<td align="center">'.converteData($mostra['orc_data']).'</td>';
	 
				$indicacaoId = $mostra['indicacao'];
				$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
				echo '<td>'.substr($indicacao['nome'],0,8).'</td>';
				
				echo '<td>'.substr($mostra['orc_residuo'],0,10).'</td>';
				
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.substr($consultor['nome'],0,10).'</td>';
				
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td>'.$status['nome'].'</td>';
				
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
					<img src="ico/editar.png" title="Editar" />
					</a>
					</td>';
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEnviar='.$mostra['id'].'">
					<img src="ico/email.png" title="Enviar Email" />
					</a>
					</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoAprovar='.$mostra['id'].'">
							<img src="../admin/ico/aprovado.png" Contrato" title="Aprovar Contrato" class="tip" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoBaixar='.$mostra['id'].'">
							<img src="../admin/ico/baixar.png" title="Cancelar"  />
              			</a>
						</td>';
		

				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="14">' . 'Total de registros : ' .  $total . '</td>';
					echo '</tr>';
						   
					echo '<tr>';
					echo '<td colspan="14">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
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