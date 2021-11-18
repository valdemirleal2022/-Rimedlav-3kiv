<?php 
	 
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autCliente']['id'])){
			header('Location: painel.php');	
		}	
	}
	 	
	$clienteId = $_SESSION['autCliente']['id'];
	$readCliente = read('cliente',"WHERE id = '$clienteId'");
	if(!$readCliente){
			header('Location: painel.php?execute=suporte/naoEncontrado');
	}
	foreach($readCliente as $cliente);

	$contrato = mostra('contrato',"WHERE id AND id_cliente='$clienteId'");
	$contratoId = $contrato['id'];

	$edit['hora_solicitacao'] = date("H:i");
	$edit['data_solicitacao'] = date('Y-m-d');
	$edit['status'] = "Aguardando";

?>

 <div class="content-wrapper">
  <section class="content-header">
          <h1>Atendimento ao Cliente</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=suporte/pedidos">Atendimento ao Cliente</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $cliente['nome']  ;?></small></h3>
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
if(isset($_POST['abrir'])){
	$cad['hora_solicitacao'] = date("H:i");
	$cad['status'] = "Aguardando";
	$cad['solicitacao'] =strip_tags(trim(mysql_real_escape_string($_POST['solicitacao'])));
	$cad['data_solicitacao'] = date('Y-m-d');
	$cad['usuario'] = strip_tags(trim(mysql_real_escape_string($_POST['usuario'])));
	$cad['id_suporte']=strip_tags(trim(mysql_real_escape_string($_POST['id_suporte'])));
	$cad['id_cliente'] = $clienteId;
	$cad['id_contrato'] = $contratoId;
	$cad['cliente_solicitou']  	= '1';
	if(in_array('',$cad)){
		echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
		
	 }else{
		create('pedido',$cad);
		$assunto  = "Atendimento ao Cliente : " . $cliente['nome'];
		$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#000099'>";
		$msg.="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'>
						<br/><br />";
		$msg .= "Cliente : " . $cliente['nome'] . "<br />";
		$msg .= "Usuário : " . $cad['usuario'] . "<br />";
		$msg .= "Solicitação : " . $cad['solicitacao'] . "<br />";
		$msg .= "Data da Solicitação : " . converteData($cad['data_solicitacao']) . "<br />";
		$msg .=  "</font>";
		enviaEmail($assunto,$msg,MAILUSER,SITENOME,$cliente['email'],$cliente['nome']);
		enviaEmail($assunto,$msg,$cliente['email'],$cliente['nome'],MAILUSER,SITENOME);
		$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
		unset($cad);
		header("Location: ".$_SESSION['url']);
	}
}
?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

         <div class="form-group col-xs-12 col-md-5">  
            <span>Motivo do Atendimento </span>
                <select name="id_suporte" class="form-control">
                    <option value="">Selecione um Motivo</option>
                    <?php 
                        $readSuporte = read('pedido_suporte',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_suporte'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		 </div> 
            
      		<div class="form-group col-xs-12 col-md-4">  
       		   <label>Status</label>
               <input type="text" name="status" value="<?php echo $edit['status'];?>" class="form-control" disabled />
             </div> 
             
            <div class="form-group col-xs-12 col-md-4">  
             <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="text" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>"/>
               </div><!-- /.input group -->
           </div> 
           
           <div class="form-group col-xs-12 col-md-4">  
                 <label>Hora</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_solicitacao" class="form-control" disabled value="<?php echo $edit['hora_solicitacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
          <div class="form-group col-xs-12 col-md-4">  
       		   <label>Contato</label>
               <input type="text" name="usuario" value="<?php echo $edit['usuario'];?>" class="form-control" />
            </div> 
           
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Solicitação</label>
  <textarea name="solicitacao" rows="5" cols="100" class="form-control" /><?php echo $edit['solicitacao'];?></textarea>
         </div>  

                       
	 	<div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                		echo '<input type="submit" name="abrir" value="Abrir Atendimento" class="btn btn-primary" />';
                     ?>  
                 </div>
             </div>
    </form>
   		
   	</div><!-- /.box-body -->
  </div><!-- /.box box-default -->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
			 