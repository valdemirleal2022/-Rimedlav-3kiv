<?php

if ( function_exists( ProtUser ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

if ( !isset( $_SESSION[ 'dataroteiro' ] ) ) {
    $dataroteiro = date( "Y/m/d" );
    $_SESSION[ 'dataroteiro' ] = $dataroteiro;
} else {
    $dataroteiro = $_SESSION[ 'dataroteiro' ];
}

if ( isset( $_POST[ 'pesquisar' ] ) ) {
    $dataroteiro = $_POST[ 'data1' ];
    unset( $_SESSION[ 'retorna' ] );
}

if ( isset( $_POST[ 'gerar_ordem-infectante' ] ) ) {
	$dataroteiro = $_POST[ 'data1' ];
	$_SESSION[ 'dataroteiro' ] = $dataroteiro;
	if (isset($_POST['feriado'])) {
		$feriado='1';
	}else{
		$feriado='0';
	}
	require_once( "gerando-ordem-infectante.php" );
  //  header( 'Location: painel.php?execute=suporte/ordem/gerar-ordem-infectante' );
}



$total = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro'" );
$leitura = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' ORDER BY rota ASC, hora ASC" );

$dia_semana = diaSemana( $dataroteiro );
$numero_semana = numeroSemana( $dataroteiro );

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Gerar Ordem de Serviço Infectante a</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Ordem de Serviço</a>
            </li>
            <li>Gerar Ordem</a>
            </li>
        </ol>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">

                    <div class="box-header">
     
                        <div class="col-xs-6 col-md-6 pull-left">
                           
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
                                 
                                   <div class="form-group pull-left">
										<input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
									</div><!-- /.input-group -->
									<div class="form-group pull-left">
										<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar">
										 <i class="fa fa-search"></i></button>
									 </div><!-- /.input-group -->
									 
									 <div class="form-group pull-left">         
										<input type="text" name="dia" value="<?php echo $dia_semana.$feriado; ?>" class="form-control input-sm" disabled>
									 </div> <!-- /.input-group -->
			
							   
						     </form>
                            
                         </div> <!-- /col-xs-6 col-md-4 pull-leftcol-xs-6 col-md-4 pull-left-->
		
				                     
                         <div class="col-xs-12 col-md-8 pull-right">
        
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
                                
                                 <div class="form-group pull-left">
									  <input type="checkbox"  name="feriado"  class="minimal" />
											   Feriado &nbsp;
								 </div><!-- /.input-group -->
                                 
                                 <div class="form-group pull-left">
										<input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
								</div><!-- /.input-group -->

								 <div class="input-group">
                                    <input type="submit" name="gerar_ordem-infectante" value="Gerar Ordem de Serviço Infectante" class="btn btn-sm btn-warning">
                                </div>
                                
                              </form>
                              
                         </div>  <!-- /col-xs-6 col-md-5 pull-right-->

                    </div>   <!-- /box-header-->

          <div class="box-body table-responsive">

    
	<?php 
		 
	
	$total =0;
	
	if($leitura){
					
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td align="center">Coleta</td>
					<td>Prev</td>
					<td align="center">Rota</td>
					<td>M</td>
					<td>Data</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
		
				$contratoId = $mostra['id_contrato'];
			    $contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				if($contrato['contrato_tipo']>'7'){
					
					echo '<td align="center">'.$mostra['id'].'</td>';
					
						$total =$total +1;
		
						$ordemId = $mostra['id'];	
						$clienteId = $mostra['id_cliente'];	

						$cliente = mostra('cliente',"WHERE id ='$clienteId'");
						echo '<td>'.substr($cliente['nome'],0,40).'</td>';
						$tipoColetaId = $mostra['tipo_coleta1'];
						$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

						echo '<td>'.$coleta['nome'].'</td>';

						$contratoId = $mostra['id_contrato'];
						$tipoColetaId = $mostra['tipo_coleta1'];
					
					//	if($mostra[ 'tipo_coleta1' ]=='1'){
//							$ins['tipo_coleta1']='19';
//							update('contrato_ordem',$ins,"id = '$ordemId'");
//						}
//						if($mostra[ 'tipo_coleta1' ]=='2'){
//							$ins['tipo_coleta1']='20';
//							update('contrato_ordem',$ins,"id = '$ordemId'");
//						}
//						if($$mostra[ 'tipo_coleta1' ]=='8'){
//							$ins['tipo_coleta1']='21';
//							update('contrato_ordem',$ins,"id = '$ordemId'");
//						}
//						if($mostra[ 'tipo_coleta1' ]=='9'){
//							$ins['tipo_coleta1']='22';
//							update('contrato_ordem',$ins,"id = '$ordemId'");
//						}

						$coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
						$tipoColetaId = $coletaPrevisto['id'];

			
						echo '<td align="center">'.$coletaPrevisto['quantidade'].'</td>';
						$rotaId = $mostra['rota'];
						$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
						echo '<td>'.$rota['nome'].'</td>';

						echo '<td align="center">'.$mostra['manifesto'].'</td>';

						$statusId = $mostra['status'];
						$status = mostra('contrato_status',"WHERE id ='$statusId'");

						echo '<td align="center">'.converteData($mostra['data']).'</td>';

						echo '<td>'.$status['nome'].'</td>';
					
						$dataroteiro=$mostra['data'];
									//VERIFICA DE EXISTE ORDEM JA CRIADA
						$ordemCadastrado = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND id_contrato='$contratoId'" );
						$ordemCadastrado = mostra('contrato_ordem',"WHERE id AND data='$dataroteiro' AND id_contrato='$contratoId'" );
						if($ordemCadastrado){ 
							echo '<td>Ja existes</td>';
						}else{
							echo '<td>-</td>';
						}


						echo '<td align="center">
								<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id_contrato'].'">
									<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
								</a>
							  </td>';


						 echo '<td align="center">
								<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
									<img src="ico/baixar.png" alt="Realizado" title="Baixar Ordem" class="tip" />
								</a>
							  </td>';

						// imprimir ordem
						echo '<td align="center">
								<a href="painel.php?execute=suporte/ordem/ordem-contrato&ordemImprimir='.$mostra['id'].'" target="_blank">
									<img src="ico/imprimir.png" alt="Imprimir" title="Imprimir"  />
								</a>
							 </td>';	

					echo '</tr>';
					
				}
		
				
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
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

	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->