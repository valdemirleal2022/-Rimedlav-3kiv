<?php

require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$dataroteiro=$_SESSION['dataroteiro'];
$rotaId=$_SESSION['rotaColeta'];
	
//L: left
//T: top
//R: right
//B: bottom

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

$leitura=read('contrato_ordem',"WHERE id AND manifesto='M' AND data='$dataroteiro' ORDER BY rota ASC, hora ASC");

if(!empty($_GET['manifestoImprimir'])){
	$manifestoId = $_GET['manifestoImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND manifesto='M' AND id='$manifestoId'");
}

if(!empty($rotaId)){
	$ordemId = $_GET['ordemImprimir'];
	$leitura=read('contrato_ordem',"WHERE id AND manifesto='M' AND data='$dataroteiro' AND rota='$rotaId' ORDER BY rota ASC, hora ASC");
}



$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5, 5);
$pdf->SetFont('Arial','B',12);

$i=1;

foreach($leitura as $mostra):

	if ($i>2){
		$pdf->AddPage();
		$pdf->SetMargins(5, 10, 5, 5);
		$pdf->SetFont('Arial','B',12);
		$i=1;
	}


  for ($x = 1; $x <5; $x++) {

	$contratoId=$mostra['id_contrato'];
    $contratoTipoId=$mostra['tipo'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");

	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$contratoTipo = mostra('contrato_tipo',"WHERE id='$contratoTipoId'");
      
    $classificacaoId=$cliente['classificacao'];
	$classificacao = mostra('cliente_classificacao',"WHERE id='$classificacaoId'");

	$rota=$mostra['rota'];
	$veiculoLiberacao = mostra('veiculo_liberacao',"WHERE rota ='$rota' AND saida='$dataroteiro'");

	$veiculoId=$veiculoLiberacao['veiculo'];
	$motoristaId=$veiculoLiberacao['motorista'];
	$aterroId=$veiculoLiberacao['aterro'];

	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$motorista= mostra('veiculo_motorista',"WHERE id ='$motoristaId'");

	$aterro= mostra('aterro',"WHERE id ='$aterroId'");
	$tratamentoId=$aterro['tratamento'];
	$aterroTratamento= mostra('aterro_tratamento',"WHERE id ='$tratamentoId'");

	$tipoColeta1=$mostra['tipo_coleta1'];
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta1'");

	$tipoResiduoId=$tipoColeta['residuo'];
	$estadoFisicoId=$tipoColeta['estado_fisico'];

	$tipoResiduo = mostra('contrato_tipo_residuo',"WHERE id ='$tipoResiduoId'");
	$estadoFisico = mostra('contrato_tipo_estado_fisico',"WHERE id ='$estadoFisicoId'");

	$rotaId=$mostra['rota'];

	$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");	
	
	$pdf->SetFont('Arial','B',13);
	$pdf->SetX(10);
	$pdf->Cell(10,3, 'INEA');
	  
	$pdf->Image("ico/codigo-barra.png", 172, 1, 30, 15,'PNG');

    $pdf->ln();
	$pdf->ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(10);
	$pdf->Cell(10,5, 'Instituto Estadual do Ambiente');
    $pdf->ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->SetX(70);
	$pdf->Cell(10, 1, 'MANIFESTO DE RESÍDUOS');
	$pdf->ln(2);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(150, 1, '');
	$pdf->Cell(30, 1, $rota['nome']);
	$pdf->Cell(10, 1, $mostra['id']);
    $pdf->ln(3);
	
	
	$pdf->Cell(130,12,' ','LTR',0,'L',0); 
	$pdf->Cell(30,12,' ','LTR',0,'L',0); 
	$pdf->Cell(40,12,' ','LTR',0,'L',0);
	$pdf->MultiCell(80, 2, '',0,'L');
	
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(130,1,'1 RESÍDUO',2,0,'L',0);
	$pdf->Cell(30,1,'CÓD RESÍDUO',2,0,'L',0);
	$pdf->Cell(40,1,'2 QUANTIDADE',2,0,'L',0);
	$pdf->MultiCell(80, 6, '',0,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(130,1,$tipoResiduo['nome'],2,0,'L',0);
	$pdf->Cell(30,1, $tipoColeta['codigo_inea'],2,0,'L',0);
	$pdf->Cell(40,1, $tipoColeta['volume_litros'],2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(100,12,' ','LTR',0,'L',0); 
	$pdf->Cell(30,12,' ','LTR',0,'L',0); 
	$pdf->Cell(70,12,' ','LTR',0,'L',0);
	$pdf->MultiCell(80, 2, '',0,'L');
	
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(100,1,'3 ESTADO FÍSICO',2,0,'L',0);
	$pdf->Cell(30,8,'4 ORIGEM',2,0,'L',0);
	$pdf->Cell(60,1,' ',2,0,'L',0);
	$pdf->MultiCell(80, 6, '',0,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(100,1,$estadoFisico['nome'],2,0,'L',0);
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(20,1,$contratoTipo['origem'],2,0,'L',0);
	$pdf->Cell(20,1,$tipoResiduo['processo'],2,0,'L',0);
	$pdf->ln(4);
	
	$pdf->Cell(70,12,' ','LTR',0,'L',0); 
	$pdf->Cell(70,12,' ','LTR',0,'L',0); 
	$pdf->Cell(60,12,' ','LTR',0,'L',0);
	$pdf->MultiCell(80, 2, '',0,'L');
	
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(70,1,'5 ACONDICIONAMENTO',2,0,'L',0);
	$pdf->Cell(70,1,'6 PROCEDENCIA',2,0,'L',0);
	$pdf->Cell(70,1,'7 TRATAMENTO / DISPOSIÇAO',2,0,'L',0);
	$pdf->MultiCell(80, 6, '',0,'L');
	
    $pdf->SetFont('Arial','B',9);
	$pdf->Cell(70,1,$tipoColeta['nome'],2,0,'L',0);
	$pdf->Cell(70,1,$classificacao['nome'],2,0,'L',0);
	$pdf->Cell(70,1,$aterroTratamento['nome'],2,0,'L',0);
	$pdf->ln(4);
	$pdf->Cell(70,1,' ','LBR',0,'L',0); 
	$pdf->Cell(70,1,' ','LBR',0,'L',0); 
	$pdf->Cell(60,1,' ','LBR',0,'L',0);
	$pdf->ln(2);
	
	//GERADOR - col,linha texto - esquerda topo direita -
	$pdf->Cell(30,53,' ','LTR',0,'L',0); 
	$pdf->Cell(130,53,' ','LTR',0,'L',0); 
	$pdf->Cell(40,53,' ','LTR',0,'L',0);

	$pdf->MultiCell(10,3, '',0,'L');
	$pdf->Cell(30,1,'8 Gerador',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(130,1,'Empresa/Razao Social',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(130,1,$cliente['nome'],2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 
	 
	$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
	  
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(130,1,'Endereço',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(135,1,$endereco,2,0,'L',0);
	$pdf->Cell(30,1,'11 Data da Entrega',2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(40,1,'Municipio',2,0,'L',0);
	$pdf->Cell(20,1,'UF',2,0,'L',0);
	$pdf->Cell(45,1,'Telefone',2,0,'L',0);
	$pdf->Cell(20,1,'N. Licença INEA',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(40,1,$cliente['cidade'],2,0,'L',0);
	$pdf->Cell(20,1,$cliente['uf'],2,0,'L',0);
	$pdf->Cell(45,1,$cliente['telefone'],2,0,'L',0);
	$pdf->Cell(40,1,$cliente['manifesto_inea'],2,0,'L',0);
	$pdf->Cell(30,1,converteData($mostra['data']),2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(70,1,'Responsável pela Expediçao do Resíduo',2,0,'L',0);
	$pdf->Cell(62,1,'Cargo',2,0,'L',0);
	$pdf->Cell(30,1,'_______________________',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(70,1,$cliente['manisfesto_responsavel'],2,0,'L',0);
	$pdf->Cell(62,1,$cliente['manifesto_cargo'],2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(30,1,'Assinatura do Responsável',2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(200,2,' ','B',0,'L',0); 
	$pdf->ln(3);
	// LINHA SIMPLES 

//TRANSPORTADOR - col,linha texto - esquerda topo direita -
	$pdf->Cell(30,65,' ','LTR',0,'L',0); 
	$pdf->Cell(130,65,' ','LTR',0,'L',0); 
	$pdf->Cell(40,65,' ','LTR',0,'L',0);

	$pdf->MultiCell(10,3, '',0,'L');
	$pdf->Cell(30,3,'9 Transportador',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(130,1,'Empresa/Razao Social',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(130,1,$empresa['nome'],2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(130,1,'Endereço',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(135,1,$empresa['endereco'],2,0,'L',0);
	$pdf->Cell(30,1,'12 Data da Entrega',2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(40,1,'Municipio',2,0,'L',0);
	$pdf->Cell(25,1,'UF',2,0,'L',0);
	$pdf->Cell(40,1,'Telefone',2,0,'L',0);
	$pdf->Cell(20,1,'N. Licença INEA',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(40,1,$empresa['cidade'],2,0,'L',0);
	$pdf->Cell(25,1,$empresa['uf'],2,0,'L',0);
	$pdf->Cell(40,1,substr($empresa['telefone'],0,14),2,0,'L',0);
	$pdf->Cell(40,1,$empresa['inea'],2,0,'L',0);
	$pdf->Cell(30,1,converteData($mostra['data']),2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(70,1,'Responsável pela Empresa de Transporte',2,0,'L',0);
	$pdf->Cell(30,1,'Cargo',2,0,'L',0);
	$pdf->Cell(30,1,'Placa',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(70,1,$empresa['responsavel'],2,0,'L',0);
	$pdf->Cell(30,1,$empresa['cargo'],2,0,'L',0);
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->ln(3);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(70,1,'Nome do Motorista',2,0,'L',0);
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(32,1,'Certificado INMETRO',2,0,'L',0);
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(62,1,'',2,0,'L',0);

	$pdf->Cell(40,1,'',2,0,'L',0);
	$pdf->Cell(30,1,$empresa['inmetro'],2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(30,1,'Assinatura do Responsável',2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(200,2,' ','B',0,'L',0); 
	$pdf->ln(3);
	// LINHA SIMPLES 

//RECEPTOR - col,linha texto - esquerda topo direita -
	$pdf->Cell(30,61,' ','LTR',0,'L',0); 
	$pdf->Cell(130,61,' ','LTR',0,'L',0); 
	$pdf->Cell(40,61,' ','LTR',0,'L',0);

	$pdf->MultiCell(10,3, '',0,'L');
	$pdf->Cell(30,3,'10 Receptor',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(130,1,'Empresa/Razao Social',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(130,1,$aterro['nome'],2,0,'L',0);
	$pdf->ln(6);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(130,1,'Endereço',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(135,1,$aterro['endereco'],2,0,'L',0);
	$pdf->Cell(30,1,'13 Data da Entrega',2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(40,1,'Municipio',2,0,'L',0);
	$pdf->Cell(30,1,'UF',2,0,'L',0);
	$pdf->Cell(30,1,'Telefone',2,0,'L',0);
	$pdf->Cell(20,1,'N. Licença INEA',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(40,1,$aterro['cidade'],2,0,'L',0);
	$pdf->Cell(30,1,$aterro['uf'],2,0,'L',0);
	$pdf->Cell(30,1,$aterro['telefone'],2,0,'L',0);
	$pdf->Cell(40,1,$aterro['inea'],2,0,'L',0);
	$pdf->Cell(30,1,converteData($mostra['data']),2,0,'L',0);
	$pdf->ln(4);

	$pdf->Cell(30,2,' ','',0,'L',0); 
	$pdf->Cell(130,2,' ','B',0,'L',0); 
	$pdf->Cell(30,2,' ','',0,'L',0);
	$pdf->ln(4);
	// LINHA SIMPLES 

	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(70,1,'Responsável pela Empresa Receptora',2,0,'L',0);
	$pdf->Cell(62,1,'Cargo',2,0,'L',0);
	$pdf->Cell(30,1,'_______________________',2,0,'L',0);
	$pdf->SetFont('Arial','B',9);
	$pdf->MultiCell(50,5, '',0,'L');
	$pdf->Cell(30,1,'',2,0,'L',0);
	$pdf->Cell(70,1,$aterro['responsavel'],2,0,'L',0);
	$pdf->Cell(62,1,$aterro['cargo'],2,0,'L',0);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(30,1,'Assinatura do Responsável',2,0,'L',0);
	$pdf->ln(10);

	$pdf->Cell(200,2,' ','B',0,'L',0); 
	$pdf->ln(2);
	// LINHA SIMPLES 
	$pdf->Cell(200,15,' ','LBR',0,'L',0); 
 
	$pdf->ln(3);
	$pdf->Cell(30,1,'Observaçao',2,0,'L',0);
	$pdf->ln(15);
	  
	if($x==1){
		$pdf->Cell(30,1,'1a Via - Conservar com o Gerador',2,0,'L',0);
	}
	if($x==2){
		$pdf->Cell(30,1,'2a Via - Conservar com o Transportador',2,0,'L',0);
	}
	if($x==3){
		$pdf->Cell(30,1,'3a Via - Conservar com o Receptor',2,0,'L',0);
	}
	if($x==4){
		$pdf->Cell(30,1,'4a Via - Conservar com o Gerador-INEA',2,0,'L',0);
	}
 	$pdf->ln(12);
	$pdf->Cell(30,1,'_',2,0,'L',0);
	$pdf->ln(10);
	
	$i=$i+1;

	} 

endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>