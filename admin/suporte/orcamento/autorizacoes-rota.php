<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_operacional='3'",'orc_valor');

 	$total = conta('cadastro_visita',"WHERE id AND aprovacao_operacional='3'");
	
	$leitura = read('cadastro_visita',"WHERE id AND aprovacao_operacional='3' ORDER BY orc_data DESC");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$aprovacao_operacional = $_POST['aprovacao_operacional'];
		$_SESSION['aprovacao_operacional']=$aprovacao_operacional;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-autorizacoes-rota-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$aprovacao_operacional = $_POST['aprovacao_operacional'];
		$_SESSION['aprovacao_operacional']=$aprovacao_operacional;

		
		header( 'Location: ../admin/suporte/relatorio/relatorio-autorizacoes-rota-excel.php' );
	}
	
	if(isset($_POST['pesquisar'])){
		$aprovacao_operacional = $_POST['aprovacao_operacional'];
		
		$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_operacional='$aprovacao_operacional'",'orc_valor');

 		$total = conta('cadastro_visita',"WHERE id AND aprovacao_operacional='$aprovacao_operacional'");
		
		$leitura = read('cadastro_visita',"WHERE id AND aprovacao_operacional='$aprovacao_operacional' ORDER BY orc_data DESC");
	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
				   
	
	
?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Autorizações</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="painel.php?execute=suporte/orcamento/orcamento">Autorizações</a></li>
         </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
        
         <div class="box-header">       
                  <div class="col-xs-10 col-md-4 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                           <div class="form-group pull-left">
								<select name="aprovacao_operacional" class="form-control input-sm"> <?php echo $aprovacao_operacional;?> >
								  <option value="">Selecione solicitação</option>
								   <option <?php if($aprovacao_operacional == '2') echo' selected="selected"';?> value="2" <?php echo $aprovacao_operacional;?>>Não Autorizado</option>
								  <option <?php if($aprovacao_operacional == '3') echo' selected="selected"';?> value="3">Aguardando</option>
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
              </div><!-- /col-xs-6 col-md-5 pull-right-->
        
   	</div><!-- /.col-xs-10 col-md-4 pull-right--> 

    <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
                  
		<?php 
				
		if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Orçamento</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Vendedor</td>
					
					<td align="center">Hora</td>
					<td align="center">Rota</td>
					
					<td align="center">Comercial</td>
					<td align="center">Operacional</td>
					<td align="center">Juridico</td>
					<td align="center">Diretoria</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
			

				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.substr($mostra['nome'],0,25).'</td>';
				echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';

	 
				echo '<td>'.converteData($mostra['orc_data']).'</td>';
				
				echo '<td>'.substr($mostra['orc_residuo'],0,15).'</td>';
				
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'. substr($consultor['nome'],0,10).'</td>';
			
				echo '<td>'.$mostra['hora'].'</td>';
			
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';
				
				if($mostra['aprovacao_comercial']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_comercial']=='2'){
					echo '<td align="center">Não Autorizado</td>';
				}else if($mostra['aprovacao_comercial']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
				if($mostra['aprovacao_operacional']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_operacional']=='2'){
					echo '<td align="center">Não Autorizado</td>';
				}else if($mostra['aprovacao_operacional']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
				if($mostra['aprovacao_juridico']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_juridico']=='2'){
					echo '<td align="center">Não Autorizado</td>';
				}else if($mostra['aprovacao_juridico']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
				if($mostra['aprovacao_diretoria']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_diretoria']=='2'){
					echo '<td align="center">Não Autorizado</td>';
				}else if($mostra['aprovacao_diretoria']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&autorizarOperacional='.$mostra['id'].'">
					<img src="ico/aprovar.png" alt="Autorizar" title="Autorizar" />
					</a>
					</td>';

				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
					echo '</tr>';
						   
					echo '<tr>';
					echo '<td colspan="10">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
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