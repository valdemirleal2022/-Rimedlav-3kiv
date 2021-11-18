<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		if(!empty($_GET['materialEditar'])){
			$materialId = $_GET['materialEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['materialDeletar'])){
			$materialId = $_GET['materialDeletar'];
			$acao = "deletar";
		}
		if(!empty($materialId)){
			$readmaterial = read('estoque_material',"WHERE id = '$materialId'");
			if(!$readmaterial){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readmaterial as $edit);
		}

 ?>
 
<div class="content-wrapper">
  <section class="content-header">
          <h1>Material</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Estoque</a></li>
            <li><a href="painel.php?execute=suporte/estoque/material-editar">Material</a></li>
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
			
			$cad['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['codigo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo'])));
			$cad['id_tipo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_tipo'])));
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				$cad['estoque'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estoque'])));
				$cad['estoque_minimo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['estoque_minimo'])));
				$cad['valor_unitario'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
				$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
				$cad['localizacao'] = strip_tags(trim(mysql_real_escape_string($_POST['localizacao'])));
				
				$cad['status']   = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
				$cad['unidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['unidade'])));
				
				update('estoque_material',$cad,"id = '$materialId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				unset($cad);
				header('Location: painel.php?execute=suporte/estoque/materiais');
				
			}
			
		}
			 
		if(isset($_POST['cadastrar'])){
			
			$edit['nome'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$edit['codigo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['codigo'])));
		
			$edit['id_tipo'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_tipo'])));
			
			if(in_array('',$edit)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				$edit['estoque'] = strip_tags(mysql_real_escape_string($_POST['estoque']));
				$edit['estoque_minimo'] = strip_tags(mysql_real_escape_string($_POST['estoque_minimo']));
		$edit['valor_unitario'] 	= strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
		$edit['valor_unitario'] = str_replace(",",".",str_replace(".","",$edit['valor_unitario']));
				$cad['localizacao'] = strip_tags(trim(mysql_real_escape_string($_POST['localizacao'])));
				$edit['unidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['unidade'])));
				
				$cad['status']   = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
				
				create('estoque_material',$edit);	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				unset($cad);
				header('Location: painel.php?execute=suporte/estoque/materiais');
				
			}
		}
			 
		if(isset($_POST['deletar'])){
			delete('estoque_material',"id = '$materialId'");
			$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
			header('Location: painel.php?execute=suporte/estoque/materiais');
		}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			<div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
   
      		<div class="form-group col-xs-12 col-md-2"> 
       		   <label>Código</label>
               <input type="text" name="codigo" value="<?php echo $edit['codigo'];?>"  class="form-control"/>
            </div> 
		
		    <div class="form-group col-xs-12 col-md-9"> 
       		   <label>Nome</label>
               <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
            </div> 
           
		
            <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Estoque</label>
               <input type="text" name="estoque"  align="right" value="<?php echo $edit['estoque'];?>"  class="form-control"/>
            </div> 
           
            <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Estoque Mínimo</label>
               <input type="text" name="estoque_minimo"  align="right" value="<?php echo $edit['estoque_minimo'];?>"  class="form-control"/>
            </div> 
		
			 <div class="form-group col-xs-12 col-md-2"> 
       		   <label>Unidade</label>
               <input type="text" name="unidade"  align="right" value="<?php echo $edit['unidade'];?>"  class="form-control"/>
            </div> 
  
           
           <div class="form-group col-xs-12 col-md-2">  
          		<label>Valor Unitário</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor_unitario" class="form-control pull-right" value="<?php echo converteValor($edit['valor_unitario']);?>" <?php echo $readonly;?> />
                 </div><!-- /.input group -->
           </div>
		
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Tipo</label>
                <select name="id_tipo" class="form-control">
                    <option value="">Selecione um Tipo</option>
                    <?php 
                        $readSuporte = read('estoque_material_tipo',"WHERE id ORDER BY codigo ASC");
                        if(!$readSuporte){
                            echo '<option value="">Não temos tipo no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_tipo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].' '.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['codigo'].' '.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		 </div> 
		
		   <div class="form-group col-xs-12 col-md-10"> 
       		   <label>Localização</label>
               <input type="text" name="localizacao" value="<?php echo $edit['localizacao'];?>"  class="form-control"/>
            </div> 
		
		 <div class="form-group col-xs-12 col-md-2"> 
			  <label>Status </label>
				<select name="status" class="form-control">
				  <option value="">Selecione o status &nbsp;&nbsp;</option>

				  <option <?php if($edit['status'] && $edit['status'] == '1') echo' selected="selected"';?> value="1"> Ativo &nbsp;&nbsp;</option>

				  <option <?php if($edit['status'] && $edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo &nbsp;&nbsp;</option>
				 </select>
			</div>
           

   		<div class="form-group col-xs-12 col-md-12">  

             <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
                      <?php 
                        if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-primary" />';	
                        }
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