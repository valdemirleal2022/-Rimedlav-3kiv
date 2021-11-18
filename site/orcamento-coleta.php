<div class="content container">
  <div class="page-wrapper">
        <header class="page-heading clearfix">
        <h1 class="heading-title pull-left">Orçamento de Coleta de Resíduos - RJ</h1>
         <div class="breadcrumbs pull-right">
              <ul class="breadcrumbs-list">
                <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                <li><a href="<?php echo URL.'/orcamento-coleta/'?>">Orçamento</a><i class="fa fa-angle-right"></i></li>
                <li class="current"><?php echo $not['titulo'];?></li>
              </ul>
            </div><!--//breadcrumbs-->
         </header> 
                
         <div class="page-content">
                
           <div class="row page-row">
                    
               <div class="course-wrapper col-md-8 col-sm-7"> 
            
				<?php

                    if(isset($_POST['orcamento'])){
						$cli['indicacao'] =1;
						$cli['atendente'] =1;
						$cli['status'] =1;
                        $cli['nome'] = mysql_real_escape_string($_POST['nome']);	
                        $cli['email'] = mysql_real_escape_string($_POST['email']);	
                        $cli['telefone'] = mysql_real_escape_string($_POST['telefone']);
						$cli['celular'] = mysql_real_escape_string($_POST['celular']);
						$cli['contato'] = mysql_real_escape_string($_POST['contato']);	
						$cli['complemento'] = mysql_real_escape_string($_POST['complemento']);
						$cli['uf'] = 'RJ';
						$cli['cep'] = mysql_real_escape_string($_POST['cep']);
                        $cli['endereco'] = mysql_real_escape_string($_POST['endereco']);
						$cli['numero'] = mysql_real_escape_string($_POST['numero']);
                        $cli['bairro'] = mysql_real_escape_string($_POST['bairro']);
                        $cli['cidade'] = mysql_real_escape_string($_POST['cidade']);
						$cli['referencia'] = mysql_real_escape_string($_POST['referencia']);
						$cli['orc_observacao'] = mysql_real_escape_string($_POST['orc_observacao']);
						$cli['orc_solicitacao']= date('Y/m/d H:i:s');
						$cli['data']= date('Y/m/d H:i:s');
                        $assunto  = "Enviado pelo Site - Orçamento";
                        
						if(empty($cli['nome'])){
							echo '<div class="alert alert-warning">O Nome é obrigatório!</div>'.'<br>';
						 }elseif(empty($cli['endereco'])){
							echo '<div class="alert alert-warning">O Endereço é obrigatório!</div>'.'<br>';
						 }elseif(empty($cli['telefone'])){
							echo '<div class="alert alert-warning">O telefone é obrigatório!</div>'.'<br>';
							
						  }else{
							
							create('cadastro_visita',$cli);	
 							$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#08a57f'>";
							$msg .="<img src='http://www.cleanambienteal.com.br/wpc/site/images/header-logo.png'> <br /><br />";
							$msg .= "Obrigado pelo seu contato, o orçamento foi solicitado com sucesso. <br />";
							$msg .= "Nossa equipe está analisando seu pedido e muito em breve você receberá nosso contato.<br /><br />";
							
							$msg .= "Orçamento Solicitado <br /><br />";
	                        $msg .= "Nome : " . $cli['nome'] . "<br />";
                            $msg .= "Email : " . $cli['email'] . "<br />";
                            $msg .= "Telefone : " . $cli['telefone'] . "<br />";
							$msg .= "Celular : " . $cli['celular'] . "<br />";
                            $msg .= "Endereço : ".$cli['endereco'].' '.$cli['numero'].' '.$cli['complemento']."<br />";
                            $msg .= "Bairro : " . $cli['bairro'] .' '. $cli['cidade'] . "<br />";
							$msg .= "Observaçao  : " . $cli['orc_observacao'] . "<br />";
						
                            $msg .=  "</font>";
							
							enviaEmail($assunto,$msg,$cli['email'],$cli['nome'],MAILUSER,SITENOME);
							enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cli['email'],$cli['nome']);
									
                             $_SESSION['retorna'] = '<div class="alert alert-info">Sua mensagem foi enviada com sucesso!</div>';	
			                 header('Location: '.URL.'/orcamento-coleta');
                           }
			             }elseif(!empty($_SESSION['retorna'])){
                           echo $_SESSION['retorna'];
                           unset($_SESSION['retorna']);
                           $_SESSION['retorna']="";
                        }
                    ?>
            
                  <form name="EnviaContato" method="post" action="" enctype="multipart/form-data">
                                  
                        <div class="form-group col-xs-12 col-md-6">
                           <label>Nome<span class="color-red">*</span></label>
                           <input name="nome" type="text"  value="<?php echo $cli['nome'];?>" 
                        	class="form-control"/>
                        </div>
                        
                       <div class="form-group col-xs-12 col-md-6">
                           <label>E-mail<span class="color-red"></span></label> 
                           <input name="email" type="email" value="<?php echo $cli['email'];?>" 
                            class="form-control"  />
                       </div>
                       
                      <div class="form-group col-xs-12 col-md-4">
                           <label>Fixo<span class="color-red">*</span></label>   
                           <input name="telefone" value="<?php echo $cli['telefone'];?>" 
                            class="form-control"/>
                    	 </div>
                         
                       <div class="form-group col-xs-12 col-md-4">
                           <label>Celular<span class="color-red"></span></label>                
                           <input name="celular" value="<?php echo $cli['celular'];?>" 
                          		class="form-control"/>
                    	</div>
                        
                       <div class="form-group col-xs-12 col-md-4">
                             <label>Contato<span class="color-red"></span></label>
                             <input type="text" name="contato" value="<?php echo $cli['contato'];?>"
                                 class="form-control" placeholder="Nome do contato"/>
                         </div>
                         
                       <div class="form-group col-xs-12 col-md-2">
                           <label>Cep<span class="color-red"></span></label>   
                           <input name="cep" id="cep" value="<?php echo $cli['cep'];?>" 
                                 class="form-control"/>
                    	 </div>
                         
                        <div class="form-group col-xs-12 col-md-6">
                            <label>Endereço<span class="color-red">*</span></label>
                            <input name="endereco" type="text" id="endereco" value="<?php echo $cli['endereco'];?>"
                        		 class="form-control"/>
                         </div>
                         
                         <div class="form-group col-xs-12 col-md-2">
                            <label>Número<span class="color-red">*</span></label>   
                            <input name="numero" value="<?php echo $cli['numero'];?>" 
                                 class="form-control" placeholder="Numero"/>
                    	 </div>
                         
                         <div class="form-group col-xs-12 col-md-2">
                           <label>Complemento<span class="color-red">*</span></label>                
                           <input name="complemento" value="<?php echo $cli['complemento'];?>" 
                          		 class="form-control"/>
                    	</div>
         
                         <div class="form-group col-xs-12 col-md-6">
                           <label>Bairro<span class="color-red"></span></label>   
                           <input name="bairro" id="bairro" value="<?php echo $cli['bairro'];?>" 
                                  class="form-control"/>
                    	 </div>
                         
                         <div class="form-group col-xs-12 col-md-6">
                           <label>Cidade<span class="color-red"></span></label>                
                           <input name="cidade" id="cidade" value="<?php echo $cli['cidade'];?>" 
                          		  class="form-control"/>
                    	</div>
                        
                                 
                         <div class="form-group col-xs-12 col-md-12">
                           <label>Referência<span class="color-red"></span></label>   
                           <input name="referencia" value="<?php echo $cli['referencia'];?>" 
                               class="form-control"/> 
                    	 </div>
                         
                        <div class="form-group col-xs-12 col-md-12">
                          	<label>Descrição do Serviço: (forneça detalhes da coleta)<span class="color-red"></span></label>
                                <textarea name="orc_observacao" class="form-control" rows="3">
								<?php if($cli['orc_observacao']) echo $cli['orc_observacao'];?>
                                </textarea>
 
                          </div>
                          
                           <div class="form-group col-xs-12 col-md-12">
                         		 <input type="submit" name="orcamento" value="Enviar Orçamento" class="btn btn-theme" />
                           </div>
    		  </form>
           
           </div><!--//course-wrapper col-md-8 col-sm-7-->
      
              <?php require("site/inc/sidebar-pg.php");?>
                 
                  
               </div><!--//row page-row-->
           </div><!--//page-content-->
         </div><!--//page-wrapper--> 
 </div><!--//content container-->