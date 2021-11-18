<?php 



	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}
	
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$suporte = $_POST[ 'suporte' ];
		$status = $_POST[ 'status' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['suporte']=$suporte;
		$_SESSION['status']=$status;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-atendimento-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$suporte = $_POST[ 'suporte' ];
		$status = $_POST[ 'status' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['suporte']=$suporte;
		$_SESSION['status']=$status;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-atendimento-excel.php' );
	}


	$total = conta('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC ");

	$leitura = read('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao DESC, hora_solicitacao DESC");
 
	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$suporte = $_POST[ 'suporte' ];
		$status = $_POST[ 'status' ];

		$total = conta('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
		$leitura = read('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC");
		
	}
 
	if(!empty($status)){
		$total = conta('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status'");
		$leitura = read('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status' ORDER BY data_solicitacao ASC");
	}
 
	if(!empty($suporte)){
		$total = conta('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte'");
		$leitura = read('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
	}
		
	if(!empty($status) && !empty($suporte ) ){
		$total = conta('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' 
			AND status='$status' AND id_suporte='$suporte'");
		$leitura = read('ouvidoria_externa',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2'
			AND status='$status' AND id_suporte='$suporte' ORDER BY data_solicitacao ASC");
	}
 
	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?><head>
    <meta charset="iso-8859-1">
</head>
 

<div class="content-wrapper">
	
  <section class="content-header">
       <h1>Ouvidoria Externa</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Contrato</a>
            <li class="active">Ouvidoria</li>
          </ol>
 </section>

<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		   <div class="box-header">
			   
				<a href="painel.php?execute=suporte/atendimento/ouvidoria-externa-editar" class="btnnovo">
					<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
				</a>
       		
         	  </div><!-- /box-tools-->
		 
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
								<select name="suporte" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('ouvidoria_motivo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($suporte == $mae['id']){
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
								<select name="status" class="form-control input-sm">
							  <option value="">Selecione Status</option>
							  <option <?php if($status== 'Aguardando') echo' selected="selected"';?> value="Aguardando">Aguardando</option>
							  <option <?php if($status == 'OK') echo' selected="selected"';?> value="OK">OK</option>
							 </select>
						</div><!-- /.row -->
                                         
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
                  
        </div> <!-- /.box-header -->
	 
   <div class="box-body table-responsive">
     
 <?php
 
	if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Protocolo</td>
					<td align="center">Anonimato</td>
					<td align="center">Telefone</td>
					<td align="center">Email</td>
					<td align="center">Data</td>
					<td align="center">Dt Limite</td>
					<td align="center">Origem</td>
					<td align="center">Atendente</td>
					<td align="center">Solicitacao</td>
					<td align="center">Motivo</td>
					<td align="center">E</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['protocolo'].'</td>';
		
				if($mostra['anonimado']==1){
					echo '<td align="center">Sim</td>';
				}else{
					echo '<td>Nao</td>';
				 }
				echo '<td>'.$mostra['telefone'].'</td>';
	 			echo '<td>'.$mostra['email'].'</td>';
		 
			 
				echo '<td>'.converteData($mostra['data_solicitacao']).'</td>';
		 
				$dia1= strtotime( $mostra['data_limite'] );
				$dia2= strtotime( date('Y-m-d') );

				$dias= ($dia1 - $dia2 ) / 86400;
				if($mostra['status']=='Aguardando' ){
					if($dias==1){
					echo '<td align="center"><span class="badge bg-yellow">'.converteData($mostra['data_limite']).'</span></td>';
					}else if($dias<1){
						echo '<td align="center"><span class="badge bg-red">'.converteData($mostra['data_limite']).'</span></td>';
					}else{
						echo '<td align="center">'.converteData($mostra['data_limite']).'</td>';
					}
				}else{
					echo '<td align="center">'.converteData($mostra['data_limite']).'</td>';
				}
				
		 
				$origemId = $mostra['id_origem'];
				$origem = mostra('pedido_origem',"WHERE id ='$origemId '");
				echo '<td>'.$origem['nome'].'</td>';
			 
				echo '<td>'.substr($mostra['atendente_abertura'],0,10).'</td>';
				echo '<td>'.substr($mostra['solicitacao'],0,15).'</td>';
				
				$suporteId = $mostra['id_motivo'];
				$suporteMostra = mostra('ouvidoria_motivo',"WHERE id ='$suporteId '");
				echo '<td>'.substr($suporteMostra['nome'],0,15).'</td>';
				if($mostra['cliente_solicitou']==1){
					echo '<td align="center"><img src="ico/usuario.png" title="Solicitado pelo Cliente"/></td>';
				}else{
					echo '<td>-</td>';
				 }
				echo '<td>'.$mostra['status'].'</td>';
		
				echo '<td align="center">
				<a href="painel.php?execute=suporte/atendimento/ouvidoria-externa-editar&suporteEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
		
				echo '<td align="center">
				<a href="painel.php?execute=suporte/atendimento/ouvidoria-externa-editar&suporteBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png"  title="Baixar"  />
              			</a>
				      </td>';
			 
					
			echo '</tr>';
		 endforeach;
		echo '<tfoot>';
                        echo '<tr>';
                            echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
                        echo '</tr>';
                     echo '</tfoot>';
        
                 echo '</table>'; 

                }
           ?>

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
