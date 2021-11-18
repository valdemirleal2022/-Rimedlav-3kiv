<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

if(isset($_SESSION['contratoTipo'])){
	$contratoTipo = $_SESSION['contratoTipo'];
}
if(isset($_SESSION[ 'contratoCobranca' ])){
	$contratoCobranca =$_SESSION[ 'contratoCobranca' ];
}

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
	  $titulo="Contratos Ativos";
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

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' 
											AND inicio<='$data2'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  
											AND inicio<='$data2' ORDER BY inicio ASC");


if(isset($contratoTipo)){
	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND inicio>='$data1'  AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5'",'valor_mensal');
			
	$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5'");
			
	$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  AND inicio<='$data2' AND contrato_tipo='$contratoTipo' AND status='5' ORDER BY inicio ASC");
}
		
if(isset($contratoCobranca)){
	$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND inicio>='$data1'  AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5'",'valor_mensal');
			
	$total = conta('contrato',"WHERE id AND tipo='2' AND inicio>='$data1' AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5'");
			
	$leitura = read('contrato',"WHERE id AND tipo='2' AND inicio>='$data1'  AND inicio<='$data2' AND cobranca_coleta='$contratoCobranca' AND status='5' ORDER BY inicio ASC");
}


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));

if(isset($contratoTipo)){
	$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipo'");
	$pdf->SetX(120);
	$pdf->Cell(22, 1, $contratoTipo['nome']);
}

if(isset($contratoCobranca)){
	$cobranca = mostra('contrato_cobranca',"WHERE id ='$contratoCobranca'");
	$pdf->SetX(140);
	$pdf->Cell(22, 1, $cobranca['nome']);
}

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(15);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Valor Mensal');
$pdf->SetX(135);
$pdf->Cell(20, 1, 'Aprovação');
$pdf->SetX(160);
$pdf->Cell(10, 1, 'Inicio');
$pdf->SetX(190);
$pdf->Cell(10, 1, 'Tipo de Contrato');
$pdf->SetX(230);
$pdf->Cell(10, 1, 'Cobrança');
$pdf->SetX(270);
$pdf->Cell(10, 1, 'Status');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $mostra['id']);

	$pdf->SetX(15);
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(65);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$pdf->SetX(90);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(118);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal']));
	
	$pdf->SetX(135);
	$pdf->Cell(10, 5, converteData($mostra['aprovacao']));

	$pdf->SetX(160);
	$pdf->Cell(10, 5, converteData($mostra['inicio']));
	
	$tipoContratoId=$mostra['contrato_tipo'];
	$tipoContrato= mostra('contrato_tipo',"WHERE id ='$tipoContratoId'");
	$pdf->SetX(190);
	$pdf->Cell(10, 5, $tipoContrato['nome']);
	
	$cobrancaId=$mostra['cobranca_coleta'];
	$cobranca = mostra('contrato_cobranca',"WHERE id ='$cobrancaId'");
	$pdf->SetX(230);
	$pdf->Cell(10, 5, $cobranca['nome']);
	
	$statusId=$mostra['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId'");
	$pdf->SetX(270);
	$pdf->Cell(10, 5, $status['nome']);


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(4);
		
		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(90);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Valor Mensal');
		$pdf->SetX(135);
		$pdf->Cell(20, 1, 'Aprovação');
		$pdf->SetX(160);
		$pdf->Cell(10, 1, 'Inicio');
		$pdf->SetX(190);
		$pdf->Cell(10, 1, 'Tipo de Contrato');
		$pdf->SetX(230);
		$pdf->Cell(10, 1, 'Cobrança');
		$pdf->SetX(270);
		$pdf->Cell(10, 1, 'Status');
		
		$pdf->Line(5, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . number_format($valor_total,2,',','.'));
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>