<?php 

		if ( function_exists( 'ProtUser' ) ) {
			if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
				header( 'Location: painel.php?execute=suporte/403' );
			}
		}

		$acao = "cadastrar";
		
		if(!empty($_GET['contaEditar'])){
			$contaId = $_GET['contaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['contaDeletar'])){
			$contaId = $_GET['contaDeletar'];
			$acao = "delelar";
		}
		if(!empty($contaId)){
			$readpagar_conta = read('pagar_conta',"WHERE id = '$contaId'");
			if(!$readpagar_conta){
				header('Location: painel.php?execute=suporte/error');	
			  }else{	
			}
			foreach($readpagar_conta as $edit);
		}

 
	?>
    
<div class="content-wrapper">
  <section class="content-header">
          <h1>Contas</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Pagar</a></li>
            <li><a href="painel.php?execute=suporte/pagar/bancos">Contas</a></li>
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
		  
		  
		  <?php
		
		  
		if(isset($_POST['atualizar'])){
			$cad['codigo'] = mysql_real_escape_string($_POST['codigo']);
			$cad['nome'] = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['previsao'] = mysql_real_escape_string($_POST['previsao']);
			$cad['id_grupo'] = mysql_real_escape_string($_POST['id_grupo']);
			$cad['status'] = mysql_real_escape_string($_POST['status']);
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				update('pagar_conta',$cad,"id = '$contaId'");	
				unset($cad);
			   header("Location: ".$_SESSION['url']);
			}
		}
		  
		if(isset($_POST['cadastrar'])){
			$cad['codigo'] = mysql_real_escape_string($_POST['codigo']);
			$cad['nome'] = strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['previsao'] = mysql_real_escape_string($_POST['previsao']);
			$cad['id_grupo'] = mysql_real_escape_string($_POST['id_grupo']);
			$cad['status'] = mysql_real_escape_string($_POST['status']);
			if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				create('pagar_conta',$cad);	
				header('Location: painel.php?execute=suporte/pagar/pagar-contas');
				unset($cad);
			}
		}
		if(isset($_POST['excluir'])){
				$readDeleta = read('pagar_conta',"WHERE id = '$contaId'");
				if(!$readDeleta){
					echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div>';	
				}else{
					delete('pagar_conta',"id = '$contaId'");
					header('Location: painel.php?execute=suporte/pagar/pagar-contas');
				}
			}

			   
		?>

	
   		 <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 			
            <div class="box-body">
            
                
                    <div class="form-group col-xs-12 col-md-1"> 
                       <label>Id</label>
                       <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
                    </div> <!-- /box-tools-->
				
				    <div class="form-group col-xs-12 col-md-1"> 
                       <label>Codigo</label>
                       <input name="codigo" type="text" value="<?php echo $edit['codigo'];?>" class="form-control"  />
                    </div> <!-- /box-tools-->
				
           			  <div class="form-group col-xs-12 col-md-10"> 
                       <label>Descricao</label>
                       <input type="text" name="nome" value="<?php echo $edit['nome'];?>"  class="form-control"/>
                    </div> <!-- /box-tools-->
           	
           	 
                     <div class="form-group col-xs-12 col-md-8">  
                        <label>Grupo </label>
                            <select name="id_grupo" class="form-control">
                                <option value="">Grupo</option>
                                <?php 
                                    $readConta = read('pagar_grupo',"WHERE id ORDER BY codigo ASC");
                                    if(!$readConta){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readConta as $mae):
                                           if($edit['id_grupo'] == $mae['id']){
											  echo '<option value="'.$mae['id'].'"selected="selected">'
												  .$mae['codigo']. '|'.$mae['nome'].
												  '</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">' .$mae['codigo']. '|'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>
                      </div> <!-- /box-tools-->
				
				   <div class="form-group col-xs-12 col-md-4"> 
                       <label>Previsão R$</label>
                       <input type="text" name="previsao" value="<?php echo $edit['previsao'];?>"  class="form-control"/>
                    </div> <!-- /box-tools-->
				
				<div class="form-group col-xs-12 col-md-2"> 
						  <label>Status (*)</label>
							<select name="status" class="form-control">
							  <option value="">Selecione o status &nbsp;&nbsp;</option>

							  <option <?php if($edit['status'] && $edit['status'] == '1') echo' selected="selected"';?> value="1"> Ativo &nbsp;&nbsp;</option>

							  <option <?php if($edit['status'] && $edit['status'] == '0') echo' selected="selected"';?> value="0">Inativo &nbsp;&nbsp;</option>
							 </select>
		    </div>
           	 
            </div><!-- /box-body-->
  			 
       	
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
   
  	 
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

