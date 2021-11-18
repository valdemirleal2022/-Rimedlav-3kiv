<?php
require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


$statusId = $_SESSION['status'];
$contratoTipoId = $_SESSION['contratoTipo'];

class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Inadimplentes";
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

$dataHoje = date("Y-m-d", strtotime("-3 day"));
$leitura = read('receber',"WHERE vencimento<='$dataHoje' AND status<>'Baixado' ORDER BY id_contrato ASC, vencimento ASC");

if(!empty($statusId )){
	$status = mostra('contrato_status',"WHERE id='$statusId'");
}

if(!empty($contratoTipoId )){
	$Tipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
}

$totalJuridido = 0;
$totalProtesto = 0;	
$totalSerasa = 0;
$totalEmAberto = 0;
			
$valorJuridido = 0;
$valorProtesto = 0;	
$valorSerasa = 0;
$valorEmAberto = 0;

$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->Cell(25, 1, converteData($data1));

$pdf->Cell(22, 1, 'Data Fim :');
$pdf->Cell(25, 1, converteData($data2));

if(!empty($statusId )){
	$pdf->SetX(120);
	$pdf->Cell(22, 1, 'Status :');
	$pdf->Cell(25, 1, $status['nome']);
}

if(!empty($contratoTipo)){
	$pdf->SetX(190);
	$pdf->Cell(22, 1, 'Tipo :');
	$pdf->Cell(25, 1, $Tipo['nome']);
}

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(20);
$pdf->Cell(20, 1, 'Controle');

$pdf->SetX(38);
$pdf->Cell(20, 1, 'Nome');

$pdf->SetX(86);
$pdf->Cell(20, 1, 'Status');

$pdf->SetX(100);
$pdf->Cell(20, 1, 'Emissão');

$pdf->SetX(120);
$pdf->Cell(20, 1, 'Vencimento');

$pdf->SetX(147);
$pdf->Cell(20, 1, 'Valor');

$pdf->SetX(168);
$pdf->Cell(20, 1, 'Situação');

$pdf->SetX(192);
$pdf->Cell(20, 1, 'Banco/Pag');

$pdf->SetX(215);
$pdf->Cell(10, 1, 'Nota');

$pdf->SetX(232);
$pdf->Cell(10, 1, 'Consultor');

$pdf->SetX(252);
$pdf->Cell(10, 1, 'Tipo');

$pdf->SetX(281);
$pdf->Cell(10, 1, 'Rota');

$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

$totalCliente=0;

