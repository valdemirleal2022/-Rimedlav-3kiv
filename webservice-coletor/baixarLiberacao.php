<?php

	require_once('../config/crud.php');
	require_once('../config/funcoes.php');

	$retorno = array();

	$liberacaoId = $_POST["liberacaoId"];
	$retorno['retorno'] = "NO";

	$leitura = read('veiculo_liberacao',"WHERE id ='$liberacaoId'"); 
	if($leitura){
		foreach($leitura as $edit);
	
 			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['checklist_motorista']= '1';
		
			$cad['conta_giro_saida']  = strip_tags(trim(mysql_real_escape_string($_POST['conta_giro_saida'])));
			$cad['conta_giro_maquinario_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['conta_giro_maquinario_saida'])));
			
			$cad['farol_alto_dir_saida']  = strip_tags(trim(mysql_real_escape_string($_POST['farol_alto_dir_saida'])));
			$cad['farol_alto_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['farol_alto_esq_saida'])));

			$cad['farol_baixo_dir_saida']  = strip_tags(trim(mysql_real_escape_string($_POST['farol_baixo_dir_saida'])));
			$cad['farol_baixo_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['farol_baixo_esq_saida'])));
			
			$cad['lanterna_dir_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['lanterna_dir_saida'])));
			$cad['lanterna_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['lanterna_esq_saida'])));
			
			$cad['pisca_dir_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['pisca_dir_saida'])));
			$cad['pisca_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['pisca_esq_saida'])));

			$cad['luz_freio_dir_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['luz_freio_dir_saida'])));
			$cad['luz_freio_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['luz_freio_esq_saida'])));

			$cad['luz_re_dir_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['luz_re_dir_saida'])));
			$cad['luz_re_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['luz_re_esq_saida'])));
			
			$cad['strobo_dir_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['strobo_dir_saida'])));
			$cad['strobo_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['strobo_esq_saida'])));

			
			$cad['limpador_dir_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['limpador_dir_saida'])));
			$cad['limpador_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['limpador_esq_saida'])));
	
			
			$cad['pneu_dir_saida'] = strip_tags(trim(mysql_real_escape_string($_POST['pneu_dir_saida'])));
			$cad['pneu_esq_saida'] = strip_tags(trim(mysql_real_escape_string($_POST['pneu_esq_saida'])));
			$cad['retrovisor_dir_saida']  = strip_tags(trim(mysql_real_escape_string($_POST['retrovisor_dir_saida'])));
			$cad['retrovisor_esq_saida']  = strip_tags(trim(mysql_real_escape_string($_POST['retrovisor_esq_saida'])));
			$cad['parachoque_dir_saida']  = strip_tags(trim(mysql_real_escape_string($_POST['parachoque_dir_saida'])));
			$cad['parachoque_esq_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['parachoque_esq_saida'])));
			$cad['embreagem_saida']  =  strip_tags(trim(mysql_real_escape_string($_POST['embreagem_saida'])));
			$cad['mola_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['mola_saida'])));
			$cad['maquinario_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['maquinario_saida'])));
			$cad['lifter_240_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['lifter_240_saida'])));
			if(empty($cad['lifter_240_saida'])){
							$cad['lifter_240_saida']=null;
			}

			$cad['lifter_12_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['lifter_12_saida'])));
			if(empty($cad['lifter_12_saida'])){
				$cad['lifter_12_saida']=null;
			}
 				
			$cad['cilindro_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['cilindro_saida'])));
 	
			$cad['mangueira_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['mangueira_saida'])));
			
			$cad['valvula_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['valvula_saida'])));
 
 			$cad['comando_dianteiro_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['comando_dianteiro_saida'])));
			
			$cad['comando_traseiro_saida']=  strip_tags(trim(mysql_real_escape_string($_POST['comando_traseiro_saida'])));
 
 			$cad['bomba_saida']= strip_tags(trim(mysql_real_escape_string($_POST['bomba_saida'])));
 
$cad['reservatorio_saida']= strip_tags(trim(mysql_real_escape_string($_POST['reservatorio_saida'])));
 	
$cad['caixa_saida']= strip_tags(trim(mysql_real_escape_string($_POST['caixa_saida'])));
 			
$cad['limpeza_cabine_saida']= strip_tags(trim(mysql_real_escape_string($_POST['limpeza_cabine_saida'])));
 	
$cad['fita_saida']= strip_tags(trim(mysql_real_escape_string($_POST['fita_saida'])));
			
$cad['vassoura_saida']= strip_tags(trim(mysql_real_escape_string($_POST['vassoura_saida'])));
 			
$cad['pa_saida']= strip_tags(trim(mysql_real_escape_string($_POST['pa_saida'])));
 	
$cad['freio_saida']= strip_tags(trim(mysql_real_escape_string($_POST['freio_saida'])));
			
$cad['triangulo_saida']= strip_tags(trim(mysql_real_escape_string($_POST['triangulo_saida'])));
 			
$cad['extintor_saida']= strip_tags(trim(mysql_real_escape_string($_POST['extintor_saida'])));
 			
$cad['tacografo_saida']= strip_tags(trim(mysql_real_escape_string($_POST['tacografo_saida'])));
$cad['km_saida']= strip_tags(trim(mysql_real_escape_string($_POST['km_saida'])));
$cad['observacao_saida']= strip_tags(trim(mysql_real_escape_string($_POST['observacao_saida'])));
	
			update('veiculo_liberacao',$cad,"id = '$liberacaoId'");
			 
		
			$retorno['retorno'] = "YES";
			echo json_encode($retorno);
		
	}else{
		
		$retorno['retorno'] = "NO";		
		
		
	}

	
?>