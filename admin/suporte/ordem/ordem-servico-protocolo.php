<?php

require_once("js/fpdf/fpdf.php");

define("FPDF_FONTPATH","font/");

$dataroteiro=$_SESSION['dataInicio'];
$rotaId=$_SESSION['rotaColeta'];


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $data=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$data,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="ORDEM DE SERVIÇO";
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

$leitura = read('contrato_ordem',"WHERE id AND data='$dataroteiro' ORDER BY rota ASC, hora ASC");

if(!empty($_GET['ordemImprimir'])){
	$ordemId = $_GET['ordemImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND id='$ordemId'");
}

if(!empty($rotaId)){
	$ordemId = $_GET['ordemImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND data='$dataroteiro' AND rota='$rotaId' ORDER BY rota ASC, hora ASC");
}

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage(); 
$pdf->SetMargins(10, 10, 5, 5);
$pdf->SetFont('Arial','B',9);

$i=1;

$pdf->SetX(5);
$pdf->Cell(5, 5, '===========================================================================================================');
$pdf->ln();

$pdf->SetX(5);
$pdf->Cell(10, 5, $empresa['nome']);
$pdf->SetX(172);
$pdf->Cell(10, 5, date('d/m/Y H:i:s'));

$pdf->ln();
$pdf->SetX(5);
$pdf->Cell(5, 5, '------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->ln();

$rotaPagina='';
$total=0;

foreach($leitura as $mostra):

	$total=$total+1;

	if ($i>5){
		$pdf->AddPage();
		$i=1;
		$pdf->SetX(5);
		$pdf->Cell(5, 5, '===========================================================================================================');
		$pdf->ln();
	}

	if(empty($rotaPagina)){
		$rotaPagina=$mostra['rota'];
	}

	if($rotaPagina<>$mostra['rota']){
		$rotaPagina=$mostra['rota'];
		$total=1;

		if($i<>1){
			$pdf->AddPage();
			$i=1;
			
		}
		
		$pdf->SetX(5);
		$pdf->Cell(5, 5, '===========================================================================================================');
		$pdf->ln();
		$pdf->SetX(5);
		$pdf->Cell(10, 5, $empresa['nome']);
		$pdf->SetX(172);
		$pdf->Cell(10, 5, date('d/m/Y H:i:s'));

		$pdf->ln();
		$pdf->SetX(5);
		$pdf->Cell(5, 5, '------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
		$pdf->ln();
	}
		

	//** // 1 VIA
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetX(05);
	$pdf->Cell(10, 5, $total);
	$pdf->SetFont('Arial','B',9);

	$pdf->SetX(15);
	$pdf->Cell(10, 5, 'Contrato :');
	$pdf->SetX(35);
	$pdf->Cell(10, 5, $mostra['id_contrato']);

    $contratoId=$mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	$pdf->SetX(50);
	$pdf->Cell(10, 5, 'Início :');
	$pdf->SetX(65);
	$pdf->Cell(10, 5, converteData($contrato['inicio']));;

 	$contratoTipo=$contrato['contrato_tipo'];
	$tipoResiduo = mostra('contrato_tipo',"WHERE id ='$contratoTipo'");

	$pdf->SetX(90);
	$pdf->Cell(5, 5, 'Tipo de Resíduo  :');
	$pdf->SetX(120);
	$pdf->Cell(10, 5, $tipoResiduo['nome']);

	$pdf->SetX(165);
	$pdf->Cell(10, 5, 'Número :');
	$pdf->SetX(185);
	$pdf->Cell(10, 5, $mostra['id']);
	$pdf->ln();

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Nome :');
	$pdf->SetX(20);
	$pdf->Cell(10, 5, $cliente['nome']);

	$pdf->SetX(165);
	$pdf->Cell(10, 5, 'Data :');
	$pdf->SetX(175);
	$pdf->Cell(10, 5, converteData($mostra['data']));
	$pdf->ln();
	
	$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Endereço :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5,$endereco);

	$pdf->SetX(110);
	$pdf->Cell(10, 5, 'Bairro : '.$cliente['bairro']);
  

	$pdf->SetX(165);
	$pdf->Cell(10, 5, 'Hora :');
	$pdf->SetX(175);
	$pdf->Cell(10, 5, $mostra['hora']);

	$pdf->ln();


	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Referencia : '.$cliente['referencia']);
  

	$rota=$mostra['rota'];
	$rota = mostra('contrato_rota',"WHERE id ='$rota'");

	$pdf->SetX(165);
	$pdf->Cell(10, 5, 'Rota :');
	$pdf->SetX(175);
	$pdf->Cell(10, 5, $rota['nome']);
	$pdf->ln();
 
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(5, 5, 'Tipo de Coleta');
	$pdf->SetX(70);
	$pdf->Cell(5, 5, 'Quantidade Prevista');
	$pdf->SetX(140);
	$pdf->Cell(5, 5, 'Quantidade Coletado');
	$pdf->ln();

    $tipoColeta=$mostra['tipo_coleta1'];
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta'");

	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $tipoColeta['nome']);
	$pdf->SetX(80);
	$pdf->Cell(10, 5, $contratoColeta['quantidade']);
	$pdf->SetX(140);
	$pdf->Cell(5, 5, '_____________________________');	
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(5, 5, 'Em _____ de ______________  de __________');
	$pdf->SetX(90);
	$pdf->Cell(5, 5, '____________________________');
	$pdf->SetX(145);
	$pdf->Cell(5, 5, '____________________________');
	$pdf->ln();

	$pdf->SetX(90);
	$pdf->Cell(5, 5, 'Cliente');
	$pdf->SetX(145);
	$pdf->Cell(5, 5, 'Motorista/Coletor');
	$pdf->ln();
	
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();

	$i=$i+1;

endforeach;

ob_clean();  
$pdf->Output('ordem-contrato.pdf', 'I');
 
?>