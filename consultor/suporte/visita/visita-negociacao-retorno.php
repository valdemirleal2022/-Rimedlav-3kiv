<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autConsultor']['id'])){
			header('Location: painel.php');	
		}	
	}
	
	$_SESSION['url2']=$_SERVER['REQUEST_URI'];

	$consultorId=$_SESSION['autConsultor']['id']
?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Negocia��es | Retorno</h1>
        <ol class="breadcrumb">
            <li>Home</a>
            <li class="active">Retorno</li>
          </ol>
 </section>
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
 
      <div class="box-body table-responsive">
    
      
	<?php 
		  
	$hoje = date('Y/m/d');
		  
	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '20';
	$inicio = ($pag * $maximo) - $maximo;
	
	$total = conta('cadastro_visita_negociacao',"WHERE id AND status='1' AND retorno<='$hoje' AND consultor='$consultorId' ORDER BY retorno DESC");
	$leitura = read('cadastro_visita_negociacao',"WHERE id AND status='1' AND retorno<='$hoje' AND consultor='$consultorId' ORDER BY retorno DESC LIMIT $inicio,$maximo");

	if($leitura){
		echo '<table class="table table-hover">	
					<tr class="set">
					<td>Id</td>
					<td>Nome</td>
					<td align="center">Consultor</td>
					<td align="center">Descri��o</td>
					<td align="center">Intera��o</td>
					<td align="center">Retorno</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				echo '<td align="left">'.$mostra['id'].'</td>';
		
				$visitaId = $mostra['id_visita'];
				$visita = mostra('cadastro_visita',"WHERE id ='$visitaId'");
				echo '<td align="left">'.substr($visita['nome'],0,30).'</td>';
	
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				
				echo '<td align="left">'.$consultor['nome'].'</td>';
				echo '<td align="left">'.substr($mostra['descricao'],0,40).'</td>';
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				echo '<td align="center">'.date('d/m/Y',strtotime($mostra['retorno'])).'</td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaEditar='.$mostra['id'].'">
			  				<img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-editar&visitaEditar='.$visitaId.'">
			  				<img src="../admin/ico/visualizar.png" alt="Editar" title="Editar Or�amento" class="tip" />
              			</a>
				      </td>';	
				echo '<td align="center">
						<a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaBaixar='.$mostra['id'].'">
			  				<img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
						  
		
			echo '</tr>';
		endforeach;
		 
		echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="11">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';
  
          echo '</tfoot>';
		 
		 echo '</table>';
		
 			$link = 'painel.php?execute=suporte/visita/visita-negociacao-retorno&pag=';		 
			pag('cadastro_visita_negociacao',"WHERE id AND status='1' ORDER BY retorno DESC", $maximo, $link, $pag);

		
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