<?php
$cTrailler .= '9'; //TIPO DE REGISTRO IDENTIFICA�AO DO REGISTRO TRANSA�AO 		001 001 9(001) 
$cTrailler .= espacoBranco( 393 ); // BRANCOS COMPLEMENTO DO REGISTRO 			002 394 X(393)
$cTrailler .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); //N�MERO SEQ�ENCIAL  		395 400 9(06)
$cTrailler .= chr( 13 ) . chr( 10 ); //essa � a quebra de linha

?> 