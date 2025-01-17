<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>

<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

	$dataHoje = date("Y-m-d");
				   
	$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_comercial='3'",'orc_valor');

 	$total = conta('cadastro_visita',"WHERE id AND aprovacao_comercial='3'");
	
	$leitura = read('cadastro_visita',"WHERE id AND aprovacao_comercial='3' ORDER BY orc_data DESC");


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$aprovacao_comercial = $_POST['aprovacao_comercial'];
		$_SESSION['aprovacao_comercial']=$aprovacao_comercial;

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-autorizacoes-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$aprovacao_comercial = $_POST['aprovacao_comercial'];
		$_SESSION['aprovacao_comercial']=$aprovacao_comercial;

		
		header( 'Location: ../admin/suporte/relatorio/relatorio-autorizacoes-excel.php' );
	}
	
	if(isset($_POST['pesquisar'])){
		$aprovacao_comercial = $_POST['aprovacao_comercial'];
		
		$valor_total = soma('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial'",'orc_valor');

 		$total = conta('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial'");
		
		$leitura = read('cadastro_visita',"WHERE id AND aprovacao_comercial='$aprovacao_comercial' ORDER BY orc_data DESC");
	
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
				   
	
	
?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Autoriza&ccedil;&otilde;es</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="painel.php?execute=suporte/orcamento/orcamento">Autoriza&ccedil;&otilde;es</a></li>
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
								<select name="aprovacao_comercial" class="form-control input-sm"> <?php echo $aprovacao_comercial;?> >
								  <option value="">Selecione solicita&ccedil;&atilde;o</option>
								  <option <?php if($aprovacao_comercial == '1') echo' selected="selected"';?> value="1" >Aprovado</option>
								  <option <?php if($aprovacao_comercial == '2') echo' selected="selected"';?> value="2" >N&atilde;o Autorizado</option>
								  <option <?php if($aprovacao_comercial == '3') echo' selected="selected"';?> value="3">Aguardando</option>
							 </select>
							</div> 
                       
                   
                         <div class="form-group pull-left">
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relat�rio PDF"></i></button>
                         </div>  <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Rela�rio Excel"></i></button>
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
					<td align="center">Or�amento</td>
					<td align="center">Tipo de Res�duo</td>
					<td align="center">Vendedor</td>
					
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
				echo '<td>'.$consultor['nome'].'</td>';
			
				if($mostra['aprovacao_comercial']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_comercial']=='2'){
					echo '<td align="center">N�o Autorizado</td>';
				}else if($mostra['aprovacao_comercial']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
				if($mostra['aprovacao_operacional']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_operacional']=='2'){
					echo '<td align="center">N�o Autorizado</td>';
				}else if($mostra['aprovacao_operacional']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
				if($mostra['aprovacao_juridico']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_juridico']=='2'){
					echo '<td align="center">N�o Autorizado</td>';
				}else if($mostra['aprovacao_juridico']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
			
				if($mostra['aprovacao_diretoria']=='1'){
					echo '<td align="center">Autorizado</td>';
				}else if($mostra['aprovacao_diretoria']=='2'){
					echo '<td align="center">N�o Autorizado</td>';
				}else if($mostra['aprovacao_diretoria']=='3'){
					echo '<td align="center">Aguardando</td>';
				}else{
					echo '<td align="center">-</td>';
				}
				
				echo '<td align="center">
					<a href="painel.php?execute=suporte/orcamento/orcamento-editar&autorizarComercial='.$mostra['id'].'">
					<img src="ico/aprovar.png" alt="Autorizar" title="Autorizar" />
					</a>
					</td>';

				
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
					echo '</tr>';
						   
					echo '<tr>';
					echo '<td colspan="11">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
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
       
    </div><!-- /.box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->