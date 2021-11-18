<?php 
	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Contatos</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="painel.php?execute=suporte/orcamento/contatos">Contatos</a></li>
         </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

    <div class="box-body table-responsive">
    
 
	<?php 
  
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '40';
	$inicio = ($pag * $maximo) - $maximo;
	
	$leitura = read('contato',"WHERE id ORDER BY interacao DESC LIMIT $inicio,$maximo");
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td>Nome</td>
					<td>Email</td>
					<td>Telefone</td>
					<td>Solicitaçao</td>
					<td align="center">Data</td>
					<td align="center">Interaçao</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
			
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td>'.$mostra['nome'].'</td>';
				echo '<td>'.$mostra['email'].'</td>';
				echo '<td>'.$mostra['telefone'].'</td>';
				echo '<td>'.$mostra['solicitacao'].'</td>';
				echo '<td>'.converteData($mostra['data']).'</td>';
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				echo '<td>'.$mostra['status'].'</td>';
	
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/contato-editar&contatoBaixar='.$mostra['id'].'">
			  				<img src="ico/email.png" alt="Enviar" title="Enviar Resposta" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/orcamento/contato-editar&contatoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
				echo '</tr>';
		 endforeach;
		 echo '</table>';
		 $link = 'painel.php?execute=suporte/orcamento/contato&pag=';
	     pag('contato',"WHERE id ORDER BY interacao DESC", $maximo, $link, $pag);
		
		}
	  ?>
      
     <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
       </div><!-- /.box-footer-->

	  </div><!-- /.box-body table-responsive -->
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->