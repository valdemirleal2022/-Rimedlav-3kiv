<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	
	 
 	$leitura = read('email',"WHERE id AND status='!' ORDER BY data ASC");
 	if($leitura){
 		foreach($leitura as $mostra):
			$cad['email'] = strip_tags(trim(mysql_real_escape_string($mostra['email'])));
			$cad['email'] = strtolower($cad['email']);
			$cad['email'] = trim($cad['email']);
			$cad['email'] = str_replace(' ','', $cad['email']);
			if(!email($cad['email'])){
				while(!email($cad['email'])){
					$cad['email'] = substr($cad['email'], 0, -1);
				}
				$id = $mostra['id'];
				$cad['status'] = '';
				update('email',$cad,"id = '$id'");
				unset($cad);
			}
		endforeach;
	}

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '50';
	$inicio = ($pag * $maximo) - $maximo;
	
		
	$leitura = read('email',"WHERE id ORDER BY email ASC LIMIT $inicio,$maximo");


?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Email</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li>Email</a>
            <li class="active">Email Marketing</li>
          </ol>
 </section>
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
         <div class="box-header">
           
     	</div><!-- /.box-header -->
     
     
      <div class="box-body table-responsive">

	<?php 

		if($leitura){
				echo '<table class="table table-hover">	
					<td align="center">Email</td>
					<td align="center"></td>
					<td align="center">Nome</td>
					<td align="center">Data</td>
					<td align="center">Status</td>
					<td align="center" colspan="6">Gerenciar:</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td>'.$mostra['email'].'</td>';
				$mostra['email'] = strtolower($mostra['email']);
				if(!email($mostra['email'])){
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/email/email-editar&emailEditar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Editar" title="Editar" class="tip" />
              			</a>
						</td>';
				}else{
					echo '<td></td>';
				}
				echo '<td>'.substr($mostra['nome'],0,20).'</td>';
				echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['data'])).'</td>';
				echo '<td>'.$mostra['status'].'</td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/email/email-editar&emailPromocao='.$mostra['id'].'">
							<img src="ico/email.png" alt="Promoção" title="Promoção" class="tip" />
              			</a>
						</td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/email/email-editar&emailEditar='.$mostra['id'].'">
							<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
						</td>';
			 
						
					echo '<td align="center">
						<a href="../cliente/painel2.php?execute=suporte/imprimir-envelope&emailEditar='.$mostra['id'].'" target="_blank">
							<img src="../admin/ico/envelope.png" alt="Envelope" title="Envelope" class="tip" />
              			</a>
						</td>';
			echo '</tr>';
		 endforeach;
		 echo '</table>';
		$link = 'painel.php?execute=suporte/email/emails-errados&pag=';
	     pag('email',"WHERE id ORDER BY email ASC", $maximo, $link, $pag);
		}
	?>
    
 <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?>
       </div><!-- /.box-footer-->

    </div><!-- /.box-body table-responsive -->
    
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->