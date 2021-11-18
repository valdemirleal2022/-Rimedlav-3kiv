
 <?php 

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = converteData1();
	$data2 = converteData2();

	if(!isset($_SESSION['inicio'])){
		$data1 = converteData1();
		$data2 = converteData2();
	}else{
		$data1 = $_SESSION['inicio'];
		$data2 = $_SESSION['fim'];
	}

	if(isset($_POST['relatorio-pdf'])){
		
		$_SESSION['inicio'] = $_POST['inicio'];
		$_SESSION['fim'] = $_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-suspensos-pdf");';
		echo '</script>';
		
	}


	if(isset($_POST['relatorio-excel'])){
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-suspensos-excel.php');
		
	}

	if(isset($_POST['aviso-suspensao'])){
	 	$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		require_once( "aviso-suspensao.php" );
	 		
	}


	// 6 Contrato Suspensos

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		
		
	}

	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='6' AND data_suspensao>='$data1'  AND data_suspensao<='$data2' AND status='6'",'valor_mensal');
	$total = conta('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
												AND data_suspensao<='$data2' AND status='6'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
											  AND data_suspensao<='$data2' AND status='6' 
											  ORDER BY data_suspensao ASC");


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
       <h1>Contratos Suspensos </h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Contratos</li>
           <li><a href="painel.php?execute=suporte/contrato/contrato-suspensos">Suspensos(6)</a></li>
         </ol>
 </section>
 
<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		 
		     <div class="box-header"> 
            <?php echo $_SESSION['retorna'];
		 
			?> 
      	 </div><!-- /box-header-->    

       
		 
		

    	<div class="box-header">	
	
                     <div class="col-xs-6 col-md-4 pull-left">
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
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group --> 
						 
						 		<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="aviso-suspensao" title="Enviar Aviso">Enviar Aviso</i></button>
								</div>   <!-- /.input-group -->   
						 
						 			
                    </form>  
                     
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
                  
          </div><!-- /box-header-->   
	   
	 
					   
     <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
   
    
    
	<?php 
  	
	$totalReceita = 0;

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center">Coleta</td>
					<td align="center">A partir</td>
					
					<td align="center">Motivo</td>
					
					<td align="center">F Pag</td>
					
					<td align="center">Vl Mensal</td>
					<td align="center">Ultimo Fat</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
		
				$contratoId = $mostra['id'];
				$clienteId = $mostra['id_cliente'];
				$dataSuspensao = $mostra['data_suspensao'];
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,15).'</td>';
		
				echo '<td align="left">'.substr($cliente['bairro'],0,10).'</td>';
				$contratoTipoId = $mostra['contrato_tipo'];
				$monstraContratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
				echo '<td>'.$monstraContratoTipo['nome'].'</td>';
				
				$contratoId = $mostra['id'];
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				$tipoColetaId = $contratoColeta['tipo_coleta'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$coleta['nome'].'</td>';
		
	 			$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' AND data ='$dataSuspensao' AND tipo='2'");
		
				echo '<td>'.converteData($dataSuspensao).'</td>';
				echo '<td>'.substr($contratoBaixa['motivo'],0,20).'</td>';
		
				if($contratoBaixa['falta_pagamento']==1){
					echo '<td>Sim</td>';
				}else{
					echo '<td>Não</td>';
				}
				
				echo '<td align="right">'.converteValor($mostra['valor_mensal']).'</td>';
		
			$receber = mostra('receber',"WHERE id_contrato ='$contratoId' ORDER BY vencimento ASC");
				echo '<td align="right">'.converteValor($receber['valor']).'</td>';
		
				$totalReceita = $totalReceita + $receber['valor'];

				//echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($contratoBaixa['interacao'])).'</td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-baixar&contratoEnviar='.$contratoBaixa['id'].'">
			  				<img src="ico/email.png" alt="Editar" title="Enviar Aviso"  />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
							<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
          echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td colspan="10">'.'Valor Total R$ : '.converteValor($valor_total).'</td>';
          echo '</tr>';
		
		  echo '<tr>';
          echo '<td colspan="10">'.'Valor Receita R$ : '.converteValor($totalReceita).'</td>';
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