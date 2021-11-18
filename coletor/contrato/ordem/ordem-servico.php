<?php
 
$empresa = mostra('empresa',"WHERE id");

if(!empty($_GET['ordemImprimir'])){
	$ordemId = $_GET['ordemImprimir'];
	$mostra=mostra('contrato_ordem',"WHERE id AND id='$ordemId'");
}
    $contratoId=$mostra['id_contrato'];
	$contrato = mostra('contrato',"WHERE id ='$contratoId'");
	
	$clienteId = $mostra['id_cliente'];
	$cliente = mostra('cliente',"WHERE id ='$clienteId '");

	$tipoColeta=$mostra['tipo_coleta1'];
	$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta'");
	$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

	

 ?>

<!doctype html>

<html>
<head>
<meta charset="iso-8859-1">
	
	<title>Painel Administrativo</title>
	
 	<meta name="language" content="pt-br"> 
	<meta name="robots" content="INDEX,FOLLOW">
    <link rel="stylesheet" type="text/css" href="../../admin/css/style.css">
    <link rel="icon" type="image/png" href="ico/favico.png" />
	
</head>

<body onload="javascript:window.print();">

	<div class="ordem">
              
         <table width="800"  class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
			 
			   <tr>
              <td width="100" align="center"><?PHP echo $empresa['nome']; ?></td>
             </tr>
			 
              <tr>
              <td width="100" align="center"><?PHP echo $empresa['telefone']; ?></td>
             </tr>
			 
			  <tr>
              <td width="100"></td>
             </tr>
		
			  <tr>
              <td width="100"></td>
             </tr>
			 
			 
			 <tr>
              <td width="100">=============================== </td>
             </tr>
			 
             <tr>
              <td width="100" align="center">ORDEM DE SERVICO </td>
             </tr>
			 
			  <tr>
              <td width="100" align="right">Número : <?PHP echo  $mostra['id']; ?></td>
             </tr>
			 
			  <tr>
              <td width="100">  </td>
             </tr>
			 <tr>
              <td width="100" align="right" style="font-size: 35px">Data/Hora: <?PHP  echo date('d/m/Y H:i:s'); ?> </td>
             </tr>
	 			<tr>
              <td width="100"> </td>
             </tr>
			  
			 <tr>
              <td width="100">=============================== </td>
             </tr>
			 
			
			 
			  </tr>
              <td width="100" align="right">Contrato : <?PHP echo $mostra['id_contrato'].'|'. substr($contrato['controle'],0,6); ?></td>
             </tr>
	
	
			 
              <tr>
        	 <td width="100"> Resíduo   : 
				 
				<?PHP
				 
				$contratoId=$mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");

 				$contratoTipo=$contrato['contrato_tipo'];
				$tipoResiduo = mostra('contrato_tipo',"WHERE id ='$contratoTipo'");
				 echo $tipoResiduo['nome']; 
				 
				 ?>
				  
				  </td>
              </tr>
			 
             
			 
              <tr>
              <td width="100"><?PHP echo  $cliente['nome'];  ?></td>
              </tr>
			 
            
	
			  <tr>
			   <td width="100"><?PHP  
				$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
				echo  $endereco;  ?></td>
             </tr>
	
			  <tr>
			  <td width="100">Data da Coleta : <?PHP echo converteData($mostra['data']); ?></td>
             </tr>

			 <tr>
			  <td width="100">Coleta : <?PHP
				  
				   $tipoColeta=$mostra['tipo_coleta1'];
				   $tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColeta'");
				  $contratoColeta = mostra('contrato_coleta',"WHERE id_contrato ='$contratoId'");

				  echo $tipoColeta['nome']; 
				  
				  ?></td>
             </tr>
			
			 <tr>
			  <td width="100">Quantidade Coletado : <?PHP echo $mostra['quantidade1']; ?></td>
             </tr>
	
	
	 		<tr>
              <td width="100"></td>
            </tr>
	 		<tr>
              <td width="100"> </td>
            </tr>
	 		<tr>
              <td width="100"> </td>
            </tr>
			<tr>
              <td width="100"></td>
            </tr>
	 		<tr>
              <td width="100"> </td>
            </tr>
	 		<tr>
              <td width="100"> </td>
            </tr>
			<tr>
              <td width="100"> </td>
            </tr>
	 		<tr>
              <td width="100"> </td>
            </tr>
	
			
	 		<tr>
              <td width="100" align="center">-------------------------------------</td>
            </tr>
	
		  	<tr>
              <td width="100" align="center">Assinatura</td>
            </tr>
	
	 		<tr>
              <td width="100">=============================== </td>
             </tr>
			 
 
          </table>
      </div>
      
 </body>

<?php 
	ob_end_flush();
?>
</html>

 
 