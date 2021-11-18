<?php


$consultor = $_SESSION['consultor'];

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
	  $titulo="Contratos por Consultor";
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

$valor_total = soma('contrato',"WHERE id AND tipo='2' AND status<>'9' AND consultor='$consultor' AND status<>'9'",'valor_mensal');
$total = conta('contrato',"WHERE id AND tipo='2' AND consultor='$consultor' AND status<>'9'");
$leitura = read('contrato',"WHERE id AND tipo='2' AND consultor='$consultor' AND status<>'9'  ORDER BY inicio ASC");

$consultor = mostra('contrato_consultor',"WHERE id ='$consultor'");


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Consultor :');
$pdf->SetX(32);
$pdf->Cell(22, 1, $consultor['nome']);

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(4);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(15);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(130);
$pdf->Cell(20, 1, 'Bairro');

$pdf->SetX(156);
$pdf->Cell(20, 1, 'Tipo Coleta');
$pdf->SetX(192);
$pdf->Cell(20, 1, 'Unitario');
$pdf->SetX(212);
$pdf->Cell(20, 1, 'Mensal');

$pdf->SetX(230);
$pdf->Cell(20, 1, 'Aprovação');
$pdf->SetX(252);
$pdf->Cell(10, 1, 'Inicio');
$pdf->SetX(266);
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

	$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

	$pdf->SetX(65);
	$pdf->Cell(10, 5, substr($endereco,0,45));
	
	$pdf->SetX(130);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

	$contratoId = $mostra['id'];
	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId' ORDER BY id DESC");
	$tipoColetaId = $contratoColeta['tipo_coleta'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(156);
	$pdf->Cell(10, 5, $coleta['nome']);

	$pdf->SetX(198);
	$pdf->Cell(10, 5, converteValor($contratoColeta['valor_unitario']),0,0,'R');
	
	$pdf->SetX(217);
	$pdf->Cell(10, 5, converteValor($contratoColeta['valor_mensal']),0,0,'R');

	$pdf->SetX(232);
	$pdf->Cell(10, 5, converteData($mostra['aprovacao']));

	$pdf->SetX(250);
	$pdf->Cell(10, 5, converteData($mostra['inicio']));
	
	$statusId=$mostra['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId'");
	$pdf->SetX(266);
	$pdf->Cell(10, 5, $status['nome']);


	$i=$i+1;
	if ($i>27){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(4);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(130);
		$pdf->Cell(20, 1, 'Bairro');
		
		$pdf->SetX(156);
		$pdf->Cell(20, 1, 'Tipo Coleta');
		$pdf->SetX(192);
		$pdf->Cell(20, 1, 'Unitario');
		$pdf->SetX(212);
		$pdf->Cell(20, 1, 'Mensal');
		
		$pdf->SetX(230);
		$pdf->Cell(20, 1, 'Aprovação');
		$pdf->SetX(250);
		$pdf->Cell(10, 1, 'Inicio');
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