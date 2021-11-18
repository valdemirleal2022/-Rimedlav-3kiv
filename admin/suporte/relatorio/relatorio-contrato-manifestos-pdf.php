<?php


$manifesto = $_SESSION['manifesto'];
$contratoTipo = $_SESSION['contratoTipo'];

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
	  $titulo="Contratos Manifestos";
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

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto' ORDER BY inicio DESC");

if(!empty($contratoTipo)){
	$total = conta('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto' AND contrato_tipo='$contratoTipo'");
	$leitura = read('contrato',"WHERE id AND tipo='2' AND status='5' AND manifesto='$manifesto' AND contrato_tipo='$contratoTipo' ORDER BY controle ASC");
	$tipocontrato = mostra('contrato_tipo',"WHERE id AND id='$contratoTipo'");
}

$manifesto = mostra('contrato_manifesto',"WHERE id AND id='$manifesto'");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Manifesto :');
$pdf->SetX(32);
$pdf->Cell(22, 1, $manifesto['nome']);

$pdf->SetX(82);
$pdf->Cell(22, 1, 'Tipo de Contrato :');
$pdf->SetX(115);
$pdf->Cell(22, 1, $tipocontrato['nome']);

$pdf->Ln(5);
$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(15);
$pdf->Cell(20, 1, 'Controle');
$pdf->SetX(30);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(78);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(105);
$pdf->Cell(20, 1, 'Tipo');
$pdf->SetX(130);
$pdf->Cell(20, 1, 'Coleta');
$pdf->SetX(162);
$pdf->Cell(20, 1, 'Valor');
$pdf->SetX(172);
$pdf->Cell(20, 1, 'Aprovação');
$pdf->SetX(192);
$pdf->Cell(10, 1, 'Inicio');
$pdf->Line(0, 33, 220, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $contrato):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(4);
	$pdf->Cell(10, 5, $contrato['id'],0,0,'R');

	$pdf->SetX(16);
	$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

	$pdf->SetX(30);
	$clienteId = $contrato['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

	$pdf->SetX(78);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,12));

	$tipoId = $contrato['contrato_tipo'];
	$tipo = mostra('contrato_tipo',"WHERE id ='$tipoId'");
	$pdf->SetX(105);
	$pdf->Cell(10, 5, $tipo['nome']);
	
	$contratoId = $contrato['id'];
	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
	$tipoColetaId = $contratoColeta['tipo_coleta'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(130);
	$pdf->Cell(10, 5,$coleta['nome']);
		
	$pdf->SetX(163);
	$pdf->Cell(10, 5, converteValor($contrato['valor_mensal']),0,0,'R');
	
	$pdf->SetX(174);
	$pdf->Cell(10, 5, converteData($contrato['aprovacao']));

	$pdf->SetX(190);
	$pdf->Cell(10, 5, converteData($contrato['inicio']));
	
	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, 'Manifesto :');
		$pdf->SetX(32);
		$pdf->Cell(22, 1, $manifesto['nome']);

		$pdf->Ln(5);
		$pdf->Line(0, 28, 220, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Controle');
		$pdf->SetX(30);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(78);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(105);
		$pdf->Cell(20, 1, 'Tipo');
		$pdf->SetX(130);
		$pdf->Cell(20, 1, 'Coleta');
		$pdf->SetX(162);
		$pdf->Cell(20, 1, 'Valor');
		$pdf->SetX(172);
		$pdf->Cell(20, 1, 'Aprovação');
		$pdf->SetX(192);
		$pdf->Cell(10, 1, 'Inicio');
		$pdf->Line(0, 33, 220, 33); // insere linha divisória
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