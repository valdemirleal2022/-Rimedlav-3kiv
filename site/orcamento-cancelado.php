<?php

		if(!empty($_GET['orcamentoId'])){
			$orcamentoId = $_GET['orcamentoId'];
		}
		
		if(!empty($orcamentoId)){
			$orcamento = mostra('cadastro_visita',"WHERE id = '$orcamentoId'");
			if(!$orcamento){
				header('Location: painel.php?execute=suporte/error');	
			}
			
			$consultorId = $servico['consultor'];
			$consultor = mostra('cadastro_visita',"WHERE id ='$consultorId '");
	 
			$assunto  = "Orçamento Cancelado pelo Cliente : " . $cliente['nome'];
			$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#08a57f'>";
			$msg .= "Orçamento Cancelado pelo Cliente <br /><br />";
			$msg .= "Proposta N&deg; : " . $orcamento['id'] . "<br />";
			$msg .= "Nome : " . $orcamento['nome'] . "<br />";
			$msg .= "Email : " . $orcamento['email'] . "<br />";
			$msg .= "Telefone : " . $orcamento['telefone'] . "<br />";
			$msg .= "Data do Orçamento : " . converteData($orcamento['orc_data']) . "<br />";
			$msg .= "Valor : " . $orcamento['orc_valor'] . "<br />";
			$msg .=  "</font>";
		 
			enviaEmail($assunto,$msg,$cliente['email'],$cliente['nome'],MAILUSER,SITENOME);
			enviaEmail($assunto,$msg,$cliente['email'],$cliente['nome'],$consultor['email'],$consultor['nome']);
			
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = 13;
			$cad['situacao'] = 4;
			update('servico',$cad,"id = '$orcamentoId'");	
		}



?>

<div class="paginas">

		<div class="tituloSingle">Atendimento Cancelado </div>
            
            <div class="formulario">
            
				<?php            
           		echo '<div class="no">Orçamento Cancelado com Sucesso  !</div>';	
            	?>
        	</div><!--/páginas-->
            
</div><!--/páginas-->
        
<?php require("inc/sidebar-pg.php");?>
