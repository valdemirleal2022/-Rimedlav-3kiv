<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if (!isset( $_SESSION[ 'dataInicio' ] ) ) {
		
		$data1 = date( "Y/m/d" );
		$data2 = date( "Y/m/d" );
		$_SESSION[ 'dataInicio' ] = $data1;
		$_SESSION[ 'dataFinal' ] = $data2;
		
	} else {
		
		$data1 = $_SESSION[ 'dataInicio' ];
		$data2 = $_SESSION[ 'dataFinal' ];
		
	}


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio'];
		$data2 = $_POST[ 'fim'];
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-ordem-avulsa-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {

		$data1 = $_POST[ 'inicio'];
		$data2 = $_POST[ 'fim' ];
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		$rotaId = $_POST['rota'];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-ordem-avulsa-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$_SESSION[ 'dataInicio' ] = $_POST[ 'inicio' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'fim' ];
		
		$rotaRoteiro = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];

	}

	$total = conta( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND avulsa='1'" );
	$leitura = read( 'contrato_ordem', "WHERE id AND data>='$data1' AND data<='$data2' AND avulsa='1' ORDER BY data ASC" );
 

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Ordem de Serviço Avulsa</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviço</a></li>
            <li><a href="#"> Avulsa</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
 
     	 <div class="box-header">
   
                  <div class="col-xs-10 col-md-7 pull-right">
                    
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
					<td>Bairro</td>
					<td align="center">Tipo Contrato</td>
					<td align="center">Coleta</td>
					<td>Quant</td>
					<td align="center">Rota</td>
					<td align="center">Observacao</td>
					<td align="center">Data</td>
					<td align="center">Impressão</td>

					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';

				$clienteId = $mostra['id_cliente'];	
				$contratoId = $mostra['id_contrato'];	
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,10).'</td>';
		
				$contratoTipoId = $contrato['contrato_tipo'];
				$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$contratoTipo['nome'].'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$coleta['nome'].'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

				echo '<td align="center">'.$mostra['quantidade1'].'</td>';
						
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';

				echo '<td align="center">'.$mostra['observacao'].'</td>';
							
				echo '<td>'.converteData($mostra['data']).'</td>';
		
				if($mostra['impressa']=='0'){
					
					echo '<td align="center">Aguardando</td>';
					
				}else{
					echo '<td align="center">Sim</td>';
				}
		
			
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
						<a href="painel.php?execute=suporte/ordem/ordem-servico&ordemImprimir='.$mostra['id'].'" target="_blank">
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