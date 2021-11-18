<?php

$_SESSION[ 'retorna' ] = '';
$_SESSION[ 'dataroteiro' ] = $dataroteiro;

$dia_semana = diaSemana($dataroteiro);
$numero_semana = numeroSemana($dataroteiro);

if ( $numero_semana == 0 ) { // DOMINGO
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND domingo='1' AND contrato_tipo>'0' ORDER BY domingo_rota1 ASC" );
}
if ( $numero_semana == 1 ) { // SEGUNDA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND segunda='1' AND contrato_tipo>'0' ORDER BY segunda_rota1 ASC" );
}
if ( $numero_semana == 2 ) { // TERÇA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND terca='1' AND contrato_tipo>'0'	ORDER BY terca_rota1 ASC" );
}
if ( $numero_semana == 3 ) { // QUARTA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND quarta='1' AND contrato_tipo>'0' ORDER BY quarta_rota1 ASC" );
}
if ( $numero_semana == 4 ) { // QUINTA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND quinta='1' AND contrato_tipo>'0' ORDER BY quinta_rota1 ASC" );
}
if ( $numero_semana == 5 ) { // SEXTA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND sexta='1' AND contrato_tipo>'0'	ORDER BY sexta_rota1 ASC" );
}
if ( $numero_semana == 6 ) { // SABADO
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND sabado='1' AND contrato_tipo>'0' 
	ORDER BY sabado_rota1 ASC" );

}


?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Gerando OS - 18:21</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem de Serviço</a></li>
            <li><a href="#">Rentabilidade</a></li>
         </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
		 
		 
   <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
    
    
<?php 
				
$contador = 0;
$contadorOrdem=0;
$registro = 0;

				
				
echo converteData($dataroteiro) . '<br>';

