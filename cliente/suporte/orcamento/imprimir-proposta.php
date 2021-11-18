<?PHP
	
	if(!empty($_GET['orcamentoId'])){
		
		  $orcamentoId = $_GET['orcamentoId'];
		  $readOrcamento = read('cadastro_visita',"WHERE id = '$orcamentoId'");
		  if(!$readOrcamento){
			echo '<div class="msgError">Orçamento Não Encontrado !</div> <br />';
			header('Location: painel.php?execute=suporte/error'); 
		   }else{
			foreach($readOrcamento as $orcamento);
		  }
		 

		$consultorId = $orcamento['consultor'];
		$readconsultor = read('contrato_consultor',"WHERE id ='$consultorId '");
		if($readconsultor){
			foreach($readconsultor as $consultor);
		}
		$readEmpresa = read('empresa');
        if($readEmpresa){
          foreach($readEmpresa as $empresa);
		}
		
	}
    	 
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
	<title>Painel Administrativo</title>
 	<meta name="language" content="pt-br"> 
	<meta name="robots" content="INDEX,FOLLOW">
     <link rel="stylesheet" type="text/css" href="../admin/css/style.css">
    <link rel="icon" type="image/png" href="ico/favico.png" />
</head>

<body onload="javascript:window.print();">

		<div class="formulario">
		  
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td><img src="<?php setaurl();?>/site/images/header-logo.png" alt=""/> </td>
                  <td></td>
                  <td></td>
                  <td><h1>Proposta Comercial</h1></td>
                  <td><h2 align="center"><?PHP echo $orcamento['id']; ?></h2></td>
              </tr>
         </table>
         <br>  
         <h2>Dados do Cliente</h2>
         
          <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td width="90">Nome:</td>
                  <td><?PHP echo $orcamento['nome']; ?></td>
              </tr>
         </table>
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="90">Endereço : </td>
                 <td><?PHP echo $orcamento['endereco'].' '.$orcamento['numero'].' '.$orcamento['complemento']; ?></td>
              </tr>
         </table>
         
          <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                   <td width="90">Bairro : </td>
                   <td><?PHP echo $orcamento['bairro']; ?></td>
                   <td width="55">Cidade : </td>
                   <td><?PHP echo $orcamento['cidade']; ?></td>
                   <td width="90">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              </tr>
         </table>
         
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                   <td width="90">Cep : </td>
                   <td><?PHP echo $orcamento['cep']; ?></td>
              </tr>
         </table>
         
          <h2>Contatos</h2>
          <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="80">Telefone : </td>
                 <td><?PHP echo $orcamento['telefone']; ?></td>
                 <td width="80">Email : </td>
                 <td><?PHP echo $orcamento['email']; ?></td>
                 <td width="80">Contato : </td>
                 <td><?PHP echo $orcamento['contato']; ?></td>
              </tr>
         </table>
         
		<h3>
        <br>  
		  Estamos enviando para vossa análise nossa proposta comercial para execução de serviços de coleta de resíduos, conforme descrito abaixo:
        <br>  
    
	    </h3>
 
       	<br>
        <h2>Proposta Comercial</h2>
        
          <br> 
        <br> 
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td width="180"><strong>Tipo de Resíduo : </strong></td>
                 <td><?PHP echo $orcamento['orc_residuo']; ?></td>
              </tr>
        </table>
          <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="180"><strong>Freqüência da Coleta :</strong></td>
                 <td><?PHP echo $orcamento['orc_frequencia']; ?></td>
              </tr>
        </table>
          <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="180"><strong>Dia da Semana  :</strong></td>
                 <td><?PHP echo $orcamento['orc_dia']; ?></td>
              </tr>
        </table>
           <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="180"><strong>Equipamento :</strong></td>
                 <td><?PHP echo $orcamento['orc_equipamento']; ?></td>
              </tr>
        </table>
          
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="180"><strong>Quantidade Mínima Diária :</strong></td>
                 <td><?PHP echo $orcamento['orc_quantidade']; ?></td>
              </tr>
        </table>
          
           <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="180"><strong>Valor Unitário R$ :</strong></td>
                 <td><?PHP echo converteValor($orcamento['orc_valor_unitario']); ?></td>
              </tr>
        </table>
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="180"><strong>Valor Extra Unitário R$ :</strong></td>
                <td><?PHP echo converteValor($orcamento['orc_valor_extra']); ?></td>
             </tr>
        </table>
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
               <td width="180"><strong>Valor Mensal R$ :</strong></td>
               <td><?PHP echo converteValor($orcamento['orc_valor']); ?></td>
              </tr>
        </table>
          <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="180"><strong>Forma de Pagamento :</strong></td>
                 <td><?PHP echo $orcamento['orc_forma_pag']; ?></td>
              </tr>
        </table>
        <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="210"><strong>Equipamento por Comodato :</strong></td>
                <td><?PHP echo $orcamento['orc_comodato']; ?></td>
              </tr>
        </table>
         <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="210"><strong>Observações :</strong></td>
                <td><?PHP echo $orcamento['orc_observacao']; ?></td>
              </tr>
        </table>

        <br>
        <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="130"><strong>Data da Proposta :</strong></td>
                 <td><?PHP echo date('d/m/Y');  ?></td>
              </tr>
        </table>
        <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="150"><strong>Validade da Proposta :</strong></td>
                 <td>30 dias, após este periodo sujeita a reavaliação do custo do serviço.</td>
              </tr>
        </table>
        
        <br> 
       	<h3>Imposto ISS 5% - a ser incluso no total da NF. </h3>
        
 
       	<br> 
       	<h3>Destino final dos resíduos em local autorizado pela COMLURB e autorizado pelos órgãos ambientais. </h3>
	   	<br>
        <br> 
		<h3>Atenciosamente, </h3>
       	<br> 
        <br> 
        <br> 
        <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="140"><strong>Consultor Técnico : </strong></td>
                 <td><?PHP echo $consultor['nome']; ?></td>
              </tr>
        </table>
        <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="70"><strong>Email : </strong></td>
                 <td><?PHP echo $consultor['email']; ?></td>
              </tr>
        </table>
        <table class="tabelaspg" id="tabelasconfig" border="0" cellspacing="0" cellpadding="0">
              <tr>
                 <td width="90"><strong>Telefone : </strong></td>
                 <td><?PHP echo  $consultor['telefone']; ?></td>
              </tr>
        </table>

        <br> 
        <br> 
       	<br>
        <h3>
       	<?PHP echo $empresa['nome'];  ?>
       	<br>
       	<?PHP echo $empresa['endereco'] .' '. $empresa['bairro'] .' '. $empresa['cidade'] .' '. $empresa['uf'];  ?>
       	<br>
       	<?PHP echo $empresa['cnpj'] ;  ?>
        </h3>
       	<br>
       </p>
    </div>
      
 </body>

<?php 
	ob_end_flush();
?>
</html> 
