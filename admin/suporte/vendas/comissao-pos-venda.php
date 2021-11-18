<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$posVendaId=strip_tags(trim(mysql_real_escape_string($_POST['posVenda'])));
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['posVenda']=$posVendaId;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-comissao-pos-venda-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$posVendaId=strip_tags(trim(mysql_real_escape_string($_POST['posVenda'])));
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['posVenda']=$posVendaId;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-comissao-pos-venda-excel.php' );
	}

	if ( isset( $_POST[ 'relatorio-geral-excel' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		header( 'Location: ../admin/suporte/relatorio/relatorio-comissao-pos-venda-geral-excel.php' );
	}


	if(isset($_POST['pesquisar'])){
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$posVendaId=strip_tags(trim(mysql_real_escape_string($_POST['posVenda'])));
		 
	}

	 
		$leitura = read('receber',"WHERE status<>'Em Aberto' AND pagamento>='$data1' AND 
							pagamento<='$data2' ORDER BY pagamento ASC");

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Comissão Pos-Venda</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Vendas</a>
     	<li class="active">Comissão</li>
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
							 <select name="posVenda" class="form-control input-sm">
									<option value="">Pos-Venda</option>
									<?php 
										$readConta = read('contrato_pos_venda',"WHERE id ORDER BY nome ASC");
										if(!$readConta){
											echo '<option value="">Não temos Bancos no momento</option>';	
										}else{
											foreach($readConta as $mae):
											   if($posVendaId == $mae['id']){
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
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
                         </div>  <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
                         </div>   <!-- /.input-group -->
						 
						 <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-geral-excel" title="Relatório Geral Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                  </div><!-- /col-xs-4-->
                  

               </div><!-- /row-->   
       </div><!-- /box-header-->   
 
    <div class="box-body table-responsive">
   	 <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  

	<?php 
			
	$valor_total=0;
	$total=0;
			
	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td align="center">Pos-Venda</td>
					<td align="center">Emissão</td>
					<td align="center">Vencimento</td>
					<td align="center">Pagamento</td>
					<td align="center">Valor</td>
					<td align="center">-</td>
					<td align="center">Comissão</td>
					<td align="center">Tipo</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId '");
				if($contrato['pos_venda']==$posVendaId){
		
					$clienteId = $mostra['id_cliente'];
					$contratoId = $mostra['id_contrato'];
					$contrato = mostra('contrato',"WHERE id ='$contratoId '");
					
					echo '<td align="center">'.$mostra['id'].'</td>';
					echo '<td align="center">'. substr($contrato['controle'],0,6).'</td>';
					
					$cliente = mostra('cliente',"WHERE id ='$clienteId '");
					echo '<td>'.substr($cliente['nome'],0,30).'</td>';
					
					$posVenda = mostra('contrato_pos_venda',"WHERE id ='$posVendaId'");
					echo '<td align="center">'.$posVenda['nome'].'</td>';
					
					echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
					echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
					echo '<td align="center">'.converteData($mostra['pagamento']).'</td>';

					echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
					
					$receber = conta('receber',"WHERE id ='$contratoId '");
					if($receber=='1'){
						$percentual=$contrato['comissao_fechamento'];
					}else{
						$percentual=$contrato['comissao_manutencao'];
					}
					
					// MUDANÇA SOLICITADA PELA PATRICIA EM 23/01/2019
					
					// Calcula a diferença em segundos entre as datas
					$diferenca = strtotime($mostra['pagamento']) - strtotime($mostra['vencimento']);
					//Calcula a diferença em dias
					$dias = floor($diferenca / (60 * 60 * 24));
					$percentual=2.00;
					if($dias>5){
						$percentual=1.50;
					}
					echo '<td align="center">'.$percentual.'</td>';
					
					$comissao=($mostra['valor']*$percentual)/100;
					echo '<td align="right">'.converteValor($comissao).'</td>';
					
					$valor_total+=$mostra['valor'];
					$valor_comissao+=$comissao;
					$total++;
					
					$tipoId = $mostra['contrato_tipo'];
					$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
					echo '<td>'.$tipo['nome'].'</td>';

					echo '<td align="center">
						<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
								<img src="ico/editar.png" alt="Editar" title="Editar"  />
							</a>
						  </td>';
					
					echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contrato['id'].'">
							<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar"  />
						</a>
						  </td>';
				echo '</tr>';
				}
				
				
		 endforeach;
		
		
		 
		echo '<tfoot>';
		
          echo '<tr>';
            echo '<td colspan="12">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
		
		  echo '<tr>';
           echo '<td colspan="12">' . 'Total Recebido R$ : '.converteValor($valor_total).'</td>';
		  echo '</tr>';
		
		 echo '<tr>';
           echo '<td colspan="12">' . 'Total Comissão R$ : '.converteValor($valor_comissao).'</td>';
		  echo '</tr>';
		
		 $valorIss=$valor_comissao/1.05;
		 $valorIss=$valor_comissao-$valorIss;
		 $valor_comissao=$valor_comissao-$valorIss;
		
		  echo '<tr>';
           echo '<td colspan="12">' . 'Desconto ISS R$ : '.converteValor($valorIss).'</td>';
		  echo '</tr>';
		
		
		
		  echo '<tr>';
           echo '<td colspan="12">' . 'Total Comissão R$ : '.converteValor($valor_comissao).'</td>';
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