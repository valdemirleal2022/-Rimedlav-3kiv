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
		$cad['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
		$cad['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$cad['complemento']=strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
		$cad['bairro']=strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
		$cad['cep']=strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
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

		if(empty($cad['nome'])){
				echo '<div class="alert alert-warning">O Nome do visita é obrigatório!</div>'.'<br>';
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
						echo 'if ( confirm("Confirma ExclusÄƒo de Registro?") ) {';
							echo 'return true;';
						echo '}';
							echo 'return false;';
						echo '}';
				    echo '</script>';
            
			$readDeleta = read('cadastro_visita',"WHERE id = '$visitaId'");
			if(!$readDeleta){
				echo '<div class="alert alert-warning">Desculpe, o registro não existe</div><br />';	
			 }else{
				delete('cadastro_visita',"id = '$visitaId'");
				header("Location: ".$_SESSION['url']);
			}
		}
			
	if(isset($_POST['cadastrar'])){
		$cad['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		$cad['nome_fantasia']=strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
		$cad['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
		$cad['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$cad['complemento']=strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
		$cad['bairro']=strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
		$cad['cep']=strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
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
			
			$cad['consultor'] = $consultorId;
			$cad['atendente'] = '31';
			$cad['indicacao'] = '16';
			
			$cad['orc_solicitacao']= date('Y/m/d H:i:s');
			$cad['data']= date('Y/m/d');
			$cad['status']= '0';
			
			if(empty($cad['nome'])){
				echo '<div class="alert alert-warning">O Nome da visita é obrigatório!</div>'.'<br>';
			}else{
				$cad['interacao']= date('Y/m/d H:i:s');
				create('cadastro_visita',$cad);	
				unset($cad);
				header("Location: ".$_SESSION['url']);
			}
			
		};
			
		if(isset($_POST['proposta'])){
			$edit['status'] = '2';
			$edit['orc_data']= date('Y/m/d');
			$edit['interacao']= date('Y/m/d H:i:s');
 			update('cadastro_visita',$edit,"id = '$visitaId'");
			header("Location: ".$_SESSION['url']);
		}
			
		if(isset($_POST['cancelar'])){
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = 18;
			$cad['motivo_cancelamento']= strip_tags(trim(mysql_real_escape_string($_POST['motivo_cancelamento'])));
			if(empty($cad['motivo_cancelamento'])){
				echo '<div class="alert alert-warning">Motivo Cancelamento é obrigatório!</div>'.'<br>';
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
          
           <div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
            <div class="form-group col-xs-12 col-md-3">  
               	<label>Interação :</label>
   				<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div>
           
          
            
             <div class="form-group col-xs-12 col-md-2">
               <label>Data do Cadastro </label>
               <input name="data" type="text" value="<?php echo date('d/m/Y',strtotime($edit['data']));?>" class="form-control" readonly/>
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
          
           <div class="form-group col-xs-12 col-md-12">
			<label>Observação</label>
				<textarea name="orc_observacao" rows="3" cols="80" class="form-control"/><?php echo htmlspecialchars($edit['orc_observacao']);?>
			</textarea>
		 </div>

         </div>  <!-- /.row -->
        </div>  <!-- /.box-body -->
            
            
        <div class="box-header with-border">
			 <h3 class="box-title">Dados do Orçamento</h3>
		</div>
        <!-- /.box-header -->

          	 <div class="box-body">
                  <div class="row">

                            <div class="form-group col-xs-12 col-md-12">
                                <label>Tipo de Resíduo</label>
                                <input type="text" name="orc_residuo" value="<?php echo $edit['orc_residuo'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Freqüencia da Coleta</label>
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
                                <label>Quantidade Mínima Diária </label>
                                <input type="text" name="orc_quantidade" value="<?php echo $edit['orc_quantidade'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Valor Unitário R$ </label>
                                <input type="text" name="orc_valor_unitario" value="<?php echo converteValor($edit['orc_valor_unitario']) ;?>" class="form-control"/>
                            </div>

							 <div class="form-group col-xs-12 col-md-3">
								  <label>Valor Extra Unitário R$  </label>
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
                        echo '<input type="submit" name="proposta" value="Gerar Orçamento" class="btn btn-primary" />';	
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
             <small> Negociação  </small>
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
           