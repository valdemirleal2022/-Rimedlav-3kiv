<?php


if(function_exists(ProtUser)){
	if(!ProtUser($_SESSION['autUser']['id'])){
		header('Location: painel.php?execute=suporte/403');	
	}	
}
		
if(!empty($_GET['contratoId'])){
	$contratoId = $_GET['contratoId'];
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
	  $titulo="Cronograma";
      $this->Ln(5);
	  $this->Cell(0,5,$titulo,0,0,'C');
	  $this->Line(10, 21, 200, 21); // insere linha divisória
      $this->Ln(11);
    }
    function Footer(){
      $this->SetY(-15);
      $this->SetFont('Arial','B',9);
      $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$i=0;

$contrato = mostra('contrato',"WHERE id='$contratoId'");

$pdf=new RELATORIO();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$clienteId = $contrato['id_cliente'];
$cliente = mostra('cliente',"WHERE id ='$clienteId '");

$pdf->SetX(10);
$pdf->Cell(22, 1, 'Cliente :');
$pdf->SetX(30);
$pdf->Cell(22, 1, substr($cliente['nome'],0,30));

$statusId=$contrato['status'];
$status = mostra('contrato_status',"WHERE id ='$statusId'");
$pdf->SetX(150);
$pdf->Cell(22, 1, 'Status :');
$pdf->SetX(165);
$pdf->Cell(22, 1, $status['nome']);

$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

$pdf->ln(5);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Endereço :');
$pdf->SetX(30);
$pdf->Cell(22, 1, $endereco);

$pdf->ln(5);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Bairro :');
$pdf->SetX(25);
$pdf->Cell(22, 1, substr($cliente['bairro'],0,30));

$pdf->SetX(100);
$pdf->Cell(22, 1, 'Cidade :');
$pdf->SetX(118);
$pdf->Cell(22, 1, substr($cliente['cidade'],0,30));

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

$consultorId=$contrato['consultor'];
$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
$pdf->SetX(90);
$pdf->Cell(22, 1, 'Consultor :');
$pdf->SetX(110);
$pdf->Cell(22, 1, $consultor['nome']);
$pdf->ln(5);

$diaSemana='';
if($contrato['domingo']==1){
	$diaSemana = ' Dom';
}
if($contrato['segunda']==1){
	$diaSemana = $diaSemana . ' Seg';
}
if($contrato['terca']==1){
	$diaSemana = $diaSemana . ' Ter';
}
if($contrato['quarta']==1){
	$diaSemana = $diaSemana . ' Qua';
}
if($contrato['quinta']==1){
	$diaSemana = $diaSemana . ' Qui';
}
if($contrato['sexta']==1){
	$diaSemana = $diaSemana . ' Sex';
}
if($contrato['sabado']==1){
	$diaSemana = $diaSemana . ' Sabado';
}

// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
$frequenciaId = $contrato[ 'frequencia' ];
$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
$frequencia = $frequencia[ 'nome' ];


// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
$quinzenalId = $contrato[ 'quinzenal' ];
$quinzenal = mostra( 'contrato_quinzenal', "WHERE id AND id='$quinzenalId'" );
$quinzenal = $quinzenal[ 'nome' ];
$frequencia = $frequencia . ' | '.$quinzenal;


$pdf->SetX(10);
$pdf->Cell(22, 1, 'Frequencia :');
$pdf->SetX(32);
$pdf->Cell(22, 1, $frequencia);

$pdf->SetX(95);
$pdf->Cell(22, 1, 'Dia(s) da Semana :');
$pdf->SetX(128);
$pdf->Cell(22, 1, $diaSemana);

$pdf->Ln(8);
$pdf->Cell(20, 1, 'Tipo de Coleta');
$pdf->SetX(50);
$pdf->Cell(20, 1, 'Quantidade');
$pdf->SetX(78);
$pdf->Cell(20, 1, 'Valor Unitário');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Inicio');
$pdf->SetX(140);
$pdf->Cell(20, 1, 'Vencimento');
$pdf->SetX(175);
$pdf->Cell(20, 1, 'Valor Mensal');
$pdf->SetX(80);
$pdf->Ln(3);
$pdf->Cell(0,0,'',1,1,'L');
$pdf->Ln(2);

$leitura = read('contrato_coleta',"WHERE id_contrato='$contratoId' ORDER BY vencimento ASC");

foreach($leitura as $mostra):

	$pdf->ln(5);

	$tipoColetaId=$mostra['tipo_coleta'];
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");

	$pdf->SetX(10);
	$pdf->Cell(22, 1, $tipoColeta['nome']);

	$pdf->SetX(58);
	$pdf->Cell(22, 1, $mostra['quantidade']);

	$pdf->SetX(82);
	$pdf->Cell(22, 1, converteValor($mostra['valor_unitario']));

	$pdf->SetX(110);
	$pdf->Cell(22, 1, converteData($mostra['inicio']));

	$pdf->SetX(140);
	$pdf->Cell(22, 1, converteData($mostra['vencimento']));
	
	$pdf->SetX(180);
	$pdf->Cell(22, 1, converteValor($mostra['valor_mensal']));

	$dataColeta=$mostra['inicio'];
	$dataVencimento=$mostra['vencimento'];

endforeach;

$pdf->Ln(7);
$pdf->Cell(20, 1, 'Data');
$pdf->SetX(30);
$pdf->Cell(20, 1, 'Hora 1');
$pdf->SetX(50);
$pdf->Cell(20, 1, 'Rota 1');
$pdf->SetX(70);
$pdf->Cell(20, 1, 'Hora 2');
$pdf->SetX(90);
$pdf->Cell(20, 1, 'Rota 2');
$pdf->SetX(110);
$pdf->Cell(20, 1, 'Hora 3');
$pdf->SetX(130);
$pdf->Cell(20, 1, 'Rota 3');
$pdf->SetX(150);
$pdf->Cell(20, 1, 'Dia da Semana');

$pdf->Ln(3);
$pdf->Cell(0,0,'',1,1,'L');

$i=$i+8;

$pdf->SetFont('Arial','B',8);
$pdf->ln();

$naoVencido = false;  

while ( ! $naoVencido ):  

	if($contrato['domingo']==1){
		if(numeroSemana($dataColeta)==0) { //DOMINGO
				$pdf->SetFont('Arial','B',8);
				$pdf->ln();
				$pdf->SetX(10);
				$pdf->Cell(10, 5, converteData($dataColeta));
			
				$pdf->SetX(30);
				$pdf->Cell(10, 5, $contrato['domingo_hora1']);
				$rotaId=$contrato['domingo_rota1'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(50);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(70);
				$pdf->Cell(10, 5, $contrato['domingo_hora2']);
				$rotaId=$contrato['domingo_rota2'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(90);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(110);
				$pdf->Cell(10, 5, $contrato['domingo_hora3']);
				$rotaId=$contrato['domingo_rota3'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(130);
				$pdf->Cell(10, 5, $rota['nome']);
	
				$pdf->SetX(150);
				$pdf->Cell(10, 5, diaSemana($dataColeta));
			
			
				$i=$i+1;
		}
	}
	if($contrato['segunda']==1){
		if(numeroSemana($dataColeta)==1) { //SEGUNDA
				$pdf->SetFont('Arial','B',8);
				$pdf->ln();
				$pdf->SetX(10);
				$pdf->Cell(10, 5, converteData($dataColeta));
			
				$pdf->SetX(30);
				$pdf->Cell(10, 5, $contrato['segunda_hora1']);
				$rotaId=$contrato['segunda_rota1'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(50);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(70);
				$pdf->Cell(10, 5, $contrato['segunda_hora2']);
				$rotaId=$contrato['segunda_rota2'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(90);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(110);
				$pdf->Cell(10, 5, $contrato['segunda_hora3']);
				$rotaId=$contrato['segunda_rota3'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(130);
				$pdf->Cell(10, 5, $rota['nome']);
	
				$pdf->SetX(150);
				$pdf->Cell(10, 5, diaSemana($dataColeta));
		}
	}
	if($contrato['terca']==1){
		if(numeroSemana($dataColeta)==2) { //TERCA
			
				$pdf->SetFont('Arial','B',8);
				$pdf->ln();
				$pdf->SetX(10);
				$pdf->Cell(10, 5, converteData($dataColeta));
			
				$pdf->SetX(30);
				$pdf->Cell(10, 5, $contrato['terca_hora1']);
				$rotaId=$contrato['terca_rota1'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(50);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(70);
				$pdf->Cell(10, 5, $contrato['terca_hora2']);
				$rotaId=$contrato['terca_rota2'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(90);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(110);
				$pdf->Cell(10, 5, $contrato['terca_hora3']);
				$rotaId=$contrato['terca_rota3'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(130);
				$pdf->Cell(10, 5, $rota['nome']);
	
				$pdf->SetX(150);
				$pdf->Cell(10, 5, diaSemana($dataColeta));
		}
	}
	if($contrato['quarta']==1){
		if(numeroSemana($dataColeta)==3) { //QUARTA
			
				$pdf->SetFont('Arial','B',8);
				$pdf->ln();
				$pdf->SetX(10);
				$pdf->Cell(10, 5, converteData($dataColeta));
			
				$pdf->SetX(30);
				$pdf->Cell(10, 5, $contrato['quarta_hora1']);
				$rotaId=$contrato['quarta_rota1'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(50);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(70);
				$pdf->Cell(10, 5, $contrato['quarta_hora2']);
				$rotaId=$contrato['quarta_rota2'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(90);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(110);
				$pdf->Cell(10, 5, $contrato['quarta_hora3']);
				$rotaId=$contrato['quarta_rota3'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(130);
				$pdf->Cell(10, 5, $rota['nome']);
	
				$pdf->SetX(150);
				$pdf->Cell(10, 5, diaSemana($dataColeta));
		}
	}
	if($contrato['quinta']==1){
		if(numeroSemana($dataColeta)==4) { // QUINTA
				$pdf->SetFont('Arial','B',8);
				$pdf->ln();
				$pdf->SetX(10);
				$pdf->Cell(10, 5, converteData($dataColeta));
			
				$pdf->SetX(30);
				$pdf->Cell(10, 5, $contrato['quinta_hora1']);
				$rotaId=$contrato['quinta_rota1'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(50);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(70);
				$pdf->Cell(10, 5, $contrato['quinta_hora2']);
				$rotaId=$contrato['quinta_rota2'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(90);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(110);
				$pdf->Cell(10, 5, $contrato['quinta_hora3']);
				$rotaId=$contrato['quinta_rota3'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(130);
				$pdf->Cell(10, 5, $rota['nome']);
	
				$pdf->SetX(150);
				$pdf->Cell(10, 5, diaSemana($dataColeta));
		}
	}
	if($contrato['sexta']==1){
		if(numeroSemana($dataColeta)==5) { // SEXTA
				$pdf->SetFont('Arial','B',8);
				$pdf->ln();
				$pdf->SetX(10);
				$pdf->Cell(10, 5, converteData($dataColeta));
			
				$pdf->SetX(30);
				$pdf->Cell(10, 5, $contrato['sexta_hora1']);
				$rotaId=$contrato['sexta_rota1'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(50);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(70);
				$pdf->Cell(10, 5, $contrato['sexta_hora2']);
				$rotaId=$contrato['sexta_rota2'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(90);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(110);
				$pdf->Cell(10, 5, $contrato['sexta_hora3']);
				$rotaId=$contrato['sexta_rota3'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(130);
				$pdf->Cell(10, 5, $rota['nome']);
	
				$pdf->SetX(150);
				$pdf->Cell(10, 5, diaSemana($dataColeta));
		}
	}

	if($contrato['sabado']==1){
		if(numeroSemana($dataColeta)==6) { // SABADO
				$pdf->SetFont('Arial','B',8);
				$pdf->ln();
				$pdf->SetX(10);
				$pdf->Cell(10, 5, converteData($dataColeta));
			
			
				$pdf->SetX(30);
				$pdf->Cell(10, 5, $contrato['sabado_hora1']);
				$rotaId=$contrato['sabado_rota1'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(50);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(70);
				$pdf->Cell(10, 5, $contrato['sabado_hora2']);
				$rotaId=$contrato['sabado_rota2'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(90);
				$pdf->Cell(10, 5, $rota['nome']);
			
				$pdf->SetX(110);
				$pdf->Cell(10, 5, $contrato['sabado_hora3']);
				$rotaId=$contrato['sabado_rota3'];
				$rota = mostra('contrato_rota',"WHERE id ='$rotaId'");
				$pdf->SetX(130);
				$pdf->Cell(10, 5, $rota['nome']);
	
				$pdf->SetX(150);
				$pdf->Cell(10, 5, diaSemana($dataColeta));
		}
	}

	$dataColeta=proximaColeta($contratoId,$dataColeta);

  	if($dataColeta>$dataVencimento){
		$naoVencido = true;     
	}
	
	if ($i>47){
		
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(1);
		
		$pdf->Cell(20, 1, 'Data');
		$pdf->SetX(30);
		$pdf->Cell(20, 1, 'Hora');
		$pdf->SetX(60);
		$pdf->Cell(20, 1, 'Rota');
		$pdf->SetX(80);
		
		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Hora');
		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Rota');
		
		$pdf->Ln(5);
		$pdf->Cell(0,0,'',1,1,'L');
		$i=0;
	}
	

endwhile; 


ob_clean();  
$pdf->Output('relatorio.pdf', 'I');



?>