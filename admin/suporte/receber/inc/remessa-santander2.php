<?php
$cTrailler .= '9'; // 001 001 9(001) Código do registro = 9
$cTrailler .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); // 002 007 9(006) Quantidade total de linhas 
$cTrailler .= str_pad( $tvalor, 13, '0', STR_PAD_LEFT ); // 008 020 9(013) 008 020 9(013)v99 v
$cTrailler .= str_pad( '', 374, '0', STR_PAD_LEFT ); // 021 394 9(374) Zeros
$cTrailler .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); // 395 400 9(006) Número seqüencial d
$cTrailler .= chr( 13 ) . chr( 10 ); //essa é a quebra de linha
?>