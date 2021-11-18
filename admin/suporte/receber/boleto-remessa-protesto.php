<?php

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	$leitura = read('receber',"WHERE protesto='1' AND retorno<>'Confirmado' ORDER BY protesto_data DESC");
	
?>


<div class="content-wrapper">

  <section class="content-header">
          <h1>Boleto - Remessa - Protesto</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Receber</li>
            <li>Boleto</li>
            <li class="active">Remessa</li>
          </ol>
  </section>
  
 <section class="content">
    <div class="box box-default">
         
          <div class="box-header with-border">
                <h3 class="box-title"><small>Gerando Arquivo de Remessa - Protesto</small></h3>
	    	</div><!-- /.box-header -->
          
          
      <div class="box-body table-responsive">
         <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool"> 

<?php
				
$pasta = "remessa/";
if(is_dir($pasta)){
	$diretorio = dir($pasta);
	while($arquivo = $diretorio->read()){
	   if(($arquivo != '.') && ($arquivo != '..')){
		   unlink($pasta.$arquivo);
		 //  echo 'Arquivo '.$arquivo.' foi apagado com sucesso. <br />';
	   }
	}
	$diretorio->close();
}else{
	echo 'A pasta não existe.';
}


$DATA['DIA'] = date('d');
$DATA['MES'] = date('m');
$DATA['ANO'] = date('y');
				
$diretorio= 'remessa';
if(is_dir("$diretorio")) {
	//echo 'O diretorio já existe !';
 }else{
	mkdir ("$diretorio", 0777); // criar o diretorio com permissao
}

define("REMESSA",setaurl(),true);
$cArqu_CNAB = $DATA['DIA'].$DATA['MES'].$DATA['ANO'].".REM";

echo '<br>';
echo 'De : ' . converteData($data1) .'<br>';'<br>';
echo 'Nome do Arquivo : ' . $cArqu_CNAB.'<br>';

$cHeader   = '';
$cDetail   = '';
$cTrailler = '';

$nReg  = 1;
$tvalor = 0;
				
$bancoConta = mostra('banco',"WHERE id AND id='$banco'");
$bancoId=$bancoConta['id']; 
		 
$contador_banco=$bancoConta['contador_remessa']; //sequenciador para caixa/bradesco
$contador_banco=$contador_banco+1; 
$ban['contador_remessa'] = $contador_banco;
update('banco',$ban,"id = '$bancoId'");	
				
if($bancoConta['codigo_banco']== '237'){ // BRADESCO	
	//	*!*	CBDDMM??.REM 	*!*	CB – Cobrança Bradesco DD – O Dia geração do arquivo MM – O Mês da geração do Arquivo ?? - variáveis alfanumérico-Numéricas
   $cArqu_CNAB = 'CB' . $DATA['DIA'] . $DATA['MES'] . $DATA['ANO']. 'BR.REM';
 }
				
				
