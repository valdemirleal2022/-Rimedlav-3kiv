<?php

		if(!empty($_GET['orcamentoId'])){
			$orcamentoId = $_GET['orcamentoId'];
		}
		
		if(!empty($orcamentoId)){
			$servico = mostra('servico',"WHERE id = '$orcamentoId'");
			if(!$servico){
				header('Location: painel.php?execute=suporte/error');	
			}
			
			$consultorId = $servico['consultor'];
			$consultor = mostra('servico_consultor',"WHERE id ='$consultorId '");
		
			$clienteId = $servico['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
	 
			$assunto  = "Or�amento Recuperado : " . $cliente['nome'];
			$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#08a57f'>";
			$msg .= "Or�amento Recuperado <br /><br />";
			$msg .= "Proposta N&deg; : " . $servico['id'] . "<br />";
			$msg .= "Nome : " . $cliente['nome'] . "<br />";
			$msg .= "Email : " . $cliente['email'] . "<br />";
			$msg .= "Telefone : " . $cliente['telefone'] . "<br />";
			$msg .= "Inseto : " . $servico['orc_inseto'] . "<br />";
			$msg .= "Tempo de Garantia : " . $servico['orc_garantia'] . "<br />";
			$msg .= "Locais de Tratamento : " . nl2br($servico['orc_descricao']) . "<br />";
			$msg .= "Data do Or�amento : " . converteData($servico['orc_data']) . "<br />";
			$msg .= "Valor : " . $servico['orc_valor'] . "<br />";
			$msg .=  "</font>";
		 
			enviaEmail($assunto,$msg,$cliente['email'],$cliente['nome'],MAILUSER,SITENOME);
 
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = 1;
			$cad['orc_data'] = date('Y/m/d');
			$cad['situacao'] = 1;
			update('servico',$cad,"id = '$orcamentoId'");	
		}



?>

<div class="paginas">

		<div class="tituloSingle">Or�amento Recuperado </div>
            
            <div class="formulario">
            
				<?php            
           		echo '<div class="no">Or�amento Recuperado com Sucesso ! Entraremos em breve em contato para confirma a solicita��o</div>';	
            	?>
        	</div><!--/p�ginas-->
            
</div><!--/p�ginas-->
        
<?php require("inc/sidebar-pg.php");?>
