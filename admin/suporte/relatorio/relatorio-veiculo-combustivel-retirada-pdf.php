<?php


$dataInicio = $_SESSION['dataInicio'];
$dataFinal = $_SESSION['dataFinal'];

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
	  $titulo="Abastecimento de Veículo (Interno)";
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

$total = conta('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' ORDER BY data ASC");
$leitura = read('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' ORDER BY data ASC");

if(!empty($veiculoId)){
	$leitura = read('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' AND id_veiculo='$veiculoId' ORDER BY data ASC");
	$total = conta('veiculo_combustivel_retirada',"WHERE id AND data>='$dataInicio' AND data<='$dataFinal' AND id_veiculo='$veiculoId'");
}

$pdf=new RELATORIO();
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->SetX(32);
$pdf->Cell(22, 1, converteData($dataInicio));
$pdf->SetX(54);
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->SetX(73);
$pdf->Cell(22, 1, converteData($dataFinal));

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
$pdf->Cell(20, 1, 'Data');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Combustível');

$pdf->SetX(140);
$pdf->Cell(20, 1, 'Quantidade');

$pdf->SetX(180);
$pdf->Cell(10, 1, 'Km');

$pdf->SetX(200);
$pdf->Cell(10, 1, 'Km Percorrido');

$pdf->SetX(230);
$pdf->Cell(10, 1, 'Média');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

$totalAbastecimento=0;
$kminicio='';


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
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$combustivelId = $mostra['id_combustivel'];
	$veiculoCombustivel = mostra('veiculo_combustivel',"WHERE id ='$combustivelId'");
	$pdf->SetX(100);
	$pdf->Cell(10, 5, $veiculoCombustivel['nome']);

	$pdf->SetX(140);
	$pdf->Cell(10, 5, $mostra['quantidade'],0,0,'R');

	$pdf->SetX(180);
	$pdf->Cell(10, 5, $mostra['km']);

	$pdf->SetX(210);
	$pdf->Cell(10, 5, $mostra['km_percorrido']);
	
	$pdf->SetX(230);
	$pdf->Cell(10, 5, $mostra['media']);

	if(empty($kminicio)){
		$kminicio=$mostra['km'];
	}
	 
	$ultimoAbastecimento=$mostra['quantidade'];
	$ultimokm=$mostra['km'];
	$totalAbastecimento=$totalAbastecimento+$mostra['quantidade'];

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);

		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(25);
		$pdf->Cell(20, 1, 'Placa');

		$pdf->SetX(70);
		$pdf->Cell(20, 1, 'Data');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Combustível');

		$pdf->SetX(140);
		$pdf->Cell(20, 1, 'Quantidade');

		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Km');

		$pdf->SetX(200);
		$pdf->Cell(10, 1, 'Km Percorrido');

		$pdf->SetX(230);
		$pdf->Cell(10, 1, 'Média');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		
		$pdf->Ln(4);
		$i=0;
	}

endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de Abascimentos : '. $totalAbastecimento);
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total de Km : '. ($ultimokm-$kminicio) );

$abastecimento = $totalAbastecimento-$ultimoAbastecimento;
$mediaKm = ($ultimokm-$kminicio)/$totalAbastecimento;
	
$pdf->ln(5);
$pdf->Cell(10, 5, 'Media por Litros : '.  number_format($mediaKm, 2, '.', '') );

$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>