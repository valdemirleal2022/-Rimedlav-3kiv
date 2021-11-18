<?php 
		
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}

		}

		$acao = "cadastrar";
		if(!empty($_GET['usuarioEditar'])){
			$usuariosId = $_GET['usuarioEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['usuarioDeletar'])){
			$usuariosId = $_GET['usuarioDeletar'];
			$acao = "deletar";
		}

		if(!empty($usuariosId)){
			$readusuario = read('usuarios',"WHERE id = '$usuariosId'");
			if(!$readusuario){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readusuario as $edit);
		}

		 
?>

<div class="content-wrapper">

    <section class="content-header">
          <h1>Usuários</h1>
          <ol class="breadcrumb">
            <li>Home</a></li>
            <li>Cadastro</a></li>
            <li class="active">Usuários</li>
          </ol>
     </section>

 <section class="content">
      <div class="box box-default">
           
            <div class="box-header with-border">
             <h1><?php echo $edit['nome'];?></h1>	
              <div class="box-tools">
                        <small>
                         <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                         <?php if($acao=='deletar') echo 'Deletando';?>
                         <?php if($acao=='atualizar') echo 'Alterando';?>
                         <?php if($acao=='baixar') echo 'Baixando';?>
                        </small>
                    </div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
          
     	<div class="box-body">
       
 	 <?php
			
		if(isset($_POST['atualizar'])){
			
			$cad['nome'] 	 = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		
			$cad['email'] 	 = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['senha']	 = strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$cad['status']   = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			$cad['nivel']    = strip_tags(trim(mysql_real_escape_string($_POST['nivel'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-info">Todos os campos são obrigatórios!</div>'.'<br>';	
			}elseif(!email($cad['email'])){
				echo '<div class="alert alert-info">Desculpe o e-mail informado é inválido!</div>'.'<br>';	
			}elseif(strlen($cad['senha']) < 5 || strlen($cad['senha']) > 10){
				echo '<div class="alert alert-info">Sua senha deve ter entre 5 a 10 digitos!</div>'.'<br>';	
				}else{
					if(!empty($_FILES['fotoperfil']['tmp_name'])){
							$imagem = $_FILES['fotoperfil'];
							$pasta  = '../uploads/usuarios/';
							$tmp    = $imagem['tmp_name'];
							$ext    = substr($imagem['name'],-3);
							$nome   = md5(time()).'.'.$ext;
							$cad['fotoperfil'] = $nome;
							uploadImg($tmp, $nome, '160', $pasta);	
					}
				update('usuarios', $cad, "id = '$usuariosId'");
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/usuario/usuarios');
			}
		};	
		
		if(isset($_POST['cadastrar'])){
			$cad['nome'] 	 = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			//$cad['telefone']	 = strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$cad['email'] 	 = strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['senha']	 = strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$cad['status']   = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			$cad['nivel']    = strip_tags(trim(mysql_real_escape_string($_POST['nivel'])));
			if(in_array('',$cad)){
				echo '<div class="alert alert-info">Todos os campos são obrigatórios!</div>'.'<br>';	
			}elseif(!email($cad['email'])){
				echo '<div class="alert alert-info">Desculpe o e-mail informado é inválido!</div>'.'<br>';	
			}elseif(strlen($cad['senha']) < 5 || strlen($cad['senha']) > 10){
				echo '<div class="alert alert-info">Sua senha deve ter entre 5 a 10 digitos!</div>'.'<br>';	
			}else{
				if(!empty($_FILES['fotoperfil']['tmp_name'])){
						$imagem = $_FILES['fotoperfil'];
						$pasta  = '../uploads/usuarios/';
				       	$tmp    = $imagem['tmp_name'];
						$ext    = substr($imagem['name'],-3);
						$nome   = md5(time()).'.'.$ext;
						$cad['fotoperfil'] = $nome;
						uploadImg($tmp, $nome, '160', $pasta);	
				}
				create('usuarios',$cad);
				$_SESSION['cadastro'] = '<div class="msgAcerto">Cadastro com sucesso</div>'.'<br>';	
				header('Location: painel.php?execute=suporte/usuario/usuarios');
			}
		};	
			 
		if ( isset( $_POST[ 'deletar' ] ) ) {
			delete( 'usuarios', "id = '$usuarioId'" );
			header( 'Location: painel.php?execute=suporte/usuario/usuarios' );
		}

	?>
    
     <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
     		 
   
		   <div class="form-group">
				<?php 
					if($edit['fotoperfil'] != '' && file_exists('../uploads/usuarios/'.$edit['fotoperfil'])){
						echo '<img src="../uploads/usuarios/'.$edit['fotoperfil'].'"/>';
					}
				?>
		   </div>

		  <div class="form-group">
				<input type="file" name="fotoperfil"/>
		  </div>
       
    	 <div class="form-group col-xs-12 col-md-6"> 
           	<label>Nome</label>
            <input name="nome" class="form-control" type="text" value="<?php echo $edit['nome'];?>"/>
       	  </div>
          
          <div class="form-group col-xs-12 col-md-6"> 
           	<label>Telefone</label>
            <input name="telefone" class="form-control" type="text" value="<?php echo $edit['telefone'];?>"/>
       	  </div>
          
      	<div class="form-group col-xs-12 col-md-6"> 
           <label>E-mail</label>
            <input name="email" class="form-control" type="email" value="<?php echo $edit['email'];?>"/>
       	 </div>
    	<div class="form-group col-xs-12 col-md-6"> 
           <label>Senha</label>
              <input name="senha" class="form-control" type="password"  title="<?php echo $edit['senha'];?>" value="<?php echo $edit['senha'];?>"/>
       </div>
       
    	<div class="form-group col-xs-12 col-md-6"> 
          <label>Status</label>
            <select name="status" class="form-control">
              <option value="">Selecione o status &nbsp;&nbsp;</option>
              
              <option <?php if($edit['status'] && $edit['status'] == '1') echo' selected="selected"';?> value="1"> Ativo &nbsp;&nbsp;</option>
              
              <option <?php if($edit['status'] && $edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo &nbsp;&nbsp;</option>

              
             </select>
      	</div>
      
      <div class="form-group col-xs-12 col-md-6"> 
          <label>Nível</label>
            <select name="nivel" class="form-control">
				
		<!--	// 1 - Operacional 
				// 2 - Atendimento ao Cliente / Comercial
				// 3 - Faturamento / Cobrança
				// 4 - Compras / Financeiro
				// 5 - Gerencial
				// 6 - Manifesto 
				// 7 - DP/RH
				// 8 - Vendas
				// 9 -  Manutenção / Almoxarifado
				// 10 - Ambiental e Patrimonial
				// 11 - Juridico
				// 12 - Portaria
				// 13 - Oficina
				// 14 - Vistoriador
		-->
             
              <option value="">Selecione o nível &nbsp;&nbsp;</option>
				
              <option <?php if($edit['nivel'] && $edit['nivel'] == '1') echo' selected="selected"';?> value="1">Operacional &nbsp;&nbsp;</option>
       
              <option <?php if($edit['nivel'] && $edit['nivel'] == '2') echo' selected="selected"';?> value="2">Comercial &nbsp;&nbsp;</option>
              
              <option <?php if($edit['nivel'] && $edit['nivel'] == '3') echo' selected="selected"';?> value="3">Faturamento &nbsp;&nbsp;</option>
              
              <option <?php if($edit['nivel'] && $edit['nivel'] == '4') echo' selected="selected"';?> value="4">Financeiro &nbsp;&nbsp;</option>
              
              <option <?php if($edit['nivel'] && $edit['nivel'] == '5') echo' selected="selected"';?> value="5">Gerencial &nbsp;&nbsp;</option>
				
			  <option <?php if($edit['nivel'] && $edit['nivel'] == '6') echo' selected="selected"';?> value="6">Manifesto &nbsp;&nbsp;</option>
				
			  <option <?php if($edit['nivel'] && $edit['nivel'] == '7') echo' selected="selected"';?> value="7">DP/RH &nbsp;&nbsp;</option>
				
			  <option <?php if($edit['nivel'] && $edit['nivel'] == '8') echo' selected="selected"';?> value="8">Vendas &nbsp;&nbsp;</option>
				
			  <option <?php if($edit['nivel'] && $edit['nivel'] == '9') echo' selected="selected"';?> value="9">Manutenção / Almoxarifado &nbsp;&nbsp;</option>
				
			  <option <?php if($edit['nivel'] && $edit['nivel'] == '10') echo' selected="selected"';?> value="10">Ambiental e Patrimonial &nbsp;&nbsp;</option>
		
			  <option <?php if($edit['nivel'] && $edit['nivel'] == '11') echo' selected="selected"';?> value="11">Jurídico &nbsp;&nbsp;</option>
				
			 <option <?php if($edit['nivel'] && $edit['nivel'] == '12') echo' selected="selected"';?> value="12">Portaria &nbsp;&nbsp;</option>
				
			<option <?php if($edit['nivel'] && $edit['nivel'] == '13') echo' selected="selected"';?> value="13">Oficina &nbsp;&nbsp;</option>
				
				<option <?php if($edit['nivel'] && $edit['nivel'] == '14') echo' selected="selected"';?> value="14">Vistoriador &nbsp;&nbsp;</option>
              
            </select>
      	</div>
      
      
      <div class="box-footer">
        <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-danger"> </a>
    	<?php 
		if($acao=="atualizar"){
			echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary"/>';
		}
		if($acao=="deletar"){
			echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger"/>';	
		}
		if($acao=="cadastrar"){
			echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary"/>';
		}
		if($acao=="enviar"){
			echo '<input type="submit" name="enviar" value="Enviar" class="btn btn-primary"/>';	
		}
	 	?>  
    </div>
  
   </form>
 
	</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
 </section>

  
</div><!-- /.content-wrapper -->

