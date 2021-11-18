<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autRota']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}

		
		if(!empty($_GET['ordemBaixar'])){
			$ordemId = $_GET['ordemBaixar'];
			$acao = "baixar";
		}

		if(!empty($ordemId)){
			$readordem = read('contrato_ordem',"WHERE id = '$ordemId'");
			if(!$readordem){
				header('Location: painel.php?execute=suporte/403');
			}
			foreach($readordem as $edit);
			
			$contratoId = $edit['id_contrato'];
			$readContrato = read('contrato',"WHERE id = '$contratoId'");
			if(!$readContrato){
				header('Location: painel.php?execute=suporte/403');	
			}
			foreach($readContrato as $contrato);
			
			$clienteId = $edit['id_cliente'];
			$readCliente = read('cliente',"WHERE id = '$clienteId'");
			if(!$readCliente){
				header('Location: painel.php?execute=suporte/403');	
			}
			foreach($readCliente as $cliente);
		}

		
		$_SESSION['url2']=$_SERVER['REQUEST_URI'];

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
		
		if(isset($_POST['realizar'])){
						
			$cad['hora_coleta'] = date('H:i:s');;
			$cad['quantidade1'] = mysql_real_escape_string($_POST['quantidade1']);
			$cad['nao_coletada'] = mysql_real_escape_string($_POST['nao_coletada']);
			$cad['motivo_nao_coletado'] = mysql_real_escape_string($_POST['motivo_nao_coletado']);
			
		 	
			if($cad['quantidade1'] == '0' AND empty($cad['motivo_nao_coletado']) ){
				
			 	echo '<div class="alert alert-warning">Selecione Motivo</div>';
				
			  }else{
				
				
					if(!empty($_FILES['foto']['tmp_name'])){
						$imagem = $_FILES['foto'];
						$pasta  = '../uploads/fotos/';
						$tmp    = $imagem['tmp_name'];
						$ext    = substr($imagem['name'],-3);
						$nome   = md5(time()).'.'.$ext;
						$cad['foto'] = $nome;
						uploadImg($tmp, $nome, '350', $pasta);	
					}

			
				$cad['status'] = '13';
				$cad['interacao']= date('Y/m/d H:i:s');
				update('contrato_ordem',$cad,"id = '$ordemId'");	
				$interacao='Ordem Realizada (Rota) n. '.$ordemId;
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

				if($cad['quantidade1']<='0'){
					$enviarAviso='NAO';
				}

				if(empty($cad['quantidade1'])){
					$enviarAviso='NAO';
				}

				if(is_numeric(substr($cliente['email'],0,2))){
					$enviarAviso='NAO';
				}

				if($enviarAviso=='SIM'){

					$tipoColetaId = $edit['tipo_coleta1'];
					$coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
					$assunto = "Clean - COLETA REALIZADA " . $cliente['nome'];
					$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
					$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

					$msg .= "Prezado Cliente, <br /><br />";
					$msg .= "A Clean Ambiental informa que a coleta foi realizada com sucesso <br /><br />";


					$endereco=$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'].' -'.$cliente['bairro'];

					$msg .= "Contrato N : " . substr($contrato['controle'],0,6) . "<br />";
					$msg .= "Data Inicio do Contrato : " . converteData($contrato['inicio']) . "<br />";
					$msg .= "Cliente : " . $cliente['nome'] . "<br />";
					$msg .= "Endereço : " . $endereco . "<br />";
					$msg .= "Ordem Número : " . $edit['id'] . "<br />";
					$msg .= "Data da Coleta : " . converteData($edit['data']) . "<br />";
					$msg .= "Tipo de Coleta : " . $coleta['nome'] . "<br />";
					$msg .= "Quantidade  : " . $cad['quantidade1'] . "<br /><br />";

					$msg .= "Estamos também disponíveis no telefone 3104-2992  <br /><br />";

					$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
					$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
					$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";

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

	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  	
  	   	<div class="box-header with-border">
             <h3 class="box-title">Ordem de Serviço</h3>
         </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
          
            	<div class="form-group col-xs-6 col-md-2"> 
               		<label>Id</label>
              		<input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled /> 
                 </div><!-- /.col-md-12 -->
                     
       
				<div class="form-group col-xs-6 col-md-2"> 
					<label>Hora Coleta</label>
						<input name="hora_coleta" type="text" value="<?php echo $edit['hora_coleta'];?>" class="form-control" disabled /> 
			 	</div><!-- /.col-md-12 --> 
 
          
          	 	</div><!-- /.row -->
		  </div><!-- /.box-body --> 
         
          <div class="box-body">
             <div class="row">
         
           		 <div class="form-group col-xs-12 col-md-3">  
                       <label>Tipo de Coleta</label>
                      <select name="tipo_coleta1"  class="form-control"  disabled>
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id");
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
					   <input name="nao_coletada" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['nao_coletada']; ?>  class="minimal" >
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
				 
		<div class="form-group col-xs-12 col-md-12">  
			 <label>Foto da Coleta</label>
			<div class="form-group">
				<?php 
					if($edit['foto'] != '' && file_exists('../uploads/fotos/'.$edit['foto'])){
						echo '<img src="../uploads/fotos/'.$edit['foto'].'"/>';
					}
				?>
			</div>
      
			<div class="form-group">
				<input type="file" name="foto"/>
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
					echo '<input type="submit" name="realizar" value="Realizar" class="btn btn-primary" />';
				  }
				  
				  if($acao=="cadastrar"){
					echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';
				  }
				  
				  if($acao=="atualizar"){
					echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
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
                  	echo '<p align="center">'.$cliente['nome'].', Telefone : '.$cliente['telefone'].' | '.$cliente['contato'].'</p>';
             	 $address = $cliente['endereco'].', '.$cliente['numero'].', '.$cliente['cidade'].', '.$cliente['cep'];
				echo '<p align="center">'.$cliente['endereco'].', '.$cliente['numero'].' '.$cliente['complemento'].
				' - '.$cliente['bairro'].'</p>';
           		?>
             <iframe width='100%' height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
         		</iframe>
  		 </div>
	 </div>
</section><!-- /.content -->
 

</div><!-- /.content-wrapper -->
           
