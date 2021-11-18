<head>
    <meta charset="iso-8859-1">
</head>

<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}

	}
		
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	$_SESSION['aba']=0;

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/cliente-etiqueta-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		header( 'Location: ../admin/suporte/relatorio/relatorio-cliente-email-excel.php' );
	}
	 
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
            <a href="painel.php?execute=suporte/cliente/cliente-editar" class="btnnovo">
	   			<img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
    		</a>
            <small>Novo Cliente</small>
       		<div class="box-tools">
              <!--     <a href="painel.php?execute=suporte/cliente/reajuste-valor" class="btnnovo">
			  <img src="ico/extrato.png" alt="Criar Novo" title="Criar Novo" class="tip" />
               Reajuste de Valor-->
       		  </a> 
           </div><!-- /box-tools-->
		 
		   	<div class="col-xs-10 col-md-2 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline " role="form">
						 	 <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relatório Excel"></i></button>
                              </div>   <!-- /.input-group -->
							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa fa-tag" title="Etiqueta PDF"></i></button>
								</div>  <!-- /.input-group -->
                        </form>
          </div>   <!-- /col-xs-10 col-md-3 pull-left-->
       </div><!-- /.box-header -->

	   
    <div class="box-body table-responsive">

	<?php 
		
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
    $maximo = '20';
    $inicio = ($pag * $maximo) - $maximo;
   
	if(isset($_POST['nome'])){
		$pesquisa=strip_tags(trim(mysql_real_escape_string($_POST['pesquisa'])));
		if(!empty($pesquisa)){
			$leitura =read('cliente',"WHERE id AND (
													nome LIKE '%$pesquisa%' OR
													nome_fantasia LIKE '%$pesquisa%' OR
													endereco LIKE '%$pesquisa%' OR 
													email LIKE '%$pesquisa%' OR 
													telefone LIKE '%$pesquisa%' OR
													celular LIKE '%$pesquisa%' OR
													contato LIKE '%$pesquisa%' OR
													cnpj LIKE '%$pesquisa%' OR
													cpf LIKE '%$pesquisa%'
													) 
													ORDER BY endereco");  
		}
	}else{
		$total = conta('cliente',"WHERE id AND status='1'");
		$leitura = read('cliente',"WHERE id ORDER BY data DESC LIMIT $inicio,$maximo");
	}
		
	if($leitura){
			echo '<table class="table table-hover">
				<tr class="set">
					 
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Endereco</td>
					<td align="center">Bairro</td>
					<td align="center">Cidade</td>
					<td align="center">Tipo de Contrato</td>
					<td align="center">Status</td>
					
					<td align="center" colspan="6">Gerenciar</td>
					
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				
				
				echo '<td>'.$mostra['id'].'</td>';
		
				if($mostra['tipo']==4){
					echo '<td>'.substr($mostra['nome'],0,25).' <img src="ico/ouro.png"/></td>';
				}elseif($mostra['tipo']==5){
					echo '<td>'.substr($mostra['nome'],0,25).' <img src="ico/premium.png"/></td>';
				}elseif($mostra['tipo']==6){
					echo '<td>'.substr($mostra['nome'],0,25).' <img src="ico/prata.png"/></td>';
				}else{
					echo '<td>'.substr($mostra['nome'],0,25).'</td>';
				}
		
					$endereco=substr($mostra['endereco'],0,25).', '.$mostra['numero'].' - '.substr($mostra['complemento'],0,10);
					echo '<td>'.$endereco.'</td>';
	
					echo '<td>'.substr($mostra['bairro'],0,25).'</td>';
					echo '<td>'.substr($mostra['cidade'],0,15).'</td>';

					$clienteId = $mostra['id'];
					$contrato = mostra('contrato',"WHERE id_cliente ='$clienteId'");

					$contratoTipoId = $contrato['contrato_tipo'];
					$monstraContratoTipo = mostra('contrato_tipo',"WHERE id ='$contratoTipoId'");
					echo '<td>'.$monstraContratoTipo['nome'].'</td>';
		
					// pegar latituto e longitude atualizado em 04/08/2017
					//$endereco = url($mostra['endereco'].', '.$mostra['numero'].' - '.$mostra['bairro'].' - //'.$mostra['cidade'].' - '.$mostra['cep']);
					
					//if(empty( $mostra['latitude'])){
//						$geo=geo($endereco);
//						$edit['latitude'] = $geo[0];
//						$edit['longitude'] = $geo[1];
//						update('cliente',$edit,"id = '$clienteId'");
//					}
//						
					$statusId = $contrato['status'];
					$status = mostra('contrato_status',"WHERE id ='$statusId '");
					echo '<td>'.$status['nome'].'</td>';
	
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-cliente&clienteId='.$mostra['id'].'">
							<img src="ico/agenda.png" alt="Contrato Cronograma" title="Contrato Cronograma"  />
							</a>
						  </td>';
						  
					echo '<td align="center">
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$mostra['id'].'">
								<img src="ico/editar-cliente.png" alt="Editar Cliente" title="Editar Cliente" />
							</a>
						  </td>';
		
					echo '<td align="center">
							<a href="painel.php?execute=suporte/relatorio/cliente-etiqueta-pdf&clienteEditar='.$mostra['id'].'">
								<img src="ico/etiqueta.png" alt="Etiqueta Cliente" title="Etiqueta Cliente" />
							</a>
						  </td>';
			
				echo '</tr>';
			 endforeach;
		 echo '</table>';
		 
			 if(!isset($_POST['nome'])){       
			   $link = 'painel.php?execute=suporte/cliente/clientes&pag=';
				pag('cliente',"WHERE id ORDER BY nome ASC", $maximo, $link, $pag);
			   }else{

			 }
		  }
        ?>
    
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

