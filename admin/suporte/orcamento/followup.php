<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}

	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

	$valor_total = soma('cadastro_visita',"WHERE id AND status='3'",'orc_valor');
	$total = conta('cadastro_visita',"WHERE id AND status='3'");
	$leitura = read('cadastro_visita',"WHERE id AND status='3' ORDER BY orc_data DESC, orc_hora ASC");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-followup-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-followup-excel.php' );
	}


	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$consultorId = $_POST['consultor'];
		
		$total = conta('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2'");
		
		$leitura = read('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' ORDER BY orc_data ASC, orc_hora ASC");
		
		$valor_total = soma('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2'",'orc_valor');
			
		
		if(!empty($consultorId)){
			
			$total = conta('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId'");
			
			$valor_total = soma('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId'",'orc_valor');
			
			$leitura = read('cadastro_visita',"WHERE id AND status='3' AND orc_data>='$data1' AND orc_data<='$data2' AND consultor='$consultorId' ORDER BY orc_data ASC, orc_hora ASC");
		}
		
	}
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	
	$_SESSION['url2']=$_SERVER['REQUEST_URI'];
	
?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>FollowUp dos Orçamentos</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Orcamento</a>
     	<li class="active">FollowUp</li>
     </ol>
 </section>

 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
    
     <div class="box box-default">
     
			  <div class="box-header">
				<a href="painel.php?execute=suporte/orcamento/orcamento-aviso" class="btnnovo">
					<img src="ico/email.png" alt="Aviso" title="Aviso para propostas" class="imagem" />
					<small>Proposta em Aberto</small>
				</a> 
			  </div><!-- /box-header-->
      
			 <div class="box-header">
        	 <div class="col-xs-8 col-md-9 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
					
                           <div class="form-group pull-left">
								<select name="consultor" class="form-control input-sm">
									<option value="">Consultor</option>
									<?php 
										$readContrato = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($consultorId == $mae['id']){
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
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
             </div>
        <!-- /.box-header -->
            
     
    
    <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
			<div class="box-body table-responsive">
	<?php 

	
	
	if($leitura){
		 echo '<table class="table table-hover">	
					<tr class="set">
					<td>Id</td>
					<td>Nome</td>
					<td>Telefone</td>
					<td align="center">Consultor</td>
					<td align="center">Orçamento</td>
					<td align="center">Valor</td>
					<td align="center">Interaçao</td>
					<td align="center" colspan="6">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';


				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td align="left">'.substr($mostra['nome'],0,30).'</td>';
				echo '<td align="left">'.substr($mostra['telefone'],0,15).'</td>';
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td align="left">'.$consultor['nome'].'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['orc_data'])).'</td>';
				echo '<td align="right">'.(converteValor($mostra['orc_valor'])).'</td>';
					
				
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
			  				<img src="../admin/ico/editar.png" alt="Editar" title="Editar Orçamento" />
              			</a>
				      </td>';
		
					
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEnviar='.$mostra['id'].'">
							<img src="../admin/ico/email.png" alt="Enviar Proposta" title="Enviar Proposta" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="../cliente/painel2.php?execute=suporte/orcamento/imprimir-proposta&orcamentoId='.$mostra['id'].'" target="_blank">
							<img src="../admin/ico/imprimir.png" alt="imprimir Proposta" title="Imprimir Proposta" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoBaixar='.$mostra['id'].'">
			  				<img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar Orçamento" />
              			</a>
				      </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoAprovar='.$mostra['id'].'">
							<img src="../admin/ico/aprovado.png" alt="Aprovar Contrato" title="Aprovar Contrato" class="tip" />
              			</a>
						</td>';
		
			echo '</tr>';
		endforeach;
		 
			echo '<tfoot>';
				   echo '<tr>';
				   echo '<td colspan="14">'.'Total de registros : ' .  $total . '</td>';
				   echo '</tr>';
				   echo '<tr>';
				   echo '<td colspan="14">'.'Total R$ : '.converteValor($valor_total).'</td>';
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

	    </div><!--/col-md-12 scrool-->   
	  </div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
 	  	  
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->