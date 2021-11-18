<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autpos_venda']['id'])){
			header('Location: painel.php');	
		}	
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];

	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	$_SESSION['aba']=0;

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
          
	
	   
    <div class="box-body table-responsive">

	<?php 

   
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
													ORDER BY endereco ASC");  
		}
	}else{
		$total = conta('cliente',"WHERE id AND status='1'");
		$leitura = read('cliente',"WHERE id ORDER BY data DESC");
	}
		
	if($leitura){
			echo '<table class="table table-hover">
				<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Telefone</td>
					<td align="center" colspan="6">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
			
			$listar='Nao';
			$clienteId = $mostra['id'];
			$leituraContrato = read('contrato',"WHERE id_cliente ='$clienteId'");
			foreach($leituraContrato as $contrato):
				if($pos_vendaId==$contrato['pos_venda']){
					$listar='Sim';
				}
			endforeach;
	 
		
			if($listar=='Sim'){
		 			echo '<tr>';
					echo '<td>'.$mostra['id'].'</td>';
					echo '<td>'.substr($mostra['nome'],0,30).'</td>';
				//	$endereco=substr($mostra['endereco'],0,50).','.$mostra['numero'].' - '.$mostra['complemento'];
				//	echo '<td>'.$endereco.'</td>';
	
				//	echo '<td>'.substr($mostra['bairro'],0,10).'</td>';
					echo '<td>'.substr($mostra['telefone'],0,10).'</td>';
							  
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-cliente&clienteId='.$mostra['id'].'">
							<img src="../admin/ico/agenda.png" alt="Contrato Cronograma" title="Contrato Cronograma"  />
							</a>
						  </td>';
					
					echo '</tr>';
		 
			}
		
		 endforeach;
	 
		 echo '</table>';
		 
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

