<?php

if(isset($_SESSION['dataInicio'])){
	$data1 = $_SESSION['dataInicio'];
}
if(isset($_SESSION['dataFinal'])){
	$data2 = $_SESSION['dataFinal'];
}
if(isset($_SESSION[ 'rotaColeta' ])){
	$rotaId =$_SESSION[ 'rotaColeta' ];
	
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
	  $titulo="Conferencia de Coletados";
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

$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2'");
$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' ORDER BY data DESC, hora ASC");

if(isset($_SESSION[ 'rotaColeta' ])){
	$rotaId =$_SESSION[ 'rotaColeta' ];
	$rotaColeta = mostra('contrato_rota',"WHERE id ='$rotaId'");
	$total = conta('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId'");
	$leitura = read('contrato_ordem',"WHERE data>='$data1' AND data<='$data2' AND rota='$rotaId' ORDER BY data DESC, hora ASC");
}

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

$pdf->SetX(5);
$pdf->Cell(20, 1, 'Hora');

$pdf->SetX(18);
$pdf->Cell(20, 1, 'Bairro');

$pdf->SetX(45);
$pdf->Cell(20, 1, 'Endereco');

$pdf->SetX(110);
$pdf->Cell(20, 1, 'Cliente');

$pdf->SetX(150);
$pdf->Cell(20, 1, 'Numero');

$pdf->SetX(170);
$pdf->Cell(20, 1, 'Coleta');

$pdf->SetX(200);
$pdf->Cell(10, 1, 'Previsto');

$pdf->SetX(218);
$pdf->Cell(10, 1, 'Coleta');

$pdf->SetX(235);
$pdf->Cell(10, 1, 'Status');

$pdf->SetX(260);
$pdf->Cell(10, 1, 'Contrato');

$pdf->SetX(280);
$pdf->Cell(10, 1, 'Rota');

$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $mostra['hora']);
	
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId'");

	$pdf->SetX(18);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));
	
	$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
	$pdf->SetX(45);
	$pdf->Cell(10, 5, substr($endereco,0,35));

	$pdf->SetX(110);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));

	$pdf->SetX(155);
	$pdf->Cell(10, 5, $mostra['id']);

	$tipoColetaId = $mostra['tipo_coleta1'];
    $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
	$pdf->SetX(170);
	$pdf->Cell(10, 5, $coleta['nome']);

	$contratoId = $mostra['id_contrato'];
    $contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

	$pdf->SetX(206);
	$pdf->Cell(10, 5, $contratoColeta['quantidade']);

	$pdf->SetX(223);
	$pdf->Cell(10, 5, $mostra['quantidade1']);

	$statusId = $mostra['status'];
    $status = mostra('contrato_status',"WHERE id ='$statusId'");
	
	$pdf->SetX(235);
	$pdf->Cell(10, 5, $status['nome']);

	$pdf->SetX(268);
	$pdf->Cell(10, 5, $mostra['id_contrato']);

	$rotaId = $mostra['rota'];
    $rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

	$pdf->SetX(280);
	$pdf->Cell(10, 5, $rota['nome']);

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Hora');

		$pdf->SetX(18);
		$pdf->Cell(20, 1, 'Bairro');

		$pdf->SetX(45);
		$pdf->Cell(20, 1, 'Endereco');

		$pdf->SetX(110);
		$pdf->Cell(20, 1, 'Cliente');

		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Numero');

		$pdf->SetX(170);
		$pdf->Cell(20, 1, 'Coleta');

		$pdf->SetX(200);
		$pdf->Cell(10, 1, 'Previsto');

		$pdf->SetX(218);
		$pdf->Cell(10, 1, 'Coleta');

		$pdf->SetX(235);
		$pdf->Cell(10, 1, 'Status');

		$pdf->SetX(260);
		$pdf->Cell(10, 1, 'Contrato');

		$pdf->SetX(280);
		$pdf->Cell(10, 1, 'Rota');
	
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