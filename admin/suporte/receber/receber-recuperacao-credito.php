<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}


	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '50';
	$inicio = ($pag * $maximo) - $maximo;

	$valor_total = soma('receber',"WHERE recuperacao_credito='1'",'valor');

	$total = conta('receber',"WHERE recuperacao_credito='1'");
	
	$leitura = read('receber',"WHERE recuperacao_credito='1' ORDER BY recuperacao_credito_data DESC LIMIT $inicio,$maximo");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receita-recuperacao-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
			$data1 = $_POST[ 'inicio' ];
			$data2 = $_POST[ 'fim' ];
			$_SESSION['inicio']=$data1;
			$_SESSION['fim']=$data2;
			header( 'Location: ../admin/suporte/relatorio/relatorio-receita-recuperacao-excel.php' );
	}

		
	if(isset($_POST['pesquisar_numero'])){
		$receberId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		header('Location: painel.php?execute=suporte/receber/receber-editar&receberEditar='.$receberId.'');
	}
	
	if(isset($_POST['pesquisar_nota'])){
		$notaId=strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
		header('Location: painel.php?execute=suporte/receber/receber-editar&receberNota='.$notaId.'');
	}

	if(isset($_POST['pesquisar'])){
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$valor_total = soma('receber',"WHERE recuperacao_credito_data>='$data1' AND recuperacao_credito_data<='$data2' AND recuperacao_credito='1'",'valor');

	$total = conta('receber',"WHERE recuperacao_credito_data>='$data1' AND recuperacao_credito_data<='$data2' AND recuperacao_credito='1'");
		
		$leitura = read('receber',"WHERE recuperacao_credito_data>='$data1' AND recuperacao_credito_data<='$data2' AND recuperacao_credito='1' ORDER BY recuperacao_credito_data ASC LIMIT $inicio,$maximo");
	}
	
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Recuperação de Crédito</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Receber</a>
     	<li class="active">Recuperação</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         
    
    		 <div class="box-header">
               <div class="row">
                    <div class="col-xs-10 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                           <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                    </form> 
                  </div><!-- /col-xs-4-->
                  
                  <div class="col-xs-6 col-md-3 pull-right">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="nota" class="form-control input-sm" placeholder="Nota">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_nota" type="submit"><i class="fa fa-search"></i></button>                            
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-xs-6-->
                  
                   <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="Boleto">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
               </div><!-- /row-->   
            </div><!-- /box-header-->   
    

    <div class="box-body table-responsive">
    
	<?php 

	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Vencimento</td>
					<td align="center">Crédito</td>
					<td align="center">Nota</td>
					<td align="center">FormPag/Banco</td>
					<td align="center">Status</td>
					<td align="center">Interação</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td align="center">'.$mostra['id'].'</td>';
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				echo '<td>'.substr($contrato['controle'],0,6).'</td>';
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
	
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['recuperacao_credito_data']).'</td>';

				echo '<td align="center">'.$mostra['nota'].'</td>';
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';
		
				$receberId = $mostra['id'];
		
				if($mostra['recuperacao_credito']=='1'){
					$cad['status']=	'Recuperação';
				}
					
			//	update('receber',$cad,"id = '$receberId'");
				
				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											alt="Contrato Ativo" title="Contrato Ativo" />  </td>';
				}
				if($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											alt="Contrato Suspenso" title="Contrato Suspenso" /> </td>';
				}
				if($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" 
										alt="Contrato Cancelad" title="Contrato Cancelado" /> </td>';
				}
				
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';

 				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Visualizar" title="Contrato Visualizar"  />
							</a>
						  </td>';	
		
			echo '</tr>';
		 endforeach;
		 
			 
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="14">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="14">' . 'Total Valor R$ : ' . number_format($valor_total,2,',','.') . '</td>';
                        echo '</tr>';
  
          echo '</tfoot>';
		 
		 
		 echo '</table>';
		 
		 $link = 'painel.php?execute=suporte/receber/receber-recuperacao-credito&pag=';
	     pag('receber',"WHERE recuperacao_credito_data>='$data2' AND recuperacao_credito_data<='$data1' AND recuperacao_credito='1' ORDER BY recuperacao_credito_data DESC", $maximo, $link, $pag);
	}
  ?>
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