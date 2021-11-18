<?php 
	
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php');		
		}	
	}
		
	$receberId = $_GET['receberNumero'];
	$edit = mostra('receber',"WHERE id = '$receberId'");
	if(!$edit){
			header('Location: painel.php?execute=suporte/error');	
	}

	$clienteId = $edit['id_cliente'];
	$contratoId = $edit['id_contrato'];
		
	$status = $edit['status'];
		
	if($edit['status']=="Em Aberto"){
			$valorPago=$edit['valor'];
			$edit['pagamento'] =date("Y-m-d");
		 }else{
			$valorPago=$edit['valor'];
	}

		if ($edit['serasa'] == "1") {
				$edit['serasa'] = "checked='CHECKED'";
		} else {
				$edit['serasa'] = "";
		}

		if ($edit['juridico'] == "1") {
			$edit['juridico'] = "checked='CHECKED'";
		} else {
			$edit['juridico'] = "";
		}
		
		$protesto=0;
		if ($edit['protesto'] == "1") {
			$edit['protesto'] = "checked='CHECKED'";
			$protesto=1;
		} else {
			$edit['protesto'] = "";
		}

	$cliente = mostra('cliente',"WHERE id = '$clienteId'");

	if ($edit['dispensa'] == "1") {
			$edit['dispensa'] = "checked='CHECKED'";
	 } else {
			$edit['dispensa'] = "";
 	}

	 

?>

