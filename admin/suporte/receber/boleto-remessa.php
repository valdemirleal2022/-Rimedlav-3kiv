<?php

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	$data1=$_SESSION['$data1'];
	$banco=$_SESSION['$banco'];
	$formpag=$_SESSION['$formpag'];
	
	$leitura = read('receber',"WHERE remessa_data='$data1' AND banco='$banco' AND formpag='$formpag' 
							AND status='EM ABERTO'  ORDER BY remessa_data DESC");
	
?>


<div class="content-wrapper">

  <section class="content-header">
          <h1>Boleto - Remessa</h1>
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
                <h3 class="box-title"><small>Gerando Arquivo de Remessa</small></h3>
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


$DATA['DIA'] = date( 'd', strtotime( $data1 ) );
$DATA['MES'] = date('m', strtotime( $data1 ) );
$DATA['ANO'] = date('y', strtotime( $data1 ) );
				
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
$empresaId=$bancoConta['empresa']; 
		 
$contador_banco=$bancoConta['contador_remessa']; //sequenciador para caixa/bradesco
$contador_banco=$contador_banco+1; 
$ban['contador_remessa'] = $contador_banco;
update('banco',$ban,"id = '$bancoId'");	
				
				
				
if($bancoConta['codigo_banco']== '237'){ // BRADESCO	
	//	*!*	CBDDMM??.REM 	*!*	CB – Cobrança Bradesco DD – O Dia geração do arquivo MM – O Mês da geração do Arquivo ?? - variáveis alfanumérico-Numéricas
   $cArqu_CNAB = 'CB' . $DATA['DIA'] . $DATA['MES'] . $DATA['ANO']. 'BR.REM';
 }
				
				
$empresa = mostra('empresa',"WHERE id='$empresaId'");
			
$nomeempresa=$empresa['nome'];
$cnome_cedente=$empresa['nome'];
$cnpjempresa=limpaNumero($empresa['cnpj']);
$cnome_cedente=$empresa['nome'];			
echo 'Empresa : ' .$nomeempresa .'<br><br>';
				
if(!$leitura){
	
	echo '<div class="alert alert-warning">Nenhum boleto ! Arquivo nao Gerado!</div>';	
	
 }else{
	
	foreach($leitura as $mostra):
		
		//PGI / PGF / PRI / PIG / AMB / GGI e PGA(PADRÃO)
		$contratoTipo='E';
		if($mostra['contrato_tipo']>'7'){
			$contratoTipo='I';
		}
		
	    $gerarArquivo='SIM';
		if($mostra['retorno']=='Confirmado'){
			 $gerarArquivo='NAO';
		}
		if($mostra['retorno']=='Alterado'){
			 $gerarArquivo='NAO';
		}
	
		if($gerarArquivo=='SIM'){
			
			$clienteId = $mostra['id_cliente'];
			$numrec = $mostra['id'];
			$receberId = $mostra['id'];
			$bancoId = $mostra['banco'];
			$emissao=$mostra['emissao'];
			$vencimento=$mostra['vencimento'];

			$valor=$mostra['valor']+$mostra['juros']-$mostra['desconto'];
			$valor=converteValor($valor);

			$dataemissao=$mostra['emissao'];

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

			$cmora = ($valor*$percentual_juros)/100;
			$cmora = converteValor($cmora) ;

			$cmulta=($valor*$percentual_multa)/100; // multa apos vencimento

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId'");
			
			// DADOS DO COBRANÇA
			$clienteCobranca = read('cliente_cobranca',"WHERE id_cliente = '$clienteId'");
			if($clienteCobranca ){
				foreach($clienteCobranca as $cliente);
			}

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
			$cepcli=trim(limpaNumero($cepcli));

			

			echo 'CNPJ/CPF : ' . $tipoinscricao.'|'.$cgccli.'<br>';
			echo 'CEP : ' . $cepcli.'<br>';
			
			if(strlen($cepcli)<8){
				echo '<div class="msgError">Desculpe o CEP informado é inválido!</div>'.'<br>';
				return;
			} 
			

			if($ccod_banco=='237'){   // BRADESCO - atualizado em 07/10/2017
				include("inc/remessa-bradesco1.php"); 
			}

			if($ccod_banco=='341'){   // ITAU - atualizado em 25/09/2017
				include("inc/remessa-itau1.php"); 
			}

			if($ccod_banco=='033'){   // SANTANDER - atualizado em 26/08/2016
				include("inc/remessa-santander1.php"); 
			}

			if($ccod_banco=='104'){   // CAIXA - atualizado 26/08/2016

				include("inc/remessa-caixa1.php"); 
			}
			
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