<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}

		$acao = "cadastrar";
		if(!empty($_GET['funcionarioEditar'])){
			$funcionarioId = $_GET['funcionarioEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['funcionarioVisualizar'])){
			$funcionarioId = $_GET['funcionarioVisualizar'];
			$acao = "visualizar";
		}

		if(!empty($_GET['funcionarioDeletar'])){
			$funcionarioId = $_GET['funcionarioDeletar'];
			$acao = "deletar";
		}

		if(!empty($funcionarioId)){
			$readfuncionario = read('funcionario',"WHERE id = '$funcionarioId'");
			if(!$readfuncionario){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readfuncionario as $edit);
		}

		
 ?>

<div class="content-wrapper">
 
  <section class="content-header">
	  
          <h1>Funcionários</h1>
	  
          <ol class="breadcrumb">
            <li><a href="../cadastro/painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cadastro</a></li>
            <li><a href="#">Funcionários</a></li>
             <li class="active">Editar</li>
          </ol>
	  
  </section>
  
  <section class="content">
	  
      	<div class="box box-default">
		  
            <div class="box-header with-border">
				
                <h3 class="box-title"><?php echo $edit['nome'];?></h3>
				
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
			$cad['id_funcao']= strip_tags(trim(mysql_real_escape_string($_POST['id_funcao'])));
			$cad['data_admissao']= mysql_real_escape_string($_POST['data_admissao']);
			$cad['data_nascimento']	= mysql_real_escape_string($_POST['data_nascimento']);
		$cad['vencimento_habilitacao']= mysql_real_escape_string($_POST['vencimento_habilitacao']);
			$cad['ferias']	= strip_tags(trim(mysql_real_escape_string($_POST['ferias'])));
			$cad['telefone']		= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
 			$cad['rg'] 		= strip_tags(trim(mysql_real_escape_string($_POST['rg'])));
			$cad['cpf'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$cad['status'] 		= strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			
			if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				if(!empty($_FILES['fotoperfil']['tmp_name'])){
						$imagem = $_FILES['fotoperfil'];
						$pasta  = '../uploads/funcionarios/';
				        if(file_exists($pasta.$edit['fotoperfil'])
						  && !is_dir($pasta.$edit['fotoperfil'])){
						    unlink($pasta.$edit['fotoperfil']);
						}
						$tmp    = $imagem['tmp_name'];
						$ext    = substr($imagem['name'],-3);
						$nome   = md5(time()).'.'.$ext;
						$cad['fotoperfil'] = $nome;
						uploadImg($tmp, $nome, '160', $pasta);		
				}
			 
				update('funcionario',$cad,"id = '$funcionarioId'");	
				header('Location: painel.php?execute=suporte/funcionario/funcionarios');
				unset($cad);
			}
			
		}
		
		if(isset($_POST['cadastrar'])){
			
			$cad['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['id_funcao']= strip_tags(trim(mysql_real_escape_string($_POST['id_funcao'])));
			$cad['data_admissao']= mysql_real_escape_string($_POST['data_admissao']);
			$cad['data_nascimento']	= mysql_real_escape_string($_POST['data_nascimento']);
		$cad['vencimento_habilitacao']= mysql_real_escape_string($_POST['vencimento_habilitacao']);
			$cad['ferias']	= strip_tags(trim(mysql_real_escape_string($_POST['ferias'])));
			$cad['telefone']		= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
 			$cad['rg'] 		= strip_tags(trim(mysql_real_escape_string($_POST['rg'])));
			$cad['cpf'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				if(!empty($_FILES['fotoperfil']['tmp_name'])){
						$imagem = $_FILES['fotoperfil'];
						$pasta  = '../uploads/funcionarios/';
				        if(file_exists($pasta.$edit['fotoperfil'])
						  && !is_dir($pasta.$edit['fotoperfil'])){
						    unlink($pasta.$edit['fotoperfil']);
						}
						$tmp    = $imagem['tmp_name'];
						$ext    = substr($imagem['name'],-3);
						$nome   = md5(time()).'.'.$ext;
						$cad['fotoperfil'] = $nome;
						uploadImg($tmp, $nome, '160', $pasta);		
				}
				create('funcionario',$cad);	
				header('Location: painel.php?execute=suporte/funcionario/funcionarios');
				unset($cad);
			}
		}
		
		if(isset($_POST['deletar'])){
			delete('funcionario',"id = '$funcionarioId'");	
			header('Location: painel.php?execute=suporte/funcionario/funcionarios');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
      	<div class="form-group">
          	<?php 
				if($edit['fotoperfil'] != '' && file_exists('../uploads/funcionarios/'.$edit['fotoperfil'])){
					echo '<img src="../uploads/funcionarios/'.$edit['fotoperfil'].'"/>';
				}else{
					
					echo '<img src="'.URL.'/site/images/autor.png">';
				}
					 
		?>
       </div>
      
      <div class="form-group">
      		<input type="file" name="fotoperfil"/>
      </div>
        
  			<div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
		
		  <div class="form-group col-xs-12 col-md-4">   
             <label>Selecione Função</label>
                  <select name="id_funcao" class="form-control" />   
                    <option value="">Selecione uma função</option>
                        <?php 
                            $readclassificacao = read('funcionario_funcao',"id ORDER BY nome ASC");
                            if(!$readclassificacao){
                                echo '<option value="">Não temos função no momento</option>';	
                            }else{
                                foreach($readclassificacao as $mae):
                                   if($edit['id_funcao'] == $mae['id']){
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
                <label>Nome </label>
                <input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control"  />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-3"> 
                 <label>Telefone</label>
                 <input type="text" name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control"  />
         </div> 
   
      		<div class="form-group col-xs-12 col-md-3"> 
                 <label>RG</label>
                 <input name="rg" type="text" value="<?php echo $edit['rg'];?>" class="form-control"  />
           </div> 
   
      		<div class="form-group col-xs-12 col-md-3"> 
                 <label>CPF</label>
                 <input name="cpf" type="text" value="<?php echo $edit['cpf'];?>" class="form-control"  />
           </div> 
      	 	
      	 <div class="form-group col-xs-12 col-md-3"> 
                <label>Data de Nascimento</label>
              		<input name="data_nascimento" type="date" value="<?php echo $edit['data_nascimento'];?>" class="form-control" /> 
		</div><!-- /.col-md-12 -->
		
		  <div class="form-group col-xs-12 col-md-3"> 
                <label>Data de Admissão</label>
              		<input name="data_admissao" type="date" value="<?php echo $edit['data_admissao'];?>" class="form-control" /> 
		</div><!-- /.col-md-12 -->
		
	  <div class="form-group col-xs-12 col-md-3"> 
                <label>Vencimento Habilitação</label>
              		<input name="vencimento_habilitacao" type="date" value="<?php echo $edit['vencimento_habilitacao'];?>" class="form-control" /> 
		</div><!-- /.col-md-12 -->
			 
		 <div class="form-group col-xs-12 col-md-3"> 
                <label>Vencimento de Férias</label>
              		<input name="ferias" type="date" value="<?php echo $edit['ferias'];?>" class="form-control" /> 
		</div><!-- /.col-md-12 -->
			 
			 
		 <div class="form-group col-xs-12 col-md-3">   
             <label>Selecione Status</label>
                  <select name="status" class="form-control" />   
                    <option value="">Selecione um Status</option>
                        <?php 
                            $readclassificacao = read('funcionario_status',"id ORDER BY id ASC");
                            if(!$readclassificacao){
                                echo '<option value="">Não temos status no momento</option>';	
                            }else{
                                foreach($readclassificacao as $mae):
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
		
     <div class="form-group col-xs-12 col-md-12"> 
      	<div class="box-footer">
        <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-danger"> </a>
        <?php 
			if($acao=="atualizar"){
				echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
			}
			if($acao=="deletar"){
				echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-primary" />';	
			}
			if($acao=="cadastrar"){
				echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
			}
			if($acao=="enviar"){
				echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
			}
		 ?>  
    </div>
		 
		</div><!-- /.col-md-12 -->
  
   </form>

 </section>

<section class="content">
	
 	<div class="box box-warning">
     	 <div class="box-body">
			 
			 
             <div class="box-header">
			 <a href="painel.php?execute=suporte/funcionario/divergencia-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small> Divergencia  </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_divergencia',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
						<td align="center">Divergencia</td>
						<td align="center">Data</td>
						<td align="center">Solicitacao</td>
						<td align="center">Solução</td>
						<td align="center">Status</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
					  
                              echo '<td>'.$mostra['id'].'</td>';
	
								$divergenciaId = $mostra['id_divergencia'];
				
								$divergencia = mostra(' funcionario_advertencia_motivo',"WHERE id ='$divergenciaId'");
					  
								echo '<td>'.$divergencia['nome'].'</td>';

								echo '<td>'.converteData($mostra['data_solicitacao']).'</td>';

								echo '<td>'.substr($mostra['solicitacao'],0,25).'</td>';
					  			echo '<td>'.substr($mostra['solucao'],0,25).'</td>';

								echo '<td>'.$mostra['status'].'</td>';
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/divergencia-editar&divergenciaEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/divergencia-editarr&divergenciaDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/divergencia-editar&divergenciaBaixar='.$mostra['id'].'">
                                            <img src="../admin/ico/baixar.png" title="Baixar"  />
                                        </a>
                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
 	
		 </div>
      </div>

  	<div class="box box-warning">
     	 <div class="box-body">
			 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/suspensao-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small> Suspensões </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_suspensao',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
						<td align="center">Motivo</td>
						<td align="center">Data</td>
						<td align="center">Observação</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                          echo '<tr>';
                            echo '<td>'.$mostra['id'].'</td>';
								$motivoId = $mostra['id_motivo'];
							$motivo=mostra('funcionario_suspensao_motivo',"WHERE id ='$motivoId'");
								echo '<td>'.$motivo['nome'].'</td>';
								echo '<td>'.converteData($mostra['data']).'</td>';
								echo '<td>'.substr($mostra['observacao'],0,55).'</td>';

								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/suspensao-editar&suspensaoEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/suspensao-editar&suspensaoDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
							 
                           echo '</tr>';
                      endforeach;
                echo '</table>';
                }
               ?>   
 	
			 </div>
   	</div>
	
  	<div class="box box-warning">
     	 <div class="box-body">
			 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/advertencia-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small> Advertência </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_advertencia',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
						<td align="center">Motivo</td>
						<td align="center">Data</td>
						<td align="center">Observação</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                          echo '<tr>';
                            echo '<td>'.$mostra['id'].'</td>';
								$motivoId = $mostra['id_motivo'];
							$motivo=mostra('funcionario_advertencia_motivo',"WHERE id ='$motivoId'");
								echo '<td>'.$motivo['nome'].'</td>';
								echo '<td>'.converteData($mostra['data']).'</td>';
								echo '<td>'.substr($mostra['observacao'],0,55).'</td>';

								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/adivertencia-editar&adivertenciaEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/adivertencia-editar&adivertenciaDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
							 
                           echo '</tr>';
                      endforeach;
                echo '</table>';
                }
               ?>   
 	
			 </div>
      	</div>
	
	
		<div class="box box-warning">
     	 <div class="box-body">
			 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/acidente-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small>Acidente de trânsito </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_acidente',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
						<td align="center">Veículo</td>
						<td align="center">Tipo</td>
						<td align="center">Data</td>
						<td align="center">Local</td>
						<td align="center">Agente</td>
						<td align="center">Status</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
					  
                          echo '<tr>';
					  
                            	echo '<td>'.$mostra['id'].'</td>';
					  
								$veiculoId = $mostra['id_veiculo'];
								$veiculo = mostra('veiculo',"WHERE id ='$veiculoId '");
		
								echo '<td>'.$veiculo['placa'].'</td>';
		
								$tipoId = $mostra['tipo_acidente'];
								$tipo = mostra('funcionario_acidente_tipo',"WHERE id ='$tipoId'");
								echo '<td>'.$tipo['nome'].'</td>';
		
								echo '<td>'.converteData($mostra['data']).'</td>';
								echo '<td>'.substr($mostra['local'],0,55).'</td>';
					  			echo '<td>'.$mostra['status'].'</td>';
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/acidente-editar&acidenteEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/acidente-editarr&acidenteDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/acidente-editar&acidenteBaixar='.$mostra['id'].'">
                                            <img src="../admin/ico/baixar.png" title="Baixar"  />
                                        </a>
                                      </td>';  
                           echo '</tr>';
                      endforeach;
                echo '</table>';
                }
               ?>   
 	
			 </div>
      	</div>
	
	
		<div class="box box-warning">
     	 <div class="box-body">
			 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/multa-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small> Multas </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_multa',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
						
                        <td align="center">Id</td>
						<td align="center">Veículo</td>
						<td align="center">Infração</td>
						<td align="center">Data</td>
						<td align="center">Numero</td>
						<td align="center">Valor</td>
						<td align="center">Descontado</td>
						<td align="center">Data Desconto</td>
						
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
					  
                          echo '<tr>';
					  
                            	echo '<td>'.$mostra['id'].'</td>';
					  
								$veiculoId = $mostra['id_veiculo'];
								$veiculo = mostra('veiculo',"WHERE id ='$veiculoId '");
		
								echo '<td>'.$veiculo['placa'].'</td>';
		
								$tipoId = $mostra['id_multa'];
								$tipo = mostra('funcionario_multa_motivo',"WHERE id ='$tipoId'");
								echo '<td>'.$tipo['nome'].'</td>';
		
								echo '<td align="center">'.converteData($mostra['data']).'</td>';
								echo '<td align="center">'.$mostra['numero_infracao'].'</td>';
					  			echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
					  
					  			if($mostra['descontado']=='1'){
									echo '<td align="center">Sim</td>';
								}else{
									echo '<td align="center">Não</td>';
								}
					  			
					  			echo '<td align="center">'.converteData($mostra['descontado_data']).'</td>';
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/multa-editar&multaEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/multa-editar&multaDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
						 
                           echo '</tr>';
                      endforeach;
                echo '</table>';
                }
               ?>   
 	
			 </div>
      	</div>
	
	
	
	<div class="box box-warning">
     	 <div class="box-body">
			 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/diaria-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small> Diária </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_diaria',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
						
                        <td align="center">Id</td>
						<td align="center">Motivo</td>
						<td align="center">Rota</td>
						<td align="center">Hora</td>
						<td align="center">Data</td>
						<td align="center">Hora chegada</td>
						<td align="center">Liberação Entrada</td>
			
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
					  
                          echo '<tr>';
					  
                            	echo '<td>'.$mostra['id'].'</td>';
					  
								$rotad = $mostra['rota'];
								$rota = mostra('contrato_rota',"WHERE id ='$rotaId '");
		 
								$tipoId = $mostra['id_motivo'];
								$tipo = mostra('funcionario_diaria_motivo',"WHERE id ='$tipoId'");
								echo '<td>'.$tipo['nome'].'</td>';
					  
					  			echo '<td>'.$rota['nome'].'</td>';
					  
					  			echo '<td>'.$mostra['rota_hora'].'</td>';
		
								echo '<td align="center">'.converteData($mostra['data']).'</td>';
					  
					  			echo '<td>'.$mostra['hora_chegada'].'</td>';
					  			echo '<td>'.$mostra['liberacao_entrada'].'</td>';
								 
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/diaria-editar&diariaEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/diaria-editar&diariaDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
						 
                           echo '</tr>';
                      endforeach;
                echo '</table>';
                }
               ?>   
 	
			 </div>
      	</div>
	
		<div class="box box-warning">
     	 <div class="box-body">
			 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/acidente-trabalho-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small>  Acidente de trabalho </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_acidente_trabalho',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
						
                        <td align="center">Id</td>
						<td align="center">Tipo de acidente </td>
						<td align="center">Data</td>
						<td align="center">Local da lesão</td>
						<td align="center">Agente causador</td>
						<td align="center">Status </td>
						<td align="center">Dias afastado</td>
			
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
					  
                          echo '<tr>';
					  
					  			echo '<td>'.$mostra['id'].'</td>';
					  
                            	if($mostra['tipo_acidente']=='1'){
									echo '<td align="center">Condição Insegura</td>';
								}else if($mostra['tipo_acidente']=='2'){
									echo '<td align="center">Ato Inseguro</td>';
								}else{
									echo '<td align="center">-</td>';
								}
		 
		
								echo '<td align="center">'.converteData($mostra['data']).'</td>';
					  
					  			echo '<td>'.$mostra['local_lesao'].'</td>';
					  			echo '<td>'.$mostra['agente_causador'].'</td>';
					  
					  			if($mostra['status']=='1'){
									echo '<td align="center">Com afastamento</td>';
								}else if($mostra['status']=='2'){
									echo '<td align="center">Sem afastamento</td>';
								}else{
									echo '<td align="center">-</td>';
								}
					  			echo '<td>'.$mostra['dias_afastados'].'</td>';
				 
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/acidente-trabalho-editar&acidenteEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/acidente-trabalho-editar&acidenteDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
						 
                           echo '</tr>';
                      endforeach;
                echo '</table>';
                }
               ?>   
 	
			 </div>
      	</div>
	
	
	<div class="box box-warning">
     	 <div class="box-body">

             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/acidente-trabalho-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small>  Acidente de trabalho </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('funcionario_acidente_trabalho',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
						
                        <td align="center">Id</td>
						<td align="center">Tipo de acidente </td>
						<td align="center">Data</td>
						<td align="center">Local da lesão</td>
						<td align="center">Agente causador</td>
						<td align="center">Status </td>
						<td align="center">Dias afastado</td>
			
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
					  
                          echo '<tr>';
					  
					  			echo '<td>'.$mostra['id'].'</td>';
					  
                            	if($mostra['tipo_acidente']=='1'){
									echo '<td align="center">Condição Insegura</td>';
								}else if($mostra['tipo_acidente']=='2'){
									echo '<td align="center">Ato Inseguro</td>';
								}else{
									echo '<td align="center">-</td>';
								}
		 
		
								echo '<td align="center">'.converteData($mostra['data']).'</td>';
					  
					  			echo '<td>'.$mostra['local_lesao'].'</td>';
					  			echo '<td>'.$mostra['agente_causador'].'</td>';
					  
					  			if($mostra['status']=='1'){
									echo '<td align="center">Com afastamento</td>';
								}else if($mostra['status']=='2'){
									echo '<td align="center">Sem afastamento</td>';
								}else{
									echo '<td align="center">-</td>';
								}
					  			echo '<td>'.$mostra['dias_afastados'].'</td>';
				 
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/acidente-trabalho-editar&acidenteEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/acidente-trabalho-editar&acidenteDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
						 
                           echo '</tr>';
                      endforeach;
                echo '</table>';
                }
               ?>   
 	
			 </div>
     </div>
	 
	<div class="box box-warning">
     	 <div class="box-body">
	 		 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/desconto-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small>  Descontos </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		 $leitura = read('funcionario_desconto',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
						<td align="center">Divergencia</td>
						<td align="center">Data</td>
						<td align="center">Solicitacao</td>
						<td align="center">Solução</td>
						<td align="center">Status</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
					  
                              echo '<td>'.$mostra['id'].'</td>';
	
								$divergenciaId = $mostra['id_desconto'];
				
								$divergencia = mostra(' funcionario_desconto_motivo',"WHERE id ='$divergenciaId'");
					  
								echo '<td>'.$divergencia['nome'].'</td>';

								echo '<td>'.converteData($mostra['data_solicitacao']).'</td>';

								echo '<td>'.substr($mostra['solicitacao'],0,25).'</td>';
					  			echo '<td>'.substr($mostra['solucao'],0,25).'</td>';

								echo '<td>'.$mostra['status'].'</td>';
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/desconto-editar&descontoEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/desconto-editarr&descontoDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/desconto-editar&descontoBaixar='.$mostra['id'].'">
                                            <img src="../admin/ico/baixar.png" title="Baixar"  />
                                        </a>
                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
 	
		 </div>
      </div>
	
	
	<div class="box box-warning">
     	 <div class="box-body">
	 		 
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/funcionario/epi-editar&funcionarioId=<?PHP echo $funcionarioId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" title="Criar Novo" />
				 </a>	
				 <small>  EPI </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		 $leitura = read('funcionario_epi',"WHERE id AND id_funcionario = '$funcionarioId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
						
						<td align="center">Tipo</td>
						<td align="center">Motivo</td>
						
						<td align="center">Data</td>
						<td align="center">Quantidade</td>
						
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
					  
                              echo '<td>'.$mostra['id'].'</td>';
	
								$epiId = $mostra['epi'];
				
								$epi = mostra(' funcionario_epi_tipo',"WHERE id ='$epiId'");
					  
								echo '<td>'.$epi['nome'].'</td>';
					  
					  			if($mostra['motivo']=='1'){
									echo '<td align="center">Novo</td>';
								}else if($mostra['motivo']=='2'){
									echo '<td align="center">Troca</td>';
								}else{
									echo '<td align="center">-</td>';
								}

								echo '<td>'.converteData($mostra['data']).'</td>';
 								echo '<td>'.$mostra['quantiade'].'</td>';
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/desconto-editar&descontoEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png"  title="Editar"  />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/desconto-editarr&descontoDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png"  title="Deletar"/>
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/funcionario/desconto-editar&descontoBaixar='.$mostra['id'].'">
                                            <img src="../admin/ico/baixar.png" title="Baixar"  />
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

</div><!-- /.content-wrapper -->
 