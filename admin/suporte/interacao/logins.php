 <?php 
/* Informa o nível dos erros que serão exibidos */
 //error_reporting(E_ALL);
 
//* Habilita a exibição de erros */
 //ini_set("display_errors", 1);

	 if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}

	}

	if(isset($_POST['relatorio-pdf'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-interacoes-pdf");';
		echo '</script>';
	}


	if(isset($_POST['relatorio-excel'])){
		$_SESSION['inicio']=$_POST['inicio'];
		$_SESSION['fim']=$_POST['fim'];
	    header('Location: ../admin/suporte/relatorio/relatorio-interacoes-excel.php');
	}

	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '30';
	$inicio = ($pag * $maximo) - $maximo;
	
	$total = conta('usuarios_login',"WHERE id");

	$leitura = read('usuarios_login',"WHERE id ORDER BY data DESC LIMIT $inicio,$maximo");

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		
		$data1 = $_POST['inicio'];
		$data2 = $_POST['fim'];
		$usuario = $_POST['usuario'];
		
		$total = conta('usuarios_login',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2'  
											//	AND id_usuario='$usuario'");

		$leitura = read('usuarios_login',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2' 
		AND id_usuario='$usuario' ORDER BY data DESC");

	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

	$data1= date('Y/m/d H:i:s');

?>

<div class="content-wrapper">
 
  <section class="content-header">
       <h1>Login</h1>
         <ol class="breadcrumb">
           <li><i class="fa fa-home"></i> Home</li>
           <li>Usuário</li>
           <li>Login</li>
         </ol>
 </section>
 
 <section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">

     	<div class="box-header">	 
  
                    <div class="col-xs-10 col-md-7 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d') ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                       <div class="form-group pull-left">
                            <input type="date" name="fim" value="<?php echo date('Y-m-d') ?>" class="form-control input-sm" >
                       </div><!-- /.input-group -->
                       
                        <div class="form-group pull-left">
							 <select name="usuario" class="form-control input-sm">
									<option value="">Usuário</option>
									<?php 
										$readConta = read('usuarios',"WHERE id ORDER BY nome ASC");
										if(!$readConta){
											echo '<option value="">Não temos Bancos no momento</option>';	
										}else{
											foreach($readConta as $mae):
											   if($usuario == $mae['nome']){
													echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
												 }else{
													echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
												}
											endforeach;	
										}
									?> 
								</select>    
						</div><!-- /.form-group pull-left -->

                        
                       	 <div class="form-group pull-left">
								<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
								 <i class="fa fa-search"></i></button>
							 </div><!-- /.input-group -->

							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->                         
                    </form>  
                     
                  </div><!-- /col-xs-10 col-md-5 pull-right-->
                  
          </div><!-- /box-header-->   
       

    <div class="box-body table-responsive">
   
	<?php 

	if($leitura){
		
			echo '<table class="table table-hover">	
			
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Usuário</td>
					<td align="center">Tipo</td>
					<td align="center">Data</td>
					<td align="center">Latitude</td>
					<td align="center">Logitude</td>
					<td align="center" colspan="3">Gerenciar</td>
					
				</tr>';
		
		foreach($leitura as $mostra):
		
		 	echo '<tr>';

				echo '<td>'.$mostra['id'].'</td>';
		
				if(!empty($mostra['id_usuario'])){
					$usuarioId = $mostra['id_usuario'];
					$usuarioMostra= mostra('usuarios',"WHERE id ='$usuarioId'");
					$usuarioTipo="Administrativo";
				}
				if(!empty($mostra['id_consultor'])){
					$usuarioId = $mostra['id_consultor'];
					$usuarioMostra= mostra('contrato_consultor',"WHERE id ='$usuarioId'");
					$usuarioTipo="Consultor";
				}
				if(!empty($mostra['id_pos_venda'])){
					$usuarioId = $mostra['id_pos_venda'];
					$usuarioMostra= mostra('contrato_pos_venda',"WHERE id ='$usuarioId'");
					$usuarioTipo="Pos-venda";
				}
				if(!empty($mostra['id_rota'])){
					$usuarioId = $mostra['id_rota'];
					$usuarioMostra= mostra('contrato_rota',"WHERE id ='$usuarioId'");
					$usuarioTipo="Rota";
				}
		
				echo '<td>'.$usuarioMostra['nome'].'</td>';
				echo '<td>'.$usuarioTipo.'</td>';
				echo '<td align="center">'. date('d/m/Y H:i:s',strtotime($mostra['data'])).'</td>';
				echo '<td>'.$mostra['latitude'].'</td>';
				echo '<td>'.$mostra['longitude'].'</td>';

				echo '<td align="center">
						<a href="painel.php?execute=suporte/interacao/login-editar&loginEditar='.$mostra['id'].'">
			  				<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar" />
              			</a>
				    </td>';
		
			echo '</tr>';
		 endforeach;
		 
			 echo '<tfoot>';
					echo '<tr>';
					echo '<td colspan="12">' . 'Total de registros : ' .  $total . '</td>';
			 echo '</tfoot>';
			
		 echo '</table>';
		
		 $link = 'painel.php?execute=suporte/interacao/logins&pag=';
		
			if (!isset( $_POST[ 'pesquisar' ] ) ) {
				pag('usuarios_login',"WHERE id ORDER BY data DESC", $maximo, $link, $pag);
			}else{
				pag('usuarios_login',"WHERE id AND DATE(data)>='$data1' AND DATE(data)<='$data2' 
		AND id_usuario='$usuario' ORDER BY data DESC", $maximo, $link, $pag);
			}
		
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