foreach($leitura as $mostra):

		$contratoId = $mostra['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		$listar='NAO';
		
		$listar='NAO';
 		
 		 if($mostra['status']=='Em Aberto'){
 			$listar='SIM';
 		}
		if($mostra['status']=='Juridico'){
 			$listar='SIM';
 		}

		
		if($mostra['serasa']=='1'){
		  $listar='SIM';
		}
		if($mostra['juridico']=='1'){
		  $listar='SIM';
		}
		if($mostra['protesto']=='1'){
		  $listar='SIM';
		}
		

		if(!empty($statusId) AND $listar=='SIM'){
			$listar='NAO';
			if($contrato['status']==$statusId){
				$listar='SIM';
			}
		}
		
		if(!empty($contratoTipoId) AND $listar=='SIM'){
			$listar='NAO';
			if($contrato['tipo']==$contratoTipoId){
				$listar='SIM';
			}
		}
		
		if($listar=='SIM'){
			$pdf->SetFont('Arial','B',8);
			$pdf->ln();

			$pdf->SetX(8);
			$pdf->Cell(10, 5, $mostra['id'],0,0,R);

			$valor_total += $mostra['valor'];
			$total++;

			$contratoId = $mostra['id_contrato'];
			$contrato = mostra('contrato',"WHERE id ='$contratoId'");

			$pdf->SetX(22);
			$pdf->Cell(10, 5, substr($contrato['controle'],0,6));

			$clienteId = $mostra['id_cliente'];
			$cliente = mostra('cliente',"WHERE id ='$clienteId '");

			$pdf->SetX(38);
			$pdf->Cell(10, 5, substr($cliente['nome'],0,25));

			if($contrato['status']==5){
				$pdf->SetX(89);
				$pdf->Cell(10, 5,'A');
			}elseif($contrato['status']==6){
				$pdf->SetX(89);
				$pdf->Cell(10, 5,'S');
			}elseif($contrato['status']==9){
				$pdf->SetX(89);
				$pdf->Cell(10, 5,'C');
		   }elseif($contrato['status']==10){
				$pdf->SetX(89);
				$pdf->Cell(10, 5,'J');
			}else{
				$pdf->SetX(89);
				$pdf->Cell(10, 5,'!');
			}


			$pdf->SetX(100);
			$pdf->Cell(10, 5, converteData($mostra['emissao']));

			$pdf->SetX(122);
			$pdf->Cell(10, 5, converteData($mostra['vencimento']));

			$pdf->SetX(155);
			$pdf->Cell(10, 5, converteValor($mostra['valor']),0,0,'R');

			$pdf->SetX(168);
			$pdf->Cell(10, 5, $mostra['status']);

			$bancoId=$mostra['banco'];
			$banco = mostra('banco',"WHERE id ='$bancoId'");
			$formapagId=$mostra['formpag'];
			$formapag = mostra('formpag',"WHERE id ='$formapagId'");
			$pdf->SetX(192);
			$pdf->Cell(10, 5, $banco['nome']. "|".substr($formapag['nome'],0,10));

			$pdf->SetX(215);
			$pdf->Cell(10, 5, $mostra['nota']);

			$consultorId = $contrato['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");

			$pdf->SetX(232);
			$pdf->Cell(10, 5, $consultor['nome']);

			$TipoId = $mostra['contrato_tipo'];
			$Tipo = mostra('contrato_tipo',"WHERE id ='$TipoId'");

			$pdf->SetX(252);
			$pdf->Cell(10, 5, $Tipo['nome']);
			
				if($mostra['status']=='Em Aberto' AND $mostra['vencimento']<=$dataHoje){
					$totalEmAberto = $totalEmAberto+1;
					$valorEmAberto = $valorEmAberto+$mostra['valor'];
				}
		
				if($mostra['serasa']=='1'){
				  $totalSerasa = $totalSerasa+1;
				  $valorSerasa = $valorSerasa+$mostra['valor'];
				}
				if($mostra['juridico']=='1'){
				  $totalJuridido = $totalJuridido+1;
				  $valorJuridido = $valorJuridido+$mostra['valor'];
				}
				if($mostra['protesto']=='1'){
				  $totalProtesto = $totalProtesto+1;
				  $valorProtesto = $valorProtesto+$mostra['valor'];
				}
			
			
			$diaSemana='';
			$rota='';
			if($contrato['domingo']==1){
				$diaSemana = ' Dom';
				$rota=$contrato['domingo_rota1'];
			}
			if($contrato['segunda']==1){
				$diaSemana = $diaSemana . ' Seg';
				$rota=$contrato[ 'segunda_rota1' ];
			}
			if($contrato['terca']==1){
				$diaSemana = $diaSemana . ' Ter';
				$rota=$contrato[ 'terca_rota1' ];
			}
			if($contrato['quarta']==1){
				$diaSemana = $diaSemana . ' Qua';
				$rota=$contrato[ 'quarta_rota1' ];
			}
			if($contrato['quinta']==1){
				$diaSemana = $diaSemana . ' Qui';
				$rota=$contrato[ 'quinta_rota1' ];
			}
			if($contrato['sexta']==1){
				$diaSemana = $diaSemana . ' Sex';
				$rota=$contrato[ 'sexta_rota1' ];
			}
			if($contrato['sabado']==1){
				$diaSemana = $diaSemana . ' Sab';
				$rota=$contrato[ 'sabado_rota1' ];
			}

			$rota = mostra( 'contrato_rota', "WHERE id AND id='$rota'" );
			$rota = $rota[ 'nome' ];
			$pdf->SetX(282);
			$pdf->Cell(10, 5, $rota);
			
			
			$i=$i+1;

	}

	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		
		$pdf->Ln(5);
		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(20);
		$pdf->Cell(20, 1, 'Controle');

		$pdf->SetX(38);
		$pdf->Cell(20, 1, 'Nome');

		$pdf->SetX(86);
		$pdf->Cell(20, 1, 'Status');

		$pdf->SetX(100);
		$pdf->Cell(20, 1, 'Emissão');

		$pdf->SetX(120);
		$pdf->Cell(20, 1, 'Vencimento');

		$pdf->SetX(147);
		$pdf->Cell(20, 1, 'Valor');

		$pdf->SetX(168);
		$pdf->Cell(20, 1, 'Situação');

		$pdf->SetX(192);
		$pdf->Cell(20, 1, 'Banco/Pag');

		$pdf->SetX(215);
		$pdf->Cell(10, 1, 'Nota');

		$pdf->SetX(232);
		$pdf->Cell(10, 1, 'Consultor');

		$pdf->SetX(252);
		$pdf->Cell(10, 1, 'Tipo');

		$pdf->SetX(281);
		$pdf->Cell(10, 1, 'Rota');

		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		$pdf->Ln(4);
		
		$i=0;
	}
endforeach;

$total=$totalEmAberto+$totalSerasa+$totalJuridido+$totalProtesto;
$valor_total=$valorEmAberto+$valorSerasa+$valorJuridido+$valorProtesto;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total em Aberto : '. $totalEmAberto);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valorEmAberto));
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total Serasa : '. $totalSerasa);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valorSerasa));
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total Juridido : '. $totalJuridido);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valorJuridido));
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total Protesto : '. $totalProtesto);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valorProtesto));	
$pdf->ln(5);
$pdf->Cell(10, 5, 'Total Listado : '. $total);
$pdf->SetX(60);
$pdf->Cell(10, 5, 'Valor Total R$ : ' . converteValor($valor_total));	
ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>