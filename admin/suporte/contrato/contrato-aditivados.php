 <meta charset="iso-8859-1">

<?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-aditivos-pdf");';
		echo '</script>';
	}

	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-aditivos-excel.php');
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	}

	
	$leitura = read('contrato_aditivo',"WHERE id AND inicio>='$data1' 
											  AND inicio<='$data2' ORDER BY inicio ASC");
	$total = conta('contrato_aditivo',"WHERE id AND inicio>='$data1' 
											  AND inicio<='$data2' ORDER BY inicio ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contrato Aditivos</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contrato</li>
           <li>Aditivos</li>
         </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

          	<div class="box-header">	    
                    <div class="col-xs-10 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                       <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                        
                        
                       <div class="form-group pull-left">
                        	 <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>   
                       </div><!-- /.input-group -->  
						 
                        <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>  
                        </div><!-- /.input-group -->
                          <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>  
                        </div><!-- /.input-group -->                              
                    </form> 
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
          </div><!-- /box-header-->   
       
 	<div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
                   
	<?php 
	
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td align="center">Aprovação</td>
					<td align="center">Inicio</td>
					<td align="center">Motivo</td>
					<td align="center">Frequencia</td>
					<td align="center">Tipo Coleta</td>
					<td align="center">Quant</td>
					<td align="center">Unit</td>
					<td align="center">Extra</td>
					<td align="center">Mensal</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td>'.$mostra['id'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';

				echo '<td align="left">'.converteData($mostra['aprovacao']).'</td>';
				echo '<td align="left">'.converteData($mostra['inicio']).'</td>';
		
				$motivoId=$mostra['motivo'];		
				$motivo= mostra('contrato_aditivo_motivo',"WHERE id ='$motivoId'");
				echo '<td align="left">'.substr($motivo['nome'],0,22).'</td>';

				echo '<td align="left">'.substr($mostra['frequencia_aditivo'],0,10).'</td>';
					
				$tipoColetaId=$mostra['tipo_coleta_aditivo'];		
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					
				echo '<td align="left">'.substr($tipoColeta['nome'],0,10).'</td>';
				echo '<td align="center">'.$mostra['quantidade_aditivo'].'</td>';	
				echo '<td align="right">'.converteValor($mostra['valor_unitario_aditivo']).'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_extra_aditivo']).'</td>';
				echo '<td align="right">'.converteValor($mostra['valor_mensal_aditivo']).'</td>';

		
				echo '<td align="center">
                       <a href="painel.php?execute=suporte/contrato/contrato-aditivo&contratoAditivoEditar='.$mostra['id'].'">
                            <img src="ico/editar.png" alt="Editar" title="Editar"  />
                          </a>
                      </td>';
					
				echo '<td align="center">
                       <a href="painel.php?execute=suporte/contrato/contrato-aditivo&contratoAditivoDeletar='.$mostra['id'].'">
                           <img src="ico/excluir.png"  title="Deletar"  />
                       </a>
                       </td>';
				
		
		
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="13">' . 'Total de registros : ' .  $total . '</td>';
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