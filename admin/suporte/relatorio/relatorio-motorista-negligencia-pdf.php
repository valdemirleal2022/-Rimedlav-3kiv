<?php

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$motoristaId =$_SESSION[ 'motorista' ];

$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");

$total = conta(' veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC ");
$leitura = read('  veiculo_motorista_negligencia',"WHERE id AND data>='$dataEmissao1' AND data<='$data2' ORDER BY data DESC");

if(!empty($motoristaId)){
	$total = conta(' veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motorista='$motoristaId' ORDER BY data ASC ");
	$leitura = read('  veiculo_motorista_negligencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_motorista='$motoristaId' ORDER BY data DESC");
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
	  $titulo="Relatório Negligencias";
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
 

$pdf=new RELATORIO();
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->SetX(32);
$pdf->Cell(22, 1, converteData($data1));
$pdf->SetX(54);
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->SetX(73);
$pdf->Cell(22, 1, converteData($data2));
$pdf->SetX(97);
$pdf->Cell(22, 1, 'Motorista :');
$pdf->SetX(118);
$pdf->Cell(22, 1, $motorista['nome']);


$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(22);
$pdf->Cell(20, 1, 'Motorista');

$pdf->SetX(70);
$pdf->Cell(20, 1, 'Tipo');

$pdf->SetX(90);
$pdf->Cell(20, 1, 'Rota');


$pdf->SetX(110);
$pdf->Cell(20, 1, 'Negligencia');

$pdf->SetX(215);
$pdf->Cell(20, 1, 'Pontuação');

$pdf->SetX(250);
$pdf->Cell(20, 1, 'Data');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;


$pontuacao=0;

foreach($leitura as $mostra):
	
 
		$pdf->SetFont('Arial','B',8);
		$pdf->ln();
	
		$pdf->SetX(10);
		$pdf->Cell(10, 5, $mostra['id']);

		$negligenciaId = $mostra['id_negligencia'];
		$motoristaId = $mostra['id_motorista'];
		$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId '");
		
		$pdf->SetX(22);
		$pdf->Cell(10, 5, $motorista['nome']);
		
		$tipo='';
		if($$motorista['tipo']=='1'){
				$tipo='Motorista';
		}elseif($motorista['tipo']=='2'){
				$tipo='Ajudante';
		}

		$pdf->SetX(70);
		$pdf->Cell(10, 5, $tipo);

		$rotaId = $mostra['rota'];
		$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");

		$pdf->SetX(90);
		$pdf->Cell(10, 5, $rota['nome']);


		$negligencia = mostra('veiculo_motorista_motivo_negligencia',"WHERE id ='$negligenciaId'");
		
		$pdf->SetX(110);
		$pdf->Cell(10, 5, $negligencia['nome']);

		$pdf->SetX(220);
		$pdf->Cell(10, 5, $negligencia['pontuacao']);

		$pontuacao=$pontuacao+$negligencia['pontuacao'];

		$pdf->SetX(250);
		$pdf->Cell(10, 5, converteData($mostra['data']));

		$i=$i+1;
 
	
		if ($i>28){
			$pdf->AddPage();   
			$pdf->SetFont('Arial','B',10);
			$pdf->Ln(5);

			$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

			$pdf->SetX(10);
			$pdf->Cell(20, 1, 'Id');

			$pdf->SetX(22);
			$pdf->Cell(20, 1, 'Motorista');

			$pdf->SetX(75);
			$pdf->Cell(20, 1, 'Tipo');

			$pdf->SetX(110);
			$pdf->Cell(20, 1, 'Negligencia');

			$pdf->SetX(215);
			$pdf->Cell(20, 1, 'Pontuação');

			$pdf->SetX(250);
			$pdf->Cell(20, 1, 'Data');

			$pdf->Line(10, 33, 290, 33); // insere linha divisória
			$pdf->Ln(4);
			$i=0;
		}

endforeach;

$pdf->ln(10);
$pdf->SetFont('Arial','B',8);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total da Pontuação : '. $pontuacao);
 
$pdf->SetFont('Arial','B',8);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de registros : '. $total);

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>