<?php


$percentual = str_replace(",",".",str_replace(".","",$percentual));

$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1' AND vencimento<='$data2' ORDER BY vencimento ASC");

$contador = 0;

$_SESSION[ 'retorna' ]='';

if ( $leitura ) {
	
    foreach ( $leitura as $mostra ):
		
			$contratoId=$mostra['id_contrato'];
			$clienteId=$mostra['id_cliente'];
			
			$contrato = mostra('contrato',"WHERE id ='$contratoId'");
			$status=$contrato['status'];
	
			$tipoColeta = $mostra['tipo_coleta'];
            $quantidade = $mostra['quantidade'];
	
			$valorUnitario = $mostra['valor_unitario'];
			$valorUnitario = $valorUnitario + ($valorUnitario*$percentual)/100;
	
			$valorExtra = $mostra['valor_extra'];
			$valorExtra = $valorExtra + ($valorExtra*$percentual)/100;
	
			$valorMensal =$mostra['valor_mensal'];
			$valorMensal = $valorMensal + ($valorMensal*$percentual)/100;
	
			if($status!='9'){
				
				$inicio = $mostra['vencimento'];
				$vencimento = somarMes($mostra['vencimento'],'12');

				//VERIFICA SE JA RENOVADO
				$coletaRenovado = read( 'contrato_coleta',"WHERE id AND vencimento='$vencimento' 
																	AND id_contrato='$contratoId'" );

				if ( !$coletaRenovado ) {
					
					$cad[ 'id_cliente' ] = $clienteId;
					$cad[ 'id_contrato' ] = $contratoId;
					$cad[ 'tipo_coleta' ] = $tipoColeta;
					$cad[ 'quantidade' ] = $quantidade;
					$cad[ 'valor_unitario' ] = $valorUnitario;
					$cad[ 'valor_extra' ] = $valorExtra;
					$cad[ 'valor_mensal' ] = $valorMensal;
					$cad[ 'percentual' ] = $percentual;
					$cad[ 'inicio' ] = $inicio;
					$cad[ 'vencimento' ] = $vencimento;
					$cad[ 'interacao' ] = date( 'Y/m/d H:i:s' );
					create( 'contrato_coleta', $cad );
					$contador = $contador + 1;

					$interacao='Contrato renovado';
					interacao($interacao,$contratoId);	
				}
			}
	
			
    endforeach;

}

if ( $contador == 0 ) {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-warning">Nenhuma Contrato renovado!</div>';
} else {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-success">Contrato renovado com sucesso!</div>';
}