if ( $leitura ) {
	
    foreach ( $leitura as $contrato ):
	
		$registro=$registro+1;
	
		$contratoId = $contrato[ 'id' ];
		$clienteId = $contrato[ 'id_cliente' ];

		$cliente = mostra('cliente',"WHERE id ='$clienteId'");
			
		echo  $registro. ' - ' . $contratoId . '|' . $cliente['nome'].'-'.'<br>';

		//TESTAR STATUS DO CONTRATO
		$gerarOrdem='SIM';
		if($contrato['status'] =='5'){ // 5 Contrato Ativo 
			$gerarOrdem='SIM';
			
			// TESTAR DATA INICIO 06/11/2017>06/1/2017
			if($contrato['inicio']>$dataroteiro){ // CONTRATO AINDA NAO COMEÇOU
				$gerarOrdem='NAO';
			}
			
		}
			
		if($contrato['status'] =='6'){ // 6 Contrato Suspensos
			// 31/08/2017 < 03/12/2018
			if($contrato['data_suspensao']<$dataroteiro){ // NAO GERAR ORDEM
				$gerarOrdem='NAO';
				echo ' * Contrato Suspensos -';
			}
		}
	
		if($contrato['status'] =='7'){ // 7 Contrato Cancelado
			
			if($contrato['data_cancelamento']<$dataroteiro){ // NAO GERAR ORDEM
				$gerarOrdem='NAO';
			}

		}
	
		// FERIADO
		if($feriado=='1'){
			if($contrato['coletar_feriado']<>'1'){ //9 Contrato Serasa
				$gerarOrdem='NAO';// NAO GERAR ORDEM
			}
		}
	

		// TESTAR AVULSO
		if($contrato['frequencia']=='4'){ // 4 - COLETA AVULSO
			$gerarOrdem='NAO';// NAO GERAR ORDEM
		}

		//  TESTAR TEM TIPO DE COLETA
		$tipoColeta = read( 'contrato_coleta', "WHERE id AND id_contrato='$contratoId'" );
		if(!$tipoColeta){ 
			$gerarOrdem='NAO';// NAO GERAR ORDEM
		}
	
		// TESTAR TEM TIPO DE COLETA
		$cliente = read( 'cliente', "WHERE id AND id='$clienteId'" );
		if(!$cliente){ 
			$gerarOrdem='NAO';// NAO GERAR ORDEM
		}
	
		//VERIFICA DE EXISTE ORDEM JA CRIADA
		$ordemCadastrado = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND id_contrato='$contratoId'" );
		if($ordemCadastrado){ 
			$gerarOrdem='NAO';// NAO GERAR ORDEM
			echo ' ***  JÁ EXISTE OS -';
		}
	
		
		// TESTAR SIM
		if($gerarOrdem=='SIM'){ 

	
			// TESTAR COLETA QUINZENAL
			if($contrato['frequencia']=='2'){ // 2 - COLETA QUINZENAL

				$gerarOrdem='NAO';// NAO GERAR ORDEM

				$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
				$dataColeta = $tipoColeta[ 'inicio' ];

				// 03/07/2017 < 02/10/2017
				while($dataColeta<$dataroteiro):

					if(empty($dataColeta)){
						break;
					}

					$dataColeta=proximaColeta($contratoId,$dataColeta);

					// 02/10/2017 == 02/10/2017
					if($dataColeta==$dataroteiro){
						$gerarOrdem='SIM';// GERAR ORDEM
						break;
					}

				endwhile;

			}

			//// TESTAR COLETA MENSAL
			if($contrato['frequencia']=='3'){ // 3 - COLETA MENSAL

				$gerarOrdem='NAO';// NAO GERAR ORDEM

				$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
				$dataColeta = $tipoColeta[ 'inicio' ];

				// 03/07/2017 < 02/10/2017
				while($dataColeta<$dataroteiro):

					if(empty($dataColeta)){
						break;
					}

					$dataColeta=proximaColeta($contratoId,$dataColeta);

					// 02/10/2017 == 02/10/2017
					if($dataColeta==$dataroteiro){
						$gerarOrdem='SIM';// GERAR ORDEM
						break;
					}

				endwhile;
			}
			
		}
	
		
		echo  '  Data :' . converteData($dataColeta).' ';

		// GERAR ORDEM DE SERVIÇO - GERAR ORDEM == SIM
		if($gerarOrdem=='SIM'){

			echo ' * Gerando OS -';
			
			$clienteId = $contrato[ 'id_cliente' ];

			$tipoColeta1 = '';
			$tipoColeta2 = '';
			$tipoColeta3 = '';
			$tipoColeta4 = '';
			$tipoColeta5 = '';

			$leituraTipoColeta = read( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
			
			// SE O CONTRATO NAO ESTÁ VENCIDO
			if($leituraTipoColeta){
				
				foreach ( $leituraTipoColeta as $tipoColeta ):
					if ( empty( $tipoColeta1 ) ) {
						$tipoColeta1 = $tipoColeta[ 'tipo_coleta' ];
					} elseif ( empty( $tipoColeta2 ) ) {
						$tipoColeta2 = $tipoColeta[ 'tipo_coleta' ];
					} elseif ( empty( $tipoColeta3 ) ) {
						$tipoColeta3 = $tipoColeta[ 'tipo_coleta' ];
					} elseif ( empty( $tipoColeta4 ) ) {
						$tipoColeta4 = $tipoColeta[ 'tipo_coleta' ];
					} elseif ( empty( $tipoColeta5 ) ) {
						$tipoColeta5 = $tipoColeta[ 'tipo_coleta' ];
					}

				endforeach;
				
				$rota='';
				$hora='';

				if ( $numero_semana == 0 ) { // DOMINGO
					$quantidade = $contrato[ 'domingo_quantidade' ];
					$rota = $contrato[ 'domingo_rota1' ];
					$hora = $contrato[ 'domingo_hora1' ];
					$rota2 = $contrato[ 'domingo_rota2' ];
					$hora2 = $contrato[ 'domingo_hora2' ];
					$rota3 = $contrato[ 'domingo_rota3' ];
					$hora3 = $contrato[ 'domingo_hora3' ];
				}
				if ( $numero_semana == 1 ) { // SEGUDA
					$quantidade = $contrato[ 'segunda_quantidade' ];
					$rota = $contrato[ 'segunda_rota1' ];
					$hora = $contrato[ 'segunda_hora1' ];
					$rota2 = $contrato[ 'segunda_rota2' ];
					$hora2 = $contrato[ 'segunda_hora2' ];
					$rota3 = $contrato[ 'segunda_rota3' ];
					$hora3 = $contrato[ 'segunda_hora3' ];
				}
				if ( $numero_semana == 2 ) { // TERCA
					$quantidade = $contrato[ 'terca_quantidade' ];
					$rota = $contrato[ 'terca_rota1' ];
					$hora = $contrato[ 'terca_hora1' ];
					$rota2 = $contrato[ 'terca_rota2' ];
					$hora2 = $contrato[ 'terca_hora2' ];
					$rota3 = $contrato[ 'terca_rota3' ];
					$hora3 = $contrato[ 'terca_hora3' ];
				}
				if ( $numero_semana == 3 ) { // QUARTA
					$quantidade = $contrato[ 'quarta_quantidade' ];
					$rota = $contrato[ 'quarta_rota1' ];
					$hora = $contrato[ 'quarta_hora1' ];
					$rota2 = $contrato[ 'quarta_rota2' ];
					$hora2 = $contrato[ 'quarta_hora2' ];
					$rota3 = $contrato[ 'quarta_rota3' ];
					$hora3 = $contrato[ 'quarta_hora3' ];
				}
				if ( $numero_semana == 4 ) { // QUINTA
					$quantidade = $contrato[ 'quinta_quantidade' ];
					$rota = $contrato[ 'quinta_rota1' ];
					$hora = $contrato[ 'quinta_hora1' ];
					$rota2 = $contrato[ 'quinta_rota2' ];
					$hora2 = $contrato[ 'quinta_hora2' ];
					$rota3 = $contrato[ 'quinta_rota3' ];
					$hora3 = $contrato[ 'quinta_hora3' ];
				}
				if ( $numero_semana == 5 ) { // SEXTA
					$quantidade = $contrato[ 'sexta_quantidade' ];
					$rota = $contrato[ 'sexta_rota1' ];
					$hora = $contrato[ 'sexta_hora1' ];
					$rota2 = $contrato[ 'sexta_rota2' ];
					$hora2 = $contrato[ 'sexta_hora2' ];
					$rota3 = $contrato[ 'sexta_rota3' ];
					$hora3 = $contrato[ 'sexta_hora3' ];
				}
				if ( $numero_semana == 6 ) { // SABADO
					$quantidade = $contrato[ 'sabado_quantidade' ];
					$rota = $contrato[ 'sabado_rota1' ];
					$hora = $contrato[ 'sabado_hora1' ];
					$rota2 = $contrato[ 'sabado_rota2' ];
					$hora2 = $contrato[ 'sabado_hora2' ];
					$rota3 = $contrato[ 'sabado_rota3' ];
					$hora3 = $contrato[ 'sabado_hora3' ];
				}
		
				//VERIFICA DE EXISTE ORDEM JA CRIADA
				$ordemCadastrado = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro'
															AND id_contrato='$contratoId'" );

				if ( !$ordemCadastrado ) {
	
					if ( $contrato[ 'manifesto' ] == '1' ) {
						$cad[ 'manifesto' ] = 'M';
						$cad[ 'manifesto_status' ] = 'EM ABERTO';
					}else{
						$cad[ 'manifesto' ] = '';
						$cad[ 'manifesto_status' ] = '';
					}
					
					$cad[ 'id_cliente' ] = $clienteId;
					$cad[ 'id_contrato' ] = $contratoId;
					$cad[ 'rota' ] = $rota;
					$cad[ 'hora' ] = $hora;
					$cad[ 'status' ] = '12';
					$cad[ 'tipo_coleta1' ] = $tipoColeta1;
					$cad[ 'tipo_coleta2' ] = $tipoColeta2;
					$cad[ 'tipo_coleta3' ] = $tipoColeta3;
					$cad[ 'tipo_coleta4' ] = $tipoColeta4;
					$cad[ 'tipo_coleta5' ] = $tipoColeta5;
					$cad[ 'interacao' ] = date( 'Y/m/d H:i:s' );
					$cad[ 'data' ] = $dataroteiro;
					create( 'contrato_ordem', $cad );
					$contador = $contador + 1;
					
					$contadorOrdem=$contadorOrdem+1;

					if($quantidade>'1'){
						$cad[ 'rota' ] = $rota2;
						$cad[ 'hora' ] = $hora2;
						create( 'contrato_ordem', $cad );
						$contador = $contador + 1;
						if($quantidade>'2'){
							$cad[ 'rota' ] = $rota3;
							$cad[ 'hora' ] = $hora3;
							create( 'contrato_ordem', $cad );
							$contador = $contador + 1;
							
						} // fim if > 2  da quantidade
						
					} // fim  IF > 1  da quantidade
					
				}  // IF  VERIFICA DE EXISTE ORDEM JA CRIADA
				
			} // SE O CONTRATO NAO ESTÁ VENCIDO
			
		}else{
				echo ' ***  Não Gera OS -';
		}// GERAR ORDEM DE SERVIÇO - GERAR ORDEM == SIM
	

	
		if($registro>2500){
			break;
		}
		

	
  endforeach;
	
	echo  'Ordens Geradas :' . $contadorOrdem.'<br>';
	
	$totalRegistro = count($leitura);
	
	echo  'Total Registros :' . $totalRegistro.'<br>';

}

if ( $contador == 0 ) {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-warning">Nenhuma Ordem de Serviço Gerada!</div>';
} else {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-success">Ordem de Serviço gerada com sucesso!</div>';
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