<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>
<?php 
	
		 if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autConsultor']['id'])){
				header('Location: painel.php?execute=suporte/403');
			}	
		}
		
		if(!empty($_GET['contatoBaixar'])){
			$contatoId = $_GET['contatoBaixar'];
			$acao = "baixar";
		}
		
		if(!empty($_GET['contatoDeletar'])){
			$contatoId = $_GET['contatoDeletar'];
			$acao = "deletar";
		}
	 

		if(!empty($contatoId)){
			$readorcamento = read('contato',"WHERE id = '$contatoId'");
			if(!$readorcamento){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readorcamento as $edit);
			$edit['interacao']= date('Y/m/d H:i:s');

		}
 ?>

<div class="content-wrapper">
      <section class="content-header">
              <h1>Contatos </h1>
              <ol class="breadcrumb">
                <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="#">Contatos</a></li>
                <li><a href="painel.php?execute=suporte/orcamento/solicitacoes">Contatos</a></li>
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
	
		if(isset($_POST['visualizar'])){
		 	header('Location: painel.php?execute=suporte/orcamento/orcamentos');
		}
	
		if(isset($_POST['baixar'])){
			$cad['resposta'] = mysql_real_escape_string($_POST['resposta']);
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status']='Baixado' ;
			update('contato',$cad,"id = '$contatoId'");
			
			$assunto  = "Orçamento : " . $edit['nome'];
			$msg = "<font size='2px' face='Verdana, Geneva, sans-serif' color='#08a57f'>";
			$msg .="<img src='https://www.cleaambiental.com.br/wpc/site/images/header-logo.png'> <br /><br />";
			
			$msg .= "Contato N&deg; : " . $edit['id'] . "<br />";
			$msg .= "Nome : " . $edit['nome'] . "<br />";
			$msg .= "Email : " . $edit['email'] . "<br />";
			$msg .= "Telefone : " . $edit['telefone'] . "<br />";
			$msg .= "Solicitação : " . $edit['solicitacao'] . "<br /><br />";
			$msg .= "Resposta : " . $cad['resposta'] . "<br />";
			$msg .= "Data : " . date('d/m/Y'). "<br /><br />";
			
			enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);
		
			header("Location: ".$_SESSION['url']);
		}

		
		
		if(isset($_POST['deletar'])){
				$readDeleta = read('contato',"WHERE id = '$contatoId'");
				if(!$readDeleta){
					echo '<div class="msgError">Desculpe, o registro não existe</div><br />';	
				}else{
					delete('contato',"id = '$contatoId'");
					header("Location: ".$_SESSION['url']);
				}
		}
	?>
	
  	<form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			   
                 <div class="box-header with-border">
                  <h3 class="box-title">Status do Contato</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                <div class="row">
                     <div class="form-group col-xs-12 col-md-2">  
                     <label>Id</label>
                      <input name="id"  class="form-control" type="text" value="<?php echo $edit['id'];?>" disabled/>
                     </div>
                     <div class="form-group col-xs-12 col-md-5">  
                       <label>Status</label>
                       <input type="text" name="status" value="<?php echo $edit['status'];?>" class="form-control" readonly  /> 
                     </div>
                     <div class="form-group col-xs-12 col-md-5">  
                    <label>Interação</label>
                    <input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" class="form-control"  disabled /> 
                   </div>
                 </div><!-- /.row -->
                </div><!-- /.box-body -->
                

                <div class="box-header with-border">
                  <h3 class="box-title">Dados do Contato</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div class="row">
                     <div class="form-group col-xs-12 col-md-2">  
       					<label>Data</label>
   						<input name="data" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['orc_data']));?>" class="form-control" readonly  /> 
               		</div>
                     <div class="form-group col-xs-12 col-md-6"> 
						<label>Nome</label>
   						<input type="text" name="nome" value="<?php echo $edit['nome'];?>" class="form-control" readonly  /> 
                	</div>
                      <div class="form-group col-xs-12 col-md-4"> 
						<label>Email</label>
   						<input type="text" name="nome" value="<?php echo $edit['email'];?>" class="form-control" readonly  /> 
                	</div>
                     <div class="form-group col-xs-12 col-md-12"> 
						<label>Telefone</label>
               		 <input type="text" name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control" readonly  /> 
			   		</div>
       			 </div><!-- /.row -->
                </div><!-- /.box-body -->
                
                <div class="box-header with-border">
                 	 <h3 class="box-title">Solicitaçao</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
               		 <div class="row">
                     <div class="form-group col-xs-12 col-md-12"> 
                        <label>Solicitaçao :</label>
                        <textarea name="solicitacao" cols="120" rows="5" class="form-control"> <?php echo $edit['solicitacao'];?></textarea>
                    </div><!-- /.col-md-12 -->
                 </div><!-- /.row -->
                </div><!-- /.box-body -->
                
                  <div class="box-header with-border">
                 	 <h3 class="box-title">Resposta do Contato</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
               		 <div class="row">
                     <div class="form-group col-xs-12 col-md-12"> 
                        <label>Resposta :</label>
                        <textarea name="resposta" cols="120" rows="5" class="form-control"> <?php echo $edit['resposta'];?></textarea>
                    </div><!-- /.col-md-12 -->
                 </div><!-- /.row -->
                </div><!-- /.box-body -->
                
               <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
                 <?php 
                    echo '<input type="submit" name="baixar" value="Baixar-Enviar" class="btn btn-primary" />';	
                    echo '<input type="submit" name="deletar" value="Excluir" class="btn btn-danger" />'; 

                ?>
             </div> <!-- /.box-footer -->
          
      </form>
      
    </div><!-- /.box-body -->
  </div><!--/box box-default-->
 </section><!-- /.content -->
 
  
 

</div><!-- /.content-wrapper -->
           
           	 

