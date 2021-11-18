 <meta charset="iso-8859-1">

<?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
 
	$data1 = converteData1();
	$data2 = converteData2();
 
	
	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	}
 
	$leitura = read('contrato_aditivo',"WHERE id AND aprovacao_comercial='1'  AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' ORDER BY aprovacao ASC");
	$total = conta('contrato_aditivo',"WHERE id AND aprovacao_comercial='1' AND aprovacao>='$data1' 
											  AND aprovacao<='$data2' ORDER BY aprovacao ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contrato Aditivo - Autorizados</h1>
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
					<td align="center">Valor</td>
					<td align="center">Hora</td>
					<td align="center">Rota</td>
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
				echo '<td align="left">'.substr($motivo['nome'],0,25).'</td>';

				echo '<td align="left">'.substr($mostra['frequencia_aditivo'],0,10).'</td>';
					
				$tipoColetaId=$mostra['tipo_coleta_aditivo'];		
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					
				echo '<td align="left">'.substr($tipoColeta['nome'],0,15).'</td>';
				echo '<td align="center">'.$mostra['quantidade_aditivo'].'</td>';	
				echo '<td align="right">'.converteValor($mostra['valor_unitario_aditivo']).'</td>';
				echo '<td align="right">'.substr($mostra['hora'],0,10).'</td>';
				echo '<td align="right">'.substr($mostra['rota'],0,10).'</td>';

		
				echo '<td align="center">
                       <a href="painel.php?execute=suporte/contrato/contrato-aditivo-consultor&autorizarComercial='.$mostra['id'].'">
                            <img src="ico/baixar.png" title="Autorizar"  />
                          </a>
                      </td>';
					
				echo '<td align="center">
						<a href="../cliente/painel2.php?execute=suporte/contrato/imprimir-aditivo&aditivoId='.$mostra['id'].'" target="_blank">
							<img src="ico/imprimir.png" title="Imprimir Aditivo" />
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