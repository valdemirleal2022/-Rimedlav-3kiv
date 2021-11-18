<?PHP
	 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');	
			}	
		}

		$clienteId = $_SESSION['autUser']['id'];
		$readCliente = read('cliente',"WHERE id = '$clienteId'");
		if(!$readCliente){
				header('Location: painel.php?execute=suporte/error');
		}
		foreach($readCliente as $cliente);
		
		$empresa = mostra('empresa',"");
		$contrato_modelo = mostra('contrato_modelo',"");


?>

<!DOCTYPE HTML>
	
    <html lang="pt-br">
    <head>
        <meta charset="iso-8859-1">
        <title>WPC Sistema Integrado | Contrato</title>
    </head>

	<body onload="javascript:window.print();">
		
        
       <h1 align="center">Contrato</h1>
       
       <h2><strong> Clean Ambiental</strong></h2>
       <p>
       Cliente : <?PHP print $empresa['nome'];  ?>
       <br>
       Endereço : <?PHP print $empresa['endereco'] .' '. $empresa['bairro'] .' '. $empresa['cidade'] .' '. $empresa['uf'];  ?>
       <br>
       CNPJ : <?PHP print $empresa['cnpj'] ;  ?>
       <br>
             
	      
       <h2><strong>LICENCIADA:</strong></h2>
       <p>
       Cliente : <?PHP print $cliente['nome'];  ?>
       <br>
       Endereço : <?PHP print $cliente['end_nome'];  ?>
       <br>
       CNPJ : <?PHP print $cliente['cnpj'] ;  ?>
       <br>
       Sistema : <?PHP print $sistema['nome']; ?>
       <br>
       Valor Mensal R$ : <?PHP print number_format($cliente['valor'],2,',','.'); ?>
       <br>
       Dia de Vencimento : <?PHP print $cliente['vencimento']; ?>
       <br>
       Número de Usuários : <?PHP print $cliente['num_usuarios']; ?>
       </p> 
       
       <p>
        
        <?PHP print $contrato_modelo['contrato']; ?>  
       
                     
</body>
</html>

 