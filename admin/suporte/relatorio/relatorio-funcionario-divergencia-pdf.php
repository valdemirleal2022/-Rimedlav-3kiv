<?php


$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];
$divergencia = $_SESSION[ 'divergencia' ];
$status = $_SESSION[ 'status' ];

	$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao ASC ");

	$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao DESC, hora_solicitacao DESC");
 
	if(!empty($status)){
		$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status'");
		$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND status='$status' ORDER BY data_solicitacao ASC");
	}
		

	if(!empty($divergencia)){
		$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_divergencia='$divergencia'");
		$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_divergencia='$divergencia' ORDER BY data_solicitacao ASC");
	}
		
	if(!empty($status) && !empty($divergencia ) ){
		$total = conta('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' 
			AND status='$status' AND id_divergencia='$divergencia'");
		$leitura = read('funcionario_divergencia',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2'
			AND status='$status' AND id_divergencia='$divergencia' ORDER BY data_solicitacao ASC");
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
	  $titulo="Relatorio de Divergencias";
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

$pdf->SetX(20);
$pdf->Cell(20, 1, 'Funcionário');

$pdf->SetX(50);
$pdf->Cell(20, 1, 'Divergencia');

$pdf->SetX(80);
$pdf->Cell(20, 1, 'Data');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Solicitacao');

$pdf->SetX(230);
$pdf->Cell(20, 1, 'Status');
 
$pdf->SetX(250);
$pdf->Cell(20, 1, 'Solução');
 
$pdf->SetX(270);
$pdf->Cell(20, 1, 'Tipo');
 
$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$funcionarioId = $mostra['id_funcionario'];
	$divergenciaId = $mostra['id_divergencia'];
			
	$funcionario = mostra('funcionario',"WHERE id ='$funcionarioId '");
	
	$pdf->SetX(20);
	$pdf->Cell(10, 5,substr($funcionario['nome'],0,15));
	 	
	$divergencia = mostra('funcionario_divergencia_motivo',"WHERE id ='$divergenciaId'");
 
	$pdf->SetX(50);
	$pdf->Cell(10, 5,$divergencia['nome']);
	 
	$pdf->SetX(80);
	$pdf->Cell(10, 5,converteData($mostra['data_solicitacao']));
 
	$pdf->SetX(100);
	$pdf->Cell(10, 5,substr($mostra['solicitacao'],0,75));

	$pdf->SetX(230);
	$pdf->Cell(10, 5,$mostra['status']);

	$pdf->SetX(250);
	$pdf->Cell(10, 5,substr($mostra['solucao'],0,25));

	if($mostra['procedente']=='1'){
			$pdf->SetX(270);
			$pdf->Cell(10, 5,'Procedente');
		}elseif($mostra['procedente']=='0'){
			$pdf->SetX(270);
			$pdf->Cell(10, 5,'Improcedente');
		}elseif($mostra['procedente']=='2'){
			$pdf->SetX(270);
			$pdf->Cell(10, 5,'Pagamento Extra');
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