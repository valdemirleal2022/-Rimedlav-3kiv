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
	  $titulo="Clientes Correios";
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


$total=0;
$leitura = read('contrato',"WHERE id AND enviar_boleto_correio='1' ORDER BY inicio ASC");

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(5);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(180);
$pdf->Cell(10, 1, 'Email');
$pdf->SetX(250);
$pdf->Cell(10, 1, 'Contrato');
$pdf->SetX(270);
$pdf->Cell(10, 1, 'Consultor');
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;


foreach($leitura as $mostra):

		$total++;

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->SetX(5);

		$clienteId = $mostra['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		$pdf->Cell(10, 5,substr($cliente['nome'],0,30));

		$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
		$pdf->SetX(70);
		$pdf->Cell(10, 5, substr($endereco,0,30));

		$pdf->SetX(120);
		$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));

		$pdf->SetX(150);
		$pdf->Cell(10, 5, substr($cliente['telefone'],0,20));

		$pdf->SetX(180);
		$pdf->Cell(10, 5, $cliente['email']);
		
		$pdf->SetX(250);
		$pdf->Cell(10, 5, substr($mostra['controle'],0,6));

		$pdf->SetX(270);
		$consultorId=$mostra['consultor'];
		$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
		$pdf->Cell(10, 5, $consultor['nome']);


		$i=$i+1;

	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(80);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Telefone');
		$pdf->SetX(180);
		$pdf->Cell(20, 1, 'Email');
		$pdf->SetX(250);
		$pdf->Cell(10, 1, 'Contrato');
		$pdf->SetX(270);
		$pdf->Cell(10, 1, 'Consultor');
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