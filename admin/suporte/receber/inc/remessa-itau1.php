<?php

$gb_nossonumero = limite($gb_nossonumero,10);
$nossonumero=substr($gb_nossonumero,2,8);
			
if(empty($cHeader)){
	$cHeader .='0';  // tipo de registron id registro header        	001 001 9(01) 
	$cHeader .='1'; // operacao tipo operacao remessa    				002 002 9(01)
	$cHeader .='REMESSA' ;  // literal remessa  escr. extenso       	003 009 X(07)
	$cHeader .='01' ;  // codigo contrato id tipo contrato       		010 011 9(02)
	$cHeader .= limite('COBRANCA',15); // literal cobranca   			012 026 X(15)
	$cHeader .= $ccod_agencia  ;   // agencia  mantenedora conta    	027 030 9(04)
	$cHeader .='00'; // zeros complemento d registro    				031 032 9(02)
	$cHeader .= $ccod_conta;  // conta conta da empresa        			033 037 9(05)
	$cHeader .= $ccod_conta_dv;  // dac digito autoconf conta    		038 038 9(01)
	$cHeader .= espacoBranco(8); // complemento registro     			039 046 X(08)
	$cHeader .=limite($cnome_cedente,30);  // nome da empresa       	047 076 X(30)
	$cHeader .='341';// codigo banco No banco CÂMARA COMP.   			077 079 9(03)
	$cHeader .=limite('BANCO ITAU SA',15);	// nome do banco   			080 094 X(15)
	$cHeader .=ddmmyy($dataemissao); // data geracao arquivo    		095 100 9(06)
	$cHeader .= espacoBranco(294);	// complemento de registr    		101 394 X(294)
	$cHeader .= '000001'; // numero sequencial registro no arquivo  	395 400 9(06)
	$cHeader .= chr(13).chr(10); //essa é a quebra de linha
	$nReg = $nReg+1;
}
		 		
$cDetail = $cDetail;
$cDetail .= '1';  // tipo registro id registro transacac.    			001 001 9(01)
$cDetail .= '02';  // codigo inscricao tipo inscricao empresa    		002 003 9(02)
$cDetail .= $cnpjempresa; // cnpj da empresa 							004 017 9(14)
$cDetail .= substr($ccod_agencia,0,4);  // agencia da conta    			018 021 9(04)
$cDetail .= '00';   // zeros complemento registro    					022 023 9(02)
$cDetail .= substr($ccod_conta,0,5);  // conta numero da conta     		024 028 9(05)
$cDetail .= substr($ccod_conta_dv,0,1);  // dac dig autoconf conta 		029 029 9(01)
$cDetail .= espacoBranco(4); // brancos complemento registro     		030 033 X(04)
$cDetail .= espacoZero(4); // CÓD.INSTRUÇAO/ALEGAÇAO A SER C			034 037 9(04)
$cDetail .= limite($cnome_cedente,25); 	// U EMPRESA NOTA 2   			038 062 X(25)
$cDetail .= $nossonumero;  // NOSSO NUMERO/ID TITULO DO banco NOTA 		063 070 9(08)
$cDetail .= espacoZero(13); // QTDE MOEDA  NOTA 4                 		071 083 9(08)V9(5)
$cDetail .= substr($ccarteira,0,3); // no da carteira no carteira 		084 086 9(03) 
$cDetail .=  espacoBranco(21); // uso do banco ident. oper. no			087 107 X(21)
$cDetail .= 'I';  // carteira codigo da carteira NOTA 5           		108 108 X(01)
$cDetail .= '01';  // codigo ocorrencia/ident da ocorrencia NOTA 6 		109 110 9(02)
$cDetail .= $gb_nossonumero; // ndocumento /ndocumento de cobranca  	111 120 X(10)
$cDetail .= ddmmyy($vencimento);// vencimento data venc. titulo 		121 126 9(06)
$cDetail .= str_pad(limpaValor($valor),13,'0',STR_PAD_LEFT);// valor 	127 139 9(11)V9(2)
$cDetail .= $ccod_banco;// codigo do banco   No banco CÂMARA COMP. 		140 142 9(03)
$cDetail .= espacoZero(5);// agencia cobradora/SERÁ COBRADO NOTA 9 		143 147 9(05)
$cDetail .= '08'; // especie especie do titulo NOTA 10            		148 149 X(02)
$cDetail .= 'A'; // aceite A=aceite,N=nao aceite)   					150 150 X(01)
$cDetail .= ddmmyy($emissao); // data emissao titulo NOTA 31          	151 156 9(06)

$cDetail .= '66';  // instrucao 1 nOTA 11                    			157 158 X(02)
$cDetail .= '58'; // instrucao 2 NOTA 11                    			159 160 X(02)
	

$cDetail .= str_pad(limpaValor($cmora),13,'0',STR_PAD_LEFT);// juros 	161 173 9(11)V9(02)
$cDetail .= espacoZero(6); // desconto até data limite p/ descont    	174 179 9(06)
$cDetail .= espacoZero(13);	// valor desconto a ser concedido NOTA 13 	180 192 9(11)V9(02)
$cDetail .= espacoZero(13); // valor IOF RECOLHIDO P NOTAS SEGURO  		193 205 9(11)V9(02)
$cDetail .= espacoZero(13); // abatimento a ser concedido NOTA 13    	206 218 9(11)V9(02)
$cDetail .= $tipoinscricao; // codigo de   insc. sacado 01=CPF 02=CNPJ  219 220 9(02)
$cDetail .= $cgccli; // numero de inscricao cpf ou cnpj              	221 234 9(14)
$cDetail .= limite($nomcli,30); // nome nome do sacado NOTA 15    		235 264 X(30)
$cDetail .= espacoBranco(10); // NOTA 15 complem regist       			265 274 X(10)
$cDetail .= limite($endcli,40); // logradouro rua numero e compl  		275 314 X(40)
$cDetail .= limite($baicli,12); // bairro do sacado        				315 326 X(12)
$cDetail .= limite($cepcli,8); // cep do sacado            				327 334 9(08)
$cDetail .= limite($cidcli,15); // cidade cidade do sacado        		335 349 X(15)        
$cDetail .= 'RJ'; // estado  uf do sacado            					350 351 X(02)
$cDetail .= espacoBranco(30);  // sacador/avalista  sacad ou aval. 		352 381 X(30) 
$cDetail .= espacoBranco(4); // complemento de regist.        			382 385 X(04)
$cDetail .= espacoZero(6); // data de mora data de mora            		386 391 9(06)        
$cDetail .= '90';  // prazo qtde de dias NOTA 11(A)   					392 393 9(02)
$cDetail .= espacoBranco(1);// brancos complemento de registr.    		394 394 X(01)
$cDetail .= str_pad($nReg,6,'0',STR_PAD_LEFT);// numero sequencial     	395 400 9(06)
				
$cDetail .= chr(13).chr(10); //essa é a quebra de linha
$nReg = $nReg+1;
	
?> 