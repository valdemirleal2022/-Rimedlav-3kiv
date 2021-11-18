<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');	
		}
	}
	
?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Usuários</h1>
       <ol class="breadcrumb">
         <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Home</a></li>
         <li><a href="#">Cadastro</a></li>
         <li class="active">Usuários</li>
       </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
     
          <div class="box-header">
                <a href="painel.php?execute=suporte/usuario/usuario-editar" class="btnnovo">
                <img src="ico/novo.png" alt="Criar Novo" title="Criar Novo" />
                </a>
           </div><!-- /.box-header -->
            
     <div class="box-body table-responsive">
        
	<?php

	
	$total = conta('usuarios',"WHERE id");
	$leitura = read('usuarios',"WHERE id ORDER BY id ASC, nome ASC");
	if($leitura){
			echo '<table class="table table-hover">
			<tr class="set">
			<td align="center">Id</td>
			<td>Foto</td>
			<td>Nome</td>
			<td>E-mail</td>
			<td align="center">Nível</td>
			<td align="center">Status</td>
			<td align="center">Telefone</td>
			<td align="center" colspan="3">Gerenciar</td>
			</tr>';	
				foreach($leitura as $mostra):
				 
					$status = ($mostra['status'] == '1' ? 'Ativo' : 'Inativo');
					echo '<tr>';	
					echo '<td align="center">'.$mostra['id'].'</td>';	
					
					if($mostra['fotoperfil']!= '' && file_exists('../uploads/usuarios/'.$mostra['fotoperfil'])){
                        echo '<td align="center">
                              <img class="img-circle img-thumbnail" width="50" height="50" src="'.URL.'/uploads/usuarios/'
                                         .$mostra['fotoperfil'].'">';
                      }else{
                       echo '<td align="center">
                              <img class="img-circle img-thumbnail" width="50" height="50" src="'.URL.'/site/images/autor.png">';
                	}	
		
					echo '<td>'.$mostra['nome'].'</td>';	
					echo '<td>'.$mostra['email'].'</td>';
		
			
					// 1 - Operacional 
				// 2 - Atendimento ao Cliente / Comercial
				// 3 - Faturamento / Cobrança
				// 4 - Compras / Financeiro
				// 5 - Gerencial
				// 6 - Manifesto 
				// 7 - DP/RH
				// 8 - Vendas
				// 9 -  Manutenção / Almoxarifado
				// 10 - Ambiental e Patrimonial
				// 11 - Juridico
				// 12 - Portaria
				// 13 - Oficina
				// 14 - Vistoriador
		
		
					if($mostra['nivel']==1){
						echo '<td>Operacional</td>';	
					}
					if($mostra['nivel']==2){
						echo '<td>Comercial</td>';	
					}
					if($mostra['nivel']==3){
						echo '<td>Faturamento</td>';	
					}
					if($mostra['nivel']==4){
						echo '<td>Financeiro</td>';	
					}
					if($mostra['nivel']==5){
						echo '<td>Gerencial</td>';	
					}
					if($mostra['nivel']==6){
						echo '<td>Manifesto </td>';	
					}
					if($mostra['nivel']==7){
						echo '<td>DP/RH</td>';	
					}
					if($mostra['nivel']==8){
						echo '<td>Vendas</td>';	
					}
					if($mostra['nivel']==9){
						echo '<td>Manutenção / Almoxarifado </td>';	
					}
		
					if($mostra['nivel']==10){
						echo '<td>Ambiental e Patrimonial </td>';	
					}
		
		
					if($mostra['nivel']==11){
						echo '<td>Jurídico </td>';	
					}
		
					if($mostra['nivel']==12){
						echo '<td>Oficina </td>';	
					}
		
					if($mostra['nivel']==13){
						echo '<td>Vistoriador </td>';	
					}
		
					echo '<td>'.$status.'</td>';	
					echo '<td>'.$mostra['telefone'].'</td>';	
		
					echo '<td align="center">
						<a href="painel.php?execute=suporte/usuario/usuario-editar&usuarioEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar" class="tip" />
              			</a>
				    </td>';
					echo '<td align="center">
			  			<a href="painel.php?execute=suporte/usuario/usuario-editar&usuarioDeletar='.$mostra['id'].'">
							<img src="ico/excluir.png" alt="Excluir" title="Excluir" class="tip" />
              			</a>
					</td>';
				endforeach;
				
				echo '<tfoot>';
                        echo '<tr>';
                            echo '<td colspan="13">' . 'Total de Registros : ' .  $total . '</td>';
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

