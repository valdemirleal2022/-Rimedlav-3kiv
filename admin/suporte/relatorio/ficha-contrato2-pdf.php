<?php


if(function_exists(ProtUser)){
	if(!ProtUser($_SESSION['autUser']['id'])){
		header('Location: painel.php?execute=suporte/403');	
	}	
}
		
if(isset($_SESSION['dataInicio'])){
	$data1 = $_SESSION['dataInicio'];
}
if(isset($_SESSION['dataFinal'])){
	$data2 = $_SESSION['dataFinal'];
}
if(isset($_SESSION[ 'rotaColeta' ])){
	$rotaId =$_SESSION[ 'rotaColeta' ];
	
}

$dia_semana = diaSemana($dataroteiro);
$numero_semana = numeroSemana($dataroteiro);

$leitura = read( 'contrato', "WHERE id AND status='5'" );
	

require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','B',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','B',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Ficha do Contrato";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
	  $this->Line(10, 20, 200, 20); // insere linha divisória
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','B',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf=new RELATORIO();
$pdf->AliasNbPages();

foreach($leitura as $mostra):
	
	$testeRota=0;

	if($mostra['segunda_rota1']==$rotaId){
		$testeRota=1;
	}
	if($mostra['terca_rota1']==$rotaId){
		$testeRota=1;
	}
	if($mostra['terca_rota1']==$rotaId){
		$testeRota=1;
	}
	if($mostra['quarta_rota1']==$rotaId){
		$testeRota=1;
	}
	if($mostra['quinta_rota1']==$rotaId){
		$testeRota=1;
	}
	if($mostra['sexta_rota1']==$rotaId){
		$testeRota=1;
	}
	if($mostra['sabado_rota1']==$rotaId){
		$testeRota=1;
	}

