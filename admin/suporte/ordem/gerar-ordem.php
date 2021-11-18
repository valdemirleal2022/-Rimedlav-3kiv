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


if ( isset( $_POST[ 'gerar_ordem' ] ) ) {
	$dataroteiro = $_POST[ 'data1' ];
	$rotaId = $_POST['rota'];
	if (isset($_POST['feriado'])) {
		$feriado='1';
	}else{
		$feriado='0';
	}
	require_once( "gerando-ordem.php" );
	header( 'Location: painel.php?execute=suporte/ordem/gerar-ordem' );
}


$pag = ( empty( $_GET[ 'pag' ] ) ? '1' : $_GET[ 'pag' ] );
$maximo = '20';
$inicio = ( $pag * $maximo ) - $maximo;

$total = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro'" );
$leitura = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' 
						ORDER BY rota ASC, hora ASC LIMIT $inicio,$maximo" );

$dia_semana = diaSemana( $dataroteiro );
$numero_semana = numeroSemana( $dataroteiro );

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Gerar Ordem de Serviço</h1>
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
      
               					
								 <div class="form-group pull-left">
									<select name="rota" class="form-control input-sm">
										<option value="">Selecione Rota</option>
										<?php 
											$readBanco = read('contrato_rota',"WHERE id ORDER BY nome ASC");
											if(!$readBanco){
												echo '<option value="">Não temos Bancos no momento</option>';	
											}else{
												foreach($readBanco as $mae):
												   if($rotaId == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
													}
												endforeach;	
											}
										?> 
									</select>
								</div>

                                <div class="input-group">
                                    <input type="submit" name="gerar_ordem" value="Gerar Ordem de Serviço" class="btn btn-sm btn-warning">
                                </div>
                                
                              </form>
                              
                         </div>  <!-- /col-xs-6 col-md-5 pull-right-->

                    </div>   <!-- /box-header-->

          <div class="box-body table-responsive">

    
	<?php 
		 
			 
	if($leitura){
					
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Nome</td>
					<td>Bairro</td>
					<td align="center">Coleta</td>
					<td>Q Prev</td>
					<td>Hora Prev</td>
					<td align="center">Rota</td>
					<td>M</td>
					<td>Data</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
		
				echo '<td align="center">'.$mostra['id'].'</td>';

				$clienteId = $mostra['id_cliente'];	
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,12).'</td>';
		
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
				echo '<td>'.$coleta['nome'].'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$tipoColetaId = $mostra['tipo_coleta1'];
                $coletaPrevisto = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
				
				echo '<td align="center">'.$coletaPrevisto['quantidade'].'</td>';

				echo '<td>'.$mostra['hora'].'</td>';
								
				$rotaId = $mostra['rota'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				echo '<td>'.$rota['nome'].'</td>';
		
				echo '<td align="center">'.$mostra['manifesto'].'</td>';
		
			    $statusId = $mostra['status'];
                $status = mostra('contrato_status',"WHERE id ='$statusId'");
		
				echo '<td align="center">'.converteData($mostra['data']).'</td>';
				
				echo '<td>'.$status['nome'].'</td>';
		
			
				echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Ordem" class="tip" />
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
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
         echo '</tfoot>';
		 
		 echo '</table>';
		 
		 $link = 'painel.php?execute=suporte/ordem/gerar-ordem&pag=';
	
		 pag('contrato_ordem',"WHERE id AND data='$dataroteiro' 
		 				ORDER BY rota ASC, hora ASC", $maximo, $link, $pag);
		
		
		}
		
	?>
			  
		<br><br>
		 <div class="box-footer">
            <?php echo $_SESSION['retorna'];?>
       </div><!-- /.box-footer-->

	  </div><!-- /.box-body table-responsive -->
	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->