<?php
$cTrailler .= '9'; // 001 001 9(001) C�digo do registro = 9
$cTrailler .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); // 002 007 9(006) Quantidade total de linhas 
$cTrailler .= str_pad( $tvalor, 13, '0', STR_PAD_LEFT ); // 008 020 9(013) 008 020 9(013)v99 v
$cTrailler .= str_pad( '', 374, '0', STR_PAD_LEFT ); // 021 394 9(374) Zeros
$cTrailler .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); // 395 400 9(006) N�mero seq�encial d
$cTrailler .= chr( 13 ) . chr( 10 ); //essa � a quebra de linha
?>