<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}

		if(!empty($_GET['contratoAditivoEditar'])){
			$contratoAditivoId = $_GET['contratoAditivoEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['contratoAditivoDeletar'])){
			$contratoAditivoId = $_GET['contratoAditivoDeletar'];
			$acao = "deletar";
		}

		if(!empty($_GET['contratoId'])){
			
			$contratoId = $_GET['contratoId'];
			$acao = "cadastrar";
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readContrato as $contrato);
			 
			$clienteId = $contrato['id_cliente'];
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				//header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readCliente as $cliente);
			$acao = "cadastrar";
			
			$endereco=substr($cliente['endereco'],0,25).', '.$cliente['numero'].' - '.substr($cliente['complemento'],0,10);
			$edit['endereco']= $endereco;
			$contratoId = $_GET['contratoId'];
			
			
			$diaSemana='';
			$hora='';
			
			$rotaNome='';
			if($contrato['domingo']==1){
				$diaSemana = ' Dom [ x ]';
				$hora = ' Dom '.$contrato[ 'domingo_hora1' ];
				$rotaId = $contrato[ 'domingo_rota1' ];
				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
				$rotaNome = $rota[ 'nome' ];
			}
			if($contrato['segunda']==1){
				$diaSemana = $diaSemana . ' Seg [ x ]';
				$hora = $hora . ' Seg '.$contrato[ 'segunda_hora1' ];
				$rotaId = $contrato[ 'segunda_rota1' ];
				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
				$rotaNome = $rotaNome .' ' . $rota[ 'nome' ];
			}
			if($contrato['terca']==1){
				$diaSemana = $diaSemana . ' Ter [ x ]';
				$hora = $hora . ' Ter '.$contrato[ 'terca_hora1' ];
				$rotaId = $contrato[ 'terca_rota1' ];
				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
				$rotaNome = $rotaNome .' ' . $rota[ 'nome' ];
			}
			if($contrato['quarta']==1){
				$diaSemana = $diaSemana . ' Qua [ x ]';
				$hora = $hora . ' Qua '.$contrato[ 'quarta_hora1' ];
				$rotaId = $contrato[ 'quarta_rota1' ];
				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
				$rotaNome = $rotaNome .' ' . $rota[ 'nome' ];
			}
			if($contrato['quinta']==1){
				$diaSemana = $diaSemana . ' Qui [ x ]';
				$hora = $hora . ' Qui '.$contrato[ 'quinta_hora1' ];
				$rotaId = $contrato[ 'quinta_rota1' ];
				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
				$rotaNome = $rotaNome .' ' . $rota[ 'nome' ];
			}
			if($contrato['sexta']==1){
				$diaSemana = $diaSemana . ' Sex [ x ]';
				$hora = $hora . ' Sex '.$contrato[ 'sexta_hora1' ];
				$rotaId = $contrato[ 'sexta_rota1' ];
				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
				$rotaNome = $rotaNome .' ' . $rota[ 'nome' ];
			}
			if($contrato['sabado']==1){
				$diaSemana = $diaSemana . ' Sab [ x ]';
				$hora = $hora . ' Sab '.$contrato[ 'sabado_hora1' ];
				$rotaId = $contrato[ 'sabado_rota1' ];
				$rota = mostra( 'contrato_rota', "WHERE id AND id='$rotaId'" );
				$rotaNome = $rotaNome .' ' . $rota[ 'nome' ];
			}


			// 1 - semanal - 2 quinzenal - 3 mensal - 4 avulso
			$frequenciaId = $contrato[ 'frequencia' ];
			$frequencia = mostra( 'contrato_frequencia', "WHERE id AND id='$frequenciaId'" );
			$frequencia = $frequencia[ 'nome' ];

			$edit['frequencia']= $frequencia;
			$edit['dia']= $diaSemana;
			$edit['hora']= $hora;
			$edit['rota']= $rotaNome;
			
			$dataroteiro = date('Y-m-d');
			
			$tipoColeta = mostra( 'contrato_coleta', "WHERE id AND inicio<='$dataroteiro' AND vencimento>='$dataroteiro' AND id_contrato='$contratoId'" );
			
			$edit['tipo_coleta'] = $tipoColeta[ 'tipo_coleta' ];
			$edit['quantidade'] = $tipoColeta[ 'quantidade' ];
			$edit['valor_unitario'] = $tipoColeta[ 'valor_unitario' ];
			$edit['valor_extra'] = $tipoColeta[ 'valor_extra' ];
			$edit['valor_mensal'] = $tipoColeta[ 'valor_mensal' ];
		 		
			$edit['interacao']= date('Y/m/d H:i:s');
			$edit['aprovacao'] = date('Y-m-d');
			
			$_SESSION['aba']='10';
				
		}

		if(!empty($contratoAditivoId)){
			
			$readAdtidivo = read('contrato_aditivo',"WHERE id = '$contratoAditivoId'");
			if(!$readAdtidivo){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readAdtidivo as $edit);
			
			$contratoId = $edit['id_contrato'];;
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readContrato as $contrato);
			
			$clienteId = $contrato['id_cliente'];
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readCliente as $cliente);
		}


		

		$_SESSION['aba']=1;
 ?>
 


