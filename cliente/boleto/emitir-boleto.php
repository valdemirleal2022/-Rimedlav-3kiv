<head>
    <meta charset="iso-8859-1">
</head>

<?php 

	if(!empty($_GET['boletoId'])){
    	$boletoId = $_GET['boletoId'];
	}
	   
	$boleto = mostra('receber',"WHERE id = '$boletoId'");
	if(!$boleto){
		echo '<div class="msgError">Boleto Não Encontrado !</div> <br />';
		header('Location: painel.php?execute=suporte/error');	
	}

	if($boleto['protesto']==1){
		echo '<div class="msgError">Boleto Protestado!</div> <br />';
		header('Location: painel.php?execute=suporte/error');	
	}

	$clienteId = $boleto['id_cliente'];
	$contratoId = $boleto['id_contrato'];
	$cliente = mostra('cliente',"WHERE id = '$clienteId'");
	
	// DADOS DO COBRANÇA
	$clienteCobranca = read('cliente_cobranca',"WHERE id_cliente = '$clienteId'");
	if($clienteCobranca ){
		foreach($clienteCobranca as $cliente);
	}

	// Recupera boleto 
    $id_boleto=$boleto['id'];
	$id_banco=$boleto['banco'];
	$emissao=$boleto['emissao'];
	$id_cliente=$boleto['id_cliente'];
	$vencimento = $boleto['vencimento'];
	$vencimento_original = $boleto['vencimento'];
	$emissao = implode("/",array_reverse(explode("-",$emissao)));
	$valor_normal = $boleto["valor"]+$boleto["juros"]-$boleto["desconto"];
	$valor =$boleto["valor"]+$boleto["juros"]-$boleto["desconto"];
	
	$banco = mostra('banco',"WHERE id = '$id_banco'");

	// boleto vencido - calculando juros	
	$valor_juros_multa=0;
	$percentual_juros=$banco["juros"];
	$percentual_multa=$banco["multa"];
	$empresaId=$banco["empresa"];
 

 	$vencimento = implode("/",array_reverse(explode("-",$vencimento)));

	$imprimir='Boleto Gerado pelo Suporte';

	if(!isset($_SESSION['autUser']['id']) ){

		$imprimir='Boleto Gerado pelo Cliente';
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['imprimir']= date('Y/m/d H:i:s');
		update('receber',$cad,"id = '$boletoId'");
		
		if(empty($receber['imprimir'])){
			// INTERAÇÃO
			$interacao='Boleto gerado pelo cliente n. '.$boletoId. ' IP '.$_SERVER["REMOTE_ADDR"];
			interacao($interacao,$contratoId);
		}
		
	}else{
		
		$imprimir='Boleto Gerado pela Clean';
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['imprimir']= date('Y/m/d H:i:s');
		update('receber',$cad,"id = '$boletoId'");
		
		if(empty($receber['imprimir'])){
			// INTERAÇÃO
			$interacao='Boleto gerado pelo Clean n. '.$boletoId. ' IP '.$_SERVER["REMOTE_ADDR"];
			interacao($interacao,$contratoId);
		}
		
	}	

	 
	// Recupera cliente
	$nome = $cliente['nome'];
	$endereco = $cliente['endereco'] . " ". $cliente['numero'] . " ". $cliente['complemento'];
	$bairro = $cliente['bairro'];
	$cep = $cliente['cep'];
	$cidade = $cliente['cidade'];
	$uf = $cliente['uf'];
	$cnpj = $cliente['cnpj'].$cliente['cpf'];
	$inscricao = $cliente['inscricao'];




	// EMPRESA
	$empresa = mostra('empresa',"where id='$empresaId'");
    $cedenteNome = $empresa["nome"];
    $cedenteCNPJ = substr($empresa["cnpj"],0,20);
	$cedenteEndereco = $empresa["endereco"].' '.$empresa["bairro"].' '.$empresa["cidade"].' '.$empresa["cep"];
	$cedenteTelefone = $empresa["telefone"];
	
	$ins_juros_dia = ($valor_normal*$percentual_juros)/100;
 	$ins_multa = ($percentual_multa * $valor_normal) / 100; 

	$instrucoes1='Apos vencimento multa de R$ '.converteValor($ins_multa).' 
	e Juros Diaria de R$ '.converteValor($ins_juros_dia).'' ;

	$instrucoes2='Após 15 dias em atraso, o boleto entrará automaticamente em Negativação Expressa.';
	$instrucoes3='Após 30 dias em atraso, o boleto será automaticamente Protestado.';

	if($ins_multa==0){
		$instrucoes1='Após Vencimento Juros Diaria de R$ '.converteValor($ins_juros_dia).'' ;
	}
   
    $instrucoes4 = '';
    $instrucoes5 = '';
	
	// INÍCIO DA ÁREA DE CONFIGURAÇÃO
    $dadosboleto["codigo_banco"] = $banco["codigo_banco"];  
	$dadosboleto["digito_banco"] = $banco["digito_banco"];
    $dadosboleto["agencia"] = $banco["agencia"]; // 4 posições
    $dadosboleto["conta"] = $banco["conta"];  // 5 posições sem dígito
	$dadosboleto["conta_dv"] = $banco["conta_digito"];  // digito apenas para boleto
    $dadosboleto["carteira"] = $banco["carteira"]; // A sem registro é 175 para o Itaú
	$dadosboleto["codigo_cedente"] = $banco["codigo_cedente"]; // Código do Cliente (PSK) (Somente 7 digitos)
    $dadosboleto["nosso_numero"] = $id_boleto; // Número de controle do Emissor (pode usar qq número de até 8 digitos);
    $emissao = $emissao; // Data de emissão do boleto
    $dadosboleto["data_vencimento"] = $vencimento; // Data no formato dd/mm/yyyy
	$dadosboleto["valor_boleto"]=number_format($valor, 2, '', '.');// Colocar PONTO no formato REAIS.CENTAVOS (ex: 666.01)
	
	if($dadosboleto["codigo_banco"]=='341'){ //  ITAÚ
		include("inc/funcoes_itau.php"); 
		$banco["nome"]='BANCO ITAU S.A.';
		
	 }elseif($dadosboleto["codigo_banco"]=='104'){ // CAIXA ECONOMICA FEDERAL
		
		include("inc/funcoes_cef_sigcb.php");
		$banco["nome"]='CAIXA ECONOMICA';
		$agencia_codigo=$banco["agencia"].'/'.$dadosboleto["codigo_cedente"];
		
	 }elseif($dadosboleto["codigo_banco"]=='001'){ // BANCO DO BRASIL
	 	$dadosboleto["carteira"] = "18";
		include("inc/funcoes_bb.php");
		
	 }elseif($dadosboleto["codigo_banco"]=='237'){ // BRADESCO
		include("inc/funcoes_bradesco.php");
		$agencia_codigo=$banco["agencia"].'/'.$dadosboleto["codigo_cedente"];
		 
	 }elseif($dadosboleto["codigo_banco"]=='033'){  // SANTANDER
		$dadosboleto["carteira"] = "102";  // Cobrança Simples - SEM Registro
		include("inc/funcoes_santander.php");
		 
	}
	

