<?php


$data1 = $_SESSION['data1'];
$data2 = $_SESSION['data2'];

if(isset($_SESSION[ 'veiculo' ])){
	$veiculoId =$_SESSION[ 'veiculo' ];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$veiculoNome = $veiculo['modelo'].' | '.$veiculo['placa'];
}


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");


class RELATORIO extends FPDF{
    function Header(){
	  $titulo=SITENOME;
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'L');
      $titulo=date('d/m/Y H:i:s');
      $this->SetFont('Arial','I',8);
      $this->Cell(0,5,$titulo,0,0,'R'); 	  
	  $this->SetFont('Arial','B',12);
	  $titulo="Manutenção de Veículo";
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

$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' ORDER BY data_solicitacao DESC");

$total = conta('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2'");

if(!empty($veiculoId)){
	$leitura = read('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_veiculo='$veiculoId' ORDER BY data_solicitacao ASC");
	
	$total = conta('veiculo_manutencao',"WHERE id AND data_solicitacao>='$data1' AND data_solicitacao<='$data2' AND id_veiculo='$veiculoId'");
}

$pdf=new RELATORIO();
$pdf=new RELATORIO("L","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
$pdf->Cell(22, 1, 'Data Inicio :');
$pdf->SetX(32);
$pdf->Cell(22, 1, converteData($data1));
$pdf->SetX(54);
$pdf->Cell(22, 1, 'Data Fim :');
$pdf->SetX(73);
$pdf->Cell(22, 1, converteData($data2));
$pdf->Cell(22, 1, 'Veículo :');
$pdf->SetX(144);
$pdf->Cell(22, 1, $veiculoNome);

$pdf->Ln(5);
$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

$pdf->SetX(10);
$pdf->Cell(20, 1, 'Id');

$pdf->SetX(22);
$pdf->Cell(20, 1, 'Placa');

$pdf->SetX(40);
$pdf->Cell(20, 1, 'Data');

$pdf->SetX(62);
$pdf->Cell(20, 1, 'Tipo');

$pdf->SetX(80);
$pdf->Cell(10, 1, 'Motorista');

$pdf->SetX(135);
$pdf->Cell(10, 1, 'Turno');

$pdf->SetX(155);
$pdf->Cell(10, 1, 'Manutenção');

$pdf->SetX(180);
$pdf->Cell(10, 1, 'Concluida');

$pdf->SetX(200);
$pdf->Cell(10, 1, 'Pendente');

$pdf->SetX(220);
$pdf->Cell(10, 1, 'Status');

$pdf->SetX(250);
$pdf->Cell(10, 1, 'Valor');


$pdf->Line(10, 33, 290, 33); // insere linha divisória
$pdf->Ln(4);
$i=0;

foreach($leitura as $mostra):

	$pdf->SetFont('Arial','B',8);
	$pdf->ln();

	$pdf->SetX(10);
	$pdf->Cell(10, 5, $mostra['id']);

	$veiculoId = $mostra['id_veiculo'];
	$veiculo = mostra('veiculo',"WHERE id ='$veiculoId'");
	$pdf->SetX(22);
	$pdf->Cell(10, 5, $veiculo['placa']);

	$pdf->SetX(40);
	$pdf->Cell(10, 5, converteData($mostra['data_solicitacao']));

	if($mostra['manutencao']=='1'){
		$pdf->SetX(62);
		$pdf->Cell(10, 5, 'Preventiva' );
	}elseif($mostra['manutencao']=='2'){
		$pdf->SetX(62);
		$pdf->Cell(10, 5, 'Corretiva' );
	}elseif($mostra['manutencao']=='3'){
		$pdf->SetX(62);
		$pdf->Cell(10, 5, 'Socorro' );
	}elseif($mostra['manutencao']=='4'){
		$pdf->SetX(62);
		$pdf->Cell(10, 5, 'Diversos' );
	}else{
		$pdf->SetX(62);
		$pdf->Cell(10, 5, '' );
    }


	$motoristaId = $mostra['id_motorista'];
	$motorista = mostra('veiculo_motorista',"WHERE id ='$motoristaId'");
	$pdf->SetX(80);
	$pdf->Cell(10, 5, $motorista['nome']);

	if($mostra['turno']==1){
		$pdf->SetX(140);
		$pdf->Cell(10, 5, '1º');
	 }elseif($mostra['turno']==2){
        $pdf->SetX(140);
		$pdf->Cell(10, 5, '2º');
     }elseif($mostra['turno']==3){
        $pdf->SetX(140);
		$pdf->Cell(10, 5, '3º');
	  }else{
		$pdf->SetX(140);
		$pdf->Cell(10, 5, '');
	}

	$manutencao=0;
	$concluida=0;
		
				if(!empty($mostra['descricao1'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao2'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao3'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao4'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao5'])){
					$manutencao=$manutencao+1;
				}
				if(!empty($mostra['descricao6'])){
					$manutencao=$manutencao+1;
				}
		
				if($mostra['status1']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status2']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status3']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status4']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status5']=='2'){
					$concluida=$concluida+1;
				}
				if($mostra['status6']=='2'){
					$concluida=$concluida+1;
				}
		
	$pdf->SetX(162);
	$pdf->Cell(10, 5, $manutencao);
	$pdf->SetX(185);
	$pdf->Cell(10, 5, $concluida);
	$pendencias=$manutencao-$concluida;
	$pdf->SetX(205);
	$pdf->Cell(10, 5, $pendencias);

	$manutencaoId = $mostra['id'];
		
				$valorManutencao=0;
		
				$leituraPecas = read('estoque_material_retirada',"WHERE id AND id_manutencao='$manutencaoId' ORDER BY id ASC");
				foreach($leituraPecas as $pecas):
	  
					$materialId = $pecas['id_material'];
					$material = mostra('estoque_material',"WHERE id ='$materialId'");
				
				$valorManutencao=$valorManutencao+$pecas['quantidade']*$material['valor_unitario'];
			
				 endforeach;
		
		
				if($pendencias=='0'){
					$pdf->SetX(220);
					$pdf->Cell(10, 5, 'Concluida');
					 
				}else{
					
					$pdf->SetX(220);
					$pdf->Cell(10, 5, 'Em Manutenção');
				}
		
	$pdf->SetX(260);
	$pdf->Cell(10, 5, converteValor($valorManutencao),0,0,R);
 	$totalManutencao = $totalManutencao + $valorManutencao;
			

	$i=$i+1;
	if ($i>28){
		$pdf->AddPage();   
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(5);

		$pdf->Line(10, 28, 290, 28); // insere linha divisória (Col, Lin, Col, Lin)

		$pdf->SetX(10);
		$pdf->Cell(20, 1, 'Id');

		$pdf->SetX(22);
		$pdf->Cell(20, 1, 'Placa');

		$pdf->SetX(45);
		$pdf->Cell(20, 1, 'Data');

		$pdf->SetX(65);
		$pdf->Cell(20, 1, 'Km');

		$pdf->SetX(80);
		$pdf->Cell(10, 1, 'Motorista');

		$pdf->SetX(135);
		$pdf->Cell(10, 1, 'Turno');

		$pdf->SetX(155);
		$pdf->Cell(10, 1, 'Manutenção');

		$pdf->SetX(180);
		$pdf->Cell(10, 1, 'Concluida');

		$pdf->SetX(200);
		$pdf->Cell(10, 1, 'Pendente');

		$pdf->SetX(220);
		$pdf->Cell(10, 1, 'Status');

		$pdf->SetX(250);
		$pdf->Cell(10, 1, 'Vl Manutenção');


		$pdf->Line(10, 33, 290, 33); // insere linha divisória
		
		$pdf->Ln(4);
		$i=0;
	}
endforeach;

$pdf->SetFont('Arial','B',8);
$pdf->ln(10);
$pdf->Cell(10, 5, 'Total de registros : '. $total);
	

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>