<div class="content-wrapper">

  <section class="content-header">
          <h1>Receita</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Cliente</li>
            <li><a href="#">Contrato</a>
            </li>
            <li class="active">Receita</li>
          </ol>
  </section>

  <section class="content">

      <div class="box box-warning">
      
       <div class="box-header">
             <h3 class="box-title"><?php echo $cliente['nome'];?></h3>
      
      	<div class="box-tools">
               <small> Status Atual - <?php echo $status;?></small>
           </div><!-- /box-tools-->
       </div><!-- /.box-header -->
            
     <div class="box-body">
    
	<?php 
	
		if(isset($_POST['baixar'])){
			
				$cad['pagamento']= strip_tags(trim(mysql_real_escape_string($_POST['pagamento'])));
				$cad['status']=strip_tags(trim(mysql_real_escape_string($_POST['status'])));
				$cad['formpag']=strip_tags(trim(mysql_real_escape_string($_POST['formpag'])));
				$cad['banco'] = strip_tags(trim(mysql_real_escape_string($_POST['banco'])));
				$cad['status']= 'Baixado';
			
				$valorPago = strip_tags(trim(mysql_real_escape_string($_POST['valorPago'])));
				$valorPago = str_replace(",",".",str_replace(".","",$valorPago));
			
				if($valorPago<>$edit['valor']){
					if($valorPago>$$edit['valor']){
						$cad['juros'] =$valorPago-$edit['valor'];
					}else{
						$cad['desconto'] = $edit['valor']-$valorPago;
					}
				}
			
			 //echo 'Juros : '. converteValor($cad['juros']) .'<br />';
			 //echo 'Desconto : '.$cad['desconto'] .'<br />';
			 
			if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios</div>';
			 }else{
				
			$cad['serasa']= strip_tags(trim(mysql_real_escape_string($_POST['serasa'])));
			$cad['serasa_data']=strip_tags(trim(mysql_real_escape_string($_POST['serasa_data'])));
			$cad['juridico']=strip_tags(trim(mysql_real_escape_string($_POST['juridico'])));
			$cad['juridico_data']=strip_tags(mysql_real_escape_string($_POST['juridico_data']));
			$cad['protesto']=strip_tags(trim(mysql_real_escape_string($_POST['protesto'])));
			$cad['protesto_data']=strip_tags(mysql_real_escape_string($_POST['protesto_data']));
			$cad['observacao']=mysql_real_escape_string($_POST['observacao']);
				
				if($cad['serasa']==1){
					$cad['status']	= 'Serasa';
					$cad['pagamento']=null;
				}

				if($cad['juridico']==1){
					$cad['status']	= 'Juridico';
					$cad['pagamento']=null;
				}

				if($cad['protesto']==1){
					$cad['status']	= 'Protesto';
					$cad['pagamento']=null;
				}

			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['usuario']	=  $_SESSION['autUser']['nome'];
			update('receber',$cad,"id = '$receberId'");
					
				// INTERAÇÃO
				 $interacao='Baixa do Boleto';
				interacao($interacao,$contratoId);

				$_SESSION['cadastro']='<div class="alert alert-success">Baixado com sucesso
				</div><br />';
			header("Location: ".$_SESSION['url']);
			}
		}
		 
		 
		 if(isset($_POST['dispensar'])){
			 
			 $cad['dispensa']=strip_tags(trim(($_POST['dispensa'])));
			 $cad['dispensa_data']=strip_tags(trim(($_POST['dispensa_data'])));
			 $cad['dispensa_motivo']=strip_tags(trim(($_POST['dispensa_motivo'])));
			 $cad['status']='Dispensa';
			 
			 $cad['interacao']= date('Y/m/d H:i:s');
			 $cad['usuario']	=  $_SESSION['autUser']['nome'];
	
			 if(in_array('',$cad)){
					echo '<div class="alert alert-warning">Todos os campos são obrigatórios</div>';
			 }else{
				update('receber',$cad,"id = '$receberId'");
					
				// INTERAÇÃO
				$interacao='Dispensa de Boleto';
				interacao($interacao,$contratoId);

				$_SESSION['cadastro']='<div class="alert alert-success">Dispensado com sucesso</div><br/>';
				header("Location: ".$_SESSION['url']);
				 
			}
		}
	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
    <div class="box-body">
       <div class="row">
           
  		
  		 <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
         </div> 
         <div class="form-group col-xs-12 col-md-2">  
               	<label>Interação</label>
   				<input name="orc_resposta" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
		 </div> 
             <div class="form-group col-xs-12 col-md-2">  
            	<label>Status</label>
                <input name="status" type="text" value="<?php echo $edit['status'];?>" readonly class="form-control" /> 
            </div> 
             <div class="form-group col-xs-12 col-md-3">  
            	<label>Usuário</label>
                <input name="usuario" type="text" value="<?php echo $edit['usuario'];?>" readonly class="form-control" /> 
            </div> 
            <div class="form-group col-xs-12 col-md-3">  
                <label>Print</label>
   				<input name="imprimir" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['imprimir']));?>" class="form-control" readonly  /> 
            </div> 
         
         <div class="form-group col-xs-12 col-md-4">  
                 <label>Emissão</label>
                  <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="date" name="emissao" class="form-control pull-right" value="<?php echo $edit['emissao'];?>" disabled/>
                  </div><!-- /.input group -->
           </div> 
		   
           <div class="form-group col-xs-12 col-md-4">  
                 <label>Vencimento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="vencimento" class="form-control pull-right" value="<?php echo $edit['vencimento'];?>" disabled />
                 </div><!-- /.input group -->
           </div>

           
           <div class="form-group col-xs-12 col-md-4">  
          		<label>Valor</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="valor" class="form-control pull-right" value="<?php echo converteValor($edit['valor']);?>" disabled />
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
             
           <div class="form-group col-xs-12 col-md-8"> 
                 <label>Observação</label>
                 <input type="text" name="observacao" value="<?php  echo $edit['observacao'];?>" class="form-control" />
     	    </div>
            
            <div class="form-group col-xs-12 col-md-4"> 
                 <label>Status</label>
                 <input type="text" name="status" value="<?php  echo $edit['status'];?>" class="form-control" disabled  />
     	    </div>
     	    
     	  </div><!-- /.row -->
         </div><!-- /.box-body -->
          
          
       	<div class="box-header with-border">
                <h3 class="box-title">Baixa de Pagamento</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
            
           
            <div class="form-group col-xs-12 col-md-6">  
                 <label>Pagamento</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                       </div>
                       <input type="date" name="pagamento" class="form-control pull-right" value="<?php echo $edit['pagamento'];?>"/>
                 </div><!-- /.input group -->
           </div>

			<div class="form-group col-xs-12 col-md-6">  
          		<label>Valor Pago</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                       </div>
                       <input type="text" name="valorPago" class="form-control pull-right" value="<?php echo converteValor($valorPago);?>"/>
                 </div><!-- /.input group -->
           </div>
           
           </div><!-- /.row -->
         </div><!-- /.box-body -->
		

           <div class="box-header with-border">
                <h3 class="box-title">Serasa/Juridico/Protesto</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
           
            <!-- SERASA-->
            <div class="form-group col-xs-12 col-md-1">
                   <input name="serasa" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['serasa']; ?>  class="minimal"  <?php echo $disabled;?> >
                Serasa
            </div> 
               
            <div class="form-group col-xs-12 col-md-3">  
                 <label>Entrada no Serada</label>
                  <input type="date" name="serasa_data" class="form-control pull-right" value="<?php echo $edit['serasa_data'];?>"  <?php echo $readonly;?> />
               
           </div>
           
            <!-- JURIDICO-->
            <div class="form-group col-xs-12 col-md-1">
                   <input name="juridico" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['juridico']; ?>  class="minimal"  <?php echo $disabled;?> >
                Jurídico
            </div> 
               
            <div class="form-group col-xs-12 col-md-3">  
                 <label>Entrada no Juridico</label>
                  <input type="date" name="juridico_data" class="form-control pull-right" value="<?php echo $edit['juridico_data'];?>"  <?php echo $readonly;?> />
               
           </div>
				  
			  <!-- PROTESTO-->
            <div class="form-group col-xs-12 col-md-1">
                   <input name="protesto" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['protesto']; ?>  class="minimal"  <?php echo $disabled;?> >
                Protesto
            </div> 
               
            <div class="form-group col-xs-12 col-md-3">  
                 <label>Entrada no Protesto</label>
                  <input type="date" name="protesto_data" class="form-control pull-right" value="<?php echo $edit['protesto_data'];?>"  <?php echo $readonly;?> />
               
           </div>
           
            </div><!-- /.row -->
           </div><!-- /.box-body -->
           
               
             <div class="box-header with-border">
                <h3 class="box-title">Dispensa de Pagamento</h3>
            </div><!-- /.box-header -->
                
			<div class="box-body">
              <div class="row">
           
           <!-- SERASA-->
            <div class="form-group col-xs-12 col-md-3">
                   <input name="dispensa" type="checkbox" id="documentos_0" value="1" <?PHP print $edit['dispensa']; ?>  class="minimal"  <?php echo $disabled;?> >
               Dispensa de Pagamento
            </div> 
              
              <div class="form-group col-xs-3">
                <label>Motivo da Dispensa</label>
                <select name="dispensa_motivo" class="form-control" <?php echo $disabled;?> >
                  <option value="">Selecione</option>
                  <option <?php if($edit['dispensa_motivo'] == '1') echo' selected="selected"';?> value="1">Cobrança Indevida</option>
                  <option <?php if($edit['dispensa_motivo'] == '2') echo' selected="selected"';?> value="0">Por Duplicidade</option>
                 </select>
            </div><!-- /.row -->
              
              <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Recuperação</label>
                  <input type="date" name="dispensa_data" class="form-control pull-right" value="<?php echo $edit['dispensa_data'];?>"  <?php echo $readonly;?> />
               
           </div>
  
         </div><!-- /.row -->
         </div><!-- /.box-body -->
		
		
            
		
        <div class="form-group col-xs-12 col-md-12">  
   		  <div class="box-footer">
          	  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
           	
            <?php 
	
                echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-primary" />';
				echo '<input type="submit" name="dispensar" value="Dispensa" class="btn btn-success" />';
					  
             ?>  
          </div> 
		</div>	 
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
