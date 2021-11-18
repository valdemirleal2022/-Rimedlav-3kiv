<?php
$cTrailler .= '9'; //TIPO DE REGISTRO IDENTIFICAÇAO DO REGISTRO TRANSAÇAO 		001 001 9(001) 
$cTrailler .= espacoBranco( 393 ); // BRANCOS COMPLEMENTO DO REGISTRO 			002 394 X(393)
$cTrailler .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); //NÚMERO SEQÜENCIAL  		395 400 9(06)
$cTrailler .= chr( 13 ) . chr( 10 ); //essa é a quebra de linha

?> 