<?php 
		if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autUser']['id'])){
				header('Location: painel.php');		
			}	
		}

		
		if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
			$_SESSION['compraId']=$compraId;
			echo '<script type="text/javascript">';
			echo 'window.open("painel.php?execute=suporte/relatorio/cotacao-compras-pdf");';
			echo '</script>';
		}

		if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
			$_SESSION['compraId']=$compraId;
			header( 'Location: ../admin/suporte/relatorio/cotacao-compras.php' );
		}

		if(!empty($_GET['compraEditar'])){
			$compraId = $_GET['compraEditar'];
			$acao = "atualizar";
		}
		if(!empty($_GET['compraBaixar'])){
			$compraId = $_GET['compraBaixar'];
			$acao = "baixar";
		}
		if(!empty($_GET['compraDeletar'])){
			$compraId = $_GET['compraDeletar'];
			$acao = "deletar";
		}
		if(!empty($compraId)){
			$readcompra= read('estoque_compras',"WHERE id = '$compraId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $edit);
	

		}
 ?>
 
<div class="content-wrapper">
	
  <section class="content-header">
          <h1>Compras</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Compras</a></li>
            <li><a href="#">Cotação</a></li>
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
	if(isset($_POST['atualizar'])){
		
		$cad['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['interacao']= date('Y/m/d H:i:s');
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			$cad['fornecedor1'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1'])));
			$cad['fornecedor2'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2'])));
			$cad['fornecedor3'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3'])));
			$cad['fornecedor1_desconto'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1_desconto'])));
			$cad['fornecedor2_desconto'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2_desconto'])));
			$cad['fornecedor3_desconto'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3_desconto'])));
			$cad['fornecedor1_pag'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1_pag'])));
			$cad['fornecedor2_pag'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2_pag'])));
			$cad['fornecedor3_pag'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3_pag'])));
			$cad['fornecedor1_prazo'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1_prazo'])));
			$cad['fornecedor2_prazo'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2_prazo'])));
			$cad['fornecedor3_prazo'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3_prazo'])));
			update('estoque_compras',$cad,"id = '$compraId'");	
			header("Location: ".$_SESSION['url']);
			
		}
	}
		
	if(isset($_POST['cadastrar'])){
		
		$cad['status'] = strip_tags(trim(mysql_real_escape_string($_POST['status'])));
		$cad['id_material'] = strip_tags(trim(mysql_real_escape_string($_POST['id_material'])));
		$cad['data'] = strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		$cad['interacao']= date('Y/m/d H:i:s');
		if(in_array('',$cad)){
				echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		}else{
			$cad['fornecedor1'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1'])));
			$cad['fornecedor2'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2'])));
			$cad['fornecedor3'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3'])));
			$cad['fornecedor1_desconto'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1_desconto'])));
			$cad['fornecedor2_desconto'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2_desconto'])));
			$cad['fornecedor3_desconto'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3_desconto'])));
			$cad['fornecedor1_pag'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1_pag'])));
			$cad['fornecedor2_pag'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2_pag'])));
			$cad['fornecedor3_pag'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3_pag'])));
			$cad['fornecedor1_prazo'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor1_prazo'])));
			$cad['fornecedor2_prazo'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor2_prazo'])));
			$cad['fornecedor3_prazo'] = strip_tags(trim(mysql_real_escape_string($_POST['fornecedor3_prazo'])));
			create('estoque_compras',$cad);
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
		}
	}
		
	if(isset($_POST['deletar'])){
		delete('estoque_compras',"id = '$compraId'");
		$_SESSION['cadastro'] = '<div class="alert alert-success">Deletado com sucesso</div>';
		header("Location: ".$_SESSION['url']);
	}

	?>
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
  			 <div class="form-group col-xs-12 col-md-2">  
               <label>Id </label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>"  readonly class="form-control" />
             </div> 
		
             <div class="form-group col-xs-12 col-md-4">  
               	<label>Interação</label>
   				<input name="orc_resposta" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div> 
		
             <div class="form-group col-xs-12 col-md-6">  
            	<label>Status</label>
                <input name="status" type="text" value="<?php echo $edit['status'];?>" readonly class="form-control" /> 
            </div> 
		
			 <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Solicitação</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data_solicitacao" class="form-control pull-right" value="<?php echo $edit['data_solicitacao'];?>"  readonly/>
                </div><!-- /.input group -->
           </div> 
		
			<div class="form-group col-xs-12 col-md-6">  
				 <label>Tipo Material</label>
				<select name="id_material" class="form-control input-sm"  readonly>
					<option value="">Selecione o Tipo</option>
							<?php 
							$readContrato = read('estoque_material_tipo',"WHERE id ORDER BY codigo ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($edit['id_material'] == $mae['id']){
										echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['codigo'].' - '.$mae['nome'].'</option>';
									}else{
										echo '<option value="'.$mae['id'].'">'.$mae['codigo'].' -  '.$mae['nome'].'</option>';
									}
									endforeach;	
								}
							?> 
				 </select>
			</div> 

           <div class="form-group col-xs-12 col-md-3">  
                 <label>Data da Cotação</label>
                  <div class="input-group">
                       <div class="input-group-addon">
                         <i class="fa fa-calendar"></i>
                       </div>
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>"/>
                </div><!-- /.input group -->
           </div> 
		
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 1</label>
                <select name="fornecedor1" class="form-control">
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['fornecedor1'] == $mae['id']){
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
          		<label>Desconto (%) </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor1_desconto" class="form-control pull-right" value="<?php echo converteValor($edit['fornecedor1_desconto']);?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-3">  
          		<label>Cond Pagamento</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor1_pag" class="form-control pull-right" value="<?php echo $edit['fornecedor1_pag'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-3">  
          		<label>Prazo de Entrega</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor1_prazo" class="form-control pull-right" value="<?php echo $edit['fornecedor1_prazo'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 2 </label>
                <select name="fornecedor2" class="form-control">
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['fornecedor2'] == $mae['id']){
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
          		<label>Desconto (%) </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor2_desconto" class="form-control pull-right" value="<?php echo converteValor($edit['fornecedor2_desconto']);?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-3">  
          		<label>Cond Pagamento</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor2_pag" class="form-control pull-right" value="<?php echo $edit['fornecedor2_pag'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-3">  
          		<label>Prazo de Entrega</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor2_prazo" class="form-control pull-right" value="<?php echo $edit['fornecedor2_prazo'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor 3</label>
                <select name="fornecedor3" class="form-control">
                    <option value="">Fornecedor</option>
                    <?php 
                        $readConta = read('estoque_fornecedor',"WHERE id ORDER BY nome ASC");
                        if(!$readConta){
                            echo '<option value="">Não temos fornecedor no momento</option>';	
                        }else{
                            foreach($readConta as $mae):
							   if($edit['fornecedor3'] == $mae['id']){
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
          		<label>Desconto (%) </label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor3_desconto" class="form-control pull-right" value="<?php echo converteValor($edit['fornecedor3_desconto']);?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-3">  
          		<label>Cond Pagamento</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor3_pag" class="form-control pull-right" value="<?php echo $edit['fornecedor3_pag'];?>"  />
                 </div><!-- /.input group -->
          </div>
		
		<div class="form-group col-xs-12 col-md-3">  
          		<label>Prazo de Entrega</label>
               <div class="input-group">
                      <div class="input-group-addon">
                            <i class="fa fa-credit-card"></i>
                          </div>
                          <input type="text" name="fornecedor3_prazo" class="form-control pull-right" value="<?php echo $edit['fornecedor3_prazo'];?>"  />
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
	
	
	
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					
				<div class="box-header">
		
				 <a href="painel.php?execute=suporte/compras/compras-material-editar&comprasId=<?PHP print $compraId; ?>" class="btnnovo">
				  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
					<small>Novo Material</small>
					 </a>
		
				</div><!-- /.box-header -->
					
				 <div class="box-header">
             	  <div class="row">
            
					<div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
                </div><!-- /row-->  
         </div><!-- /box-header-->   

		<div class="box-body table-responsive">

			<?php 
			
			$fornecedorId = $edit['fornecedor1'];
			$fornecedor1 = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");
			$fornecedorId = $edit['fornecedor2'];
			$fornecedor2 = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");
			$fornecedorId = $edit['fornecedor3'];
			$fornecedor3 = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");
			
			$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$compraId' ORDER BY id ASC");
			
			$total=0;
			$totalGeral1=0;
			$totalGeral2=0;
			$totalGeral3=0;
			
			if($leitura){
				
			
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center" colspan="1"> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;</td>
					<td align="center" colspan="3">'.substr($fornecedor1['nome'],0,12).'</td>
					<td align="center" colspan="3">'.substr($fornecedor2['nome'],0,12).'</td>
					<td align="center" colspan="3">'.substr($fornecedor3['nome'],0,12).'</td>
					<td align="center" colspan="1"> &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</td>
				</tr>';
				
				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Material</td>
					<td align="center">Quantidade</td>
					<td align="center">Unidade</td>
					<td align="center">Preço Unit</td>
					<td align="center">Total</td>
					<td align="center">Preço Unit</td>
					<td align="center">Total</td>
					<td align="center">Preço Unit</td>
					<td align="center">Total</td>
					<td align="center" colspan="4">Gerenciar</td>
				</tr>';
				
			foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				echo '<td>'.$material['nome'].'</td>';
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="right">'.$mostra['unidade'].'</td>';
				
				echo '<td align="right">'.converteValor($mostra['valor1']).'</td>';
				
				$total1=$mostra['quantidade']*$mostra['valor1'];
				$total2=$mostra['quantidade']*$mostra['valor2'];
				$total3=$mostra['quantidade']*$mostra['valor3'];
				
				$totalGeral1+=$total1;
				$totalGeral2+=$total2;
				$totalGeral3+=$total3;
				
				if($total1<$total2 and $total1<$total3){
					 echo '<td align="center"><span class="badge bg-green">'.converteValor($total1).'</span></td>';
				}else{
					echo '<td align="center">'.converteValor($total1).'</td>';
				}
				
				
				echo '<td align="right">'.converteValor($mostra['valor2']).'</td>';
					
				if($total2<$total1 and $total2<$total3){
					 echo '<td align="center"><span class="badge bg-green">'.converteValor($total2).'</span></td>';
				}else{
					echo '<td align="right">'.converteValor($total2).'</td>';
				}
				
				echo '<td align="right">'.converteValor($mostra['valor3']).'</td>';
				
				if($total3<$total1 and $total3<$total2){
					 echo '<td align="center"><span class="badge bg-green">'.converteValor($total3).'</span></td>';
				}else{
					echo '<td align="center">'.converteValor($total3).'</td>';
				}
				
				$total++;
					
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/compras-material-editar&compraMaterialEditar='.$mostra['id'].'">
							<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
					</td>';

					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/compras/compras-material-editar&compraMaterialDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" />
              			</a>
					</td>';
				
						
			echo '</tr>';
		
		 endforeach;
		 
		  echo '<tfoot>';
				
         		echo '<tr>';
                	echo '<td></td>';
					echo '<td>Sub-Total</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($totalGeral1).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($totalGeral2).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($totalGeral3).'</td>';
                echo '</tr>';
				
				
				$desconto1=($totalGeral1*$edit['fornecedor1_desconto'])/100;
				$desconto2=($totalGeral2*$edit['fornecedor1_desconto'])/100;
				$desconto3=($totalGeral3*$edit['fornecedor1_desconto'])/100;
				
				echo '<tr>';
                	echo '<td></td>';
					echo '<td>Desconto</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($desconto1).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($desconto2).'</td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($desconto3).'</td>';
                echo '</tr>';
				
				$totalGeral1=$totalGeral1-$desconto1;
				$totalGeral2=$totalGeral2-$desconto2;
				$totalGeral3=$totalGeral3-$desconto3;
				
				echo '<tr>';
                	echo '<td></td>';
					echo '<td>Total Líquido</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
				
					if($totalGeral1<=$totalGeral2 and $totalGeral1<=$totalGeral3){
						echo '<td align="right"><span class="badge bg-green">'.converteValor($totalGeral1).'</span></td>';
					}else{
						echo '<td align="right">'.converteValor($totalGeral1).'</td>';
					}
				
					
					echo '<td></td>';
					if($totalGeral2<=$totalGeral1 and $totalGeral2<=$totalGeral3){
						echo '<td align="right"><span class="badge bg-green">'.converteValor($totalGeral2).'</span></td>';
					}else{
						echo '<td align="right">'.converteValor($totalGeral2).'</td>';
					}
					echo '<td></td>';
					if($totalGeral3<=$totalGeral1 and $totalGeral3<=$totalGeral2){
						echo '<td align="right"><span class="badge bg-green">'.converteValor($totalGeral3).'</span></td>';
					}else{
						echo '<td align="right">'.converteValor($totalGeral3).'</td>';
					}
                echo '</tr>';
				
          echo '</tfoot>';
		
		echo '</table>';
		
		$link = 'painel.php?execute=suporte/estoque/material-retirada&pag=';
	     pag('estoque_material_retirada',"WHERE id ORDER BY data DESC", $maximo, $link, $pag);
		
		}
	?>

				<div class="box-footer">
					<?php echo $_SESSION['cadastro'];
					unset($_SESSION['cadastro']);
					?>
				</div>
						<!-- /.box-footer-->

					</div>
					<!-- /.box-body table-responsive -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col-md-12 -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
	
	
	
	
</div><!-- /.content-wrapper -->