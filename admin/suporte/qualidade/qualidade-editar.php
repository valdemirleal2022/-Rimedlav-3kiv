<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php?execute=suporte/403');	
			}	
		}

	 	$acao = "cadastrar";
		$edit['status'] = "Aguardando";
		$edit['data']=   date("Y-m-d");
	 			
		if(!empty($_GET['qualidadeEditar'])){
			$qualidadeId = $_GET['qualidadeEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['qualidadeDeletar'])){
			$qualidadeId = $_GET['qualidadeDeletar'];
			$acao = "deletar";
		}
		if(!empty($_GET['qualidadeBaixar'])){
			$qualidadeId = $_GET['qualidadeBaixar'];
			$acao = "baixar";
		}

		if(!empty($qualidadeId)){
			$readsuporte = read('qualidade',"WHERE id = '$qualidadeId'");
			if(!$readsuporte){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
			foreach($readsuporte as $edit);
		}

		if($acao=="baixar"){
			$edit['status'] = "Ok";
			$edit['fechamento']=   date("d-m-Y");
		 
		}
		
 		
 ?>
 
 <div class="content-wrapper">
  <section class="content-header">
          <h1>Qualidade</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Cliente</a></li>
            <li><a href="painel.php?execute=suporte/qualidade/qualidade">Qualidade</a></li>
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
                     <?php if($acao=='baixar') echo 'Baixando';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
	
     	 <div class="box-body">

		<?php 
		
			if(isset($_POST['atualizar'])){
				
				$edit['identificacao'] = strip_tags(trim(mysql_real_escape_string($_POST['identificacao'])));
				$edit['data'] = mysql_real_escape_string($_POST['data']);
				$edit['setor'] = mysql_real_escape_string($_POST['setor']);
				$edit['item']= strip_tags(trim(mysql_real_escape_string($_POST['item'])));
				$edit['origem'] = strip_tags(trim(mysql_real_escape_string($_POST['origem'])));
				$edit['acao_corretiva'] = mysql_real_escape_string($_POST['acao_corretiva']);
					$edit['causa'] = mysql_real_escape_string($_POST['causa']);
					$edit['causa_principal'] = mysql_real_escape_string($_POST['causa_principal']);
					$edit['responsavel'] = mysql_real_escape_string($_POST['responsavel']);
					$edit['previsao'] = mysql_real_escape_string($_POST['previsao']);
					$edit['implantacao'] = mysql_real_escape_string($_POST['implantacao']);
					$edit['acompanhamento'] = mysql_real_escape_string($_POST['acompanhamento']);
					$edit['reincidencia'] = mysql_real_escape_string($_POST['reincidencia']);
					$edit['fechamento'] = mysql_real_escape_string($_POST['fechamento']);
					$edit['responsavel_fechamento'] = mysql_real_escape_string($_POST['responsavel_fechamento']);
					$edit['status'] = "Aguardando";
				
				update('qualidade',$edit,"id = '$qualidadeId'");	
				
				$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
				header("Location: ".$_SESSION['url']);
			}
		
			if(isset($_POST['baixar'])){
				$cad['status']		='OK';
			 
				if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					
					update('qualidade',$cad,"id = '$qualidadeId'");	
					$_SESSION['cadastro'] = '<div class="alert alert-success">Baixado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
				 }
			}
			
			if(isset($_POST['abrir'])){
				  	
				$edit['identificacao'] = strip_tags(trim(mysql_real_escape_string($_POST['identificacao'])));
				$edit['data'] = mysql_real_escape_string($_POST['data']);
				$edit['setor'] = mysql_real_escape_string($_POST['setor']);
				$edit['item']= strip_tags(trim(mysql_real_escape_string($_POST['item'])));
				$edit['origem'] = strip_tags(trim(mysql_real_escape_string($_POST['origem'])));
				
				
				 if(in_array('',$edit)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';
				  }else{
					 
					$edit['acao_corretiva'] = mysql_real_escape_string($_POST['acao_corretiva']);
					$edit['causa'] = mysql_real_escape_string($_POST['causa']);
					$edit['causa_principal'] = mysql_real_escape_string($_POST['causa_principal']);
					$edit['responsavel'] = mysql_real_escape_string($_POST['responsavel']);
					$edit['previsao'] = mysql_real_escape_string($_POST['previsao']);
					$edit['implantacao'] = mysql_real_escape_string($_POST['implantacao']);
					$edit['acompanhamento'] = mysql_real_escape_string($_POST['acompanhamento']);
					$edit['reincidencia'] = mysql_real_escape_string($_POST['reincidencia']);
					$edit['fechamento'] = mysql_real_escape_string($_POST['fechamento']);
					$edit['responsavel_fechamento'] = mysql_real_escape_string($_POST['responsavel_fechamento']);
					$edit['status'] = "Aguardando";
					 
				    create('qualidade',$edit);
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
				    header("Location: ".$_SESSION['url']);
		    	}
				
			}
			
			
			if(isset($_POST['deletar'])){
					$readDeleta = read('qualidade',"WHERE id = '$qualidadeId'");
					if(!$readDeleta){
						echo '<div class="alert alert-warning">Desculpe, o registro não existe!</div><br />';
					}else{
						delete('pedido',"id = '$qualidadeId'");
						$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
						header('Location: painel.php?execute=suporte/qualidade/qualidade');
					}
			 }
		?>
		
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
     <div class="form-group col-xs-12 col-md-12">  
		 
		 <div class="box-header with-border">
             <h3 class="box-title">IDENTIFICAÇÃO DA NÃO CONFORMIDADE</h3>
           </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
 
      		<div class="form-group col-xs-12 col-md-1">  
                <label>Id</label>
                 <input name="id" class="form-control" type="text" disabled value="<?php echo $edit['id'];?>"/>
            </div>
		 
		   <div class="form-group col-xs-12 col-md-2">  
       		   <label>Status</label>
               <input type="text" name="status" value="<?php echo $edit['status'];?>" class="form-control" disabled />
            </div> 

            <div class="form-group col-xs-12 col-md-3">  
            <label>Identificação</label>
                <select name="identificacao" class="form-control" >
                    <option value="">Selecione um item</option>
                    <?php 
                        $readSuporte = read('qualidade_identificacao',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos regisro no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['identificacao'] == $mae['id']){
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
            <label>Setor</label>
                <select name="setor" class="form-control" >
                    <option value="">Selecione um setor</option>
                    <?php 
                        $readSuporte = read('qualidade_setor',"WHERE id");
                        if(!$readSuporte){
                            echo '<option value="">Não temos regisro no momento</option>';	
                        }else{
                            foreach($readSuporte as $mae):
							   if($edit['setor'] == $mae['id']){
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
                 <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="data" class="form-control"  value="<?php echo $edit['data'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           
          
        
			
		 <div class="form-group col-xs-12 col-md-12"> 
              <label>Item do POP</label>
                <textarea name="item" rows="5" cols="100" class="form-control" /><?php echo $edit['item'];?></textarea>
         </div>  
			 
		  
              
          <div class="form-group col-xs-12 col-md-12"> 
              <label>ORIGEM DA NÃO CONFORMIDADE</label>
                <textarea name="origem" rows="5" cols="100" class="form-control" /><?php echo $edit['origem'];?></textarea>
         </div>  
			 
			 
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
          
		
  		<div class="box-header with-border">
                  <h3 class="box-title">AÇÃO CORRETIVA</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
			 
		 <div class="form-group col-xs-12 col-md-12"> 
              <label>AÇÃO </label>
                <textarea name="acao_corretiva" rows="5" cols="100" class="form-control" /><?php echo $edit['acao_corretiva'];?></textarea>
         </div>  
		
		 </div><!-- /.row -->
       </div><!-- /.box-body -->
          
		
  		<div class="box-header with-border">
              <h3 class="box-title">CAUSA DA NÃO CONFORMIDADE</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
              
             <div class="form-group col-xs-12 col-md-12"> 
              <label>CAUSA</label>
                <textarea  name="causa" rows="5" cols="100" class="form-control"  /><?php echo $edit['causa'];?></textarea>
        	 </div>  
			 
			     
             <div class="form-group col-xs-12 col-md-12"> 
              <label>CAUSA PRINCIPAL E AÇÕES A SEREM TOMADAS PELO RESPONSÁVEL DA ÁREA</label>
                <textarea  name="causa_principal" rows="5" cols="100" class="form-control" /><?php echo $edit['causa_principal'];?></textarea>
        	 </div>  
			 
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
	  
	  
	  <div class="box-header with-border">
              <h3 class="box-title">Previsão</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
			
		  <div class="form-group col-xs-12 col-md-4">  
       		   <label>Responsável pela ação:</label>
               <input type="text" name="responsavel" value="<?php echo $edit['responsavel'];?>" class="form-control" />
           </div> 
			
			
           <div class="form-group col-xs-12 col-md-4">  
       		   <label>Previsão para implementar a ação::</label>
               <input type="text" name="previsao" value="<?php echo $edit['previsao'];?>" class="form-control" />
           </div> 
              
           <div class="form-group col-xs-12 col-md-4">  
                 <label>Ação Implantada em:</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="implantacao" class="form-control" value="<?php echo $edit['implantacao'];?>"/>
                  </div><!-- /.input group -->
           </div> 

		  </div><!-- /.row -->
       </div><!-- /.box-body -->
	  
	  
	  <div class="box-header with-border">
              <h3 class="box-title">ACOMPANHAMENTO DA ÁREA DE QUALIDADE</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
			
		 <div class="form-group col-xs-12 col-md-12">  
       		   <label>Acompanhamento</label>
               <input type="text" name="acompanhamento" value="<?php echo $edit['acompanhamento'];?>" class="form-control" />
           </div> 
		  
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
	  
	  
	    <div class="box-header with-border">
              <h3 class="box-title">REINCIDÊNCIA</h3>
        </div><!-- /.box-header -->
                
         <div class="box-body">
        <div class="row">
			
			  <div class="form-group col-xs-4">
                <label>REINCIDÊNCIA</label>
                <select name="reincidencia" class="form-control" >
                  <option value="">Selecione o Status</option>
                  <option <?php if($edit['reincidencia'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['reincidencia'] == '2') echo' selected="selected"';?> value="2">Não</option>
                 </select>
            </div>  
              
              <div class="form-group col-xs-12 col-md-4">  
                 <label>DATA DE FECHAMENTO</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
                  <input type="date" name="fechamento" class="form-control"  value="<?php echo $edit['fechamento'];?>"/>
                  </div><!-- /.input group -->
           </div> 
			
			 <div class="form-group col-xs-12 col-md-4">  
       		   <label>RESPONSÁVEL</label>
               <input type="text" name="responsavel_fechamento" value="<?php echo $edit['responsavel_fechamento'];?>" class="form-control" />
           </div> 

		  
		  </div><!-- /.row -->
       </div><!-- /.box-body -->
          

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
                            echo '<input type="submit" name="abrir" value="Abrir Qualidade" class="btn btn-primary" />';
	
                        }
                     ?>  
                 </div>
             </div>
			</div>
    </form>
   		
   	</div><!-- /.box-body -->
  </div><!-- /.box box-default -->
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->
			 