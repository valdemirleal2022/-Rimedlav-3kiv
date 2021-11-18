<?php


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$data1 = $_SESSION['inicio'];
$data2 = $_SESSION['fim'];

$total = conta('estoque_equipamento_retirada',"WHERE id AND data_entrega>='$data1' 
					 AND data_entrega<='$data2'");
$leitura = read('estoque_equipamento_retirada',"WHERE id AND data_entrega>='$data1' 
					 AND data_entrega<='$data2' ORDER BY data_entrega ASC");

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Retirada com Equipamento";
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
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(22, 1, converteData($data1));
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(22, 1, converteData($data2));

$pdf->Ln(5);
$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
$pdf->SetX(5);
$pdf->Cell(20, 1, 'Id');
$pdf->SetX(15);
$pdf->Cell(20, 1, 'Equipamento');
$pdf->SetX(65);
$pdf->Cell(20, 1, 'Bairro');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Endereço');
$pdf->SetX(145);
$pdf->Cell(20, 1, 'Cliente');
 
$pdf->SetX(213);
$pdf->Cell(20, 1, 'Tipo');
$pdf->SetX(222);
$pdf->Cell(20, 1, 'Quant');
 
$pdf->SetX(257);
$pdf->Cell(20, 1, 'Entrega');
 
$pdf->Line(5, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

				
	$equipamentoId = $mostra['id_equipamento'];
	$contratoId = $mostra['id_contrato'];
	$clienteId = $mostra['id_cliente'];

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $mostra['id']);

	$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
	$pdf->SetX(15);
	$pdf->Cell(10, 5,substr($equipamento['nome'],0,35));

	$pdf->SetX(65);
	$pdf->Cell(10, 5, substr($cliente['bairro'],0,15));
	
	$endereco=substr($cliente['endereco'],0,50).','.$cliente['numero'].' - '.$cliente['complemento'];
	$pdf->SetX(90);
	$pdf->Cell(10, 5, substr($endereco,0,30));

	$pdf->SetX(145);
	$pdf->Cell(10, 5, substr($cliente['nome'],0,20));
 	 
	$pdf->SetX(213);
	if($mostra['tipo'] == '1'){
		$pdf->Cell(10, 5, 'Troca');
	}elseif($mostra['tipo'] == '2'){
		$pdf->Cell(10, 5, 'Entrega');
	}elseif($mostra['tipo'] == '3'){
		$pdf->Cell(10, 5, 'Retirada');
	}else{
		$pdf->Cell(10, 5, '-');
	} 
	
	$contrato = mostra('contrato',"WHERE id ='$contratoId '");
	
	$pdf->SetX(227);
	$pdf->Cell(10, 5, $mostra['quantidade']);
 
	$pdf->SetX(257);
	$pdf->Cell(10, 5, converteData($mostra['data_entrega']));

  

	$i=$i+1;
	if ($i>45){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);
		$pdf->Line(5, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)
		$pdf->SetX(5);
		$pdf->Cell(20, 1, 'Id');
		$pdf->SetX(15);
		$pdf->Cell(20, 1, 'Equipamento');
		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Bairro');
		$pdf->SetX(90);
		$pdf->Cell(20, 1, 'Endereço');
		$pdf->SetX(155);
		$pdf->Cell(20, 1, 'Cliente');
		$pdf->SetX(213);
		$pdf->Cell(20, 1, 'Tipo');
		$pdf->SetX(222);
		$pdf->Cell(20, 1, 'Quant');
		$pdf->SetX(235);
		$pdf->Cell(20, 1, 'Solicitação');
		$pdf->SetX(257);
		$pdf->Cell(20, 1, 'Entrega');
		$pdf->SetX(275);
		$pdf->Cell(20, 1, 'Status');
		$pdf->Line(5, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
$pdf->ln(5);

$pdf->SetX(80);
$pdf->Cell(10, 5, 'Conferencia de Saida');
$pdf->SetX(155);
$pdf->Cell(10, 5, 'Conferencia de Retorno');
$pdf->ln(7);

$pdf->SetX(80);
$pdf->Cell(10, 5, 'Manutenção : _________________________ ');
$pdf->SetX(155);
$pdf->Cell(10, 5, 'Manutenção : _________________________ ');
$pdf->ln(7);

$pdf->SetX(80);
$pdf->Cell(10, 5, 'Almoxarifado : _______________________ ');
$pdf->SetX(155);
$pdf->Cell(10, 5, 'Almoxarifado : _______________________ ');
$pdf->ln(7);

$pdf->SetX(80);
$pdf->Cell(10, 5, 'Portaria : ___________________________ ');
$pdf->SetX(155);
$pdf->Cell(150, 5, 'Portaria : ___________________________ ');
$pdf->ln(7);


$pdf->SetX(110);
$pdf->Cell(10, 5, 'Assinatura da Equipe : _______________________ ');
$pdf->ln(7);
$pdf->SetX(110);
$pdf->Cell(10, 5, 'Km de Saida : _______________________ ');
$pdf->ln(7);
$pdf->SetX(110);
$pdf->Cell(10, 5, 'Km de Retorno : _______________________ ');
$pdf->ln(7);


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>