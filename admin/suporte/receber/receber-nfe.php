<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	unset($_SESSION['inicio']);
	unset($_SESSION['fim']);

	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

    $leitura = read('receber',"WHERE nota_data>='$data1' AND nota_data<='$data2' ORDER BY nota_emissao DESC");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-nfe-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		header( 'Location: ../admin/suporte/relatorio/relatorio-nfe-excel.php' );
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
		
		$total = conta('receber',"WHERE nota_data>='$data1' AND nota_data<='$data2' ORDER BY vencimento ASC");
		$valor_total = soma('receber',"WHERE nota_data>='$data1' AND nota_data<='$data2' ORDER BY vencimento ASC",'valor');
		
		$leitura = read('receber',"WHERE nota_data>='$data1' AND nota_data<='$data2' ORDER BY vencimento ASC");

	}
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">

  <section class="content-header">
     <h1>NFe</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Contas a Receber</a>
     	<li class="active">NFe</li>
     </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     		        
			 <div class="box-header"> 
				 
			 </div><!-- /box-header-->
     
             <div class="box-header">
               <div class="row">

                     <div class="col-xs-6 col-md-2 pull-left">
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
                   
                                            
                  <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                        
                         
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
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Emissão</td>
					<td align="center">Vencimento</td>
					<td align="center">Emissão NFe</td>
					<td align="center">Nota</td>
					<td align="center">FormPag/Banco</td>
					<td colspan="8" align="center">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td align="center">'.$mostra['id'].'</td>';


				$receberId = $mostra['id'];
				$clienteId = $mostra['id_cliente'];
				$contratoId = $mostra['id_contrato'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				if(!$cliente){
					echo '<td align="center">Cliente Nao Encontrado</td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,15).'</td>';
					
				}

				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
				echo '<td align="center">'.converteData($mostra['nota_emissao']).'</td>';
				echo '<td align="center">'.$mostra['nota'].'</td>';
				
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';
			
				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
	
				if(empty($mostra['link'])){
					echo '<td align="center">-</td>';
				}else{
					 echo '<td align="center">
						<a href="'.$mostra['link'] .'" target="_blank">
							<img src="ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
				      </td>';
				}
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Visualizar" title="Contrato Visualizar"  />
							</a>
						  </td>';	

			echo '</tr>';
		 endforeach;

		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Valor Total : ' .  converteValor($valor_total) . '</td>';
          echo '</tr>';
		 echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
         echo '</tfoot>';
		 
		 echo '</table>';


		} //FIM DO $LEITURA
		
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