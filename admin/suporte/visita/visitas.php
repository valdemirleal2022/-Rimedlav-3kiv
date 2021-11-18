
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
		$empresaAtual= $_POST['empresaAtual'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['empresaAtual']=$empresaAtual;
		$_SESSION['consultor']=$consultorId;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-visitas-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
		$empresaAtual= $_POST['empresaAtual'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		$_SESSION['empresaAtual']=$empresaAtual;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-visitas-excel.php' );
	}

	if(!isset(	$_SESSION['inicio'])){
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
	}else{
		$data1=$_SESSION['inicio'];
		$data2=$_SESSION['fim'];
	}

    $total = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' ORDER BY data ASC");
	$leitura = read('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' ORDER BY data ASC");

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
		$empresaAtual = $_POST['empresaAtual'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		
		$total = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' ORDER BY interacao ASC");
		$leitura = read('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' ORDER BY interacao ASC");
		
		if(!empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId' ORDER BY interacao ASC");
		}
		
		if(!empty($empresaAtual)){
			$total = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND empresa_atual='$empresaAtual'");
			$leitura = read('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND empresa_atual='$empresaAtual' ORDER BY interacao ASC");
		}
	}

if (isset( $_POST[ 'pesquisaNome' ] ) ) {
    $pesquisa = strip_tags( trim( mysql_real_escape_string( $_POST[ 'nome' ] ) ) );
    if ( !empty( $pesquisa ) ) {
        $leitura = read( 'cadastro_visita', "WHERE  id AND status='0' AND (nome LIKE '%$pesquisa%')
			                 ORDER BY nome ASC" );
        $total = conta( 'cadastro_visita', "WHERE  id AND status='0' AND (nome LIKE '%$pesquisa%')" );
        $pesquisaAtiva = 1;
    }

}

if ( isset( $_POST[ 'pesquisaEndereco' ] ) ) {
    $enderecoPesquisa = strip_tags( trim( mysql_real_escape_string( $_POST[ 'endereco' ] ) ) );
    if ( !empty( $enderecoPesquisa ) ) {
        $leitura = read( 'cadastro_visita', "WHERE  id AND status='0' AND (endereco LIKE '%$enderecoPesquisa%') ORDER BY endereco ASC, numero ASC" );
        $total = conta( 'cadastro_visita', "WHERE  id AND status='0' AND (endereco LIKE '%$enderecoPesquisa%')" );
        $pesquisaAtiva = 1;
    }
    $_SESSION[ 'enderecoPesquisa' ] = $enderecoPesquisa;
}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Cadastro de Visitas</h1>
        <ol class="breadcrumb">
            <li>Home</a>
             <li>Visitas</a>
             <li class="active">Cadastro</li>
        </ol>
    </section>

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
                    
                 <div class="box-header">
                   <a href="painel.php?execute=suporte/visita/visita-editar" class="btnnovo">
                    <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
                    </a>
                 </div>
                 <!-- /.box-header -->
      
                 <div class="box-header">
                     
                    <div class="col-xs-10 col-md-2 pull-left">
                        <form name="form-pesquisa" method="post" class="form-inline " role="form">
                            <div class="input-group">
                                <input type="text" name="nome" class="form-control input-sm" placeholder="Nome">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisaNome" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                <!-- /.input-group -->
                            </div>
                            <!-- /input-group-->
                        </form>
                    </div>
                    <!-- /col-xs-10 col-md-3 pull-left-->

                    <div class="col-xs-10 col-md-2 pull-left">
                        <form name="form-pesquisa" method="post" class="form-inline " role="form">
                            <div class="input-group">
                                <input type="text" name="endereco" class="form-control input-sm" placeholder="Endereço">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisaEndereco" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                <!-- /.input-group -->
                            </div>
                            <!-- /input-group-->
                        </form>
                    </div>
                    <!-- /col-xs-10 col-md-3 pull-left-->
               </div>
        <!-- /.box-header -->
      
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
							  <select name="empresaAtual" class="form-control input-sm">
								<option value="">Empresa atual</option>
									<?php 
									$readEmpresa = read('cadastro_visita_empresa_atual',"WHERE id ORDER BY nome ASC");
									if(!$readEmpresa){
												echo '<option value="">Nao temos empresa no momento</option>';	
									}else{
										foreach($readEmpresa as $mae):
										if($empresaAtual == $mae['id']){
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
                    <td align="center">Id</td>
					<td align="center">Nome</td>
                    <td align="center">Bairro</td>
                    <td align="center">Empresa Atual</td>
					<td align="center">Consultor</td>
					<td align="center">Ligação</td>
					<td align="center">Atendida</td>
					<td align="center">Data</td>
                    <td align="center">Interação</td>
					<td align="center" colspan="6">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			
			$visitaId=$mostra['id'];
			$data = date("Y-m-d", strtotime("-30 day"));
			
			if($mostra['data']<$data){
				$cad['interacao']= date('Y/m/d H:i:s');
				$cad['status'] = 18;
				$cad['motivo_cancelamento']= 'Cancelamento automático';
				update('cadastro_visita',$cad,"id = '$visitaId'");	
				header("Location: ".$_SESSION['url']);
			}
			
				echo '<td>'.$mostra['id'].'</td>';
                echo '<td>'.substr($mostra['nome'],0,15).'</td>';
                echo '<td>'.substr($mostra['bairro'],0,15).'</td>';
			    $empresaAtualId = $mostra['empresa_atual'];
				$empresaAtual = mostra('cadastro_visita_empresa_atual',"WHERE id ='$empresaAtualId '");
                echo '<td>'.$empresaAtual['nome'].'</td>';
			  
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td>'.$consultor['nome'].'</td>';

				if($mostra['ligacao']==1){
					 echo '<td>Sim</td>';
				}else{
					echo '<td>-</td>';
				}
			
				if($mostra['atendida']==1){
					 echo '<td>Sim</td>';
				}else{
					echo '<td>-</td>';
				}
			
			
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';

				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/visita/visita-editar&visitaEditar='.$mostra['id'].'">
							<img src="ico/editar.png" alt="Editar" title="Editar"  />
              			</a>
					</td>';
			
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-editar&visitaVisualizar='.$mostra['id'].'">
			  				<img src="../admin/ico/visualizar.png" alt="Visualiza" title="Visualiza Visita" />
              			</a>
				      </td>';	
			
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-editar&visitaDeletar='.$mostra['id'].'">
			  				<img src="../admin/ico/excluir.png" alt="Deletar" title="Deletar" />
              			</a>
				      </td>';	
			
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/visita/visita-editar&visitaCancelar='.$mostra['id'].'">
							<img src="../admin/ico/baixar.png" alt="Cancelar" title="Cancelar" />
              			</a>
					</td>';
				
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-editar&visitaEnviar='.$mostra['id'].'">
			  				<img src="../admin/ico/email.png" alt="Aviso" title="Enviar Mensagem Consultor"  />
              			</a>
				      </td>';	

			echo '</tr>';
		 endforeach;
            
         echo '<tfoot>';
     			echo '<tr>';
                	echo '<td colspan="16">' . 'Total de Registros : ' .  $total . '</td>';
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