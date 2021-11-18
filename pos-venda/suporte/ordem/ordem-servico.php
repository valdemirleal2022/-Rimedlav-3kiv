<?php

require_once("fpdf/fpdf.php");

define("FPDF_FONTPATH","font/");

$dataroteiro=$_SESSION['dataroteiro'];
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

if(!empty($_GET['ordemImprimir'])){
	$ordemId = $_GET['ordemImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND id='$ordemId'");
}

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage(); 
$pdf->SetMargins(10, 10, 5, 5);
$pdf->SetFont('Arial','B',9);

$i=1;



foreach($leitura as $mostra):


	if ($i>2){
		$pdf->AddPage();
		$i=1;
	}
	
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();

	$pdf->SetX(172);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s'));
    $pdf->ln();

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

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();
	
	if ($i==1){
		$pdf->Image("../admin/ico/codigo-barra.png", 172, 20, 30, 15,'PNG');
	}else{
		$pdf->Image("../admin/ico/codigo-barra.png", 172, 160, 30, 15,'PNG'); 
	 }
    $contratoId=$mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
	
	$pdf->SetFont('Arial','B',9);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Contrato :');
	$pdf->SetX(23);
	$pdf->Cell(10, 5, $mostra['id_contrato'].'|'. substr($contrato['controle'],0,6));

	$pdf->SetX(55);
	$pdf->Cell(10, 5, 'Início :');
	$pdf->SetX(68);
	$pdf->Cell(10, 5, converteData($contrato['inicio']));;

 	$contratoTipo=$contrato['contrato_tipo'];
	$tipoResiduo = mostra('contrato_tipo',"WHERE id ='$contratoTipo'");

	$pdf->SetX(95);
	$pdf->Cell(5, 5, 'Tipo de Resíduo  :');
	$pdf->SetX(125);
	$pdf->Cell(10, 5, $tipoResiduo['nome']);

	$pdf->SetX(170);
	$pdf->Cell(10, 5, 'Número :');
	$pdf->SetX(190);
	$pdf->Cell(10, 5, $mostra['id']);
	$pdf->ln();


	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Nome :');
	$pdf->SetX(20);
	$pdf->Cell(10, 5, $cliente['nome']);

	$pdf->SetX(170);
	$pdf->Cell(10, 5, 'Data :');
	$pdf->SetX(180);
	$pdf->Cell(10, 5, converteData($mostra['data']));
	$pdf->ln();
	
	$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
	  
	
	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Endereço :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $endereco);

	$pdf->SetX(170);
	$pdf->Cell(10, 5, 'Hora :');
	$pdf->SetX(180);
	$pdf->Cell(10, 5, $mostra['hora']);
    $pdf->ln();

	$rota=$mostra['rota'];
	$rota = mostra('contrato_rota',"WHERE id ='$rota'");

	$pdf->SetX(170);
	$pdf->Cell(10, 5, 'Rota :');
	$pdf->SetX(180);
	$pdf->Cell(10, 5, $rota['nome']);

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Bairro :');
	$pdf->SetX(20);
	$pdf->Cell(10, 5, $cliente['bairro']);

	$pdf->SetX(100);
	$pdf->Cell(10, 5, 'Cidade :');
	$pdf->SetX(115);
	$pdf->Cell(10, 5, $cliente['cidade']);
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Telefone :');
	$pdf->SetX(25);
	$pdf->Cell(10, 5, $cliente['telefone']);

	$pdf->SetX(100);
	$pdf->Cell(10, 5, 'Contato :');
	$pdf->SetX(115);
	$pdf->Cell(10, 5, $cliente['contato']);
	$pdf->ln();

	$pdf->SetX(05);
	$pdf->Cell(10, 5, 'Referencia :');
	$pdf->SetX(30);
	$pdf->Cell(10, 5, $cliente['referencia']);

	$pdf->SetX(155);
	$pdf->Cell(10, 5, 'Saldo de Etiquetas :');
	$pdf->SetX(190);
	$pdf->Cell(10, 5, $contrato['saldo_etiqueta']);
    $pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
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
	$pdf->SetX(150);
	$pdf->Cell(10, 5, $mostra['quantidade1']);
	$pdf->ln();

    $pdf->ln();
    $pdf->ln();

	$pdf->SetFont('Arial','B',18);
	$statusId = $mostra['status'];
	$status = mostra('contrato_status',"WHERE id ='$statusId'");	

	if ($mostra['nao_coletada'] == "1") {
		$pdf->SetX(80);
		$pdf->Cell(5, 5, 'NÃO COLETADA');
		$pdf->SetFont('Arial','B',9);

		
	}else{

		$pdf->SetX(80);
		$pdf->Cell(5, 5, $status['nome']);
		$pdf->SetFont('Arial','B',9);

	}

	
	

    $pdf->ln();
    $pdf->ln();
	$pdf->ln();
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

	

	$pdf->ln();
	
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
    
    break;

endforeach;

ob_clean();  
$pdf->Output('ordem-contrato.pdf', 'I');
 
?>