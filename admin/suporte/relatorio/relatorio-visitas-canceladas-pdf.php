<?php

 
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
	  $titulo="Relatorio de Visitas Canceladas";
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


$consultorId = $_SESSION['consultor'];
$total = conta('cadastro_visita',"WHERE id AND status='17'");
$leitura = read('cadastro_visita',"WHERE id AND status='17' ORDER BY orc_data DESC");

if(!empty($consultorId)){
	$total = conta('cadastro_visita',"WHERE id AND status='17' AND consultor='$consultorId'");
	$leitura = read('cadastro_visita',"WHERE id AND status='17' AND consultor='$consultorId' ORDER BY interacao ASC");
}

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(140);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(175);
$pdf->Cell(20, 1, 'Telefone');
$pdf->SetX(200);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(219);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(250);
$pdf->Cell(20, 1, 'Empresa Atual');
$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($mostra['nome'],0,30));

	$endereco=$mostra['endereco'].', '.$mostra['numero'].' '.$mostra['complemento'];
	$pdf->SetX(75);
	$pdf->Cell(10, 5, $endereco);

	$pdf->SetX(140);
	$pdf->Cell(10, 5, substr($mostra['bairro'],0,20));

	$pdf->SetX(175);
	$pdf->Cell(10, 5, substr($mostra['telefone'],0,15));

	$pdf->SetX(200);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$pdf->SetX(219);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(250);
	$empresa_atualId=$mostra['empresa_atual'];
	$empresa_atual = mostra('cadastro_visita_empresa_atual',"WHERE id ='$empresa_atualId'");
	$pdf->Cell(10, 5, $empresa_atual['nome']);

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->SetX(10);
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(75);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(140);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(175);
		$pdf->Cell(20, 1, 'Telefone');
		$pdf->SetX(200);
		$pdf->Cell(20, 1, 'Data');
		$pdf->SetX(219);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(250);
		$pdf->Cell(20, 1, 'Empresa Atual');
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