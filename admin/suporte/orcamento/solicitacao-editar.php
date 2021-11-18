<?php 
	
		 if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['solicitacaoEditar'])){
			$solicitacaoId = $_GET['solicitacaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['solicitacaoDeletar'])){
			$solicitacaoId = $_GET['solicitacaoDeletar'];
			$acao = "deletar";
		}

		if ( !empty( $_GET[ 'solicitacaoEnviar' ] ) ) {
			$solicitacaoId = $_GET[ 'solicitacaoEnviar' ];
			$acao = "enviar";
			$readonly = "readonly";
			$disabled = 'disabled="disabled"';
		}
	 

		if(!empty($solicitacaoId)){
			$readorcamento = read('cadastro_visita',"WHERE id = '$solicitacaoId'");
			if(!$readorcamento){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readorcamento as $edit);
			$clienteId = $edit['id'];
			$cliente = mostra('cadastro_visita',"WHERE id = '$clienteId'");
		  }else{
			$edit['status']= 1;
		}
		
		$_SESSION['url2']=$_SERVER['REQUEST_URI'];

		// 0 - visita
		// 1 - solicitacoes
		// 2 - orcamento
 ?>

<div class="content-wrapper">
    
     <section class="content-header">
              <h1>Solicitações -</h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Orçamento</a></li>
                <li><a href="painel.php?execute=suporte/orcamento/solicitaçoes">Solicitações</a></li>
                 <li class="active">Editar</li>
              </ol>
     </section>
     
	 <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <?php require_once ("cliente-modal.php");?>
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
	
		if(isset($_POST['visualizar'])){
		 	header('Location: painel.php?execute=suporte/orcamento/orcamentos');
		}
	
		if(isset($_POST['cadastrar'])){
			
			$cad['nome'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['endereco']	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['numero'] 		= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$cad['complemento'] 		= strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$cad['bairro']		= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['telefone'] 		= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['contato'] 		= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));

				$cad['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
				$cad['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
				$cad['inscricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
				$cad['cpf']   	= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
				$cad['referencia']   	= strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
			
				$cad['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
				$cad['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
				$cad['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
				$cad['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
				$cad['orc_observacao']= strip_tags(trim(mysql_real_escape_string($_POST['orc_observacao'])));
				$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
				$cad['orc_limite']= strip_tags(trim(mysql_real_escape_string($_POST['orc_limite'])));
				$cad['consultor']= strip_tags(trim(mysql_real_escape_string($_POST['consultor'])));
				$cad['atendente']= strip_tags(trim(mysql_real_escape_string($_POST['atendente'])));
				$cad['indicacao']  = mysql_real_escape_string($_POST['indicacao']);
				$cad['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
				$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
				$cad['orc_limite']= strip_tags(trim(mysql_real_escape_string($_POST['orc_limite'])));
				$cad['interacao']= date('Y/m/d H:i:s');
			
				if(empty($cad['nome'])){
						echo '<div class="alert alert-warning">O Nome é obrigatório!</div>'.'<br>';
					}elseif(empty($cad['endereco'])){
						echo '<div class="alert alert-warning">O Endereço é obrigatório!</div>'.'<br>';
					}elseif(empty($cad['telefone'])){
						echo '<div class="alert alert-warning">O telefone é obrigatório!</div>'.'<br>';
					}else{
						$cad['interacao']= date('Y/m/d H:i:s');
						$cad['orc_solicitacao']= date('Y/m/d H:i:s');
							
						
						$cad['status'] =1;
					
						create('cadastro_visita',$cad);	
						header("Location: ".$_SESSION['url']);
				}
		}
			 
			 
		if(isset($_POST['atualizar'])){
			
			$cad['nome'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['endereco']	= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['numero'] 		= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$cad['complemento'] 		= strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$cad['bairro']		= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['telefone'] 		= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['contato'] 		= strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
			$cad['email'] = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			
			$cad['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$cad['inscricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
			$cad['referencia']   	= strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
			
			$cad['cpf']   	= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$cad['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
			$cad['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
			$cad['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
			$cad['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
			$cad['orc_observacao']= strip_tags(trim(mysql_real_escape_string($_POST['orc_observacao'])));
			$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
			$cad['orc_limite']= strip_tags(trim(mysql_real_escape_string($_POST['orc_limite'])));
			$cad['consultor']= strip_tags(trim(mysql_real_escape_string($_POST['consultor'])));
			$cad['atendente']= strip_tags(trim(mysql_real_escape_string($_POST['atendente'])));
			$cad['indicacao']  = mysql_real_escape_string($_POST['indicacao']);
			$cad['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
			$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
			$cad['orc_limite']= strip_tags(trim(mysql_real_escape_string($_POST['orc_limite'])));
			$cad['interacao']= date('Y/m/d H:i:s');
			
			update('cadastro_visita',$cad,"id = '$solicitacaoId'");	
			header("Location: ".$_SESSION['url']);
		}
			 
		if(isset($_POST['enviar'])){
			
			$cad['status'] = 2;
			$cad['interacao']= date('Y/m/d H:i:s');
			update('cadastro_visita',$cad,"id = '$solicitacaoId'");	
			
			$consultorId = $edit['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");

			$assunto  = "Nova solicitação de visita : " . $edit['nome'];

			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";

			$msg .= "Visita N&deg; : " . $edit['id'] . "<br />";
		//	$msg .= "Nome : " . $edit['nome'] . "<br />";
//			$msg .= "Endereco : ".$edit['endereco'].', '.$edit['numero'].' - '.$edit['complemento'] .'  '.$edit['bairro'] . "<br />";
//			$msg .= "Telefone : " . $edit['telefone'] . "<br />";
//			$msg .= "Data da Visita : " . converteData($edit['orc_data']) . "<br />";
//			$msg .= "Observação : " . $edit['orc_observacao'] . "<br /><br />";
//			$msg .= SITENOME . "<br />";
//			$msg .= "Consultor : " . $consultor['nome'] . "<br />";
//			$msg .= "Email : " . $consultor['email'] . "<br />";
//			$msg .= "Telefone : " . $consultor['telefone'] . "<br />";
			$msg .= "Data : " . date('d/m/Y'). "<br /><br />";
			$msg .=  "</font>";

			enviaEmail($assunto,$msg,MAILUSER,SITENOME,$consultor['email'],$consultor['nome']);
			header("Location: ".$_SESSION['url']);
			
		}

		if(isset($_POST['agendar'])){
			$cad['status'] = 2;
			$cad['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
			$cad['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
			$cad['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
			$cad['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
			$cad['orc_observacao']= strip_tags(trim(mysql_real_escape_string($_POST['orc_observacao'])));
			$cad['consultor']= strip_tags(trim(mysql_real_escape_string($_POST['consultor'])));
			$cad['atendente']= strip_tags(trim(mysql_real_escape_string($_POST['atendente'])));
			$cad['indicacao']  = mysql_real_escape_string($_POST['indicacao']);
			$cad['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
			$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
			$cad['orc_limite']= strip_tags(trim(mysql_real_escape_string($_POST['orc_limite'])));
			$cad['interacao']= date('Y/m/d H:i:s');
			if(empty($cad['orc_data'])){
				echo '<div class="alert alert-warning">Ainda sem data de orçamento!</div>';
			  }else{
				update('cadastro_visita',$cad,"id = '$solicitacaoId'");	
				header("Location: ".$_SESSION['url']);
			}
		}
		
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('cadastro_visita',"WHERE id = '$solicitacaoId'");
				if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div>';
				}else{
					delete('cadastro_visita',"id = '$solicitacaoId'");
					header("Location: ".$_SESSION['url']);
				}
		}
	?>
	
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			
                
        <div class="box-header with-border">
           <h3 class="box-title">Dados do Cliente</h3>
        </div><!-- /.box-header -->
                
		<div class="box-body">
        <div class="row">
          
           <div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
            <div class="form-group col-xs-12 col-md-3">  
               	<label>Interação </label>
   				<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div>
           
            <div class="form-group col-xs-12 col-md-8">  
                 <label>Nome</label>
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>">
            </div>
     		<div class="form-group col-xs-12 col-md-2">
               <label>CEP </label>
                <input name="cep" id="cep" value="<?php echo $edit['cep'];?>"  class="form-control" />  
           </div>
			<div class="form-group col-xs-12 col-md-4">   
                <label>Endereco</label>
                <input name="endereco" id="endereco" value="<?php echo $edit['endereco'];?>" class="form-control" />  
            </div>
           <div class="form-group col-xs-12 col-md-1">   
                <label>Numero </label>
                <input name="numero"  value="<?php echo $edit['numero'];?>" class="form-control" />  
            </div> 
             <div class="form-group col-xs-12 col-md-2">   
                <label>Complemento </label>
                <input name="complemento"  value="<?php echo $edit['complemento'];?>" class="form-control" />  
            </div> 
      		<div class="form-group col-xs-12 col-md-3">   
                <label>Bairro</label>
                <input name="bairro" id="bairro" value="<?php echo $edit['bairro'];?>" class="form-control" />           					
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
                <input name="cpf" type="text"  value="<?php echo $edit['cpf'];?>"  class="form-control" OnKeyPress="formatar('###.###.###-##', this)" />
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>CNPJ </label>
                <input type="text" name="cnpj" value="<?php echo $edit['cnpj'];?>"   class="form-control" OnKeyPress="formatar('##.###.###/####-##', this)" />
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
             <label>Inscrição</label>
             <input type="text" name="inscricao"  value="<?php echo $edit['inscricao'];?>" class="form-control" />
           </div> 
           
           <div class="form-group col-xs-12 col-md-2">
               <label>Data do Cadastro </label>
               <input name="orc_solicitacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['orc_solicitacao']));?>" class="form-control" readonly/>
            </div>
            
            <div class="form-group col-xs-12 col-md-10">
                 <label>Referencia</label>
                 <input type="text" name="referencia" value="<?php echo $edit['referencia'];?>" class="form-control" <?php echo $readonly;?>/>
            </div>
    
         </div>  <!-- /.row -->
        </div>  <!-- /.box-body -->
                

                <div class="box-header with-border">
                  <h3 class="box-title">Dados da Proposta</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                
                  <div class="row">
                    
                     <div class="form-group col-xs-12 col-md-2">  
       					<label>Data</label>
   						<input name="orc_solicitacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['orc_solicitacao']));?>" class="form-control"  /> 
               		</div>
                    
                     <div class="form-group col-xs-12 col-md-5"> 
						<label>Tipo de Resíduo</label>
   						<input type="text" name="orc_residuo" value="<?php echo $edit['orc_residuo'];?>" class="form-control" /> 
                	</div>
                     <div class="form-group col-xs-12 col-md-5"> 
						<label>Freqüência da Coleta</label>
   						<input type="text" name="orc_frequencia" value="<?php echo $edit['orc_frequencia'];?>" class="form-control"  /> 
                	</div>
                     <div class="form-group col-xs-12 col-md-6"> 
						<label>Dia da Semana</label>
   						<input type="text" name="orc_dia" value="<?php echo $edit['orc_dia'];?>" class="form-control"   /> 
                	</div>
                     <div class="form-group col-xs-12 col-md-6"> 
						<label>Tipo de Equipamento </label>
               		 <input type="text" name="orc_equipamento" value="<?php echo $edit['orc_equipamento'];?>" class="form-control"/> 
			   		</div>
     			   
      			   <div class="form-group col-xs-12 col-md-12"> 
						<label>Observação da Coleta </label>
               		 <input type="text" name="orc_observacao" value="<?php echo $edit['orc_observacao'];?>" class="form-control"  /> 
			   		</div>
      			 
      			 	
      			 
       			 </div><!-- /.row -->
               
                </div><!-- /.box-body -->
                
               <div class="box-header with-border">
                  <h3 class="box-title">Roteiro</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                 
                  <div class="row">
                  
               		 <div class="form-group col-xs-12 col-md-4">
                		<label>Atendente </label>
                		<select name="atendente" <?php echo $disabled;?> class="form-control"/>
                        	<option value="">Selecione o Atendente</option>
								<?php 
                                    $leitura = read('contrato_atendente',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Não temos atendente no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['atendente'] == $mae['id']){
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
                    <label>Consultor </label>
                    <select name="consultor" <?php echo $disabled;?> class="form-control"/>
                            <option value="">Selecione o Consultor</option>
                                <?php 
                                    $leitura = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Não temos consultor no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['consultor'] == $mae['id']){
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
                        <label>Indicação</label>
                        <select name="indicacao" <?php echo $disabled;?> class="form-control"/>
                            <option value="">Selecione o Indicação</option>
                                <?php 
                                    $leitura = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Não temos indicacao no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['indicacao'] == $mae['id']){
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
                        <label>Data do Orçamento</label>
                        <input type="date" name="orc_data" value="<?php echo $edit['orc_data'];?>"  class="form-control" >
                    </div> 
       
                     <div class="form-group col-xs-12 col-md-4">
                        <label>Hora</label>
                        <select name="orc_hora" <?php echo $disabled;?> class="form-control"/>
                            <option value="">hora</option>
                                <?php 
                                    $leitura = read('contrato_hora',"WHERE nome");
                                    if(!$leitura){
                                        echo '<option value="">Não temos hora no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['orc_hora'] == $mae['nome']){
                                                echo '<option value="'.$mae['nome'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['nome'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      </select> 
                     </div>   
                     <div class="form-group col-xs-12 col-md-4"> 
                        <label>Limite de Horário</label>
                        <input type="text" name="orc_limite" value="<?php echo $edit['orc_limite'];?>"  class="form-control" <?php echo $readonly;?>/>
                     </div> 
         		  </div><!-- /.row -->
               </div><!-- /.box-body -->

             <div class="box-footer">
                  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>

             <?php 
                 
               if($acao=="atualizar"){
                  echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
                }
				
				if($acao=="enviar"){
                  echo '<input type="submit" name="enviar" value="Enviar Email Consultor" class="btn btn-primary" />';
                }
				
                if($acao=="cancelar"){
                    echo '<input type="submit" name="cancelar" value="Cancelar" class="btn btn-danger" />';
                }
                 
				if($acao=="deletar"){
					echo '<input type="submit" name="deletar" value="Excluir" class="btn btn-danger" />';
                }
                if($acao=="cadastrar"){
                   echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />'; 
                }
					
			 ?>

              </div>
              <!-- /.box-footer -->
      </form>
      
  </div><!-- /.box-body -->

  </div><!--/box box-default-->
  
 </section><!-- /.content -->
 
  <section class="content">
  	<div class="box box-warning">
     	 <div class="box-body">
            <div class="box-tools">
  			  <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&cadastro_visitaId=<?PHP echo $solicitacaoId; ?>" class="btnnovo">
			  	<img src="../admin/ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
             </a>	
             <small> Negociaçao  </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('cadastro_visita_negociacao',"WHERE id AND id_visita = '$solicitacaoId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Retorno</td>
                        <td align="center">Descricao</td>
                        <td align="center">Consultor</td>
						<td align="center" colspan="5">Gerenciar</td>
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
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&visitaEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&visitaDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&visitaBaixar='.$mostra['id'].'">
                                            <img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
                                        </a>
                                      </td>';  
		
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
                  	echo '<p align="center">'.$cliente['nome'].', '.$cliente['telefone'].' / '.$cliente['contato'].'</p>';
             	 $address = $cliente['endereco'].', '.$cliente['numero'].', '.$cliente['cidade'].', '.$cliente['cep'];
				echo '<p align="center">'.$cliente['endereco'].', '.$cliente['numero'].' / '.$cliente['complemento'].
				' - '.$cliente['bairro'].' - '.$cliente['cep'].'</p>';
           		?>
             <iframe width='100%' height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
         		</iframe>
  		 </div>
	 </div>
</section><!-- /.content -->
 

</div><!-- /.content-wrapper -->

		
<script>
	
	$(document).ready(function() {
		
            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                $("#ibge").val("");
            }
		
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {
                //Nova variável "cep" somente com dígitos.
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
                                limpa_formulário_cep();
                                alert("CEP nao encontrado.");
                            }
                        });
                    } //end if.
					
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
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