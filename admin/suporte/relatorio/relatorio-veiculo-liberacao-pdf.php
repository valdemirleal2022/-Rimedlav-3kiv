<?php

$rota='';
$dataEmissao1 = $_SESSION['dataEmissao1'];
$dataEmissao2 = $_SESSION['dataEmissao2'];

if(isset($_SESSION[ 'rotaId' ])){
	$rotaId =$_SESSION[ 'rotaId' ];
	$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
	$rotaNome = $rota['nome'];
}
if(isset($_SESSION[ 'veiculoId' ])){
	$veiculoId =$_SESSION[ 'veiculoId' ];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$veiculoNome = $veiculo['modelo'].' | '.$veiculo['placa'];
}

if(isset($_SESSION[ 'aterroId' ])){
	$aterroId =$_SESSION[ 'aterroId' ];
	$aterro = mostra('aterro',"WHERE id ='$aterroId'");
	$aterroNome = $aterro['nome'];
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
	  $titulo="Liberação de Veículo";
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

	$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' ORDER BY saida ASC");

	$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' ORDER BY saida ASC");


	if(!empty($rotaId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' ORDER BY saida ASC");
		$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' ORDER BY saida ASC");
	}
	if(!empty($rotaId) && !empty($veiculoId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' ORDER BY saida ASC");
		$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' ORDER BY saida ASC");
	}
	if(!empty($rotaId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND aterro='$aterroId' ORDER BY saida ASC");
		$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId'AND aterro='$aterroId' ORDER BY saida ASC");
	}
	if(!empty($rotaId) && !empty($veiculoId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida ASC");
		$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND rota='$rotaId' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida ASC");
	}
	if(!empty($veiculoId) && !empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida ASC");
		$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND veiculo='$veiculoId' AND aterro='$aterroId' ORDER BY saida ASC");
		
	}
	if(!empty($aterroId)){
		$leitura = read('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND aterro='$aterroId' ORDER BY saida ASC");
		$total = conta('veiculo_liberacao',"WHERE id AND saida>='$dataEmissao1' AND saida<='$dataEmissao2' AND aterro='$aterroId' ORDER BY saida ASC");
	}

$pdf=new RELATORIO();
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->SetX(32);
$pdf->Cell(22, 1, converteData($dataEmissao1));
$pdf->SetX(54);
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->SetX(73);
$pdf->Cell(22, 1, converteData($dataEmissao2));
$pdf->SetX(97);
$pdf->Cell(22, 1, 'Rota :');
$pdf->SetX(108);
$pdf->Cell(22, 1, $rotaNome);
$pdf->SetX(128);
$pdf->Cell(22, 1, 'Veículo :');
$pdf->SetX(144);
$pdf->Cell(22, 1, $veiculoNome);
$pdf->SetX(198);
$pdf->Cell(22, 1, 'Aterro :');
$pdf->SetX(213);
$pdf->Cell(22, 1, $aterroNome);



$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(22);
$pdf->Cell(20, 1, 'Placa');

$pdf->SetX(40);
$pdf->Cell(20, 1, 'Saida/Hora');

$pdf->SetX(68);
$pdf->Cell(20, 1, 'Chegada/Hora');

$pdf->SetX(96);
$pdf->Cell(10, 1, 'Pesagem');

$pdf->SetX(115);
$pdf->Cell(10, 1, 'Rota');

$pdf->SetX(128);
$pdf->Cell(10, 1, 'Km');

$pdf->SetX(136);
$pdf->Cell(10, 1, 'Aterro');

$pdf->SetX(165);
$pdf->Cell(10, 1, 'Prev');

$pdf->SetX(174);
$pdf->Cell(10, 1, 'Real.');

$pdf->SetX(185);
$pdf->Cell(10, 1, 'Motorista');

$pdf->SetX(210);
$pdf->Cell(10, 1, 'Ajudante 1');

$pdf->SetX(235);
$pdf->Cell(10, 1, 'Ajudante 2');

$pdf->SetX(261);
$pdf->Cell(10, 1, 'H Trab');

$pdf->SetX(274);
$pdf->Cell(10, 1, 'Status');

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
	//$pdf->SetX(22);
//	$pdf->Cell(10, 5, $veiculo['modelo']);

	$pdf->SetX(22);
	$pdf->Cell(10, 5, $veiculo['placa']);

	$pdf->SetX(40);
	$pdf->Cell(10, 5, converteData($mostra['saida']).' | '. $mostra['saida_hora']);

	$pdf->SetX(68);
	$pdf->Cell(10, 5, converteData($mostra['chegada']).' | '. $mostra['chegada_hora']);
	
	$pdf->SetX(100);
	$pdf->Cell(10, 5, $mostra['pesagem'],0,0,'R');

	$rotaId = $mostra['rota'];
	$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
	$pdf->SetX(115);
	$pdf->Cell(10, 5, $rota['nome']);

	$dataroteiro=$mostra['saida'];

	$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND rota='$rotaId'" );
	$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND rota='$rotaId' AND status='13'" );

	$KM=$mostra['km_chegada']-$mostra['km_saida'];
		
	$pdf->SetX(125);
	$pdf->Cell(10, 5, $KM,0,0,'R');

	$aterroId = $mostra['rota'];
	$aterro = mostra('aterro',"WHERE id ='$aterroId'");
	$pdf->SetX(136);
	$pdf->Cell(10, 5, substr($aterro['nome'],0,15));

	$pdf->SetX(167);
	$pdf->Cell(10, 5, $ordemEmaberto);

	$pdf->SetX(175);
	$pdf->Cell(10, 5, $ordemRealizada );


	$motoristaId = $mostra['motorista'];
	$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
	$pdf->SetX(185);
	$pdf->Cell(10, 5,substr($motorista['nome'],0,12) );
		
	$motoristaId = $mostra['ajudante1'];
	$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
	$pdf->SetX(210);
	$pdf->Cell(10, 5,substr($motorista['nome'],0,12) );
		
	$motoristaId = $mostra['ajudante2'];
	$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
	$pdf->SetX(235);
	$pdf->Cell(10, 5,substr($motorista['nome'],0,12) );

	$horas = (int) ($mostra['horas_trabalhadas']/60);
	$minutos = $mostra['horas_trabalhadas']- ($horas*60) ;
	$totalHoras = $horas.'h ' . $minutos.'min';
	$pdf->SetX(261);
	$pdf->Cell(10, 5, $totalHoras);	

	$pdf->SetX(274);
	$pdf->Cell(10, 5, $mostra['status']);


	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);

		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(22);
		$pdf->Cell(20, 1, 'Placa');

		$pdf->SetX(40);
		$pdf->Cell(20, 1, 'Saida/Hora');

		$pdf->SetX(68);
		$pdf->Cell(20, 1, 'Chegada/Hora');

		$pdf->SetX(96);
		$pdf->Cell(10, 1, 'Pesagem');

		$pdf->SetX(115);
		$pdf->Cell(10, 1, 'Rota');

		$pdf->SetX(128);
		$pdf->Cell(10, 1, 'Km');

		$pdf->SetX(136);
		$pdf->Cell(10, 1, 'Aterro');

		$pdf->SetX(165);
		$pdf->Cell(10, 1, 'Prev');

		$pdf->SetX(174);
		$pdf->Cell(10, 1, 'Real.');

		$pdf->SetX(185);
		$pdf->Cell(10, 1, 'Motorista');

		$pdf->SetX(210);
		$pdf->Cell(10, 1, 'Ajudante 1');

		$pdf->SetX(235);
		$pdf->Cell(10, 1, 'Ajudante 2');

		$pdf->SetX(261);
		$pdf->Cell(10, 1, 'H Trab');

		$pdf->SetX(274);
		$pdf->Cell(10, 1, 'Status');

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