<?php

if(function_exists(ProtUser)){
	if(!ProtUser($_SESSION['autUser']['id'])){
		header('Location: painel.php?execute=suporte/403');	
	}	
}

$dataEmissao = $_SESSION[ 'dataEmissao' ];
$contratoTipo = $_SESSION['contratoTipo'];
$empresaId = $_SESSION['empresa'];

$leitura = read('receber',"WHERE nota_emissao='$dataEmissao' ORDER BY nota_emissao ASC");

if (!empty($contratoTipo)) {
	$leitura = read('receber',"WHERE id AND nota_emissao='$dataEmissao' AND contrato_tipo='$contratoTipo' ORDER BY nota_emissao ASC");
}

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>NFe - Gerar Remessa</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">NFe</a></li>
         <li class="active">NFe - Gerar Remessa</li>
       </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

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
	   }
	}
	$diretorio->close();
}

$DATA['DIA'] = date('d');
$DATA['MES'] = date('m');
$DATA['ANO'] = date('y');
					
$notaEmissao = date("Y-m-d");

$diretorio= 'remessa';
if(is_dir("$diretorio")) {
	//echo 'O diretorio j� existe !';
 }else{
	mkdir ("$diretorio", 0777); // criar o diretorio com permissao
}

define("REMESSA",setaurl(),true);
$cArqu_CNAB = $DATA['DIA'].$DATA['MES'].$DATA['ANO']."NFe.REM";
					
$tipo = mostra('contrato_tipo',"WHERE id ='$contratoTipo'");
					
echo '<br>';
echo 'Data : ' . converteData($dataEmissao) .'<br>';
echo 'Tipo de Contrato : ' . $tipo['nome'] .'<br>';
echo 'Nome do Arquivo : ' . $cArqu_CNAB.'<br>';

$empresa = mostra('empresa',"WHERE id='$empresaId'");
$nomeEmpresa=$empresa['nome'];
$inscricaoEmpresa=limpaNumero($empresa['inscricao']);
$cnpjEmpresa=limpaNumero($empresa['cnpj']);
$notaMunicipio=$empresa['cidade'];
$CodigoServicoFederal  = $empresa['codigo_servico_federal'];
$CodigoServicoMunicipal = $empresa['codigo_servico_municipal'];
$optanteSimples= $empresa['optante_simples'];
 
echo 'Empresa : ' . $empresa['nome'] .'<br><br>';

$cHeader   = '';
$cDetail   = '';
$cTrailler = '';

$TotalDeducao=0;
$totalValorNota=0;
$TotalDescontoCondicional=0;
$TotalDescontoIncondicional=0;

$nReg=0;

