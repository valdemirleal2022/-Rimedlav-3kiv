<?php
	
	session_start();
	
	if(!empty($_GET['boletoId'])){
		
        $boletoId = $_GET['boletoId'];
		$readBoleto = read('receber',"WHERE id = '$boletoId'");
		foreach($readBoleto as $boleto);
		
		// Recupera boleto 
		$id_boleto=$boleto['id'];
		$nome_banco=$boleto['banco'];
		$emissao=$boleto['emissao'];
		$id_cliente=$boleto['id_cliente'];
		$vencimento = $boleto['vencimento'];
		$emissao = implode("/",array_reverse(explode("-",$emissao)));
		$valor_normal = $boleto["valor"];
		$valor =$boleto["valor"];
		
		$readBanco = read('banco',"WHERE nome = '$nome_banco'");
		foreach($readBanco as $banco);
		
		// boleto vencido - calculando juros	
		$valor_juros_multa=0;
		$percentual_juros=$banco["juros"];
		$percentual_multa=$banco["multa"];
		
		if (($vencimento < date('Y-m-d'))) {
			$hoje=date('Y-m-d');
			$time_inicial = strtotime($vencimento);
			$time_final = strtotime($hoje);
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			
			$juros_dia = ($valor*$percentual_juros)/100;
			$juros =  (($juros_dia * $dias));
			$multa = (($percentual_multa * $valor) / 100); 
			$valor = $valor+ ($juros + $multa);
			$valor_juros_multa=$valor;
			
			// 1 dia para pagar
			$vencimento=date("Y-m-d");
			$vencimento = date("Y-m-d", strtotime("$vencimento + 1 DAYS"));
		}
	 
		$_SESSION['valor']=$valor;
		$_SESSION['id']=$boletoId;
		
	    $clienteId = $boleto['id_cliente'];
	    $readCliente = read('cliente',"WHERE id = '$clienteId'");
	    foreach($readCliente as $cliente);
		
		$_SESSION['nome']=$cliente['nome'];
		$_SESSION['email']=$cliente['email'];
		
	 }
	 
	header('Location: cartao/PagSeguro.php');
?>