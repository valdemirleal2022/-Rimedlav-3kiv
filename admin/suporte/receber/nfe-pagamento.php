<?php

if ( function_exists( ProtUser ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}

if ( !isset( $dataEmissao ) ) {
    $dataEmissao = date( "Y/m/d" );
	$contratoTipo = $_POST['contrato_tipo'];
    $_SESSION[ 'dataEmissao' ] = $dataEmissao;
	$_SESSION['contratoTipo']=$contratoTipo;
} else {

}

if ( isset( $_POST[ 'pesquisar' ] ) ) {
    $dataEmissao = $_POST[ 'data' ];
	$contratoTipo = $_POST['contrato_tipo'];
    $_SESSION[ 'dataEmissao' ] = $dataEmissao;
	$_SESSION['contratoTipo']=$contratoTipo;
}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
   
    <section class="content-header">
        <h1>Gerar Arquivo NFe </h1>
        <ol class="breadcrumb">
            <li>Home</a>
                <li>Contas a Receber</li>
                </a>
                <li class="active">Gerar NFe</li>
        </ol>
    </section>

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
                   
                    <div class="box-header">
                        <div class="col-xs-10 col-md-4 pull-left">
                            <a href="painel.php?execute=suporte/receber/nfe-remessa" class="btnnovo">
                                <img src="ico/download.png" alt="Remessa" title="Remessa" class="tip" />
                                <small>Gerar Arquivo de Remessa</small>
                            </a>
                        
                            <a href="painel.php?execute=suporte/receber/nfe-retorno" class="btnnovo">
                                <img src="ico/upload.png" alt="Retorno" title="Retorno" class="tip" />
                                <small>Ler Arquivo de Retorno</small>
                            </a>

                        </div>
                        <!-- /col-xs-4-->
                    </div>
                    <!-- /box-header-->

                    <div class="box-header">
                        <div class="col-xs-8 col-md-5 pull-right">
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
								
							 <div class="form-group pull-left">
								<select name="contrato_tipo" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('contrato_tipo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($contratoTipo == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
													}
											endforeach;	
										}
									?> 
							 	</select>
							</div> 
								
                                <div class="form-group pull-left">
                                    <input type="date" name="data" value="<?php echo date('Y-m-d',strtotime($dataEmissao)) ?>" class="form-control input-sm">
                                </div>

                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                                <!-- /.input-group -->
                            </form>

                        </div>
                        <!-- /col-xs-6 col-md-5 pull-right-->
                    </div>
                    <!-- /box-header-->

     <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     	 <div class="col-md-12 scrool"> 

    <?php 
	
	$total=0;
	$valorTotal=0;
										   
    $leitura = read('receber',"WHERE pagamento='$dataEmissao' AND link=''");
							   
    if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Cidade</td>
					<td align="center">Valor</td>
					<td align="center">Vl Des</td>
					<td align="center">Vl Juros</td>
					<td align="center">Vl NFe</td>
					<td align="center">Faturamento</td>
					<td align="center">Pagamento</td>
                    <td align="center">Emissão Nfe</td>
					<td align="center">Nota</td>
					<td align="center">Pag/Banco</td>
                    <td align="center">Link</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
		
		  //MARCA REGISTRO PARA GERAR ARQUIVO DE REMESSA
				$receberId = $mostra['id'];
                $emissao = $mostra['emissao'];
        
                $clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		  if($contrato['nota_fiscal']=='1'){
			  
		 	echo '<tr>';
				echo '<td align="center">'.$mostra['id'].'</td>';
				if(!$cliente){
					echo '<td align="center">Cliente Nao Encontrado</td>';
					echo '<td align="center">Cliente Nao Encontrado</td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,20).'</td>';
					echo '<td>'.substr($cliente['cidade'],0,15).'</td>';
				}
				
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="right">'.converteValor($mostra['desconto']).'</td>';
				echo '<td align="right">'.converteValor($mostra['juros']).'</td>';
			  
				echo '<td align="right">'.converteValor($mostra['valor_nota_fiscal']).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['pagamento']).'</td>';
				
				//MARCA REGISTRO PARA GERAR ARQUIVO DE REMESSA NFE/BANCO
				if($contrato['nota_fiscal']=='1'){
					$receberId = $mostra['id'];
					$emissao = $mostra['pagamento'];
					$cad['nota_emissao']= $emissao;
					$cad['link']= '';
					update('receber',$cad,"id = '$receberId'");	
					echo '<td align="center">'.converteData($cad['nota_emissao']).'</td>';
				}else{
					 echo '<td align="center">'.converteData($mostra['nota_emissao']).'</td>';
				}
         			
				echo '<td align="center">'.$mostra['nota'].'</td>';
		
					$bancoId=$mostra['banco'];
					$banco = mostra('banco',"WHERE id ='$bancoId'");
					$formpagId=$mostra['formpag'];
					$formapag = mostra('formpag',"WHERE id ='$formpagId'");
					echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';
					
					echo '<td align="left">'.substr($mostra['link'],0,10).'</td>';
    	
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
                                    <img src="../admin/ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
                              </td>';
                        }
							echo '<td align="center">
										<a href="../cliente/painel2.php?execute=suporte/contrato/extrato-cliente&boletoId='.$mostra['id'].'" target="_blank">
											<img src="ico/extrato.png" alt="Extrato" title="Extrato Interno"  />
										</a>
										</td>';	
			  
						echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Editar" title="Contrato Editar"  />
							</a>
						  </td>';	

				   echo '</tr>';
                    
                    $total=$total+1;
                    $valorTotal=$valorTotal+($mostra['valor']-$mostra['juros']);
		    }
			
		 endforeach;
		 
			echo '<tfoot>';
             	echo '<tr>';
                	echo '<td colspan="16">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
                echo '<tr>';
                	echo '<td colspan="16">' . 'Valor Total R$ : ' . converteValor($valorTotal) . '</td>';
                echo '</tr>';
            echo '</tfoot>';
        
		 echo '</table>';
		}
		?>
       
      </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
     	  
     	  
    </div><!-- /."box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->