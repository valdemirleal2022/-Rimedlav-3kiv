<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		$edit['saida'] = date('Y-m-d');
		$edit['saida_hora']= date('H:i');
		$edit['interacao']= date('Y/m/d H:i:s');

		if(!empty($_GET['liberacaoEditar'])){
			$liberacaoId = $_GET['liberacaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['liberacaoBaixar'])){
			$liberacaoId = $_GET['liberacaoBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['liberacaoVisualizar'])){
			$liberacaoId = $_GET['liberacaoVisualizar'];
			$acao = "visualizar";
		}
		if(!empty($_GET['liberacaoDeletar'])){
			$liberacaoId = $_GET['liberacaoDeletar'];
			$acao = "deletar";
		}

		if(!empty($liberacaoId)){
			$readliberacao= read('veiculo_liberacao',"WHERE id = '$liberacaoId'");
			if(!$readliberacao){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readliberacao as $edit);
			
			$edit['saida_hora']=date('H:i', strtotime($edit['saida_hora']));
			$edit['chegada_hora']=date('H:i', strtotime($edit['chegada_hora']));
			
			$saida   = $edit['saida'].' '.$edit['saida_hora'];
			$chegada = $edit['chegada'].' '.$edit['chegada_hora'];			

			$saida   = strtotime($saida);
			$chegada = strtotime($chegada);		

			$minutos_iniciais = $saida/60;
			$minutos_finais = $chegada/60;
			$intervalo_minutos = $minutos_finais - $minutos_iniciais;
			$intervalo_minutos = $intervalo_minutos;

			$horas = (int) ($intervalo_minutos/60);
			$minutos = $intervalo_minutos- ($horas*60) ;
			$totalHoras = $horas.' Horas e ' . $minutos.' Minutos';
		}


		if ($edit['motorista_diarista'] == "1") {
			$edit['motorista_diarista'] = "checked='CHECKED'";
		} else {
			$edit['motorista_diarista'] = "";
		}

		if ($edit['ajudante1_diarista'] == "1") {
			$edit['ajudante1_diarista'] = "checked='CHECKED'";
		} else {
			$edit['ajudante1_diarista'] = "";
		}

		if ($edit['ajudante2_diarista'] == "1") {
			$edit['ajudante2_diarista'] = "checked='CHECKED'";
		} else {
			$edit['ajudante2_diarista'] = "";
		}

 ?>
 
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Liberações</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="painel.php?execute=suporte/veiculos/liberacoes">Liberações</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
	
  <section class="content">
      <div class="box box-default">
		  
            <div class="box-header with-border">
				
                <h3 class="box-title"><small><?php echo $edit['nome'];?></small></h3>
				
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
          		
          		 <div class="box-header">  
					 
                   <a href="painel.php?execute=suporte/veiculo/liberacao&liberacaoImprimir=<?PHP echo $liberacaoId; ?>" 
            		class="btnnovo" target="_blank">
						<img src="ico/imprimir.png" alt="Imprimir Liberação" title="Imprimir Liberação"  />
					 Imprimir Liberação
					</a>
	
      			  </div><!-- /.box-header -->
      	  
      	  
     	 <div class="box-body">
      
	<?php 
			 
		if(isset($_POST['atualizar'])){
			
			$cad['id_veiculo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['saida'] 	= strip_tags(trim(mysql_real_escape_string($_POST['saida'])));
 			$cad['saida_hora']	= strip_tags(trim(mysql_real_escape_string($_POST['saida_hora'])));
			$cad['motorista'] 	= strip_tags(trim(mysql_real_escape_string($_POST['motorista'])));
			$cad['rota'] 	= strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
			$cad['aterro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['aterro'])));
			
			if(in_array('',$cad)){
				
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
				
			  }else{
				
				$cad['chegada'] 	= strip_tags(trim(mysql_real_escape_string($_POST['chegada'])));
				$cad['chegada_hora']= strip_tags(trim(mysql_real_escape_string($_POST['chegada_hora'])));
				$cad['km_saida'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km_saida'])));
				$cad['km_chegada'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km_chegada'])));
				$cad['pesagem'] 	= strip_tags(trim(mysql_real_escape_string($_POST['pesagem'])));
				$cad['ocorrencia'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ocorrencia'])));
				$cad['interacao']= date('Y/m/d H:i:s');
				
				$cad['ajudante1'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ajudante1'])));
				$cad['ajudante2'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ajudante2'])));
				
				$cad['motorista_diarista'] 	= mysql_real_escape_string($_POST['motorista_diarista']);
				$cad['ajudante1_diarista'] 	= mysql_real_escape_string($_POST['ajudante1_diarista']);
				$cad['ajudante2_diarista'] 	= mysql_real_escape_string($_POST['ajudante2_diarista']);

				$saida   = $cad['saida'].' '.$cad['saida_hora'];
				$chegada = $cad['chegada'].' '.$cad['chegada_hora'];			

				$saida   = strtotime($saida);
				$chegada = strtotime($chegada);		

				$minutos_iniciais = $saida/60;
				$minutos_finais = $chegada/60;
				$intervalo_minutos = $minutos_finais - $minutos_iniciais;
				$horasTrabalhadas = $intervalo_minutos;
				$cad['horas_trabalhadas']= $horasTrabalhadas;
				
				update('veiculo_liberacao',$cad,"id = '$liberacaoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/liberacoes');
				unset($cad);
			}
		}
		
		if(isset($_POST['cadastrar'])){
			
			$cad['id_veiculo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['motorista'] 	= strip_tags(trim(mysql_real_escape_string($_POST['motorista'])));
			$cad['rota'] 	= strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
			$cad['aterro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['aterro'])));
			
			if(in_array('',$cad)){
	
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				
				$edit['id_veiculo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
				$edit['motorista'] 	= strip_tags(trim(mysql_real_escape_string($_POST['motorista'])));
				$edit['rota'] 	= strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
				$edit['aterro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['aterro'])));
			
			  }else{
				
				$cad['saida'] 	= date('Y/m/d H:i');
				$cad['saida_hora'] 	= date('H:i');
		
				$cad['chegada'] 	= strip_tags(trim(mysql_real_escape_string($_POST['chegada'])));
				$cad['chegada_hora'] = strip_tags(trim(mysql_real_escape_string($_POST['chegada_hora'])));
				$cad['km_saida'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km_saida'])));
				$cad['km_chegada'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km_chegada'])));
				$cad['pesagem'] 	= strip_tags(trim(mysql_real_escape_string($_POST['pesagem'])));
				$cad['ocorrencia'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ocorrencia'])));
				$cad['ajudante1'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ajudante1'])));
				$cad['ajudante2'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ajudante2'])));
				$cad['status']= 'Em Aberto';
				$cad['interacao']= date('Y/m/d H:i:s');
				
				$cad['motorista_diarista'] 	= mysql_real_escape_string($_POST['motorista_diarista']);
				$cad['ajudante1_diarista'] 	= mysql_real_escape_string($_POST['ajudante1_diarista']);
				$cad['ajudante2_diarista'] 	= mysql_real_escape_string($_POST['ajudante2_diarista']);
			
				
				create('veiculo_liberacao',$cad);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/liberacoes');
				unset($cad);
			}
		}
			 
		if(isset($_POST['baixar'])){
			
			$cad['id_veiculo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$cad['motorista'] 	= strip_tags(trim(mysql_real_escape_string($_POST['motorista'])));
			$cad['km_saida'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km_saida'])));
			$cad['km_chegada'] 	= strip_tags(trim(mysql_real_escape_string($_POST['km_chegada'])));
			$cad['rota'] 	= strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
			$cad['aterro'] 	= strip_tags(trim(mysql_real_escape_string($_POST['aterro'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			 }elseif($cad['km_chegada'] < $cad['km_saida'] ){
					echo '<div class="alert alert-warning">KM de chegada Errada!</div>'.'<br>';
			  }else{
				
				$cad['saida'] 	= strip_tags(trim(mysql_real_escape_string($_POST['saida'])));
 				$cad['saida_hora']	= strip_tags(trim(mysql_real_escape_string($_POST['saida_hora'])));
				
				$cad['chegada'] 	= strip_tags(trim(mysql_real_escape_string($_POST['chegada'])));
				$cad['chegada_hora']= strip_tags(trim(mysql_real_escape_string($_POST['chegada_hora'])));
				$cad['pesagem'] 	= strip_tags(trim(mysql_real_escape_string($_POST['pesagem'])));
				$cad['ocorrencia'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ocorrencia'])));
				$cad['interacao']= date('Y/m/d H:i:s');
				
				$cad['ajudante1'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ajudante1'])));
				$cad['ajudante2'] 	= strip_tags(trim(mysql_real_escape_string($_POST['ajudante2'])));
				
				$cad['motorista_diarista'] 	= mysql_real_escape_string($_POST['motorista_diarista']);
				$cad['ajudante1_diarista'] 	= mysql_real_escape_string($_POST['ajudante1_diarista']);
				$cad['ajudante2_diarista'] 	= mysql_real_escape_string($_POST['ajudante2_diarista']);
				
				$cad['status']= 'Baixado';

				$saida   = $cad['saida'].' '.$cad['saida_hora'];
				$chegada = $cad['chegada'].' '.$cad['chegada_hora'];			

				$saida   = strtotime($saida);
				$chegada = strtotime($chegada);		

				$minutos_iniciais = $saida/60;
				$minutos_finais = $chegada/60;
				$intervalo_minutos = $minutos_finais - $minutos_iniciais;
				$horasTrabalhadas = $intervalo_minutos;
				$cad['horas_trabalhadas']= $horasTrabalhadas;
				
				update('veiculo_liberacao',$cad,"id = '$liberacaoId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/veiculo/liberacoes');
				unset($cad);
			}
		}
		
		if(isset($_POST['deletar'])){
			delete('veiculo_liberacao',"id = '$liberacaoId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/liberacoes');
		}
			 
			 
		if(isset($_POST['atualizar-check'])){
			
$cad['conta_giro_saida']  = mysql_real_escape_string($_POST['conta_giro_saida']);
$cad['conta_giro_chegada']  = mysql_real_escape_string($_POST['conta_giro_chegada']);
$cad['conta_giro_maquinario_saida']= mysql_real_escape_string($_POST['conta_giro_maquinario_saida']);
$cad['conta_giro_maquinario_chegada']= mysql_real_escape_string($_POST['conta_giro_maquinario_chegada']);
			
			$cad['farol_alto_dir_saida']  = mysql_real_escape_string($_POST['farol_alto_dir_saida']);
			$cad['farol_alto_esq_saida']  = mysql_real_escape_string($_POST['farol_alto_esq_saida']);
			$cad['farol_alto_dir_chegada']= mysql_real_escape_string($_POST['farol_alto_dir_chegada']);
			$cad['farol_alto_esq_chegada']= mysql_real_escape_string($_POST['farol_alto_esq_chegada']);
			
			$cad['farol_baixo_dir_saida']  = mysql_real_escape_string($_POST['farol_baixo_dir_saida']);
			$cad['farol_baixo_esq_saida']  = mysql_real_escape_string($_POST['farol_baixo_esq_saida']);
			$cad['farol_baixo_dir_chegada']= mysql_real_escape_string($_POST['farol_baixo_dir_chegada']);
			$cad['farol_baixo_esq_chegada']= mysql_real_escape_string($_POST['farol_baixo_esq_chegada']);
			
			$cad['lanterna_dir_saida']  = mysql_real_escape_string($_POST['lanterna_dir_saida']);
			$cad['lanterna_esq_saida']  = mysql_real_escape_string($_POST['lanterna_esq_saida']);
			$cad['lanterna_dir_chegada']= mysql_real_escape_string($_POST['lanterna_dir_chegada']);
			$cad['lanterna_esq_chegada']= mysql_real_escape_string($_POST['lanterna_esq_chegada']);
			
			$cad['pisca_dir_saida']  = mysql_real_escape_string($_POST['pisca_dir_saida']);
			$cad['pisca_esq_saida']  = mysql_real_escape_string($_POST['pisca_esq_saida']);
			$cad['pisca_dir_chegada']= mysql_real_escape_string($_POST['pisca_dir_chegada']);
			$cad['pisca_esq_chegada']= mysql_real_escape_string($_POST['pisca_esq_chegada']);
			
			$cad['luz_freio_dir_saida']  = mysql_real_escape_string($_POST['luz_freio_dir_saida']);
			$cad['luz_freio_esq_saida']  = mysql_real_escape_string($_POST['luz_freio_esq_saida']);
			$cad['luz_freio_dir_chegada']= mysql_real_escape_string($_POST['luz_freio_dir_chegada']);
			$cad['luz_freio_esq_chegada']= mysql_real_escape_string($_POST['luz_freio_esq_chegada']);
			
			$cad['luz_re_dir_saida']  = mysql_real_escape_string($_POST['luz_re_dir_saida']);
			$cad['luz_re_esq_saida']  = mysql_real_escape_string($_POST['luz_re_esq_saida']);
			$cad['luz_re_dir_chegada']= mysql_real_escape_string($_POST['luz_re_dir_chegada']);
			$cad['luz_re_esq_chegada']= mysql_real_escape_string($_POST['luz_re_esq_chegada']);
			
			$cad['strobo_dir_saida']  = mysql_real_escape_string($_POST['strobo_dir_saida']);
			$cad['strobo_esq_saida']  = mysql_real_escape_string($_POST['strobo_esq_saida']);
			$cad['strobo_dir_chegada']= mysql_real_escape_string($_POST['strobo_dir_chegada']);
			$cad['strobo_esq_chegada']= mysql_real_escape_string($_POST['strobo_esq_chegada']);
			
			$cad['limpador_dir_saida']  = mysql_real_escape_string($_POST['limpador_dir_saida']);
			$cad['limpador_esq_saida']  = mysql_real_escape_string($_POST['limpador_esq_saida']);
			$cad['limpador_dir_chegada']= mysql_real_escape_string($_POST['limpador_dir_chegada']);
			$cad['limpador_esq_chegada']= mysql_real_escape_string($_POST['limpador_esq_chegada']);
			
			$cad['pneu_dir_saida']  = mysql_real_escape_string($_POST['pneu_dir_saida']);
			$cad['pneu_esq_saida']  = mysql_real_escape_string($_POST['pneu_esq_saida']);
			$cad['pneu_dir_chegada']= mysql_real_escape_string($_POST['pneu_dir_chegada']);
			$cad['pneu_esq_chegada']= mysql_real_escape_string($_POST['pneu_esq_chegada']);
			
			$cad['retrovisor_dir_saida']  = mysql_real_escape_string($_POST['retrovisor_dir_saida']);
			$cad['retrovisor_esq_saida']  = mysql_real_escape_string($_POST['retrovisor_esq_saida']);
			$cad['retrovisor_dir_chegada']= mysql_real_escape_string($_POST['retrovisor_dir_chegada']);
			$cad['retrovisor_esq_chegada']= mysql_real_escape_string($_POST['retrovisor_esq_chegada']);
 			
			$cad['parachoque_dir_saida']  = mysql_real_escape_string($_POST['parachoque_dir_saida']);
			$cad['parachoque_esq_saida']  = mysql_real_escape_string($_POST['parachoque_esq_saida']);
			$cad['parachoque_dir_chegada']= mysql_real_escape_string($_POST['parachoque_dir_chegada']);
			$cad['parachoque_esq_chegada']= mysql_real_escape_string($_POST['parachoque_esq_chegada']);
			
			$cad['embreagem_saida']  = mysql_real_escape_string($_POST['embreagem_saida']);
			$cad['embreagem_chegada']  = mysql_real_escape_string($_POST['embreagem_chegada']);
			
			$cad['mola_saida']= mysql_real_escape_string($_POST['mola_saida']);
			$cad['mola_chegada']= mysql_real_escape_string($_POST['mola_chegada']);
			
			$cad['maquinario_saida']= mysql_real_escape_string($_POST['maquinario_saida']);
			$cad['maquinario_chegada']= mysql_real_escape_string($_POST['maquinario_chegada']);
			
			$cad['lifter_240_saida']= mysql_real_escape_string($_POST['lifter_240_saida']);
			$cad['lifter_240_chegada']= mysql_real_escape_string($_POST['lifter_240_chegada']);
			
			$cad['lifter_12_saida']= mysql_real_escape_string($_POST['lifter_12_saida']);
			$cad['lifter_12_chegada']= mysql_real_escape_string($_POST['lifter_12_chegada']);
 				
			$cad['cilindro_saida']= mysql_real_escape_string($_POST['cilindro_saida']);
			$cad['cilindro_chegada']= mysql_real_escape_string($_POST['cilindro_chegada']);
 	
			$cad['mangueira_saida']= mysql_real_escape_string($_POST['mangueira_saida']);
			$cad['mangueira_chegada']= mysql_real_escape_string($_POST['mangueira_chegada']);
			
			$cad['valvula_saida']= mysql_real_escape_string($_POST['valvula_saida']);
			$cad['valvula_chegada']= mysql_real_escape_string($_POST['valvula_chegada']);
 
 			$cad['comando_dianteiro_saida']= mysql_real_escape_string($_POST['comando_dianteiro_saida']);
		$cad['comando_dianteiro_chegada']= mysql_real_escape_string($_POST['comando_dianteiro_chegada']);
			
			$cad['comando_traseiro_saida']= mysql_real_escape_string($_POST['comando_traseiro_saida']);
			$cad['comando_traseiro_chegada']= mysql_real_escape_string($_POST['comando_traseiro_chegada']);
 
 			$cad['bomba_saida']= mysql_real_escape_string($_POST['bomba_saida']);
			$cad['bomba_chegada']= mysql_real_escape_string($_POST['bomba_chegada']);
 
			$cad['reservatorio_saida']= mysql_real_escape_string($_POST['reservatorio_saida']);
			$cad['reservatorio_chegada']= mysql_real_escape_string($_POST['reservatorio_chegada']);
 	
 			$cad['caixa_saida']= mysql_real_escape_string($_POST['caixa_saida']);
			$cad['caixa_chegada']= mysql_real_escape_string($_POST['caixa_chegada']);
 			
			$cad['limpeza_cabine_saida']= mysql_real_escape_string($_POST['limpeza_cabine_saida']);
			$cad['limpeza_cabine_chegada']= mysql_real_escape_string($_POST['limpeza_cabine_chegada']);
 	
			$cad['fita_saida']= mysql_real_escape_string($_POST['fita_saida']);
			$cad['fita_chegada']= mysql_real_escape_string($_POST['fita_chegada']);
			
			$cad['vassoura_saida']= mysql_real_escape_string($_POST['vassoura_saida']);
			$cad['vassoura_chegada']= mysql_real_escape_string($_POST['vassoura_chegada']);
 			
			$cad['pa_saida']= mysql_real_escape_string($_POST['pa_saida']);
			$cad['pa_chegada']= mysql_real_escape_string($_POST['pa_chegada']);
 	
 			$cad['freio_saida']= mysql_real_escape_string($_POST['freio_saida']);
			$cad['freio_chegada']= mysql_real_escape_string($_POST['freio_chegada']);
			
			$cad['triangulo_saida']= mysql_real_escape_string($_POST['triangulo_saida']);
			$cad['triangulo_chegada']= mysql_real_escape_string($_POST['triangulo_chegada']);
 			
			$cad['extintor_saida']= mysql_real_escape_string($_POST['extintor_saida']);
			$cad['extintor_chegada']= mysql_real_escape_string($_POST['extintor_chegada']);
 			
			$cad['tacografo_saida']= mysql_real_escape_string($_POST['tacografo_saida']);
			$cad['tacografo_chegada']= mysql_real_escape_string($_POST['tacografo_chegada']);
			
			$cad['observacao_saida']= mysql_real_escape_string($_POST['observacao_saida']);
 			$cad['observacao_chegada']= mysql_real_escape_string($_POST['observacao_chegada']);

			update('veiculo_liberacao',$cad,"id = '$liberacaoId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/liberacoes');
			unset($cad);
			 
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
    	<div class="box-header with-border">
              <h3 class="box-title">Saída do Veículo</h3>
         </div><!-- /.box-header -->
         
         <div class="box-body">
           <div class="row">
    
  			<div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
            <div class="form-group col-xs-12 col-md-2">  
               	<label>Interação</label>
   				<input name="orc_resposta" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div> 
            
           <div class="form-group col-xs-12 col-md-3">  
            <label>Veículo (*)</label>
                <select name="id_veiculo" class="form-control">
                    <option value="">Veículo (*)</option>
                    <?php 
                        $readConta = read('veiculo',"WHERE id ORDER BY modelo ASC, placa DESC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_veiculo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['placa'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['placa'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 

           <div class="form-group col-xs-12 col-md-3">  
                 <label>Saída (*)</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="saida" class="form-control pull-right" value="<?php echo $edit['saida'];?>"   />
                </div><!-- /.input group -->
           </div> 
           
           <div class="form-group col-xs-12 col-md-2">  
          		<label>Hora da Saída (*)</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" name="saida_hora" class="form-control pull-right"  value="<?php echo $edit['saida_hora'];?>" />
                 </div><!-- /.input group -->
           </div>
			   
			   
		 <div class="form-group col-xs-12 col-md-2">  
            <label>Rota (*)</label>
                <select name="rota" class="form-control">
                    <option value="">Rota</option>
                    <?php 
                        $readConta = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['rota'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
			   
		    <div class="form-group col-xs-12 col-md-10">  
            <label>Aterro (*)</label>
                <select name="aterro" class="form-control">
                    <option value="">Aterro</option>
                    <?php 
                        $readConta = read('aterro',"WHERE id ORDER BY id ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos aterro no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['aterro'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
           
           <div class="form-group col-xs-12 col-md-4">  
            <label>Motorista (*) </label>
                <select name="motorista" class="form-control">
                    <option value="">Motorista</option>
                    <?php 
                        $readConta = read('veiculo_motorista',"WHERE tipo='1' ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['motorista'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
			   
		  <div class="form-group col-xs-12 col-md-4">  
            <label>Coletor (1) </label>
                <select name="ajudante1" class="form-control">
                    <option value="">Ajudante</option>
                    <?php 
                        $readConta = read('veiculo_motorista',"WHERE tipo='2' ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['ajudante1'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
              
           <div class="form-group col-xs-12 col-md-4">  
            <label>Coletor (2) </label>
                <select name="ajudante2" class="form-control">
                    <option value="">Ajudante</option>
                    <?php 
                        $readConta = read('veiculo_motorista',"WHERE tipo='2' ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos motorista no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['ajudante2'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
			   
			   
			     <!-- parcial-->
               <div class="form-group col-xs-12 col-md-4">
                   <input name="motorista_diarista" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['motorista_diarista']; ?>  class="minimal"  <?php echo $disabled;?> >
                Mototista Diarista
              </div> 
			   
			    <!-- parcial-->
               <div class="form-group col-xs-12 col-md-4">
                   <input name="ajudante1_diarista" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['ajudante1_diarista']; ?>  class="minimal"  <?php echo $disabled;?> >
                Coletor (1) Diarista
              </div> 
	
			       <!-- parcial-->
               <div class="form-group col-xs-12 col-md-4">
                   <input name="ajudante2_diarista" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['ajudante2_diarista']; ?>  class="minimal"  <?php echo $disabled;?> >
                Coletor (2) Diarista
              </div> 
 
        </div><!-- /.row -->
       </div><!-- /.box-body -->
       
        <div class="box-header with-border">
            <h3 class="box-title">Chegada do Veículo</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
         	<div class="row">
              
              <div class="form-group col-xs-12 col-md-3">  
                 <label>Chegada</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="chegada" class="form-control pull-right" value="<?php echo $edit['chegada'];?>"/>
                </div><!-- /.input group -->
           </div> 
           
           <div class="form-group col-xs-12 col-md-3">  
          		<label>Hora da Chegada</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" name="chegada_hora" class="form-control pull-right"  value="<?php echo $edit['chegada_hora'];?>"/>
                 </div><!-- /.input group -->
           </div>
              
            <div class="form-group col-xs-12 col-md-2"> 
                 <label>Km Saida</label>
                 <input type="text" name="km_saida" value="<?php  echo $edit['km_saida'];?>" class="form-control" />
     	    </div>

           <div class="form-group col-xs-12 col-md-2"> 
                 <label>Km Chegada</label>
                 <input type="text" name="km_chegada" value="<?php  echo $edit['km_chegada'];?>" class="form-control" />
     	    </div>
     	    
     	     <div class="form-group col-xs-12 col-md-2"> 
                 <label>Pesagem</label>
                 <input type="text" name="pesagem" value="<?php  echo $edit['pesagem'];?>" class="form-control" />
     	    </div>
     	    
     	     <div class="form-group col-xs-12 col-md-12"> 
                 <label>Ocorrencias</label>
                 <input type="text" name="ocorrencia" value="<?php  echo $edit['ocorrencia'];?>" class="form-control" />
     	    </div>
				
			  <div class="form-group col-xs-12 col-md-12"> 
                 <label>Total de Horas</label>
                 <input type="text" value="<?php  echo $totalHoras;?>" class="form-control" />
     	    </div>
				

          </div><!-- /.row -->
       </div><!-- /.box-body -->
		
		<div class="box-footer">
				 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
					  <?php 
						if($acao=="baixar"){
							echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-success" />';	
						}
						 if($acao=="atualizar"){
							echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
						}
						if($acao=="deletar"){
						 	echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
						}
						if($acao=="cadastrar"){
							echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
						}
						if($acao=="enviar"){
							echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
						}
					 ?>  
         	 </div> 
          
         </div>
 
		</form>
				
	 
      </div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
	
   <section class="content">
      <div class="box box-default"> 
 <div class="box-body">
 	<section><!-- /.content -->
		
	 <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  
		<div class="box-header with-border">
            <h3 class="box-title">Check List de Equipamento do Veículo</h3>
        </div><!-- /.box-header -->
		  
		 <div class="box-body">
         	<div class="row">
				
				
               <div class="form-group col-xs-12 col-md-12">
                   <input name="checklist_motorista" type="checkbox" 
						   <?php echo ($edit['checklist_motorista'] == "1") ? "checked" : null;?> readonly >
                Checklist Motorista App
              </div> 
				
            </div><!-- /.row -->
       </div><!-- /.box-body -->   
		 
         <div class="box-body">
         	<div class="row">
				
				<div class="box-header with-border">
					<h3 class="box-title">1 - CONTA GIRO DO MOTOR</h3>
				</div><!-- /.box-header -->

				<div class="form-group col-xs-12 col-md-3"> 
					 <label>Motor ligado (Saida)</label>
					 <input type="text" name="conta_giro_saida" value="<?php  echo $edit['conta_giro_saida'];?>" class="form-control"  />
				</div>

				<div class="form-group col-xs-12 col-md-3"> 
					 <label>Motor ligado (Chegada)</label>
					 <input type="text" name="conta_giro_chegada" value="<?php  echo $edit['conta_giro_saida'];?>" class="form-control" />
				</div>

				<div class="form-group col-xs-12 col-md-3"> 
					 <label>Maquinario em funcionamento (Saida)</label>
					 <input type="text" name="conta_giro_maquinario_saida" value="<?php  echo $edit['conta_giro_maquinario_saida'];?>" class="form-control" />
				</div>

				<div class="form-group col-xs-12 col-md-3"> 
					 <label>Maquinario em funcionamento (Chegada)</label>
					 <input type="text" name="conta_giro_maquinario_chegada" value="<?php  echo $edit['conta_giro_maquinario_chegada'];?>" class="form-control" />
				</div>
              
        
          </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">
				
				<div class="box-header with-border">
					<h3 class="box-title">2 - EQUIPAMENTO ELÉTRICOS</h3>
				</div><!-- /.box-header -->

			 </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">
					
			  <div class="form-group col-xs-12 col-md-12">  
					 <label>1 - Farol Alto</label>
			  </div>
		 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
				   <label class="radio-inline">
					   <input type="radio"
						   name="farol_alto_dir_saida"
						   value="1"
						   <?php echo ($edit['farol_alto_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				  <label class="radio-inline">
					   <input type="radio"
						   name="farol_alto_dir_saida"
						   value="0"
						   <?php echo ($edit['farol_alto_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>

             </div>  
				
			 
				
			 <div class="form-group col-xs-12 col-md-3" > 
				 <label class="radio-inline"> <small>Saida Esq</small></label>
				 <label class="radio-inline">
                 	  <input type="radio"
                       name="farol_alto_esq_saida"
                       value="1"
                       <?php echo ($edit['farol_alto_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				  <label class="radio-inline">
				   	<input type="radio"
                       name="farol_alto_esq_saida"
                       value="0"
                       <?php echo ($edit['farol_alto_esq_saida'] == "0") ? "checked" : null;?>>Não
 
				 </label>
            </div>  
				
			 
				
			 <div class="form-group col-xs-12 col-md-3" > 
				 <label class="radio-inline"> <small>Chegada Dir</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="farol_alto_dir_chegada"
						   value="1"
						   <?php echo ($edit['farol_alto_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				  <label class="radio-inline">
					   <input type="radio"
						   name="farol_alto_dir_chegada"
						   value="0"
						   <?php echo ($edit['farol_alto_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
				
            </div>  
			 
				
			 <div class="form-group col-xs-12 col-md-3" >  
				 <label class="radio-inline"> <small>Chegada Esq</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="farol_alto_esq_chegada"
						   value="1"
						   <?php echo ($edit['farol_alto_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="farol_alto_esq_chegada"
						   value="0"
						   <?php echo ($edit['farol_alto_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
				
            </div>  
 
	     </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
	 <div class="box-body">
         	<div class="row">

			   <div class="form-group col-xs-12 col-md-12">  
					 <label>2 - Farol Baixo</label>
			  </div>
				
			
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_dir_saida"
						   value="1"
						   <?php echo ($edit['farol_baixo_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_dir_saida"
						   value="0"
						   <?php echo ($edit['farol_baixo_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				
             </div>  
				
			 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
				 
                  <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_esq_saida"
						   value="1"
						   <?php echo ($edit['farol_baixo_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_esq_saida"
						   value="0"
						   <?php echo ($edit['farol_baixo_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				 
            </div>  
				
			 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
				 
                 <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_dir_chegada"
						   value="1"
						   <?php echo ($edit['farol_baixo_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_dir_chegada"
						   value="0"
						   <?php echo ($edit['farol_baixo_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
				
            </div>  
	 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		  
                  <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_esq_chegada"
						   value="1"
						   <?php echo ($edit['farol_baixo_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="farol_baixo_esq_chegada"
						   value="0"
						   <?php echo ($edit['farol_baixo_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
				
            </div>  

		    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>3 - Lanterna</label>
			</div>
				
			  <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_dir_saida"
						   value="1"
						   <?php echo ($edit['lanterna_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_dir_saida"
						   value="0"
						   <?php echo ($edit['lanterna_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
					
             </div>  
				 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_esq_saida"
						   value="1"
						   <?php echo ($edit['lanterna_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_esq_saida"
						   value="0"
						   <?php echo ($edit['lanterna_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				 
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
     
                  <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_dir_chegada"
						   value="1"
						   <?php echo ($edit['lanterna_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_dir_chegada"
						   value="0"
						   <?php echo ($edit['lanterna_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		  
                <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_esq_chegada"
						   value="1"
						   <?php echo ($edit['lanterna_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lanterna_esq_chegada"
						   value="0"
						   <?php echo ($edit['lanterna_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
	 	 </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         <div class="row">
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>4 - Pisca Alerta</label>
			 </div>
			 
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="pisca_dir_saida"
						   value="1"
						   <?php echo ($edit['pisca_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pisca_dir_saida"
						   value="0"
						   <?php echo ($edit['pisca_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			 	 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="pisca_esq_saida"
						   value="1"
						   <?php echo ($edit['pisca_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pisca_esq_saida"
						   value="0"
						   <?php echo ($edit['pisca_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
     
                  <label class="radio-inline">
					   <input type="radio"
						   name="pisca_dir_chegada"
						   value="1"
						   <?php echo ($edit['pisca_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pisca_dir_chegada"
						   value="0"
						   <?php echo ($edit['pisca_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		  
                 <label class="radio-inline">
					   <input type="radio"
						   name="pisca_esq_chegada"
						   value="1"
						   <?php echo ($edit['pisca_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pisca_esq_chegada"
						   value="0"
						   <?php echo ($edit['pisca_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">		
	 
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>5 - Luz de Freio</label>
			</div>
				
		     <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_dir_saida"
						   value="1"
						   <?php echo ($edit['luz_freio_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_dir_saida"
						   value="0"
						   <?php echo ($edit['luz_freio_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>  
             </div>  
				
				 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_esq_saida"
						   value="1"
						   <?php echo ($edit['luz_freio_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_esq_saida"
						   value="0"
						   <?php echo ($edit['luz_freio_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
    			  <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_dir_chegada"
						   value="1"
						   <?php echo ($edit['luz_freio_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_dir_chegada"
						   value="0"
						   <?php echo ($edit['luz_freio_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		  
               <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_esq_chegada"
						   value="1"
						   <?php echo ($edit['luz_freio_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_freio_esq_chegada"
						   value="0"
						   <?php echo ($edit['luz_freio_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
	     </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
          <div class="row">		
				
			<div class="form-group col-xs-12 col-md-12">  
				 <label>6 - Luz de Ré</label>
			</div>
			  
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_dir_saida"
						   value="1"
						   <?php echo ($edit['luz_re_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_dir_saida"
						   value="0"
						   <?php echo ($edit['luz_re_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
				 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_esq_saida"
						   value="1"
						   <?php echo ($edit['luz_re_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_esq_saida"
						   value="0"
						   <?php echo ($edit['luz_re_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
       
                <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_dir_chegada"
						   value="1"
						   <?php echo ($edit['luz_re_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_dir_chegada"
						   value="0"
						   <?php echo ($edit['luz_re_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		  
               <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_esq_chegada"
						   value="1"
						   <?php echo ($edit['luz_re_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="luz_re_esq_chegada"
						   value="0"
						   <?php echo ($edit['luz_re_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>
 
				
		    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">		
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>7 - Strobo</label>
			 </div>
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="strobo_dir_saida"
						   value="1"
						   <?php echo ($edit['strobo_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="strobo_dir_saida"
						   value="0"
						   <?php echo ($edit['strobo_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
				 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="strobo_esq_saida"
						   value="1"
						   <?php echo ($edit['strobo_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="strobo_esq_saida"
						   value="0"
						   <?php echo ($edit['strobo_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 	
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
     
                  <label class="radio-inline">
					   <input type="radio"
						   name="strobo_dir_chegada"
						   value="1"
						   <?php echo ($edit['strobo_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="strobo_dir_chegada"
						   value="0"
						   <?php echo ($edit['strobo_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		   
                 <label class="radio-inline">
					   <input type="radio"
						   name="strobo_esq_chegada"
						   value="1"
						   <?php echo ($edit['strobo_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="strobo_esq_chegada"
						   value="0"
						   <?php echo ($edit['strobo_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
     </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">		
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>8 - Limpador de Parabrisa</label>
			 </div>
				
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="limpador_dir_saida"
						   value="1"
						   <?php echo ($edit['limpador_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="limpador_dir_saida"
						   value="0"
						   <?php echo ($edit['limpador_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
				 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="limpador_esq_saida"
						   value="1"
						   <?php echo ($edit['limpador_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="limpador_esq_saida"
						   value="0"
						   <?php echo ($edit['limpador_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
     
                 <label class="radio-inline">
					   <input type="radio"
						   name="limpador_dir_chegada"
						   value="1"
						   <?php echo ($edit['limpador_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="limpador_dir_chegada"
						   value="0"
						   <?php echo ($edit['limpador_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		  
                  <label class="radio-inline">
					   <input type="radio"
						   name="limpador_esq_chegada"
						   value="1"
						   <?php echo ($edit['limpador_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="limpador_esq_chegada"
						   value="0"
						   <?php echo ($edit['limpador_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				

		    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
          <div class="row">		
				
			  <div class="form-group col-xs-12 col-md-12">  
				 <label>9 - Pneus</label>
			  </div>
				
              <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="pneu_dir_saida"
						   value="1"
						   <?php echo ($edit['pneu_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pneu_dir_saida"
						   value="0"
						   <?php echo ($edit['pneu_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			  	 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="pneu_esq_saida"
						   value="1"
						   <?php echo ($edit['pneu_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pneu_esq_saida"
						   value="0"
						   <?php echo ($edit['pneu_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
     
                 <label class="radio-inline">
					   <input type="radio"
						   name="pneu_dir_chegada"
						   value="1"
						   <?php echo ($edit['pneu_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pneu_dir_chegada"
						   value="0"
						   <?php echo ($edit['pneu_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		  
                 <label class="radio-inline">
					   <input type="radio"
						   name="pneu_esq_chegada"
						   value="1"
						   <?php echo ($edit['pneu_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pneu_esq_chegada"
						   value="0"
						   <?php echo ($edit['pneu_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 

		    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
 
		
		<div class="box-body">
          <div class="row">		
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>10 - Espelho Retrovisor</label>
			 </div>
				
              <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                   <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_dir_saida"
						   value="1"
						   <?php echo ($edit['retrovisor_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_dir_saida"
						   value="0"
						   <?php echo ($edit['retrovisor_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
				 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                    <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_esq_saida"
						   value="1"
						   <?php echo ($edit['retrovisor_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_esq_saida"
						   value="0"
						   <?php echo ($edit['retrovisor_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
     
                 <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_dir_chegada"
						   value="1"
						   <?php echo ($edit['retrovisor_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_dir_chegada"
						   value="0"
						   <?php echo ($edit['retrovisor_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		   
                  <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_esq_chegada"
						   value="1"
						   <?php echo ($edit['retrovisor_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="retrovisor_esq_chegada"
						   value="0"
						   <?php echo ($edit['retrovisor_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  

		    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">		
				
			  <div class="form-group col-xs-12 col-md-12">  
				 <label>11 - Parachoque</label>
			 </div>
				
              <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Dir</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_dir_saida"
						   value="1"
						   <?php echo ($edit['parachoque_dir_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_dir_saida"
						   value="0"
						   <?php echo ($edit['parachoque_dir_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
				 
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida Esq</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_esq_saida"
						   value="1"
						   <?php echo ($edit['parachoque_esq_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_esq_saida"
						   value="0"
						   <?php echo ($edit['parachoque_esq_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Dir</small></label>
     
                <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_dir_chegada"
						   value="1"
						   <?php echo ($edit['parachoque_dir_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_dir_chegada"
						   value="0"
						   <?php echo ($edit['parachoque_dir_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada Esq</small></label>
		    
                 <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_esq_chegada"
						   value="1"
						   <?php echo ($edit['parachoque_esq_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="parachoque_esq_chegada"
						   value="0"
						   <?php echo ($edit['parachoque_esq_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
		    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">		
				
			<div class="box-header with-border">
					<h3 class="box-title">3- EQUIPAMENTOS DE COLETA </h3>
			 </div><!-- /.box-header -->

			 
	 	    </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">	
			
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>12 - Embreagem</label>
			</div>
				
           <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saidar</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="embreagem_saida"
						   value="1"
						   <?php echo ($edit['embreagem_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="embreagem_saida"
						   value="0"
						   <?php echo ($edit['embreagem_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				  
             </div>  
				
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
     
                 <label class="radio-inline">
					   <input type="radio"
						   name="embreagem_chegada"
						   value="1"
						   <?php echo ($edit['embreagem_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="embreagem_chegada"
						   value="0"
						   <?php echo ($edit['embreagem_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		  </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">	
				
			  <div class="form-group col-xs-12 col-md-12">  
				 <label>13 - Molas</label>
			 </div>
				
           <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida </small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="mola_saida"
						   value="1"
						   <?php echo ($edit['mola_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="mola_saida"
						   value="0"
						   <?php echo ($edit['mola_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				  
             </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
     
                 <label class="radio-inline">
					   <input type="radio"
						   name="mola_chegada"
						   value="1"
						   <?php echo ($edit['mola_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="mola_chegada"
						   value="0"
						   <?php echo ($edit['mola_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		  </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">	
				
			  <div class="form-group col-xs-12 col-md-12">  
				 <label>14 - Maquinário/Compactador</label>
			</div>
				
            <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="maquinario_saida"
						   value="1"
						   <?php echo ($edit['maquinario_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="maquinario_saida"
						   value="0"
						   <?php echo ($edit['maquinario_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				  
             </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
     
                 <label class="radio-inline">
					   <input type="radio"
						   name="maquinario_chegada"
						   value="1"
						   <?php echo ($edit['maquinario_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="maquinario_chegada"
						   value="0"
						   <?php echo ($edit['maquinario_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		  </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">	
		
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>15 - Lifter 240 Litros</label>
			</div>
				
              <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="lifter_240_saida"
						   value="1"
						   <?php echo ($edit['lifter_240_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lifter_240_saida"
						   value="0"
						   <?php echo ($edit['lifter_240_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				  
             </div>  
				
			   <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
     
                 <label class="radio-inline">
					   <input type="radio"
						   name="lifter_240_chegada"
						   value="1"
						   <?php echo ($edit['lifter_240_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lifter_240_chegada"
						   value="0"
						   <?php echo ($edit['lifter_240_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		  </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
		<div class="box-body">
         	<div class="row">	
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>16 - Lifter 1.2 m3</label>
			</div>
				
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="lifter_12_saida"
						   value="1"
						   <?php echo ($edit['lifter_12_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lifter_12_saida"
						   value="0"
						   <?php echo ($edit['lifter_12_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				  
             </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
     
                  <label class="radio-inline">
					   <input type="radio"
						   name="lifter_12_chegada"
						   value="1"
						   <?php echo ($edit['lifter_12_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="lifter_12_chegada"
						   value="0"
						   <?php echo ($edit['lifter_12_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">		
				
			<div class="box-header with-border">
					<h3 class="box-title">4 - EQUIPAMENTO HIDRÁULICOS</h3>
			 </div><!-- /.box-header -->
 
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>17 - Cilindro</label>
			</div>
				
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="cilindro_saida"
						   value="1"
						   <?php echo ($edit['cilindro_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="cilindro_saida"
						   value="0"
						   <?php echo ($edit['cilindro_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				  
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="cilindro_chegada"
						   value="1"
						   <?php echo ($edit['cilindro_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="cilindro_chegada"
						   value="0"
						   <?php echo ($edit['cilindro_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>18 - Mangueira</label>
			</div>
				
            	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="mangueira_saida"
						   value="1"
						   <?php echo ($edit['mangueira_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="mangueira_saida"
						   value="0"
						   <?php echo ($edit['mangueira_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
				  
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="mangueira_chegada"
						   value="1"
						   <?php echo ($edit['mangueira_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="mangueira_chegada"
						   value="0"
						   <?php echo ($edit['mangueira_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>19 - Válvula</label>
			</div>
				
             	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="valvula_saida"
						   value="1"
						   <?php echo ($edit['valvula_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="valvula_saida"
						   value="0"
						   <?php echo ($edit['valvula_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="valvula_chegada"
						   value="1"
						   <?php echo ($edit['valvula_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="valvula_chegada"
						   value="0"
						   <?php echo ($edit['valvula_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">
				
			  <div class="form-group col-xs-12 col-md-12">  
				 <label>20 -Comando Dianteiro</label>
			</div>
				
             	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="comando_dianteiro_saida"
						   value="1"
						   <?php echo ($edit['comando_dianteiro_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="comando_dianteiro_saida"
						   value="0"
						   <?php echo ($edit['comando_dianteiro_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="comando_dianteiro_chegada"
						   value="1"
						   <?php echo ($edit['comando_dianteiro_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="comando_dianteiro_chegada"
						   value="0"
						   <?php echo ($edit['comando_dianteiro_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">			
			
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>21 -Comando Traseiro</label>
			</div>
				
             	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="comando_traseiro_saida"
						   value="1"
						   <?php echo ($edit['comando_traseiro_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="comando_traseiro_saida"
						   value="0"
						   <?php echo ($edit['comando_traseiro_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="comando_traseiro_chegada"
						   value="1"
						   <?php echo ($edit['comando_traseiro_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="comando_traseiro_chegada"
						   value="0"
						   <?php echo ($edit['comando_traseiro_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
				
			  <div class="form-group col-xs-12 col-md-12">  
				 <label>22 - Bomba Hidráulica</label>
			</div>
				
           	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="bomba_saida"
						   value="1"
						   <?php echo ($edit['bomba_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="bomba_saida"
						   value="0"
						   <?php echo ($edit['bomba_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="bomba_chegada"
						   value="1"
						   <?php echo ($edit['bomba_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="bomba_chegada"
						   value="0"
						   <?php echo ($edit['bomba_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">			
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>23 -Reservatório</label>
			</div>
				
            	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="reservatorio_saida"
						   value="1"
						   <?php echo ($edit['reservatorio_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="reservatorio_saida"
						   value="0"
						   <?php echo ($edit['reservatorio_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			 <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="reservatorio_chegada"
						   value="1"
						   <?php echo ($edit['reservatorio_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="reservatorio_chegada"
						   value="0"
						   <?php echo ($edit['reservatorio_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
				
			  <div class="form-group col-xs-12 col-md-12">  
				 <label>24 -Caixa Coletora</label>
			 </div>
				
              	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="caixa_saida"
						   value="1"
						   <?php echo ($edit['caixa_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="caixa_saida"
						   value="0"
						   <?php echo ($edit['caixa_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="caixa_chegada"
						   value="1"
						   <?php echo ($edit['caixa_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="caixa_chegada"
						   value="0"
						   <?php echo ($edit['caixa_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">		
				
			<div class="box-header with-border">
					<h3 class="box-title">5 - EQUIPAMENTO  DE SEGURANÇA</h3>
			 </div><!-- /.box-header -->
  
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>25 -Limpeza de Cabine</label>
			</div>
				
              	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="limpeza_cabine_saida"
						   value="1"
						   <?php echo ($edit['limpeza_cabine_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="limpeza_cabine_saida"
						   value="0"
						   <?php echo ($edit['limpeza_cabine_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="limpeza_cabine_chegada"
						   value="1"
						   <?php echo ($edit['limpeza_cabine_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="limpeza_cabine_chegada"
						   value="0"
						   <?php echo ($edit['limpeza_cabine_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
				
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>26 - Fita zebrada</label>
			</div>
				
             	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="fita_saida"
						   value="1"
						   <?php echo ($edit['fita_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="fita_saida"
						   value="0"
						   <?php echo ($edit['fita_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="fita_chegada"
						   value="1"
						   <?php echo ($edit['fita_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="fita_chegada"
						   value="0"
						   <?php echo ($edit['fita_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
					
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
					
				
		    <div class="form-group col-xs-12 col-md-12">  
				 <label>27 - Vassoura</label>
			</div>
				
              	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="vassoura_saida"
						   value="1"
						   <?php echo ($edit['vassoura_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="vassoura_saida"
						   value="0"
						   <?php echo ($edit['vassoura_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="vassoura_chegada"
						   value="1"
						   <?php echo ($edit['vassoura_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="vassoura_chegada"
						   value="0"
						   <?php echo ($edit['vassoura_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
				
					
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
					
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>28 - Pá</label>
			</div>
				
              	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="pa_saida"
						   value="1"
						   <?php echo ($edit['pa_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pa_saida"
						   value="0"
						   <?php echo ($edit['pa_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="pa_chegada"
						   value="1"
						   <?php echo ($edit['pa_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="pa_chegada"
						   value="0"
						   <?php echo ($edit['pa_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
					
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
					
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>29 - Freios</label>
			</div>
				
            	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                  <label class="radio-inline">
					   <input type="radio"
						   name="freio_saida"
						   value="1"
						   <?php echo ($edit['freio_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="freio_saida"
						   value="0"
						   <?php echo ($edit['freio_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="freio_chegada"
						   value="1"
						   <?php echo ($edit['freio_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="freio_chegada"
						   value="0"
						   <?php echo ($edit['freio_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
					
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
					
				
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>30 - Triângulo</label>
			</div>
				
             	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="triangulo_saida"
						   value="1"
						   <?php echo ($edit['triangulo_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="triangulo_saida"
						   value="0"
						   <?php echo ($edit['triangulo_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="triangulo_chegada"
						   value="1"
						   <?php echo ($edit['triangulo_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="triangulo_chegada"
						   value="0"
						   <?php echo ($edit['triangulo_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div>  
				
				
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
						
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>31 - Extintor</label>
			</div>
				
              	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="extintor_saida"
						   value="1"
						   <?php echo ($edit['extintor_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="extintor_saida"
						   value="0"
						   <?php echo ($edit['extintor_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="extintor_chegada"
						   value="1"
						   <?php echo ($edit['extintor_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="extintor_chegada"
						   value="0"
						   <?php echo ($edit['extintor_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
				
					
		   </div><!-- /.row -->
       </div><!-- /.box-body --> 
		
		<div class="box-body">
         	<div class="row">	
					
			 <div class="form-group col-xs-12 col-md-12">  
				 <label>32 - Tacógrafo</label>
			</div>
				
             	
             <div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Saida</small></label>
                 <label class="radio-inline">
					   <input type="radio"
						   name="tacografo_saida"
						   value="1"
						   <?php echo ($edit['tacografo_saida'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="tacografo_saida"
						   value="0"
						   <?php echo ($edit['tacografo_saida'] == "0") ? "checked" : null;?>>Não
				 </label>
             </div>  
				
			<div class="form-group col-xs-12 col-md-3" > 
				  <label class="radio-inline"> <small>Chegada</small></label>
                <label class="radio-inline">
					   <input type="radio"
						   name="tacografo_chegada"
						   value="1"
						   <?php echo ($edit['tacografo_chegada'] == "1") ? "checked" : null;?>>OK
				  </label>
				
				  <label class="radio-inline">
					   <input type="radio"
						   name="tacografo_chegada"
						   value="0"
						   <?php echo ($edit['tacografo_chegada'] == "0") ? "checked" : null;?>>Não
				 </label>
            </div> 
				
		 	
		  <div class="form-group col-xs-12 col-md-12">  
              <label>Observação Saida</label>
                <textarea name="observacao_saida" rows="5" cols="100" class="form-control" /><?php echo $edit['observacao_saida'];?></textarea>
          </div>  
			
			 <div class="form-group col-xs-12 col-md-12">  
              <label>Observação Chegada</label>
                <textarea name="observacao_chegada" rows="5" cols="100" class="form-control" /><?php echo $edit['observacao_chegada'];?></textarea>
          </div>  
					
 
          </div><!-- /.row -->
       </div><!-- /.box-body -->   
		
     		<div class="box-footer">
				 
					  <?php 
						 
						 if($acao=="atualizar" || $acao=="visualizar"){
							echo '<input type="submit" name="atualizar-check" value="Atualizar Check List" class="btn btn-primary" />';	
						}
						 
					 ?>  
         	 </div> 
          
         </div>
 
		</form>
	 
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->