if(!$leitura){
	
	echo '<div class="alert alert-warning">Nenhum boleto ! Arquivo nao Gerado!</div>';	
	
 }else{
	
	foreach($leitura as $mostra):
	
		$clienteId = $mostra['id_cliente'];
		$numrec = $mostra['id'];
		$receberId = $mostra['id'];
		$bancoId = $mostra['banco'];
		$emissao=$mostra['emissao'];
		$vencimento=$mostra['vencimento'];
	
		$valor=$mostra['valor']+$mostra['juros']-$mostra['desconto'];
		
		$dataemissao=$mostra['emissao'];
		
		$dataemissao = date("Y-m-d");
		$emissao = date("Y-m-d");
		$vencimento = date("Y-m-d", strtotime("+1 day"));
		
		$cad['remessa']= $cArqu_CNAB;
		$cad['usuario']	=  $_SESSION['autUser']['nome'];
		update('receber',$cad,"id = '$receberId'");

		$tvalor=$tvalor+$valor;
		
		$gb_nossonumero=$mostra['id'];
		$gb_nossonumero=str_pad($gb_nossonumero,10,'0',STR_PAD_LEFT);
		$banco = mostra('banco',"WHERE id ='$bancoId'");
  
		$cnom_banco =trim($banco['nome']);
		$ccod_banco =trim($banco['codigo_banco']);
	 	$ccedente= trim($banco['codigo_cedente']);
		$ccod_agencia = trim($banco['agencia']);
		$ccod_conta = trim($banco['conta']);
		$ccod_conta_dv=trim($banco['conta_digito']);
		$ccarteira = trim($banco['carteira']);
  		$ccad_cod_transmissao = trim($banco['transmissao']);
 		$ccad_cod_instrucoes1 = '00';
 		$ccad_cod_instrucoes2 = '00';
	
 		$percentual_juros=$banco["juros"];
		$percentual_multa=$banco["multa"];
	
		$percentual_juros='0.66';
		$percentual_multa='5';
 		
 		$cmora = ($valor*$percentual_juros)/100;
		$cmora = converteValor($cmora) ;
	
	
		$cmulta=($valor*$percentual_multa)/100; // multa apos vencimento
		$cmulta = converteValor($cmulta) ;
	
		$valor=converteValor($valor);
	
		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
	    $nomcli=$cliente['nome'];
	    $endcli=$cliente['endereco'].' '.$cliente['numero'].' '.$cliente['complemento'];
	    $baicli=$cliente['bairro'];
	    $cepcli=$cliente['cep'];
	    $cidcli=$cliente['cidade'];
	    $cgccli=$cliente['cnpj'];
        $cpfcli=$cliente['cpf'];
		
		echo 'Id|Cliente : ' . $receberId .' | '. $nomcli.'<br>';
		
 		if($emissao>$vencimento){ 
			echo '<div class="alert alert-warning">Data do vencimento menor que emissao - Arquivo nao Gerado!</div>';	
			return;
 		} 
 		if($ccod_banco=='033'){  // SANTANDER
 			if(empty($ccad_cod_transmissao)){	
 	 			echo '<div class="alert alert-warning">Sem Codigo de Transmissao - Arquivo nao Gerado!</div>';
 			} 
 		} 
		if(empty($ccad_cod_instrucoes1)){
			echo '<div class="alert alert-warning">Sem codigo de Instruçao 1 - Arquivo nao Gerado!</div>';
		} 
		if(empty($ccad_cod_instrucoes2)){
			echo '<div class="alert alert-warning">Sem codigo de Instruçao 2 - Arquivo nao Gerado!</div>';
		} 
		
		echo 'Valor : ' . $valor.'<br>';
		echo 'Juros : ' . $cmora.'<br>';
		echo 'Multa : ' . $cmulta.'<br>';

		$cgccli=limpaNumero($cgccli);

		if(empty($cepcli)){
			echo '<div class="msgError">Desculpe o CEP informado é inválido!</div>'.'<br>';
			return;
		} 
		

		if(!empty($cgccli)){ 
			$cgccli=limpaNumero($cgccli);
		  	$cgccli=str_pad($cgccli,14,'0',STR_PAD_LEFT);
			$tipoinscricao='02';
		}

		if(!empty($cpfcli)) { // 01 - CPF 02 - CNPJ  
			 $cpfcli=limpaNumero($cpfcli);
		   	 $cgccli=str_pad($cpfcli,14,'0',STR_PAD_LEFT);
		  	 $tipoinscricao='01';
		}
		
		$cgccli=limpaNumero($cgccli);
		$cepcli=limpaNumero($cepcli);

		$empresa = mostra('empresa',"WHERE id");
		$nomeempresa=$empresa['nome'];
		$cnome_cedente=$empresa['nome'];
		$cnpjempresa=limpaNumero($empresa['cnpj']);
		$cnome_cedente=$empresa['nome'];
	
		echo 'CNPJ/CPF : ' . $tipoinscricao.'|'.$cgccli.'<br>';

		if($ccod_banco=='237'){   // BRADESCO - atualizado em 07/10/2017
			include("inc/remessa-bradesco1.php"); 
		}
		
 		if($ccod_banco=='341'){   // ITAU - atualizado em 25/09/2017
			$gb_nossonumero = limite($gb_nossonumero,10);
$nossonumero=substr($gb_nossonumero,2,8);
			
if(empty($cHeader)){
	$cHeader .='0';  // tipo de registron id registro header        	001 001 9(01) 
	$cHeader .='1'; // operacao tipo operacao remessa    				002 002 9(01)
	$cHeader .='REMESSA' ;  // literal remessa  escr. extenso       	003 009 X(07)
	$cHeader .='01' ;  // IDENTIFICAÇÃO DO TIPO DE SERVIÇO    			010 011 9(02)
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
$cDetail .= '31';  // codigo ocorrencia/ident da ocorrencia NOTA 6 		109 110 9(02)
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
$cDetail .= espacoZero(2);  // prazo qtde de dias NOTA 11(A)   			392 393 9(02)
$cDetail .= espacoBranco(1);// brancos complemento de registr.    		394 394 X(01)
$cDetail .= str_pad($nReg,6,'0',STR_PAD_LEFT);// numero sequencial     	395 400 9(06)
				
$cDetail .= chr(13).chr(10); //essa é a quebra de linha
$nReg = $nReg+1;
		}
	
		if($ccod_banco=='033'){   // SANTANDER - atualizado em 26/08/2016
			include("inc/remessa-santander1.php"); 
		}
		
		if($ccod_banco=='104'){   // CAIXA - atualizado 26/08/2016
			
			include("inc/remessa-caixa1.php"); 
		}

	 endforeach;
	
	
	if($ccod_banco == '237'){   // BRADESCO
		include("inc/remessa-bradesco2.php"); 
	}
	
	if($ccod_banco == '341'){   // ITAU
		include("inc/remessa-itau2.php"); 
	}
	
	if($ccod_banco == '033'){   // SANTANDER
		include("inc/remessa-santander2.php"); 
	}
	
	if($ccod_banco == '104'){   // CAIXA
		include("inc/remessa-caixa2.php"); 			
	}
						
	$conteudo=$cHeader.$cDetail.$cTrailler;

	if (!$handle = fopen('remessa/'.$cArqu_CNAB, 'w+')){
		 echo "Não foi possível abrir o arquivo ($cArqu_CNAB)";
	}
	// Escreve $conteudo no nosso arquivo aberto.
	if (fwrite($handle, "$conteudo") === FALSE) {
		echo "Não foi possível escrever no arquivo ($cArqu_CNAB)";
	}
	
	fclose($handle);
	// Escreve $conteudo no nosso arquivo aberto.
	$arquivo = fopen(URL.'/admin/remessa'.$cArqu_CNAB,'r');
	if ($arquivo == false) die('O arquivo nao existe.');
	?> 

          </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
  </section><!-- /.content -->
 
  <section class="content">
      <div class="box box-default">
           
          <div class="box-header with-border">
              <h3 class="box-title"> Arquivo de remessa gerado com sucesso !</h3>
      	  </div><!-- /.box-header -->
          
          <div class="box-body">

          <p>
          <?php

			echo "Nome : " .$cnom_banco .'<BR>';
			echo "Arquivo : " .$cArqu_CNAB .'<BR><BR>';

			$pasta = './remessa/';
			if(is_dir($pasta)){
				foreach(glob("$pasta*.REM") as $arquivo){
					//echo "Download : <a href='$arquivo'>$arquivo</a><br /><br />";
					echo substr($arquivo,10).' ->'."<a href=".$arquivo." download>Clique para Download</a><br />";

				}
			}else{
				echo 'A pasta nao existe.';
			}
		 }

		 ?>

        </p>
        <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
       </div><!-- /.col-md-6 -->

  </div><!-- /.col-md-12 -->
 </section><!-- /.content -->
 
</div><!-- /.content-wrapper -->


<?php


function ddmmyy($dataformatar){
         $d = explode ("-", $dataformatar);//tira a barra
          $dataformatar = $d[2].$d[1].substr($d[0],2,2);//separa as datas $d[2] = ano $d[1] = mes etc...
          return $dataformatar;
}


function modulo_11($num, $base=9, $r=0)  {
    $soma = 0;
    $fator = 2;

    /* Separacao dos numeros */
    for ($i = strlen($num); $i > 0; $i--) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num,$i-1,1);
        // Efetua multiplicacao do numero pelo falor
        $parcial[$i] = $numeros[$i] * $fator;
        // Soma dos digitos
        $soma += $parcial[$i];
        if ($fator == $base) {
            // restaura fator de multiplicacao para 2 
            $fator = 1;
        }
        $fator++;
    }

    /* Calculo do modulo 11 */
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }
        return $digito;
    } elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}

