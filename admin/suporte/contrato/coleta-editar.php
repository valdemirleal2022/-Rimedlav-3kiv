	<?php 


		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		if(!empty($_GET['coletaEditar'])){
			$coletaId = $_GET['coletaEditar'];
			$acao = "atualizar";
		}

		if(!empty($_GET['coletaDeletar'])){
			$coletaId = $_GET['coletaDeletar'];
			$acao = "deletar";
		}

		if(!empty($_GET['coletaVisualizar'])){
			$coletaId = $_GET['coletaVisualizar'];
			$acao = "visualizar";
		}
		
		if(!empty($_GET['contratoId'])){
			
			$contratoId = $_GET['contratoId'];
			$acao = "cadastrar";
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			if(!$contrato){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			 
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			if(!$cliente){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			$acao = "cadastrar";
			
			$edit['inicio']=$_SESSION['dataInicio'];
			$vencimento=$edit['inicio'];
			$edit['vencimento'] = somarMes($vencimento,'12');

		}
		
		if(!empty($coletaId)){
			
			$edit = mostra('contrato_coleta',"WHERE id = '$coletaId'");
			if(!$edit){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
		
			$contratoId = $edit['id_contrato'];
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			if(!$contrato){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			 
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			if(!$cliente){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
		}
		
		
		$_SESSION['aba']=1;
 ?>
 


<div class="content-wrapper">
     <section class="content-header">
         <h1>Contrato</h1>
         <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=suporte/contrato/contrato-editar">Coleta</a></li>
             <li class="active">Editar</li>
          </ol>
      </section>
      
	 <section class="content">
         <div class="box box-default">
           <div class="box-header with-border">
            	 <?php require_once('cliente-modal.php');?>
               	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->

	<div class="box-body table-responsive">
	<?php 
	
		if(isset($_POST['visualizar'])){
		 	header("Location: ".$_SESSION['contrato-editar']);
		}
		
		if(isset($_POST['cadastrar'])){
			
			$cad['id_contrato'] = $contratoId;
			$cad['id_cliente'] = $clienteId;
			$cad['tipo_coleta'] = mysql_real_escape_string($_POST['tipo_coleta']);
			$cad['quantidade'] = mysql_real_escape_string($_POST['quantidade']);
			$cad['valor_unitario']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
			$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
			$cad['valor_extra']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_extra'])));
			$cad['valor_extra'] = str_replace(",",".",str_replace(".","",$cad['valor_extra']));
			$cad['valor_tratamento']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_tratamento'])));
			$cad['valor_tratamento'] = str_replace(",",".",str_replace(".","",$cad['valor_tratamento']));
			$cad['valor_mensal']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal'])));
			$cad['valor_mensal'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal']));
			$cad['quantidade_mensal'] = mysql_real_escape_string($_POST['quantidade_mensal']);
			$cad['inicio'] = mysql_real_escape_string($_POST['inicio']);
			$cad['vencimento'] = mysql_real_escape_string($_POST['vencimento']);
			$cad[ 'interacao' ] = date( 'Y/m/d H:i:s' );
			create('contrato_coleta',$cad);	
			
			$interacao='Cadastro do Tipo de Coleta';
			interacao($interacao,$contratoId);	
			
			header("Location: ".$_SESSION['contrato-editar']);
		}
		
		if(isset($_POST['atualizar'])){
			
			$cad['tipo_coleta'] = mysql_real_escape_string($_POST['tipo_coleta']);
			$cad['quantidade'] = mysql_real_escape_string($_POST['quantidade']);
			$cad['valor_unitario']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_unitario'])));
			$cad['valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['valor_unitario']));
			$cad['valor_extra']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_extra'])));
			$cad['valor_extra'] = str_replace(",",".",str_replace(".","",$cad['valor_extra']));
			$cad['valor_tratamento']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_tratamento'])));
			$cad['valor_tratamento'] = str_replace(",",".",str_replace(".","",$cad['valor_tratamento']));
			$cad['valor_mensal']		= strip_tags(trim(mysql_real_escape_string($_POST['valor_mensal'])));
			$cad['valor_mensal'] = str_replace(",",".",str_replace(".","",$cad['valor_mensal']));
			$cad['quantidade_mensal'] = mysql_real_escape_string($_POST['quantidade_mensal']);
			$cad['inicio'] = mysql_real_escape_string($_POST['inicio']);
			$cad['vencimento'] = mysql_real_escape_string($_POST['vencimento']);
			$cad[ 'interacao' ] = date( 'Y/m/d H:i:s' );
			
			$interacao='Alteração do Tipo de Coleta';
			interacao($interacao,$contratoId);	
			
			update('contrato_coleta',$cad,"id = '$coletaId'");	
			header("Location: ".$_SESSION['contrato-editar']);
		}

		if(isset($_POST['deletar'])){
			delete('contrato_coleta',"id = '$coletaId'");
			
			$interacao='Exclusão do Tipo de Coleta';
			interacao($interacao,$contratoId);	
			
			header("Location: ".$_SESSION['contrato-editar']);
		}
		
	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 				
                <div class="box-header with-border">
                  <h3 class="box-title">Status do Contrato</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                
                <div class="row">
                    
                    <div class="form-group col-xs-12 col-md-1">  
                     <label>Id</label>
                      <input name="id"  class="form-control" type="text" value="<?php echo $edit['id'];?>" disabled/>
                     </div>
                     
                     <div class="form-group col-xs-12 col-md-2">  
                   		 <label>Interaçao</label>
                  	 	 <input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control"  disabled /> 
                     </div>
          
                   <div class="form-group col-xs-12 col-md-3">  
                       <label>Tipo de Coleta</label>
                      <select name="tipo_coleta"  class="form-control" >
                            <option value="">Selecione tipo de coleta</option>
                                <?php 
                                    $leitura = read('contrato_tipo_coleta',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos tipo de coleta no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['tipo_coleta'] == $mae['id']){
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
						<label>Quantidade</label>
						<input type="text" name="quantidade" style="text-align:right" value="<?php echo $edit['quantidade'];?>" class="form-control" >
				   </div> 
                    
                   <div class="form-group col-xs-12 col-md-2"> 
						<label>Valor Unitário</label>
						<input type="text" name="valor_unitario" style="text-align:right" value="<?php echo converteValor($edit['valor_unitario']);?>" class="form-control" >
				   </div> 
                    
                   <div class="form-group col-xs-12 col-md-2"> 
						<label>Valor Extra</label>
						<input type="text" name="valor_extra" style="text-align:right" value="<?php echo converteValor($edit['valor_extra']);?>" class="form-control" >
				   </div> 
                     
                   <div class="form-group col-xs-12 col-md-4"> 
						<label>Valor Mensal</label>
						<input type="text" name="valor_mensal" style="text-align:right" value="<?php echo converteValor($edit['valor_mensal']);?>" class="form-control" >
				   </div> 
					
					
				<div class="form-group col-xs-12 col-md-4"> 
						<label>Quantidade Minima  Mensal</label>
						<input type="text" name="quantidade_mensal" style="text-align:right" value="<?php echo $edit['quantidade_mensal'];?>" class="form-control" >
				   </div> 
                     
                  <div class="form-group col-xs-12 col-md-4"> 
					<label>Início</label>
					<input type="date" name="inicio" value="<?php echo $edit['inicio'];?>"  class="form-control" >
				  </div> 
				   
				  <div class="form-group col-xs-12 col-md-4"> 
					<label>Término </label>
					<input type="date" name="vencimento" value="<?php echo $edit['vencimento'];?>"  class="form-control" >
				  </div> 
			
               </div><!-- /.row -->
       </div><!-- /.box-body -->
          
          
           
       	  <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>	
				 <?php 
		
					if($acao=="atualizar"){
						 echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
					}
			  
			  		if($acao=="cadastrar"){
						 echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';
					}
					
					if($acao=="deletar"){
						 echo '<input type="submit" name="deletar" value="Excluir" class="btn btn-danger" />';
					}
				?>

				 </div> <!-- /.box-footer -->
		  </form>
		  
		  
		     
    </div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
  
   

</div><!-- /.content-wrapper -->
  