<div class="content-wrapper">
     <section class="content-header">
         <h1>Aditivo</h1>
         <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Contrato</a></li>
            <li><a href="painel.php?execute=suporte/contrato/contrato-editar">Aditivo</a></li>
             <li class="active">Editar</li>
          </ol>
      </section>
      
	 <section class="content">
         <div class="box box-default">
           <div class="box-header with-border">
            	 <?php require_once('cliente-modal.php'); ?>
               	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->

	<div class="box-body table-responsive">
	
	<?php 
		
	 
	
		if(isset($_POST['cadastrar'])){
			
			$cad['id_contrato'] = $contratoId;
			$cad['id_cliente'] = $clienteId;
			
			$cad['motivo'] = mysql_real_escape_string($_POST['motivo']);
			$cad['aprovacao'] = mysql_real_escape_string($_POST['aprovacao']);
			$cad['inicio'] = mysql_real_escape_string($_POST['inicio']);

			$cad['endereco'] = mysql_real_escape_string($_POST['endereco']);
			$cad['frequencia'] = mysql_real_escape_string($_POST['frequencia']);
			$cad['dia'] = mysql_real_escape_string($_POST['dia']);
			$cad['hora'] = mysql_real_escape_string($_POST['hora']);
			$cad['tipo_coleta'] = mysql_real_escape_string($_POST['tipo_coleta']);
			$cad['quantidade'] = mysql_real_escape_string($_POST['quantidade']);
			$cad['valor_unitario'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
			$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
			$cad['valor_extra']	= strip_tags(trim(mysql_real_escape_string($_POST['valor_extra'])));
			$cad['valor_extra'] = str_replace(",",".",str_replace(".","",$cad['valor_extra']));
			$cad['valor_mensal'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal'])));
			$cad['valor_mensal'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal']));
			$cad['rota'] = mysql_real_escape_string($_POST['rota']);
			
			$cad['endereco_aditivo'] = mysql_real_escape_string($_POST['endereco_aditivo']);
			$cad['frequencia_aditivo'] = mysql_real_escape_string($_POST['frequencia_aditivo']);
			$cad['dia_aditivo'] = mysql_real_escape_string($_POST['dia_aditivo']);
			$cad['hora_aditivo'] = mysql_real_escape_string($_POST['hora_aditivo']);
			$cad['tipo_coleta_aditivo'] = mysql_real_escape_string($_POST['tipo_coleta_aditivo']);
			$cad['quantidade_aditivo'] = mysql_real_escape_string($_POST['quantidade_aditivo']);
			$cad['valor_unitario_aditivo'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario_aditivo'])));
			$cad['valor_unitario_aditivo'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario_aditivo']));
			$cad['valor_extra_aditivo']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_extra_aditivo'])));
			$cad['valor_extra_aditivo'] = str_replace(",",".",str_replace(".","",$cad['valor_extra_aditivo']));
			$cad['valor_mensal_aditivo']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal_aditivo'])));
			$cad['valor_mensal_aditivo'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal_aditivo']));
				
				
			$cad['rota_aditivo'] = mysql_real_escape_string($_POST['rota_aditivo']);
			
			$cad['aprovacao_comercial']= '3';
			$cad['aprovacao_comercial_observacao']= strip_tags(trim($_POST['aprovacao_comercial_observacao']));
			
			$cad['aprovacao_operacional']= strip_tags(trim($_POST['aprovacao_operacional']));
		 	$cad['aprovacao_operacional_observacao']= strip_tags(trim($_POST['aprovacao_operacional_observacao']));
		
			
			$cad['interacao']= date('Y/m/d H:i:s');
			
			
			create('contrato_aditivo',$cad);	
			
			$interacao='Contrato Aditivado';
			interacao($interacao,$contratoId);
			$_SESSION['aba']='10';
			header("Location: ".$_SESSION['contrato-editar']);
			

		}
		
		if(isset($_POST['atualizar'])){
			
			$cad['motivo'] = mysql_real_escape_string($_POST['motivo']);
			$cad['aprovacao'] = mysql_real_escape_string($_POST['aprovacao']);
			$cad['inicio'] = mysql_real_escape_string($_POST['inicio']);
			$cad['empresa_concorrente'] = mysql_real_escape_string($_POST['empresa_concorrente']);
			
			$cad['endereco'] = mysql_real_escape_string($_POST['endereco']);
			$cad['frequencia'] = mysql_real_escape_string($_POST['frequencia']);
			$cad['dia'] = mysql_real_escape_string($_POST['dia']);
			$cad['hora'] = mysql_real_escape_string($_POST['hora']);
			$cad['tipo_coleta'] = mysql_real_escape_string($_POST['tipo_coleta']);
			$cad['quantidade'] = mysql_real_escape_string($_POST['quantidade']);
			$cad['valor_unitario'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
			$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
			$cad['valor_extra']	= strip_tags(trim(mysql_real_escape_string($_POST['valor_extra'])));
			$cad['valor_extra'] = str_replace(",",".",str_replace(".","",$cad['valor_extra']));
			$cad['valor_mensal'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal'])));
			$cad['valor_mensal'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal']));
			$cad['rota'] = mysql_real_escape_string($_POST['rota']);
			
			$cad['endereco_aditivo'] = mysql_real_escape_string($_POST['endereco_aditivo']);
			$cad['frequencia_aditivo'] = mysql_real_escape_string($_POST['frequencia_aditivo']);
			$cad['dia_aditivo'] = mysql_real_escape_string($_POST['dia_aditivo']);
			$cad['hora_aditivo'] = mysql_real_escape_string($_POST['hora_aditivo']);
			$cad['tipo_coleta_aditivo'] = mysql_real_escape_string($_POST['tipo_coleta_aditivo']);
			$cad['quantidade_aditivo'] = mysql_real_escape_string($_POST['quantidade_aditivo']);
			$cad['valor_unitario_aditivo'] = strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario_aditivo'])));
			$cad['valor_unitario_aditivo'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario_aditivo']));
			$cad['valor_extra_aditivo']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_extra_aditivo'])));
			$cad['valor_extra_aditivo'] = str_replace(",",".",str_replace(".","",$cad['valor_extra_aditivo']));
			$cad['valor_mensal_aditivo']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal_aditivo'])));
			$cad['valor_mensal_aditivo'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal_aditivo']));

			$cad['rota_aditivo'] = mysql_real_escape_string($_POST['rota_aditivo']);
			
			$cad['interacao']= date('Y/m/d H:i:s');
			
			update('contrato_aditivo',$cad,"id = '$contratoAditivoId'");	

			header("Location: ".$_SESSION['contrato-editar']);
			$_SESSION['aba']='10';
		}
		
			if(isset($_POST['deletar'])){
		 
			
			delete('contrato_aditivo',"id = '$contratoAditivoId'");

			header("Location: ".$_SESSION['contrato-editar']);
			$_SESSION['aba']='10';
		}
		
		if(isset($_POST['autorizarComercial'])){
		
		if($_POST['aprovacao_comercial']<>"3"){
			
			$cad['aprovacao_comercial']= strip_tags(trim($_POST['aprovacao_comercial']));
			$cad['aprovacao_comercial_observacao']= strip_tags(trim($_POST['aprovacao_comercial_observacao']));
			$cad['interacao']= date('Y/m/d H:i:s');
			update('contrato_aditivo',$cad,"id = '$contratoAditivoId'");
		 
			header("Location: ".$_SESSION['url']);
		 }else{ 
			
			echo '<div class="alert alert-warning">Autorização ainda não marcada!</div>'.'<br>';
		}	
	}
		
	if(isset($_POST['autorizarOperacional'])){
		
		if($_POST['autorizarOperacional']<>"2"){
			
		$cad['aprovacao_operacional']= strip_tags(trim($_POST['aprovacao_operacional']));
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['rota_aditivo']= strip_tags(trim(mysql_real_escape_string($_POST['rota_aditivo'])));
		$cad['hora_aditivo	']= strip_tags(trim(mysql_real_escape_string($_POST['hora_aditivo	'])));
$cad['aprovacao_operacional_observacao']= strip_tags(trim($_POST['aprovacao_operacional_observacao']));

			update('contrato_aditivo',$cad,"id = '$contratoAditivoId'");	
			header("Location: ".$_SESSION['url']);
					
		}else{ 
			
			echo '<div class="alert alert-warning">Autorização ainda não marcada!</div>'.'<br>';
		}	
	}
		
		if(isset($_POST['pdf-aditivos'])){
			// arquivo
			$arquivo = $_FILES['arquivo'];
			// Tamanho máximo do arquivo (em Bytes)
			$tamanhoPermitido = 1024 * 1024 * 2; // 2Mb
			//Define o diretorio para onde enviaremos o arquivo
			$diretorio = "../uploads/aditivos/";

			// verifica se arquivo foi enviado e sem erros
			if( $arquivo['error'] == UPLOAD_ERR_OK ){

				// pego a extensão do arquivo
				$extensao = extensao($arquivo['name']);

				// valida a extensão
				if( in_array( $extensao, array("pdf") ) ){

					// verifica tamanho do arquivo
					if ( $arquivo['size'] > $tamanhoPermitido ){
							echo '<div class="alert alert-info">O arquivo enviado é muito grande!</div>'.'<br>';	
					}else{

						// atribui novo nome ao arquivo
						$novo_nome  = $edit['id'].".".$extensao;
						// faz o upload
						$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);

						if($enviou){
							echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
						}else{
							echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
						}
					}

				}else{
					echo '<div class="alert alert-info">Somente arquivos PDF são permitidos</div>'.'<br>';
				}

			}else{
				echo '<div class="alert alert-info">Você deve enviar um arquivo!</div>'.'<br>';
			}
		}
		
		
		function extensao($arquivo){
			$arquivo = strtolower($arquivo);
			$explode = explode(".", $arquivo);
			$arquivo = end($explode);
			return ($arquivo);
		}
		
		
		
	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 				
                <div class="box-header with-border">
                  <h3 class="box-title">Aditivo de Contrato</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                
                <div class="row">
                    
                    <div class="form-group col-xs-12 col-md-1">  
                     <label>Id</label>
                      <input name="id"  class="form-control" type="text" value="<?php echo $edit['id'];?>" disabled/>
                     </div>
                     
                     <div class="form-group col-xs-12 col-md-2">  
                   		 <label>Interação</label>
                  	 	 <input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control"  disabled /> 
                     </div>
 
		  		 <div class="form-group col-xs-12 col-md-3">  
                       <label>Motivo</label>
                      <select name="motivo"  class="form-control" >
                            <option value="">Selecione Motivo</option>
                                <?php 
                                    $leitura = read('contrato_aditivo_motivo',"WHERE id ORDER BY id ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos motivo de baixar no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['motivo'] == $mae['id']){
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
                     <label>Empresa Concorrente</label>
                      <input name="empresa_concorrente"  class="form-control" type="text" value="<?php echo $edit['empresa_concorrente'];?>" />
                     </div>		 
					 
		   <div class="form-group col-xs-12 col-md-2">  
                 <label>Aprovação<label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
              			 <input type="date" name="aprovacao" class="form-control pull-right" value="<?php echo $edit['aprovacao'];?>"/>
                </div><!-- /.input group -->
           </div> 
					 
		 <div class="form-group col-xs-12 col-md-2">  
                 <label>Inicio<label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
              			 <input type="date" name="inicio" class="form-control pull-right" value="<?php echo $edit['inicio'];?>"/>
                </div><!-- /.input group -->
           </div> 
				   
		
				  
		<div class="form-group col-xs-12 col-md-6"> 
					<label>4.1 - Frequência da Coleta: </label>
					<input type="text" name="frequencia" value="<?php echo $edit['frequencia'];?>"  class="form-control"  >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Frequencia  (Aditivo)</label>
					<input type="text" name="frequencia_aditivo" value="<?php echo $edit['frequencia_aditivo'];?>"  class="form-control" >
		</div> 
		 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>4.2 – Dias de Coleta </label>
					<input type="text" name="dia" value="<?php echo $edit['dia'];?>"  class="form-control"  >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Dias de Coleta  (Aditivo)</label>
					<input type="text" name="dia_aditivo" value="<?php echo $edit['dia_aditivo'];?>"  class="form-control" >
		</div> 
					 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>4.3 - Endereço </label>
					<input type="text" name="endereco" value="<?php echo $edit['endereco'];?>"  class="form-control" >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Endereço (Aditivo) </label>
					<input type="text" name="endereco_aditivo" value="<?php echo $edit['endereco_aditivo'];?>"  class="form-control" >
		</div> 
	 
	
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>4.4 – Valor a ser cobrado - Valor Unitário </label>
					<input type="text" name="valor_unitario" value="<?php echo converteValor($edit['valor_unitario']);?>"  class="form-control"  >
		</div> 
		
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Valor Unitário (Aditivo)</label>
					<input type="text" name="valor_unitario_aditivo" value="<?php echo converteValor($edit['valor_unitario_aditivo']);?>"  class="form-control" >
		</div> 
					 
		<div class="form-group col-xs-12 col-md-6"> 
					<label>4.5 – Valor mínimo a ser cobrado mensalmente </label>
					<input type="text" name="valor_mensal" value="<?php echo converteValor($edit['valor_mensal']);?>"  class="form-control">
		</div> 	
	
		<div class="form-group col-xs-12 col-md-6"> 
					<label>Valor Mensal (Aditivo)</label>
					<input type="text" name="valor_mensal_aditivo" value="<?php echo converteValor($edit['valor_mensal_aditivo']);?>"  class="form-control" >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>4.6 – Quantidade a ser cobrada como coletada por viagem </label>
					<input type="text" name="quantidade" value="<?php echo $edit['quantidade'];?>"  class="form-control" >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Quantidade (Aditivo)</label>
					<input type="text" name="quantidade_aditivo" value="<?php echo $edit['quantidade_aditivo'];?>"  class="form-control" >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>4.7 – A cada contêiner ou saco extra coletado será cobrado o valor de </label>
					<input type="text" name="valor_extra" value="<?php echo converteValor($edit['valor_extra']);?>"  class="form-control" >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Valor Extra Aditivo </label>
					<input type="text" name="valor_extra_aditivo" value="<?php echo converteValor($edit['valor_extra_aditivo']);?>"  class="form-control" >
		</div> 
					 
		  <div class="form-group col-xs-12 col-md-6">  
                       <label>4.8  - Tipo de Coleta</label>
                      <select name="tipo_coleta"  class="form-control"  >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>
                    
		
		   <div class="form-group col-xs-12 col-md-6">  
                       <label>Tipo de Coleta (Aditivo)</label>
                      <select name="tipo_coleta_aditivo"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta_aditivo'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select>
                  </div>
                     
					 
		
					 
		
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Rota </label>
					<input type="text" name="rota" value="<?php echo $edit['rota'] ;?>"  class="form-control"  >
		</div> 
					 
		 <div class="form-group col-xs-12 col-md-6"> 
					<label>Rota (Aditivo) </label>
					<input type="text" name="rota_aditivo" value="<?php echo $edit['rota_aditivo'];?>"  class="form-control" >
		</div> 
	 
			 
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
					 
					  <div class="box-header with-border">
                        <h3 class="box-title">Liberações</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                       <div class="row">  
                      
							 <div class="form-group col-xs-3">
								<label>Aprovação Comecial</label>
								<select name="aprovacao_comercial" class="form-control" <?php echo $liberacaoComercial;?> >
								 <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_comercial'] == '1') echo' selected="selected"';?> value="1" >Aprovado</option>
								  <option <?php if($edit['aprovacao_comercial'] == '2') echo' selected="selected"';?> value="2" >Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_comercial'] == '3') echo' selected="selected"';?> value="3">Solicitar Aprovação</option>
								 </select>
							</div><!-- /.row -->
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_comercial_observacao" value="<?php echo $edit['aprovacao_comercial_observacao'];?>" class="form-control" <?php echo $liberacaoComercial;?>  />
                            </div>
		                       
                        </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->    
					 
					  <div class="box-body">
                       <div class="row">  
						   
                            <div class="form-group col-xs-3">
								<label>Liberação Operacional</label>
								<select name="aprovacao_operacional" class="form-control"  >
								  <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_operacional'] == '1') echo' selected="selected"';?> value="1" <?php echo $liberacaoOperacional;?>>Liberado</option>
								  <option <?php if($edit['aprovacao_operacional'] == '2') echo' selected="selected"';?> value="2" <?php echo $liberacaoOperacional;?>>Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_operacional'] == '3') echo' selected="selected"';?> value="3">Solicitar Rota</option>
								 </select>
							</div><!-- /.row -->
                           
                             <div class="form-group col-xs-12 col-md-2">  
                               <label>Rota </label>
							  <select name="rota_aditivo"  class="form-control"  <?php echo $liberacaoOperacional;?>>
									<option value="">Selecione Rota</option>
										<?php 
											$leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
											if(!$leitura){
												echo '<option value="">Nao temos status no momento</option>';	
											}else{
												foreach($leitura as $mae):
												   if($edit['rota_aditivo'] == $mae['id']){
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
                                <label>Hora </label>
                                <input type="text" name="hora_aditivo" value="<?php echo $edit['hora_aditivo'];?>" class="form-control" <?php echo $liberacaoOperacional;?>  />
                            </div>
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_operacional_observacao" value="<?php echo $edit['aprovacao_operacional_observacao'];?>" class="form-control" <?php echo $liberacaoOperacional;?>  />
                            </div>
						   
						 
                            
                     </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->   
                     
                     
               
       	  <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>	
			  
				 <?php 
		
					if($acao=="atualizar"){
						 echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
					}
			  
			  		if($acao=="cadastrar"){
						 echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';
					}
					
					if($acao=="deletar"){
						 echo '<input type="submit" name="deletar" value="Excluir" class="btn btn-danger" />';
					}
			  
			  		if($acao=="enviar"){
						echo '<input type="submit" name="enviar" value="Enviar Aviso" class="btn btn-primary" />';	
					}
			  
			  		if($acao=="autorizarComercial"){
					  echo '<input type="submit" name="autorizarComercial" value="Autorizar Proposta" class="btn btn-primary" />';
					}

					if($acao=="autorizarOperacional"){
					  echo '<input type="submit" name="autorizarOperacional" value="Autorizar Rota" class="btn btn-primary" />';
					}
			  
				?>

				 </div> <!-- /.box-footer -->
		  </form>
		  
		  
		     
    </div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
		
<section class="content">	
	
	  <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
					   <input type="file" name="arquivo" placeholder="Procurar">
						
					   <br><br>
						
					  <input type="submit" name="pdf-aditivos" value="Upload Aditivos PDF" class="btn btn-primary"/>
					</form>
				  </div> 
				
					<div class="form-group pull-right">
					
						<?php
						$pdf='../uploads/aditivos/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/aditivos/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Aditivos" />
								</a>';	
						}
					?>
				</div> 

			</div>
		 </div>
		</div>
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
           
  

