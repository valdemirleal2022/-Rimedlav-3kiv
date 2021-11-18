<?php 

		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		$acao = "cadastrar";
		$edit['data'] = date('Y-m-d');
	
		if(!empty($_GET['retiradaEditar'])){
			$retiradaId = $_GET['retiradaEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['retiradaDeletar'])){
			$retiradaId = $_GET['retiradaDeletar'];
			$acao = "deletar";
		}

		if(!empty($retiradaId)){
			
			$readretirada= read('veiculo_combustivel_retirada',"WHERE id = '$retiradaId'");
			if(!$readretirada){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readretirada as $edit);
		}
 
		
 ?>
 
<div class="content-wrapper">
 
  <section class="content-header">
          <h1>Abastecimento Interno</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Veículo</a></li>
            <li><a href="#">Abastecimento</a></li>
             <li class="active">Editar</li>
          </ol>
  </section>
  
  <section class="content">
     
     <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $cliente['nome'];?></small></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
          		</div><!-- /box-tools-->
          	 </div><!-- /.box-header -->
          	  	
          	<div class="box-header">
              <div class="row">
          		<div class="col-xs-10 col-md-3 pull-left">  
            		   
            	</div><!-- /col-xs-10 col-md-5 pull-right-->
   			 </div><!-- /row-->  
           </div><!-- /box-header-->   
      	  
    <div class="box-body">
      
	<?php 
			 
	if(isset($_POST['cadastrar'])){

		$edit['id_combustivel'] = mysql_real_escape_string($_POST['id_combustivel']);
		$edit['id_veiculo'] = strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
		$edit['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
		$edit['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$edit['km'] = strip_tags(trim(mysql_real_escape_string($_POST['km'])));
		
		if(in_array('',$edit)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			
			$edit['lacre_entrada'] = strip_tags(trim(mysql_real_escape_string($_POST['lacre_entrada'])));
			$edit['lacre_saida'] = strip_tags(trim(mysql_real_escape_string($_POST['lacre_saida'])));

			$edit['hodometro_entrada'] = strip_tags(trim(mysql_real_escape_string($_POST['hodometro_entrada'])));
			$edit['hodometro_saida'] = strip_tags(trim(mysql_real_escape_string($_POST['hodometro_saida'])));
			
			$edit['calibragem_dd'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_dd'])));
			$edit['calibragem_de'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_de'])));
			$edit['calibragem_tdi'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tdi'])));
			$edit['calibragem_tde'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tde'])));
			$edit['calibragem_tei'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tei'])));
			$edit['calibragem_tee'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tee'])));
			 
			$edit['profundidade_dd'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_dd'])));
			$edit['profundidade_de'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_de'])));
			$edit['profundidade_tdi'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tdi'])));
			$edit['profundidade_tde'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tde'])));
			$edit['profundidade_tei'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tei'])));
			$edit['profundidade_tee'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tee'])));
			 
			$edit['tomada_forca'] = strip_tags(trim(mysql_real_escape_string($_POST['tomada_forca'])));


			$data = $edit['data'];
			$veiculo = $edit['id_veiculo'];
			
			$veiculoCombustivel = mostra('veiculo_combustivel_retirada',"WHERE id AND data<'$data' AND id_veiculo='$veiculo' ORDER BY data ASC"); 
			if($veiculoCombustivel){
				$km = $edit['km']-$veiculoCombustivel['km'];
				$media = $km/$edit['quantidade'];
				$edit['media']=$media ;
				$edit['km_percorrido']=$km ;
			}
			
			$edit['id_combustivel2'] = mysql_real_escape_string($_POST['id_combustivel2']);
			$edit['quantidade2'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade2'])));
			if(!empty($edit['id_combustivel2'])){
				$media2 = $km/$edit['quantidade2'];
				$edit['media2']=$media2 ;
			}
 
			create('veiculo_combustivel_retirada',$edit);
 			
			//RETIRAA DO ESTOQUE;
 			$combustivelId = $edit['id_combustivel'];
 			$estoque = mostra('veiculo_combustivel',"WHERE id = '$combustivelId'");
 
 			$est['estoque'] = $estoque['estoque'] - $edit['quantidade'];
 			update('veiculo_combustivel',$est,"id = '$combustivelId'");	
			
			
			if(!empty($edit['id_combustivel2'])){
				// RETIRAA DO ESTOQUE;
				$combustivelId = $edit['id_combustivel2'];
				$estoque = mostra('veiculo_combustivel',"WHERE id = '$combustivelId'");

				$est['estoque'] = $estoque['estoque'] - $edit['quantidade'];
				update('veiculo_combustivel',$est,"id = '$combustivelId'");	
			}
			
 		
  			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
 			header('Location: painel.php?execute=suporte/veiculo/combustivel-retirada');
			 
		}
	}
		
		if(isset($_POST['atualizar'])){
			
			$edit['id_combustivel'] = mysql_real_escape_string($_POST['id_combustivel']);
			$edit['id_veiculo'] = strip_tags(trim(mysql_real_escape_string($_POST['id_veiculo'])));
			$edit['quantidade'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade'])));
			$edit['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
			$edit['km'] = strip_tags(trim(mysql_real_escape_string($_POST['km'])));
		
		 	$edit['lacre_entrada'] = strip_tags(trim(mysql_real_escape_string($_POST['lacre_entrada'])));
			$edit['lacre_saida'] = strip_tags(trim(mysql_real_escape_string($_POST['lacre_saida'])));

		$edit['hodometro_entrada'] = strip_tags(trim(mysql_real_escape_string($_POST['hodometro_entrada'])));
			$edit['hodometro_saida'] = strip_tags(trim(mysql_real_escape_string($_POST['hodometro_saida'])));
			
			$edit['calibragem_dd'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_dd'])));
			$edit['calibragem_de'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_de'])));
			$edit['calibragem_tdi'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tdi'])));
			$edit['calibragem_tde'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tde'])));
			$edit['calibragem_tei'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tei'])));
			$edit['calibragem_tee'] = strip_tags(trim(mysql_real_escape_string($_POST['calibragem_tee'])));
			 
			$edit['profundidade_dd'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_dd'])));
			$edit['profundidade_de'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_de'])));
			$edit['profundidade_tdi'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tdi'])));
			$edit['profundidade_tde'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tde'])));
			$edit['profundidade_tei'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tei'])));
			$edit['profundidade_tee'] = strip_tags(trim(mysql_real_escape_string($_POST['profundidade_tee'])));
			 
			$edit['tomada_forca'] = strip_tags(trim(mysql_real_escape_string($_POST['tomada_forca'])));

			$data = $edit['data'];
			$veiculo = $edit['id_veiculo'];
			
			$veiculoCombustivel = mostra('veiculo_combustivel_retirada',"WHERE id AND data<'$data' AND id_veiculo='$veiculo' ORDER BY data ASC"); 
			if($veiculoCombustivel){
				$km = $edit['km']-$veiculoCombustivel['km'];
				$media = $km/$edit['quantidade'];
				$edit['media']=$media ;
				$edit['km_percorrido']=$km ;
			}
			
			$edit['id_combustivel2'] = mysql_real_escape_string($_POST['id_combustivel2']);
			$edit['quantidade2'] = strip_tags(trim(mysql_real_escape_string($_POST['quantidade2'])));
			if(!empty($edit['id_combustivel2'])){
				$media2 = $km/$edit['quantidade2'];
				$edit['media2']=$media2 ;
			}
			update('veiculo_combustivel_retirada',$edit,"id = '$retiradaId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Atualizado com sucesso</div>';
			header('Location: painel.php?execute=suporte/veiculo/combustivel-retirada');
			unset($cad);
			
		}

	if(isset($_POST['deletar'])){
	 
		delete('veiculo_combustivel_retirada',"id = '$retiradaId'");
		
		//DEVOLVE AO ESTOQUE;
		$combustivelId=$edit['id_combustivel'];
		$estoque= mostra('estoque_equipamento',"WHERE id = '$combustivelId'");
		$est['estoque'] = $estoque['estoque'] + $edit['quantidade'];
		update('estoque_equipamento',$est,"id = '$combustivelId'");	
		
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header('Location: painel.php?execute=suporte/veiculo/combustivel-retirada');
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
    
  		<div class="form-group col-xs-12 col-md-1"> 
             <label>Id</label>
            <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
        </div> 
		
		<div class="form-group col-xs-12 col-md-3">  
            <label>Veículo </label>
                <select name="id_veiculo" class="form-control">
                    <option value="">Veículo (*)</option>
                    <?php 
                        $readConta = read('veiculo',"WHERE id ORDER BY placa ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos veiculos no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_veiculo'] == $mae['id']){
									echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['placa'].'</option>';
								 }else{
                                	echo '<option value="'.$mae['id'].'">'.$mae['placa'].'</option>';
								}
                            endforeach;	
                        }
                    ?> 
                </select>
          </div> 

         <div class="form-group col-xs-12 col-md-2">  
            <label>Combustivel 1</label>
                <select name="id_combustivel" class="form-control">
                    <option value="">Combustivel 1</option>
                    <?php 
                        $readConta = read('veiculo_combustivel',"WHERE id ORDER BY id ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos combustivel no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_combustivel'] == $mae['id']){
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
               <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
                       </div>
                      <input type="text" name="quantidade" class="form-control pull-right"  value="<?php echo $edit['quantidade'];?>"/>
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-2">  
            <label>Combustivel 2</label>
                <select name="id_combustivel2" class="form-control">
                    <option value="">Combustivel 2</option>
                    <?php 
                        $readConta = read('veiculo_combustivel',"WHERE id ORDER BY id ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos combustivel no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['id_combustivel2'] == $mae['id']){
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
         <div class="input-group">
              <div class="input-group-addon">
                        <i class="fa fa-archive"></i>
               </div>
                <input type="text" name="quantidade2" class="form-control pull-right"  value="<?php echo $edit['quantidade2'];?>"/>
           </div><!-- /.input group -->
    </div>
		
         <div class="form-group col-xs-12 col-md-4">  
          		<label>Km</label>
               <div class="input-group">
                      
                      <input type="text" name="km" class="form-control pull-right"  value="<?php echo $edit['km'];?>"/>
                 </div><!-- /.input group -->
          </div>
		
		
		  <div class="form-group col-xs-12 col-md-2">  
          		<label>Lacre Entrada</label>
               <div class="input-group">
                      
                      <input type="text" name="lacre_entrada" class="form-control pull-right"  value="<?php echo $edit['lacre_entrada'];?>"/>
                 </div><!-- /.input group -->
         </div>
		
		
		 <div class="form-group col-xs-12 col-md-2">  
          		<label>Lacre Saída</label>
               <div class="input-group">
                       
                      <input type="text" name="lacre_saida" class="form-control pull-right"  value="<?php echo $edit['lacre_saida'];?>"/>
                 </div><!-- /.input group -->
         </div>
           
            <div class="form-group col-xs-12 col-md-2">  
          		<label>Hodômetro de entrada </label>
               <div class="input-group">
                      
                      <input type="text" name="hodometro_saida" class="form-control pull-right"  value="<?php echo $edit['hodometro_saida'];?>"/>
                 </div><!-- /.input group -->
         </div>
		
		 <div class="form-group col-xs-12 col-md-2">  
          		<label>Hodômetro de saída</label>
               <div class="input-group">
                       
                      <input type="text" name="hodometro_saida" class="form-control pull-right"  value="<?php echo $edit['hodometro_saida'];?>"/>
                 </div><!-- /.input group -->
         </div>
	 	
		 <div class="form-group col-xs-2">
			 
                <label>Calibragem DD  </label>
			 
                <div class="input-group">
                       
                      <input type="text" name="calibragem_dd" class="form-control pull-right"  value="<?php echo $edit['calibragem_dd'];?>"/>
                </div><!-- /.input group -->
			  
         </div>  
		
		
		
		<div class="form-group col-xs-2">
			 
                <label>Calibragem DE  </label>
			 
                <div class="input-group">
                      
                      <input type="text" name="calibragem_de" class="form-control pull-right"  value="<?php echo $edit['calibragem_de'];?>"/>
                </div><!-- /.input group -->
			  
         </div>  
		
		<div class="form-group col-xs-2">
			 
                <label>Calibragem TDI  </label>
					 
			    <div class="input-group">
                      
                      <input type="text" name="calibragem_tdi" class="form-control pull-right"  value="<?php echo $edit['calibragem_tdi'];?>"/>
                 </div><!-- /.input group -->
		 
			 
         </div>  
	 
		
		<div class="form-group col-xs-2">
			 
                <label>Calibragem TDE </label>
			 
                <div class="input-group">
                  
                      <input type="text" name="calibragem_tde" class="form-control pull-right"  value="<?php echo $edit['calibragem_tde'];?>"/>
                </div><!-- /.input group -->
			  
         </div> 
		
		<div class="form-group col-xs-2">
			 
                <label>Calibragem TEI  </label>
			 
                <div class="input-group">
                  
                      <input type="text" name="calibragem_tei" class="form-control pull-right"  value="<?php echo $edit['calibragem_tei'];?>"/>
                </div><!-- /.input group -->
			  
         </div>  
		
		<div class="form-group col-xs-2">
			 
                <label>Calibragem TEE  </label>
			 
                <div class="input-group">
                  
                      <input type="text" name="calibragem_tee" class="form-control pull-right"  value="<?php echo $edit['calibragem_tee'];?>"/>
                </div><!-- /.input group -->
			  
        </div>  

		<div class="form-group col-xs-2">
			 
                <label>Profundidade DD  </label>
			 
                <div class="input-group">
                       
                      <input type="text" name="profundidade_dd" class="form-control pull-right"  value="<?php echo $edit['profundidade_dd'];?>"/>
                </div><!-- /.input group -->
			  
        </div>  
		
		<div class="form-group col-xs-2">
			 
                <label>Profundidade DE  </label>
			 
                <div class="input-group">
                      
                      <input type="text" name="profundidade_de" class="form-control pull-right"  value="<?php echo $edit['profundidade_de'];?>"/>
                </div><!-- /.input group -->
			  
        </div>  
		
		<div class="form-group col-xs-2">
			 
                <label>Profundidade TDI  </label>
					 
			    <div class="input-group">
                      
                      <input type="text" name="profundidade_tdi" class="form-control pull-right"  value="<?php echo $edit['profundidade_tdi'];?>"/>
                 </div><!-- /.input group -->
	 
        </div>  

		<div class="form-group col-xs-2">
			 
                <label>Profundidade TDE </label>
			 
                <div class="input-group">
                  
                      <input type="text" name="profundidade_tde" class="form-control pull-right"  value="<?php echo $edit['profundidade_tde'];?>"/>
                </div><!-- /.input group -->
			  
        </div> 
		
		<div class="form-group col-xs-2">
			 
                <label>Profundidade TEI  </label>
			 
                <div class="input-group">
                  
                      <input type="text" name="profundidade_tei" class="form-control pull-right"  value="<?php echo $edit['profundidade_tei'];?>"/>
                </div><!-- /.input group -->
			  
        </div>  
		
		<div class="form-group col-xs-2">
			 
                <label>Profundidade TEE  </label>
			 
                <div class="input-group">
                  
                      <input type="text" name="profundidade_tee" class="form-control pull-right"  value="<?php echo $edit['profundidade_tee'];?>"/>
                </div><!-- /.input group -->
			  
         </div>  
          
         <div class="form-group col-xs-3">
                <label>Verificado os apertos (Tomada de Força) </label>
                <select name="tomada_forca" class="form-control" >
                  <option value="">Selecione...</option>
                  <option <?php if($edit['tomada_forca'] == '1') echo' selected="selected"';?> value="1">Sim</option>
                  <option <?php if($edit['tomada_forca'] == '0') echo' selected="selected"';?> value="0">Não</option>
                 </select>
         </div>                  

         <div class="form-group col-xs-12 col-md-3">  
               <label>Data</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               			<input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
              </div><!-- /.input group -->
          </div> 
     
           
    	 <div class="form-group col-xs-12 col-md-12">  
             
		 <div class="box-footer">
			 <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>
				  <?php 
					if($acao=="baixar"){
						echo '<input type="submit" name="baixar" value="Baixar" class="btn btn-primary" />';	
					}
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
          
          </div>
		</form>
  	</div><!-- /.box-body -->
    </div><!-- /.box box-default -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->