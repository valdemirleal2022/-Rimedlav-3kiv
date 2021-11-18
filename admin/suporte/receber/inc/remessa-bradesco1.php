<?php

$nossonumeroDigito = str_pad($gb_nossonumero,11,'0', STR_PAD_LEFT);
$digito_verificador = modulo_11_2a7($ccarteira.$nossonumeroDigito);

if(empty($cHeader)){
	$cHeader .='0' ;     		// 001 a 001 Identificaçao do Registro 001 0 X
	$cHeader .='1';      		// 002 a 002 Identificaçao do Arquivo Remessa 001 1 X
	$cHeader .='REMESSA' ;   	// 003 a 009 Literal Remessa 007 REMESSA X
	$cHeader .='01' ;			// 010 a 011 Código de Serviço 002 01 X
	$cHeader .=str_pad('COBRANCA',15,' ', STR_PAD_RIGHT);// 012 a 026 Literal  015 COBR X
	$cHeader .=str_pad($ccedente,20,'0', STR_PAD_LEFT);// 027 a 046 Código da Empresa 020 
	$cHeader .=str_pad($cnome_cedente,30,' ', STR_PAD_RIGHT); // 047 a 076 Nome da Empresa 030 
	$cHeader .='237';// 077 a 079 Número do Bradesco na Câmara de Compensaçao 003 237 X
	$cHeader .=str_pad('BRADESCO',15,' ', STR_PAD_RIGHT); // 080 a 094 banco por Extenso 015 
	$cHeader .=ddmmyy($dataemissao)	;// 095 a 100 Data da Gravaçao do Arquivo 006 DDMMAA 
	$cHeader .= str_pad('',8,' ', STR_PAD_BOTH);
	$cHeader .='MX'; // 109 a 110 Identificaçao do sistema 002 MX Vide Obs. Pág.16 X
	$cHeader .=str_pad($contador_banco,7,'0',STR_PAD_LEFT);//  Seqüencial -  390 394 9(005)
	$cHeader .= str_pad('',277,' ', STR_PAD_BOTH);	// 118 a 394 Branco 277 Branco X
	$cHeader .= '000001';		 // 395 a 400 No Seqüencial de 006 000001 X
	$cHeader .= chr(13).chr(10); //essa é a quebra de linha
	$nReg = $nReg+1;
}
		
	$ccarteira=str_pad($ccarteira,3,'0', STR_PAD_LEFT);
	$ccod_agencia=str_pad($ccod_agencia,5,'0', STR_PAD_LEFT);
	$ccod_conta=str_pad($ccod_conta,5,'0', STR_PAD_LEFT);
 	$empresaBeneficiaria= trim('0' . $ccarteira . $ccod_agencia . $ccod_conta . $ccod_conta_dv);
 	$empresaBeneficiaria= str_pad($empresaBeneficiaria,17,'0',STR_PAD_LEFT);

	$cDetail = $cDetail;
	$cDetail .= '1';  	// 001 a 001 Identificaçao do Registro 001 1 X 
	$cDetail .= str_pad('',5,'0',STR_PAD_BOTH); // 002 a 006 Agencia de Débito (opcional) 		
	$cDetail .= '0'; // 007 a 007  001 Dígito da Agencia do Pagador Vide Obs.Pág. 17  X
	$cDetail .= str_pad('',5,'0',STR_PAD_BOTH);	// 008 a 012 Razao da Conta Corrente (opcional)
	$cDetail .= str_pad('',7,'0',STR_PAD_BOTH); // 013 a 019 onta Corrente (opcional) 			
	$cDetail .= '0'; // 020 a 020 Dígito da Conta Corrente (opcional) 	001 Dígito da Conta do 
	$cDetail .= $empresaBeneficiaria ;  // 021 a 037 Id da Empresa Beneficiária no banco 	017 
	$cDetail .= str_pad($gb_nossonumero,25,'0', STR_PAD_LEFT); // 038 a 062 No Controle do 
	$cDetail .= '000'; // 063 a 065 Cód do banco a ser debitado 003 No do banco “237” 
	$cDetail .= '2'; // 066 a 066 Multa 001 Se= 2 considerar percentual de multa.Se =0,sem multa. 
	$cDetail .= str_pad(limpaValor($banco["multa"]),4,'0',STR_PAD_LEFT);// 067 a 070 multa 004	
		$cDetail .= str_pad($gb_nossonumero,11,'0', STR_PAD_LEFT); //071 a 081 Id do Título  
		$cDetail .= $digito_verificador ; // 082 a 082 Dig Auto Conferencia do Núm Bancário. 
		$cDetail .= str_pad('',10,'0', STR_PAD_BOTH); //083 a 092 Desconto Bonificaçao por dia 		
		$cDetail .= '2'; // 093 a 093 Conda emissao da Papeleta de Cobrança 	
		$cDetail .= 'N'; // 094 a 094 Id se emite Boleto para Déb Automático 001 N= Nao registra 
		$cDetail .= str_pad('',10,' ', STR_PAD_BOTH);// 095 a 104 Identificaçao da Operaçao do
		$cDetail .= str_pad('',1,' ', STR_PAD_BOTH); // 105 a 105 Indicador Rateio Crédito (opcional) 
		$cDetail .= '2'; // 106 a 106 End para Aviso do Débito CC (opcional) 001 Vide Obs. Pág. 19 X
		$cDetail .= str_pad('',2,' ', STR_PAD_BOTH); // 107 a 108 Branco 002 Branco X 
		$cDetail .= '01';// 109 a 110 Identificaçao da ocorrencia 	
		$cDetail .= str_pad($gb_nossonumero,10,'0',STR_PAD_LEFT);    // 111 a 120 No do
		$cDetail .= ddmmyy($vencimento);// 121 a 126 Data do vencimento do Título 		
		$cDetail .= str_pad(limpaValor($valor),13,'0',STR_PAD_LEFT);// valor titulo   valor nominal 
		$cDetail .= str_pad('',3,'0', STR_PAD_BOTH); // 140 a 142 banco Encarregado da Cobrança 003
		$cDetail .= str_pad('',5,'0', STR_PAD_BOTH); // 143 a 147 Agencia Depositária 005 Preencher 0
		$cDetail .='01';// 148 a 149 Espécie de Título 002 01-Duplicata 02-Nota  03-Nota de 
		$cDetail .='N'; // 150 a 150 Identificaçao 	001 Sempre = N X
		$cDetail .= ddmmyy($emissao); // 151 a 156 Data da emissao do Título 006 DDMMAA X
		$cDetail .= $ccad_cod_instrucoes1; // 157 a 158 1a instruçao 002 Vide Obs. Pág. 20 X
		$cDetail .= $ccad_cod_instrucoes2; // 159 a 160 2a instruçao 002 Vide Obs. Pág. 20 X
		$cDetail .= str_pad(limpaValor($cmora),13,'0',STR_PAD_LEFT);// 161 a 173 cobrado por Dia de 
		
		$cDetail .= '000000'; // 174 a 179 Data Limite P/Concessao de Desconto 006 DDMMAA X
		$cDetail .= str_pad('',13,'0', STR_PAD_LEFT);// 180 a 192 valor  Desconto 013  valor  
		$cDetail .= str_pad('',13,'0', STR_PAD_LEFT);// 193 a 205 valor IOF 013 valor do IOF – Vide 
		$cDetail .= str_pad('',13,'0', STR_PAD_LEFT);// 206 a 218 valor do Abatimento a ser 
		$cDetail .= $tipoinscricao; // 219 a 220  Tipo de Inscriçao do Pagador 002 01-
		$cDetail .= $cgccli ; // 221 a 234 No Inscriçao do Pagador 014 CNPJ/ CPF 
		$cDetail .= str_pad($nomcli,40,' ', STR_PAD_RIGHT);  // 235 a 274 Nome do Pagador 040 
		$cDetail .= str_pad($endcli,40,' ', STR_PAD_RIGHT);// 275 a 314 Endereço  040 Endereço 
		$cDetail .= str_pad('',12,' ', STR_PAD_BOTH);// 315 a 326 1a Mensagem 012 // 20260-132
		$cDetail .= limite($cepcli,8);  ;// 327 a 331 CEP 005 CEP Pagador X 
		$cDetail .= str_pad('',60,' ', STR_PAD_BOTH);// 335 a 394 Sacador/Avalista ou 2a Mensagem 060 
		$cDetail .= str_pad($nReg,6,'0', STR_PAD_LEFT);// 395 a 400 No Seqüencial do Registro 006 No 
		$cDetail .= chr(13).chr(10); //essa é a quebra de linha
		$nReg = $nReg+1;
	
?> 