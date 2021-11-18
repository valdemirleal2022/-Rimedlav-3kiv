<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
			}	
		}
 
		if(!empty($_GET['contratoId'])){
			$contratoId = $_GET['contratoId'];
		}
		
		if(!empty($contratoId)){
			$contrato = mostra('contrato',"WHERE id = '$contratoId'");
			if(!$contrato){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
		
			$clienteId = $contrato['id_cliente'];
			$cliente = mostra('cliente',"WHERE id = '$clienteId'");
			if(!$contrato){
				header('Location: painel.php?execute=suporte/naoencontrado');	
			}
		}
		 
		$edit['emissao'] = date('Y-m-d');
		$edit['vencimento'] =  date('Y-m-d');
		$edit['valor'] =$contrato['valor_mensal'];
		$parcela = 1;
		
		$_SESSION['aba']=4;

		if ($contrato['enviar_boleto_correio'] == "1") {
			$edit['enviar_boleto_correio'] = "checked='CHECKED'";
		  } else {
			$edit['enviar_boleto_correio'] = "";
		 }
	
		
	 
	?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Receita</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li>Cliente</li>
            <li><a href="#">Contrato</a>
            </li>
            <li class="active">Receita</li>
          </ol>
  </section>
    <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $cliente['nome'].' || '.$cliente['email'];?></small></h3>
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
     
     if(isset($_POST['cadastrar'])){
		 	
			$cad['id_contrato'] = $contratoId;
			$cad['contrato_tipo'] = $contrato[ 'contrato_tipo' ];
			$cad['id_cliente'] 	= $clienteId;
		 	
			$cad['emissao']		= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
			$cad['remessa_data']= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
			$cad['vencimento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['vencimento'])));
			$cad['valor']		= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
			$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor']));
			$cad['formpag'] 	= strip_tags(trim(mysql_real_escape_string($_POST['formpag'])));
			$cad['banco']  		= strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
			$cad['status'] 		= 'Em Aberto';
			$cad['interacao']= date('Y/m/d H:i:s');
			if(in_array('',$cad)){
				print_r($cad);
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
			  }else{
				$cad['enviar_boleto_correio'] = $_POST['enviar_boleto_correio'];
				$cad['observacao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['observacao'])));
				create('receber',$cad);
				$interacao='Lançamento Boleto Avulso';
				interacao($interacao,$contratoId);
				$_SESSION['retorna'] = '<div class="alert alert-success">Atualizado com sucesso!</div>'; 
				header("Location: ".$_SESSION['contrato-editar']);
			}

			
		};	
		 
	?>
   <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    	
         <div class="box-body">
                <div class="row">
  			 <div class="form-group col-xs-12 col-md-3">  
               <label>Id :</label>
               <input name="id" type="text" value="<?php echo $cad['id'];?>"  readonly class="form-control" />
             </div> 

      		 <div class="form-group col-xs-12 col-md-3">  
                 <label>Emissão</label>
                  <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="date" name="emissao" class="form-control pull-right" value="<?php echo $cad['emissao'];?>"/>
                  </div><!-- /.input group -->
           </div> 
           <div class="form-group col-xs-12 col-md-3">  
                 <label>Vencimento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="vencimento" class="form-control pull-right" value="<?php echo $cad['vencimento'];?>"/>
                 </div><!-- /.input group -->
           </div>
                   
           <div class="form-group col-xs-12 col-md-3">  
          		<label>Valor</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right" value="<?php echo converteValor($cad['valor']);?>"/>
                 </div><!-- /.input group -->
           </div>
           
           <div class="form-group col-xs-12 col-md-6"> 
              <label>Banco</label>
                <select name="banco" class="form-control">
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
            <label>Forma de Pagamento </label>
                <select name="formpag" class="form-control">
                    <option value="">Forma de Pagamento</option>
                    <?php 
                        $readFormpag = read('formpag',"WHERE id");
                        if(!$readFormpag){
                            echo '<option value="">Não temos Forma de Pagamento no momento</option>';	
                        }else{
                            foreach($readFormpag as $mae):
							   if($edit['formpag'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
            </div>
            
            
             
            <div class="form-group col-xs-12 col-md-12"> 
                 <label>Observação</label>
                 <input type="text" name="observacao" value="<?php  echo $cad['observacao'];?>" class="form-control" />
     	    </div>
           
           
             <div class="form-group col-xs-12 col-md-4">
                   <input name="enviar_boleto_correio" type="checkbox" value="1" <?PHP echo $edit['enviar_boleto_correio']; ?>  class="minimal" <?php echo $disabled;?> >
            	 Enviar Boleto pelo Correio
              </div> 
            
            
               </div><!-- /.row -->
           </div><!-- /.box-body -->
           

             <div class="box-body">
                <div class="row">
            
                <div class="box-footer">
                 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
                  <?php 
     
                        echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                   
                 ?>  
                 </div>
              </div><!-- /.row -->
           </div><!-- /.box-body -->
			 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