function mod11_2a9($num) {// Calculo de Modulo 11 "Invertido" (com pesos de 9 a 2  e não de 2 a 9)
   $ftini = 2;
   $fator = $ftfim = 9;
   $soma = 0;
	
   for ($i = strlen($num); $i > 0; $i--) 
   {
      $soma += substr($num,$i-1,1) * $fator;
	  if(--$fator < $ftini) 
	     $fator = $ftfim;
    }
	
    $digito = $soma % 11;
	
	if($digito > 9) 
	   $digito = 0;
	
	return $digito;
}
	
function modulo_11_2a7($num, $base=7, $r=0)  {

    $soma = 0;
    $fator = 2;

    /* Separacao dos numeros */
    for ($i = strlen($num); $i > 0; $i--) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num,$i-1,1);
        // Efetua multiplicacao do numero pelo falor
        $parcial[$i] = $numeros[$i] * $fator;
        // Soma dos digitos
        $soma += $parcial[$i];
        if ($fator == $base) {
            // restaura fator de multiplicacao para 2 
            $fator = 1;
        }
        $fator++;
    }

    /* Calculo do modulo 11 */
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }
        return $digito;
    } elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}


function espacoBranco($int){
        $espacoBranco = '';
        for($i = 1; $i <= $int; $i++)  {
            $espacoBranco .= ' ';
        }
    return $espacoBranco;
}

function limiteZero($valor,$limite){
        $valor = '';
        for($i = 1; $i <= $limite; $i++){      
            $$valor .= '0';
        }
    return $$valor;
}

function espacoZero($int){
        $espacoBranco = '';
        for($i = 1; $i <= $int; $i++){      
            $espacoBranco .= '0';
        }
    return $espacoBranco;
}

function limite($palavra,$limite){
    if(strlen($palavra) >= $limite)    {
        $var = substr($palavra, 0,$limite);
    }else{
        $max = (int)($limite-strlen($palavra));
        $var = $palavra.espacoBranco($max,"brancos");
    }
    return $var;
}


function limpaValor($valor){
 $valor = trim($valor);
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 return $valor;
}
 

?> 