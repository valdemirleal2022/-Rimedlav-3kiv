<?php

$clienteTipo = $_SESSION['clienteTipo'];
$clienteClassificacao = $_SESSION['clienteClassificacao'];

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
	  $titulo="Clientes Tipo/Classificação";
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

$leitura = read('cliente',"WHERE id ORDER BY nome ASC");
$total =  conta('cliente',"WHERE id ORDER BY nome ASC");

if(!empty($clienteTipo)){
			$leitura = read('cliente',"WHERE id AND tipo='$clienteTipo' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND tipo='$clienteTipo'");
		}
		if(!empty($clienteClassificacao)){
			$leitura = read('cliente',"WHERE id AND classificacao='$clienteClassificacao' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND classificacao='$clienteClassificacao'");
		}
		
		if(!empty($clienteClassificacao) and !empty($clienteTipo) ){
			$leitura = read('cliente',"WHERE id AND tipo='$clienteTipo' AND classificacao='$clienteClassificacao' ORDER BY nome ASC");
			$total =  conta('cliente',"WHERE id AND tipo='$clienteTipo' AND classificacao='$clienteClassificacao'");
}

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(5);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(60);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Contato');
$pdf->SetX(150);
$pdf->Cell(20, 1, 'Telefone');
$pdf->SetX(180);
$pdf->Cell(10, 1, 'Email');
$pdf->SetX(260);
$pdf->Cell(10, 1, 'Classificacao');
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;


foreach($leitura as $mostra):

		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
		$pdf->SetX(5);

		$pdf->Cell(10, 5,substr($mostra['nome'],0,25));

		$endereco=substr($mostra['endereco'],0,30).','.$mostra['numero'].' - '.$mostra['complemento'];
		$pdf->SetX(60);
		$pdf->Cell(10, 5, substr($endereco,0,30));

		$pdf->SetX(120);
		$pdf->Cell(10, 5, substr($mostra['contato'],0,15));

		$pdf->SetX(150);
		$pdf->Cell(10, 5, substr($mostra['telefone'],0,20));

		$pdf->SetX(180);
		$pdf->Cell(10, 5, $mostra['email']);

		$classificacaoId=$mostra['classificacao'];
		$classificacao = mostra('cliente_classificacao',"WHERE id='$classificacaoId'");
		
		$pdf->SetX(260);
		$pdf->Cell(10, 5, $classificacao['nome']);


		$i=$i+1;

	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(60);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Contato');
		$pdf->SetX(150);
		$pdf->Cell(20, 1, 'Telefone');
		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Email');
		$pdf->SetX(260);
		$pdf->Cell(10, 1, 'Classificacao');
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