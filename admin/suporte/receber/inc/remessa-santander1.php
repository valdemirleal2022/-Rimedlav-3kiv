<?php

$gb_nossonumero = substr( $gb_nossonumero, 2, 7 );
$seu_numero = str_pad( $gb_nossonumero, 10, '0', STR_PAD_LEFT );

$digito_verificador = mod11_2a9( $gb_nossonumero );
$gb_nossonumero = $gb_nossonumero + $digito_verificador;

if ( empty( $cHeader ) ) {
	$cHeader .= '0'; // 001 001 9(001) Código do registro = 0 
	$cHeader .= '1'; // 002 002 9(001) Código da remessa = 1
	$cHeader .= 'REMESSA'; // 003 009 X(007) Literal de transmissao = REMESSA
	$cHeader .= '01'; // 010 011 9(002) Código do serviço = 01
	$cHeader .= str_pad( 'COBRANCA', 15, ' ', STR_PAD_RIGHT ); // 012 026 X(015)  = COBRANÇA
	$cHeader .= str_pad( $ccad_cod_transmissao, 20, ' ', STR_PAD_RIGHT ); // 027 046 9(020) 
	$cHeader .= str_pad( $cnome_cedente, 30, ' ', STR_PAD_RIGHT ); // 047 076 X(030) Nome do cedente
	$cHeader .= '033'; // 077 079 9(003) Código do banco = 033
	$cHeader .= str_pad( 'SANTANDER', 15, STR_PAD_LEFT ); // 080 094 X(015) Nome do banco = SANTANDER
	$cHeader .= ddmmyy( $dataemissao ); // 095 100 9(006) Data de Gravaçao
	$cHeader .= str_pad( '', 16, '0', STR_PAD_LEFT ); // 101 116 9(016) Zeros
	$cHeader .= str_pad( '', 274, STR_PAD_BOTH ); // 117 391 X(274) Brancos
	$cHeader .= str_pad( '', 3, '0', STR_PAD_LEFT ); // 392 394 9(003) opcional, se informada,
	$cHeader .= '000001'; // 395 400 9(006) Número seqüencial do registro no arquivo = 000001	
	$cHeader .= chr( 13 ) . chr( 10 ); //essa é a quebra de linha
	$nReg = $nReg + 1;
	echo 'nReg : ' . str_pad( $nReg, 6, '0', STR_PAD_LEFT ) . '<br>';
}

$cDetail = $cDetail;
$cDetail .= '1'; // 001 001 9(001) Código do registro = 1
$cDetail .= '02'; // 002 003 9(002) Tipo de inscriçao do cedente 01 = CPF 02 = CGC)
$cDetail .= $cnpjempresa; // 004 017 9(014) CGC ou CPF do cedente
$cDetail .= str_pad( $ccad_cod_transmissao, 20, ' ' ); // 018 037 9(020) (nota 1)
$cDetail .= str_pad( '', 25, STR_PAD_BOTH ); //038 062 X(025) Número de controle do cedente
$cDetail .= $gb_nossonumero; // 063 070 9(008) Nosso número (nota 3)
$cDetail .= '000000'; // 071 076 9(006) Data do segundo desconto
$cDetail .= espacoBranco( 1 ); // 077 077 X(001) Branco
$cDetail .= '4'; // 078 078 9(001) Informaçao de multa = 4, página 16
$cDetail .= str_pad( $cmulta, 4, '0', STR_PAD_LEFT ); // 079 082 9(004)v99 Percentual multa por 
$cDetail .= '00'; // 083 084 9(002) Unidade de valor moeda corrente = 00
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 085 097 9(013)v99 valor do título em outra 
$cDetail .= str_pad( '', 4, '0', STR_PAD_LEFT ); // 098 101 X(004) Brancos
$cDetail .= str_pad( '', 6, '0', STR_PAD_LEFT ); // 102 107 9(006) Data para cobrança de multa. 
$cDetail .= '5'; // 108 108 9(001) Código da carteira 1=ELETRÔNICA COM REGISTRO 3=CAUCIONADA 
$cDetail .= '01'; // 109 110 9(002) Código da ocorrencia: 01=ENTRADA DE TÍTULO  02 = BAIXA DE TÍTULO 
$cDetail .= $seu_numero; // 111 120 X(010) Seu número
$cDetail .= ddmmyy( $vencimento ); // 121 126 9(006) Data de vencimento do título
$cDetail .= str_pad( $valor, 13, '0', STR_PAD_LEFT ); // 127 139 9(013)v99 valor do título - moeda 
$cDetail .= '033'; // 140 142 9(003) Número do banco cobrador = 033
$cDetail .= str_pad( '', 5, '0', STR_PAD_LEFT ); // 143 147 9(005) Códi agencia Santander, opcional 
$cDetail .= '01'; // 148 149 9(002) Espécie: 01=DUPLICATA 02=NOTA PROMISSÓRIA 03=APÓLICE 05 = RECIBO 
$cDetail .= 'N'; // 150 150 X(001) Tipo de aceite = N
$cDetail .= ddmmyy( $emissao ); // 151 156 9(006) Data da emissao do título 
$cDetail .= $ccad_cod_instrucoes1; // 157 158 9(002) Primeira instruçao cobrança
$cDetail .= $ccad_cod_instrucoes2; // 159 160 9(002) Segunda instruçao cobrança
$cDetail .= str_pad( $cmora, 13, '0', STR_PAD_LEFT ); // 161 173 9(013)v99 valor de mora a ser 
$cDetail .= '000000'; // 174 179 9(006) Data limite para concessao de desconto
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 180 192 9(013)v99 valor de desconto a ser 
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 193 205 9(013)v99 valor do IOF a ser recolhido 
$cDetail .= str_pad( '', 13, '0', STR_PAD_LEFT ); // 206 218 9(011)v99 valor do abatimento a ser 
$cDetail .= $tipoinscricao; // 219 220 9(002) Tipo de inscriçao do sacado: 01 = CPF 02 = CGC
$cDetail .= $cgccli; // 221 234 9(014) CGC ou CPF do sacado
$cDetail .= limite( $nomcli, 40 ); // 235 274 X(040) Nome do sacado
$cDetail .= limite( $endcli, 40 ); // 275 314 X(040) Endereço do sacado
$cDetail .= limite( $baicli, 12 ); // 315 326 X(012) Bairro do sacado
$cDetail .= substr( $cepcli, 0, 4 ); // 327 331 9(005) CEP do sacado
$cDetail .= substr( $cepcli, 6, 3 ); // 332 334 9(003) Complemento do CEP
$cDetail .= limite( $cidcli, 15 ); // cidade cidade do sacado        				335 349 X(15)     
$cDetail .= 'RJ'; // 350 351 X(002) UF Estado do sacado
$cDetail .= espacoBranco( 30 ); // 352 381 X(030) Nome do sacador ou coobrigado
$cDetail .= espacoBranco( 1 ); // 382 382 X(001) Brancos
$cDetail .= 'I'; // 383 383 X(001) Identificador do Complemento (i maiúsculo – vide nota 2)
$cDetail .= '35'; // 384 385 9(002) Complemento (nota 2)
$cDetail .= espacoBranco( 6 ); // 386 391 X(006) Brancos
$cDetail .= '00'; // 392 393 9(002) Número de dias para protesto.Quando posiçoes 157/158 ou 159/160 
$cDetail .= espacoBranco( 1 ); // 394 394 X(001) Branco
$cDetail .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); // 395 400 9(006) Número seqüencial do registro 
$cDetail .= chr( 13 ) . chr( 10 ); //essa é a quebra de linha
$nReg = $nReg + 1;

?>