?>

     
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
    <title>Clean Ambiental - Imprimir Boleta</title>
    <link rel="stylesheet" type="text/css" href="boleto/css/estilo_boleto.css">
    <![if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]>
</head>

<body onload="javascript:window.print();">
 
  
    <!-- Menu de emissão do boleto -->
    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
         
          </tr>
   	</table>
    <!-- Recibo do Sacado -->
    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td class="logo_fs"><img src="../site/images/header-logo.png"></td>
          <td width="439" class="titulo">Boleto para Pagamento n&ordm; | <strong>&nbsp;<? echo $id_boleto; ?></strong></td>
          	
          </tr>
    </table>
        
      <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="157" class="logo_banco"><?PHP echo $banco["nome"]; ?></td>
            <td width="55" class="num_banco"><?PHP echo $dadosboleto["codigo_banco"] . "-" . $dadosboleto["digito_banco"]; ?></td>
            <td width="492" class="linha_digitavel"><?PHP echo $dadosboleto["linha_digitavel"]; ?></td>
          </tr>
</table>
        
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2" class="titulo_dir">Benefici&aacute;rio</td>
            <td colspan="2" class="titulo_dir">CNPJ/CPF</td>
            <td colspan="1" class="titulo_dir">Sacado/Avalista</td>
            <td width="146" class="titulo_inf">Vencimento</td>
          </tr>
           <tr>
            <td colspan="2" class="linha_dir"><?PHP echo $cedenteNome;  ?></td>
            <td colspan="2" class="linha_dir"><?PHP echo $cedenteCNPJ; ?></td>
            <td class="linha_dir">&nbsp;</td>
            <td class="linha_inf">&nbsp;&nbsp;&nbsp;<?PHP echo $vencimento; ?></td>
          </tr>
          <tr>
            <td colspan="5" class="titulo_dir">Endere&ccedil;o Benefici&aacute;rio </td>
          </tr>
          <tr>
            <td colspan="5" class="linha_dir"><?PHP echo $cedenteEndereco; ?></td>
            <td class="linha_inf"> </td>
          </tr>
          <tr>
            <td width="109" class="titulo_dir">Numero do Documento</td>
            <td width="129" class="titulo_dir">Carteira</td>
            <td width="113" class="titulo_dir">Especie</td>
            <td width="84" class="titulo_dir">Quantidade</td>
            <td width="119" class="titulo_dir">Valor</td>
            <td class="titulo_inf">Agencia/C&oacute;digo do Beneficiario</td>
          </tr>
          <tr>
            <td class="linha_dir"><?PHP echo $id_boleto ?></td>
            <td class="linha_dir"><? echo $carteira ; ?></td>
            <td class="linha_dir"><?PHP echo "DM"; ?></td>
            <td class="linha_dir">&nbsp;</td>
            <td class="linha_dir">&nbsp;</td>
            <td class="linha_inf"><?PHP echo $agencia_codigo; ?></td>
          </tr>
          <tr>
            <td class="titulo_dir">Data do Documento </td>
            <td class="titulo_dir">N&uacute;mero do Documento</td>
            <td class="titulo_dir">Esp&eacute;cie  do Documento</td>
            <td class="titulo_dir">Aceite</td>
            <td class="titulo_dir">Data do Processamento</td>
            <td class="titulo_inf">(=) Valor do Documento </td>
          </tr>
  <tr>
            <td class="linha_dir"><?PHP echo date('d/m/Y'); ?></td>
            <td class="linha_dir">&nbsp;</td>
            <td class="linha_dir"><?PHP echo $id_boleto; ?></td>
            <td class="linha_dir">&nbsp;</td>
    		<td class="linha_dir"><?PHP echo date('d/m/Y'); ?></td>
            <td class="valor"><?PHP echo converteValor($valor_normal); ?></td>
          </tr>
  <tr>
            <td colspan="6"><div align="right"><span class="autenticacao">Autentica&ccedil;&atilde;o Mec&acirc;nica</span> </div></td>
          </tr>
  <tr>
            <td colspan="6">&nbsp;</td>
          </tr>		
          <tr>
            <td colspan="6">&nbsp;</td>
          </tr>	
          <tr>
            <td colspan="6">&nbsp;</td>
          </tr>	
          <tr>
            <td colspan="6"><img src="boleto/images/corte.gif" width="700" height="12" /></td>
          </tr>	
          <tr>
            <td colspan="6">&nbsp;</td>
          </tr>	
          <tr>
            <td colspan="6">&nbsp;</td>
          </tr>						
        </table>
        
        <!-- Ficha de Compensação -->
        <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="157" class="logo_banco"><?PHP echo $banco["nome"]; ?></td>
            <td width="55" class="num_banco"><?PHP echo $dadosboleto["codigo_banco"] . "-" . $dadosboleto["digito_banco"]; ?></td>
            <td width="492" class="linha_digitavel"><?PHP echo $dadosboleto["linha_digitavel"]; ?></td>
          </tr>
        </table>
        
        <!-- Ficha de compensação -->
        <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="5" class="titulo_dir">Local de Pagamento</td>
            <td width="146" class="titulo_inf">Vencimento</td>
          </tr>
           <tr>
            <td colspan="5" class="linha_dir">PAGAVEL AT&Eacute; O VENCIMENTO EM QUALQUER BANCO</td>
            <td class="linha_inf"><?PHP echo $vencimento; ?></td>
          </tr>
          <tr>
            <td colspan="5" class="titulo_dir">Cedente</td>
            <td class="titulo_inf">Ag&ecirc;ncia/C&oacute;digo Cedente</td>
          </tr>
          <tr>
            <td colspan="5" class="linha_dir"><?PHP echo $cedenteNome; ?></td>
            <td class="linha_inf"><?PHP echo $agencia_codigo; ?></td>
          </tr>
          <tr>
            <td width="109" class="titulo_dir">Data do Documento </td>
            <td width="129" class="titulo_dir">Numero do Documento </td>
            <td width="113" class="titulo_dir">Esp&eacute;cie Documento </td>
            <td width="84" class="titulo_dir">Aceite</td>
            <td width="119" class="titulo_dir">Data do Processamento</td>
            <td class="titulo_inf">Nosso N&uacute;mero</td>
          </tr>
          <tr>
            <td class="linha_dir"><?PHP echo $emissao; ?></td>
            <td class="linha_dir"><? echo $id_boleto ; ?></td>
            <td class="linha_dir"><?PHP echo "DM"; ?></td>
            <td class="linha_dir"><?PHP echo "N"; ?></td>
            <td class="linha_dir"><?PHP echo date('d/m/Y'); ?></td>
            <td class="linha_inf"><?PHP echo $nossonumero; ?></td>
          </tr>
          <tr>
            <td class="titulo_dir">Uso do Banco </td>
            <td class="titulo_dir">Carteira</td>
            <td class="titulo_dir">Esp&eacute;cie</td>
            <td class="titulo_dir">Quantidade</td>
            <td class="titulo_dir">Valor</td>
            <td class="titulo_inf">(=) Valor do Documento </td>
          </tr>
          <tr>
            <td class="linha_dir">&nbsp;</td>
            <td class="linha_dir"><?PHP echo $carteira; ?></td>
            <td class="linha_dir"><?PHP echo 'REAL'; ?></td>
            <td class="linha_dir">&nbsp;</td>
            <td class="linha_dir">&nbsp;</td>
            <td class="valor"><?PHP echo converteValor($valor_normal); ?></td>
          </tr>
          <tr>
            <td colspan="5" class="titulo_dir">Instru&ccedil;&otilde;es de responsabilidade do BENEFICI&Aacute;RIO. Qualquer d&uacute;vida sobre este boleto contate o BENEFICI&Aacute;RIO </td>
            <td class="titulo_inf">(-) Desconto/Abatimento </td>
          </tr>
          <tr>
          
           <td colspan="5" rowspan="9" valign="top" class="linha_dir">
                <p>&nbsp;</p>
                <p> <?PHP echo $instrucoes1; ?></p>
                <p> <?PHP echo $instrucoes2; ?></p>
                <p> <?PHP echo $instrucoes3; ?></p>
                
           </td>
                
            <td class="linha_inf">&nbsp;</td>
          </tr>
          <tr>
            <td class="titulo_inf">(-) Outras Dedu&ccedil;&otilde;es </td>
          </tr>
          <tr>
            <td class="linha_inf">&nbsp;</td>
          </tr>
          <tr>
            <td class="titulo_inf">(+) Multa </td>
          </tr>
          
          <tr>
          	 <?PHP if($multa==0){ ?>
             	 <td class="linha_inf">&nbsp;</td>
             <?PHP } ?>
             <?PHP if($multa<>0){ ?>
             	 <td class="valor"><?PHP echo converteValor($multa); ?></td>
             <?PHP } ?>
          </tr>
          
          <tr>
            <td class="titulo_inf">(+) Juros </td>
          </tr>
          
          <tr>
              <?PHP if($juros==0){ ?>
             	 <td class="linha_inf">&nbsp;</td>
             <?PHP } ?>
             <?PHP if($juros<>0){ ?>
             	 <td class="valor"><?PHP echo converteValor($juros); ?></td>
             <?PHP } ?>
          </tr>
          <tr>
            <td class="titulo_inf">(=) Valor Cobrado </td>
          </tr>
          <tr>
 			 <?PHP if($valor_juros_multa==0){ ?>
             	 <td class="linha_inf">&nbsp;</td>
             <?PHP } ?>
             <?PHP if($valor_juros_multa<>0){ ?>
             	 <td class="valor"><?PHP echo converteValor($valor_juros_multa); ?></td>
             <?PHP } ?>
           </tr>
           
          <tr>
            <td colspan="6" class="titulo_inf">Sacado</td>
          </tr>
          <tr>
           <td colspan="6" class="sacado">
                 &nbsp;   &nbsp;    &nbsp;  &nbsp;      &nbsp;  &nbsp;       &nbsp;                         &nbsp; &nbsp; &nbsp; <?PHP echo $nome; ?> &nbsp; &nbsp; &nbsp; CNPJ/CPF&nbsp; &nbsp; &nbsp; <?PHP echo $cnpj; ?><br />
                &nbsp;   &nbsp;    &nbsp;  &nbsp;      &nbsp;  &nbsp;       &nbsp;                         &nbsp; &nbsp; &nbsp <?PHP echo $endereco; ?><br />
                &nbsp;   &nbsp;    &nbsp;  &nbsp;      &nbsp;  &nbsp;       &nbsp;                         &nbsp; &nbsp; &nbsp<?PHP echo $cep . " " . $bairro . " " . $cidade . " ". $uf; ?></td>
          </tr>
          <tr>
            <td colspan="5" class="avalista">Sacador/Avalista</td>
            <td class="avalista"><div align="right">C&oacute;digo de Baixa </div></td>
          </tr>
          <tr>
            <td colspan="6"><div align="right"><strong>Ficha de Compensa&ccedil;&atilde;o -</strong> <span class="autenticacao">Autentica&ccedil;&atilde;o Mec&acirc;nica</span> </div></td>
          </tr>
            
          <tr> 
            <td colspan="6"><img src="boleto/codigodebarra.php?valor=<? echo $dadosboleto["codigo_barras"]; ?>"></td>
          </tr>	
           <tr> 
            <td colspan="6"><? echo $dadosboleto["codigo_barras"]; ?></td>
          </tr>	
			
		  <tr> 
			 
           <td colspan="6"><div align="right"><span class="autenticacao"><? echo $imprimir; ?></span></td>
          </tr>	
          	
    </table>
</body>

<?php 
	ob_end_flush();
?>
</html>