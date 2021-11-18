
 <?php 
	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = date("Y-m-d");
	$data2 = somarMes($data1,1);

	if(isset($_POST['relatorio-pdf'])){
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-renovacao-pdf");';
		echo '</script>';
		
	}

	if(isset($_POST['relatorio-excel'])){
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-renovacao-excel.php');
		
	}

	$percentual = 0;

	if ( isset( $_POST[ 'contrato_renovando' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$percentual = $_POST['percentual'];
		
		if($percentual>0){
			require_once( "renovando-contrato.php" );
			header( 'Location: painel.php?execute=suporte/contrato/contrato-renovacao' );
		}

		$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1' AND vencimento<='$data2' ORDER BY vencimento ASC");
	}

			
	$valor_total = soma('contrato_coleta',"WHERE id AND vencimento>='$data1' AND  vencimento<='$data2'",'valor_mensal');
	$total = conta('contrato_coleta',"WHERE id AND vencimento>='$data1' AND  vencimento<='$data2'");
	$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1' 
												AND vencimento<='$data2' ORDER BY vencimento ASC");

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$valor_total = soma('contrato_coleta',"WHERE id AND vencimento>='$data1' AND
																vencimento<='$data2'",'valor_mensal');
		$total = conta('contrato_coleta',"WHERE id AND vencimento>='$data1' 
																AND vencimento<='$data2'");
		$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1'
																AND vencimento<='$data2' ORDER BY vencimento ASC");
	}

	if(isset($_POST['pesquisar_numero'])){
		$contratoId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(!empty($contratoId)){
			$leitura=read('contrato',"WHERE id AND (id LIKE '%$contratoId%' OR
						controle LIKE '%$contratoId%')");  
		}
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Renovação</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Contrato</li>
           <li>Renovação</li>
         </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
      <div class="box-header"> 

      </div><!-- /box-header-->
        
        
     	<div class="box-header">
                	 
                 <div class="col-xs-6 col-md-2 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="numero">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
                    
                                     
                 <div class="col-xs-10 col-md-8 pull-left">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                      	<div class="form-group pull-left">
                           	<input type="date" name="inicio" value="<?php echo date('Y-m-d') ?>" class="form-control input-sm" >
                       	</div><!-- /.input-group -->
                       	<div class="form-group pull-left">
                           	<input type="date" name="fim" value="<?php echo date('Y-m-d') ?>" class="form-control input-sm" >
                       	</div><!-- /.input-group -->
                       
                        <div class="form-group pull-left">
                             <input type="text" name="percentual"  value="<?php echo converteValor($percentual);?>" class="form-control input-sm" style="text-align:right" placeholder="Percentual % (2,71)">
                        </div><!-- /input-group-->
                        
                       	<div class="form-group pull-left">
							<button  name="contrato_renovando" type="submit" class="btn btn-sm btn-warning">Renovar</button>    
                 		</div><!-- /.input-group -->
                 		 
                    </form>  
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
                     
         </div><!-- /box-header-->
         
      	 <div class="box-header"> 
                           
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
						<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
					</div>  <!-- /.input-group -->

					<div class="form-group pull-left">
						<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
					</div>   <!-- /.input-group -->
                                            
                  </form>  
                </div><!-- /col-xs-10 col-md-5 pull-right-->
                  
          </div><!-- /box-header-->   
       

    <div class="box-body table-responsive">
   
	<?php 

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td align="center">Nome</td>
					<td align="center">Coleta</td>
					<td align="center">Unitário</td>
					<td align="center">Mensal</td>
					<td align="center">Vencimento</td>
					<td align="center">Renovado</td>
					<td align="center">Renovado</td>
					<td align="center">Renovacao</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				$contratoId=$mostra['id_contrato'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				$clienteId = $mostra['id_cliente'];
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
				echo '<td>'.$mostra['id_contrato'].'</td>';
				echo '<td>'.substr($contrato['controle'],0,6).'</td>';

				echo '<td>'.substr($cliente['nome'],0,18).'</td>';

				$tipoColetaId = $mostra['tipo_coleta'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$coleta['nome'].'</td>';
				
				echo '<td align="right">'.(converteValor($mostra['valor_unitario'])).'</td>';
				echo '<td align="right">'.(converteValor($mostra['valor_mensal'])).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
		
				//$percentual='2,71';
//				$percentual = str_replace(",",".",str_replace(".","",$percentual));
//		
//				$coleta=($mostra['valor_unitario']*$percentual)/100;
//				$coleta=$mostra['valor_unitario']+$coleta;
					
				$vencimento=$mostra['vencimento'];
				$leituraColetaRenovacao = read('contrato_coleta',"WHERE id AND id_contrato='$contratoId'												AND vencimento>'$vencimento'");

				if($leituraColetaRenovacao){
					foreach($leituraColetaRenovacao as $coletaRenovacao)
					$coletaRenovacaoId=$coletaRenovacao['id'];
					echo '<td align="right">'.(converteValor($coletaRenovacao['valor_unitario'])).'</td>';
					echo '<td align="right">'.(converteValor($coletaRenovacao['valor_mensal'])).'</td>';
					echo '<td align="center">'.converteData($coletaRenovacao['vencimento']).'</td>';
					// delete('contrato_coleta',"id = '$coletaRenovacaoId'");
					// break;
				}else{
					echo '<td align="center">-</td>';
					echo '<td align="center">-</td>';
					echo '<td align="center">-</td>';
				}
		
				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" title="Contrato Ativo"/></td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" title="Contrato Suspenso"/></td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" title="Contrato Cancelado"/></td>';
				}else{
					echo '<td align="center">-</td>';
				}
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id_contrato'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id_contrato'].'">
			  				<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar Contrato" class="tip" />
              			</a>
				      </td>';

			echo '</tr>';
		 endforeach;
		 
		if(!isset($_POST['pesquisar_numero'])){
			 echo '<tfoot>';
							echo '<tr>';
							echo '<td colspan="13">' . 'Total de registros : ' .  $total . '</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td colspan="13">' . 'Valor Total R$ : ' . number_format($valor_total,2,',','.') . '</td>';
							echo '</tr>';

			 echo '</tfoot>';
		 }
		
		 echo '</table>';

	  }
	?>
    
      <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
       </div><!-- /.box-footer-->

	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->
