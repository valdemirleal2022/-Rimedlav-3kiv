<?php

$gb_nossonumero = str_pad( $gb_nossonumero, 13, '0', STR_PAD_LEFT );
$gb_nossonumero = '14' . $gb_nossonumero;
$digito_verificador = mod11_2a9( $gb_nossonumero );

if ( empty( $cHeader ) ) {
	$cHeader .= '0'; // 01.0 Código do registro Código do Registro  1 1 9(001) Preencher ‘0’ 
	$cHeader .= '1'; // 02.0 Código da Remessa Código da Remessa 	2 2 9(001) Preencher '1'
	$cHeader .= 'REMESSA'; // 03.0 Literal da Remessa Literal da Remessa  3 9 X(007)  ‘REMESSA’, se em produçao
	$cHeader .= '01'; // 04.0 Código do Serviço Código do Serviço   	10 11 9(002) Preencher ‘01’
	$cHeader .= limite( 'COBRANCA', 15 ); // 05.0 Literal de Serviço Literal de Serviço 	12 26 X(015) Preencher 'COBRANCA'
	$cHeader .= $ccod_agencia; // 06.0 Código da Agencia 						27 30 9(004) 
	$cHeader .= limite( $ccedente, 6 ); // 07.0 Código Código do CEDENTE				31 36 9(006) Codigo Cedente
	$cHeader .= espacoBranco( 10 ); // 08.0 Uso Exclusivo CAIXA   CAIXA 			37 46 X(010) Preencher espaços
	$cHeader .= limite( $cnome_cedente, 30 ); // 09.0 Nome da Empresa 					    47 76 X(030) Preencher com o Nome da Empresa NE005
	$cHeader .= '104'; // 10.0 Código do banco Código do banco 		77 79 9(003) Preencher ‘104’
	$cHeader .= limite( 'C ECON FEDERAL', 15 ); // 11.0 Nome do banco Nome do banco 80 94 X(015) Preencher 'C ECON FEDERAL'
	$cHeader .= ddmmyy( $dataemissao ); // 12.0 Data de Geraçao Data de Geraçao 95 100 9(006)data formato DDMMAA (Dia,Mes e Ano
	$cHeader .= espacoBranco( 289 ); // 13.0 Uso Exclusivo Uso Exclusivo CAIXA 101 389 X(289) Preencher espaços
	$cHeader .= str_pad( $nReg, 5, '0', STR_PAD_LEFT ); // 14.0 No Seqüencial o Arquivo Remessa 		390 394 9(005) Número seqüencial adotado e
	$cHeader .= '000001'; // 15.0 No Sequencial - B Número Sequencial do Registro no Arquivo 395 400 9(006) Preencher ‘000001’
	$cHeader .= chr( 13 ) . chr( 10 ); //essa é a quebra de linha				 
	$nReg = $nReg + 1;
}

