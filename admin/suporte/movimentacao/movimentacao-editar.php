<?php 
		
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}
		
		$acao = "cadastrar";
		if(!empty($_GET['movimentacaoDeletar'])){
			$movimentacaoId = $_GET['movimentacaoDeletar'];
			$acao = "excluir";
		}
		if(!empty($_GET['movimentacaoEditar'])){
			$movimentacaoId = $_GET['movimentacaoEditar'];
			$acao = "atualizar";
		}
		 
		if(!empty($movimentacaoId)){
			$readmovimentacao = read('movimentacao',"WHERE id = '$movimentacaoId'");
			if($readmovimentacao){
			    foreach($readmovimentacao as $edit);	
			}
		}
		
?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Saldo Inicial</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Suporte</li>
            <li><a href="painel.php?execute=suporte/cliente/cliente-receber&clienteId=<?PHP print $clienteId; ?>">
              	Movimentação</a>
            </li>
            <li class="active">Saldo</li>
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
			$cad['banco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
			$cad['saldo']  		= strip_tags(trim(mysql_real_escape_string($_POST['saldo'])));
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div><br />';
			  }else{
				  create('movimentacao',$cad);	
				  header("Location: ".$_SESSION['movimentacoes']);
			  }
		   }
		   
		   if(isset($_POST['atualizar'])){
			$cad['data']		= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$cad['banco'] 	= strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
			$cad['saldo']  		= strip_tags(trim(mysql_real_escape_string($_POST['saldo'])));
			if(in_array('',$cad)){
				echo '<div class="msgError">Todos os campos são obrigatórios!</div>'.'<br>';	
			  }else{
				update('movimentacao',$cad,"id = '$movimentacaoId'");	
				header("Location: ".$_SESSION['movimentacoes']);
			}
		}
			
			if(isset($_POST['excluir'])){
				$readDeleta = read('movimentacao',"WHERE id = '$movimentacaoId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('movimentacao',"id = '$movimentacaoId'");
					header("Location: ".$_SESSION['movimentacoes']);
				}
			}
			
	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
 
              <div class="form-group col-xs-12 col-md-5"> 
               <span>Id :</span>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  class="form-control" readonly  />
           </div> 
             
              <div class="form-group col-xs-12 col-md-5"> 

            <span>Selecione um Banco :</span>
                <select name="banco" class="form-control">
                    <option value="">Selecione um Banco</option>
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
       		   <span>Data :</span>
               <input type="date" name="data" value="<?php echo $edit['data'];?>"  class="form-control" />
            </div> 
             
              <div class="form-group col-xs-12 col-md-5"> 
                 <span>Saldo R$ :</span>
                 <input type="text" name="saldo" value="<?php echo $edit['saldo'];?>"  class="form-control" />
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