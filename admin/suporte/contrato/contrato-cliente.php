<head>
    <meta charset="iso-8859-1">
</head>
       

<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}	
	}
	
	if(!empty($_GET['clienteId'])){
		$clienteId = $_GET['clienteId'];
		$acao = "contrato";
	}
 
	$readCliente = read('cliente',"WHERE id = '$clienteId'");
	foreach($readCliente as $cliente);
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
	$_SESSION['aba']=1;
?>


<div class="content-wrapper">
  <section class="content-header">
       <h1>Cliente</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Cadastro</a></li>
         <li class="active">Cliente</li>
       </ol>
 </section>
 
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
           <div class="box-header with-border">
            	 <?php require_once('cliente-modal.php');?>
               	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                     <?php if($acao=='contrato') echo 'Contratos';?>
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header -->
    
   
      <div class="box-header">
      
      	 <a href="painel.php?execute=suporte/contrato/contrato-editar&clienteId=<?PHP print $clienteId; ?>" class="btnnovo">
			  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
                <small>Cadastrar Novo Contato</small>
         </a>
      
		  <!--// <a href="painel.php?execute=suporte/orcamento/orcamento-editar&clienteId=<?PHP print $clienteId; ?>" class="btnnovo">
//				  <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
//				   <small>Cadastrar Novo Orçamento</small>
//			 </a>-->
         
       

    </div><!-- /.box-header -->
     
     <div class="box-body table-responsive">  
     
     
     <?php 
	 
 	$leitura = read('contrato',"WHERE id AND id_cliente='$clienteId' ORDER BY id ASC");
	if($leitura){
			echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">ID</td>
					<td align="center">Controle</td>
					<td align="center">Tipo de Resíduo</td>
					<td align="center">Consultor</td>
					<td align="center">Aprovação</td>
					<td align="center">Início</td>
					<td align="center">Valor Mensal</td>
					<td align="center">Status</td>
					<td align="center">Interação</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
  
		 	echo '<tr>';
				echo '<td>'.$mostra['id'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
		
		        echo '<td>'.$mostra['controle'].'</td>';
		
				$tipocontratoId = $mostra['contrato_tipo'];
				$tipo = mostra('contrato_tipo',"WHERE id ='$tipocontratoId'");
				echo '<td>'.$tipo['nome'].'</td>';
		
				$consultorId = $mostra['consultor'];
				$consultor= mostra('contrato_consultor',"WHERE id ='$consultorId'");
				echo '<td>'.$consultor['nome'].'</td>';
		
		        if($mostra['tipo']=='1'){
				   echo '<td align="center">'.converteData($mostra['orc_data']).'</td>';
				  }else{
				  echo '<td align="center">'.converteData($mostra['aprovacao']).'</td>';
				}
		
				if($mostra['tipo']=='1'){
				   echo '<td align="center">-</td>';
				  }else{
				  echo '<td align="center">'.converteData($mostra['inicio']).'</td>';
				}
		
				$contratoId = $mostra['id'];
				$coleta = mostra('contrato_coleta',"WHERE id AND id_contrato='$contratoId' ORDER BY vencimento ASC");
			  
				echo '<td align="right">'.converteValor($coleta['valor_mensal']).'</td>';
				 
		 
				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td align="center">'.$status['nome'].'</td>';
		
				echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
		
				if($mostra['tipo']=="1"){
					echo '<td align="center">
							<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoEditar='.$mostra['id'].'">
								<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
							</a>
						  </td>';
					echo '<td align="center">
							<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoVisualizar='.$mostra['id'].'">
								<img src="ico/visualizar.png" alt="Editar" title="visualizar" class="tip" />
							</a>
						  </td>';	
					echo '<td align="center">
						<a href="painel.php?execute=suporte/orcamento/orcamento-editar&orcamentoCancelar='.$mostra['id'].'">
			  				<img src="ico/excluir.png" alt="Editar" title="Cancelar Orçamento" class="tip" />
              			</a>
						  </td>';
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoAprovar='.$mostra['id'].'">
								<img src="ico/aprovado.png" alt="Aprovar" title="Orçamento Aprovar" class="tip" />
							</a>
						  </td>';
				}else{
				
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
								<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
							</a>
						  </td>';
					
				echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
								<img src="ico/visualizar.png" alt="Visualizar" title="Visualizar" class="tip" />
							</a>
						  </td>';	
		
				}
		
			    $pdfContrato='../../wpc/uploads/contratos/'.$mostra['id'].'.pdf';
				if(file_exists($pdfContrato)){
					echo '<td align="center">
						<a href="../../wpc/uploads/contratos/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" alt="Contrato" title="Contrato" />
              			</a>
						</td>';	
				}
		
				$pdfAssinatura='../../wpc/uploads/assinaturas/'.$mostra['id'].'.pdf';
				if(file_exists($pdfAssinatura)){
					echo '<td align="center">
						<a href="../../wpc/uploads/assinaturas/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" alt="Assinatura" title="Assinatura" />
              			</a>
						</td>';	
				}
		
				$pdfCancelamento='../../wpc/uploads/cancelamentos/'.$mostra['id'].'.pdf';
				if(file_exists($pdfCancelamento)){
					echo '<td align="center">
						<a href="../../wpc/uploads/cancelamentos/'.$mostra['id'].'.pdf" target="_blank">
							<img src="ico/pdf.png" alt="Assinatura" title="Carta de cancelamento" />
              			</a>
						</td>';	
				}
		
			echo '</tr>';
		 endforeach;
		 
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
  
  
 <section class="content">
	<div class="row">          
	 <div class="col-md-12">   
          <div class="box">
         	<div class="box-header">
        		

				<?php
				   echo '<p align="center">'.$cliente['nome'].', '.$cliente['telefone'].' / '.$cliente['contato'].'</p>';
					echo '<p align="center">'.$cliente['endereco'].', '.$cliente['numero'].'  '.$cliente['complemento'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'].'</p>';

					$address = url($cliente['endereco'].', '.$cliente['numero'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'])

				?>


				<iframe width='100%' height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
				</iframe>

         		
          	</div><!-- /.box-header -->
    	</div><!-- /.box  -->
     </div><!-- /.col-md-12  -->
   </div><!-- /.row  -->
   
</section><!-- /.content -->
 
  
</div><!-- /.content-wrapper -->

