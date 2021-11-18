<?php


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
 

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
	  $titulo="Relatorio de Acidentes";
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

$total = conta('funcionario_acidente',"WHERE id AND data>='$data1' AND data<='$data2' 
ORDER BY data ASC ");
$leitura = read('funcionario_acidente',"WHERE id AND data>='$data1' AND data<='$data2'
ORDER BY data DESC");

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

$pdf->SetX(20);
$pdf->Cell(20, 1, 'Funcionário');

$pdf->SetX(50);
$pdf->Cell(20, 1, 'Veículo');

$pdf->SetX(70);
$pdf->Cell(20, 1, 'Tipo');

$pdf->SetX(90);
$pdf->Cell(20, 1, 'Data');

$pdf->SetX(110);
$pdf->Cell(20, 1, 'Local');

$pdf->SetX(155);
$pdf->Cell(20, 1, 'Agente');

$pdf->SetX(210);
$pdf->Cell(20, 1, 'Status');

$pdf->SetX(230);
$pdf->Cell(20, 1, 'Seguradora');

$pdf->SetX(260);
$pdf->Cell(20, 1, 'Status do Seguro');



$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$funcionarioId = $mostra['id_funcionario'];
			
	$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId '");
	
	$pdf->SetX(20);
	$pdf->Cell(10, 5,substr($funcionario['nome'],0,15));
	 	
	$veiculoId = $mostra['id_veiculo'];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId '");

	$pdf->SetX(50);
	$pdf->Cell(10, 5,$veiculo['placa']);
	
	$tipoId = $mostra['tipo_acidente'];
	$tipo = mostra('funcionario_acidente_tipo',"WHERE id ='$tipoId'");
	$pdf->SetX(70);
	$pdf->Cell(10, 5,$tipo['nome']);

	$pdf->SetX(90);
	$pdf->Cell(10, 5,converteData($mostra['data']));

	$pdf->SetX(110);
	$pdf->Cell(10, 5,substr($mostra['local'],0,25));

		$pdf->SetX(155);
		$pdf->Cell(10, 5,substr($mostra['agente_causador'],0,25));

		if($mostra['status']=='1'){
			$pdf->SetX(210);
			$pdf->Cell(10, 5,'Advertência');
		}else if($mostra['status']=='2'){
			$pdf->SetX(210);
			$pdf->Cell(10, 5,'NA');
		}else if($mostra['status']=='2'){
			$pdf->SetX(210);
			$pdf->Cell(10, 5,'-');
			
		}
   	 	
		if($mostra['acionou_seguradora']=='1'){
			$pdf->SetX(230);
			$pdf->Cell(10, 5,'Sim');
		}else if($mostra['acionou_seguradora']=='2'){
			$pdf->SetX(230);
			$pdf->Cell(10, 5,'Não');
		}else if($mostra['acionou_seguradora']=='2'){
			$pdf->SetX(230);
			$pdf->Cell(10, 5,'-');
			
		}

		if($mostra['status_seguradora']=='1'){
			$pdf->SetX(260);
			$pdf->Cell(10, 5,'Em Andamento');
		}else if($mostra['status_seguradora']=='2'){
			$pdf->SetX(260);
			$pdf->Cell(10, 5,'Concluído');
		}else{
			$pdf->SetX(260);
			$pdf->Cell(10, 5,'Não');
		}


	
  
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
		$pdf->Cell(20, 1, 'Data Troca');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Data Prevista');

		$pdf->SetX(145);
		$pdf->Cell(20, 1, 'Observacao');

		$pdf->SetX(250);
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