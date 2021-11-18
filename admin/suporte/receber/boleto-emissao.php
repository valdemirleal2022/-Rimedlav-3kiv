<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];

	$data1=date("Y-m-d");

	if(isset($_POST['pesquisar'])){
		$data1=$_POST['data1'];
		$banco=$_POST['banco'];
		$formpag=$_POST['formpag'];
		if(empty($data1)){
			echo '<div class="alert alert-warning">Data do Relatorio Inválido!</div><br />';
		}
		
		$leitura = read('receber',"WHERE emissao='$data1' AND banco='$banco' AND formpag='$formpag' AND status='Em Aberto' ORDER BY emissao ASC");
	}
	
	$_SESSION['$data1']=$data1;
	$_SESSION['$banco']=$banco;
	$_SESSION['$testeRemessa']=$testeRemessa;
	$_SESSION['$formpag']=$formpag;

?>

<div class="content-wrapper">
  <section class="content-header">
     <h1>Arquivo de Remessa - Emissão</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Contas a Receber</a>
     	<li class="active">Remessa</li>
     </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
            
      <div class="box-header"> 
      
           <div class="col-xs-10 col-md-8 pull-left">
                
                 <form name="form-pesquisa" method="post" class="form-inline" role="form">
                      
                       <div class="form-group pull-left">
                           <input type="date" name="data1" value="<?php echo $data1;?>" class="form-control input-sm" />
                       </div><!-- /.input-group -->
                       
                       <div class="form-group pull-left">
                            <select name="banco" class="form-control input-sm">
                                <option value="">Selecione Banco</option>
                                <?php 
                                    $readBanco = read('banco',"WHERE id");
                                    if(!$readBanco){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readBanco as $mae):
                                           if($banco == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>
            			</div>

                       <div class="form-group pull-left">
                            <select name="formpag" class="form-control input-sm">
                                <option value="">Forma de Pagamento</option>
                                <?php 
                                    $readFormpag = read('formpag',"WHERE id");
                                    if(!$readFormpag){
                                        echo '<option value="">Não temos Forma de Pagamento no momento</option>';	
                                    }else{
                                        foreach($readFormpag as $mae):
                                           if($formpag == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                            </select>
                        </div>
                       
                       <div class="form-group pull-left">
                         <button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar"><i class="fa fa-search"></i></button>
                   		</div><!-- /.input-group -->
                </form> 
           </div><!-- /col-xs-8-->   
           
            <div class="col-xs-10 col-md-4 pull-left">
                <a href="painel.php?execute=suporte/receber/boleto-remessa" class="btnnovo">
                    <img src="ico/download.png" alt="Remessa" title="Remessa" class="tip" />
                    <small>Gerar Arquivo de Remessa</small>
                </a> 
                 <a href="painel.php?execute=suporte/receber/boleto-retorno" class="btnnovo">
                    <img src="ico/upload.png" alt="Retorno" title="Retorno" class="tip" />
                    <small>Ler Arquivo de Retorno</small>
                </a> 
           </div><!-- /col-xs-4-->   
 
     </div><!-- /box-header-->   
   
    <div class="box-body table-responsive">
       <div class="box-body table-responsive data-spy='scroll'">
     			<div class="col-md-12 scrool"> 
 	<?php 
	
	
    if($leitura){
			echo '<table class="table table-hover">
					<tr class="set">
					<td align="center">Id</td>
					<td align="center">Nome</td>
					<td align="center">Valor</td>
					<td align="center">Emissao</td>
					<td align="center">Vencimento</td>
					<td align="center">Pag/Banco</td>
					<td align="center">Remessa</td>
					<td align="center">Retorno</td>
					<td align="center">Interação</td>
    				<td colspan="8" align="center">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
	 
		 	echo '<tr>';
				echo '<td align="center">'.$mostra['id'].'</td>';

				$clienteId = $mostra['id_cliente'];
				$contratoId = $mostra['id_contrato'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				if(!$cliente){
					echo '<td align="center">Cliente Nao Encontrado</td>';
				}else{
					echo '<td>'.substr($cliente['nome'],0,15).'</td>';
				}
			 	
				$valor=$mostra['valor']+$mostra['juros']-$mostra['desconto'];
				echo '<td align="right">'.converteValor($valor).'</td>';
				echo '<td align="center">'.converteData($mostra['emissao']).'</td>';
		
				if($mostra['emissao']<=$mostra['vencimento']){
					echo '<td align="center">'.converteData($mostra['vencimento']).'</td>';
				}else{
					 echo '<td align="center"><span class="badge bg-red">VENCIMENTO!</span></td>';
				}

				$bancoId=$mostra['banco'];
				$banco = mostra('banco',"WHERE id ='$bancoId'");
				$formpagId=$mostra['formpag'];
				$formapag = mostra('formpag',"WHERE id ='$formpagId'");
				echo '<td align="center">'.$banco['nome']. "|".$formapag['nome'].'</td>';
			
				echo '<td align="center">'.$mostra['remessa'].'</td>';
				echo '<td align="center">'.$mostra['retorno'].'</td>';
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
				echo '<td align="center">
					<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				      </td>';
		
				echo '<td align="center">
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$mostra['id_cliente'].'">
								<img src="ico/editar-cliente.png" alt="Editar Cliente" title="Editar Cliente" />
							</a>
						  </td>';
		
	
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
             	echo '<tr>';
                	echo '<td colspan="16">' . 'Total de Registros : ' .  $total . '</td>';
                echo '</tr>';
                echo '<tr>';
                	echo '<td colspan="16">' . 'Valor Total R$ : ' . number_format($valor_total,2,',','.') . '</td>';
                echo '</tr>';
          echo '</tfoot>';
		 echo '</table>';

		}
		?>
        
		
          </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->
     	  
     	  
    </div><!-- /."box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->