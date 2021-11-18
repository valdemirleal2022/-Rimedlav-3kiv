<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autConsultor']['id'])){
				header('Location: painel.php');	
			}	
		}

		$consultorId=$_SESSION['autConsultor']['id'];
		$acao = "cadastrar";

		if(!empty($_GET['visitaEditar'])){
			$visitaId = $_GET['visitaEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['visitaDeletar'])){
			$visitaId = $_GET['visitaDeletar']; 
			$acao = "deletar";
		}

		if(!empty($_GET['visitaCancelar'])){
			$visitaId = $_GET['visitaCancelar']; 
			$acao = "cancelar";
		}


		if(!empty($_GET['visitaProposta'])){
			$visitaId = $_GET['visitaProposta']; 
			$acao = "proposta";
		}


		if(!empty($visitaId)){
			$readvisita = read('cadastro_visita',"WHERE id = '$visitaId'");
			if(!$readvisita){
				header('Location: painel.php?execute=suporte/error');
			}
			foreach($readvisita as $edit);
        }else{
			$edit['interacao'] = date('Y/m/d H:i:s');
			$edit['data'] = date('Y/m/d');
		}

		

$_SESSION['url2']=$_SERVER['REQUEST_URI'];
   
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>




<div class="content-wrapper">

     <section class="content-header">
              <h1>Visita</h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Cadastro</a></li>
                <li><a href="painel.php?execute=suporte/visita/visitas">Visitas</a></li>
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
      	  </div><!-- /.box-header -->
      	  
    	<div class="box-body">
         
 <?php 
	
	if(isset($_POST['atualizar'])){
			
		$cad['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		$cad['nome_fantasia']=strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
		
		
			$cad['cep']=strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$cad['complemento']=strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$cad['bairro']=strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cidade']=strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$cad['uf']=strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
			$cad['referencia']=strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
		
		$cad['telefone']=strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
		$cad['contato']=strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
		$cad['email']=strip_tags(trim(mysql_real_escape_string($_POST['email'])));
		$cad['cnpj']=strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
		$cad['inscricao']=strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
		$cad['cpf']=strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
		$cad['empresa_atual']=strip_tags(trim(mysql_real_escape_string($_POST['empresa_atual'])));
		
		$cad['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
		$cad['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
		$cad['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
		$cad['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
		$cad['orc_quantidade']= strip_tags(trim(mysql_real_escape_string($_POST['orc_quantidade'])));
		
		$cad['orc_valor_unitario']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_unitario'])));
		$cad['orc_valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['orc_valor_unitario']));
		
		$cad['orc_valor_extra']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_extra'])));
		$cad['orc_valor_extra'] = str_replace(",",".",str_replace(".","",$cad['orc_valor_extra']));
		
		$cad['orc_valor']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor'])));
		$cad['orc_valor'] = str_replace(",",".",str_replace(".","",$cad['orc_valor']));
		
		$cad['orc_forma_pag']= strip_tags(trim(mysql_real_escape_string($_POST['orc_forma_pag'])));
		
		$cad['orc_comodato']= strip_tags(trim(mysql_real_escape_string($_POST['orc_comodato'])));
		
		$cad['orc_observacao']= $_POST['orc_observacao'];
		$cad['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
		$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
		
			$cad['ligacao']= strip_tags(trim(mysql_real_escape_string($_POST['ligacao'])));
		$cad['atendida']= strip_tags(trim(mysql_real_escape_string($_POST['atendida'])));
	

		if(empty($cad['nome'])){
				echo '<div class="alert alert-warning">O Nome do visita � obrigat�rio!</div>'.'<br>';
			  }else{
				$cad['interacao']= date('Y/m/d H:i:s');
				$cad['consultor'] = $consultorId;
				$cad['data']= date('Y/m/d');
 				update('cadastro_visita',$cad,"id = '$visitaId'");
				header("Location: ".$_SESSION['url']);
			}
		}
			

	 	if(isset($_POST['deletar'])){
            
            	echo '<script type="text/javascript">';
					echo 'function excluir() {';
						echo 'if ( confirm("Confirma Exclusăo de Registro?") ) {';
							echo 'return true;';
						echo '}';
							echo 'return false;';
						echo '}';
				    echo '</script>';
            
			$readDeleta = read('cadastro_visita',"WHERE id = '$visitaId'");
			if(!$readDeleta){
				echo '<div class="alert alert-warning">Desculpe, o registro n�o existe</div><br />';	
			 }else{
				delete('cadastro_visita',"id = '$visitaId'");
				header("Location: ".$_SESSION['url']);
			}
		}
			
	if(isset($_POST['cadastrar'])){
		$edit['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		$edit['nome_fantasia']=strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
		
		$edit['cep']=strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
		$edit['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
		$edit['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$edit['complemento']=strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
		$edit['bairro']=strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
		$edit['cidade']=strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
		$edit['uf']=strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
		$edit['referencia']=strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
		
		$edit['telefone']=strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
		$edit['contato']=strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
		$edit['email']=strip_tags(trim(mysql_real_escape_string($_POST['email'])));
		$edit['cnpj']=strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
		$cad['inscricao']=strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
		$edit['cpf']=strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
		$edit['empresa_atual']=strip_tags(trim(mysql_real_escape_string($_POST['empresa_atual'])));

		$edit['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
		$edit['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
		$edit['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
		$edit['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
		$edit['orc_quantidade']= strip_tags(trim(mysql_real_escape_string($_POST['orc_quantidade'])));

		$edit['orc_valor_unitario']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_unitario'])));
		$edit['orc_valor_unitario'] = str_replace(",",".",str_replace(".","",$edit['orc_valor_unitario']));

		$edit['orc_valor_extra']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_extra'])));
		$edit['orc_valor_extra'] = str_replace(",",".",str_replace(".","",$edit['orc_valor_extra']));

			$edit['orc_valor']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor'])));
			$edit['orc_valor'] = str_replace(",",".",str_replace(".","",$edit['orc_valor']));

			$edit['orc_forma_pag']= strip_tags(trim(mysql_real_escape_string($_POST['orc_forma_pag'])));

			$edit['orc_comodato']= strip_tags(trim(mysql_real_escape_string($_POST['orc_comodato'])));

			$edit['orc_observacao']= $_POST['orc_observacao'];
			$edit['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
			$edit['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
			
			$edit['consultor'] = $consultorId;
			$edit['atendente'] = '31';
			$edit['indicacao'] = '16';
		
			$edit['ligacao']= strip_tags(trim(mysql_real_escape_string($_POST['ligacao'])));
			$edit['atendida']= strip_tags(trim(mysql_real_escape_string($_POST['atendida'])));
	
			$edit['orc_solicitacao']= date('Y/m/d H:i:s');
			$edit['data']= date('Y/m/d');
			$edit['status']= '0';
			
			if(empty($edit['nome'])){
				echo '<div class="alert alert-warning">O Nome da visita � obrigat�rio!</div>'.'<br>';
			}else{
				$edit['interacao']= date('Y/m/d H:i:s');
				create('cadastro_visita',$edit);	
				unset($edit);
				header("Location: ".$_SESSION['url']);
			}
			
		};
			
		if(isset($_POST['proposta'])){
			
			$empresa = mostra('empresa');
			$empresa = mostra('empresa',"WHERE id ORDER BY id DESC");
			$numero=$empresa['numero_contrato'];
			$empresaId=$empresa['id'];
			$numero=$numero+1;
		 	
			$cad['numero_contrato'] = $numero;
			$cad['status'] = '2';
			$cad['orc_data']= date('Y/m/d');
			$cad['interacao']= date('Y/m/d H:i:s');
 			update('cadastro_visita',$cad,"id = '$visitaId'");
			
			$num['numero_contrato']=$numero;	
			update('empresa',$num,"id = '$empresaId'");	 
			header("Location: ".$_SESSION['url']);
		}
			
		if(isset($_POST['cancelar'])){
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = 18;
			$cad['motivo_cancelamento']= strip_tags(trim(mysql_real_escape_string($_POST['motivo_cancelamento'])));
			if(empty($cad['motivo_cancelamento'])){
				echo '<div class="alert alert-warning">Motivo Cancelamento � obrigat�rio!</div>'.'<br>';
			}else{
				update('cadastro_visita',$cad,"id = '$visitaId'");	
				header("Location: ".$_SESSION['url']);
			}
		}

	?>

    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
         
         
         <div class="box-header with-border">
              <h3 class="box-title">Dados da Visita</h3>
         </div><!-- /.box-header -->
                
		<div class="box-body">
        <div class="row">
          
         
           <div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
            <div class="form-group col-xs-12 col-md-2">  
               	<label>Intera��o</label>
   				<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div>
         
         	 <div class="form-group col-xs-12 col-md-2">
               <label>Data do Cadastro </label>
               <input name="data" type="text" value="<?php echo date('d/m/Y',strtotime($edit['data']));?>" class="form-control" readonly/>
            </div>
		 
               <div class="form-group col-xs-1">
                <label>Liga��o</label>
					<select name="ligacao" class="form-control" >
					  <option value="">...</option>
					  <option <?php if($edit['ligacao'] == '1') echo' selected="selected"';?> value="1">Sim</option>
					  <option <?php if($edit['ligacao'] == '0') echo' selected="selected"';?> value="0">N�o</option>
					 </select>
           		 </label>
       		</div>
			
			<div class="form-group col-xs-1">
                <label>Atendida</label>
					<select name="atendida" class="form-control" >
					  <option value="">...</option>
					  <option <?php if($edit['atendida'] == '1') echo' selected="selected"';?> value="1">Sim</option>
					  <option <?php if($edit['atendida'] == '0') echo' selected="selected"';?> value="0">N�o</option>
					 </select>
           		 </label>
       		</div>
            
             <div class="form-group col-xs-12 col-md-5">
              <label>Empresa Atual</label>
              <select name="empresa_atual" <?php echo $disabled;?> class="form-control"/>
				<option value="">Selecione empresa atual</option>
					<?php 
					$leitura = read('cadastro_visita_empresa_atual',"WHERE id ORDER BY nome ASC");
					if(!$leitura){
								echo '<option value="">Nao temos empresa no momento</option>';	
					}else{
						foreach($leitura as $mae):
						if($edit['empresa_atual'] == $mae['id']){
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
                 <label>Nome</label>
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>">
            </div>
             <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome Fantasia</label>
                  <input name="nome_fantasia"  class="form-control" type="text" value="<?php echo $edit['nome_fantasia'];?>">
            </div>
       
     		<div class="form-group col-xs-12 col-md-2">
               <label>CEP </label>
                <input name="cep" id="cep" value="<?php echo $edit['cep'];?>"  class="form-control" <?php echo $readonly;?>/>  
           </div>
			<div class="form-group col-xs-12 col-md-6">   
                <label>Endereco</label>
                <input name="endereco" id="endereco" value="<?php echo $edit['endereco'];?>" class="form-control" <?php echo $readonly;?>/>  
            </div>
           <div class="form-group col-xs-12 col-md-2">   
                <label>Numero </label>
                <input name="numero"  value="<?php echo $edit['numero'];?>" class="form-control" <?php echo $readonly;?>/>  
            </div> 
             <div class="form-group col-xs-12 col-md-2">   
                <label>Complemento </label>
                <input name="complemento"  value="<?php echo $edit['complemento'];?>" class="form-control" <?php echo $readonly;?>/> 
            </div> 
      		<div class="form-group col-xs-12 col-md-3">   
                <label>Bairro</label>
                <input name="bairro" id="bairro" value="<?php echo $edit['bairro'];?>" class="form-control" <?php echo $readonly;?>/>           					
            </div> 
			<div class="form-group col-xs-12 col-md-2">   
                <label>Cidade</label>
                <input name="cidade" id="cidade" value="<?php echo $edit['cidade'];?>" class="form-control" <?php echo $readonly;?>/>           					
            </div> 
			  <div class="form-group col-xs-12 col-md-1">   
                <label>UF </label>
                <input name="uf" id="uf" value="<?php echo $edit['uf'];?>"  class="form-control"  <?php echo $readonly;?>/>       
            </div>

           <div class="form-group col-xs-12 col-md-6">   
                <label>Referencia </label>
                <input name="referencia" value="<?php echo $edit['referencia'];?>" class="form-control" <?php echo $readonly;?>/>         
           </div>
			
            <div class="form-group col-xs-12 col-md-3">   
                <label>Telefone</label>
                <input name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control" />           					
            </div> 
            <div class="form-group col-xs-12 col-md-4">   
                <label>Contato</label>
                <input name="contato"  value="<?php echo $edit['contato'];?>" class="form-control" />           					
            </div> 
             <div class="form-group col-xs-12 col-md-5">   
                <label>Email</label>
                <input name="email"  value="<?php echo $edit['email'];?>" class="form-control" />           					
            </div> 

             <div class="form-group col-xs-12 col-md-4">  
                <label>CPF </label>
                <input name="cpf" type="text" id="cpf"  value="<?php echo $edit['cpf'];?>"  class="form-control" <?php echo $readonly;?>/>
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>CNPJ </label>
                <input type="text" name="cnpj" id="cnpj" value="<?php echo $edit['cnpj'];?>"   class="form-control"   <?php echo $readonly;?>/>
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
             <label>Inscri��o</label>
             <input type="text" name="inscricao"  value="<?php echo $edit['inscricao'];?>" class="form-control" />
           </div> 
          
			
           <div class="form-group col-xs-12 col-md-12">
			<label>Observa��o</label>
				<textarea name="orc_observacao" rows="3" cols="80" class="form-control"/><?php echo htmlspecialchars($edit['orc_observacao']);?></textarea>
		 </div>

         </div>  <!-- /.row -->
        </div>  <!-- /.box-body -->
            
            
        <div class="box-header with-border">
			 <h3 class="box-title">Dados do Or�amento</h3>
		</div>
        <!-- /.box-header -->

          	 <div class="box-body">
                  <div class="row">

                            <div class="form-group col-xs-12 col-md-12">
                                <label>Tipo de Res�duo</label>
                                <input type="text" name="orc_residuo" value="<?php echo $edit['orc_residuo'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Freq�encia da Coleta</label>
                                <input type="text" name="orc_frequencia" value="<?php echo $edit['orc_frequencia'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-8">
                                <label>Dia da Semana</label>
                                <input type="text" name="orc_dia" value="<?php echo $edit['orc_dia'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Tipo de Equipamento </label>
                                <input type="text" name="orc_equipamento" value="<?php echo $edit['orc_equipamento'];?>" class="form-control"/>
                            </div>
                            
                              <div class="form-group col-xs-12 col-md-3">
                                <label>Quantidade M�nima Di�ria </label>
                                <input type="text" name="orc_quantidade" value="<?php echo $edit['orc_quantidade'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Valor Unit�rio R$ </label>
                                <input type="text" name="orc_valor_unitario" value="<?php echo converteValor($edit['orc_valor_unitario']) ;?>" class="form-control"/>
                            </div>

							 <div class="form-group col-xs-12 col-md-3">
								  <label>Valor Extra Unit�rio R$  </label>
								   <input type="text" name="orc_valor_extra" value="<?php echo converteValor($edit['orc_valor_extra']);?>" class="form-control"/>
							 </div>

							<div class="form-group col-xs-12 col-md-3">
                                <label>Valor Mensal R$ </label>
                                <input type="text" name="orc_valor" value="<?php echo converteValor($edit['orc_valor']) ;?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Equipamento por Comodato  </label>
                                <input type="text" name="orc_comodato" value="<?php echo $edit['orc_comodato'] ;?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-6">
                                <label>Forma de Pagamento </label>
                                <input type="text" name="orc_forma_pag" value="<?php echo $edit['orc_forma_pag'];?>" class="form-control"/>
                            </div>

                    </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->
                 
                 
            <div class="form-group col-xs-12 col-md-5">
              <label>Motivo Cancelamento</label>
              <select name="motivo_cancelamento" <?php echo $disabled;?> class="form-control"/>
				<option value="">Selecione motivo cancelamento</option>
					<?php 
					$leitura = read('cadastro_visita_motivo_cancelamento',"WHERE id ORDER BY nome ASC");
					if(!$leitura){
						echo '<option value="">Nao temos motivo no momento</option>';	
					}else{
						foreach($leitura as $mae):
						if($edit['motivo_cancelamento'] == $mae['id']){
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
                <div class="box-footer">
                  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
                 <?php 
					
                    if($acao=="atualizar"){
                       echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
                    }

                    if($acao=="cadastrar"){
                        echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                    }
					
				//   if($acao=="cancelar"){
//                        echo '<input type="submit" name="cancelar" value="Cancelar" class="btn btn-danger" />';	
//                    }
					
					if($acao=="proposta"){
                        echo '<input type="submit" name="proposta" value="Gerar Or�amento" class="btn btn-primary" />';	
                    }
					
                 ?>  
              </div>  <!-- /. box-footer -->        
         </div>  <!-- /. col-md-12 -->   
              
    	</form>
       </div><!-- /.box-body -->

  </div><!--/box box-default-->
  
 </section><!-- /.content -->
 
  <section class="content">
  	<div class="box box-warning">
     	 <div class="box-body">
           
           <div class="box-header">
  			 <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&visitaId=<?PHP echo $visitaId; ?>" class="btnnovo">
			  	<img src="../admin/ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
             </a>	
             <small> Negocia��o  </small>
          	</div>
             <!-- /.box-header -->
         
			 <?php 
      		
             $leitura = read('cadastro_visita_negociacao',"WHERE id AND id_visita = '$visitaId' ORDER BY id DESC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Retorno</td>
                        <td align="center">Descricao</td>
                        <td align="center">Consultor</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
                                echo '<td>'.$mostra['id'].'</td>';
                               echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
								echo '<td>'.converteData($mostra['retorno']).'</td>';
                                echo '<td>'.$mostra['descricao'].'</td>';
								$consultorId = $mostra['consultor'];
								$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
								echo '<td>'.$consultor['nome'].'</td>';
							//	echo '<td align="center">
//                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaEditar='.$mostra['id'].'">
//                                            <img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
//                                        </a>
//                                      </td>';
//  
//								echo '<td align="center">
//                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaDeletar='.$mostra['id'].'">
//                                            <img src="../admin/ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
//                                        </a>
//                                      </td>';  
//								
//								echo '<td align="center">
//                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaBaixar='.$mostra['id'].'">
//                                            <img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
//                                        </a>
//                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
 	
	 </div>
      </div>
</section><!-- /.content -->
 
 <section class="content">
  	<div class="box box-warning">
     	 <div class="box-body">
        		 	  <?php
                  	echo '<p align="center">'.$edit['nome'].', '.$edit['telefone'].' / '.$edit['contato'].'</p>';
             	 $address = $edit['endereco'].', '.$edit['numero'].', '.$edit['cidade'].', '.$edit['cep'];
				echo '<p align="center">'.$edit['endereco'].', '.$edit['numero'].' / '.$edit['complemento'].
				' - '.$edit['bairro'].' - '.$edit['cep'].'</p>';
           		?>
             <iframe width='100%' height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
         		</iframe>
  		 </div>
	 </div>
</section><!-- /.content -->
 

</div><!-- /.content-wrapper -->


<script>
	
	$(document).ready(function() {
		
            function limpa_formul�rio_cep() {
                // Limpa valores do formul�rio de cep.
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                $("#ibge").val("");
            }
		
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {
                //Nova vari�vel "cep" somente com d�gitos.
                var cep = $(this).val().replace(/\D/g, '');
                //Verifica se campo cep possui valor informado.
                if (cep != "") {
                    //Expressao regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;
                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endereco").val("procurando... Aguarde ")
                        $("#bairro").val("")
                        $("#cidade").val("")
                        $("#uf").val("")
                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
								$("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado nao foi encontrado.
                                limpa_formul�rio_cep();
                                alert("CEP nao encontrado.");
                            }
                        });
                    } //end if.
					
                    else {
                        //cep � inv�lido.
                        limpa_formul�rio_cep();
                        alert("Formato de CEP inv�lido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formul�rio.
                    limpa_formul�rio_cep();
                }
            });
	
	
        });
	  
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor-texto');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
	  
	  
	   //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

		
 </script>

           