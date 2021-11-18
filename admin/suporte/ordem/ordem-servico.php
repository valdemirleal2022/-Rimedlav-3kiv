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


$leitura = read('contrato_ordem',"WHERE id AND data='$dataroteiro' ORDER BY rota ASC, hora ASC");

if(!empty($rotaId)){
	$ordemId = $_GET['ordemImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND data='$dataroteiro' AND rota='$rotaId' ORDER BY rota ASC, hora ASC");
}

if(!empty($_GET['ordemImprimir'])){
	$ordemId = $_GET['ordemImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND id='$ordemId'");
}

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage(); 
$pdf->SetMargins(10, 10, 5, 5);
$pdf->SetFont('Arial','B',9);

$i=1;

$rotaPagina='';
$total=0;

foreach($leitura as $mostra):
	
	$contratoId=$mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

 	if($mostra['id_contrato']==26){
		$empresa = mostra('empresa',"WHERE id ORDER BY id ASC");
	}else{
		$empresa = mostra('empresa',"WHERE id ORDER BY id DESC");
	}
	

	
	$total=$total+1;

	if($mostra['avulsa']=='1'){
		
		$ordemAvulsaId=$mostra['id'];
		
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['impressa']='1';
		update('contrato_ordem',$cad,"id = '$ordemAvulsaId'");
			 
		$interacao='Imprimiu Ordem Avulsa';
		interacao($interacao,$contratoId);
   }

	if ($i>2){
		$pdf->AddPage();
		$i=1;
	}
	
	if(empty($rotaPagina)){
		$rotaPagina=$mostra['rota'];
	}

	if($rotaPagina<>$mostra['rota']){
		$rotaPagina=$mostra['rota'];
		$total=1;
	}

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();

	$pdf->SetX(172);
	$pdf->Cell(10, 5, date('d/m/Y H:i:s'));
    $pdf->ln();

	if ($i==1){
		//$pdf->Image("ico/header-logo.png", 06, 18, 30, 15,'PNG');
	}

	if ($i==2){
		//$pdf->Image("ico/header-logo.png", 06, 158, 30, 15,'PNG'); 
	}

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
	$pdf->Cell(10, 5, $empresa['telefone']);
	$pdf->SetX(80);
	$pdf->Cell(10, 5, 'Whatsapp - '. $empresa['celular']);

	
	$pdf->ln();

	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();

	
	if ($i==1){
		$pdf->Image("ico/codigo-barra.png", 172, 20, 30, 15,'PNG');
	}
	if ($i==2){
		$pdf->Image("ico/codigo-barra.png", 172, 160, 30, 15,'PNG'); 
	}

    $contratoId=$mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetX(05);
	$pdf->Cell(10, 5, $total);
	$pdf->SetFont('Arial','B',9);

	$pdf->SetX(15);
	$pdf->Cell(10, 5, 'Contrato :');
	$pdf->SetX(32);
	$pdf->Cell(10, 5, $mostra['id_contrato'].'|'. substr($contrato['controle'],0,6));

	$pdf->SetX(55);
	$pdf->Cell(10, 5, 'Início :');
	$pdf->SetX(70);
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

//	$pdf->SetX(155);
//	$pdf->Cell(10, 5, 'Saldo de Etiquetas :');
//	$pdf->SetX(190);
//	$pdf->Cell(10, 5, $contrato['saldo_etiqueta']);

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

	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId' AND vencimento>='$dataroteiro'");

	$pdf->SetX(5);
	$pdf->Cell(10, 5, $tipoColeta['nome']);
	$pdf->SetX(80);
	$pdf->Cell(10, 5, $contratoColeta['quantidade']);
	$pdf->SetX(140);
	$pdf->Cell(10, 5, '_______________________________');
	$pdf->ln();

    $pdf->ln();
    $pdf->ln();
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
	
	$dataColeta=$mostra['data'];
	$pdf->SetX(10);
	$pdf->Cell(10, 5, 'Proxima Coleta : '. converteData( proximaColeta($contratoId,$dataColeta) ) );
 
	if ( $contrato[ 'manifesto' ] == '2' ) {
		$pdf->SetX(145);
		$pdf->Cell(5, 5, '* Pegar manifesto com cliente');
	}
	

	$pdf->ln();
	
	$pdf->SetX(5);
	$pdf->Cell(5, 5, '===========================================================================================================');
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();
	$pdf->ln();

	$i=$i+1;

endforeach;

ob_clean();  
$pdf->Output('ordem-contrato.pdf', 'I');
 
?>