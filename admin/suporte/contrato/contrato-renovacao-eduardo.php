
 <?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = $_SESSION['inicio'];
	$data2 = $_SESSION['fim'];
	$percentual = $_SESSION['percentual'];
	$valor1 =$_SESSION['valor1'];
	$valor1 =$_SESSION['valor2'];
	
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


	if ( isset( $_POST[ 'contrato_renovando' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$percentual = $_POST['percentual'];
		$valor1 = $_POST['valor1'];
		$valor2 = $_POST['valor2'];
	
		require_once( "renovando-contrato-eduardo.php" );

	}


	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$percentual = $_POST['percentual'];
		
		$valor1 = $_POST['valor1'];
		$valor2 = $_POST['valor2'];
		
		
	}

	$valor_total = soma('contrato_coleta',"WHERE id 
	AND vencimento>='$data1' AND vencimento<='$data2' 
	AND valor_unitario>='$valor1' AND valor_unitario<='$valor2'",'valor_unitario');
	$total = conta('contrato_coleta',"WHERE id 
	AND vencimento>='$data1' AND vencimento<='$data2' 
	AND valor_unitario>='$valor1' AND valor_unitario<='$valor2'");

	$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1' AND vencimento<='$data2' 
	AND valor_unitario>='$valor1' AND valor_unitario<='$valor2' ORDER BY vencimento ASC");
 
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Renovação - Simular</h1>
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
               
                 <div class="col-xs-10 col-md-10 pull-left">
					 
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                     	
                         <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
	
						 <div class="form-group pull-left">
								 <input type="text" name="percentual"  value="<?php echo converteValor($percentual);?>" class="form-control input-sm" style="text-align:right" title="Percentual %">
						 </div><!-- /input-group-->

						 <div class="form-group pull-left">
								 <input type="text" name="valor1"  value="<?php echo converteValor($valor1);?>" class="form-control input-sm" style="text-align:right" title="Valor 1 R$">
						 </div><!-- /input-group-->
                       	 
                       	  <div class="form-group pull-left">
								 <input type="text" name="valor2"  value="<?php echo converteValor($valor2);?>" class="form-control input-sm" style="text-align:right" title="Valor 2 R$">
						 </div><!-- /input-group-->

                       	 <div class="form-group pull-left">
							<button  name="contrato_renovando" type="submit" class="btn btn-sm btn-warning">Renovar</button>    
                 		 </div><!-- /.input-group -->
                 		 
                    </form>  
               </div><!-- /col-xs-10 col-md-5 pull-right-->
                     
         </div><!-- /box-header-->
         
      	 <div class="box-header"> 
                           
                <div class="col-xs-10 col-md-10 pull-right">
                 
                  <form name="form-pesquisa" method="post" class="form-inline" role="form">
                  
                   <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
						 
                      
                    <div class="form-group pull-left">
                             <input type="text" name="percentual"  value="<?php echo converteValor($percentual);?>" class="form-control input-sm" style="text-align:right" title="Percentual %">
                     </div><!-- /input-group-->
                       
                    <div class="form-group pull-left">
							<input type="text" name="valor1"  value="<?php echo converteValor($valor1);?>" class="form-control input-sm" style="text-align:right" title="Valor 1 R$">
					</div><!-- /input-group-->
                       	 
                    <div class="form-group pull-left">
							<input type="text" name="valor2"  value="<?php echo converteValor($valor2);?>" class="form-control input-sm" style="text-align:right" title="Valor 2 R$">
					 </div><!-- /input-group-->
                        
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
					
					<td align="center">Percentual</td>
					
					<td align="center">Unitario</td>
					<td align="center">Mensal</td>

					<td align="center">Status</td>
					<td align="center">Tipo</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	
				$contratoId=$mostra['id_contrato'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				$clienteId = $contrato['id_cliente'];
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
		
				$listar='NAO';
				if($cliente['tipo']<4){ 
					$listar='SIM';
				}
				
									   
			if($listar=='SIM'){
		 
			 
		 	   echo '<tr>';
		
		
				echo '<td>'.$mostra['id_contrato'].'</td>';
				echo '<td>'.substr($contrato['controle'],0,6).'</td>';
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';

				$tipoColetaId = $mostra['tipo_coleta'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$coleta['nome'].'</td>';
				
				echo '<td align="right">'.(converteValor($mostra['valor_unitario'])).'</td>';
				echo '<td align="right">'.(converteValor($mostra['valor_mensal'])).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
		
				$valorUnitario = $mostra['valor_unitario'];
				$valorUnitario = $valorUnitario + ($valorUnitario*$percentual)/100;

				$valorExtra = $mostra['valor_extra'];
				$valorExtra = $valorExtra + ($valorExtra*$percentual)/100;

				$valorMensal =$mostra['valor_mensal'];
				$valorMensal = $valorMensal + ($valorMensal*$percentual)/100;
		
				echo '<td align="right">'.(converteValor($percentual)).'</td>';
				echo '<td align="right">'.(converteValor($valorUnitario)).'</td>';
				echo '<td align="right">'.(converteValor($valorMensal)).'</td>';
		
		
				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" title="Contrato Ativo"/></td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" title="Contrato Suspenso"/></td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" title="Contrato Cancelado"/></td>';
				}else{
					echo '<td align="center">-</td>';
				}
		
				if($cliente['tipo']==4){
					echo '<td align="center"><img src="ico/ouro.png" title="Cliente Ouro"/></td>';
				}elseif($cliente['tipo']==5){
					echo '<td align="center"><img src="ico/premium.png" title="Cliente Premium"/></td>';
				}elseif($cliente['tipo']==6){
					echo '<td align="center"><img src="ico/prata.png" title="Cliente Prata"/></td>';
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
				
			}
		 
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
            <?php echo $_SESSION['retorna'];
		  		unset($_SESSION['retorna'])
		  ?>
       </div><!-- /.box-footer-->

	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->
