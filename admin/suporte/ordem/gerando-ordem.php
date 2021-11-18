<?php

 
/* Informa o nível dos erros que serão exibidos */
error_reporting(E_ALL);
 
/* Habilita a exibição de erros */
ini_set("display_errors", 1);

$_SESSION[ 'retorna' ] = '';
$_SESSION[ 'dataroteiro' ] = $dataroteiro;

$dia_semana = diaSemana($dataroteiro);
$numero_semana = numeroSemana($dataroteiro);


if ( $numero_semana == 0 ) { // DOMINGO
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND domingo='1' AND contrato_tipo>'0' ORDER BY domingo_rota1 ASC" );
	
	if(!empty($rotaId)){
		$leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND domingo='1' AND contrato_tipo>'0' AND domingo_rota1='$rotaId' ORDER BY domingo_rota1 ASC" );
	}
}

if ( $numero_semana == 1 ) { // SEGUNDA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND segunda='1' AND contrato_tipo>'0' ORDER BY segunda_rota1 ASC" );
	if(!empty($rotaId)){
		$leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND segunda='1' AND contrato_tipo>'0' AND segunda_rota1='$rotaId' ORDER BY segunda_rota1 ASC" );
	}
}

if ( $numero_semana == 2 ) { // TERÇA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND terca='1' AND contrato_tipo>'0'	ORDER BY terca_rota1 ASC" );
	if(!empty($rotaId)){
		$leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND terca='1' AND contrato_tipo>'0' AND terca_rota1='$rotaId' ORDER BY terca_rota1 ASC" );
	}
}

if ( $numero_semana == 3 ) { // QUARTA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND quarta='1' AND contrato_tipo>'0' ORDER BY quarta_rota1 ASC" );
	if(!empty($rotaId)){
		$leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND quarta='1' AND contrato_tipo>'0' AND quarta_rota1='$rotaId' ORDER BY quarta_rota1 ASC" );
	}
}

if ( $numero_semana == 4 ) { // QUINTA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND quinta='1' AND contrato_tipo>'0' ORDER BY quinta_rota1 ASC" );
	if(!empty($rotaId)){
		$leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND quinta='1' AND contrato_tipo>'0' AND quinta_rota1='$rotaId' ORDER BY quinta_rota1 ASC" );
	}
}

if ( $numero_semana == 5 ) { // SEXTA
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND sexta='1' AND contrato_tipo>'0'	ORDER BY sexta_rota1 ASC" );
	if(!empty($rotaId)){
		$leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND sexta='1' AND contrato_tipo>'0' AND sexta_rota1='$rotaId' ORDER BY sexta_rota1 ASC" );
	}
}

if ( $numero_semana == 6 ) { // SABADO
    $leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND sabado='1' AND contrato_tipo>'0' 
	ORDER BY sabado_rota1 ASC" );
	if(!empty($rotaId)){
		$leitura = read( 'contrato', "WHERE id AND tipo='2' AND status<>'9' AND sabado='1' AND contrato_tipo>'0' AND sabado_rota1='$rotaId' ORDER BY sabado_rota1 ASC" );
	}

}

$contador = 0;
$registro = 0;