if(!$leitura){
	echo '<div class="alert alert-warning">Nenhum NFe ! Arquivo nao Gerado!</div>';	
 }else{
	 
	foreach($leitura as $mostra):
    
		$contratoId = $mostra['id_cliente'];
		$clienteId = $mostra['id_cliente'];
	
		$numeroRps=$mostra['id'];
		$receberId = $mostra['id'];
		$valorNota=0;
	
		if($mostra['link']==''){
	
			echo 'RPS : ' . $numeroRps .' '. $mostra['link'].'<br>';
 
			// DESCONTO NOTA
			if($mostra['desconto']>'0'){
			  $valorNota=$mostra['valor']-$mostra['desconto'];
			}else{
			  $valorNota=$mostra['valor'];
			}
			
			// DESCONTO DO JUROS
			if($mostra['status']=='Baixado'){
			  $valorNota = $mostra['valor'] - $mostra['juros'];
			  if($mostra['valor_nota_fiscal']<>0){
				  $valorNota = $mostra['valor_nota_fiscal']; 
			  }
			}

			$dataemissao=$mostra['emissao'];

			$valorIr=0;
			$valorInss=0;
			$valorCsll=0;
			$valorPis=0;
			$valorCofins=0;

			$deducao=0;
			$descontoCondicional=0;
			$descontoIncondicional=0;
			$issRetencao=0; 

			$contratoId = $mostra['id_contrato'];
			$contrato = mostra('contrato',"WHERE id ='$contratoId'");
			$issRetido = limpaNumero($contrato['iss_retido']);
			$issValor= $contrato['iss_valor'];

			if($issValor<'1'){
				$issValor = '5.00';
			}

			if(empty($issRetido)){
				$issRetido = 0;
			}

			$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
			$tipoColetaId = $contratoColeta['tipo_coleta'];

			$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
			$tipoResiduoId=$coleta['residuo'];
			$tipoResiduo= mostra('contrato_tipo_residuo',"WHERE id ='$tipoResiduoId'");

			$tipoResiduoNome='Servi�o Ambiental de Coleta de Res�duos :'.$tipoResiduo['nome'].'|';

			if($contrato['contrato_tipo']>7){
				//$tipoResiduoNome='Coleta de Res�duo : Biologico';
			}

			$contratoNumero =' - Contrato : '.substr($contrato['controle'],0,6).' '.$contrato['observacao_nota']."|";
 

			$descricao=$empresa['nota_descricao'];
			$descricao=str_replace('<br />', "|", nl2br($descricao));
			$descricao = preg_replace('/\s/',' ',$descricao);

			$descricao='"N�o obrigatoriedade da reten��o da CSRF de acordo com " IN SRF n�. 459 art.1�, Par�grafo 2� e IRRF cf. art.647 do RIR".|Informamos que nossos pagamentos s�o somente atrav�s de boleto banc�rio, n�o temos cobradores.||A nota tem um prazo de 48 horas para ser cancelada, qualquer reclama��o posterior, ser� dado desconto na pr�xima nota fiscal emitida.|';

			$descricao = $tipoResiduoNome . $contratoNumero . $descricao ;

			$cad['remessa_nfe']= $cArqu_CNAB;
			$cad['nota_data']= $notaEmissao;
			$cad['usuario']	=  $_SESSION['autUser']['nome'];
			update('receber',$cad,"id = '$receberId'");

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId'");
			
			// DADOS DO COBRAN�A
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
			$ufcli=$cliente['uf'];
			$inscli=$cliente['inscricao'];;
			if(empty($ufcli)){
				$ufcli='RJ';
			}
			
			$totalValorNota=$totalValorNota+$valorNota;
			
			$valorNota =converteValor($valorNota);

			echo 'Cliente : ' . $nomcli    .'<br>';
			echo 'bairro/Cidade : ' . $baicli  .' | ' . $cidcli .'<br>';
			echo 'Valor R$ : ' . $mostra['valor'];
			echo ' - Desconto R$ : ' .$mostra['desconto'];
			echo ' - Juros R$ : ' .$mostra['juros'];
			echo ' ---------------------> Total R$ : ' .$valorNota;
			echo ' ---------------------> Total das Notas R$ : ' .$totalValorNota.'<br>';
			
			
			if(empty($cidcli)){
				echo '<div class="msgError">Desculpe o CIDADE n�o informado!</div>'.'<br>';
				return;
			} 
			if(empty($ufcli)){
				echo '<div class="msgError">Desculpe o UF n�o informado!</div>'.'<br>';
				return;
			} 

				
			if($issRetido=='1'){
				echo 'Reten��o : SIM <br>';
			}


			if(!empty($cgccli)){
				if(!cnpj($cgccli)){
					$cgccli=limpaNumero($cgccli);
					echo '<div class="msgError">Desculpe o CNPJ informado � inv�lido!</div>'.'<br>';
					return;
				}
			} 

			if(empty($cepcli)){
				echo '<div class="msgError">Desculpe o CEP informado � inv�lido!</div>'.'<br>';
				return;
			} 

			$cepcli=limpaNumero($cepcli);

			echo 'CEP : ' . $cepcli;

			if (is_numeric($cepcli)) {
				//echo "numero";
			}else {
				echo "     CEP INVALIDO !!!!!!!!!!!!!!!!!!";
				echo '<div class="msgError">Cliente com CEP Invalido !</div>'.'<br>';
				return;
			}

			if(!empty($cgccli)){ 
				$cgccli=limpaNumero($cgccli);
				$cgccli=str_pad($cgccli,14,'0',STR_PAD_LEFT);
				$tipoInscricao='2';
			}

			if(!empty($cpfcli)) { // 01 - CPF 02 - CNPJ  
				 $cpfcli=limpaNumero($cpfcli);
				 $cgccli=str_pad($cpfcli,14,'0',STR_PAD_LEFT);
				 $tipoInscricao='1';
			}

			$cepcli=limpaNumero($cepcli);

			echo ' | CNPJ/CPF : ' . $cgccli.' | '.$tipoInscricao.'<br>';

			if(empty($cgccli)){
				echo '<div class="msgError">cliente sem CNPJ/CPF !</div>'.'<br>';
				return;
			} 

			$notaMunicipio='Rio de Janeiro';
			if($notaMunicipio=='Rio de Janeiro'){   // RIO DE JANEIRO - atualizado em 14/09/2017

			if(empty($cHeader)){
				$nReg   = 1;
				$nValor = 0;
				$cHeader.='10' ;// 1 Tipo de registro  1 2 2 Num�rico S Deve ser preenchido com valor �10�, cabe�alho. 
				$cHeader.='003' ;// 2 Versao do Arquivo 3 5 3 Num�rico S A versao atual � a 003. 
				$cHeader.='2' ;  // 3 Identifica�ao CPF ou CNPJ do Contribuinte 6 6 1 Num�rico S  1 - CPF OU 2 - CNPJ.
				$cHeader.=str_pad($cnpjEmpresa,14,'0', STR_PAD_LEFT);// 4 CPF ou CNPJ do Contribuinte 7 20 14 Num�rico 
				$cHeader.=str_pad($inscricaoEmpresa,15,'0', STR_PAD_LEFT);  // 5 Inscri�ao Municipal do Contribuinte 
				$cHeader.=limpaNumero($notaEmissao);  // 6 Data de In�cio do Per�odo Arquivo 36 43 8 
				$cHeader.=limpaNumero($notaEmissao);  // 7 Data de Fim do Per�odo no Arquivo 44 51 8 AAAAMMDD S
				$cHeader .= chr(13).chr(10); //essa � a quebra de linha
			}


	$nReg = $nReg+1;

	// REGISTRO DETALHE (OBRIGATORIO)
	$cDetail = $cDetail;
	$cDetail .= '20';// 1 Tipo do registro Deve ser preenchido com valor �20�, indicando  linha de detalhe.
	$cDetail .= '0';// 2 Tipo do RPS - Tipo do RPS 01 posi�ao. 0 � (RPS); 
	$cDetail .= espacoBranco(5); 	// 3 S�rie do RPS  Informe a S�rie do RPS com 05 posi�oes
	$cDetail .= str_pad($numeroRps,15,'0', STR_PAD_LEFT);	// 4 N�mero do RPS o N�mero do RPS com 15 posi�oes.
	$cDetail .= limpaNumero($notaEmissao); // 5 Data de Emissao do RPS 24 31 8 AAAAMMDD S 
	$cDetail .=  '1';	// 6 Status do RPS - Status do RPS: 1 � Normal; 2 � Cancelado.

	$cDetail .=  $tipoInscricao; // 7 Identifica�ao de CPF ou CNPJ	33 33 1 Num�rico 
	$cDetail .=  $cgccli;// 8 CPF ou CNPJ do Tomador de Servi�os	34 47 14 Num�rico 
	$cDetail .=  espacoBranco(15); // 9 Inscri�ao Municipal do Tomador 	48 62 15 Texto N 
	$cDetail .=  espacoBranco(15);	// 10 Inscri�ao Estadual 63 77 15 	Num�rico N 

	$cDetail .=  str_pad($nomcli,115,' ', STR_PAD_RIGHT);  // 11 Nome/Razao Social 78 192 115 Texto 
	$cDetail .=  espacoBranco(3);                          // 12 Tipo do Endere�o (Rua, Av, ...) 193 195 3 Texto
	$cDetail .=  str_pad($endcli,125,' ', STR_PAD_RIGHT);	// 13 Endere�o  196 320 125 Texto   
	$cDetail .=  espacoBranco(10);     						// 14 N�mero do Endere�o 321 330 10 Texto N N�mero
	$cDetail .=  espacoBranco(60);       					// 15 Complemento do Endere�o 331 390 60 Texto N 
	$cDetail .=  str_pad($baicli,72,' ', STR_PAD_RIGHT);	// 16 Bairro 391 462 72 Texto 
	$cDetail .=  str_pad($cidcli,50,' ', STR_PAD_RIGHT);	// 17 Cidade 463 512 50 Texto
	$cDetail .=  $ufcli;									// 18 UF 513 514 2 	Texto 
	$cDetail .=  str_pad($cepcli,8,' ', STR_PAD_RIGHT);		// 19 CEP 515 522 8 Num�rico 
	$cDetail .=  espacoBranco(11);  						// 20 Telefone de Contato 523 533 11 Texto
	$cDetail .=  espacoBranco(80); 				           	// 21 E-mail 534 613 80 Texto 														

	// Tipo de Tributa�ao de Servi�os 
	$cDetail .= '01' ;	// 22 Tipo de Tributa�ao de Servi�os 614 615 2 Num�rico S Tributa�ao de Servi�os da Nota:
						// 01 - Tributa�ao no Munic�pio;
						// 02 - Tributa�ao Fora do Munic�pio;
						// 03 - Opera�ao Isenta;
						// 04 - Opera�ao Imune;
						// 05 - Opera�ao Suspensa por Decisao Judicial;
						// 06 - Opera�ao Suspensa por Decisao
	$cDetail .=  str_pad($cidcli,50,' ', STR_PAD_RIGHT);// 23 Cidade de Presta�ao do Servi�o 616 665 50 Texto 
	$cDetail .=   $ufcli;	// 24 UF de Presta�ao do Servi�o 	666 667 2 	Texto S UF onde o Servi�o foi prestado. 

	// Regime Especial de Tributa�ao
	$cDetail .=  '00'; //  25 Regime Especial de Tributa�ao 	668 669 2 Num�rico S
							// 00 � Nenhum;
							// 01 � Microempresa Municipal;
							// 02 � Estimativa;
							// 03 � Sociedade de Profissionais;
							// 04 � Cooperativa;
							// 05 � Microempreendedor Individual (MEI)
	//Op�ao Pelo Simples
	$cDetail .=  $optanteSimples; 	// 26 Op�ao Pelo Simples 670 670 1 Num�rico Prestador de														// 0 - Nao-Optante pelo Simples Nacional;
									// 1 - Optante pelo Simples Nacional

	$cDetail .=  '0';      // 27 Incentivo Cultural 671 671 1 Num�rico S
									//  0 � Nao;
									//  1 � Sim.
	// C�digo do Servi�o Federal 
	$cDetail .= $CodigoServicoFederal;  // 28 C�digo do Servi�o Federal 672 675 4 Texto 
										// "07.20" ser� "0720" ou " 720"
	$cDetail .=  espacoBranco(6);		// 29.A Reservado 	676 681 6 Texto N Preencher com brancos.
	$cDetail .=  espacoZero(5);			// 29.B C�digo do Pa�s 	682 686 5 Num�rico N/S C�digo do Pa�s de exporta�ao 
	$cDetail .=  espacoZero(3);			// 29.C C�digo do N Benef�cio 687 689 3 Num�rico N Nos casos em que o Tipo 

	//C�digo do Servi�o Municipal
	$cDetail .= $CodigoServicoMunicipal;// 29.D C�digo do Servi�o Municipal 690 695 6 Texto S C�digo do Servi�o da lista Municipal. O campo deve ser preenchido sem formata�ao

	// Al�quota  	
	$cDetail .= str_pad(limpaValor($issValor),5,'0',STR_PAD_LEFT);  // 30 Al�quota 696 700 5 Num�rico  Valor da Al�quota, incluindo duas casas


	// valor da nota
	$cDetail .= str_pad(limpaValor($valorNota),15,'0',STR_PAD_LEFT);

	// dedu�ao e desconto

	$cDetail .=str_pad(limpaValor($deducao),15,'0', STR_PAD_LEFT);     
	$cDetail .=str_pad(limpaValor($descontoCondicional),15,'0', STR_PAD_LEFT);    
	$cDetail .=str_pad(limpaValor($descontoIncondicional),15,'0', STR_PAD_LEFT); 

	//reten�oes
	$cDetail .=str_pad(limpaValor($valorCofins),15,'0', STR_PAD_LEFT);     
	$cDetail .=str_pad(limpaValor($valorCsll),15,'0', STR_PAD_LEFT);     
	$cDetail .=str_pad(limpaValor($valorInss),15,'0', STR_PAD_LEFT);     
	$cDetail .=str_pad(limpaValor($valorIr),15,'0', STR_PAD_LEFT);     
	$cDetail .=str_pad(limpaValor($valorPis),15,'0', STR_PAD_LEFT);     


	// outras reten�oes e valor de ISS
	$cDetail .=espacoZero(15);				// 40 Valor de Outras Reten�oes Federais 836 850 15 Num�rico N 
	$cDetail .=espacoZero(15);	         	// 41 Valor do ISS 	851 865 15 Num�rico N V

	 //ISS Retido 
	$cDetail .=  $issRetido;	            // 42 ISS Retido 866 866 1 Num�rico S Reten�ao do ISS:
											// 0 - Nota Fiscal sem ISS Retido;  1 - ISS Retido.

	$cDetail .=  limpaNumero($notaEmissao); // 43 Data de Competencia 867 874 8 AAAAMMDD  emissao do RPS 
	$cDetail .=  espacoBranco(15);			// 44 C�digo da Obra 	875 889 15 Texto N/S 
	$cDetail .=  espacoBranco(15);          // 45 Anota�ao de Responsabilidade T�cnica 	890 904 15 Texto N
	$cDetail .=  espacoBranco(5);			// 46 S�rie do RPS Substituido 905 909 5  Texto N S�rie do RPS 
	$cDetail .=  espacoZero(15);			// 47 N�mero do RPS Substituido 910 924 15 Num�rico N
	$cDetail .=  espacoBranco(30);			// 48 Reservado 925 954 30 Texto N Preencher com brancos

	$cDetail .=  $descricao;   				// 49 Discrimina�ao dos Servi�os 	955 955 + (N-1)

	$cDetail .= chr(13).chr(10); //essa � a quebra de linha 	


	//$totalValorNota = $totalValorNota + $valorNota;
	$TotalDeducao = $TotalDeducao + $deducao;
	$totalDescontoCondicional = $totalDescontoCondicional + $descontoCondicional;
	$TotalDescontoIncondicional = $TotalDescontoIncondicional + $descontoIncondicional;
			
 }
			
}

endforeach;
	
	$totalValorNota = converteValor($totalValorNota); 
	$TotalDeducao = converteValor($TotalDeducao); 
	$totalDescontoCondicional = converteValor($totalDescontoCondicional); 
	$TotalDescontoIncondicional = converteValor($TotalDescontoIncondicional); 

	if($notaMunicipio=='Rio de Janeiro'){   // RIO DE JANEIRO - atualizado em 23/05/2017
		$cTrailler .='90';	// 1 Tipo de registro 1 2 2 Num�rico S Deve ser preenchido com valor �90�

		$nReg = $nReg-1;

		$cTrailler .= str_pad( $nReg, 8 , '0', STR_PAD_LEFT);

		//valor de linhas de detalhe (Tipo de registro 20 + 21 + 30 + 50 + 50) 
		$cTrailler .=str_pad(limpaValor($totalValorNota),15,'0', STR_PAD_LEFT);     
		$cTrailler .=str_pad(limpaValor($TotalDeducao),15,'0', STR_PAD_LEFT);     
		$cTrailler .=str_pad(limpaValor($TotalDescontoCondicional),15,'0', STR_PAD_LEFT);     
		$cTrailler .=str_pad(limpaValor($TotalDescontoIncondicional),15,'0', STR_PAD_LEFT);     

		$cTrailler .= chr(13).chr(10); //essa � a quebra de linha

	}
    
    echo 'Valor Total : ' .$totalValorNota .'<br>';
	echo 'Total Deducao : ' .$TotalDeducao .'<br>';
	echo 'Total Desconto Condicional: ' .$totalDescontoCondicional .'<br>';
	echo 'Total Desconto Incondicional : ' .$TotalDescontoIncondicional .'<br>';
						
	$conteudo=$cHeader.$cDetail.$cTrailler;

	if (!$handle = fopen('remessa/'.$cArqu_CNAB, 'w+')){
		 echo "N�o foi poss�vel abrir o arquivo ($cArqu_CNAB)";
	}
	// Escreve $conteudo no nosso arquivo aberto.
	if (fwrite($handle, "$conteudo") === FALSE) {
		echo "N�o foi poss�vel escrever no arquivo ($cArqu_CNAB)";
	}
	
	fclose($handle);
	// Escreve $conteudo no nosso arquivo aberto.
	$arquivo = fopen(URL.'/admin/remessa'.$cArqu_CNAB,'r');
	
	if ($arquivo == false) die('O arquivo nao existe.');

?> 

 
		</div><!--/col-md-12 scrool-->   
	 </div><!-- /.box-body table-responsive data-spy='scroll -->
   </div><!-- /.box-body table-responsive -->
 	  
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->
 
  <section class="content">
       
         <div class="box box-default">
            	<div class="box-header with-border">
                  <h3 class="box-title"> Arquivo de remessa gerado com sucesso !</h3>
       	  </div><!-- /.box-header -->
          
          <div class="box-body table-responsive">

          <p>
       <?php
		echo "Arquivo : " .$cArqu_CNAB .'<BR><BR>';
		$pasta = './remessa/';
			if(is_dir($pasta)){
			  foreach(glob("$pasta*.REM") as $arquivo){
				echo substr($arquivo,10).' -> '."<a href=".$arquivo." download> Clique para Download</a><br />";
			   }
			 }else{
		   }
	 	}
	  ?>
     </p>
      <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
	</div><!-- /.box-body table-responsive -->
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->

<?php

function ddmmyy($dataformatar){
         $d = explode ("-", $dataformatar);//tira a barra
          $dataformatar = $d[2].$d[1].substr($d[0],2,2);//separa as datas $d[2] = ano $d[1] = mes etc...
          return $dataformatar;
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