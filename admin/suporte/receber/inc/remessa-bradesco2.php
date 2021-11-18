<?php

	$cTrailler .='9';	//TIPO DE REGISTRO IDENTIFICAÇAO DO REGISTRO TRAILER 001 001 9(01) 9
	$cTrailler .= espacoBranco(393);
	$cTrailler .= str_pad($nReg,6,'0',STR_PAD_LEFT);
	$cTrailler .= chr(13).chr(10); //essa é a quebra de linha
	
?> 