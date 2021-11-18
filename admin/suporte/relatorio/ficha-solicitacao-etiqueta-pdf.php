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
	  $titulo=SITENOME;
      $this->SetFont('Arial','B',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','B',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Solicitação de etiqueta";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
	  $this->Line(10, 20, 200, 20); // insere linha divisória
      $this->Ln(10);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','B',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$i=0;

$etiquetaRetirada = mostra('estoque_etiqueta_retirada',"WHERE id='$retiradaId'");

$contratoId = $etiquetaRetirada['id_contrato'];
$contrato = mostra('contrato',"WHERE id = '$contratoId'");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$clienteId = $contrato['id_cliente'];
$cliente = mostra('cliente',"WHERE id ='$clienteId '");

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

$pdf->ln(10);
$pdf->SetX(10);
$pdf->Cell(5, 5, '________________________________________________________________________________________________');
$pdf->ln(10);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Id :');
$pdf->SetX(25);
$pdf->Cell(22, 1, $etiquetaRetirada['id']);

$pdf->SetX(40);
$pdf->Cell(22, 1, 'Data da Solicitação :');
$pdf->SetX(80);
$pdf->Cell(22, 1, converteData($etiquetaRetirada['data_solicitacao']));

$pdf->SetX(120);
$pdf->Cell(22, 1, 'Data da Entrega :');
$pdf->SetX(155);
$pdf->Cell(22, 1, converteData($etiquetaRetirada['data_entrega']));

$pdf->ln(15);

$etiquetaId=$etiquetaRetirada['id_etiqueta'];
$etiqueta = mostra('estoque_etiqueta',"WHERE id ='$etiquetaId'");
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Etiqueta :');
$pdf->SetX(30);
$pdf->Cell(22, 1, $etiqueta['nome']);

$pdf->SetX(100);
$pdf->Cell(22, 1, 'Quantidade :');
$pdf->SetX(130);
$pdf->Cell(22, 1, $etiquetaRetirada['quantidade']);

$pdf->SetX(160);
$pdf->Cell(22, 1, 'Saldo Atual :');
$pdf->SetX(190);
$pdf->Cell(22, 1, $contrato['saldo_etiqueta']);

$pdf->ln(15);

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Assinatura : ____________________________________');
$pdf->SetX(140);
$pdf->Cell(22, 1, 'Data : ______/______/________');

$pdf->ln(15);
$pdf->SetX(10);
$pdf->Cell(5, 5, '________________________________________________________________________________________________');
$pdf->ln(10);

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');



 
?>