<?php


require_once("js/fpdf/fpdf.php");
define("FPDF_FONTPATH","font/");

$mesq = "20"; // Margem Esquerda (mm)
$mdir = "10"; // Margem Direita (mm)
$msup = "2"; // Margem Superior (mm)
$leti = "89"; // Largura da Etiqueta (mm)
$aeti = "26,00"; // Altura da Etiqueta (mm)
$ehet = "30"; // Espaço horizontal entre as Etiquetas (mm)
	
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','I',6);
$pdf->SetMargins('5','5'); // Define as margens do documento

$coluna = 0;
$linha = 0;

 $leitura = read( 'cliente', "WHERE id ORDER BY cep ASC, endereco ASC, numero ASC" );


if(!empty($_GET['clienteEditar'])){
	$clienteId = $_GET['clienteEditar'];
	$leitura = read('cliente',"WHERE id = '$clienteId'");
	if(!$leitura){
		header('Location: painel.php?execute=suporte/error');
	}
}


foreach($leitura as $mostra):

		$nome = $mostra['nome'];
		$endereco = $mostra['endereco']. "  " . $mostra['numero'] . "  " . $mostra['complemento'];
		$bairro = $mostra['bairro']. "  " . $mostra['cidade'];
		$cep = "Cep : " .  $mostra['cep'] .'  ';
	
	//	$contato = "A/C Sindico";
		

		if($linha == "11") {
			$pdf->AddPage();
			$linha = 0;
		}
		
		if($coluna == "2") { // Se for a terceira coluna
			$coluna = 0; // $coluna volta para o valor inicial
			$linha = $linha +1; // $linha é igual ela mesma +1
		}
		
		if($linha == "11") { // Se for a última linha da página
			$pdf->AddPage(); // Adiciona uma nova página
			$linha = 0; // $linha volta ao seu valor inicial
		}
		
		$posicaoV = $linha*$aeti;
		$posicaoH = $coluna*$leti;
		
		if($coluna == "0") { // Se a coluna for 0
			$somaH = $mesq; // Soma Horizontal é apenas a margem da esquerda inicial
		 }else{ // Senão
		  $somaH = $mesq+$posicaoH; // Soma Horizontal é a margem inicial mais a posiçãoH
		}
		
		if($linha =="0") { // Se a linha for 0
			$somaV = $msup; // Soma Vertical é apenas a margem superior inicial
		 } else { // Senão
		  $somaV = $msup+$posicaoV; // Soma Vertical é a margem superior inicial mais a posiçãoV
		}
		
		$pdf->Text($somaH,$somaV,$nome); // Imprime o nome da pessoa de acordo com as coordenadas
		$pdf->Text($somaH,$somaV+4,$endereco); // Imprime o endereço da pessoa de acordo com as coordenadas
		$pdf->Text($somaH,$somaV+8,$bairro); // Imprime a localidade da pessoa de acordo com as coordenadas
		$pdf->Text($somaH,$somaV+12,$cep); // Imprime o cep da pessoa de acordo com as coordenadas
		$pdf->Text($somaH,$somaV+16,$contato); // Imprime o cep da pessoa de acordo com as coordenadas
			
		$coluna = $coluna+1;
endforeach;

ob_clean();  
$pdf->Output('relatorio.pdf', 'I');
 
?>