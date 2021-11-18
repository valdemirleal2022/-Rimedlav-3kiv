<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>
  
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
       <h1>Solicita&ccedil;&otilde;es</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Solicita&ccedil;&otilde;es</a></li>
         </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
   
        <div class="box-body table-responsive">
       	 
				<div class="box-header">
                   <a href="painel.php?execute=suporte/orcamento/solicitacao-editar" class="btnnovo">
                    <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
                    </a>
                 </div>
                 <!-- /.box-header -->

           		<div class="box-header">
        	 		<div class="col-xs-8 col-md-2 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
							   <div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
								</div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
        </div> <!-- /.box-header -->
                  
	<?php 

	
	$leitura = read('cadastro_visita',"WHERE id AND status='1' ORDER BY interacao DESC");
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td>Nome</td>
					<td align="center">Solicitaçao</td>
					<td align="center">Consultor</td>
					<td align="center">Atendente</td>
					<td align="center">Status</td>
					<td align="center">Interação</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
		 	echo '<tr>';
		

				echo '<td align="left">'.substr($mostra['nome'],0,20).'</td>';
		        echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['orc_solicitacao'])).'</td>';
				
				$consultorId = $mostra['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
				echo '<td align="left">'.$consultor['nome'].'</td>';
				
				$atendenteId = $mostra['atendente'];
				$atendente = mostra('contrato_atendente',"WHERE id ='$atendenteId '");
				echo '<td align="left">'.$atendente['nome'].'</td>';
		
	        	$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId '");
				echo '<td align="left">'.$status['nome'].'</td>';
			 
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';

				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/solicitacao-editar&solicitacaoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Solicitação" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/orcamento/solicitacao-editar&solicitacaoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
						</td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/solicitacao-editar&solicitacaoEnviar='.$mostra['id'].'">
			  				<img src="../admin/ico/email.png" alt="Aviso" title="Enviar Solicitação" class="tip" />
              			</a>
				      </td>';	
		
				echo '</tr>';
				
		 endforeach;
		 echo '</table>';
		 $link = 'painel.php?execute=suporte/orcamento/orcamentos&pag=';
	     pag('contrato',"WHERE id AND tipo='1' AND status='1' ORDER BY interacao DESC", $maximo, $link, $pag);
		
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