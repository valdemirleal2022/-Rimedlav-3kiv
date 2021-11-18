<?php


$data1 = $_SESSION['data1'];
$data2 = $_SESSION['data2'];

if(isset($_SESSION[ 'veiculo' ])){
	$veiculoId =$_SESSION[ 'veiculo' ];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$veiculoNome = $veiculo['modelo'].' | '.$veiculo['placa'];
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
	  $titulo="Tarcógrafo de Veículo";
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

$total = conta('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' ORDER BY data_troca ASC");
$leitura = read('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' ORDER BY data_troca ASC");

if(!empty($veiculoId)){
	$leitura = read('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' AND id_veiculo='$veiculoId' ORDER BY data_troca ASC");
	$total = conta('veiculo_tacografo',"WHERE id AND data_troca>='$data1' AND data_troca<='$data2' AND id_veiculo='$veiculoId'");
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

if(!empty($veiculoId)){
	$pdf->Cell(22, 1, 'Veículo :');
	$pdf->SetX(144);
	$pdf->Cell(22, 1, $veiculoNome);

}

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(25);
$pdf->Cell(20, 1, 'Placa');

$pdf->SetX(70);
$pdf->Cell(20, 1, 'Data Troca');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Data Prevista');

$pdf->SetX(145);
$pdf->Cell(20, 1, 'Observacao');

$pdf->SetX(220);
$pdf->Cell(20, 1, 'Status');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$veiculoId = $mostra['id_veiculo'];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
 
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $veiculo['placa']);

	$pdf->SetX(70);
	$pdf->Cell(10, 5, converteData($mostra['data_troca']));

	$pdf->SetX(100);
	$pdf->Cell(10, 5, converteData($mostra['data_prevista']) );

 	$pdf->SetX(145);
	$pdf->Cell(10, 5, $mostra['observacao']);

	if($mostra['status']==1){
		$pdf->SetX(220);
		$pdf->Cell(10, 5, 'Realizada');
    }

 
	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);

		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(25);
		$pdf->Cell(20, 1, 'Placa');

		$pdf->SetX(70);
		$pdf->Cell(20, 1, 'Data Troca');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Data Prevista');

		$pdf->SetX(145);
		$pdf->Cell(20, 1, 'Observacao');

		$pdf->SetX(220);
		$pdf->Cell(20, 1, 'Status');

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