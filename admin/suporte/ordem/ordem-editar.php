<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}

		echo '<script>';
			echo 'function confirmacao() {';
				echo 'if ( confirm("Confirma Exclusão de Registro?") ) {';
					echo 'return true;';
				echo '}';
					echo 'javascript:window.history.go(-1)';
				echo '}';
		echo '</script>';
		
		
		if(!empty($_GET['ordemBaixar'])){
			$ordemId = $_GET['ordemBaixar'];
			$acao = "baixar";
		}
		
		if(!empty($_GET['ordemEditar'])){
			$ordemId = $_GET['ordemEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['ordemVisualizar'])){
			$ordemId = $_GET['ordemVisualizar'];
			$acao = "visualizar";
		}

		if(!empty($_GET['ordemDeletar'])){
			$ordemId = $_GET['ordemDeletar'];
			$acao = "deletar";
		}

		if(!empty($_GET['contratoId'])){
			
			$contratoId = $_GET['contratoId'];
			$acao = "cadastrar";
			$parcela = 1;

			if(!empty($contratoId)){
				$contrato = mostra('contrato',"WHERE id = '$contratoId'");
				if(!$contrato){
					header('Location: painel.php?execute=suporte/naoencontrado');	
				}

				$clienteId = $contrato['id_cliente'];
				$cliente = mostra('cliente',"WHERE id = '$clienteId'");
				if(!$cliente){
					header('Location: painel.php?execute=suporte/naoencontrado');	
				}

				// PEGAR TIPOS DE COLETA E QUANTIDADE
				$contratoColeta = mostra('contrato_coleta',"WHERE id AND id_contrato='$contratoId' 
									ORDER BY id DESC");
				$edit['tipo_coleta1'] = $contratoColeta['tipo_coleta'];
				$edit['quantidade1'] = $contratoColeta['quantidade'];
				
				// GERAR DATA DO DIA E VERIFICAR O HORA E ROTA DE COLETA
				$edit['interacao'] = date('Y/m/d H:i:s');
				$edit['data'] = date('Y-m-d');
				$edit['data'] = $contrato['inicio'];
				
				$dia_semana = diaSemana($edit['data']);
				$numero_semana = numeroSemana($edit['data']);

				if ( $numero_semana == 0 ) {
					$rota = $contrato[ 'domingo_rota1' ];
					$hora = $contrato[ 'domingo_hora1' ];
				}
				if ( $numero_semana == 1 ) {
					$rota = $contrato[ 'segunda_rota1' ];
					$hora = $contrato[ 'segunda_hora1' ];
				}
				if ( $numero_semana == 2 ) {
					$rota = $contrato[ 'terca_rota1' ];
					$hora = $contrato[ 'terca_hora1' ];
				}
				if ( $numero_semana == 3 ) {
					$rota = $contrato[ 'quarta_rota1' ];
					$hora = $contrato[ 'quarta_hora1' ];
				}
				if ( $numero_semana == 4 ) {
					$rota = $contrato[ 'quinta_rota1' ];
					$hora = $contrato[ 'quinta_hora1' ];
				}
				if ( $numero_semana == 5 ) {
					$rota = $contrato[ 'sexta_rota1' ];
					$hora= $contrato[ 'sexta_hora1' ];
				}
				if ( $numero_semana == 6 ) {
					$rota = $contrato[ 'sabado_rota1' ];
					$hora = $contrato[ 'sabado_hora1' ];
				}

				if ( $mostra[ 'manifesto' ] == 1 ) {
					$edit[ 'manifesto' ] = 'M';
					$edit[ 'manifesto_status' ] = 'EM ABERTO';
				}
				
				$edit['rota'] =$rota;
				$edit['hora'] =$hora;

			  }else{
				header('Location: painel.php?execute=suporte/naoencontrado');	

			}
		}
		

		if(!empty($ordemId)){
			$readordem = read('contrato_ordem',"WHERE id = '$ordemId'");
			if(!$readordem){
				header('Location: painel.php?execute=suporte/403');
			}
			foreach($readordem as $edit);
			
			if ($edit['nao_coletada'] == "1") {
				$edit['nao_coletada'] = "checked='CHECKED'";
			} else {
				$edit['nao_coletada'] = "";
		    }
			
			if ($edit['crcode'] == "1") {
				$edit['crcode'] = "checked='CHECKED'";
			} else {
				$edit['crcode'] = "";
		    }
			
			
			$contratoId = $edit['id_contrato'];
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				//header('Location: painel.php?execute=suporte/404');	
			}
			foreach($readContrato as $contrato);
			
			$clienteId = $edit['id_cliente'];
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				//header('Location: painel.php?execute=suporte/404');	
			}
			foreach($readCliente as $cliente);
			
			
		}

		$manifestoId = $ordemId;	
		$readonly = "readonly";
		$disabled = 'disabled="disabled"';
		
		$_SESSION['url2']=$_SERVER['REQUEST_URI'];

		$_SESSION['aba']='3';

 ?>

 <div class="content-wrapper">
     <section class="content-header">
              <h1>Ordem de Serviço</h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Contrato</a></li>
                <li><a href="#">Ordem</a></li>
                 <li class="active">Editar</li>
              </ol>
      </section>
	 <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                 <?php require_once('cliente-modal.php');?>
                
                <div class="box-header">  
                   <a href="painel.php?execute=suporte/ordem/ordem-contrato&ordemImprimir=<?PHP echo $ordemId; ?>" 
            		class="btnnovo" target="_blank">
						<img src="../admin/ico/imprimir.png" alt="Imprimir Ordem de Serviço" title="Imprimir Ordem de Serviço"  />
					   Ordem de Serviço
					</a>
					
					<a href="painel.php?execute=suporte/manifesto/manifesto&manifestoImprimir=<?PHP echo $manifestoId; ?>" 
            		class="btnnovo" target="_blank">
						<img src="../admin/ico/imprimir.png" alt="Imprimir Manifesto" title="Imprimir Manifesto"  />
					   Manifesto
					</a>
				</div>
                 
                 <div class="box-tools">
              	
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
      	  
     <div class="box-body">   

	<?php 
		

		 if(isset($_POST['cadastrar'])){
			 
		    $cad['data'] = mysql_real_escape_string($_POST['data']);
			$cad['rota'] = mysql_real_escape_string($_POST['rota']);
			$cad['hora'] = mysql_real_escape_string($_POST['hora']);
			 
		    $cad['tipo_coleta1'] = mysql_real_escape_string($_POST['tipo_coleta1']);
		    $cad['tipo_coleta2'] = mysql_real_escape_string($_POST['tipo_coleta2']);
			$cad['tipo_coleta3'] = mysql_real_escape_string($_POST['tipo_coleta3']);
		    $cad['tipo_coleta4'] = mysql_real_escape_string($_POST['tipo_coleta4']);
			$cad['tipo_coleta5'] = mysql_real_escape_string($_POST['tipo_coleta5']);
			 
			$cad['quantidade1'] = mysql_real_escape_string($_POST['quantidade1']);
		    $cad['quantidade2'] = mysql_real_escape_string($_POST['quantidade2']);
			$cad['quantidade3'] = mysql_real_escape_string($_POST['quantidade3']);
		    $cad['quantidade4'] = mysql_real_escape_string($_POST['quantidade4']);
			$cad['quantidade5'] = mysql_real_escape_string($_POST['quantidade5']);
			
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);

			$cad['id_contrato']= $contratoId;
			$cad['id_cliente']= $clienteId;
			$cad[ 'status' ] = '12';
			 
			$cad[ 'avulsa' ] = '1';
			$cad[ 'impressa' ] = '0';
				
			 
			$parcela = $_POST['parcela'];
			
			 if ( $contrato[ 'manifesto' ] == 1 ) {
				$cad[ 'manifesto' ] = 'M';
				$cad[ 'manifesto_status' ] = 'EM ABERTO';
			}
			 
			 // GERAR APENAS 1 ORDEM
			  if ($parcela == 1){
				  
				  // GRAVAR ORDEM
				  	$cad['interacao'] = date('Y/m/d H:i:s');
				   
					create('contrato_ordem',$cad);	
					$interacao='Ordem gerada avulsa';
					interacao($interacao,$contratoId);
					unset($cad); 
				 }else{
				  	
				   // GERAR ORDENS DE ACORDO COM PARCELAS
					$contador=0;
					while ($contador < $parcela) {
							
							// GRAVAR ORDEM
							$contador = $contador+1;
							create('contrato_ordem',$cad);	
						
							$interacao='Ordem gerada avulsa';
							interacao($interacao,$contratoId);
						
							// VERIFICA PROXIMO COLETA
							$dataColeta= $cad['data'];
							$dataColeta=proximaColeta($contratoId,$dataColeta);
							$cad['data']=$dataColeta;
							$cad['interacao']= date('Y/m/d H:i:s');
					}
			 	}

			header("Location: ".$_SESSION['contrato-editar']);
		}
		 
		 
		 if(isset($_POST['atualizar'])){
			 
		    $cad['data'] = mysql_real_escape_string($_POST['data']);
			$cad['hora'] = mysql_real_escape_string($_POST['hora']);
			$cad['rota'] = mysql_real_escape_string($_POST['rota']);
			$cad['hora_coleta'] = mysql_real_escape_string($_POST['hora_coleta']);
			 
		    $cad['tipo_coleta1'] = mysql_real_escape_string($_POST['tipo_coleta1']);
		    $cad['tipo_coleta2'] = mysql_real_escape_string($_POST['tipo_coleta2']);
			$cad['tipo_coleta3'] = mysql_real_escape_string($_POST['tipo_coleta3']);
		    $cad['tipo_coleta4'] = mysql_real_escape_string($_POST['tipo_coleta4']);
			$cad['tipo_coleta5'] = mysql_real_escape_string($_POST['tipo_coleta5']);
			 
			$cad['quantidade1'] = mysql_real_escape_string($_POST['quantidade1']);
		    $cad['quantidade2'] = mysql_real_escape_string($_POST['quantidade2']);
			$cad['quantidade3'] = mysql_real_escape_string($_POST['quantidade3']);
		    $cad['quantidade4'] = mysql_real_escape_string($_POST['quantidade4']);
			$cad['quantidade5'] = mysql_real_escape_string($_POST['quantidade5']);
			
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
			$cad['nao_coletada'] = mysql_real_escape_string($_POST['nao_coletada']);
			$cad['motivo_nao_coletado'] = mysql_real_escape_string($_POST['motivo_nao_coletado']);
			 
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_ordem',$cad,"id = '$ordemId'");
			 
			$interacao='Ordem atualizada';
			interacao($interacao,$contratoId);
			header("Location: ".$_SESSION['url']);
		}
		 
		if(isset($_POST['realizar'])){
			
			$cad['data'] = mysql_real_escape_string($_POST['data']);
			$cad['rota'] = mysql_real_escape_string($_POST['rota']);
			$cad['hora'] = mysql_real_escape_string($_POST['hora']);
			$cad['hora_coleta'] = mysql_real_escape_string($_POST['hora_coleta']);
			 
		    $cad['tipo_coleta1'] = mysql_real_escape_string($_POST['tipo_coleta1']);
		    $cad['tipo_coleta2'] = mysql_real_escape_string($_POST['tipo_coleta2']);
			$cad['tipo_coleta3'] = mysql_real_escape_string($_POST['tipo_coleta3']);
		    $cad['tipo_coleta4'] = mysql_real_escape_string($_POST['tipo_coleta4']);
			$cad['tipo_coleta5'] = mysql_real_escape_string($_POST['tipo_coleta5']);
			 
			$cad['quantidade1'] = mysql_real_escape_string($_POST['quantidade1']);
		    $cad['quantidade2'] = mysql_real_escape_string($_POST['quantidade2']);
			$cad['quantidade3'] = mysql_real_escape_string($_POST['quantidade3']);
		    $cad['quantidade4'] = mysql_real_escape_string($_POST['quantidade4']);
			$cad['quantidade5'] = mysql_real_escape_string($_POST['quantidade5']);
			
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
			$cad['nao_coletada'] = mysql_real_escape_string($_POST['nao_coletada']);
			$cad['motivo_nao_coletado'] = mysql_real_escape_string($_POST['motivo_nao_coletado']);
			
			if($cad['quantidade1']=='0' AND empty($cad['motivo_nao_coletado']) ){
				
				$cad['nao_coletada']=='1';
				$edit['nao_coletada']=='1';
				$edit['nao_coletada'] = mysql_real_escape_string($_POST['nao_coletada']);
				
			 	echo '<div class="alert alert-warning">Selecione Motivo</div>';
				
			  }else{
				
				if($cad['quantidade1']=='0' AND !empty($cad['motivo_nao_coletado']) ){
					$cad['nao_coletada']=='1';
				}
 
				$cad['status'] = '13';
				$cad['interacao']= date('Y/m/d H:i:s');
				update('contrato_ordem',$cad,"id = '$ordemId'");	
				$interacao='Ordem Realizada (Adm) n. '.$ordemId;
				interacao($interacao,$contratoId);

				if($edit['status']<>'13'){
					//ATUALIZADO SALDO CONTRATO;
					if($contrato['saldo_etiqueta_minimo']>0){
						$con['saldo_etiqueta'] = $contrato['saldo_etiqueta'] - $cad['quantidade1'];
						update('contrato',$con,"id = '$contratoId'");	
					}

				}

				$enviarAviso='SIM';

				if($cliente['nao_enviar_email']=='1'){
					$enviarAviso='NAO';
				}

				if($cad['nao_coletada']=='1'){
					$enviarAviso='NAO';
				}

				if($cad['quantidade1']=='0'){
					$enviarAviso='NAO';
				}

				if(empty($cad['quantidade1'])){
					$enviarAviso='NAO';
				}

				if($enviarAviso=='SIM'){

					$tipoColetaId = $edit['tipo_coleta1'];
					$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					$assunto = "Clean - COLETA REALIZADA" . $cliente['nome'];
					$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
					$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

					$msg .= "Prezado Cliente, <br /><br />";
					$msg .= "A Clean Ambiental informa que a coleta foi realizada com sucesso <br /><br />";


					$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];

					$msg .= "Contrato N : " . substr($contrato['controle'],0,6) . "<br />";
					$msg .= "Cliente : " . $cliente['nome'] . "<br />";
					$msg .= "Endereço : " . $endereco . "<br />";
					$msg .= "Ordem Número : " . $edit['id'] . "<br />";
					$msg .= "Data da Coleta : " . converteData($edit['data']) . "<br />";
					$msg .= "Tipo de Coleta : " . $coleta['nome'] . "<br />";
					$msg .= "Quantidade  : " . $cad['quantidade1'] . "<br /><br />";

					$msg .= "Estamos também disponíveis no telefone 3104-2992 | 99871-0334 WhatsApp <br /><br />";

					$msg .= "Caso haja divergência na quantidade coletada, estamos a disposição no prazo de 48 horas para contestação, caso não ocorra contato, será considerada a quantidade coletada correta para cobrança no próximo faturamento. <br /><br />";

					$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
					$msg .=  "</font>";

					$administrativo='atendimento@cleanambiental.com.br';	
					enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email'], $cliente['nome']);

					$cad2['mensagem_baixa']= date('Y/m/d H:i:s');
					update('contrato_ordem',$cad2,"id = '$ordemId'");		
				}

				header("Location: ".$_SESSION['url']);
			}
		}
		 
		 
		 
		 if(isset($_POST['enviar'])){
			
				$tipoColetaId = $edit['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				$assunto = "Clean Ambiental - Coleta Realizada " . $cliente['nome'];
				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

				$msg .= "Prezado Cliente, <br /><br />";
				$msg .= "A Clean Ambiental informa que a coleta foi realizada com sucesso <br /><br />";
				
				
				$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'];
	  
				$msg .= "Contrato N : " . substr($contrato['controle'],0,6) . "<br />";
				$msg .= "Cliente : " . $cliente['nome'] . "<br />";
				$msg .= "Endereço : " . $endereco . "<br />";
				$msg .= "Ordem Número : " . $edit['id'] . "<br />";
				$msg .= "Data da Coleta : " . converteData($edit['data']) . "<br />";
				$msg .= "Tipo de Coleta : " . $coleta['nome'] . "<br />";
				$msg .= "Quantidade  : " . $cad['quantidade1'] . "<br /><br />";

				$msg .= "Estamos também disponíveis no telefone 3104-2992 <br /><br />";
				
				$msg .= "Caso haja divergência na quantidade coletada, estamos a disposição no prazo de 48 horas para contestação, caso não ocorra contato, será considerada a quantidade coletada correta para cobrança no próximo faturamento. <br /><br />";
				
				$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
				$msg .=  "</font>";

				//$cliente['nome']='Patricia';
				//$cliente['email']='qualidade@padraoambiental.com.br';

				$administrativo='administrativo@cleanambiental.com.br';	
				enviaEmail($assunto,$msg,$administrativo,SITENOME,$cliente['email'], $cliente['nome']);
			 	enviaEmail($assunto,$msg,$administrativo,SITENOME,$administrativo,SITENOME);

		
			header("Location: ".$_SESSION['url']);
		}
		 
		 if(isset($_POST['transferir'])){
			
			$cad['data'] = mysql_real_escape_string($_POST['data']);
			$cad['rota'] = mysql_real_escape_string($_POST['rota']);
			$cad['hora'] = mysql_real_escape_string($_POST['hora']);
			$cad['hora_coleta'] = mysql_real_escape_string($_POST['hora_coleta']);
			 
		    $cad['tipo_coleta1'] = mysql_real_escape_string($_POST['tipo_coleta1']);
		    $cad['tipo_coleta2'] = mysql_real_escape_string($_POST['tipo_coleta2']);
			$cad['tipo_coleta3'] = mysql_real_escape_string($_POST['tipo_coleta3']);
		    $cad['tipo_coleta4'] = mysql_real_escape_string($_POST['tipo_coleta4']);
			$cad['tipo_coleta5'] = mysql_real_escape_string($_POST['tipo_coleta5']);
			 
			$cad['quantidade1'] = mysql_real_escape_string($_POST['quantidade1']);
		    $cad['quantidade2'] = mysql_real_escape_string($_POST['quantidade2']);
			$cad['quantidade3'] = mysql_real_escape_string($_POST['quantidade3']);
		    $cad['quantidade4'] = mysql_real_escape_string($_POST['quantidade4']);
			$cad['quantidade5'] = mysql_real_escape_string($_POST['quantidade5']);
			
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
			$cad['nao_coletada'] = mysql_real_escape_string($_POST['nao_coletada']);
			$cad['status'] = '14';
			 
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_ordem',$cad,"id = '$ordemId'");	
			$interacao='Ordem Transferida';
			interacao($interacao,$contratoId);
			header("Location: ".$_SESSION['url']);
		}
		 
		 
		if(isset($_POST['cancelar'])){
			$cad['observacao'] = mysql_real_escape_string($_POST['observacao']);
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = '15';
			update('contrato_ordem',$cad,"id = '$ordemId'");	
			
			$interacao='Ordem cancelado';
			interacao($interacao,$contratoId);
			header("Location: ".$_SESSION['url']);;
		}
		 
		if(isset($_POST['deletar'])){
			delete('contrato_ordem',"id = '$ordemId'");
			
			$interacao='Ordem Excluida';
			interacao($interacao,$contratoId);
			header("Location: ".$_SESSION['url']);
		}

	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  	
  	   		 <div class="box-header with-border">
                  <h3 class="box-title">Ordem de Serviço</h3>
             </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
          
            	<div class="form-group col-xs-12 col-md-2"> 
               		<label>Id</label>
              		<input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled /> 
                 </div><!-- /.col-md-12 -->
                 
                 <div class="form-group col-xs-12 col-md-4"> 
              		<label>Interação</label>
   					<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control" readonly  /> 
                </div><!-- /.col-md-12 -->
                
                
                <div class="form-group col-xs-12 col-md-3">  
                       <label>Status</label>
                      <select name="status"  class="form-control"  disabled>
                            <option value="">Selecione Status</option>
                                <?php 
                                    $leitura = read('contrato_status',"WHERE id");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['status'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>
           	
                 
                <div class="form-group col-xs-12 col-md-3"> 
					<label>Data</label>
						<input name="data" type="date" value="<?php echo $edit['data'];?>" class="form-control" /> 
			 	</div><!-- /.col-md-12 -->
               
 				<div class="form-group col-xs-12 col-md-2"> 
					<label>Hora</label>
						<input name="hora" type="text" value="<?php echo $edit['hora'];?>" class="form-control"  /> 
			 	</div><!-- /.col-md-12 -->
   		
				<div class="form-group col-xs-12 col-md-2"> 
					<label>Hora Coleta</label>
						<input name="hora_coleta" type="text" value="<?php echo $edit['hora_coleta'];?>" class="form-control" /> 
			 	</div><!-- /.col-md-12 --> 
         
         		 <div class="form-group col-xs-12 col-md-2">  
                       <label>Rota</label>
                      <select name="rota"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
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
				  
				  
              		<div class="form-group col-xs-12 col-md-2"> 
						<label>Saldo Etiqueta</label>
						<input type="text" name="saldo_etiqueta" style="text-align:right" value="<?php echo $contrato['saldo_etiqueta'];?>" class="form-control"  readonly>
				   </div> 
          
          	 	</div><!-- /.row -->
		  </div><!-- /.box-body --> 
         
          <div class="box-body">
             <div class="row">
         
           		 <div class="form-group col-xs-12 col-md-3">  
                       <label>Tipo de Coleta</label>
                      <select name="tipo_coleta1"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta1'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>

                 <div class="form-group col-xs-12 col-md-2"> 
						<label>Quantidade</label>
						<input type="number" name="quantidade1" style="text-align:right" value="<?php echo $edit['quantidade1'];?>" class="form-control" >
				 </div> 
           		
            	 <!-- NAO COLETADO-->
				 <div class="form-group col-xs-12 col-md-2">
					   <input name="nao_coletada" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['nao_coletada']; ?>  class="minimal" >
						Não Coletada
				 </div> 
				 
				 <div class="form-group col-xs-12 col-md-3">  
                       <label>Motivo Não Coletado</label>
                      <select name="motivo_nao_coletado"  class="form-control" >
                            <option value="">Selecione Motivo</option>
                                <?php 
                                    $leitura = read('ordem_motivo_naocoletado',"WHERE id ORDER BY id ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['motivo_nao_coletado'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                 </div>
                  
         	
         		</div><!-- /.row -->
         		
		        <div class="row">    
         		            
          		 <div class="form-group col-xs-12 col-md-3">  
                           <select name="tipo_coleta2"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta2'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>
       
                   <div class="form-group col-xs-12 col-md-2"> 
						<input type="number" name="quantidade2" style="text-align:right" value="<?php echo $edit['quantidade2'];?>" class="form-control" >
				   </div> 
       		
        		</div><!-- /.row -->
         		
		        <div class="row">   
          
         		  <div class="form-group col-xs-12 col-md-3">  
                        <select name="tipo_coleta3"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta3'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>
                    
                    
                   <div class="form-group col-xs-12 col-md-2"> 
						<input type="number" name="quantidade3" style="text-align:right" value="<?php echo $edit['quantidade3'];?>" class="form-control" >
				   </div> 
       		  
        		</div><!-- /.row -->
         		
		        <div class="row">  
          
         		 <div class="form-group col-xs-12 col-md-3">  
                      <select name="tipo_coleta4"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta4'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>
                    
                    
                   <div class="form-group col-xs-12 col-md-2"> 
						<input type="number" name="quantidade4" style="text-align:right" value="<?php echo $edit['quantidade4'];?>" class="form-control" >
				   </div> 
         
       		    </div><!-- /.row -->
         		
		        <div class="row"> 
          
        		  <div class="form-group col-xs-12 col-md-3">  
                      <select name="tipo_coleta5"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta5'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>
             
                   <div class="form-group col-xs-12 col-md-2"> 
						<input type="number" name="quantidade5" style="text-align:right" value="<?php echo $edit['quantidade5'];?>" class="form-control" >
				   </div> 
          
         
            	   <div class="form-group col-xs-12 col-md-12"> 
             		<label>Observaçao</label>
						<input type="text" name="observacao" value="<?php echo $edit['observacao'];?>" class="form-control" >
				   </div> 
				   
				   
					<?php
					 if($acao == "cadastrar"){
					?>
					 <div class="form-group col-xs-12 col-md-12"> 
						  <div class="form-group col-xs-12 col-md-3"> 
							 <label>Quantidade de Ordem de Serviço</label>
							 <input name="parcela" type="number" max="8" min="1" value="<?php echo $parcela;?>" class="form-control" />
						  </div>
						</div> 

					<?php
					 }
					 ?>
					
				<div class="form-group col-xs-12 col-md-6">  
				 <label>Foto da Coleta</label>
				<div class="form-group">
					<?php 
						if($edit['foto'] != '' && file_exists('../uploads/fotos/'.$edit['foto'])){
							echo '<img src="../uploads/fotos/'.$edit['foto'].'"/>';
						}
					?>
				</div>

				<div class="form-group">
					<input type="file" name="assinatura"/>
				</div>
				
		 	</div>
					
		 	<div class="form-group col-xs-12 col-md-6">  
				 <label>Assinatura da Coleta</label>
				<div class="form-group">
					<?php 
						if($edit['assinatura'] != '' && file_exists('../uploads/assinaturas-ordem/'.$edit['assinatura'])){
							echo '<img src="../uploads/assinaturas-ordem/'.$edit['assinatura'].'"/>';
						}
					?>
				</div>

				<div class="form-group">
					<input type="file" name="assinatura"/>
				</div>
		  	</div>
					
		           
            </div><!-- /.row -->
		  </div><!-- /.box-body -->                   
               
		  <div class="box-body">
             <div class="row">
                                     
              <div class="box-footer">
				  
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>	
				  
				 <?php 
				
					if($acao=="baixar"){
						echo '<input type="submit" name="realizar" value="Realizar" 
							class="btn btn-primary" />';
						echo '<input type="submit" name="transferir" value="Transferir" 
							class="btn btn-success" />';
						echo '<input type="submit" name="cancelar" value="Cancelar""
							class="btn btn-danger" />' ;
						echo '<input type="submit" name="deletar" value="Deletar"  
							class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';  
					}

					if($acao=="cadastrar"){
						echo '<input type="submit" name="cadastrar" value="Cadastrar" 
						class="btn btn-primary" />';
					}

					if($acao=="atualizar"){
						
				 		echo '<input type="submit" name="atualizar" value="Atualizar" 	class="btn btn-primary" />';
						
						echo '<input type="submit" name="enviar" value="Enviar Ordem" 
							class="btn btn-success" />';
					}
					
				  ?>
				  
				 </div> <!-- /.box-footer -->
				 
				</div><!-- /.row -->
		      </div><!-- /.box-body --> 
		      
		  </form>
		
  	</div><!-- /.box-body -->

  </section>
<!-- /.content -->
	
	
<section class="content">
  	<div class="box box-warning">
     	 <div class="box-body">
       		 
       		 
         <?php
           echo '<p align="center">'.$cliente['nome'].', '.$cliente['telefone'].' / '.$cliente['contato'].'</p>';
			echo '<p align="center">'.$cliente['endereco'].', '.$cliente['numero'].'  '.$cliente['complemento'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'].'</p>';
				
			$address = url($cliente['endereco'].', '.$cliente['numero'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'])
				
        ?>
           		
         		
       	<iframe width='100%' height="300px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
        </iframe>
  		 </div>
	 </div>
</section><!-- /.content -->
 
</div><!-- /.content-wrapper -->

         
           
