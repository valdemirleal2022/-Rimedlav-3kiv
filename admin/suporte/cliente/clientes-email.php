<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}

	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-cliente-sem-email-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		header( 'Location: ../admin/suporte/relatorio/relatorio-cliente-sem-email-excel.php' );
	}

 	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
  <section class="content-header">
          <h1>Clientes</h1>
          <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Clientes</li>
            <li class="active">Cadastro</li>
          </ol>
  </section>
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
		 
           	<div class="box-header">       
                  <div class="col-xs-10 col-md-4 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
            
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                  </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
        </div> <!-- /.box-header -->
	   
   <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  

	<?php 
	
    $total=0;
	$leitura = read('cliente',"WHERE id ORDER BY email ASC, email_financeiro ASC");
		
	if($leitura){
			echo '<table class="table table-hover">
				<tr class="set">
					<td align="center">Nome</td>
					<td align="center">Email</td>
					<td align="center">Financeiro 1</td>
					<td align="center">Financeiro 2</td>
					<td align="center">Enviar</td>
					<td align="center" colspan="6">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
			$listar='SIM';
			$emailTeste=substr($mostra['email'],0,2);
		
			if(is_numeric($emailTeste)){
                $listar='SIM';
			}else{
				$listar='NAO';
				
			}
		
			$emailTeste=substr($mostra['email'],0,2);
		
			if(is_numeric($emailTeste)){
                $listar='SIM';
			}else{
				$listar='NAO';
				
			}
		
			if(empty($mostra['email'])){
               $listar='SIM';
			}
	
			if($listar=='SIM'){
		 		$total++;
				echo '<tr>';
		
				
				echo '<td>'.substr($mostra['nome'],0,10).'</td>';
				echo '<td>'.$mostra['email'].'</td>';
				echo '<td>'.$mostra['email_financeiro'].'</td>';
				echo '<td>'.$mostra['email_financeiro2'].'</td>';
				
				 if($mostra['nao_enviar_email'] == '1'){
					 echo '<td>N</td>';
				 }elseif($mostra['nao_enviar_email'] == '0'){
					 echo '<td>S</td>';
				 }else{
					  echo '<td>-</td>';
				 }
				
				$clienteId = $mostra['id'];
				$contrato = mostra('contrato',"WHERE id_cliente ='$clienteId'");
//				
//				$consultorId=$contrato['consultor'];
//				
//				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId'");
//				echo '<td>'.$consultor['nome'].'</td>';
			
					echo '<td align="center">
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$mostra['id'].'">
								<img src="ico/editar-cliente.png" alt="Editar Cliente" title="Editar Cliente" />
							</a>
						  </td>';
		
		
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-cliente&clienteId='.$mostra['id'].'">
							<img src="ico/agenda.png" alt="Contrato Cronograma" title="Contrato Cronograma"  />
							</a>
						  </td>';
	
					
					echo '</tr>';
				
			}
			 endforeach;
	
		 echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="14">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
        
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