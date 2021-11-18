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


	
	$total = conta('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");
	$leitura = read('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC, data DESC");

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$suporte = $_POST[ 'suporte' ];
		$setor = $_POST[ 'setor' ];
	 
		$total = conta('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");
		$leitura = read('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC, data DESC");

	}


	if(!empty($status)){
		$total = conta('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' AND status='$status'");
		$leitura = read('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' AND status='$status' ORDER BY data ASC");
	}

	if(!empty($setor)){
		$total = conta('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' AND setor='$setor'");
		$leitura = read('qualidade',"WHERE id AND data>='$data1' AND data<='$data2' AND setor='$setor' ORDER BY data ASC");
	}
		
 

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Qualidade</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Contrato</a>
            <li class="active">Qualidade</li>
          </ol>
 </section>
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		  <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                        <a href="painel.php?execute=suporte/qualidade/qualidade-editar" class="btnnovo">
                      <img src="ico/novo.png"  title="Criar Novo"  />
                 </a>
              </div>
            <!-- /.box-header -->
		 
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
								<select name="status" class="form-control input-sm">
							  <option value="">Selecione Status</option>
							  <option <?php if($status== 'Aguardando') echo' selected="selected"';?> value="Aguardando">Aguardando</option>
							  <option <?php if($status == 'OK') echo' selected="selected"';?> value="OK">OK</option>
							 </select>
						</div><!-- /.row -->
						 
						 <div class="form-group pull-left">
								<select name="setor" class="form-control input-sm">
									<option value="">Selecione o Setor</option>
									<?php 
										$readContrato = read('qualidade_setor',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($setor == $mae['id']){
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
                  
        </div> <!-- /.box-header -->
       
     
     <div class="box-body table-responsive">
     
 <?php
	
	 
	if($leitura){
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Data</td>
					<td align="center">Identificacao</td>
					<td align="center">Setor</td>
					<td align="center">Item</td>
					<td align="center">Origem</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				echo '<td>'.converteData($mostra['data']).'</td>';

				if($mostra['identificacao']==1){
					echo '<td align="center">AUDITORIA INTERNA</td>';
				}elseif($mostra['identificacao']==2){
					echo '<td align="center">RECLAMAÇÃO DE CLIENTES</td>';
				}elseif($mostra['identificacao']==3){
					echo '<td align="center">PONTUAL</td>';
				}else{
					echo '<td align="center">-</td>';
				}
		
				$setorId = $mostra['setor'];
				$setor = mostra('qualidade_setor',"WHERE id ='$setorId'");
				echo '<td>'.$setor['nome'].'</td>';
		 
				echo '<td>'.$mostra['item'].'</td>';
			 
				echo '<td>'.substr($mostra['origem'],0,30).'</td>';
		
				echo '<td>'.$mostra['status'].'</td>';
	 
				echo '<td align="center">
					<a href="painel.php?execute=suporte/qualidade/qualidade-editar&qualidadeEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" title="Editar" />
              			</a>
				      </td>';
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/qualidade/qualidade-editar&qualidadeBaixar='.$mostra['id'].'">
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
