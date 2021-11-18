<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
 <?php 
				if($cliente['tipo']==4){
					echo $cliente['nome'].' <img src="ico/premium.png"/>';
				}else{
					echo $cliente['nome'];
				}
			?>
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
			<?php 
				if($cliente['tipo']==4){
					echo $cliente['nome'].' <img src="ico/premium.png"/>';
				}else{
					echo $cliente['nome'];
				}
			?> </h4>
      </div>
      <div class="modal-body">
      
         Endereço : <?php echo $cliente['endereco'].', '.$cliente['numero'].'/'.$cliente['complemento'];?> <br>
         Bairro : <?php echo $cliente['bairro']. ' || '.$cliente['cidade'] ;?><br>
         CEP : <?php echo $cliente['cep'];?><br>
         Telefone : <?php echo $cliente['telefone'] . ' || '.$cliente['celular']. ' || '.$cliente['contato'];?><br><br>
		  
		 Envio de Email :
		  <?php 
		  	if($cliente['nao_enviar_email'] == '1'){
				echo 'Não Enviar Email';
 			}else{
				echo 'Sem Restrição';
			};
		  ?>
		 <br>
	 
		  
         Email : <?php echo $cliente['email'];?> <br>
		 Email Financeiro : <?php echo $cliente['email_financeiro'];?><br><br>
         CNPJ/CPF : <?php echo $cliente['cnpj']. ' || '.$cliente['cpf'] ;?><br>
         Restriçao : <?php echo $cliente['restricao'];?><br><br>
		 Referência : <?php echo $cliente['referencia'];?><br><br>
     	           
      </div>
      <div class="modal-footer">
		  
		    <?php
		  
		  	echo '<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$cliente['id'].'">
			  				<img src="ico/editar-cliente.png"  title="Editar Cliente"   />
              			</a>';
	
			?>		 
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>