if($testeRota==1){
	

	$pdf->AddPage();

	$contratoId = $mostra['id'];

	$contrato = mostra('contrato',"WHERE id='$contratoId'");
	$pdf->SetFont('Arial','B',10);

	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->Cell(22, 1, 'Cliente');
	$pdf->SetX(110);
	$pdf->Cell(22, 1, 'Contrato Id :');
	$pdf->SetX(135);
	$pdf->Cell(22, 1, $contrato['id']);

	$pdf->SetX(150);
	$pdf->Cell(22, 1, 'Controle :');
	$pdf->SetX(168);
	$pdf->Cell(22, 1, $contrato['controle']);

	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Cliente :');
	$pdf->SetX(27);
	$pdf->Cell(22, 1, $cliente['nome']);

	$statusId=$contrato['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId'");
	$pdf->SetX(150);
	$pdf->Cell(22, 1, 'Status :');
	$pdf->SetX(165);
	$pdf->Cell(22, 1, $status['nome']);

	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Email :');
	$pdf->SetX(27);
	$pdf->Cell(22, 1, $cliente['email']);

	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'CNPJ/CPF :');
	$pdf->SetX(35);
	$pdf->Cell(22, 1, $cliente['cnpj'].$cliente['cpf']);

	$pdf->SetX(110);
	$pdf->Cell(22, 1, 'Inscrição :');
	$pdf->SetX(130);
	$pdf->Cell(22, 1, $cliente['inscricao']);

	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Telefone :');
	$pdf->SetX(30);
	$pdf->Cell(22, 1, $cliente['telefone']);

	$pdf->SetX(80);
	$pdf->Cell(22, 1, 'Celular :');
	$pdf->SetX(100);
	$pdf->Cell(22, 1, $cliente['celular']);

	$pdf->SetX(140);
	$pdf->Cell(22, 1, 'Contato :');
	$pdf->SetX(160);
	$pdf->Cell(22, 1, $cliente['contato']);

	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Cep :');
	$pdf->SetX(22);
	$pdf->Cell(22, 1, $cliente['cep']);
	
	$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
	
	$pdf->SetX(45);
	$pdf->Cell(22, 1, 'Endereço :');
	$pdf->SetX(65);
	$pdf->Cell(22, 1, $endereco);

	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Bairro :');
	$pdf->SetX(25);
	$pdf->Cell(22, 1, $cliente['bairro']);

	$pdf->SetX(80);
	$pdf->Cell(22, 1, 'Cidade :');
	$pdf->SetX(100);
	$pdf->Cell(22, 1, $cliente['cidade']);

	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Referência :');
	$pdf->SetX(34);
	$pdf->Cell(22, 1, $cliente['referencia']);

	$pdf->ln(8);
	$pdf->Cell(22, 1, 'Tipo de Contrato :');
	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(5);

	$tipoContratoId=$contrato['contrato_tipo'];
	$tipoContrato = mostra('contrato_tipo',"WHERE id ='$tipoContratoId'");
	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Tipo :');
	$pdf->SetX(23);
	$pdf->Cell(22, 1, $tipoContrato['nome']);

	$atendenteId=$contrato['atendente'];
	$atendente = mostra('contrato_atendente',"WHERE id ='$atendenteId'");
	$pdf->SetX(85);
	$pdf->Cell(22, 1, 'Atendente :');
	$pdf->SetX(108);
	$pdf->Cell(22, 1, $atendente['nome']);

	$indicacaoId=$contrato['indicacao'];
	$indicacao = mostra('contrato_indicacao',"WHERE id ='$indicacaoId'");
	$pdf->SetX(130);
	$pdf->Cell(22, 1, 'Indicação :');
	$pdf->SetX(150);
	$pdf->Cell(22, 1, $indicacao['nome']);

	$pdf->ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Aprovação :');
	$pdf->SetX(34);
	$pdf->Cell(22, 1, converteData($contrato['aprovacao']));

	$pdf->SetX(80);
	$pdf->Cell(22, 1, 'Início :');
	$pdf->SetX(96);
	$pdf->Cell(22, 1,  converteData($contrato['inicio']));

	$pdf->SetX(145);
	$pdf->Cell(22, 1, 'Valor Mensal :');
	$pdf->SetX(180);
	$pdf->Cell(22, 1, converteValor($contrato['valor_mensal']));

	// Consultor/Comissão

	$pdf->ln(8);
	$pdf->Cell(22, 1, 'Consultor/Comissão :');
	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(5);

	$consultorId=$contrato['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Consultor :');
	$pdf->SetX(30);
	$pdf->Cell(22, 1, $consultor['nome']);

	$pdf->SetX(63);
	$pdf->Cell(22, 1, 'Fechamento :');
	$pdf->SetX(90);
	$pdf->Cell(22, 1,  $contrato['comissao_fechamento']);

	$pdf->SetX(120);
	$pdf->Cell(22, 1, 'Manutenção :');
	$pdf->SetX(145);
	$pdf->Cell(22, 1,  $contrato['comissao_manutencao']);

	$pdf->ln(8);
	$pdf->Cell(22, 1, 'Cronograma :');
	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(3);


	$diaSemana='';
	if($contrato['domingo']==1){
		$diaSemana = ' Dom';
	}
	if($contrato['segunda']==1){
		$diaSemana = $diaSemana . ' Seg';
	}
	if($contrato['terca']==1){
		$diaSemana = $diaSemana . ' Ter';
	}
	if($contrato['quarta']==1){
		$diaSemana = $diaSemana . ' Qua';
	}
	if($contrato['quinta']==1){
		$diaSemana = $diaSemana . ' Qui';
	}
	if($contrato['sexta']==1){
		$diaSemana = $diaSemana . ' Sex';
	}
	if($contrato['sabado']==1){
		$diaSemana = $diaSemana . ' Sabado';
	}

	// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
	$frequenciaId = $contrato[ 'frequencia' ];
	$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
	$frequencia = $frequencia[ 'nome' ];

	$quinzenalId = $contrato[ 'quinzenal' ];
	$quinzenal = mostra( 'contrato_quinzenal', "WHERE id AND id='$quinzenalId'" );
	$quinzenal= $quinzenal[ 'nome' ];

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Frequencia :');
	$pdf->SetX(35);
	$pdf->Cell(22, 1, $frequencia);

	$pdf->SetX(60);
	$pdf->Cell(22, 1, 'Coleta Quinzenal :');
	$pdf->SetX(93);
	$pdf->Cell(22, 1, $quinzenal);

	$pdf->SetX(120);
	$pdf->Cell(22, 1, 'Hora Limite :');
	$pdf->SetX(143);
	$pdf->Cell(22, 1, $contrato['hora_limite']);

	$pdf->SetX(155);
	$pdf->Cell(22, 1, 'Coletar Feriado :');

	if($contrato['coletar_feriado']==1){
		$pdf->SetX(185);
		$pdf->Cell(22, 1, 'SIM');
	}else{
		$pdf->SetX(185);
		$pdf->Cell(22, 1, 'NAO');
	}

	$pdf->Ln(8);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Dia da Semana');

	$pdf->SetX(40);
	$pdf->Cell(22, 1, 'Quantidade');

	$pdf->SetX(70);
	$pdf->Cell(22, 1, 'Rota 1');

	$pdf->SetX(90);
	$pdf->Cell(22, 1, 'Hora 1');

	$pdf->SetX(110);
	$pdf->Cell(22, 1, 'Rota 2');

	$pdf->SetX(130);
	$pdf->Cell(22, 1, 'Hora 2');

	$pdf->SetX(150);
	$pdf->Cell(22, 1, 'Rota 3');

	$pdf->SetX(170);
	$pdf->Cell(22, 1, 'Hora 3');


	if($contrato['domingo']=='1'){

		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, '[ X ] Domingo');

		$pdf->SetX(48);
		$pdf->Cell(22, 1, $contrato['domingo_quantidade']);

		$rotaId = $contrato[ 'domingo_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
		$pdf->SetX(70);
		$pdf->Cell(22, 1, $rota);

		$pdf->SetX(90);
		$pdf->Cell(22, 1, $contrato['domingo_hora1']);
	}

	if($contrato['segunda']=='1'){

		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, '[ X ] Segunda');

		$pdf->SetX(48);
		$pdf->Cell(22, 1, $contrato['segunda_quantidade']);

		$rotaId = $contrato[ 'segunda_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
		$pdf->SetX(70);
		$pdf->Cell(22, 1, $rota);

		$pdf->SetX(90);
		$pdf->Cell(22, 1, $contrato['segunda_hora1']);
	}

	if($contrato['terca']=='1'){

		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, '[ X ] Terça');

		$pdf->SetX(48);
		$pdf->Cell(22, 1, $contrato['terca_quantidade']);

		$rotaId = $contrato[ 'terca_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
		$pdf->SetX(70);
		$pdf->Cell(22, 1, $rota);

		$pdf->SetX(90);
		$pdf->Cell(22, 1, $contrato['terca_hora1']);
	}


	if($contrato['quarta']=='1'){

		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, '[ X ] Quarta');

		$pdf->SetX(48);
		$pdf->Cell(22, 1, $contrato['quarta_quantidade']);

		$rotaId = $contrato[ 'quarta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
		$pdf->SetX(70);
		$pdf->Cell(22, 1, $rota);

		$pdf->SetX(90);
		$pdf->Cell(22, 1, $contrato['quarta_hora1']);
	}


	if($contrato['quinta']=='1'){

		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, '[ X ] Quinta');

		$pdf->SetX(48);
		$pdf->Cell(22, 1, $contrato['quinta_quantidade']);

		$rotaId = $contrato[ 'quinta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
		$pdf->SetX(70);
		$pdf->Cell(22, 1, $rota);

		$pdf->SetX(90);
		$pdf->Cell(22, 1, $contrato['quinta_hora1']);
	}

	if($contrato['sexta']=='1'){

		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, '[ X ] Sexta');

		$pdf->SetX(48);
		$pdf->Cell(22, 1, $contrato['sexta_quantidade']);

		$rotaId = $contrato[ 'sexta_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
		$pdf->SetX(70);
		$pdf->Cell(22, 1, $rota);

		$pdf->SetX(90);
		$pdf->Cell(22, 1, $contrato['sexta_hora1']);
	}

	if($contrato['sabado']=='1'){

		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, '[ X ] Sabado');

		$pdf->SetX(48);
		$pdf->Cell(22, 1, $contrato['sabado_quantidade']);

		$rotaId = $contrato[ 'sabado_rota1' ];
		$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
		$rota= $rota[ 'nome' ];
		$pdf->SetX(70);
		$pdf->Cell(22, 1, $rota);

		$pdf->SetX(90);
		$pdf->Cell(22, 1, $contrato['sabado_hora1']);
	}

	// Faturamento

	$pdf->ln(8);
	$pdf->Cell(22, 1, 'Faturamento :');
	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Cobrança :');
	$cobrancaId = $contrato[ 'cobranca_coleta' ];
	$cobranca = mostra( 'contrato_cobranca', "WHERE id AND id='$cobrancaId'" );
	$pdf->SetX(32);
	$pdf->Cell(22, 1, $cobranca[ 'nome']);

	$pdf->SetX(95);
	$pdf->Cell(22, 1, 'Dia do Fechamento :');
	$pdf->SetX(132);
	$pdf->Cell(22, 1, $contrato[ 'dia_fechamento']);

	$pdf->SetX(155);
	$pdf->Cell(22, 1, 'Dia do Vencimento :');
	$pdf->SetX(190);
	$pdf->Cell(22, 1, $contrato[ 'dia_vencimento']);
	$pdf->Ln(5);

	$pdf->SetX(10);
	$pdf->Cell(20, 1, 'Boleto Bancário :');
	if($contrato['boleto_bancario']==1){
		$pdf->SetX(42);
		$pdf->Cell(22, 1, 'SIM');
	}else{
		$pdf->SetX(42);
		$pdf->Cell(22, 1, 'NAO');
	}

	$pdf->SetX(60);
	$pdf->Cell(20, 1, 'Valor Boleto :');
	$pdf->SetX(85);
	$pdf->Cell(22, 1, converteValor($contrato[ 'boleto_valor']));


	$pdf->SetX(100);
	$pdf->Cell(22, 1, 'Nota Fiscal :');
	if($contrato['nota_fiscal']==1){
		$pdf->SetX(123);
		$pdf->Cell(22, 1, 'SIM');
	}else{
		$pdf->SetX(123);
		$pdf->Cell(22, 1, 'NAO');
	}
	$pdf->SetX(138);
	$pdf->Cell(20, 1, 'ISS % :');
	$pdf->SetX(152);
	$pdf->Cell(22, 1, $contrato[ 'iss_valor']);

	$pdf->SetX(166);
	$pdf->Cell(20, 1, 'ISS Retido :');
	if($contrato['iss_retido']==1){
		$pdf->SetX(188);
		$pdf->Cell(22, 1, 'SIM');
	}else{
		$pdf->SetX(188);
		$pdf->Cell(22, 1, 'NAO');
	}

	// Manifesto & Cobrança 

	$pdf->ln(8);
	$pdf->Cell(22, 1, 'Manifesto & Cobrança :');
	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(5);

	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Manifesto :');
	$manifestoId = $contrato[ 'manifesto' ];
	$manifesto = mostra('contrato_manifesto', "WHERE id AND id='$manifestoId'" );
	$pdf->SetX(32);
	$pdf->Cell(22, 1, $manifesto[ 'nome']);

	$pdf->SetX(90);
	$pdf->Cell(20, 1, 'Valor por Manifesto :');
	$pdf->SetX(135);
	$pdf->Cell(22, 1, converteValor($contrato[ 'manifesto_valor']));

	// TIPO DE COLETA

	$pdf->ln(8);
	$pdf->Cell(22, 1, 'Tipo de Coleta :');
	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(5);

	$pdf->Cell(20, 1, 'Coleta');
	$pdf->SetX(45);
	$pdf->Cell(20, 1, 'Quantidade');
	$pdf->SetX(73);
	$pdf->Cell(20, 1, 'Valor Unitário');
	$pdf->SetX(103);
	$pdf->Cell(20, 1, 'Valor Extra');
	$pdf->SetX(131);
	$pdf->Cell(20, 1, 'Inicio');
	$pdf->SetX(150);
	$pdf->Cell(20, 1, 'Vencimento');
	$pdf->SetX(175);
	$pdf->Cell(20, 1, 'Valor Mensal');
	$pdf->SetX(80);
	$pdf->Ln(3);
	$pdf->Cell(0,0,'',1,1,'L');
	$pdf->Ln(2);

	$leitura = read('contrato_coleta',"WHERE id_contrato='$contratoId'");

	foreach($leitura as $mostra):

		$pdf->ln(2);

		$tipoColetaId=$mostra['tipo_coleta'];
		$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

		$pdf->SetX(10);
		$pdf->Cell(22, 1, $tipoColeta['nome']);

		$pdf->SetX(58);
		$pdf->Cell(22, 1, $mostra['quantidade']);

		$pdf->SetX(83);
		$pdf->Cell(22, 1, converteValor($mostra['valor_unitario']));

		$pdf->SetX(111);
		$pdf->Cell(22, 1, converteValor($mostra['valor_extra']));

		$pdf->SetX(128);
		$pdf->Cell(22, 1, converteData($mostra['inicio']));

		$pdf->SetX(152);
		$pdf->Cell(22, 1, converteData($mostra['vencimento']));

		$pdf->SetX(185);
		$pdf->Cell(22, 1, converteValor($mostra['valor_mensal']));

		$dataColeta=$mostra['inicio'];
		$dataVencimento=$mostra['vencimento'];

	endforeach;

	$i=$i+1;
}

endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');

 
?>