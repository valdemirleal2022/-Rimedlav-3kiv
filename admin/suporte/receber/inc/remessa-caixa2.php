<?php
$cTrailler .= '9'; // 01.9 C�digo Identificador do Tipo de Registro no Arquivo 1 1 9(001) �9�
$cTrailler .= espacoBranco( 393 ); // 02.9 Uso Exclusivo Uso Exclusivo CAIXA 2 394 X(393) Espa�os
$cTrailler .= str_pad( $nReg, 6, '0', STR_PAD_LEFT ); // 03.9 No Sequencial N�mero Sequencial do Registro no Arquivo 395 400 9(006)
$cTrailler .= chr( 13 ) . chr( 10 ); //essa � a quebra de linha
?>