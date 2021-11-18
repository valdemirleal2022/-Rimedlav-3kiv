<?PHP
	
	if(!empty($_GET['aditivoId'])){
		
		  $aditivoId = $_GET['aditivoId'];
		  $readaditivo = read('contrato_aditivo',"WHERE id = '$aditivoId'");
		  if(!$readaditivo){
				header('Location: painel.php?execute=suporte/error'); 
		   }else{
			foreach($readaditivo as $aditivo);
		  }
		 

		$clienteId = $aditivo['id_cliente'];
		$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
		$contratoId = $aditivo['id_contrato'];
		$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
		$empresa = mostra('empresa',"WHERE id ORDER BY id DESC");
        
		
	}
    	 
?>
 
<!DOCTYPE HTML>
	
 <html>
  <head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Clean Ambiental | Painel Administrativo</title>
    
  </head> 

	<body onload="javascript:window.print();">
		
        
        <h2 align="center"> ADITIVO CONTRATUAL</h2>
       
       <h3><strong>Contratada</strong></h3>
       <p>
       Empresa : <?PHP print $empresa['nome'];  ?>
       <br>
       Endereço : <?PHP print $empresa['endereco'] .' '. $empresa['bairro'] .' '. $empresa['cidade'] .' '. $empresa['uf'];  ?>
       <br>
       CNPJ : <?PHP print $empresa['cnpj'] ;  ?>
       </p>
             
	      
      <h3><strong>Contratante</strong></h3>
       <p>
       Cliente : <?PHP print $cliente['nome'];  ?>
       <br>
       Endereço : <?PHP print $cliente['endereco'].' '.$aluno['numero'].' '.$aluno['complemento'];  ?>
       <br>
       Bairro : <?PHP print $cliente['bairro'];  ?>
       <br>
       Cidade : <?PHP print $cliente['cidade'];  ?>
       <br>
       CEP : <?PHP print $cliente['cep'];  ?>
      
       <br>
       <?PHP
		   
		   if(!empty($cliente['cnpj'])){
			    echo 'CNPJ  : '.$cliente['cnpj']; 
		   }
		    if(!empty($cliente['cpf'])){
			    echo 'CPF  : '.$cliente['cpf']; 
		   }
		   
		   ?>
      </p>
       
     
      
       <p>
	   <strong>CONTRATO ORIGINAL Nº  :</strong> <?PHP echo substr($contrato['controle'],0,6); ?>
       <br>
       <strong>EMISSÃO DO ADITIVO:  :</strong> <?PHP echo converteData($aditivo['inicio']); ?> 
       <br>
       
        </p>
		
		<p>
	   Pelo presente instrumento particular e na melhor forma de direito, fica estabelecido e apensado ao CONTRATO ORIGINAL, o ADITIVO CONTRATUAL em pauta, onde os afinal assinados, denominada no Contrato Original CONTRATANTE, e de outro denominada no Contrato Original CONTRATADA, resolvem entre si, de comum acordo, alterar o item 4 da Cláusula Quarta  que passa a ter a seguintes redação:
        </p>
		
		<p>
	CLÁUSULA QUARTA: - DAS CONDIÇÕES COMERCIAIS:

        </p>
		
		 <?PHP
			//4.1 - Frequência da Coleta:
 
			if(!empty($aditivo['frequencia_aditivo'])){
			  echo '<p>'; 
			  echo '4.1 - Frequência da Coleta: '. $aditivo['frequencia_aditivo']; 
			  echo '<br>'; 
			  echo '</p>'; 
			}
		
		?>
		
		
		 <?PHP
	 
			//4.2 – Dias de Coleta:]
 
			if(!empty($aditivo['dia_aditivo'])){
			  echo '<p>'; 
			  echo '4.2 - Dias de Coleta: '. $aditivo['dia_aditivo']; 
			  echo '<br>'; 
			  echo '</p>'; 
			}
		
		?>
		
		
		 <?PHP
		 
  			//4.3 - Endereço
			
			if(!empty($aditivo['endereco_aditivo'])){
				 
				echo '<p>'; 
			   	echo '4.3 - Endereço '.  $aditivo['endereco_aditivo']; 
			   	echo '<br>'; 
			   	echo '</p>'; 
			}
		
		?>
		
		 
		
		 <?PHP
			//4.4 – Valor a ser cobrado por (contêiner) ou saco de_________lts.:R$
			if($aditivo['valor_unitario_aditivo']>0){
				
				$tipo_coletaId = $aditivo['tipo_coleta_aditivo'];
				$tipo_coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipo_coletaId'");
				
				echo '<p>'; 
			    echo '4.4 – Valor a ser cobrado por '.  $tipo_coleta['nome']. ' R$ '. converteValor($aditivo['valor_unitario_aditivo']); 
			    echo '<br>'; 
			    echo '</p>'; 
			}
		
		?>
		
		<?PHP
		
		 //4.5 – Valor mínimo a ser cobrado mensalmente: R$
			
			if($aditivo['valor_mensal_aditivo']>0){
				echo '<p>'; 
			   	echo '4.5 – Valor mínimo a ser cobrado mensalmente: R$ '. converteValor($aditivo['valor_mensal_aditivo']); 
			   	echo '<br>'; 
			   	echo '</p>'; 
			}
		
		?>
		
		<?PHP
		
		 //4.6 – Quantidade a ser cobrada como coletada por viagem:___________________________
			
			if($aditivo['quantidade_aditivo']>0){
				$tipo_coletaId = $aditivo['tipo_coleta_aditivo'];
				$tipo_coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipo_coletaId'");
				echo '<p>'; 
			   	echo '4.6 – Quantidade a ser cobrada como coletada por viagem: '.  $tipo_coleta['nome']. ' - '. $aditivo['quantidade_aditivo']; 
			   	echo '<br>'; 
			   	echo '</p>'; 
			}
		
		?>
		
		<?PHP
		
		 //4.7 – A cada contêiner ou saco extra coletado será cobrado o valor de: R$_______________
			
			if($aditivo['valor_extra_aditivo']>0){
				$tipo_coletaId = $aditivo['tipo_coleta_aditivo'];
				$tipo_coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipo_coletaId'");
				echo '<p>'; 
			   	echo '4.7 – A cada contêiner ou saco extra coletado será cobrado o valor de: '. converteValor($aditivo['valor_extra_aditivo']); 
			   	echo '<br>'; 
			   	echo '</p>'; 
			}
		
		 //4.8  - Tipo de Coleta
			
			if($aditivo['tipo_coleta_aditivo']>0){
				$tipo_coletaId = $aditivo['tipo_coleta_aditivo'];
				$tipo_coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipo_coletaId'");
				echo '<p>'; 
			   	echo '4.8  - Tipo de Coleta : '.  $tipo_coleta['nome']; 
			   	echo '<br>'; 
			   	echo '</p>'; 
			}
		
		
		
		?>
		
		
			<p>
	 Ficam mantidas as demais Cláusulas do CONTRATO ORIGINAL, anteriores e posteriores a Cláusula   Quarta.
        </p>
		
		<p>
	E por estarem as partes de comum acordo e para que produzam os efeitos legais e jurídicos, o presente instrumento de ADITIVO DE CONTRATO DE PRESTAÇÃO DE SERVIÇOS vai assinado em duas vias de igual teor e forma, pela CONTRATANTE e pela CONTRATADA, reconhecendo as partes a forma de assinatura por meios eletrônicos e digitais como válido e plenamente eficaz, ainda que seja estabelecida com a assinatura eletrônica ou certificação foras dos padrões ICP-BRASIL, conforme disposto no art. 10 da Medida Provisória n° 2.200/2001.
        </p>
		
		  <table  class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                 	 <td align="center"> </td>
                	
                     <td align="center"> </td>
					   <td align="center"><img src="<?php setaurl();?>/site/images/assinatura-guilherme.png" alt=""/>
                     </td>
                  </tr>   
                <tr>
                      <td align="center">______________________</td>
                      <td align="center">  &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</td>
                      <td align="center">______________________</td>
                 </tr>
                  <tr>
                      <td align="center">CONTRATANTE</td>
                      <td align="center">  &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</td>
                      <td align="center">CONTRATADA</td>
                 </tr>
           </table>
		
		 
       
                     
</body>
 

<?php 
	ob_end_flush();
?>
</html> 
