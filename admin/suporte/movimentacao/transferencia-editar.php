<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		$acao = "cadastrar";
		$edit['data'] = date('Y-m-d');
		if(!empty($_GET['transferenciaDeletar'])){
			$transferenciaId = $_GET['transferenciaDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['transferenciaEditar'])){
			$transferenciaId = $_GET['transferenciaEditar'];
			$acao = "atualizar2";
		}
		if(!empty($transferenciaId)){
			$readtransferencia = read('transferencia',"WHERE id = '$transferenciaId'");
			if($readtransferencia){
			    foreach($readtransferencia as $edit);	
			}
		}
		
?>
<div class="content-wrapper">
  <section class="content-header">
          <h1>Transferência</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Suporte</li>
            <li><a href="painel.php?execute=suporte/cliente/cliente-receber&clienteId=<?PHP print $clienteId; ?>">
              	Movimentação</a>
            </li>
            <li class="active">Transferência</li>
          </ol>
  </section>
  <section class="content">
      <div class="box box-warning">
       <div class="box-header">
          
      </div><!-- /.box-header -->

     <div class="box-body">
    
	<?php 
	
		  if(isset($_POST['cadastrar'])){
			$cad['data']		= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['bancocredito']= strip_tags(trim(mysql_real_escape_string($_POST['bancocredito'])));
			$cad['bancodebito'] = strip_tags(trim(mysql_real_escape_string($_POST['bancodebito'])));
			$cad['valor']		= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
			$cad['valor'] 		= str_replace(",",".",str_replace(".","",$cad['valor']));
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
			  }else{
				  $cre['data']		= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
				  $cre['banco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['bancocredito'])));
				  $cre['credito']  	= $cad['valor'];
				  create('transferencia',$cre);
				
				  // INTERAÇÃO
				  $servicoId='-';
				  $interacao='Transferência entre Conta ';
				  interacao($interacao,$servicoId);
				
				  $deb['data']		= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
				  $deb['banco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['bancodebito'])));
				  $deb['debito']  	=$cad['valor'];
				  create('transferencia',$deb);	
				  $_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
							
				  header('Location: painel.php?execute=suporte/movimentacao/transferencias');  
			  }
		   }
		   
		   if(isset($_POST['atualizar'])){
			$cad['data']		= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['banco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
			   
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				update('transferencia',$cad,"id = '$transferenciaId'");	
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header('Location: painel.php?execute=suporte/movimentacao/transferencias'); 
			}
		}
			
			if(isset($_POST['deletar'])){
				$readDeleta = read('transferencia',"WHERE id = '$transferenciaId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('transferencia',"id = '$transferenciaId'");
					header('Location: painel.php?execute=suporte/movimentacao/transferencias'); 
				}
			}
			
	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
  			 <div class="form-group col-xs-12 col-md-2">  
               <label>Id :</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
             </div> 
             
              <div class="form-group col-xs-12 col-md-5"> 
              <label>Debitar</label>
                <select name="bancodebito" class="form-control">
                    <option value="">Selecione Banco</option>
                    <?php 
                        $readBanco = read('banco',"WHERE id");
                        if(!$readBanco){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($edit['banco'] == $mae['id']){
								echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
							 }else{
                               	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
							}
                          endforeach;	
                        }
                    ?> 
                </select>
            </div>
            <div class="form-group col-xs-12 col-md-5"> 
             <label>Créditar</label>
                <select name="bancocredito" class="form-control">
                    <option value="">Selecione Banco</option>
                    <?php 
                        $readBanco = read('banco',"WHERE id");
                        if(!$readBanco){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($edit['banco'] == $mae['id']){
								echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
							 }else{
                               	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
							}
                            endforeach;	
                        }
                    ?> 
                </select>
           </div>
          <div class="form-group col-xs-12 col-md-6">  
                 <label>Data</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                 </div><!-- /.input group -->
           </div>
          <div class="form-group col-xs-12 col-md-6">  
          	<label>Valor</label>
             <div class="input-group">
                 <div class="input-group-addon">
                    <i class="fa fa-credit-card"></i>
                 </div>
        <input type="text" name="valor" class="form-control pull-right" value="<?php echo converteValor($edit['valor']);?>"/>
                 </div><!-- /.input group -->
           </div>
            
      		          
     <div class="form-group col-xs-12 col-md-12"> 
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
   
		</div><!-- /.box-body -->
   </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->