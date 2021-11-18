<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	
	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-ordem-transferida-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-transferida-excel.php' );
	}

	if(isset($_POST['pesquisar_numero'])){
		$ordemId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($ordemId)){
			echo '<div class="alert alert-warning">Número Inválido!</div><br />';
		}else{
			header('Location: painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$ordemId);
		}
	}


	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
	}

	$total = conta('contrato_ordem',"WHERE id AND data>='$data1' AND data<='$data2' AND status='14'");
	$leitura = read('contrato_ordem',"WHERE id AND data>='$data1' AND data<='$data2' AND status='14' ORDER BY data ASC");

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Ordem de Serviço Transferidas</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviço</a></li>
            <li>Transferidas</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

      <div class="box-header">
              
                <div class="row">
                     <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="numero">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
                   
                  <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                     
                     <div class="form-group pull-left">
                        <input name="inicio" type="date" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                      </div><!-- /.input-group -->
                      
                       
                     <div class="form-group pull-left">
                        <input name="fim" type="date" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                      </div><!-- /.input-group -->
    
                        
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
             </div><!-- /row-->  
              
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
					<td>Bairro</td>
					<td align="center">Coleta</td>
					<td align="center">Rota</td>
					<td align="center">Data</td>
					<td align="center">Interação</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
		
				
				$clienteId = $mostra['id_cliente'];	
				$contratoId = $mostra['id_contrato'];	
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td align="center">'.substr($contrato['controle'],0,6).'</td>';
			
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,10).'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
				echo '<td>'.$coleta['nome'].'</td>';
							
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';
		
				echo '<td>'.converteData($mostra['data']).'</td>';
		
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
		
			
				echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Ordem" class="tip" />
              			</a>
				      </td>';
			   		  
 
				 echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Realizado" title="Baixar Ordem" class="tip" />
              			</a>
				      </td>';
				
				// imprimir ordem
				echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-contrato&ordemImprimir='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png" alt="Imprimir" title="Imprimir"  />
						</a>
					 </td>';	
	
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
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