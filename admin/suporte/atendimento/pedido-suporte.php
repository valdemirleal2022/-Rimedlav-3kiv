<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}

		$clienteId = $_GET['clienteId'];
		$readCliente = read('cliente',"WHERE id = '$clienteId'");
		if(!$readCliente){
			header('Location: painel.php?execute=suporte/error');
		}
		foreach($readCliente as $cliente);
		
		$cad['status'] = "Ok";
		$cad['data_solicitacao'] =  date('d/m/Y');
		$cad['hora_solicitacao'] = date("H:i");
		$cad['data_solucao'] =  date('d/m/Y');
		$cad['hora_solucao'] = date("H:i");
		$cad['usuario'] = 'Wellington';
		$cad['id_suporte']  ='1';
		$cad['solicitacao']='Demostração do Sistema';
		$cad['solucao']='Baixe pelo site a Demostração o Sistema e use por 03 meses inteiramento grátis. Caso tenha dúvida entre em contato com nosso suporte técnico';
 
 ?>

<div class="content">

	<h1>Cliente : <strong><?php echo $cliente['nome']  ;?></strong></h1>
    
	<?php 
		if(isset($_POST['cadastrar'])){
			$cad['data_solicitacao'] 	=  implode("-",array_reverse(explode("/",$data_solicitacao)));
			$cad['hora_solicitacao']	= strip_tags(trim(mysql_real_escape_string($_POST['hora_solicitacao'])));
			$cad['data_solucao'] 	=  implode("-",array_reverse(explode("/",$data_solucao)));
			$cad['hora_solucao']	= strip_tags(trim(mysql_real_escape_string($_POST['hora_solucao'])));
			$cad['solicitacao'] 		=  strip_tags(trim(mysql_real_escape_string($_POST['solicitacao'])));
			$cad['usuario']  			= strip_tags(trim(mysql_real_escape_string($_POST['usuario'])));
			$cad['id_suporte']  			= strip_tags(trim(mysql_real_escape_string($_POST['id_suporte'])));
			$cad['status']  			= strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			$cad['id_cliente']  		= $clienteId;
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div>'.'<br>';	
			  }else{
			  create('pedido',$cad);		
			  header('Location: painel.php?execute=suporte/pedido/pedidos');
		    }
		}
	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  	            
            <label>
            <span>Motivo do Suporte :</span>
                <select name="id_suporte">
                    <option value="">Selecione um Motivo</option>
                    <?php 
                        $readSuporte = read('suporte',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($cad['id_suporte'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		</label>
            
      		<label>
       		   <span>Status :</span>
               <input type="text" name="status" value="<?php echo $cad['status'];?>"  size="10" maxlength="10" />
            </label>
            <label>
                <span>Data:</span>
                <input type="text" name="data_solicitacao" value="<?php echo $cad['data_solicitacao'];?>" />
            </label>
            
             <label>
                 <span>Hora :</span>
               <input name="hora_solicitacao" type="Text" value="<?php echo $cad['hora_solicitacao'];?>"  size="10" maxlength="10" readonly />
            </label>
            <label>
       		   <span>Usuário :</span>
               <input type="text" name="usuario" value="<?php echo $cad['usuario'];?>"  size="50" maxlength="50" />
            </label>
             <label>
                <span>Descrição:</span>
            </label>
             <label>
                <textarea name="solicitacao" cols="100" rows="5">
                    <?php echo $cad['solicitacao'];?>
                </textarea>
            </label>
            
             <label>
               <span>Solução:</span>
            </label>
                       
                 <label>
                 <span>Data :</span>
               <input name="data_solucao"  value="<?php echo $cad['data_solucao'];?>" /> 
            </label>
             <label>
                 <span>Hora :</span>
               <input name="hora_solucao" type="text" value="<?php echo $cad['hora_solucao'];?>"  size="10" maxlength="10" readonly />
            </label>
            
             <label>
                <span>Descrição:</span>
            </label>
             <label>
                <textarea name="solucao" cols="100" rows="5">
                    <?php echo $cad['solucao'];?>
                </textarea>
            </label>
                
           
			<input type="submit" name="cadastrar" value="Cadastrar" class="enviar" />
			 
            
   			
			 
  </form>
    
</div><!--/content-->
 