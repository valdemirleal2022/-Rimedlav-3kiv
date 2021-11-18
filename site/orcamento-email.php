<div class="paginas">

		<div class="tituloSingle">Orçamento de Dedetização </div>
            
            <div class="formulario">
            
				<?php

                    if(isset($_POST['enviar'])){
						$cli['tipo'] =1;
						$cli['classificacao'] =1;
						$ser['indicacao'] =11;
						$ser['consultor'] =1;
						$ser['atendente'] =1;
						$cli['senha'] ='123456';
                        $cli['nome'] = mysql_real_escape_string($_POST['nome']);	
                        $cli['email'] = mysql_real_escape_string($_POST['email']);	
                        $cli['telefone'] = mysql_real_escape_string($_POST['telefone']);
						$cli['celular'] = mysql_real_escape_string($_POST['celular']);
						$cli['cep'] = mysql_real_escape_string($_POST['cep']);
                        $cli['endereco'] = mysql_real_escape_string($_POST['endereco']);
						$cli['numero'] = mysql_real_escape_string($_POST['numero']);
                        $cli['bairro'] = mysql_real_escape_string($_POST['bairro']);
                        $cli['cidade'] = mysql_real_escape_string($_POST['cidade']);
						$cli['referencia'] = mysql_real_escape_string($_POST['referencia']);
                        $ser['orc_descricao'] = htmlspecialchars(stripslashes($_POST['orc_descricao']));
						$ser['orc_area'] = mysql_real_escape_string($_POST['orc_area']);
						$ser['orc_inseto'] = mysql_real_escape_string($_POST['orc_inseto']);
						$ser['orc_solicitacao']= date('Y/m/d H:i:s');
						$cli['data']= date('Y/m/d H:i:s');
                        $assunto  = "Enviado pelo Site - Email-Marketing";
						if(in_array('',$cli)){
							echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
						  }else{
							if(!email($cli['email'])){
                               echo '<div class="no">Desculpe, seu e-mail não tem um formato válido!</div>';	
                             }else{
								 $emailCadastrado = read('cliente',"WHERE email = '$cli[email]'");	
                              	 if($emailCadastrado){
                                        foreach($emailCadastrado as $mostraCliente);
										  $ser['id_cliente'] =  $mostraCliente['id'];	
                                       }else{
										 $cli['contato'] = mysql_real_escape_string($_POST['contato']);	
										 $cli['complemento'] = mysql_real_escape_string($_POST['complemento']);
										 $cli['uf'] = 'RJ';
										 create('cliente',$cli);	
										 $ser['id_cliente'] = mysql_insert_id();
								    }
									$ser['interacao']= date('Y/m/d H:i:s');
									$ser['orc_data']= date('Y/m/d H:i:s');
									$ser['status'] =1;
									$ser['tipo'] =1;
									create('servico',$ser);
									
									$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#08a57f'>";
									$msg .="<img src='http://www.toyamadedetizadora.com.br/site/images/header-logo.png'> <br /><br />";
									$msg .= "Orçamento Solicitado <br /><br />";
	                                $msg .= "Nome : " . $cli['nome'] . "<br />";
                                    $msg .= "Email : " . $cli['email'] . "<br />";
                                    $msg .= "Telefone : " . $cli['telefone'] . "<br />";
									$msg .= "Celular : " . $cli['celular'] . "<br />";
                                    $msg .= "Endereço : " . $cli['endereco'] .' '. $cli['numero'] .' '. $cli['complemento'] .  "<br />";
                                    $msg .= "Bairro : " . $cli['bairro'] .' '. $cli['cidade'] . "<br />";
                                    $msg .= "Inseto : " . $ser['orc_inseto'] . "<br />";
									$msg .= "Area : " . $ser['orc_area'] . "<br />";
									$msg .= "Descrição : " . $ser['orc_descricao'] . "<br />";
                                    $msg .= "Data : " . date('d/m/Y H:i') . "<br /><br />";
									$msg .= "Já recebemos sua solicitação e estamos analisando seu orçamento, respondemos em 30 minutos em horário comercial. <br /><br />";
                                    $msg .=  "</font>";
							
									// $assunto,$mensagem,$remetente,$nomeremetente,$destino,$nomedestino, $reply = NULL, $replyname = NULL
								    enviaEmail($assunto,$msg,$cli['email'],$cli['nome'],MAILUSER,SITENOME);
									enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cli['email'],$cli['nome']);
									
                                    $_SESSION['retorna'] = '<div class="ok">Sua mensagem foi enviada com sucesso!</div>';	
                                    header('Location: '.URL.'/orcamento-dedetizacao');
                                }
						    }
                            }elseif(!empty($_SESSION['retorna'])){
                                echo $_SESSION['retorna'];
                                unset($_SESSION['retorna']);
                                $_SESSION['retorna']="";
                        }
                    ?>
            
                <form name="EnviaContato" method="post" action="" enctype="multipart/form-data">
                
                    <fieldset>
                    	<h2>Identificação :</h2>
                        <label>
                        <span>Nome:</span>
                        <input name="nome" type="text"  autofocus value="<?php echo $cli['nome'];?>" size="60" />
                        </label>
                        <label>
                        <span>E-mail:</span>
                        <input name="email" type="email" value="<?php echo $cli['email'];?>" size="60"   />
                        </label>
                        <h2>Telefones</h2>
                        <label>
                        <span>Celular :</span>
                         <input name="celular" type="text" placeholder="(xx) 9999-9999" value="<?php echo $cli['celular'];?>"  size="20" maxlength="20" />
                         <span>Fixo :</span>
                         <input name="telefone" type="text" placeholder="(xx) 9999-9999"  value="<?php echo $cli['telefone'];?>" size="20" maxlength="20" />
                         </label>
                        <label>
                        <span>Contato :</span>
                        <input type="text" name="contato" value="<?php echo $cli['contato'];?>" size="20" maxlength="20" /> <p>* caso empresa forneça o nome do contato</p>
                        <h2>Endereço</h2>
                        <label>
                        <span>Cep:</span>
                        <input name="cep" type="text" id="cep" placeholder="99999-999" onblur="consultacep(this.value)" value="<?php if($cli['cep']) echo $cli['cep'];?>" size="10" />
                        <input type="button" autofocus value="Pesquisar" class="pesquisar">
                        <p> * digite o CEP para pesquisar sua rua</p>
                        </label>

                        <label>
                        <span>Endereço:</span>
                        <input name="endereco" type="text" id="endereco" value="<?php echo $cli['endereco'];?>" size="60" />
                        </label>
                         <label>
                        <span>Número:</span>
                        <input type="text" name="numero" id="numero" value="<?php echo $cli['numero'];?>" size="5" />
                        <span>Complemento:</span>
                        <input type="text" name="complemento" id="complemento" value="<?php echo $cli['complemento'];?>"  size="10" />
                        </label>
                        
                        <label>
                        <span>Bairro:</span>
                        <input name="bairro" type="text" id="bairro" value="<?php echo $cli['bairro'];?>" size="20" />
                        <span>Cidade:</span>
                        <input name="cidade" type="text" id="cidade" value="<?php echo $cli['cidade'];?>"  size="20" />
                        </label>
                        <label>
                        <span>Referência :</span>
                        <input name="referencia" value="<?php echo $cli['referencia'];?>" size="60" /> 
                    	</label>  
                        <h2>Dados dos Orçamento</h2>
                        <label>
                        <span>Inseto :</span>
                        <input name="orc_inseto" value="<?php echo $ser['orc_inseto'];?>" size="60" /> 
                    	</label>
                          
                        <label>
                        <span>Descreva a área  :</span>                 
                        <input name="orc_area" value="<?php echo $ser['orc_area'];?>" size="60" />
                         <p>* apto/casa (quartos), condom&iacute;nio (andares) ou empresa (m<sup>2</sup>) </p>
                    	</label> 
                         
                        <label>
                        <span>Descrição do Serviço: (forneça detalhes do problema) </span>
                        <label>
                        <textarea name="orc_descricao" cols="70" rows="4"> <?php if($ser['orc_descricao']) echo $ser['orc_descricao'];?></textarea>
                       	
                         <p> * Todos os campos são obrigatórios</p>
                       
                        </label>
                    </fieldset>
                    <input type="submit" name="enviar" value="Enviar Orçamento" class="enviar" />
    		  </form>
              
              </div><!--/formulário-->
            
        </div><!--/páginas-->
        
<?php require("inc/sidebar-pg.php");?>



