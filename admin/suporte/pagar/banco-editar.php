<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}

		$acao = "cadastrar";
		if(!empty($_GET['bancoEditar'])){
			$bancoId = $_GET['bancoEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['bancoDeletar'])){
			$bancoId = $_GET['bancoDeletar'];
			$acao = "deletar";
		}
		if(!empty($bancoId)){
			$readbanco = read('banco',"WHERE id = '$bancoId'");
			if(!$readbanco){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readbanco as $edit);
			 
		}
 
?>
	
    
<div class="content-wrapper">
  <section class="content-header">
          <h1>Bancos</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Movimentação</a></li>
            <li><a href="painel.php?execute=suporte/pagar/bancos">Bancos</a></li>
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
  
				$edit['nome'] = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
				$edit['codigo_banco'] = mysql_real_escape_string($_POST['codigo_banco']);
				$edit['digito_banco'] = mysql_real_escape_string($_POST['digito_banco']);
				$edit['agencia'] = strip_tags(trim(mysql_real_escape_string($_POST['agencia'])));
				$edit['conta'] = strip_tags(trim(mysql_real_escape_string($_POST['conta'])));
				$edit['conta_digito'] = mysql_real_escape_string($_POST['conta_digito']);;
			$edit['carteira'] = strip_tags(trim(mysql_real_escape_string($_POST['carteira'])));
				$edit['multa'] = strip_tags(trim(mysql_real_escape_string($_POST['multa'])));
				$edit['juros'] = mysql_real_escape_string($_POST['juros']);
				$edit['codigo_cedente']=mysql_real_escape_string($_POST['codigo_cedente']);
				$edit['contador_remessa']=mysql_real_escape_string($_POST['contador_remessa']);
				$edit['empresa']=mysql_real_escape_string($_POST['empresa']);
			$edit['instrucao_cobranca1']=mysql_real_escape_string($_POST['instrucao_cobranca1']);
			$edit['instrucao_cobranca2']=mysql_real_escape_string($_POST['instrucao_cobranca2']);
				$edit['dias_protesto']=mysql_real_escape_string($_POST['dias_protesto']);
				$edit['status']=mysql_real_escape_string($_POST['status']);
				$edit['codigo']=mysql_real_escape_string($_POST['codigo']); 
			$edit['codigo_transmissao']=mysql_real_escape_string($_POST['codigo_transmissao']); 
				$edit['status'] = mysql_real_escape_string($_POST['status']); 
				 
				$edit['limite']	= strip_tags(trim(mysql_real_escape_string($_POST['limite'])));
				$edit['limite'] = str_replace(",",".",str_replace(".","",$edit['limite']));
				 
				// print_r($edit);
				 
				update('banco',$edit,"id = '$bancoId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/pagar/bancos');
				unset($cad);
				
			}
			if(isset($_POST['cadastrar'])){
				
				$edit['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
				$edit['codigo_banco'] = mysql_real_escape_string($_POST['codigo_banco']);
				$edit['digito_banco'] = mysql_real_escape_string($_POST['digito_banco']);
				$edit['agencia'] = strip_tags(trim(mysql_real_escape_string($_POST['agencia'])));
				$edit['conta'] = strip_tags(trim(mysql_real_escape_string($_POST['conta'])));
				$edit['conta_digito'] = mysql_real_escape_string($_POST['conta_digito']);;
				$edit['carteira'] = strip_tags(trim(mysql_real_escape_string($_POST['carteira'])));
				$edit['multa'] = strip_tags(trim(mysql_real_escape_string($_POST['multa'])));
				$edit['juros'] = mysql_real_escape_string($_POST['juros']);
				$edit['codigo_cedente']=mysql_real_escape_string($_POST['codigo_cedente']);
				$edit['contador_remessa']=mysql_real_escape_string($_POST['contador_remessa']);
				$edit['empresa']=mysql_real_escape_string($_POST['empresa']);
				$edit['status'] = mysql_real_escape_string($_POST['status']);
				$edit['limite']	= strip_tags(trim(mysql_real_escape_string($_POST['limite'])));
				$edit['limite'] = str_replace(",",".",str_replace(".","",$edit['limite']));
			

				if(in_array('',$edit)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
				  }else{
					$edit['limite']	= strip_tags(trim(mysql_real_escape_string($_POST['limite'])));
					$edit['limite'] = str_replace(",",".",str_replace(".","",$edit['limite']));
					create('banco',$edit);	
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
					header('Location: painel.php?execute=suporte/pagar/bancos');
					unset($cad);
				}
			}
			if(isset($_POST['deletar'])){
					$readDeleta = read('banco',"WHERE id = '$bancoId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div>';	
					}else{
						delete('banco',"id = '$bancoId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header('Location: painel.php?execute=suporte/pagar/bancos');
					}
			}
		?>
			 
			 
         
    	   <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
			   
			   
			 <div class="box-header with-border">
                 
                  <h3 class="box-title">Dados da Conta</h3>
              
            </div><!-- /.box-header -->
                
            <div class="box-body">
                <div class="row">	
					 
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
					
			
   
      		<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Banco</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
           </div> 
           
      		<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Código do Banco</label>
               <input type="text" name="codigo_banco" value="<?php echo $edit['codigo_banco'];?>"  class="form-control"/>
           </div> 
           
           <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Dígito do Banco</label>
               <input type="text" name="digito_banco" value="<?php echo $edit['digito_banco'];?>"  class="form-control"/>
           </div> 
           
            <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Agência</label>
               <input type="text" name="agencia" value="<?php echo $edit['agencia'];?>"  class="form-control"/>
           </div> 
           
            <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Conta</label>
               <input type="text" name="conta" value="<?php echo $edit['conta'];?>"  class="form-control"/>
           </div> 
           
            <div class="form-group col-xs-12 col-md-1"> 
       		   <label>Dígito</label>
               <input type="text" name="conta_digito" value="<?php echo $edit['conta_digito'];?>"  class="form-control"/>
           </div> 
					 
			 </div> <!-- /.row -->
		 </div> <!-- /.box-bodyr -->
	 				 
		<div class="box-header with-border">
            <h3 class="box-title">Dados da Cobrança</h3>
        </div><!-- /.box-header -->
                
        <div class="box-body">
            <div class="row">	
             
            <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Carteira</label>
               <input type="text" name="carteira" value="<?php echo $edit['carteira'];?>"  class="form-control"/>
           </div>
         
           
			<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Código Cedente</label>
               <input type="text" name="codigo_cedente" value="<?php echo $edit['codigo_cedente'];?>"  class="form-control"/>
           </div>
 					 
		  <div class="form-group col-xs-12 col-md-6"> 
       		   <label>codigo_transmissao</label>
               <input type="text" name="codigo_transmissao" value="<?php echo $edit['codigo_transmissao'];?>"  class="form-control"/>
           </div>
			   
			<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Contador Remessa</label>
               <input type="text" name="contador_remessa" value="<?php echo $edit['contador_remessa'];?>"  class="form-control"/>
           </div>
				
		 </div> <!-- /.row -->
		</div> <!-- /.box-bodyr -->
			   
		<div class="box-header with-border">
            <h3 class="box-title">Multa/Juros</h3>
        </div><!-- /.box-header -->
                
        <div class="box-body">
            <div class="row">	
             
            <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Multa por Atraso</label>
               <input type="text" name="multa" value="<?php echo $edit['multa'];?>"  class="form-control"/>
           </div>
     
			<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Juros Diário</label>
               <input type="text" name="juros" value="<?php echo $edit['juros'];?>"  class="form-control"/>
           </div>
 					 
		  <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Dias Protesto</label>
               <input type="text" name="dias_protesto" value="<?php echo $edit['dias_protesto'];?>"  class="form-control"/>
           </div>
			   
			<div class="form-group col-xs-12 col-md-3"> 
       		   <label>Codigo Instrução de Cobranca 1</label>
               <input type="text" name="instrucao_cobranca1" value="<?php echo $edit['instrucao_cobranca1'];?>"  class="form-control"/>
           </div>
				
			<div class="form-group col-xs-12 col-md-3"> 
       		   <label>Codigo Instrução de Cobranca 2</label>
               <input type="text" name="instrucao_cobranca2" value="<?php echo $edit['instrucao_cobranca2'];?>"  class="form-control"/>
           </div>
				
		 </div> <!-- /.row -->
		</div> <!-- /.box-bodyr -->
		 
	 
	<div class="box-header with-border">
        <h3 class="box-title">Dados do Cedente</h3>
    </div><!-- /.box-header -->
                
       <div class="box-body">
         <div class="row">	
  
			<div class="form-group col-xs-12 col-md-6"> 
              <label>Empresa</label>
                <select name="empresa" class="form-control">
                    <option value="">Selecione Empresa</option>
                    <?php 
                        $readBanco = read('empresa',"WHERE id");
                        if(!$readBanco){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($edit['empresa'] == $mae['id']){
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
          		<label>Limite</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-crcad-card"></i>
                          </div>
                          <input type="text" name="limite" class="form-control pull-right"  value="<?php echo converteValor($edit['limite']);?>"/>
                 </div><!-- /.input group -->
           </div>
					 
			<div class="form-group col-xs-12 col-md-2"> 
						  <label>Status (*)</label>
							<select name="status" class="form-control">
							  <option value="">Selecione o status &nbsp;&nbsp;</option>

							  <option <?php if($edit['status'] && $edit['status'] == '1') echo' selected="selected"';?> value="1"> Ativo &nbsp;&nbsp;</option>

							  <option <?php if($edit['status'] && $edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo &nbsp;&nbsp;</option>
							 </select>
		    </div>
					 
			<div class="form-group col-xs-12 col-md-2"> 
               <label>Codigo</label>
               <input name="codigo" type="text" value="<?php echo $edit['codigo'];?>" class="form-control"  />
           </div> 
		 
					 
	   </div> <!-- /.row -->
	 </div> <!-- /.box-bodyr -->

         <div class="form-group col-xs-12 col-md-12">     
			<div class="box-footer">
				<a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
				<?php 
					if($acao=="atualizar"){
						echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
					}
					if($acao=="deletar"){
						echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
					}
					if($acao=="cadastrar"){
						echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
					}
					if($acao=="enviar"){
						echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary" />';	
					}
				 ?>  
			</div>
		   </form>
   </div>
			 
			 
		</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
