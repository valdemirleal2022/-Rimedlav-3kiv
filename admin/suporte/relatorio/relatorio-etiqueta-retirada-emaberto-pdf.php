<?php


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Retirada de Etiqueta";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}


$total = conta('estoque_etiqueta_retirada',"WHERE id AND status='Em aberto' AND data_entrega>='$data1' 
					 AND data_entrega<='$data2'");
$leitura = read('estoque_etiqueta_retirada',"WHERE id AND status='Em aberto' AND data_entrega>='$data1' 
					 AND data_entrega<='$data2' ORDER BY data_entrega ASC");


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));
$pdf->Cell(22, 1, 'Rota :');
$pdf->Cell(22, 1, $rotaColeta['nome']);

$pdf->Ln(5);

$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(12);
$pdf->Cell(20, 1, 'Etiqueta');
$pdf->SetX(45);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'Endereço');
$pdf->SetX(121);
$pdf->Cell(20, 1, 'Cliente');

$pdf->SetX(160);
$pdf->Cell(20, 1, 'Frequencia');
$pdf->SetX(182);
$pdf->Cell(20, 1, 'Dia da Semana');
$pdf->SetX(210);
$pdf->Cell(20, 1, 'Rota');

$pdf->SetX(222);
$pdf->Cell(20, 1, 'Quant');
$pdf->SetX(235);
$pdf->Cell(20, 1, 'Solicitação');
$pdf->SetX(257);
$pdf->Cell(20, 1, 'Entrega');
$pdf->SetX(275);
$pdf->Cell(20, 1, 'Status');
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

				
	$etiquetaId = $mostra['id_etiqueta'];
	$contratoId = $mostra['id_contrato'];
	$clienteId = $mostra['id_cliente'];

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id']);

	$etiqueta = mostra('estoque_etiqueta',"WHERE id ='$etiquetaId'");
	$pdf->SetX(12);
	$pdf->Cell(10, 5,$etiqueta['nome']);

	$pdf->SetX(45);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));
	
	$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
	$pdf->SetX(70);
	$pdf->Cell(10, 5, substr($endereco,0,35));

	$pdf->SetX(121);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));
	
	$contrato = mostra('contrato',"WHERE id ='$contratoId '");
	
	$diaSemana='';
	$rota='';
	if($contrato['domingo']==1){
		$diaSemana = ' D';
		$rota=$contrato['domingo_rota1'];
	}
	if($contrato['segunda']==1){
		$diaSemana = $diaSemana . ' S';
		$rota=$contrato[ 'segunda_rota1' ];
	}
	if($contrato['terca']==1){
		$diaSemana = $diaSemana . ' T';
		$rota=$contrato[ 'terca_rota1' ];
	}
	if($contrato['quarta']==1){
		$diaSemana = $diaSemana . ' Q';
		$rota=$contrato[ 'quarta_rota1' ];
	}
	if($contrato['quinta']==1){
		$diaSemana = $diaSemana . ' Q';
		$rota=$contrato[ 'quinta_rota1' ];
	}
	if($contrato['sexta']==1){
		$diaSemana = $diaSemana . ' S';
		$rota=$contrato[ 'sexta_rota1' ];
	}
	if($contrato['sabado']==1){
		$diaSemana = $diaSemana . ' S';
		$rota=$contrato[ 'sabado_rota1' ];
	}


	// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
	$frequenciaId = $contrato[ 'frequencia' ];
	$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
	$frequencia = $frequencia[ 'nome' ];

	$rota = mostra( 'contrato_rota', "WHERE id AND id='$rota'" );
	$rota = $rota[ 'nome' ];

	$pdf->SetX(160);
	$pdf->Cell(10, 5, $frequencia);
	$pdf->SetX(182);
	$pdf->Cell(10, 5, $diaSemana);
	$pdf->SetX(211);
	$pdf->Cell(10, 5, $rota);

	$pdf->SetX(225);
	$pdf->Cell(10, 5, $mostra['quantidade']);

	$pdf->SetX(237);
	$pdf->Cell(10, 5, converteData($mostra['data_solicitacao']));

	$pdf->SetX(257);
	$pdf->Cell(10, 5, converteData($mostra['data_entrega']));

	$pdf->SetX(275);
	$pdf->Cell(10, 5, $mostra['status']);


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(12);
		$pdf->Cell(20, 1, 'Etiqueta');
		$pdf->SetX(45);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(70);
		$pdf->Cell(20, 1, 'Endereço');
		$pdf->SetX(121);
		$pdf->Cell(20, 1, 'Cliente');

		$pdf->SetX(160);
		$pdf->Cell(20, 1, 'Frequencia');
		$pdf->SetX(182);
		$pdf->Cell(20, 1, 'Dia da Semana');
		$pdf->SetX(210);
		$pdf->Cell(20, 1, 'Rota');

		$pdf->SetX(222);
		$pdf->Cell(20, 1, 'Quant');
		$pdf->SetX(235);
		$pdf->Cell(20, 1, 'Solicitação');
		$pdf->SetX(257);
		$pdf->Cell(20, 1, 'Entrega');
		$pdf->SetX(275);
		$pdf->Cell(20, 1, 'Status');
		$pdf->Line(5, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);

		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>