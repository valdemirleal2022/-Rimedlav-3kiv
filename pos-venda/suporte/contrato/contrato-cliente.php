<?php 

	if(function_exists(ProtUser)){
			if(!ProtUser($_SESSION['autpos_venda']['id'])){
				header('Location: painel.php');	
			}	
	}

	$pos_vendaId=$_SESSION['autpos_venda']['id'];

	if(!empty($_GET['clienteId'])){
		$clienteId = $_GET['clienteId'];
		$acao = "contrato";
	}
 
	$readCliente = read('cliente',"WHERE id = '$clienteId'");
	foreach($readCliente as $cliente);
	
	$_SESSION['url']=$_SERVER['REQUEST_URI'];
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
					<td align="center">Pos-venda</td>
					<td align="center">Aprovação</td>
					<td align="center">Início</td>
					<td align="center">Valor Mensal</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		foreach($leitura as $mostra):
		
		if($pos_vendaId==$mostra['pos_venda']){
  
		 	echo '<tr>';
			
				echo '<td>'.$mostra['id'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
		
		        echo '<td>'.$mostra['controle'].'</td>';
		
				$tipocontratoId = $mostra['contrato_tipo'];
				$tipo = mostra('contrato_tipo',"WHERE id ='$tipocontratoId'");
				echo '<td>'.$tipo['nome'].'</td>';
	
				$pos_venda= mostra('contrato_pos_venda',"WHERE id ='$pos_vendaId'");
				echo '<td>'.$pos_venda['nome'].'</td>';
		
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
			
				if($mostra['tipo']=='1'){
				   echo '<td align="right">'.converteValor($mostra['orc_valor']).'</td>';
				  }else{
				   echo '<td align="right">'.converteValor($mostra['valor_mensal']).'</td>';
				}

				$statusId = $mostra['status'];
				$status = mostra('contrato_status',"WHERE id ='$statusId'");
				echo '<td align="center">'.$status['nome'].'</td>';
		
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoVisualizar='.$mostra['id'].'">
								<img src="../admin/ico/visualizar.png"  title="Visualizar" />
							</a>
						  </td>';	
			
					 
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/ficha-cliente-pdf&contratoId='.$mostra['id'].'" target="_blank">
								<img src="../admin/ico/contratos.png" title="Ficha Cliente"  />
							</a>
						 </td>';	
			
		 
					echo '<td align="center">
							<a href="painel.php?execute=suporte/relatorio/cronograma-pdf&contratoId='.$mostra['id'].'" target="_blank">
								<img src="../admin/ico/contratos.png" title="Imprimir Cronograma"  />
							</a>
						 </td>';	
		
					echo '<td align="center">
							<a href="painel.php?execute=suporte/contrato/contrato-aditivo-pos-venda&contratoId='.$mostra['id'].'">
								<img src="../admin/ico/atendimento.png" title="Aditivo"  />
							</a>
						 </td>';	

						// imprimir ordem
					echo '<td align="center">
							<a href="painel.php?execute=suporte/atendimento/atendimento-editar&contratoId='.$mostra['id'].'">
								<img src="../admin/ico/atendimento.png" title="Atendimento"  />
							</a>
						 </td>';	
		
			echo '</tr>';
			
		  }
		
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

