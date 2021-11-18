<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		///* Informa o nível dos erros que serão exibidos */
//		error_reporting(E_ALL);
//
//		/* Habilita a exibição de erros */
//		ini_set("display_errors", 1);
 
		if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
			echo '<script type="text/javascript">';
			echo 'window.open("painel.php?execute=suporte/relatorio/cotacao-compras-pdf");';
			echo '</script>';
		}


		if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
			header( 'Location: ../admin/suporte/relatorio/cotacao-compras.php' );
		}


		if(!empty($_GET['cotacaoEditar'])){
			$cotacaoId = $_GET['cotacaoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['cotacaoDeletar'])){
			$cotacaoId = $_GET['cotacaoDeletar'];
			$acao = "deletar";
		}

		if(!empty($_GET['cotacaoAutorizado'])){
			$cotacaoId = $_GET['cotacaoAutorizado'];
			$acao = "autorizado";
			$edit['comprado'] = "checked='CHECKED'";
		}

		if(!empty($cotacaoId)){
			$readcompra= read('estoque_compras',"WHERE id = '$cotacaoId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $edit);

			$_SESSION[ 'cotacaoId' ] = $cotacaoId;
			
			$tipo=$edit['id_material'];
			 
			if ($edit['fornecedor1_melhor'] == "1") {
				$edit['fornecedor1_melhor'] = "checked='CHECKED'";
			  } else {
				$edit['fornecedor1_melhor'] = "";
			 }
			
			 if ($edit['fornecedor2_melhor'] == "1") {
				$edit['fornecedor2_melhor'] = "checked='CHECKED'";
			  } else {
				$edit['fornecedor2_melhor'] = "";
			 }
			
			 if ($edit['fornecedor3_melhor'] == "1") {
				$edit['fornecedor3_melhor'] = "checked='CHECKED'";
			  } else {
				$edit['fornecedor3_melhor'] = "";
			 }

		}
  
	  $valorCotacao = soma('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId'",'valor1');
 
?>

 
<div class="content-wrapper">
	
  <section class="content-header">
          <h1>Cotação</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Compras</a></li>
            <li><a href="#">Cotação</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
	
  <section class="content">
	  
      <div class="box box-default">
		  
            <div class="box-header with-border">
            
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

		$cad['data_cotacao'] = strip_tags(trim(mysql_real_escape_string($_POST['data_cotacao'])));
		$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['interacao']= date('Y/m/d H:i:s');
		
		if(in_array('',$cad)){
			
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			
		 }else{
			$cad['fornecedor1'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1'])));
			$cad['fornecedor2'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2'])));
			$cad['fornecedor3'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3'])));
			$cad['fornecedor1_desconto'] = mysql_real_escape_string($_POST['fornecedor1_desconto']);
			$cad['fornecedor2_desconto'] = mysql_real_escape_string($_POST['fornecedor2_desconto']);
			$cad['fornecedor3_desconto'] = mysql_real_escape_string($_POST['fornecedor3_desconto']);
			$cad['fornecedor1_pag'] = mysql_real_escape_string($_POST['fornecedor1_pag']);
			$cad['fornecedor2_pag'] = mysql_real_escape_string($_POST['fornecedor2_pag']);
			$cad['fornecedor3_pag'] = mysql_real_escape_string($_POST['fornecedor3_pag']);
			$cad['fornecedor1_prazo'] = mysql_real_escape_string($_POST['fornecedor1_prazo']);
			$cad['fornecedor2_prazo'] = mysql_real_escape_string($_POST['fornecedor2_prazo']);
			$cad['fornecedor3_prazo'] = mysql_real_escape_string($_POST['fornecedor3_prazo']);
			
			$cad['fornecedor1_melhor'] = mysql_real_escape_string($_POST['fornecedor1_melhor']);
			$cad['fornecedor2_melhor'] = mysql_real_escape_string($_POST['fornecedor2_melhor']);
			$cad['fornecedor3_melhor'] = mysql_real_escape_string($_POST['fornecedor3_melhor']);
		 
			$cad['nota_fiscal'] = mysql_real_escape_string($_POST['nota_fiscal']);
			$cad['data_compra'] = mysql_real_escape_string($_POST['data_compra']);
			$cad['data_recebimento'] = mysql_real_escape_string($_POST['data_recebimento']);
		 
 
			update('estoque_compras',$cad,"id = '$cotacaoId'");	
			header("Location: ".$_SESSION['url']);
		 
		}
	}
 	 
	if(isset($_POST['autorizar'])){

		$cad2['data_autorizacao'] = date('Y/m/d');
		$cad2['responsavel_autorizacao'] = $_SESSION['autUser']['id'];
		
		$cad2['compra_autorizacao'] = mysql_real_escape_string($_POST['compra_autorizacao']);
		
		if($cad2['compra_autorizacao']==1){
			$cad2['status'] = '4';
		}else if($cad2['compra_autorizacao']==0){
			$cad2['status'] = '2';
		}
	 
		if(in_array('',$cad2)){
			
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			
		 }else{
		  
			update('estoque_compras',$cad2,"id = '$cotacaoId'");	
			header("Location: ".$_SESSION['url']);
		 
		}
												 
	}
			 
	if(isset($_POST['recebido'])){

		$cad2['data_recebimento'] = mysql_real_escape_string($_POST['data_recebimento']);
		$cad2['nota_fiscal'] = mysql_real_escape_string($_POST['nota_fiscal']);
	 	$cad2['recebido'] = 1;
		$cad2['status'] = 'Recebido';
		
		if(in_array('',$cad2)){
			print_r($cad2);
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!!!</div>';
			
		 }else{
		  
			update('estoque_compras',$cad2,"id = '$cotacaoId'");	
			header("Location: ".$_SESSION['url']);
		 
		}
		
	}
 
		
	if(isset($_POST['deletar'])){
		
		$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId'");
		if($leitura){
			foreach($leitura as $mostra):
				$materialId = $mostra['id'];
				delete('estoque_compras',"id = '$materialId'");
			
			 endforeach;
		}

		delete('estoque_compras',"id = '$cotacaoId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header("Location: ".$_SESSION['url']);
	}
			 
			 
	 
	if(isset($_POST['enviar'])){
		
		if(!empty($edit['fornecedor1'])){
			
				 
			$link = URL."/compras/painel2.php?execute=cotacao/cotacao-editar1&cotacaoEditar=" .$cotacaoId;
		
			$fornecedorId=$edit['fornecedor1'];
			$fornecedor = mostra('estoque_fornecedor',"WHERE id = '$fornecedorId'");
	
			$assunto = "Clean Ambiental - Cotação de Compras - Id " . $cotacaoId;
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";
				
			$msg .= "Prezado(a) Cliente,  <br /><br />";
			$msg .= "Segue link para preenchimento dos valores de nossa cotação de preço.<br /><br />";
			$msg .= "Fornecedor : " . $fornecedor['nome'] . "<br /><br />";
		 	$msg .= "<a href=" . $link . ">Cotação de Preço</a> <br /><br />";
		 		
		 
			$msg .= "Estamos também disponíveis no telefone 3104-2992  <br /><br />";
			
			$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
			$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
			$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";

			$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
			$msg .=  "</font>";

			$financeiro='financeiro@cleanambiental.com.br';
			
			$fornecedor['email']='wpcsistema@gmail.com';
			enviaEmail($assunto,$msg,$financeiro,SITENOME,$fornecedor['email'], $fornecedor['nome']);
		
		}
		
		if(!empty($edit['fornecedor2'])){	
		
			$link = URL."/compras/painel2.php?execute=cotacao/cotacao-editar2&cotacaoEditar=" .$cotacaoId;
		
			$fornecedorId=$edit['fornecedor2'];
			$fornecedor = mostra('estoque_fornecedor',"WHERE id = '$fornecedorId'");
	
			$assunto = "Clean Ambiental - Cotação de Compras - Id " . $cotacaoId;
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";
				
			$msg .= "Prezado(a) Cliente,  <br /><br />";
				
			$msg .= "Segue link para preenchimento dos valores de nossa cotação de preço.<br /><br />";

			$msg .= "Fornecedor : " . $fornecedor['nome'] . "<br /><br />";
		 	$msg .= "<a href=" . $link . ">Cotação de Preço</a> <br /><br />";
		 		
		 
			$msg .= "Estamos também disponíveis no telefone 3104-2992  <br /><br />";
			
			$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
			$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
			$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";

			$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
			$msg .=  "</font>";

			$financeiro='financeiro@cleanambiental.com.br';
			
			$fornecedor['email']='wpcsistema@gmail.com';
			enviaEmail($assunto,$msg,$financeiro,SITENOME,$fornecedor['email'], $fornecedor['nome']);
		}
		
		if(!empty($edit['fornecedor3'])){	
		
			$link = URL."/compras/painel2.php?execute=cotacao/cotacao-editar3&cotacaoEditar=" .$cotacaoId;
		
			$fornecedorId=$edit['fornecedor3'];
			$fornecedor = mostra('estoque_fornecedor',"WHERE id = '$fornecedorId'");
	
			$assunto = "Clean Ambiental - Cotação de Compras - Id " . $cotacaoId;
			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";
				
			$msg .= "Prezado(a) Cliente,  <br /><br />";
				
			$msg .= "Segue link para preenchimento dos valores de nossa cotação de preço.<br /><br />";

			$msg .= "Fornecedor : " . $fornecedor['nome'] . "<br /><br />";
		 	$msg .= "<a href=" . $link . ">Cotação de Preço</a> <br /><br />";
		 		
		 
			$msg .= "Estamos também disponíveis no telefone 3104-2992  <br /><br />";
			
			$msg .= "<font size='4 px' face='Verdana, Geneva, sans-serif' color='#0#09c89'>";
			$linkzap = "https://api.whatsapp.com/send?phone=552199871-0334&text=Ola !";
			$msg .= "<a href=" . $linkzap . ">WhatsApp 21 99871-0334</a> <br /><br />";

			$msg .= "Mensagem enviada automaticamente pelo sistema! <br /><br />";
			$msg .=  "</font>";

			$financeiro='financeiro@cleanambiental.com.br';
			
			$fornecedor['email']='wpcsistema@gmail.com';
			enviaEmail($assunto,$msg,$financeiro,SITENOME,$fornecedor['email'], $fornecedor['nome']);
	 
			
		 }
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Enviado Cotação com sucesso</div>';
   
		}
 

	?>
 
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			 <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
             </div> 
		
             <div class="form-group col-xs-12 col-md-2">  
               	<label>Interação</label>
   				<input name="orc_resposta" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div> 
		  
			<div class="form-group col-xs-12 col-md-6">  
				 <label>Tipo Material</label>
				<select name="id_material"  class="form-control" readonly>
					<option value="">Selecione o Tipo</option>
							<?php 
							$readContrato = read('estoque_material_tipo',"WHERE id  ORDER BY codigo ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($edit['id_material'] == $mae['id']){
										echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].' - '.$mae['nome'].'</option>';
									}else{
										echo '<option value="'.$mae['id'].'">'.$mae['codigo'].' -  '.$mae['nome'].'</option>';
									}
									endforeach;	
								}
							?> 
				 </select>
			</div> 
		
		
		  <div class="form-group col-xs-12 col-md-2">  
                 <label>Data da Solicitação</label>
                
               <input type="date" name="data_solicitacao" class="form-control pull-right" value="<?php echo $edit['data_solicitacao'];?>"  readonly/>
          
           </div> 
		

		
	 <div class="form-group col-xs-12 col-md-12">  

		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 1</label>
                <select name="fornecedor1" class="form-control">
                    <option value="">Selecione o Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['fornecedor1'] == $mae['id']){
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
          		<label>Desconto (%) </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor1_desconto" class="form-control pull-right" value="<?php echo converteValor($edit['fornecedor1_desconto']);?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-2">  
          		<label>Cond Pagamento</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor1_pag" class="form-control pull-right" value="<?php echo $edit['fornecedor1_pag'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-2">  
          		<label>Prazo de Entrega</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor1_prazo" class="form-control pull-right" value="<?php echo $edit['fornecedor1_prazo'];?>"  />
                 </div><!-- /.input group -->
          </div>
		 
		 
		 <div class="form-group col-xs-12 col-md-2">
                   <input name="fornecedor1_melhor" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['fornecedor1_melhor']; ?>  class="minimal"   >
                Melhor Preço
            </div> 
			
	</div>	
		
	<div class="form-group col-xs-12 col-md-12">  

		  
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 2 </label>
                <select name="fornecedor2" class="form-control">
                    <option value="">Selecione o Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['fornecedor2'] == $mae['id']){
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
          		<label>Desconto (%) </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor2_desconto" class="form-control pull-right" value="<?php echo converteValor($edit['fornecedor2_desconto']);?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-2">  
          		<label>Cond Pagamento</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor2_pag" class="form-control pull-right" value="<?php echo $edit['fornecedor2_pag'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-2">  
          		<label>Prazo de Entrega</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor2_prazo" class="form-control pull-right" value="<?php echo $edit['fornecedor2_prazo'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-2">
                   <input name="fornecedor2_melhor" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['fornecedor2_melhor']; ?>  class="minimal"   >
                Melhor Preço
            </div> 
	
	</div>	
		
	<div class="form-group col-xs-12 col-md-12">  

		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 3</label>
                <select name="fornecedor3" class="form-control">
                    <option value="">Selecione o Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['fornecedor3'] == $mae['id']){
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
          		<label>Desconto (%) </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor3_desconto" class="form-control pull-right" value="<?php echo converteValor($edit['fornecedor3_desconto']);?>"  />
                 </div><!-- /.input group -->
        </div>
		
		<div class="form-group col-xs-12 col-md-2">  
          		<label>Cond Pagamento</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor3_pag" class="form-control pull-right" value="<?php echo $edit['fornecedor3_pag'];?>"  />
                 </div><!-- /.input group -->
        </div>
		
		<div class="form-group col-xs-12 col-md-2">  
          		<label>Prazo de Entrega</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor3_prazo" class="form-control pull-right" value="<?php echo $edit['fornecedor3_prazo'];?>"  />
                 </div><!-- /.input group -->
        </div>
		
		<div class="form-group col-xs-12 col-md-2">
                   <input name="fornecedor3_melhor" type="checkbox" id="documentos_0" value="1" <?PHP echo $edit['fornecedor3_melhor']; ?>  class="minimal"   >
                Melhor Preço
            </div> 
 
	    </div>	
		
		
		<div class="form-group col-xs-12 col-md-12"> 
		
		<div class="form-group col-xs-12 col-md-2">  
				 <label>Status da Compra</label>
				<select name="status" class="form-control input-sm" >
					<option value="">Selecione o Status</option>
							<?php 
							$readContrato = read('estoque_compra_status',"WHERE id ORDER BY id ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
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
		</div> 
		
		<div class="form-group col-xs-12 col-md-12">  
		
           <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Cotação</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data_cotacao" class="form-control pull-right" value="<?php echo $edit['data_cotacao'];?>"/>
                </div><!-- /.input group -->
           </div> 
			
			 <div class="form-group col-xs-12 col-md-3">  
					 <label>Data da Compra</label>
					  <div class="input-group">
						   <div class="input-group-addon">
							 <i class="fa fa-calendar"></i>
						   </div>
				   <input type="date" name="data_compra" class="form-control pull-right" value="<?php echo $edit['data_compra'];?>"  />
					</div><!-- /.input group -->
			</div> 
		 
		   <div class="form-group col-xs-12 col-md-3">  
					 <label>Data do Recebimento</label>
					  <div class="input-group">
						   <div class="input-group-addon">
							 <i class="fa fa-calendar"></i>
						   </div>
				   <input type="date" name="data_recebimento" class="form-control pull-right" value="<?php echo $edit['data_recebimento'];?>"  />
					</div><!-- /.input group -->
			</div> 
	 			 
		  
			<div class="form-group col-xs-12 col-md-3">  
					<label>Nota Fiscal</label>
				   <div class="input-group">
						  <div class="input-group-addon">
								<i class="fa fa-credit-card"></i>
							  </div>
							  <input type="text" name="nota_fiscal" class="form-control pull-right" value="<?php echo $edit['nota_fiscal'];?>"  />
					 </div><!-- /.input group -->
			</div>
	 
		
		</div><!-- /.col-xs-12 col-md-12 -->
	 
		<div class="form-group col-xs-12 col-md-12">  
			
			 <div class="form-group col-xs-4">

					 <label>Autorização de Compra</label>
				 
					   <?php
		
							if($_SESSION['autUser']['nivel']==5){	//Gerencial 
								echo '<select name="compra_autorizacao" class="form-control" >';
							}else{
								echo '<select name="compra_autorizacao" class="form-control"> readonly';
							}
		
						?>	

						<option value="">Selecione Autorização</option>
					   <option <?php if($edit['compra_autorizacao'] == '1') echo' selected="selected"';?> value="1" >Autorizado </option>
						<option <?php if($edit['compra_autorizacao'] == '2') echo' selected="selected"';?> value="2">Não Autorizado</option>

					 </select>
			   </div>  
		
			 <div class="form-group col-xs-12 col-md-3">  
				<label>Autorizado por </label>
					<select name="responsavel_autorizacao" class="form-control" disabled>
						<option value="">...</option>
						<?php 
							$readConta = read('usuarios',"WHERE id ORDER BY id ASC");
							if(!$readConta){
								echo '<option value="">Não temos registro no momento</option>';	
							}else{
								foreach($readConta as $mae):
								   if($edit['responsavel_autorizacao'] == $mae['id']){
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
					 <label>Data da Autorização</label>
					  <div class="input-group">
						   <div class="input-group-addon">
							 <i class="fa fa-calendar"></i>
						   </div>
				   <input type="date" name="data_autorizacao" class="form-control pull-right" value="<?php echo $edit['data_autorizacao'];?>" disabled />
					</div><!-- /.input group -->
			  </div> 
		
		  </div><!-- /.col-xs-12 col-md-12 -->
		
  		 		 
    	<div class="form-group col-xs-12 col-md-12">  
             
		 <div class="box-footer">
			 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
				  <?php 
			 
				 
			 
					 if($acao=="atualizar"){
						echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
						 
						 
					 
					}
			 
			  		if( $edit['status']=="2"){
						echo '<input type="submit" name="enviar" value="Enviar Cotação" class="btn btn-success" />';	
					}
			  
			 		if($edit['status']=='3'){
						echo '<input type="submit" name="autorizar" value="Autorizar Compra" class="btn btn-success" />';

					}
			   
					 if($acao=="deletar"){
						echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';	
					}
					 
					if($acao=="enviar"){
						echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-success" />';	
					}
		
				 ?>  
			  </div> 
          
          </div>
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
	
	
	
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					
				<div class="box-header">
		
				 <a href="painel.php?execute=suporte/compras/cotacao-material-editar&cotacaoId=<?PHP echo $cotacaoId; ?>" class="btnnovo">
				  <img src="ico/novo.png"  title="Criar Novo" />
					<small>Adicionar Material</small>
					 </a>
		
				</div><!-- /.box-header -->
					
				 <div class="box-header">
             	  <div class="row">
            
					<div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
                </div><!-- /row-->  
         </div><!-- /box-header-->   

		<div class="box-body table-responsive">

			<?php 
			
			$fornecedorId = $edit['fornecedor1'];
			$fornecedor1 = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");
			$fornecedorId = $edit['fornecedor2'];
			$fornecedor2 = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");
			$fornecedorId = $edit['fornecedor3'];
			$fornecedor3 = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");
			
			$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId' 
						ORDER BY id ASC");
			
			$total=0;
			$totalGeral1=0;
			$totalGeral2=0;
			$totalGeral3=0;
			
			$titulo=0;
			
			if($leitura){

				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Material</td>
					<td align="center">Quantidade</td>
					<td align="center">Unidade</td>
					<td align="center">Preço Unit</td>
					<td align="center">Total</td>
					<td align="center">Preço Unit</td>
					<td align="center">Total</td>
					<td align="center">Preço Unit</td>
					<td align="center">Total</td>
					
					<td align="center">Quantidade</td>
					<td align="center">Data</td>
					
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
				
			foreach($leitura as $mostra):
				
				
				if($titulo==0){
					echo '<tr>';
						echo '<td align="center"> </td>';
						echo '<td align="center"> </td>';
						echo '<td align="center"> </td>';
						echo '<td align="center"> </td>';
						echo '<td align="center" colspan="2">'.substr($fornecedor1['nome'],0,12).'</td>';
						echo '<td align="center" colspan="2">'.substr($fornecedor2['nome'],0,12).'</td>';
					
						echo '<td align="center" colspan="2">'.substr($fornecedor3['nome'],0,12).'</td>';
		 
						echo '<td align="center" colspan="2">Entrada no Estoque</td> ';
						echo '<td align="center"> </td>';
						echo '<td align="center"> </td>';
						echo '<td align="center"> </td>';
					
					echo '</tr>';
					$titulo=1;
				}
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				echo '<td>'.$material['nome'].'</td>';
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.$mostra['unidade'].'</td>';
				
				$total1=$mostra['quantidade']*$mostra['valor1'];
				$total2=$mostra['quantidade']*$mostra['valor2'];
				$total3=$mostra['quantidade']*$mostra['valor3'];
				
				$totalGeral1+=$total1;
				$totalGeral2+=$total2;
				$totalGeral3+=$total3;
				
				echo '<td align="right">'.converteValor($mostra['valor1']).'</td>';
				if($mostra['fornecedor1']=='1'){
					 echo '<td align="right"><span class="badge bg-green">'.converteValor($total1).'</span></td>';
				}else{
					echo '<td align="center">'.converteValor($total1).'</td>';
				}
				
					
				echo '<td align="right">'.converteValor($mostra['valor2']).'</td>';
				if($mostra['fornecedor2']=='1'){
					 echo '<td align="right"><span class="badge bg-blue">'.converteValor($total2).'</span></td>';
				}else{
					echo '<td align="right">'.converteValor($total2).'</td>';
				}
				
				echo '<td align="right">'.converteValor($mostra['valor3']).'</td>';
				if($mostra['fornecedor3']=='1'){
					 echo '<td align="center"><span class="badge bg-red">'.converteValor($total3).'</span></td>';
				}else{
					echo '<td align="right">'.converteValor($total3).'</td>';
				}
				
				echo '<td align="right">'.$mostra['quantidade_recebida'].'</td>';
				
				if(!empty($mostra['data_entrega'])){
					echo '<td align="right">'.converteData($mostra['data_entrega']).'</td>';
				}else{
					echo '<td align="right">-</td>';
				}
				
				$total++;
					
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/cotacao-material-editar&cotacaoMaterialEditar='.$mostra['id'].'">
							<img src="ico/editar.png"  title="Editar" />
              			</a>
					</td>';
				 

					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/compras-material-editar&cotacaoMaterialDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" title="Excluir" />
              			</a>
					</td>';
				
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/estoque/material-reposicao-editar&reposicaoCotacao='.$mostra['id'] .'">
							<img src="ico/inicio.png" alt="Reposicão" title="Reposicão"  />
              			</a>
						</td>';
						
			echo '</tr>';
		
		 endforeach;
		 
		  echo '<tfoot>';
				
         		echo '<tr>';
                	echo '<td></td>';
					echo '<td>Sub-Total</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($totalGeral1).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($totalGeral2).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($totalGeral3).'</td>';
                echo '</tr>';

				$desconto1=($totalGeral1*$edit['fornecedor1_desconto'])/100;
				$desconto2=($totalGeral2*$edit['fornecedor1_desconto'])/100;
				$desconto3=($totalGeral3*$edit['fornecedor1_desconto'])/100;
				
				echo '<tr>';
                	echo '<td></td>';
					echo '<td>Desconto</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($desconto1).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($desconto2).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($desconto3).'</td>';
                echo '</tr>';
				
				$totalGeral1=$totalGeral1-$desconto1;
				$totalGeral2=$totalGeral2-$desconto2;
				$totalGeral3=$totalGeral3-$desconto3;
				
				$totalGeral=$totalGeral1+$totalGeral2+$totalGeral3;
				
				echo '<tr>';
                	echo '<td></td>';
					echo '<td>Total Líquido</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
				
					echo '<td align="right">'.converteValor($totalGeral1).'</td>';
								
					echo '<td></td>';
				
					echo '<td align="right">'.converteValor($totalGeral2).'</td>';
					echo '<td></td>';
					
					echo '<td align="right">'.converteValor($totalGeral3).'</td>';
					
                echo '</tr>';
				
          echo '</tfoot>';
		
		echo '</table>';
		
		
		}
	?>

				<div class="box-footer">
					<?php echo $_SESSION['cadastro'];
					unset($_SESSION['cadastro']);
					?>
				</div>
						<!-- /.box-footer-->

					</div>
					<!-- /.box-body table-responsive -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col-md-12 -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
	
	
	
	
</div><!-- /.content-wrapper -->