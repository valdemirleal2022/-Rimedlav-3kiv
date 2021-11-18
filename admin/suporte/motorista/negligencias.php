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
		$motorista = $_POST[ 'motorista' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['motorista'] = $_POST[ 'motorista' ];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-motorista-negligencia-pdf");';
		echo '</script>';
	}
	
	
	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$motorista = $_POST[ 'motorista' ];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['motorista'] = $_POST[ 'motorista' ];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-motorista-negligencia-excel.php' );
	}
	

	if ( isset( $_POST[ 'relatorio-total-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	 		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['motorista'] = $_POST[ 'motorista' ];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-motorista-negligencia-total-excel.php' );
	}

	if ( isset( $_POST[ 'relatorio-total2-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	 		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['motorista'] = $_POST[ 'motorista' ];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-motorista-negligencia-total2-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$motorista = $_POST[ 'motorista' ];
	}

	$total = conta(' veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC ");
	$leitura = read('  veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC");

	if(!empty($motorista)){
		$total = conta(' veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motorista='$motorista' ORDER BY data ASC ");
		$leitura = read('  veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motorista='$motorista' ORDER BY data DESC");
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Premiação</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Motorista</a>
            <li class="active">Premiação</li>
          </ol>
 </section>
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		  <div class="box-header">
				 
				 	  <a href="painel.php?execute=suporte/motorista/negligencia-editar" class="btnnovo">
					  <img src="ico/novo.png" title="Criar Novo" class="tip" />
						<small>Novo Lançamento</small>
					 </a>
				 
			</div><!-- /.box-header -->
		 
		  <div class="box-header">
               <div class="row">
		 
		 	<div class="box-header">       
                  <div class="col-xs-10 col-md-8 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                            <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        <div class="form-group pull-left">
                            <input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
						 
						  <div class="form-group pull-left">
								<select name="motorista" class="form-control input-sm">
									<option value="">Selecione a Motorista</option>
									<?php 
										$readContrato = read('veiculo_motorista',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($motorista == $mae['id']){
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                          </div>   <!-- /.input-group -->
						 
						  <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-total-excel"><i class="fa fa-file-excel-o" title="Relatório Sintético Excel"></i></button>
                          </div>   <!-- /.input-group -->
						 
						   <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-total2-excel"><i class="fa fa-file-excel-o" title="Relatório Total de Motorista Excel"></i></button>
                          </div>   <!-- /.input-group -->
                            
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
        </div> <!-- /.box-header -->
				   
 </div><!-- /row-->   
</div><!-- /box-header-->      
     
 <div class="box-body table-responsive">
     	<div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  

 <?php
				
	$totalPontuacao=0;
				
	if($leitura){
		
				echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Motorista</td>
					<td align="center">Rota</td>
					<td align="center">Tipo</td>
					<td align="center">Negligencia</td>
					<td align="center">Pontuação</td>
					<td align="center">Data</td>
					
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$id = $mostra['id'];

				$negligenciaId = $mostra['id_negligencia'];
				$motoristaId = $mostra['id_motorista'];
				$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
		
				echo '<td>'.substr($motorista['nome'],0,25).'</td>';
		
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.substr($rota['nome'],0,25).'</td>';

				if($$motorista['tipo']=='1'){
					echo '<td>Motorista</td>';
				}elseif($motorista['tipo']=='2'){
					echo '<td>Ajudante</td>';
				}else{
					echo '<td>-</td>';
				}
		
				$negligencia = mostra('veiculo_motorista_motivo_negligencia',"WHERE id ='$negligenciaId'");
				echo '<td>' . $negligencia['nome'] . '</td>';
				echo '<td align="right">' . $negligencia['pontuacao'] .'</td>';
		
				$totalPontuacao = $totalPontuacao +$negligencia['pontuacao'];
		 
				if($negligenciaId=='13'){
					 //delete('veiculo_motorista_negligencia',"id = '$id'");
					//echo '<td>*</td>';
				}else{
					//echo '<td>'.converteData($mostra['data']).'</td>';
				}
		
				echo '<td>'.converteData($mostra['data']).'</td>';

					echo '<td align="center">
                                        <a href="painel.php?execute=suporte/motorista/negligencia-editar&negligenciaEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/motorista/negligencia-editar&negligenciaDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>'; 

			echo '</tr>';
		
		 endforeach;
		
				echo '<tfoot>';
                        echo '<tr>';
                            echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
                        echo '</tr>';
						echo '<tr>';
                            echo '<td colspan="13">' . 'Total de Pontuação : ' .  $totalPontuacao . '</td>';
		
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