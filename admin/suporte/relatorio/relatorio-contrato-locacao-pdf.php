 
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
	  $titulo="Contratos com Locação";
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

$total = conta('contrato',"WHERE id AND cobrar_locacao='1'");
$leitura = read('contrato',"WHERE id AND cobrar_locacao='1' ORDER BY inicio DESC");


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(30);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(95);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(116);
$pdf->Cell(20, 1, 'Tipo de Coleta');
$pdf->SetX(165);
$pdf->Cell(20, 1, 'Quantidade');
$pdf->SetX(200);
$pdf->Cell(10, 1, 'Valor Unitario');
$pdf->SetX(230);
$pdf->Cell(10, 1, 'Valor Mensal');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Tipo de Contrato');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id'].'|'.substr($mostra['controle'],0,6));

	$pdf->SetX(30);
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));

	$pdf->SetX(70);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,13));

	$pdf->SetX(95);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);
	
	$contratoId = $mostra['id'];

		$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");
		$tipoColetaId = $contratoColeta['tipo_coleta'];
    	$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

		$contratoTipoId = $mostra['contrato_tipo'];
		$contratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
 

	$pdf->SetX(116);
	$pdf->Cell(10, 5, $coleta['nome']);

	$pdf->SetX(170);
	$pdf->Cell(10, 5, $contratoColeta['quantidade']);

	$pdf->SetX(205);
	$pdf->Cell(10, 5, $contratoColeta['valor_unitario'],0,0,'R');

	$pdf->SetX(240);
	$pdf->Cell(10, 5, converteValor($mostra['valor_mensal']),0,0,'R');

	$pdf->SetX(260);
	$pdf->Cell(10, 5, $contratoTipo['nome']);


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
 
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(30);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(70);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(95);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(116);
		$pdf->Cell(20, 1, 'Tipo de Coleta');
		$pdf->SetX(165);
		$pdf->Cell(20, 1, 'Quantidade');
		$pdf->SetX(200);
		$pdf->Cell(10, 1, 'Valor Unitario');
		$pdf->SetX(230);
		$pdf->Cell(10, 1, 'Valor Mensal');
		$pdf->SetX(260);
		$pdf->Cell(10, 1, 'Tipo de Contrato');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
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