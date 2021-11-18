<?php

		
if(!empty($_GET['contratoId'])){
	$contratoId = $_GET['contratoId'];
}

$readContrato = read('contrato',"WHERE id = '$contratoId'");
if ( !$readContrato ) {
    header( 'Location: painel.php?execute=suporte/error' );
}
	

require_once("fpdf/fpdf.php");
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
	  $titulo="Ficha de Cliente";
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

$i=0;

$contrato = mostra('contrato',"WHERE id='$contratoId'");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$clienteId = $contrato['id_cliente'];
$cliente = mostra('cliente',"WHERE id ='$clienteId '");

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

$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Endereço :');
$pdf->SetX(30);
$pdf->Cell(22, 1, $endereco);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Bairro :');
$pdf->SetX(25);
$pdf->Cell(22, 1, $cliente['bairro']);

$pdf->SetX(100);
$pdf->Cell(22, 1, 'Cep :');
$pdf->SetX(112);
$pdf->Cell(22, 1, $cliente['cep']);

$pdf->SetX(147);
$pdf->Cell(22, 1, 'Cidade :');
$pdf->SetX(170);
$pdf->Cell(22, 1, $cliente['cidade']);

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
$pdf->Cell(22, 1, 'CNPJ :');
$pdf->SetX(30);
$pdf->Cell(22, 1, $cliente['cnpj']);

$pdf->SetX(80);
$pdf->Cell(22, 1, 'Inscrição :');
$pdf->SetX(100);
$pdf->Cell(22, 1, $cliente['inscricao']);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Contrato Id :');
$pdf->SetX(35);
$pdf->Cell(22, 1, $contrato['id']);

$pdf->SetX(60);
$pdf->Cell(22, 1, 'Controle :');
$pdf->SetX(80);
$pdf->Cell(22, 1, $contrato['controle']);


$consultorId=$contrato['consultor'];
$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
$pdf->SetX(130);
$pdf->Cell(22, 1, 'Consultor :');
$pdf->SetX(150);
$pdf->Cell(22, 1, $consultor['nome']);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Referência :');
$pdf->SetX(34);
$pdf->Cell(22, 1, $cliente['referencia']);

$pdf->ln(5);

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
	$diaSemana = $diaSemana . ' Sab';
}

// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
$frequenciaId = $contrato[ 'frequencia' ];
$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
$frequencia = $frequencia[ 'nome' ];

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Frequencia :');
$pdf->SetX(35);
$pdf->Cell(22, 1, $frequencia);

$pdf->SetX(70);
$pdf->Cell(22, 1, 'Dia(s) da Semana :');
$pdf->SetX(103);
$pdf->Cell(22, 1, $diaSemana);

$pdf->Ln(8);

$pdf->Cell(20, 1, 'Tipo de Coleta');
$pdf->SetX(53);
$pdf->Cell(20, 1, 'Quantidade');
$pdf->SetX(78);
$pdf->Cell(20, 1, 'Vl Unitário');
$pdf->SetX(100);
$pdf->Cell(20, 1, 'Vl Extra');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Inicio');
$pdf->SetX(138);
$pdf->Cell(20, 1, 'Vencimento');
$pdf->SetX(161);
$pdf->Cell(20, 1, 'Vl Mensal');
$pdf->SetX(180);
$pdf->Cell(20, 1, 'Qua Mensal');

$pdf->Ln(3);
$pdf->Cell(0,0,'',1,1,'L');
$pdf->Ln(1);

$leitura = read('contrato_coleta',"WHERE id_contrato='$contratoId'");

