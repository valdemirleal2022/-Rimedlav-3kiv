<div class="content container">
  <div class="page-wrapper">
        <header class="page-heading clearfix">
        <h1 class="heading-title pull-left">Clientes</h1>
         <div class="breadcrumbs pull-right">
              <ul class="breadcrumbs-list">
                <li><a href="<?php setaurl();?>">Home</a><i class="fa fa-angle-right"></i></li>
                <li><a href="<?php echo URL.'/fale-conosco/'?>">Contato</a><i class="fa fa-angle-right"></i></li>
                <li class="current"><?php echo $not['titulo'];?></li>
              </ul>
            </div><!--//breadcrumbs-->
         </header> 
                
         <div class="page-content">
                
           <div class="row page-row">
                    
               <div class="course-wrapper col-md-8 col-sm-7"> 
            
            <?php
				if(isset($_POST['enviar'])){
					$con['nome'] = mysql_real_escape_string($_POST['nome']);	
					$con['email'] = mysql_real_escape_string($_POST['email']);	
					$con['telefone'] = mysql_real_escape_string($_POST['telefone']);	
					$con['solicitacao'] =  htmlspecialchars(stripslashes($_POST['solicitacao']));
					$con['status'] = 'Em aberto';
					$con['data'] = date('Y/m/d');
					$con['interacao']= date('Y/m/d H:i:s');
					$assunto  = "Enviado pelo Site - Contato";
					if(in_array('',$con)){
							echo '<div class="alert alert-info">Desculpe, todos os campos do formulário são obrigatórios!</div>';	
						}elseif(!email($con['email'])){
							echo '<div class="alert alert-info">Desculpe, seu e-mail não tem um formato válido!</div>';	
							}else{
						//		create('contato',$con);
//								$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
//								$msg .= "Nome : " . $con['nome'] . "<br />";
//								$msg .= "Email : " . $con['email'] . "<br />";
//								$msg .= "Telefone : " . $con['telefone'] . "<br />";
//								$msg .= "Mensagem : " .  stripslashes($con['solicitacao']). "<br />";
//								$msg .= "Data : " . date('d/m/Y H:i') . "<br />";
//								$msg .=  "</font>";
//					enviaEmail($assunto,$msg,$con['email'],$con['nome'],MAILUSER,SITENOME);
//					$_SESSION['retorna'] = '<div class="alert alert-info">Sua mensagem foi enviada com sucesso!</div>';	
//					header('Location: '.URL.'/fale-conosco');
				//echo "Email => ". MAILUSER ."<br>";
				//echo "Senha => ". MAILPASS ."<br>";
				//echo "Porta => ". MAILPORT ."<br>";
				//$mailReturn = enviaEmail($assunto,$msg,$con['email'],$con['nome'],MAILUSER,SITENOME);
				//echo "O mail foi enviado? => "; var_dump($mailReturn);
	
									}
						}elseif(!empty($_SESSION['retorna'])){
							echo $_SESSION['retorna'];
							unset($_SESSION['retorna']);
							$_SESSION['retorna']="";
					
					}
			?>
            
            <form name="EnviaContato" method="post" action="" enctype="multipart/form-data">
                   <div class="form-group name">
                       <label for="name">Name</label>
                       <input name="nome" type="text" class="form-control" placeholder="Nome">
                   </div><!--//form-group-->
                   <div class="form-group email">
                        <label for="email">Email<span class="required">*</span></label>
                        <input name="email" type="email" class="form-control" placeholder="email">
                    </div><!--//form-group-->
                    <div class="form-group phone">
                          <label for="phone">Telefone</label>
                          <input name="telefone" type="tel" class="form-control" placeholder="telefone">
                    </div><!--//form-group-->
                    <div class="form-group message">
                          <label for="message">Mensagem<span class="required">*</span></label>
                          <textarea name="solicitacao" class="form-control" rows="6" placeholder="sua mensagem">
                          </textarea>
                     </div><!--//form-group-->
                           <input type="submit" name="enviar" value="Enviar" class="btn btn-theme" />
               </form>    
                            
            <div class="page-row">
            
            <h1 class="heading-title pull-left">Como Chegar</h1>
            <?php
				$empresa = mostra('empresa');
                $address = $empresa['endereco'].$empresa['cidade'].', '.$empresa['cep'];
              ?>
              <iframe width="750" height="310" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
                      </iframe>
             </div><!--//page-row-->   
             
         			</div><!--//course-wrapper col-md-8 col-sm-7-->
               
                   
                 <?php require("site/inc/sidebar-tab.php");?>
                 
                  
               </div><!--//row page-row-->
           </div><!--//page-content-->
         </div><!--//page-wrapper--> 
 </div><!--//content container-->