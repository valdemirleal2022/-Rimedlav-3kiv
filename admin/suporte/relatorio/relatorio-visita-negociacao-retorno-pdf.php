<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$consultor = $_SESSION['consultor'];
 
//$data1 = date( "Y-m-d", strtotime( "$data1 -1 days" ) );
$dataFinal = date( "Y-m-d", strtotime( "$data2 +1 days" ) );
 
$total = conta('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal'");
$leitura = read('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal' 
		ORDER BY interacao DESC");

if (!empty($consultor) ) {
			
			$total = conta('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal'");
			$leitura = read('cadastro_visita_negociacao',"WHERE interacao>'$data1' AND interacao<'$dataFinal' AND consultor='$consultor' ORDER BY interacao DESC");
			
		 
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
$pdf->SetX(75);
$pdf->Cell(20, 1, 'Consultor');
$pdf->SetX(140);
$pdf->Cell(20, 1, 'Descrição');
$pdf->SetX(175);
$pdf->Cell(20, 1, 'Interação');
$pdf->SetX(200);
$pdf->Cell(20, 1, 'Retorno');
$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$visitaId = $mostra['id_visita'];
	$visita = mostra('cadastro_visita',"WHERE id ='$visitaId'");
		 
	$html .= "<td>".$visita['nome']."</td>";
	$consultorId = $mostra['consultor'];
	$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
		 
	$pdf->SetX(20);
	$pdf->Cell(10, 5, substr($visita['nome'],0,30));
 
	$pdf->SetX(75);
	$pdf->Cell(10, 5, $consultor['nome']);

	$pdf->SetX(140);
	$pdf->Cell(10, 5, $mostra['descricao']);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s',strtotime($mostra['interacao'])));

	$pdf->SetX(200);
	$pdf->Cell(10, 5, converteData($mostra['retorno']));

	 
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
		$pdf->SetX(75);
		$pdf->Cell(20, 1, 'Endereco');
		$pdf->SetX(140);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(175);
		$pdf->Cell(20, 1, 'Telefone');
		$pdf->SetX(200);
		$pdf->Cell(20, 1, 'Data');
		$pdf->SetX(215);
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