$cDetail = $cDetail;
$cDetail .= '1'; // 01.1 Código Identificador do Tipo de Registro  		1 1 9(001) Preencher ‘1
$cDetail .= '02'; // 02.1 Tipo de Inscriçao‘01’ = CPF ou ‘02’ = CNPJ	2 3 9(002)
$cDetail .= $cnpjempresa; // 03.1 Número Inscriçao daEmpresa 			4 17 9(014)
$cDetail .= $ccod_agencia; // 04.1 Código da Agencia de vinculaçao do 	18 21 9(004)
$cDetail .= $ccedente; // 05.1 Código do Beneficiário Identificaçao da 	22 27 9(006)
$cDetail .= '2'; // 06.1 ID emissao Identificaçao da emissao do Boleto	28 28 9(001)
$cDetail .= '0'; // 07.1 ID Postagem Identificaçao da 					29 29 9(001) 
$cDetail .= '00'; // 09.1 Taxa Permanencia Comissao de Permanencia 		30 31 9(002) 
$cDetail .= espacoBranco( 25 ); // 10.1 Uso Empresa Beneficiário  		32 56 X(025)
$cDetail .= '14'; // 11.1 Nosso Número Modalidade Identificaçao 		57 58 9(002) 
$cDetail .= $gb_nossonumero; // Identificaçao do Título na CAIXA 		59 73 9(015) NE015
$cDetail .= espacoBranco( 3 ); // 12.1 Filler Campos em branco 			74 76 X(003) 
$cDetail .= espacoBranco( 30 ); // 13.1 Mensagem Mensagem a ser 		77 106 X(030)
$cDetail .= '01'; // 14.1 Carteira Código da Carteira 					107 108 9(002)
$cDetail .= '01'; // 15.1 15.1 Código do arquivo remessa  				109 110 9(002)
$cDetail .= espacoBranco( 10 ); // 16.1 Uso Empresa Número do			111 120 X(010)
$cDetail .= ddmmyy( $vencimento ); // 17.1 vencimento 					121 126 9(006)
$cDetail .= str_pad( limpaValor( $valor ), 13, '0', STR_PAD_LEFT ); //	127 139 9(013) 
$cDetail .= '104'; // 19.1 Código do banco								140 142 9(003) ‘104’
$cDetail .= str_pad( '', 5, '0', STR_PAD_LEFT ); // 20.1 Agencia 		143 147 9(005) 
$cDetail .= '01'; // 21.1 Espécie de Título Espécie do Título 			148 149 9(002) 
$cDetail .= 'A'; // 22.1 Aceite Identificaçao de Título -  				150 150 9(001)
$cDetail .= ddmmyy( $emissao ); // 23.1 Data emissao  					151 156 9(006)
$cDetail .= '02'; // 24.1 Instruçao 1 Primeira Instruçao de Cobrança 	157 158 9(002)
$cDetail .= '00'; // 25.1 Instruçao 2 Segunda Instruçao de Cobrança 	159 160 9(002) ‘00’
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 26.1 Juros  		161 173 9(013
$cDetail .= '000000'; // 27.1 Data do Desconto Data limite para 		174 179 9(006) 
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 28.1 valor   		180 192 9(013)
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 29.1 valor 		193 205 9(013)
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 30.1 Abatimento   	206 218 9(013)
$cDetail .= $tipoinscricao; // 31.1 Tipo Inscriçao Identificador  		219 220 9(002)
$cDetail .= $cgccli; // 32.1 Número Inscriçao Número de 				221 234 9(014)			
$cDetail .= str_pad( $nomcli, 40, ' ', STR_PAD_RIGHT ); // 33.1 Nome  	235 274 X(040)
$cDetail .= str_pad( $endcli, 40, ' ', STR_PAD_RIGHT ); // 34.1 Endereço275 314 X(040)
$cDetail .= str_pad( $baicli, 12, ' ', STR_PAD_RIGHT ); // 35.1 Bairro  315 326 X(012)
$cDetail .= str_pad( limpaNumero( $cepcli ), 8, ' ', STR_PAD_RIGHT ); //327 334 9(008)
$cDetail .= str_pad( $cidcli, 15, ' ', STR_PAD_RIGHT ); // 37.1 Cidade  335 349 X(015) 
$cDetail .= 'RJ'; // 38.1 UF Unidade da Federaçao do Pagador 350 351 X(002)
$cDetail .= '000000'; // 39.1 Data da Multa Definiçao da data para pagamento de multa 352 357 9(006)
$cDetail .= str_pad( '', 10, '0', STR_PAD_LEFT ); // 40.1 valor da 358 367 9(010)
$cDetail .= str_pad( '', 22, ' ', STR_PAD_LEFT ); // 41.1 Sacador/ Avalista 368 389 X(022) 
$cDetail .= '00'; // 42.1 Instruçao 3 Terceira Instruçao de Cobrança 390 391 9(002)
$cDetail .= '00'; // 43.1 Prazo Número de dias para início do protesto/ devoluçao 392 393 9(002)
$cDetail .= '1'; // 44.1 Código da Moeda Código da Moeda 394 394 9(001). '1’ = REAL
$cDetail .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); // 45.1 Número Sequencial 395 400 9(006) 
$cDetail .= chr( 13 ) . chr( 10 ); //essa é a quebra de linha
$nReg = $nReg + 1;

?>