<?php

$dataEmissao1 = $_SESSION['dataEmissao1'];
$dataEmissao2 = $_SESSION['dataEmissao2'];

$parcial=$_SESSION['parcial'];

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
	  $titulo="Relatório Motorista/AJudante";
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

$i=1;


$leituraMotorista = read('veiculo_motorista',"WHERE id ORDER BY nome");
			
if ($parcial=="checked='CHECKED'") {
	$leituraMotorista = read('veiculo_motorista',"WHERE id AND parcial='1' ORDER BY nome");
}

foreach($leituraMotorista as $mostraMotorista):

	$motoristaId =$mostraMotorista[ 'id' ];

	$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND id_veiculo<>'72' ORDER BY saida ASC");

	if ($i>2){
		$pdf->AddPage();
		$i=1;
	}

	$totalMinutos=0;
	$totalSaldo=0;
 	 
	$pdf->SetX(10);
	$pdf->Cell(22, 1, 'Data Inicio :');
	$pdf->SetX(32);
	$pdf->Cell(22, 1, converteData($dataEmissao1));
	$pdf->SetX(54);
	$pdf->Cell(22, 1, 'Data Fim :');
	$pdf->SetX(73);
	$pdf->Cell(22, 1, converteData($dataEmissao2));
	$pdf->SetX(97);
	$pdf->Cell(22, 1, 'Motorista :');
	$pdf->SetX(118);
	$pdf->Cell(22, 1, $mostraMotorista['nome']);


	$pdf->Ln(5);
	$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

	$pdf->SetX(10);
	$pdf->Cell(20, 1, 'Id');

	$pdf->SetX(22);
	$pdf->Cell(20, 1, 'Veículo');

	$pdf->SetX(55);
	$pdf->Cell(20, 1, 'Placa');

	$pdf->SetX(75);
	$pdf->Cell(20, 1, 'Saida');

	$pdf->SetX(92);
	$pdf->Cell(20, 1, 'Hora');

	$pdf->SetX(107);
	$pdf->Cell(20, 1, 'Chegada');

	$pdf->SetX(124);
	$pdf->Cell(10, 1, 'Hora');

	$pdf->SetX(135);
	$pdf->Cell(10, 1, 'Rota');

	$pdf->SetX(145);
	$pdf->Cell(10, 1, 'Tot Min');

	$pdf->SetX(160);
	$pdf->Cell(10, 1, 'H Trab');

	$pdf->SetX(180);
	$pdf->Cell(10, 1, 'Saldo');

	$pdf->SetX(225);
	$pdf->Cell(10, 1, 'Motorista');

	$pdf->SetX(275);
	$pdf->Cell(10, 1, 'Status');

	$pdf->Line(10, 33, 290, 33); // insere linha divisória
	$pdf->Ln(4);
	$i=0;

	$total=0;

	foreach($leitura as $mostra):

		$listar='NAO';
		$nomeMotorista='';
		if($motoristaId==$mostra['motorista']){
			$listar='SIM';
			$nomeMotorista='';
		}
		if($motoristaId==$mostra['ajudante1']){
			$listar='SIM';
			$nomeMotorista='';
		}
		if($motoristaId==$mostra['ajudante2']){
			$listar='SIM';
			$nomeMotorista='';
		}
		
		if($mostra['status']=='2'){
			$listar='NAO';
		}
		if($mostra['status']=='3'){
			$listar='NAO';
		}

		if($listar=='SIM'){

			$pdf->SetFont('Arial','B',8);
			$pdf->ln();

			$pdf->SetX(10);
			$pdf->Cell(10, 5, $mostra['id']);

			$veiculoId = $mostra['id_veiculo'];
			$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
			$pdf->SetX(22);
			$pdf->Cell(10, 5, $veiculo['modelo']);

			$pdf->SetX(55);
			$pdf->Cell(10, 5, $veiculo['placa']);

			$pdf->SetX(75);
			$pdf->Cell(10, 5, converteData($mostra['saida']));

			$pdf->SetX(92);
			$pdf->Cell(10, 5, $mostra['saida_hora']);

			$pdf->SetX(107);
			$pdf->Cell(10, 5, converteData($mostra['chegada']));

			$pdf->SetX(124);
			$pdf->Cell(10, 5, $mostra['chegada_hora']);

			$rotaId = $mostra['rota'];
			$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
			$pdf->SetX(136);
			$pdf->Cell(10, 5, $rota['nome']);

			$pdf->SetX(146);
			$pdf->Cell(10, 5, $mostra['horas_trabalhadas'],0,0,'R');		

			$horas = (int) ($mostra['horas_trabalhadas']/60);
			$minutos = $mostra['horas_trabalhadas'] - ($horas*60) ;
			$totalHoras = $horas.'h ' . $minutos.'min';
			$pdf->SetX(164);
			$pdf->Cell(10, 5, $totalHoras,0,0,'R');		

			$saldo =$mostra['horas_trabalhadas']-588;

			$pdf->SetX(180);
			$pdf->Cell(10, 5, $saldo,0,0,'R');	

			$totalMinutos = $totalMinutos + $mostra['horas_trabalhadas'];
			$totalSaldo = $totalSaldo + $saldo;

			$pdf->SetX(225);
			$pdf->Cell(10, 5, $motorista['nome']);

			$pdf->SetX(275);
			$pdf->Cell(10, 5, $mostra['status']);

			$total=$total+1;
			$i=$i+1;
		}


			if ($i>28){
				$pdf->AddPage();   
				$pdf->SetFont('Arial','B',10);
				$pdf->Ln(5);

				$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

				$pdf->SetX(10);
				$pdf->Cell(20, 1, 'Id');

				$pdf->SetX(22);
				$pdf->Cell(20, 1, 'Veículo');

				$pdf->SetX(55);
				$pdf->Cell(20, 1, 'Placa');

				$pdf->SetX(75);
				$pdf->Cell(20, 1, 'Saida');

				$pdf->SetX(92);
				$pdf->Cell(20, 1, 'Hora');

				$pdf->SetX(107);
				$pdf->Cell(20, 1, 'Chegada');

				$pdf->SetX(124);
				$pdf->Cell(10, 1, 'Hora');

				$pdf->SetX(135);
				$pdf->Cell(10, 1, 'Rota');

				$pdf->SetX(145);
				$pdf->Cell(10, 1, 'Tot Min');

				$pdf->SetX(160);
				$pdf->Cell(10, 1, 'H Trab');

				$pdf->SetX(180);
				$pdf->Cell(10, 1, 'Saldo');

				$pdf->SetX(215);
				$pdf->Cell(10, 1, 'Motorista');

				$pdf->SetX(275);
				$pdf->Cell(10, 1, 'Status');

				$pdf->Line(10, 33, 290, 33); // insere linha divisória
				$pdf->Ln(4);
				$i=0;
			}

	endforeach;

	$pdf->ln(4);
	$pdf->SetX(146);
	$pdf->Cell(10, 5, $totalMinutos,0,0,'R');	
	$pdf->SetX(180);
	$pdf->Cell(10, 5, $totalSaldo,0,0,'R');	

	$pdf->SetFont('Arial','B',8);
	$pdf->ln(10);
	$pdf->Cell(10, 5, 'Total de registros : '. $total);

endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>