foreach($leitura as $mostra):

	$pdf->ln(6);

	$tipoColetaId=$mostra['tipo_coleta'];
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

	$pdf->SetX(10);
	$pdf->Cell(22, 1, $tipoColeta['nome']);

	$pdf->SetX(45);
	$pdf->Cell(22, 1, $mostra['quantidade'],0,0,'R'); 

	$pdf->SetX(75);
	$pdf->Cell(22, 1, converteValor($mostra['valor_unitario']),0,0,'R');

	$pdf->SetX(92);
	$pdf->Cell(22, 1, converteValor($mostra['valor_extra']),0,0,'R');

	$pdf->SetX(118);
	$pdf->Cell(22, 1, converteData($mostra['inicio']));

	$pdf->SetX(139);
	$pdf->Cell(22, 1, converteData($mostra['vencimento']));
	
	$pdf->SetX(158);
	$pdf->Cell(22, 1, converteValor($mostra['valor_mensal']),0,0,'R');

	$pdf->SetX(175);
	$pdf->Cell(22, 1, $mostra['quantidade_mensal'],0,0,'R');

	$dataColeta=$mostra['inicio'];
	$dataVencimento=$mostra['vencimento'];

endforeach;

$pdf->Ln(8);
$pdf->Cell(0,0,'',1,1,'L');
$pdf->Ln(3);
$pdf->Cell(20, 1, 'RESUMO 12 MESES');
$pdf->Ln(3);

$pdf->SetFont('Arial','B',8);
$pdf->ln();


$coletadoQuantidadeTotal = 0;
$coletadoValorTotal = 0;

$pdf->Ln(3);
$pdf->SetX(10);
$pdf->Cell(20, 1, 'MES');
$pdf->SetX(35);
$pdf->Cell(20, 1, 'QUANTIDADE');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'VALOR TOTAL');
$pdf->Ln(5);

// - 0 DIAS
$mes = date('m/Y');
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(1);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;

// - 30 DIAS
$mes = date('m/Y',strtotime('-1months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;


// - 2 MESES
$mes = date('m/Y',strtotime('-2months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');


$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;


// - 3 MESES
$mes = date('m/Y',strtotime('-3months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;


// - 4 MESES
$mes = date('m/Y',strtotime('-4months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;


// - 5 MESES
$mes = date('m/Y',strtotime('-5months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;


// - 6 MESES
$mes = date('m/Y',strtotime('-6months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;

// - 7 MESES
$mes = date('m/Y',strtotime('-7months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;

// - 8 MESES
$mes = date('m/Y',strtotime('-8months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;


// - 9 MESES
$mes = date('m/Y',strtotime('-9months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;

// - 10 MESES
$mes = date('m/Y',strtotime('-10months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;

// - 11 MESES
$mes = date('m/Y',strtotime('-11months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;


// - 12 MESES
$mes = date('m/Y',strtotime('-12months'));
$mesano = explode('/',$mes);
$leituraColetado = read('contrato_ordem',"WHERE id AND status='13' AND id_contrato='$contratoId' AND 	
							Month(data)='$mesano[0]' AND Year(data)='$mesano[1]'");
$coletadoQuantidade = 0;
$coletadoValor = 0;

foreach($leituraColetado as $coletado):

	$tipoColetaId=$coletado['tipo_coleta1'];
	$quantidade=$coletado['quantidade1'];

	$tipoColeta = mostra('contrato_coleta',"WHERE tipo_coleta ='$tipoColetaId' AND
														id_contrato='$contratoId'");
	$valor=$tipoColeta['valor_unitario'];

	$coletadoQuantidade = $coletadoQuantidade + $quantidade ;
	$coletadoValor = $coletadoValor + ($valor * $quantidade) ;

endforeach;

$coletadoQuantidade = $coletadoQuantidade;
$coletadoValor = $coletadoValor;

$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20, 1, $mes);
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidade,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValor),0,0,'R');

$coletadoQuantidadeTotal = $coletadoQuantidadeTotal + $coletadoQuantidade;
$coletadoValorTotal = $coletadoValorTotal + $coletadoValor;

$pdf->Ln(8);
$pdf->SetX(10);
$pdf->Cell(20, 1, 'TOTAL');
$pdf->SetX(40);
$pdf->Cell(20, 1, $coletadoQuantidadeTotal,0,0,'R');
$pdf->SetX(75);
$pdf->Cell(20, 1, converteValor($coletadoValorTotal),0,0,'R');


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');



 
?>