<div class="content-wrapper">
      
  <section class="content-header">
     <h1>Ler Arquivo de Retorno</h1>
     <ol class="breadcrumb">
     	<li>Home</a>
     	<li>Receber</a>
     	<li class="active">Ler Arquivo</li>
     </ol>
 </section>

 
<section class="content">
 
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
            <div class="box-header">
                <h3 class="box-title">Baixa de Boleto</h3>
                 <?php echo $_SESSION['retorna'];
					unset($_SESSION['retorna']);
				?> 
      	  	</div><!-- /.box-header -->
          
          
             <div class="box-body">
               <form action="#" method="POST" enctype="multipart/form-data">
                  <div class="form-group col-xs-12 col-md-8"> 
                     <input type="file" name="fileUpload" class="form-control">
                  </div>
                   <div class="form-group col-xs-12 col-md-8"> 
                    <input type="submit" name="atualizar" value="Baixar Boleto" class="btn btn-primary" />
                   </div> 
               </form>
            </div><!-- /.box-body -->
           
    
    <div class="box-body table-responsive">
      	<div class="box-body table-responsive data-spy='scroll'">
     		<div class="col-md-12 scrool">  
					
								 
			 <?php
			 
                $diretorio= 'retorno';
                if(is_dir("$diretorio")) {
                //	echo 'O diretorio já existe !';
                 }else{
                    mkdir ("$diretorio", 0777); // criar o diretorio com permissao
                }
				
                if(isset($_FILES['fileUpload'])){
                  $new_name =$_FILES['fileUpload']['name']; //Pegando extensao do arquivo
                  $dir = 'retorno/'; //Diretório para uploads
                  move_uploaded_file($_FILES['fileUpload']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
                  $ponteiro = fopen ($dir.$new_name,"r");
				}
				  
				if(empty($ponteiro)){
				   return;
				}

				$total=0;
				$valor_total=0;
				$codigoBanco='';
				  
                while (!feof ($ponteiro)) {
					  
                      $linha = fgets($ponteiro,4096);
					  $codigoConta=substr($linha,23,5);

					  if(substr($linha,0,2)=='02'){
						 $codigoBanco=substr($linha,76,3);
						 $codigoConta=substr($linha,32,5);
						 
						 $tipoLinha=substr($linha,0,2);
						 
						 echo '<h3 class="box-title">Banco : '.$codigoBanco.' Conta : '.$codigoConta.'</h3>';
						 
						 echo '<table class="table table-hover">
								<tr class="set">
								<td align="center">Id</td>
								<td align="center">Nome</td>
								<td align="center">Valor Pg</td>
								<td align="center">Valor</td>
								<td align="center">Desc</td>
								<td align="center">Juros</td>
								<td align="center">Pagamento</td>
								<td align="center">Pag/Banco</td>
								<td align="center">Remessa</td>
								<td align="center">Retorno</td>
								<td colspan="8" align="center">Gerenciar</td>
						</tr>';
					  }
					  
					  $tipo='';
					  $receberId='';
                      if(substr($linha,0,1)=='1'){
						   $tipo='';
						   $receberId='';
						  
					       if($codigoBanco=='341'){
							   
							  $receberId=substr($linha,62,9);
							  
							  // valor principal + juros
							  $valorPago=substr($linha,155,10);
							  $valorPago=(float) $valorPago/100;
							  
							  $jurosPago=substr($linha,269,10);
							  $jurosPago=(float) $jurosPago/100;
							  
							 // $valorPago=$valorPago+$jurosPago;
							  $jurosPago=converteValor($jurosPago);
							   
							  $valorPago=converteValor($valorPago);
							   
							  $dia = substr($linha,295,2);
							  $mes = substr($linha,297,2);
							  $ano = substr($linha,299,2);
							  $dataPagamento=  $dia.'/'.$mes.'/20'.$ano;
														  
							  $dataPagamento = strtotime(str_replace('/', '.', $dataPagamento));
							  $dataPagamento = date('Y-m-d', $dataPagamento); 
							  if($dataPagamento>date('Y/m/d')){
							  	$dataPagamento=date('Y/m/d');
						      }
					 	 							   
							  $tipo=substr($linha,108,2);
							  $tipoRejeicao=substr($linha,377,2);
							   
							  if($tipo=='02'){ //02 ENTRADA CONFIRMADA (NOTA 20 – TABELA 10)
								$tipo='C';
							  } 
			 	   
							  if($tipo=='04'){ //04 ALTERAÇÃO DE DADOS – NOVA ENTRADA OU ALTERAÇÃO
								$tipo='A';
							  }
							   
							  if($tipo=='06'){ // 06 LIQUIDAÇÃO NORMAL
							  	$tipo='B';
							  }
							   
							  if($tipo=='09'){ // 09 BAIXA - DEVOLVIDO
							  	$tipo='D';
							  }
							 
							  if($tipo=='03'){ //03 ENTRADA REJEITADA (NOTA 20 – TABELA 1)
								$tipo='R';
							  }
							  
							  if($tipo=='29'){ // 29 DEVOLUÇÃO
								$tipo='D';
							  }
							   
							  if($tipo=='75'){ //02 ENTRADA CONFIRMADA (NOTA 20 – TABELA 10)
								$tipo='C';
							  } 
							   
							}
					  }

						if(!empty($receberId)){
							
							$receita = mostra('receber',"WHERE id = '$receberId'");  
							
							//if ($receita['status']=='Baixado'){
//								if($tipo=='B'){
//									$dataPagamento = date("Y-m-d", strtotime("$dataPagamento -1 days"));
//									$cad['pagamento'] = $dataPagamento;
//									update('receber',$cad,"id = '$receberId'");
//									unset($cad);
//								}
//							}
							
							if ($receita['status']<>'Baixado'){
						
								if($tipo=='B'){
									
									$total+=1;
									$valor_total+=$valorPago;
									
								$cad['valor'] = str_replace(",",".",str_replace(".","",$valorPago));
								$cad['juros'] = str_replace(",",".",str_replace(".","",$jurosPago));
									
									$cad['pagamento'] = $dataPagamento;
									$cad['retorno']= 'Baixado';
									$cad['status'] 	= 'Baixado';
									$cad['interacao']= date('Y/m/d H:i:s');
									$cad['usuario']	=  $_SESSION['autUser']['nome'];
									update('receber',$cad,"id = '$receberId'");
									unset($cad);
								}

								if($tipo=='C'){
									$receita = mostra('receber',"WHERE id = '$receberId'");  
									$cad['retorno']= 'Confirmado';
									$cad['interacao']= date('Y/m/d H:i:s');
									$cad['usuario']	=  $_SESSION['autUser']['nome'];
									update('receber',$cad,"id = '$receberId'");
									unset($cad);
								}

								if($tipo=='R'){
									$receita = mostra('receber',"WHERE id = '$receberId'");  
									$cad['remessa']= '';
									$cad['retorno']= '* Rejeitado ('.$tipoRejeicao.')';
									$cad['interacao']= date('Y/m/d H:i:s');
									$cad['usuario']	=  $_SESSION['autUser']['nome'];
									update('receber',$cad,"id = '$receberId'");
									unset($cad);
								}
								
								if($tipo=='D'){
									$receita = mostra('receber',"WHERE id = '$receberId'");  
									$cad['retorno']= '* Devolvido';
									$cad['interacao']= date('Y/m/d H:i:s');
									$cad['usuario']	=  $_SESSION['autUser']['nome'];
									update('receber',$cad,"id = '$receberId'");
									unset($cad);
								}
								
								if($tipo=='A'){
									$receita = mostra('receber',"WHERE id = '$receberId'");  
									$cad['retorno']= 'Alterado';
									$cad['interacao']= date('Y/m/d H:i:s');
									$cad['usuario']	=  $_SESSION['autUser']['nome'];
									update('receber',$cad,"id = '$receberId'");
									unset($cad);
								}

						
						   }
							
							echo '<td>'.$receberId.'</td>';
							
							$mostra = mostra('receber',"WHERE id = '$receberId'");
							
							$clienteId = $mostra['id_cliente'];
							$cliente = mostra('cliente',"WHERE id ='$clienteId '");
							if(!$cliente){
								echo '<td align="center">cliente Nao Encontrado</td>';
							}else{
								echo '<td>'.substr($cliente['nome'],0,12).'</td>';
							}
				 
							echo '<td align="right">'.$valorPago.'</td>';
							echo '<td align="right">'.converteValor($mostra['valor']).'</td>';
							echo '<td align="right">'.converteValor($mostra['desconto']).'</td>';
							echo '<td align="right">'.converteValor($mostra['juros']).'</td>';
							//cho '<td align="center">'.converteData($mostra['pagamento']).'</td>';
							
							if(empty($dataPagamento)){
								echo '<td align="center">-</td>';
							}else{
								echo '<td align="center">'. converteData($dataPagamento).'</td>';
							}
						
							$bancoId=$mostra['banco'];
							$banco = mostra('banco',"WHERE id ='$bancoId'");
							$formapagId=$mostra['formpag'];
							$formapag = mostra('formpag',"WHERE id ='$formapagId'");
							echo '<td align="center">'.$banco['nome']. "|".substr($formapag['nome'],0,10).'</td>';
							echo '<td align="center">'.$mostra['remessa'].'</td>';
							echo '<td align="center">'.$mostra['retorno'].'</td>';
								
							echo '<td align="center">
						<a href="painel.php?execute=suporte/receber/receber-editar&receberEditar='.$mostra['id'].'">
								<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
							</a>
						  </td>';
							
						echo '</tr>';
						
					} // fim do receberId

                  } // fim do while
				  
				   echo '<tfoot>';
                        echo '<tr>';
                        echo '<td colspan="13">' . 'Total de registros : ' .  $total . '</td>';
                        echo '</tr>';

						echo '<tr>';
                        echo '<td colspan="13">' . 'Total Pago R$ : ' . converteValor($valor_total) . '</td>';
                        echo '</tr>';
                       
                       	echo '<tr>';
                        echo '<td colspan="13">' . 'Total de Juros R$ : ' . converteValor($juros_total) . '</td>';
                        echo '</tr>';

						echo '<tr>';
                        echo '<td colspan="13">' . 'Total Baixado R$ : ' . converteValor($valor_total+$juros_total) . '</td>';
                        echo '</tr>';
  
				  echo '</tfoot>';
	
				 echo '</table>';
				 
               fclose ($ponteiro);
			   
            ?>
            
      	 </div><!--/col-md-12 scrool-->   
		</div><!-- /.box-body table-responsive data-spy='scroll -->
 	  </div><!-- /.box-body table-responsive -->

    </div><!-- /."box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper -->