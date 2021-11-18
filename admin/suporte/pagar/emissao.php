<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	if(!isset($_SESSION['inicio'])){
		$data1 = date("Y-m-d");
		$data2 = date("Y-m-d");
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$conta='';
	}else{
		$data1 = $_SESSION['inicio'];
		$data2 = $_SESSION['fim'];
	}

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST[ 'conta' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['conta']=$conta;
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-despesas-pdf");';
		echo '</script>';
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST[ 'conta' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['conta']=$conta;
		header( 'Location: ../admin/suporte/relatorio/relatorio-despesas-excel.php' );
	}

	if ( isset( $_POST[ 'pesquisar' ] ) ) {
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		$conta = $_POST[ 'conta' ];
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
	}

	$leitura = read('pagar',"WHERE status='Em Aberto' AND emissao>='$data1' AND emissao<='$data2'
					ORDER BY interacao ASC, emissao DESC");
	$total = conta('pagar',"WHERE status='Em Aberto' AND emissao>='$data1' AND emissao<='$data2'
					ORDER BY emissao ASC");
	$valor_total = soma('pagar',"WHERE status='Em Aberto' AND emissao>='$data1' AND emissao<='$data2'
					ORDER BY emissao ASC",'valor');
		
	if(!empty($conta) ){
			$total = conta('pagar',"WHERE  id_conta='$conta' AND status='Em Aberto' AND emissao>='$data1' AND emissao<='$data2'");
			$valor_total = soma('pagar',"WHERE id_conta='$conta' AND status='Em Aberto' AND emissao>='$data1' AND emissao<='$data2'",'valor');
			$leitura = read('pagar',"WHERE  id_conta='$conta' AND status='Em Aberto' AND emissao>='$data1' AND emissao<='$data2' ORDER BY emissao ASC");
	}

	$_SESSION['url']=$_SERVER['REQUEST_URI'];

?>

<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Lançamento por Emissão</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Lançamento</a>
     	<li class="active">Emissão</li>
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
				 </div><!-- /box-header-->
		 
         		 <div class="box-header"> 

                    <!--PESQUISA DE RELATORIO-->
                    <div class="col-xs-10 col-md-9 pull-right">
                        <form name="form-pesquisa" method="post" class="form-inline" role="form">
							
                           <div class="form-group pull-left">
                            <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                         </div>   <!-- /.input-group -->
                            
                         <div class="form-group pull-left">
                             <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                         </div>  <!-- /.input-group -->
							
                        <div class="form-group pull-left">
                         <select name="conta" class="form-control input-sm">
                                <option value="">Conta</option>
                                <?php 
                                    $readConta = read('pagar_conta',"WHERE id ORDER BY nome ASC");
                                    if(!$readConta){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readConta as $mae):
                                           if($conta == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>    
                        </div><!-- /.form-group pull-left -->
							
                       <!-- /.input-group -->
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>
                            </div>
                            <!-- /.input-group -->
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>
                            </div>
                            <!-- /.input-group -->
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>
                            <!-- /.input-group -->

                	    </form>
                    </div><!-- /col-xs-10 col-md-5 pull-right-->
					 
  					</div>  <!-- /box-header-->

    
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
					<td align="center">Emissão</td>
					<td align="center">Vencimento</td>
					<td align="center">Parcela</td>
					<td align="center">Conta</td>
					<td align="center" colspan="6">Gerenciar</td>
		 </tr>';
		foreach($leitura as $mostra):

			   	echo '<tr>';
				
				echo '<td align="center">'.$mostra['id'].'</td>';
				echo '<td>'.substr($mostra['descricao'],0,25).'</td>';
				echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
				echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
		
				echo '<td>'.$mostra['parcela'].'</td>';
		
				$contaId = $mostra['id_conta'];
				$contaMostra = mostra('pagar_conta',"WHERE id ='$contaId '");
		
				if(!$contaMostra){
					echo '<td align="center">Conta Nao Encontrado</td>';
				}else{
					echo '<td>'.substr($contaMostra['nome'],0,35).'</td>';
				}
				echo '<td align="center">
					<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
					<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
              			</a>
				      </td>';
				echo '<td align="center">
			  			<a href="painel.php?execute=suporte/pagar/despesa-editar&pagamentoDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png"  title="Excluir" class="tip" />
              			</a>
						</td>';
		
				$pdf='../uploads/pagamentos/boletos/'.$mostra['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../uploads/pagamentos/boletos/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" title="Boleto" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}
				
				$pdf='../uploads/pagamentos/comprovantes/'.$mostra['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../uploads/pagamentos/comprovantes/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" title="Comprovante" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}
		
				$pdf='../uploads/pagamentos/notas/'.$mostra['id'].'.pdf';
				if(file_exists($pdf)){
					echo '<td align="center">
						<a href="../uploads/pagamentos/notas/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" title="Nota" />
              			</a>
						</td>';	
				}else{
					echo '<td align="center">-</td>';	
				}
				
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
