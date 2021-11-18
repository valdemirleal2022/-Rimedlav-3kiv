<?php

$percentual = str_replace(",",".",str_replace(".","",$percentual));

$leitura = read('contrato_coleta',"WHERE id AND vencimento>='$data1' AND vencimento<='$data2' 
	AND valor_unitario>='$valor1' AND valor_unitario<='$valor2' ORDER BY vencimento ASC");

$contador = 0;

if ( $leitura ) {
	
    foreach ( $leitura as $mostra ):
	
			
			$coletaId=$mostra['id'];
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

			$cliente = mostra('cliente',"WHERE id ='$clienteId'");
	
	
			$status=$contrato['status'];
	
			if($status!='9'){
					
					// nao atualizar clientes premium e prata - 11/02/2020
					if($cliente['tipo']<4){
						$cad[ 'valor_unitario' ] = $valorUnitario;
						$cad[ 'valor_extra' ] = $valorExtra;
						$cad[ 'valor_mensal' ] = $valorMensal;
			//			$cad[ 'percentual' ] = $percentual;
						update('contrato_coleta',$cad,"id = '$coletaId'");	
						$contador = $contador + 1;
					}
				
			}

    endforeach;

}

if ( $contador == 0 ) {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-warning">Nenhuma Contrato atualizado!</div>';
} else {
    $_SESSION[ 'retorna' ] = '<div class="alert alert-success">Contrato atualizado com sucesso!</div>';
}