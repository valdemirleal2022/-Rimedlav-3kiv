<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$valor_total = soma('receber',"WHERE desconto_autorizar='1' AND  desconto_autorizacao='0'",'valor');

	$total = conta('receber',"WHERE desconto_autorizar='1' AND desconto_autorizacao='0'");
	
	$leitura = read('receber',"WHERE desconto_autorizar='1' AND desconto_autorizacao='0' ORDER BY desconto_data DESC ");

	if(isset($_POST['pesquisar_numero'])){
		$receberId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$leitura = read('receber',"WHERE id='$receberId'");
		$total = conta('receber',"WHERE id='$receberId'");
		$valor_total = soma('receber',"WHERE id='$receberId'",'valor');
	}

	 
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Solicitar Desconto</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Receber</a>
     	<li class="active">Desconto</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

    
    		 <div class="box-header">
               <div class="row">
				   
				   
                   <div class="col-xs-6 col-md-2 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="Boleto">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                       
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
				 
				   
                    <div class="col-xs-10 col-md-5 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                           <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                          
                    </form> 
                  </div><!-- /col-xs-4-->

               
          </div><!-- /row-->   
       </div><!-- /box-header-->   
    

    <div class="box-body table-responsive">
    
	<?php 

	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Desconto</td>
					<td align="center">Proximo Faturamento</td>
					<td align="center">Observacao</td>
					<td align="center">Status</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td align="center">'.$mostra['id'].'</td>';
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
	
				echo '<td align="right">'.converteValor($mostra['desconto_valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['desconto_data']).'</td>';
				echo '<td>'.substr($mostra['desconto_observacao'],0,30).'</td>';
			 
				if($mostra['desconto_autorizacao'] == '1'){
					echo '<td align="center">Autorizado</td>';
				} else if($mostra['desconto_autorizacao'] == '2'){
					echo '<td align="center">Autorizado</td>';
				} else if($mostra['desconto_autorizacao'] == '0'){
					echo '<td align="center">Solicitar Autorização</td>';
		
				}else{
					echo '<td align="center">-</td>';
				}
 
 				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" />
              			</a>
				      </td>';
		
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Visualizar" title="Contrato Visualizar"  />
							</a>
						  </td>';	
		
			echo '</tr>';
		 endforeach;
		 
			 
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="14">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="14">' . 'Total Valor R$ : ' . number_format($valor_total,2,',','.') . '</td>';
                        echo '</tr>';
  
          echo '</tfoot>';
		 
		 
		 echo '</table>';
		 
	}
  ?>
	  <div class="box-footer">
			<?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?> 
	  </div><!-- /.box-footer-->
       
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
</section><!-- /.content -->
  
</div><!-- /.content-wrapper -->