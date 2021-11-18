<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}
		}
		if(!empty($_GET['suporteEditar'])){
			$suporteId = $_GET['suporteEditar'];
		}
		
		$readsuporte = read('pedido',"WHERE id = '$suporteId'");
		if(!$readsuporte){
			header('Location: painel.php?execute=suporte/error');	
		}
		foreach($readsuporte as $edit);

		$clienteId = $edit['id_cliente'];
		$readCliente = read('cliente',"WHERE id = '$clienteId'");
		if($readCliente ){
			foreach($readCliente as $cliente);
		}else{
		 $cliente['nome']='Cliente Exluido';
	   }
 ?>

 <div class="content-wrapper">
  <section class="content-header">
          <h1>Atendimento</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=suporte/atendimento/pedidos">Atendimento</a></li>
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
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
            <span>Clientes</span>
                <select name="id_cliente" class="form-control" disabled>
                    <option value="">Selecione um Cliente</option>
                    <?php 
                        $readSuporte = read('cliente',"WHERE id AND status='1' ORDER BY nome");
                        if(!$readSuporte){
                            echo '<option value="">Não temos Suporte no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['id_cliente'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
      		 </div> 
    
    
      		<div class="form-group col-xs-12 col-md-3">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>

            <div class="form-group col-xs-12 col-md-5">  
            <span>Motivo do Atendimento</span>
                <select name="id_suporte" class="form-control" disabled>
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
                  <input type="date" name="data_solicitacao" class="form-control" disabled value="<?php echo $edit['data_solicitacao'];?>"/>
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
       		   <label>Usuário :</label>
               <input type="text" name="usuario" value="<?php echo $edit['usuario'];?>" class="form-control" disabled />
             </div> 
           
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>Solicitação</label>
                <textarea name="solicitacao" rows="5" cols="100" class="form-control" disabled /><?php echo $edit['solicitacao'];?></textarea>
         </div>  

         <div class="form-group col-xs-12 col-md-12"> 
               <span>Solução:</span>
           </div> 
           
            
             <div class="form-group col-xs-12 col-md-4">  
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data_solucao" class="form-control" disabled value="<?php echo $edit['data_solucao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-4">  
                 <label>Hora</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-clock-o "></i>
                       </div>
                  <input type="text" name="hora_solucao" class="form-control" disabled value="<?php echo $edit['hora_solucao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
            <div class="form-group col-xs-12 col-md-4">  
       		   <label>Técnico</label>
               <input type="text" name="tecnico" value="<?php echo $edit['tecnico'];?>"  class="form-control" disabled />
             </div> 

            
             <div class="form-group col-xs-12 col-md-12"> 
              <label>Solução</label>
                <textarea  name="solucao" rows="5" cols="100" class="form-control" disabled /><?php echo $edit['solucao'];?></textarea>
        	 </div>  

			 <div class="form-group col-xs-12 col-md-12">  
                <div class="box-footer">
                   <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
                     <?php 
                        if($acao=="atualizar"){
                            echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                        }
                        if($acao=="deletar"){
                            echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
                        }
						 if($acao=="baixar"){
                            echo '<input type="submit" name="baixar" value="Baixar"  class="btn btn-success" />';	
                        }
                        if($acao=="cadastrar"){
                            echo '<input type="submit" name="abrir" value="Abrir Suporte" class="btn btn-primary" />';
							echo '<input type="submit" name="registrar" value="Registrar Suporte" class="btn btn-primary" />';	
                        }
                     ?>  
                 </div>
             </div>
    </form>
   		
   	</div><!-- /.box-body -->
  </div><!-- /.box box-default -->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
			 