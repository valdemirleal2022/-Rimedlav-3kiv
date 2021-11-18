<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$consultorId = $_SESSION['consultor'];
$empresaAtual = $_SESSION['empresaAtual'];

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
	  $titulo="Relatorio de Visitas";
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



$total = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' ORDER BY interacao ASC");
$leitura = read('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' ORDER BY interacao ASC");
		
if(!empty($consultorId)){
	$total = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
	$leitura = read('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId' ORDER BY interacao ASC");
}
		
if(!empty($empresaAtual)){
	$total = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND empresa_atual='$empresaAtual'");
	$leitura = read('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND empresa_atual='$empresaAtual' ORDER BY interacao ASC");
}


$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->SetX(32);
$pdf->Cell(22, 1, converteData($data1));
$pdf->SetX(70);
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->SetX(88);
$pdf->Cell(22, 1, converteData($data2));
$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(20);
$pdf->Cell(20, 1, 'Nome');
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Endereco');
$pdf->SetX(120);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(160);
$pdf->Cell(20, 1, 'Telefone');
$pdf->SetX(186);
$pdf->Cell(20, 1, 'Contato');
$pdf->SetX(210);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(230);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(260);
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
	$pdf->Cell(10, 5, substr($mostra['nome'],0,25));

	$endereco=$mostra['endereco'].', '.$mostra['numero'].' '.$mostra['complemento'];
	$pdf->SetX(65);
	$pdf->Cell(10, 5,  substr($endereco,0,35));;

	$pdf->SetX(120);
	$pdf->Cell(10, 5, substr($mostra['bairro'],0,20));

	$pdf->SetX(160);
	$pdf->Cell(10, 5, substr($mostra['telefone'],0,15));

	$pdf->SetX(186);
	$pdf->Cell(10, 5, substr($mostra['contato'],0,15));

	$pdf->SetX(210);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$pdf->SetX(230);
	$consultorId=$mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
	$pdf->Cell(10, 5, substr($consultor['nome'],0,15) );

	$pdf->SetX(260);
	$empresa_atualId=$mostra['empresa_atual'];
	$empresa_atual = mostra('cadastro_visita_empresa_atual',"WHERE id ='$empresa_atualId'");
	$pdf->Cell(10, 5, substr($empresa_atual['nome'],0,20));

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->SetX(10);
		$pdf->Cell(22, 1, 'Data Inicio :');
		$pdf->SetX(32);
		$pdf->Cell(22, 1, converteData($data1));
		$pdf->SetX(70);
		$pdf->Cell(22, 1, 'Data Fim :');
		$pdf->SetX(88);
		$pdf->Cell(22, 1, converteData($data2));
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Nome');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(160);
		$pdf->Cell(20, 1, 'Telefone');
		$pdf->SetX(186);
		$pdf->Cell(20, 1, 'Contato');
		$pdf->SetX(210);
		$pdf->Cell(20, 1, 'Data');
		$pdf->SetX(230);
		$pdf->Cell(20, 1, 'Consultor');
		$pdf->SetX(260);
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