<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}

	if(!isset($_SESSION['inicio'])){
		$hoje = date("Y-m-d");
		$_SESSION['hoje']=$data1;
	}else{
		$hoje = $_SESSION['hoje'];
	}

	
 
	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$hoje = $_POST[ 'inicio' ];
		$_SESSION['hoje']=$hoje;
	}

	$leitura = read('pagar',"WHERE data='$hoje' ORDER BY interacao ASC");
	$total = conta('pagar',"WHERE data='$hoje' ORDER BY interacao ASC");
	$valor_total = soma('pagar',"WHERE data='$hoje' ORDER BY interacao ASC",'valor');
	 
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Lançamentos</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Contas a Pagar</a>
     	<li class="active">Lançamentos</li>
     </ol>
 </section>

 <section class="content">
   <div class="row">
    <div class="col-xs-12">
		
     <div class="box box-default">
	
		 <div class="box-header"> 
			 
				 <a href="painel.php?execute=suporte/pagar/despesa-novo" class="btnnovo">
				 <img src="ico/novo.png" alt="Novo" title="Novo" class="imagem" />
				 </a>
			  
			 <!--PESQUISA DE RELATORIO-->
                    <div class="col-xs-10 col-md-2 pull-right">
                        <form name="form-pesquisa" method="post" class="form-inline" role="form">
							
                           <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($hoje)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                 
                       <!-- /.input-group -->
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>
                            </div>
                            <!-- /.input-group -->
                         

                	    </form>
                    </div><!-- /col-xs-10 col-md-5 pull-right-->
 
		 </div><!-- /box-header-->
	
      <div class="box-body table-responsive data-spy='scroll'">
     	<div class="col-md-12 scrool">  
		  <div class="box-body table-responsive">

	<?php 
  
	if($leitura){
		echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Descrição</td>
					<td align="center">Valor</td>
					<td align="center">Lançamento</td>
					<td align="center">Emissão</td>
					<td align="center">Vencimento</td>
					<td align="center">Conta</td>
					<td align="center">Usuário</td>
					<td>Interação</td>
					<td align="center" colspan="6">Gerenciar</td>
		 </tr>';
		foreach($leitura as $mostra):

			   	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td>'.substr($mostra['descricao'],0,25).'</td>';
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['data']).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
		 
				$contaId = $mostra['id_conta'];
				$contaMostra = mostra('pagar_conta',"WHERE id ='$contaId '");
		
				if(!$contaMostra){
					echo '<td align="center">Conta Nao Encontrado</td>';
				}else{
					echo '<td>'.substr($contaMostra['nome'],0,35).'</td>';
				}
				
				echo '<td align="center">'.substr($mostra['usuario'],0,12).'</td>';
				echo '<td>'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
		
				echo '<td align="center">
					<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				 
		 
				
			echo '</tr>';
			
			   
				
		 endforeach;
		 
		 echo '<tfoot>';
				echo '<tr>';
                	echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
                echo '<tr>';
                	echo '<td colspan="13">' . 'Valor Total R$ : ' . converteValor($valor_total) . '</td>';
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

	  </div><!-- /.box-body table-responsive -->
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->

</div><!-- /.content-wrapper -->
