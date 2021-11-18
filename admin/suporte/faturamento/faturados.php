<head>
    <meta charset="iso-8859-1">
</head>


<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$contratoTipo = $_POST['contrato_tipo'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['contratoTipo']=$contratoTipo;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-faturados-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$contratoTipo = $_POST['contrato_tipo'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['contratoTipo']=$contratoTipo;
		header( 'Location: ../admin/suporte/relatorio/relatorio-faturados-excel.php' );
	}



	if(!isset($_SESSION['inicio'])){
		$data1 = date( "Y/m/d");
		$data2 = date( "Y/m/d");
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['contratoTipo']=$contratoTipo;
		
	}else{
		$data1=$_SESSION['inicio'];
		$data2=$_SESSION['fim'];
	}

	if(isset($_POST['pesquisar-dia'])){
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$contratoTipo = $_POST['contrato_tipo'];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['contratoTipo']=$contratoTipo;
	}
	
	$total = conta('receber',"WHERE id AND emissao>='$data1' AND emissao<='$data2'");
	$leitura = read('receber',"WHERE id AND emissao>='$data1' AND emissao<='$data2'	ORDER BY emissao ASC");

	if (isset($_POST['enviar_boleto_correio'])) {
		$total = conta('receber',"WHERE id AND emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1'");
		$leitura = read('receber',"WHERE id AND emissao>='$data1' AND emissao<='$data2' AND enviar_boleto_correio='1' ORDER BY emissao ASC");
		$enviar_boleto_correio= "checked='CHECKED'";
		$_SESSION['enviar_boleto_correio']='1';
	}
	
	if (!empty($contratoTipo)) {
		$total = conta('receber',"WHERE id AND emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo'");
		$leitura = read('receber',"WHERE id AND emissao>='$data1' AND emissao<='$data2' AND contrato_tipo='$contratoTipo' ORDER BY emissao DESC");
	}

	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Faturamento</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="painel.php?execute=suporte/faturamento/construir-faturamento">Faturamento</a></li>
         </ol>
 </section>
 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
      <div class="box-header with-border">
         <h3 class="box-title">NFe/Boleto</h3>
       </div><!-- /.box-header -->
     
       <div class="box-header">
          <div class="row">
          		<div class="col-md-12">  
				 <div class="col-xs-10 col-md-3 pull-left">
					   <a href="painel.php?execute=suporte/receber/nfe-emissao" class="btnnovo">
						<img src="ico/nota.png" alt="Remessa" title="Remessa" class="tip" />
						<small>1 - Gerar Remessa NFe</small>
					</a> 
				 </div><!-- /col-xs-4-->   

				 <div class="col-xs-10 col-md-3 pull-left">
					   <a href="painel.php?execute=suporte/receber/aviso-a-vencer" class="btnnovo">
						<img src="ico/email.png" alt="Remessa" title="Remessa" class="tip" />
						<small>2 - Enviar Comunicado por Email</small>
					</a> 
				</div><!-- /col-xs-4-->  
                
                 <div class="col-xs-10 col-md-3 pull-left">
                       <a href="painel.php?execute=suporte/receber/boleto-emissao" class="btnnovo">
                        <img src="ico/download.png" alt="Remessa" title="Remessa" class="tip" />
                        <small>3 - Gerar Remessa Boleto </small>
                    </a> 
                 </div><!-- /col-xs-4-->   
   
    	     
     	       </div><!-- /col-md-12-->   
     	    </div><!-- /row-->   
        </div><!-- /box-header-->   
   

 		<div class="box-header">
          <div class="row">  
          	 
          	  	 <div class="col-xs-10 col-md-12 pull-left">
          	  
          	  
             	   <div class="col-xs-10 col-md-7 pull-right">
                     
                      <form name="form-pesquisa" method="post" class="form-inline " role="form">
						  						  
						    <div class="form-group pull-left">
								<select name="contrato_tipo" class="form-control input-sm">
									<option value="">Selecione o Tipo</option>
									<?php 
										$readContrato = read('contrato_tipo',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($contratoTipo == $mae['id']){
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
                             <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
						</div>
						  
						<div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
						</div>
            	         
             	        <div class="form-group pull-left">
                                    <button class="btn btn-sm btn-default" name="pesquisar-dia" type="submit"><i class="fa fa-search" title="Pesquisar" title="Pesquisar"></i></button>                   
                         </div><!-- /.input-group -->
                          
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                            </div>   <!-- /.input-group -->
           
                     </form> 
              </div><!-- /col-xs-6 col-md-4 pull-right-->
              
              	<div class="col-xs-10 col-md-3 pull-right">
				  <form name="formPesquisa" method="post" class="form-inline " role="form">
                      <input type="checkbox"  name="enviar_boleto_correio" <?PHP echo $enviar_boleto_correio; ?>  class="minimal"  onclick="this.form.submit()"/>
                        <!--  <input type="checkbox" onclick="this.form.submit()"/>-->
                       <small> Boleto Enviados por Correio</small>
                     </form>
            	  </div><!-- /col-xs-6 col-md-5 pull-right--> 
            	  
          
      </div><!-- /col-md-12-->
    </div><!-- /row-->   
   </div><!-- /box-header-->    
    
    <div class="box-body table-responsive">
    
      <?php echo $_SESSION['retorna'];
			unset($_SESSION['retorna']);
			?> 
    
    <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
     
 	<?php 
	
	$valor_total=0;
    if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td>Id</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Emissão</td>
					<td align="center">Vencimento</td>

					<td>NF</td>
					<td align="center">NFe</td>
					
					<td align="center">Ban</td>
					<td align="center">Rem</td>
					<td align="center">Ret</td>
					<td>BI</td>
					<td align="center">Status</td>
					<td align="center">Interação</td>
					<td colspan="7" align="center">Gerenciar</td>
				</tr>';
		
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
		
				echo '<td align="center">'.$mostra['id'].'</td>';
		
				$contratoId = $mostra['id_contrato'];
				$contrato = mostra('contrato',"WHERE id ='$contratoId'");
				$contratoTipo = $contrato['contrato_tipo'];
				$enviar_boleto_correio = $mostra['enviar_boleto_correio'];

				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,25).'</td>';
				
				$dias=30;
				// contrato suspenso temporariamente
				$contratoBaixa = mostra('contrato_baixa',"WHERE id_contrato = '$contratoId' AND tipo='8'");
				if($contratoBaixa){
					$contratoBaixa['data'];
					$diferenca = strtotime( $mostra['emissao'] ) - strtotime($contratoBaixa['data']);
					$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
				}
				
				 	
				if($dias<20 and $contrato['status']=='5' ){
				 	echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				 }else{
					echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				 }
 
				$valor_total=$valor_total+$mostra['valor'];
		
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
		
				$gerarNfe='';
		
			   //MARCA REGISTRO PARA GERAR ARQUIVO DE REMESSA NFE/BANCO
				if($contrato['nota_fiscal']=='1'){
					
					if ( $contrato[ 'nota_no_faturamento' ] == '1' ) {
						
						$receberId = $mostra['id'];
						$emissao = $mostra['emissao'];
						$cad['nota_emissao']= $emissao;
						$cad['contrato_tipo']= $contratoTipo;
						$cad['enviar_boleto_correio']= $enviar_boleto_correio;
						
						$gerarNfe='S';
						
					}else{
						
						if(empty($mostra['nota'])){
							
							$receberId = $mostra['id'];
							$emissao = $mostra['emissao'];
							$cad['nota_emissao']= null;
							$cad['contrato_tipo']= $contratoTipo;
							$cad['enviar_boleto_correio']= $enviar_boleto_correio;
							$cad['valor_nota_fiscal']=  $mostra['valor'];;
						}
						
						$gerarNfe='Nd';
					}
					
				}else{
					
					$receberId = $mostra['id'];
					$emissao = $mostra['emissao'];
					$cad['nota_emissao']= null;
					$cad['contrato_tipo']= $contratoTipo;
					$cad['enviar_boleto_correio']= $enviar_boleto_correio;
					$gerarNfe='N';
				}
				echo '<td align="center">'.$gerarNfe.'</td>';
		
				$enviaEmail=0;
				$email=substr($cliente['email_financeiro'],0,2);
				if(is_numeric($email)){
				   $cad[ 'enviar_boleto_correio' ] = '1';
				}
		
			  	// BOLETO BANCARIO
				if ($contrato[ 'boleto_bancario' ] == '1' ) {
					$cad['remessa_data']= $emissao;
				}
		
				update('receber',$cad,"id = '$receberId'");
		
				if ($contrato[ 'contrato_tipo' ] == '1' ) {
					if ($mostra[ 'banco' ] == '8' ) {
						$cad[ 'banco' ] = '1';
					//	update('receber',$cad,"id = '$receberId'");
					}
				}
		
				if ($contrato[ 'contrato_tipo' ]>'18'){
					//delete('receber',"id = '$receberId'");
				}
				
				echo '<td align="center">'.$mostra['nota'].'</td>';
				echo '<td align="center">'.$mostra['banco'].'</td>';
		
				
				if(!empty($mostra['remessa'])){
					echo '<td align="center">OK</td>';
				}else{
					echo '<td align="center">-</td>';
				}
				echo '<td align="center">'.substr($mostra['retorno'],0,1).'</td>';

				if(empty($mostra['imprimir'])){
					echo '<td align="center">-</td>';
				}else{
					echo '<td align="center">*</td>';
				}
		
				if($contrato['status']==5) {
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											 title="Contrato Ativo" />  </td>';
				}elseif($contrato['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==7){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($contrato['status']==9){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
										 title="Contrato Rescindo" /> </td>';
				}elseif($contrato['status']==10){
					echo '<td align="center"><img src="ico/juridico.png" 
										 title="Contrato no Juridico" /> </td>';
				}elseif($contrato['status']==19){
					echo '<td align="center"><img src="ico/contrato-suspenso-temporario.png" 
											 title="Contrato Suspenso Temporario" /> </td>';
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}

				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
		
				
						
				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar"  />
              			</a>
				      </td>';
		
				if( $cad[ 'enviar_boleto_correio' ]<>'1'){
					echo '<td align="center">
								<a href="painel.php?execute=suporte/receber/receber-editar&receberEnviar='.$mostra['id'].'">
									<img src="ico/email.png" alt="Aviso" title="Aviso-Email" />
								</a>
								</td>';
				}else{
						echo '<td align="center">
							<a href="#">
								<img src="ico/correios.png" alt="Enviar por Correio" title="Enviar por Correio" />
							</a>
							</td>';
				}
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$contratoId.'">
								<img src="ico/visualizar.png" alt="Contrato Visualizar" title="Contrato Visualizar"  />
							</a>
						  </td>';	
		
				echo '<td align="center">
						<a href="../cliente/painel2.php?execute=boleto/emitir-boleto&boletoId='.$mostra['id'].'" target="_blank">
							<img src="ico/boleto.png" alt="Boleto" title="Boleto"  />
              			</a>
						</td>';		

				if(empty($mostra['link'])){
					echo '<td align="center">
						<a href="../cliente/painel2.php?execute=suporte/contrato/rps&rps='.$mostra['id'].'" target="_blank">
							<img src="ico/rps.png" alt="RPS" title="RPS"  />
              			</a>
						</td>';	
				}else{
					 echo '<td align="center">
						<a href="'.$mostra['link'] .'" target="_blank">
							<img src="../admin/ico/nota.png" alt="Nota Fiscal" title="Nota Fiscal" </a>
				      </td>';
				}
	
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
			echo '<tr>';
				echo '<tr>';
                	echo '<td colspan="16">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
                echo '<tr>';
                	echo '<td colspan="16">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
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