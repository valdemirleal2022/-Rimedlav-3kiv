<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	$data1 = date( "Y/m/d" );
	$data2 = date( "Y/m/d" );

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-receita-pagas-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1=strip_tags(trim(mysql_real_escape_string($_POST['inicio'])));
		$data2=strip_tags(trim(mysql_real_escape_string($_POST['fim'])));
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		header( 'Location: ../admin/suporte/relatorio/relatorio-receita-pagas-excel.php' );
	}

	
	if(isset($_POST['pesquisar_numero'])){
		$receberId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($receberId)){
			echo '<div class="alert alert-warning">Número de recebimento Inválido!</div><br />';
		}else{
			header('Location: painel.php?execute=suporte/receber/receber-editar&receber-editar='.$receberId.'');
		}
	}

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
	 	
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$banco=$_POST['banco'];
		$formpag=$_POST['formpag'];
	
		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'",'valor');
		
	   $juros_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'",'juros');
		
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'");

	}

	if(!empty($banco)){
		
		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND banco='$banco' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'AND banco='$banco'",'valor');
		
		$juros_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado'AND banco='$banco'",'juros');
		
		
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco'");
	}

	if(!empty($formpag)){
		
		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND formpag='$formpag' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND formpag='$formpag'",'valor');
		
		$juros_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND formpag='$formpag'",'juros');
		
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND formpag='$formpag'");
	}
 

	if(!empty($banco) AND !empty($formpag) ){

		$leitura = read('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' AND status='Baixado' AND banco='$banco' AND formpag='$formpag' ORDER BY pagamento ASC");

		$valor_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco' AND formpag='$formpag'",'valor');
		
		$juros_total = soma('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco' AND formpag='$formpag'",'juros');
		
		$total = conta('receber',"WHERE pagamento>='$data1' AND pagamento<='$data2' 
													AND status='Baixado' AND banco='$banco' AND formpag='$formpag'");
	}

	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Receitas Quitadas</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Receber</a>
     	<li class="active">Quitados</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
           
          <div class="box-header"> 

              <div class="col-xs-10 col-md-9 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
						 
                         <div class="form-group pull-left">
                         <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
							 
						  <div class="form-group pull-left">
                            <select name="banco" class="form-control input-sm">
                                <option value="">Selecione Banco</option>
                                <?php 
                                    $readBanco = read('banco',"WHERE id");
                                    if(!$readBanco){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readBanco as $mae):
                                           if($banco == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>
            			</div>

                       <div class="form-group pull-left">
                            <select name="formpag" class="form-control input-sm">
                                <option value="">Forma de Pagamento</option>
                                <?php 
                                    $readFormpag = read('formpag',"WHERE id");
                                    if(!$readFormpag){
                                        echo '<option value="">Não temos Forma de Pagamento no momento</option>';	
                                    }else{
                                        foreach($readFormpag as $mae):
                                           if($formpag == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>
                        </div>
                            
                         <div class="form-group pull-left">
                            <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                            </div>  <!-- /.input-group -->
                            
                        <div class="form-group pull-left">
                          <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
                           </div>  <!-- /.input-group -->
                            
                        <div class="form-group pull-left">
                           <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
                        </div>   <!-- /.input-group -->
							 
                    </form> 
                  </div><!-- /col-xs-4-->

         </div><!-- /box-header-->   
    
    </div><!-- /box-header-->   
    
   <div class="box-body table-responsive">
   	 <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
     		
	<?php 

	if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Contrato</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Desc</td>
					<td align="center">Juros</td>
					<td align="center">Vencimento</td>
					<td align="center">Pagamento</td>
					<td align="center">FormPag/Banco</td>
					<td align="center">Nota</td>
					<td align="center">Status</td>
					<td align="center">S</td>
					<td align="center">J</td>
					<td align="center">Motivo</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				echo '<td>'.$mostra['id'].'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$clienteId = $mostra['id_cliente'];
		
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
		
				echo '<td>'.$contrato['id'].'</td>';
		
				$cliente = mostra('cliente',"WHERE id ='$clienteId '");
				if(!$cliente){
					echo '<td align="center">Cliente Nao Encontrado</td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,18).'</td>';
				}
			 
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="right">'.converteValor($mostra['desconto']).'</td>';
				echo '<td align="right">'.converteValor($mostra['juros']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
				echo '<td align="center">'.converteData($mostra['pagamento']).'</td>';
				
				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formapagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formapagId'");
				echo '<td align="center">'.$banco['nome']. "|".substr($formapag['nome'],0,6).'</td>';
				echo '<td align="center">'.$mostra['nota'].'</td>';
		
				if($contrato['status']==5){
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											alt="Contrato Ativo" title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											alt="Contrato Suspenso" title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-cancelado.png" 
										alt="Contrato Cancelad" title="Contrato Cancelado" /> </td>';
				}else{
					echo '<td align="center">!</td>';
				}
		 
				if($mostra['serasa']=='1'){
				  echo '<td align="center"><img src="ico/serasa.png" title="Serasa"/></td>';
				}else{
				  echo '<td align="center"></td>';			
				}
				
				if($mostra['juridico']=='1'){
				  echo '<td align="center"><img src="ico/juridico.png" title="Juridico"/></td>';
				}else{
				  echo '<td align="center"></td>';			
				}
		
				if($contrato['status']==6){
					$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato ='$contratoId' ORDER BY data ASC");
					echo '<td align="left">'.substr($contratoBaixa['motivo'],0,10).'</td>';
				}else{
					 echo '<td align="center"></td>';			
				}
				
				echo '<td align="center">
				<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				if(empty($mostra['link'])){
					echo '<td align="center">-</td>';
				}else{
					 echo '<td align="center">
						<a href="'.$mostra['link'] .'" target="_blank">
							<img src="../admin/ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" class="tip" />              			</a>
				      </td>';
				}
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/receber/receber-editar&receberDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
           echo '<tr>';
            echo '<td colspan="15">' . 'Total de registros : '.$total.'</td>';
          echo '</tr>';
                       
          	echo '<tr>';
           echo '<td colspan="15">' . 'Total de Juros R$ : '.converteValor($juros_total).'</td>';
           echo '</tr>';
						
		echo '<tr>';
            echo '<td colspan="15">'.'Total Recebido R$ : '.converteValor($valor_total).'</td>';
          echo '</tr>';
  
       echo '</tfoot>';
		 	 
	 echo '</table>';
	
		}
				   
				
		?>
		  </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
       
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