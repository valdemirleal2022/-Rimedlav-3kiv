
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
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-suspensos-temporario-pdf");';
		echo '</script>';
		
	}


	if(isset($_POST['relatorio-excel'])){
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-suspensos-temporario-excel.php');
		
	}

	if(isset($_POST['relatorio-excel-ativo'])){
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-contrato-suspensos-ativos-excel.php');
		
	}
 

	if(isset($_POST['construir'])){
		
		$bancoExtrato = $_POST['banco'];
		$_SESSION['bancoExtrato']=$bancoExtrato;
		
		if(!empty($bancoExtrato)){
			
			// construir na pasta de contratos
		 
			require_once( "construir-faturamento-suspenso.php" );
		
		 }else{
			echo '<script>alert("Selecione o Banco")</script>';
		}
	}

	
	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		
		
	}

	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='19' AND data_suspensao>='$data1' 
											  AND data_suspensao<='$data2' AND status='19'",'valor_mensal');
	$total = conta('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
												AND data_suspensao<='$data2' AND status='19'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND data_suspensao>='$data1' 
											  AND data_suspensao<='$data2' AND status='19' 
											  ORDER BY data_suspensao ASC");

 
	$_SESSION['url']=$_SERVER['REQUEST_URI'];


?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contratos Suspensos Temporariamente </h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
           <li>Contratos</li>
           <li><a href="painel.php?execute=suporte/contrato/contrato-suspensos">Suspensos(19)</a></li>
         </ol>
 </section>
	
<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

    	<div class="box-header">	
	
         	<div class="col-xs-6 col-md-4 pull-left">
                <form name="form-pesquisa-banco" method="post" class="form-inline" role="form">
               
                  	    <div class="form-group pull-right-sm">
                            <select name="banco" class="form-control input-sm">
                                <option value="">Selecione Banco</option>
                                <?php 
                                    $readBanco = read('banco',"WHERE id");
                                    if(!$readBanco){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readBanco as $mae):
                                           if($bancoExtrato == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
								</select>
							 </div>
                  	  
                   	  <div class="form-group pull-right-sm">
                         <button class="btn btn-sm btn-warning pull-right" type="submit"  title="Construir Faturamento" name="construir"> Construir Faturamento</button>
                      </div><!-- /.input-group -->

                 </form> 
           	</div><!-- /.col-xs-10 col-md-4 pull-right--> 
			
		 
		</div><!-- /.col-xs-10 col-md-4 pull-right--> 
		 
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
						 
						 
						<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel-ativo" title="Relatório Excel - Ativos"><i class="fa fa-file-excel-o"></i></button>
						</div>   <!-- /.input-group --> 
						  
                    </form>  
                     
              
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
                  
          </div><!-- /box-header-->   
       
   <div class="box-body table-responsive">
    <div class="box-body table-responsive data-spy='scroll'">
     <div class="col-md-12 scrool">  
    
    
	<?php 

	 
		
	$ultimoFaturamento = 0;
	$suspensoFaturamento = 0;							   

	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center">Coleta</td>
					<td align="center">A partir</td>
					<td align="center">Vl Mensal</td>
					<td align="center">Dat Fat</td>
					<td align="center">Ultimo Fat</td>
					
					<td align="center">Fat Data</td>
					<td align="center">Fat Valor</td>

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
				echo '<td align="right">'.converteValor($mostra['valor_mensal']).'</td>';

				$receber = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao<='$dataSuspensao' ORDER BY vencimento ASC");
				echo '<td align="right">'.converteData($receber['emissao']).'</td>';

				echo '<td align="right">'.converteValor($receber['valor']).'</td>';
	 
				$ultimoFaturamento = $ultimoFaturamento+$receber['valor'];

				$receber2 = mostra('receber',"WHERE id_contrato ='$contratoId' AND emissao>'$dataSuspensao' ORDER BY vencimento ASC");
				echo '<td align="right">'.converteData($receber2['emissao']).'</td>';
			 
				if( date('d',strtotime($dataSuspensao)) < date('d',strtotime($receber['emissao'])) ){
					echo '<td align="right">'.converteValor($receber2['valor']).'</td>';
				}else{
					echo '<td align="right">*'.converteValor($receber2['valor']).'</td>';
				}
		
				$suspensoFaturamento = $suspensoFaturamento+$receber2['valor'];			

			    echo '<td align="center">
			  			<a href="painel.php?execute=suporte/faturamento/extrato-cliente-suspenso&contratoId='.$mostra['id'].'" target="_blank">
							<img src="ico/extrato.png"  title="Extrato"  />
              			</a>
						</td>';
		
		
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
							<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar" />
              			</a>
						</td>';
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$receber2['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
		
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
          echo '<td colspan="10">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td colspan="10">'.'Ultimo Faturamento R$ : '.converteValor($ultimoFaturamento).'</td>';
          echo '</tr>';
		
		  echo '<tr>';
          echo '<td colspan="10">'.'Suspensos R$ : '.converteValor($suspensoFaturamento).'</td>';
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