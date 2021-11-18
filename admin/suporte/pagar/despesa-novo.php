<?php 

if ( function_exists( 'ProtUser' ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}
	$cad['emissao'] = date('Y-m-d');
	$cad['vencimento'] =  date('Y-m-d');
	$parcela = 1;

?>

<div class="content-wrapper">

  <section class="content-header">
	  
          <h1>Despesas</h1>
	  
          <ol class="breadcrumb">
			  
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Contas a Pagar</li>
            <li><a href="painel.php?execute=suporte/pagar/despesas">
              	Despesas</a>
            </li>
            <li class="active">Novo</li>
			  
          </ol>
	  
  </section>

   <section class="content">
      <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Novo Lançamento</small></h3>
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
			
			$cad['id_conta'] 	= strip_tags(trim(mysql_real_escape_string($_POST['id_conta'])));
			$cad['emissao']		= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
			$cad['vencimento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['vencimento'])));
			$cad['valor']		= strip_tags(trim(mysql_real_escape_string($_POST['valor'])));
			$cad['valor'] = str_replace(",",".",str_replace(".","",$cad['valor']));
			$cad['formpag'] 	= strip_tags(trim(mysql_real_escape_string($_POST['formpag'])));
			$cad['banco']  	= strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
			$cad['descricao'] 	= strip_tags(trim(mysql_real_escape_string($_POST['descricao']))) ;
		
			$cad['status'] 		= 'Em Aberto';
			$parcela 			= $_POST['parcela'];
			$baixaautomatica     = $_POST['baixaautomatica'];
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['usuario']	=  $_SESSION['autUser']['nome'];
			
			if(in_array('',$cad)){
				echo '<div class="alert alert-warning"">Todos os campos são obrigatórios!</div>'.'<br>';
				//print_r($cad);
			  }else{
				 if ($parcela == 1){
				 
					 $cad['juros']	= strip_tags(trim(mysql_real_escape_string($_POST['juros'])));
					 $cad['juros'] = str_replace(",",".",str_replace(".","",$cad['juros']));
					 
					 $cad['nota'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
					 
					 $cad['autorizacao'] = mysql_real_escape_string($_POST['autorizacao']);
						 
					 $cad['cheque']= strip_tags(trim(mysql_real_escape_string($_POST['cheque'])));
					 $cad['id_fornecedor']  = strip_tags(trim(mysql_real_escape_string($_POST['id_fornecedor'])));
					 $cad['data']= date('Y/m/d');

					 create('pagar',$cad);
					 $pagamentoId = $ultimoId;
					 
					  // INTERAÇÃO
					 $servicoId='-';
				 	 $interacao='Pagamento novo n. '.$pagamentoId;
					 interacao($interacao,$servicoId);
					 unset($cad); 
				 }else{
					$contador=0;
					while ($contador < $parcela) {
						
							$contador = $contador+1;
							$num_parcela = $contador ."/".$parcela;
							$cad['parcela'] =  $num_parcela;
							$cad['nota'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nota'])));
							$cad['cheque'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cheque'])));
							$cad['id_fornecedor']  = strip_tags(trim(mysql_real_escape_string($_POST['id_fornecedor'])));
						
							$cad['data']= date('Y/m/d');
							$cad['interacao']= date('Y/m/d H:i:s');
							$cad['usuario']	=  $_SESSION['autUser']['nome'];
						
							create('pagar',$cad);
							$pagamentoId = $ultimoId;
						
							// INTERAÇÃO
							$servicoId='-';
				 			$interacao='Pagamento novo n. '.$pagamentoId;
							interacao($interacao,$servicoId);
						
							$vencimento=$cad['vencimento'];
							$cad['vencimento'] = date("Y-m-d", strtotime("$vencimento + 1 MONTH"));
							
						
						
					}
			 	}
 
				if ($baixaautomatica=='on'){
					$cad['vencimento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
					$cad['pagamento'] 	= strip_tags(trim(mysql_real_escape_string($_POST['emissao'])));
					$cad['status'] 	='Baixado';
					update('pagar',$cad,"id = '$pagamentoId'");	
					unset($cad); 
					
					 // INTERAÇÃO
					$servicoId='-';
				 	$interacao='Pagamento Baixado Automaticamente n. '.$pagamentoId;
					interacao($interacao,$servicoId);
					
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div><br />';		header("Location: ".$_SESSION['url']);
				  }else{
					unset($cad); 
					$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div><br />';
					header("Location: ".$_SESSION['url']);
					 
			  	}
				
			  }
		};	
	 
	?>
	
   <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
  			 <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $cad['id'];?>"  disabled class="form-control" />
             </div> 
             <div class="form-group col-xs-12 col-md-5">  
               	<label>Interação</label>
                <input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s');?>" disabled class="form-control" /> 
			</div> 
             <div class="form-group col-xs-12 col-md-5">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $_SESSION['autUser']['nome'];?>" disabled class="form-control" /> 
            </div> 
	   
	   	<div class="form-group col-xs-12 col-md-12">  
            <label>Fornecedor</label>
                <select name="id_fornecedor" class="form-control">
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos Registro no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_fornecedor'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].' | '.$mae['cnpj'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['nome'].' | '.$mae['cnpj'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
             </div> 
              
           	<div class="form-group col-xs-12 col-md-12">  
            <label>Conta </label>
                <select name="id_conta" class="form-control">
                    <option value="">Conta</option>
                    <?php 
                        $readConta = read('pagar_conta',"WHERE status='1' ORDER BY codigo ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos Registro no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_conta'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].'-'.$mae['nome'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['codigo'].'-'.$mae['nome'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
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
                          <input type="text" name="valor" class="form-control pull-right"  value="<?php echo converteValor($cad['valor']);?>"/>
                 </div><!-- /.input group -->
           </div>
	   
	   
	  	 <div class="form-group col-xs-12 col-md-3">  
          		<label>Juros</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="juros" class="form-control pull-right"  value="<?php echo converteValor($cad['juros']);?>"/>
                 </div><!-- /.input group -->
           </div>
              
            <div class="form-group col-xs-12 col-md-6"> 
              <label>Banco</label>
                <select name="banco" class="form-control">
                    <option value="">Selecione Banco</option>
                    <?php 
                        $readBanco = read('banco',"WHERE status='1' ORDER BY nome ASC");
                        if(!$readBanco){
                            echo '<option value="">Não temos Bancos no momento</option>';	
                        }else{
                            foreach($readBanco as $mae):
							   if($cad['banco'] == $mae['id']){
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
                <select name="formpag" class="form-control" >
                    <option value="">Forma de Pagamento</option>
                    <?php 
                        $readFormpag = read('formpag',"WHERE id");
                        if(!$readFormpag){
                            echo '<option value="">Não temos Forma de Pagamento no momento</option>';	
                        }else{
                            foreach($readFormpag as $mae):
							   if($cad['formpag'] == $mae['id']){
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
                 <label>Descrição</label>
                 <input type="text" name="descricao" value="<?php  echo $cad['descricao'];?>" class="form-control" />
     	    </div>
	   
	   		 <div class="form-group col-xs-12 col-md-12"> 
                 <label>Código de Barra</label>
                 <input type="text" name="codigo_barra" value="<?php  echo $cad['codigo_barra'];?>" class="form-control" />
     	    </div>

            <div class="form-group col-xs-12 col-md-4"> 
                 <label>Nota Fiscal </label>
                 <input type="text" name="nota" value="<?php if($cad['nota']) echo $cad['nota'];?>" class="form-control" />
    		 </div>
             
              <div class="form-group col-xs-12 col-md-4"> 
                 <label>Cheque</label>
                 <input type="text" name="cheque" value="<?php if($cad['cheque']) echo $cad['cheque'];?>" class="form-control" />
    		 </div>
             
              <div class="form-group col-xs-12 col-md-4"> 
                 <label>Parcela</label>
                 <input name="parcela" type="number" max="60" min="1" value="<?php echo $parcela;?>" class="form-control" />
    		  </div>
	   
	    
	    <div class="form-group col-xs-12 col-md-12">
	   
	       <div class="form-group col-xs-12 col-md-3">
               <input type="checkbox" name="baixaautomatica"> Pagamento A vista
           </div>
            
	   
	       <div class="form-group col-xs-12 col-md-3">  
                 <label>Pagamento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="pagamento" class="form-control pull-right" value="<?php echo $edit['pagamento'];?>"/>
                 </div><!-- /.input group -->
           </div>
              
        </div>  
            
       <div class="form-group col-xs-12 col-md-12">     
         <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
           <input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />
		</form>
      </div>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

