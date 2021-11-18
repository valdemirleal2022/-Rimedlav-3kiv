<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	 	
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
	 	
		echo '<script type="text/javascript">';
			echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-visita-negociacao-retorno-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	 	
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		header( 'Location: ../admin/suporte/relatorio/relatorio-visita-negociacao-retorno-excel.php' );
	}

	
	$_SESSION['url2']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Negocia��es | Retorno</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li class="active">Retorno</li>
          </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
 
           
             <div class="box-header">
               <div class="row">
                    <div class="col-xs-10 col-md-7 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                          
 					  <div class="form-group pull-left">
						 <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
					  </div>
                            
                       <div class="form-group pull-left">
						 <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
					  </div>
                            
                        <div class="form-group pull-left">
							 <select name="consultor" class="form-control input-sm">
									<option value="">Consultor</option>
									<?php 
										$readConta = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
										if(!$readConta){
											echo '<option value="">N�o temos Bancos no momento</option>';	
										}else{
											foreach($readConta as $mae):
											   if($consultorId == $mae['id']){
													echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
												 }else{
													echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
												}
											endforeach;	
										}
									?> 
								</select>    
						</div><!-- /.form-group pull-left -->

                        <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                        </div>  <!-- /.input-group -->
                            
                        <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relat�rio PDF"><i class="fa fa-file-pdf-o"></i></button>
                         </div>  <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relat�rio Excel"><i class="fa fa-file-excel-o"></i></button>
                         </div>   <!-- /.input-group -->
				 
                    </form> 
                  </div><!-- /col-xs-4-->
                  

               </div><!-- /row-->   
       </div><!-- /box-header-->   
 
   <div class="box-body table-responsive">
     <div class="box-body table-responsive data-spy='scroll'">
       <div class="col-md-12 scrool">  

	<?php 
	
	$total = conta('cadastro_visita_negociacao',"WHERE id AND status='1' AND retorno>='$data1' ORDER BY retorno DESC");
	$leitura = read('cadastro_visita_negociacao',"WHERE id AND status='1' AND retorno>='$data1' AND retorno<='$data2' ORDER BY retorno ASC");

	if($leitura){
		echo '<table class="table table-hover">	
					<tr class="set">
					<td>Id</td>
					<td>Nome</td>
					<td align="center">Consultor</td>
					<td align="center">Descri��o</td>
					<td align="center">Intera��o</td>
					<td align="center">Retorno</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td align="left">'.$mostra['id'].'</td>';
		
				$visitaId = $mostra['id_visita'];
				$visita = mostra('cadastro_visita',"WHERE id ='$visitaId'");
				echo '<td align="left">'.substr($visita['nome'],0,30).'</td>';
	
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				
				echo '<td align="left">'.$consultor['nome'].'</td>';
				echo '<td align="left">'.substr($mostra['descricao'],0,40).'</td>';
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['retorno'])).'</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaEditar='.$mostra['id'].'">
			  				<img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-editar&visitaEditar='.$visitaId.'">
			  				<img src="../admin/ico/visualizar.png" alt="Editar" title="Editar Or�amento" class="tip" />
              			</a>
				      </td>';	
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaBaixar='.$mostra['id'].'">
			  				<img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaDeletar='.$mostra['id'].'">
			  				<img src="../admin/ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
              			</a>
				      </td>';
					  
				
			echo '</tr>';
		endforeach;
		 
			echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
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