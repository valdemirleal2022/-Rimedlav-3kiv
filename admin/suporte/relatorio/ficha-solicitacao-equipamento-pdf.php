<?php


if(function_exists(ProtUser)){
	if(!ProtUser($_SESSION['autUser']['id'])){
		header('Location: painel.php?execute=suporte/403');	
	}	
}
		
if(!empty($_GET['retiradaId'])){
	$retiradaId = $_GET['retiradaId'];
}


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){

    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','B',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$i=0;

$equipamentoRetirada = mostra('estoque_equipamento_retirada',"WHERE id='$retiradaId'");

$contratoId = $equipamentoRetirada['id_contrato'];
$contrato = mostra('contrato',"WHERE id = '$contratoId'");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);


$empresa = mostra('empresa',"WHERE id");

$clienteId = $contrato['id_cliente'];
$cliente = mostra('cliente',"WHERE id ='$clienteId '");

$pdf->SetX(5);
	$pdf->Cell(5, 5, '===============================================================================================');
	$pdf->ln();
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(165);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s'));
    $pdf->ln();
 	$pdf->SetFont('Arial','B',10);

	$pdf->Image("ico/header-logo.png", 06, 18, 30, 15,'PNG');
	 

	$pdf->SetX(40);
	$pdf->Cell(10, 5, $empresa['nome']);
    $pdf->ln();

	$pdf->SetX(40);
	$pdf->Cell(10, 5, $empresa['endereco']);
	$pdf->SetX(105);
	$pdf->Cell(10, 5, $empresa['bairro']);
	$pdf->SetX(135);
	$pdf->Cell(10, 5, $empresa['cidade']);
    $pdf->ln();

	$pdf->SetX(40);
	$pdf->Cell(10, 5, $empresa['cnpj']);

	
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '==============================================================================================');
	$pdf->ln();


$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Cliente :');
$pdf->SetX(27);
$pdf->Cell(22, 1, $cliente['nome']);

$statusId=$contrato['status'];
$status = mostra('contrato_status',"WHERE id ='$statusId'");
$pdf->SetX(150);
$pdf->Cell(22, 1, 'Status :');
$pdf->SetX(165);
$pdf->Cell(22, 1, $status['nome']);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Email :');
$pdf->SetX(27);
$pdf->Cell(22, 1, $cliente['email']);

$pdf->ln(5);

$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Endereço :');
$pdf->SetX(30);
$pdf->Cell(22, 1, $endereco);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Bairro :');
$pdf->SetX(25);
$pdf->Cell(22, 1, $cliente['bairro']);

$pdf->SetX(100);
$pdf->Cell(22, 1, 'Cep :');
$pdf->SetX(112);
$pdf->Cell(22, 1, $cliente['cep']);

$pdf->SetX(147);
$pdf->Cell(22, 1, 'Cidade :');
$pdf->SetX(170);
$pdf->Cell(22, 1, $cliente['cidade']);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Telefone :');
$pdf->SetX(30);
$pdf->Cell(22, 1, $cliente['telefone']);

$pdf->SetX(80);
$pdf->Cell(22, 1, 'Celular :');
$pdf->SetX(100);
$pdf->Cell(22, 1, $cliente['celular']);

$pdf->SetX(140);
$pdf->Cell(22, 1, 'Contato :');
$pdf->SetX(160);
$pdf->Cell(22, 1, $cliente['contato']);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Contrato Id :');
$pdf->SetX(35);
$pdf->Cell(22, 1, $contrato['id']);

$pdf->SetX(60);
$pdf->Cell(22, 1, 'Controle :');
$pdf->SetX(80);
$pdf->Cell(22, 1, $contrato['controle']);


$consultorId=$contrato['consultor'];
$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
$pdf->SetX(130);
$pdf->Cell(22, 1, 'Consultor :');
$pdf->SetX(150);
$pdf->Cell(22, 1, $consultor['nome']);

$pdf->ln(5);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Referência :');
$pdf->SetX(34);
$pdf->Cell(22, 1, $cliente['referencia']);

$pdf->ln(5);
$pdf->SetX(10);
$pdf->Cell(5, 5, '________________________________________________________________________________________________');
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Id :');
$pdf->SetX(22);
$pdf->Cell(22, 1, $equipamentoRetirada['id']);

$pdf->SetX(40);
$pdf->Cell(22, 1, 'Data da Solicitação :');
$pdf->SetX(78);
$pdf->Cell(22, 1, converteData($equipamentoRetirada['data_solicitacao']));

$pdf->SetX(110);
$pdf->Cell(22, 1, 'Data da Entrega :');
$pdf->SetX(142);
$pdf->Cell(22, 1, converteData($equipamentoRetirada['data_entrega']));

$pdf->SetX(170);
$pdf->Cell(22, 1, 'Tipo :');

$pdf->SetX(180);
if($equipamentoRetirada['tipo'] == '1'){
	$pdf->Cell(22, 1, 'Troca');
}
if($equipamentoRetirada['tipo'] == '2'){
	$pdf->Cell(22, 1, 'Entrega');
}
if($equipamentoRetirada['tipo'] == '3'){
	$pdf->Cell(22, 1, 'Retirada');
}
if($equipamentoRetirada['tipo'] == '4'){
	$pdf->Cell(22, 1, 'Manutenção');
}

$pdf->ln(10);

$equipamentoId=$equipamentoRetirada['id_equipamento'];
$equipamento = mostra('estoque_equipamento',"WHERE id ='$equipamentoId'");
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Equipamento :');
$pdf->SetX(40);
$pdf->Cell(22, 1, $equipamento['nome']);

$pdf->SetX(110);
$pdf->Cell(22, 1, 'Quantidade :');
$pdf->SetX(140);
$pdf->Cell(22, 1, $equipamentoRetirada['quantidade']);


$pdf->ln(5);
$pdf->SetX(10);
$pdf->Cell(5, 5, '________________________________________________________________________________________________');
$pdf->ln(10);

$pdf->ln(10);
$pdf->SetX(10);
$pdf->Cell(5, 5, '________________________________________________________________________________________________');
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Declaro que as informações acima prestadas são verdadeiras, e assumo a inteira responsabilidade pelas mesmas.');
$pdf->ln(15);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Assinatura do Cliente : ____________________________________');
//$pdf->SetX(140);
//$pdf->Cell(22, 1, 'Data : ______/______/________');

$pdf->ln(15);
$pdf->SetX(10);
$pdf->Cell(5, 5, '________________________________________________________________________________________________');
$pdf->ln(10);

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');



 
?>