if ( $leitura ) {
	
    foreach ( $leitura as $contrato ):
	
		$registro=$registro+1;
	
		$contratoId = $contrato[ 'id' ];
		$clienteId = $contrato[ 'id_cliente' ];
	 
		//echo $contratoId .'<br>';
	
		// TESTAR STATUS DO CONTRATO
		$gerarOrdem='SIM';
	
		if($contrato['status'] =='5'){ // 5 Contrato Ativo 
			$gerarOrdem='SIM';
			
			// TESTAR DATA INICIO 06/11/2017>06/1/2017
			if($contrato['inicio']>$dataroteiro){ // CONTRATO AINDA NAO COMEÇOU
				$gerarOrdem='NAO';
			}
			
		}

		if($contrato['status'] =='6'){ // 6 Contrato Suspensos
			// 31/08/2017 < 
			if($contrato['data_suspensao']<$dataroteiro){ // NAO GERAR ORDEM
				$gerarOrdem='NAO';
			}
		}
	
		if($contrato['status'] =='19'){ // 19 Contrato Suspensos temporariamente
			// 31/08/2017 < 
			if($contrato['data_suspensao']<$dataroteiro){ // NAO GERAR ORDEM
				$gerarOrdem='NAO';
			}
		}
	
		if($contrato['status'] =='7'){ // 7 Contrato Rescindido
			if($contrato['data_rescisao']<$dataroteiro){ // NAO GERAR ORDEM
				$gerarOrdem='NAO';
			}
			if($contrato['data_cancelamento']<$dataroteiro){ // NAO GERAR ORDEM
				$gerarOrdem='NAO';
			}

		}
	
		if($contrato['status'] =='10'){ // 10 Ação Judicial
			$gerarOrdem='NAO';
		}
	
		// FERIADO
		if($feriado=='1'){
			if($contrato['coletar_feriado']<>'1'){ // FERIADO
				$gerarOrdem='NAO';// NAO GERAR ORDEM
			}
		}

		// TESTAR AVULSO
		if($contrato['frequencia']=='4'){ // 4 - COLETA AVULSO
			$gerarOrdem='NAO';// NAO GERAR ORDEM
		}
	
		if(empty($contrato['id'])){
			$gerarOrdem='NAO';// NAO GERAR ORDEM
		}

	
		
		// FREQUENCIA NAO MARCADA
//		if($contrato['frequencia']<1){
//			$gerarOrdem='NAO';// NAO GERAR ORDEM
//		}
//	
//		//FREQUENCIA NAO MARCADA
//		if(empty($contrato['frequencia'])){
//			$gerarOrdem='NAO';// NAO GERAR ORDEM
//		}


		//VERIFICA DE EXISTE ORDEM JA CRIADA
		$ordemCadastrado = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND id_contrato='$contratoId'" );
		if($ordemCadastrado){ 
			$gerarOrdem='NAO';// NAO GERAR ORDEM
		}
	
		///echo  'VERIFICA DE EXISTE ORDEM JA CRIADA';
	
		// TESTAR SIM
		if($gerarOrdem=='SIM'){ 

			// TESTAR COLETA QUINZENAL
			if($contrato['frequencia']=='2'){ // 2 - COLETA QUINZENAL

				$gerarOrdem='NAO';// NAO GERAR ORDEM

				$tipoColetaLeitura = read( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
				
				if($tipoColeta){
					
					foreach ( $tipoColetaLeitura as $tipoColeta )
						
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
				} // se encontrado tipo de coleta
				
			} // fim COLETA QUINZENAL
			

			//// TESTAR COLETA MENSAL
			if($contrato['frequencia']=='3'){ // 3 - COLETA MENSAL

				$gerarOrdem='NAO';// NAO GERAR ORDEM

				$tipoColetaLeitura = read( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
				
				if($tipoColeta){
					
					foreach ( $tipoColetaLeitura as $tipoColeta )
				
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
					
				} // se encontrado tipo de coleta
	
			} // fim COLETA MENSAL
			
			
			//// TESTAR COLETA QUINZENAL
			if($contrato['frequencia']=='5'){ // 5 - COLETA Bimestral

				$gerarOrdem='NAO';// NAO GERAR ORDEM

				$tipoColetaLeitura = read( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
				
				if($tipoColetaLeitura){
					
					foreach ( $tipoColetaLeitura as $tipoColeta )
				
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
					
				} // se encontrado tipo de coleta
	
			} // fim COLETA MENSAL
			
			
			
			//// TESTAR COLETA Trimestral
			if($contrato['frequencia']=='6'){ // 6 - COLETA Trimestral

				$gerarOrdem='NAO';// NAO GERAR ORDEM

				$tipoColetaLeitura = read( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
				
				if($tipoColeta){
					
					foreach ( $tipoColetaLeitura as $tipoColeta )
					
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
					
				} // se encontrado tipo de coleta
	
			} // fim COLETA Trimestral
			
		} // fim do quinzenal e mensal

		//echo  'busca cliente';
	
	
		$clienteLeitura = read('cliente',"WHERE id ='$clienteId'");
		if(!$clienteLeitura){
			$gerarOrdem='NAO';// NAO GERAR ORDEM
		}
		foreach ( $clienteLeitura as $cliente );
	
		//echo $cliente[ 'nome' ].'<br>';
		
		// GERAR ORDEM DE SERVIÇO - GERAR ORDEM == SIM
		if($gerarOrdem=='SIM'){

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
						break;
						
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
				
				$quantidade=1;

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
						$cad[ 'manifesto_status' ] = '';
					}
					if ( $contrato[ 'manifesto' ] == '2' ) {
						$cad[ 'manifesto' ] = 'C';
					}
					if ( $contrato[ 'manifesto' ] == '3' ) {
						$cad[ 'manifesto' ] = 'N';
					}
					if ( $contrato[ 'manifesto' ] == '4' ) {
						$cad[ 'manifesto' ] = 'O';
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
					$interacao='Ordem gerada automaticamente';
					interacao($interacao,$contratoId);
					$contador = $contador + 1;

					if($quantidade>'1'){
						$cad[ 'rota' ] = $rota2;
						$cad[ 'hora' ] = $hora2;
						create( 'contrato_ordem', $cad );
						$interacao='Ordem gerada automaticamente';
						interacao($interacao,$contratoId);
						$contador = $contador + 1;
						if($quantidade>'2'){
							$cad[ 'rota' ] = $rota3;
							$cad[ 'hora' ] = $hora3;
							create( 'contrato_ordem', $cad );
							$interacao='Ordem gerada automaticamente';
							interacao($interacao,$contratoId);
							$contador = $contador + 1;
						} // fim if > 2  da quantidade
						
					} // fim  IF > 1  da quantidade
					
				}  // IF  VERIFICA DE EXISTE ORDEM JA CRIADA
				
			} // SE O CONTRATO NAO ESTÁ VENCIDO
		
		} // GERAR ORDEM DE SERVIÇO - GERAR ORDEM == SIM
		
		//if($registro>2100){
//			break;
//		}
//		
	
    endforeach;

}

//$totalRegistro = count($leitura);

if ( $contador == 0 ) {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-warning">Nenhuma Ordem de Serviço Gerada!</div>';
} else {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-success">Ordem de Serviço gerada com sucesso!</div>';
}

 // $_SESSION[ 'retorna' ] = 'Total de Registro : ' .$totalRegistro.' - Registro : ' .$registro ;

