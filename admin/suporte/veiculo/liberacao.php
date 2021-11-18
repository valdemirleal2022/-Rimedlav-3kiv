<?php

require_once("js/fpdf/fpdf.php");

define("FPDF_FONTPATH","font/");

$dataroteiro=$_SESSION['dataEmissao1'];


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $data=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$data,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Liberação do Veículo";
      $this->Ln(8);
	  $this->Cell(0,5,$titulo,0,0,'C'); 
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','I',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$empresa = mostra('empresa',"WHERE id");
$empresa = mostra('empresa',"WHERE id ORDER BY id DESC");

//$leitura = read('veiculo_liberacao',"WHERE id AND saida='$dataEmissao1' ORDER BY saida ASC, rota ASC");

if(!empty($_GET['liberacaoImprimir'])){
	$liberacaoId = $_GET['liberacaoImprimir'];
	$leitura=read('veiculo_liberacao',"WHERE id AND id='$liberacaoId'");
}


$pdf= new fpdf("P","mm","Letter");
$pdf->AddPage();
$pdf->SetMargins(10, 10, 5, 5);
$pdf->SetFont('Arial','B',9);

$i=1;

foreach($leitura as $liberacao):

	if ($i>2){
		$pdf->AddPage();
		$i=1;
	}


	$pdf->SetX(5);
	$pdf->Cell(5, 5, '==============================================================================================================');
	$pdf->ln();
	$pdf->SetX(175);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s'));
    

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $empresa['nome']);
    $pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $empresa['endereco']);
	$pdf->SetX(70);
	$pdf->Cell(10, 5, $empresa['bairro']);
	$pdf->SetX(100);
	$pdf->Cell(10, 5, $empresa['cidade']);

    $pdf->ln();
	$pdf->SetX(5);
	$pdf->Cell(10, 5, $empresa['telefone']);

	$pdf->ln();

	$pdf->SetFont('Arial','B',14);
	$pdf->SetX(85);
	$pdf->Cell(10, 5, 'Liberação de Veículo');

	
	$pdf->ln();
	$pdf->SetX(160);
	$pdf->Cell(10, 5, 'Numero :');
	$pdf->SetX(185);
	$pdf->Cell(10, 5, $liberacao['id']);

	$pdf->SetFont('Arial','B',9);
	$pdf->ln();
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '==============================================================================================================');

	$pdf->ln();
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Data :');
	$pdf->SetX(20);
	$pdf->Cell(10, 5, converteData($liberacao['saida']));
	$motoristaId = $liberacao['motorista'];
	$motorista= mostra('veiculo_motorista',"WHERE id ='$motoristaId '");

	$veiculoId=$liberacao['id_veiculo'];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");

	$pdf->ln();
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Placa :');
	$pdf->SetX(20);
	$pdf->Cell(10, 5, $veiculo['placa']);
	$pdf->SetX(60);
	$pdf->Cell(10, 5, 'Veículo :');
	$pdf->SetX(74);
	$pdf->Cell(10, 5, $veiculo['modelo']);

	$pdf->ln();
 	$aterroId=$liberacao['aterro'];
	$aterro = mostra('aterro',"WHERE id ='$aterroId'");
	$pdf->SetX(05);
	$pdf->Cell(5, 5, 'Aterro :');
	$pdf->SetX(20);
	$pdf->Cell(10, 5, $aterro['nome']);

	$rota=$liberacao['rota'];
	$rota = mostra('contrato_rota',"WHERE id ='$rota'");

	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Rota :');
	$pdf->SetX(153);
	$pdf->Cell(10, 5, $rota['nome']);
	
	$pdf->ln();
	$pdf->SetX(30);
	$pdf->Cell(5, 5, '______________________________________________________________________________________');
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(30);
	$pdf->Cell(5, 5, '______________________________________________________________________________________');
	$pdf->ln();
	$pdf->ln();
	
	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'Carimbo e Assinatura do Mostorista');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(80);
	$pdf->Cell(10, 5, '________________________________');

	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Km Saída : ________________');
	$pdf->SetX(70);
	$pdf->Cell(10, 5, 'Km Chegada : ____________________');
	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Abastecimento ____________________');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Ocorrência : _________________________________________________________');
	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Assinatura Manutenção________________');

	$pdf->ln();
	$pdf->ln();
	$pdf->ln();

	
	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'TESTE DE BAFÔMETRO');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'TESTE DE SAIDA : ( )  NEGATIVO ( ) POSITIVO - PERCENTUAL _________ ASSINATURA : _________________)');
	$pdf->SetX(80);
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'TESTE DE RETORNO : ( )  NEGATIVO ( ) POSITIVO - PERCENTUAL _________ ASSINATURA : _________________)');
	$pdf->SetX(80);
	$pdf->ln();
	$pdf->ln();
	
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'COORDENADOR : _________________________');



	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
 
	$pdf->SetX(100);
	$pdf->Cell(10, 5, 'SAIDA');

	$pdf->SetX(150);
	$pdf->Cell(10, 5, 'RETORNO');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'ITENS');
	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'Coordenador');
	$pdf->SetX(110);
	$pdf->Cell(10, 5, 'Motorista');

	$pdf->SetX(140);
	$pdf->Cell(10, 5, 'Coordenador');
	$pdf->SetX(170);
	$pdf->Cell(10, 5, 'Motorista');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Kit Veicular');
	$pdf->SetX(80);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(110);
	$pdf->Cell(10, 5, '_____________');

	$pdf->SetX(140);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(170);
	$pdf->Cell(10, 5, '_____________');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Tablet');	
	$pdf->SetX(80);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(110);
	$pdf->Cell(10, 5, '_____________');

	$pdf->SetX(140);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(170);
	$pdf->Cell(10, 5, '_____________');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Manifesto');
	$pdf->SetX(80);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(110);
	$pdf->Cell(10, 5, '_____________');

	$pdf->SetX(140);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(170);
	$pdf->Cell(10, 5, '_____________');
	$pdf->ln();
	$pdf->ln();
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Protocolo OSs');
	$pdf->SetX(80);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(110);
	$pdf->Cell(10, 5, '_____________');

	$pdf->SetX(140);
	$pdf->Cell(10, 5, '_____________');
	$pdf->SetX(170);
	$pdf->Cell(10, 5, '_____________');

	$i=$i+1;

endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');

 
?>