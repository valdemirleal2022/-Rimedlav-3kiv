<?php


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$veiculo=$_SESSION['veiculo'];
$tipo=$_SESSION['tipo'];
$rota=$_SESSION['rota'];

$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data ASC");

$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' ORDER BY data DESC, hora DESC");


	if(!empty($tipo)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}
		

	if(!empty($veiculo)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_veiculo='$veiculo' ORDER BY data ASC");
	}

	if(!empty($rota)){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_rota='$rota'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' AND id_rota='$rota' ORDER BY data ASC");
	}
		
	if(!empty($tipo) && !empty($veiculo ) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_ocorrencia='$tipo' ORDER BY data ASC");
	}

	if(!empty($rota) && !empty($veiculo ) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_rota='$rota'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_rota='$rota' ORDER BY data ASC");
	}

	if(!empty($rota) && !empty($veiculo) && !empty($tipo) ){
		$total = conta('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2' 
			AND id_veiculo='$veiculo' AND id_rota='$rota' AND id_ocorrencia='$tipo'");
		$leitura = read('rota_ocorrencia',"WHERE id AND data>='$data1' AND data<='$data2'
			AND id_veiculo='$veiculo' AND id_rota='$rota' AND id_ocorrencia='$tipo' ORDER BY data ASC");
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
	  $titulo="Ocorrências da Rota";
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

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(22);
$pdf->Cell(20, 1, 'Rota');

$pdf->SetX(35);
$pdf->Cell(20, 1, 'Tipo Ocorrência');

$pdf->SetX(90);
$pdf->Cell(20, 1, 'Veículo');

$pdf->SetX(135);
$pdf->Cell(20, 1, 'Data');

$pdf->SetX(150);
$pdf->Cell(20, 1, 'Hora');

$pdf->SetX(165);
$pdf->Cell(10, 1, 'Ocorrência');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$rotaId = $mostra['id_rota'];
	$contratoRota = mostra('contrato_rota',"WHERE id ='$rotaId'");
		
	$pdf->SetX(22);
	$pdf->Cell(10, 5, $contratoRota['nome']);

	$ocorrenciaId = $mostra['id_ocorrencia'];
	$contratoOcorrencia = mostra('rota_ocorrencia_tipo',"WHERE id ='$ocorrenciaId'");

	$pdf->SetX(35);
	$pdf->Cell(10, 5, $contratoOcorrencia['nome']);

	$veiculoId = $mostra['id_veiculo'];
	$contratoVeiculo = mostra('veiculo',"WHERE id ='$veiculoId'");

	$pdf->SetX(90);
	$pdf->Cell(10, 5, $contratoVeiculo['modelo'].'|'.$contratoVeiculo['placa']);

	$pdf->SetX(135);
	$pdf->Cell(10, 5, converteData($mostra['data']));

	$pdf->SetX(150);
	$pdf->Cell(10, 5, $mostra['hora']);

	$pdf->SetX(165);
	$pdf->Cell(10, 5, substr($mostra['ocorrencia'],0,100) );

	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);

		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(22);
		$pdf->Cell(20, 1, 'Veículo');

		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Placa');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Data');

		$pdf->SetX(125);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(150);
		$pdf->Cell(10, 1, 'Descrição');

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