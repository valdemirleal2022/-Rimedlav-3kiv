<?php 

		if(!empty($_GET['cotacaoEditar'])){
			$cotacaoId = $_GET['cotacaoEditar'];
			$acao = "atualizar";
		}
		 
		if(!empty($cotacaoId)){
			
			$readcompra= read('estoque_compras',"WHERE id = '$cotacaoId'");
			if(!$readcompra){
				header('Location: painel.php?execute=suporte/error');	
			}
			foreach($readcompra as $edit);
 
			$tipo=$edit['id_material'];
			
			$_SESSION['url']=$_SERVER['REQUEST_URI'];

		}
 

?>
 
<div class="content-wrapper">
	
  <section class="content-header">
          <h1>Cotação</h1>
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

		$cad['interacao'] = date('Y/m/d H:i:s');
		$cad['fornecedor3_pag'] = mysql_real_escape_string($_POST['fornecedor3_pag']);
		$cad['fornecedor3_prazo'] = mysql_real_escape_string($_POST['fornecedor3_prazo']);
		$cad['fornecedor3_desconto'] = mysql_real_escape_string($_POST['fornecedor3_desconto']);
		if(in_array('',$cad)){
			echo '<div class="alert alert-warning">Todos os campos são obrigatórios!</div>';	
		 }else{
			update('estoque_compras',$cad,"id = '$cotacaoId'");	
			$_SESSION['cadastro'] = '<div class="alert alert-success">Cadastrado com sucesso</div>';
			header("Location: ".$_SESSION['url']);
		}
	}
		
	
	?>
			 
	
    <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">
		
		 <div class="form-group col-xs-12 col-md-12">  

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
							$readContrato = read('estoque_material_tipo',"WHERE id  ORDER BY codigo ASC");
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
               <input type="date" name="data" class="form-control pull-right" value="<?php echo $edit['data'];?>" disabled />
                </div><!-- /.input group -->
           </div> 
		
	   </div>	
			 
	 <div class="form-group col-xs-12 col-md-12">  

		 <div class="form-group col-xs-12 col-md-4">  
            <label>Fornecedor</label>
                <select name="fornecedor3" class="form-control" disabled>
                    <option value="">Selecione o Fornecedor</option>
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
			
	</div>	
		
	 
	 
    	<div class="form-group col-xs-12 col-md-12">  
             
		 <div class="box-footer">
			 
		<?php 
				 
		echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
				 
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
		

		<div class="box-body table-responsive">

			<?php 
			
			$fornecedorId = $edit['fornecedor3'];
			$fornecedor3 = mostra('estoque_fornecedor',"WHERE id ='$fornecedorId'");
		
			$leitura = read('estoque_compras_material',"WHERE id AND id_compras='$cotacaoId' 
						ORDER BY id ASC");
			
			$total=0;
			$totalGeral=0;

			if($leitura){

				echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Material</td>
					<td align="center">Quantidade</td>
					<td align="center">Unidade</td>
					<td align="center">Preço Unitário</td>
					<td align="center">Total</td>
					<td align="center" colspan="1">Gerenciar</td>
				</tr>';
				
			foreach($leitura as $mostra):
			
	 
		 	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				
				$materialId = $mostra['id_material'];
				$material = mostra('estoque_material',"WHERE id ='$materialId'");
				echo '<td>'.$material['nome'].'</td>';
				
				echo '<td align="right">'.$mostra['quantidade'].'</td>';
				echo '<td align="center">'.$mostra['unidade'].'</td>';
				
				$total = $mostra['quantidade']*$mostra['valor3'];
				
				$totalGeral = $totalGeral+$total;
			
				echo '<td align="right">'.converteValor($mostra['valor3']).'</td>';
				echo '<td align="center">'.converteValor($total).'</td>';

				$total++;
					
				echo '<td align="center">
			  			<a href="painel2.php?execute=cotacao/cotacao-material-editar3&cotacaoMaterialEditar='.$mostra['id'].'">
							<img src="../admin/ico/editar.png"  title="Editar" />
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
					echo '<td align="right">'.converteValor($totalGeral).'</td>';
					echo '<td></td>';
					
                echo '</tr>';

				$desconto=($totalGeral*$edit['fornecedor3_desconto'])/100;
				
				echo '<tr>';
                	echo '<td></td>';
					echo '<td>Desconto</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td align="right">'.converteValor($desconto).'</td>';

                echo '</tr>';
				
				$totalGeral=$totalGeral-$desconto;
				
				echo '<tr>';
                	echo '<td></td>';
					echo '<td>Total Líquido</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
				
					echo '<td align="right">'.converteValor($totalGeral).'</td>';
					
                echo '</tr>';
				
          echo '</tfoot>';
		
		echo '</table>';
		
		
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