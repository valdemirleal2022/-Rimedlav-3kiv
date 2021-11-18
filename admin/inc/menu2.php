<head>
    <meta charset="iso-8859-1">
</head>

<?php 

$hoje= date( "Y/m/d");

$mes = date('m/Y');
$mesano = explode('/',$mes);

$data1 = converteData1();
$data2 = converteData2();

$usuarioId=$_SESSION['autUser']['id'];
$usuario= mostra('usuarios',"WHERE id AND id='$usuarioId'");

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

?>

<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <?php 
					if(!empty($_SESSION['autUser']['fotoperfil'])){
						   echo '<img src="'.URL.'/uploads/usuarios/'.$_SESSION['autUser']		['fotoperfil'].'" class="img-circle"/>';
						  }else{
							echo '<img src="'.URL.'/site/images/autor.png" class="img-circle"/>';
					}	
				?>
            </div>
            <div class="pull-left info">
                <p>
               <?php echo $usuario['nome']; ?>
                </p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <form action="painel.php?execute=suporte/cliente/clientes" method="post" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="pesquisa" class="form-control" placeholder="Localizar">
                <span class="input-group-btn">
                <button type="submit" name="nome" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>

		
        <ul class="sidebar-menu">

            <!--MENU atualizado 15:30-->
            <li class="header">Menu</li>
            
             <li>
                   <a href="painel.php?execute=suporte/agenda/agenda-lembrete">
				      <i class="fa fa-check"></i></i> <span> Post It</span>
			       </a>
                    
            </li>
            
       		<?php if($usuario['nivel']=='5'){ // COMERCIAL & GERENCIAL ?>
			
	           <!--PAINEL-->
            <li class="treeview">
                <a href="#">
					<i class="fa fa-pie-chart"></i>
					</i> <span>Painel Gr&aacute;ficos</span> <i class="fa fa-angle-left pull-right"></i>
				  </a>
     
                <ul class="treeview-menu">
                    <li><a href="painel.php?execute=suporte/grafico/grafico-contrato-ativo">
                    <i class="fa fa-circle-o"></i> Contratos Ativos</a>
                    </li>
                    
                    <li><a href="painel.php?execute=suporte/grafico/graficos-consultor">
                    <i class="fa fa-circle-o"></i>  Contratos por Consultor</a>
                    </li>
                    
                     <li><a href="painel.php?execute=suporte/grafico/acompanhamento-visitas">
                    <i class="fa fa-circle-o"></i>  Acompanhanto de Visitas</a>
                    </li>
                    
                    <li><a href="painel.php?execute=suporte/grafico/graficos-ordem">
                    <i class="fa fa-circle-o"></i>  Ordem de Servi&ccedil;o</a>
                    </li>
					
					  <li><a href="painel.php?execute=suporte/grafico/graficos-pesagem">
                     <i class="fa fa-circle-o"></i>Pesagem por Rota</a>
                    </li>
					
					 <li><a href="painel.php?execute=suporte/grafico/graficos-analise-rota">
                     <i class="fa fa-circle-o"></i>Analise de Rota</a>
                    </li>
					
					 <li><a href="painel.php?execute=suporte/grafico/graficos-rentabilidade-rota">
                     <i class="fa fa-circle-o"></i>Rentabilidade por Rota</a>
                    </li>
                    
                    
                     <li><a href="painel.php?execute=suporte/grafico/graficos-venda">
                     <i class="fa fa-circle-o"></i> Vendas Mensal</a>
                    </li>
                    
                     <li><a href="painel.php?execute=suporte/grafico/graficos-financeiro">
                     <i class="fa fa-circle-o"></i> Financeiro</a>
                    </li>
                </ul>
            </li>
       	
	<?php }?>
	
	<?php if($usuario['nivel']=='5'){ // COMERCIAL & GERENCIAL ?>

	<!--SITE-->
            <li class="treeview">
                <a href="#">
                <i class="fa fa-chrome"></i>
               </i> <span>Site</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
  
                <ul class="treeview-menu">
                   	 <li><a href="painel.php?execute=site/paginas">
                    <i class="fa fa-circle-o"></i>P&aacute;ginas</a>
                    </li>
                    <li><a href="painel.php?execute=site/categorias">
                    <i class="fa fa-circle-o"></i>Categorias</a>
                    </li>
                    <li><a href="painel.php?execute=site/noticias">
                    <i class="fa fa-circle-o"></i>Post</a>
                    </li>
                    <li><a href="painel.php?execute=site/comentarios">
                    <i class="fa fa-circle-o"></i>Coment&aacute;rios</a>
                    </li>
                    <li><a href="painel.php?execute=site/eventos">
                    <i class="fa fa-circle-o"></i>Eventos</a>
                    </li>
                    <li><a href="painel.php?execute=site/emails">
                    <i class="fa fa-circle-o"></i>NewLetters</a>
                    </li>
                    <li><a href="painel.php?execute=site/faqs">
                    <i class="fa fa-circle-o"></i>FAQ</a>
                    </li>
                    <li><a href="painel.php?execute=site/galeria">
                    <i class="fa fa-circle-o"></i>Galeria</a>
                    </li>
                    <li><a href="painel.php?execute=site/videos">
                    <i class="fa fa-circle-o"></i>V&iacute;deos</a>
                    </li>
                </ul>
            </li>
            
		 <?php }?>

		 
		 <?php if($usuario['nivel']=='2' || $usuario['nivel']=='5'){ // COMERCIAL & GERENCIAL ?>
		 
<!--EMAILS-->		
          <li class="treeview">
				<a href="#">
               <i class="fa fa-envelope"></i>
               </i> <span>Email</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
			
				<ul class="treeview-menu">

					<li>
						<a href="painel.php?execute=suporte/email/email-mkt">
						<i class="fa fa-circle-o">
						  </i>Mensagem
             			</a>
					
					</li>

					<?php // $total = conta('email',"WHERE id");?>
					<li>
						<a href="painel.php?execute=suporte/email/emails">
						 <span class="label label-primary pull-right"> <?php //echo $total;?></span>
						 <i class="fa fa-circle-o"></i>
						 Emails</a>
					</li>

					<?php 
               
                // $total = conta('email',"WHERE data>='$hoje'");
               ?>
					<li>
						<a href="painel.php?execute=suporte/email/emails-enviados">
						 <span class="label label-primary pull-right"> <?php //echo $total;?></span>
						 <i class="fa fa-circle-o"></i>
						  Enviados</a>
					</li>
				
					<!-- 5 Contrato Ativo -->
                        <?php //$ativos = conta('contrato',"WHERE id AND tipo='2' AND status='5'");?>
                        <li>
                            <a href="painel.php?execute=suporte/email/email-contrato-ativos">
							 <span class="label label-primary pull-right bg-green"> <?php// echo $ativos;?></span>
							<i class="fa fa-circle-o"></i>
							 Ativos
							</a>
                        </li>
			</ul>
		</li>
		
	<?php }?>
	
<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado  -->

<!--AGENDA-->
	
	<?php if($usuario['nivel']=='3' //3 - Faturamento / Cobrança
			 || $usuario['nivel']=='5' 
			  || $usuario['nivel']=='3'  //  3 - Faturamento
			|| $usuario['nivel']=='2'  //2 - Atendimento ao Cliente
			){ 
	?>
	
	
	<li class="treeview">
      	 <a href="#">
            <i class="fa fa-calendar-check-o"></i>
               </i> <span>Agenda</span> <i class="fa fa-angle-left pull-right"></i>
                <?php 
				 
					//$agenda= conta('agenda',"WHERE id AND status='1' AND retorno<='$hoje'");
					//if($agenda<>'0'){
					//	echo '<small class="label label-primary pull-right">'. $agenda .'</small>';  
					//}
				?>
              </a>
              
           		<ul class="treeview-menu">
					 <?php // $agenda= conta('agenda',"WHERE id AND status='1' AND retorno>='$dataHoje'");?>
					<li>
					<a href="painel.php?execute=suporte/agenda/agenda">
					 <span class="label label-primary pull-right"> <?php //echo $agenda;?></span>
					 <i class="fa fa-circle-o text-aqua"></i>
					 Agenda</a>
					</li>
                </ul>
    		</li>
    		
		<?php }?>

<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado  -->

<!--VISITAS-->
	
	<?php if($usuario['nivel']=='2'  // 2 - Atendimento ao Cliente
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			  || $usuario['nivel']=='8' //8 - Vendas 
			){ 
	?>
	
	<li class="treeview">
      	 <a href="#">
            <i class="fa fa-building"></i>
               </i> <span>Visitas</span> <i class="fa fa-angle-left pull-right"></i>
                <?php 
					//$visita= conta('cadastro_visita',"WHERE id AND status='0'");
					//if($visita<>'0'){
					//	echo '<small class="label label-primary pull-right">'. $visita .'</small>';  
					//}
				?>
              </a>
              
           <ul class="treeview-menu">
               
				<li>
				<a href="painel.php?execute=suporte/visita/visitas">
                 <span class="label label-primary pull-right"> <?php //echo $visita;?></span>
                 <i class="fa fa-circle-o text-aqua"></i>
                 Visitas</a>
				</li>

				<?php  
				//$total = conta('cadastro_visita_negociacao',"WHERE interacao>='$hoje'");
			   	?>

				<li>
				<a href="painel.php?execute=suporte/visita/visita-negociacao">
                 <span class="label label-primary pull-right"> <?php //echo $total;?></span>
                 <i class="fa fa-circle-o text-aqua"></i>
                 Contatos</a>
				</li>
				
				<?php  
					//$total = conta('cadastro_visita_negociacao',"WHERE retorno<='$hoje' AND status='1'");
			   ?>
				<li>
				<a href="painel.php?execute=suporte/visita/visita-negociacao-retorno">
                 <span class="label label-primary pull-right"> <?php //echo $total;?></span>
                 <i class="fa fa-circle-o text-aqua"></i>
                 Retorno</a>
				</li>
                  
                  
                 <?php// $cancelados = conta('cadastro_visita',"WHERE id AND status='17'"); ?>
                   <li>
                      <a href="painel.php?execute=suporte/visita/visita-canceladas">
						<span class="label label-danger pull-right"> <?php ///echo $cancelados;?></span>
						<i class="fa fa-circle-o"></i>
						Cancelados
					</a>
                 </li>
                       
                       
              <li><a href="painel.php?execute=suporte/grafico/acompanhamento-visitas">
                    <i class="fa fa-circle-o"></i>  Acompanhanto de Visitas</a>
                    </li>                        
                   
                <li>
                   <a href="painel.php?execute=suporte/visita/visita-empresa-atual">
					<span class="label label-primary pull-right"></span>
					<i class="fa fa-circle-o text-aqua"></i>
					Empresa Atual
					</a>
                  </li>
                   
                  <li>
                   <a href="painel.php?execute=suporte/visita/visita-motivo-cancelamento">
					<span class="label label-primary pull-right"></span>
					<i class="fa fa-circle-o text-aqua"></i>
					Motivo Cancelamento
					</a>
                  </li>
			   
			    <?php // $visita= conta('cadastro_prospeccao',"WHERE id AND status='0'");?>
				<li>
				<a href="painel.php?execute=suporte/visita/prospeccao">
                 <span class="label label-primary pull-right"> <?php //echo $visita;?></span>
                 <i class="fa fa-circle-o text-aqua"></i>
                 Prospeccao</a>
				</li>

			   	
  
                  </ul>
    		</li>
    		
		<?php }?>


<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado  -->

<!--Atendimentos-->

	<?php if($usuario['nivel']=='2'  //2 - Atendimento ao Cliente
			 || $usuario['nivel']=='3'  //  3 - Faturamento
			  || $usuario['nivel']=='8'  //  8 - Venda
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
		){ 
	?>

 		<li class="treeview">
              <a href="#">
                <i class="fa fa-refresh"></i>
               </i> <span>Atendimentos</span> <i class="fa fa-angle-left pull-right"></i>
       			<?php 
				 
				// $pedido = conta('pedido',"WHERE data_solicitacao>='$hoje'");
				//   if( $pedido>0){
				//	  echo '<small class="label pull-right bg-red">'. $pedido .'</small>';  
				 //  }
				// $atendimento = conta('contrato_atendimento_pos_venda',"WHERE data_solicitacao>='$hoje'");
                ?>
              </a>
              <ul class="treeview-menu">
                <li>
                <a href="painel.php?execute=suporte/atendimento/pedidos">
                 <span class="label label-primary pull-right"> <?php// echo $pedido;?></span>
                <i class="fa fa-circle-o text-aqua"></i>
                Atendimento
                </a>
                </li>
                 <li>
                <a href="painel.php?execute=suporte/atendimento/suportes">
                <i class="fa fa-circle-o text-aqua"></i>
                Motivos
                </a>
                </li>
				  
				<li>
                <a href="painel.php?execute=suporte/atendimento/atendimentos">
                 <span class="label label-primary pull-right"> <?php// echo $atendimento;?></span>
                <i class="fa fa-circle-o text-aqua"></i>
                Pos-venda
                </a>
                </li>
                 <li>
                <a href="painel.php?execute=suporte/atendimento/atendimento-motivos">
                <i class="fa fa-circle-o text-aqua"></i>
                Motivos Pos-Venda
                </a>
                </li>
               </ul>
            </li>
		
		<?php }?>
		
<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

		<?php if($usuario['nivel']=='2'  //2 - Atendimento ao Cliente
				 || $usuario['nivel']=='5'  // 5 - Gerencial 
				  || $usuario['nivel']=='8' //8 - Vendas 
				){  
		?>	

		<!--ORCAMENTOS-->
                <li class="treeview">
                    <a href="#">
					   <i class="fa fa-files-o"></i>
					 
					   </i> <span>Or&ccedil;amento</span> <i class="fa fa-angle-left pull-right"></i>

					   <?php 
	
						//$solicitacoes = conta('cadastro_visita',"WHERE id AND status='1'");
						//$contatos = conta('contato',"WHERE id AND status='Em Aberto'");
						//$orcamentos=$solicitacoes+$contatos;
						//if( $orcamentos>0){
							//echo //'<small class="label label-primary pull-right">'. $orcamentos .'</small>';  
						// }
	
						?>
					</a>
                


                    <ul class="treeview-menu">
                      
                       <?php //$contatos = conta('contato',"WHERE id AND status='Em Aberto'"); ?>
						
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/contatos">
							 <span class="label label-primary pull-right"> <?php //echo $contatos;?></span>
							 <i class="fa fa-circle-o"></i>
							Contatos
							</a>
                        </li>
                       
                        <?php  //$solicitacoes = conta('cadastro_visita',"WHERE id AND status='1'"); ?>
						
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/solicitacoes">
							 <span class="label label-primary pull-right"> <?php //echo $solicitacoes;?></span>
							 <i class="fa fa-circle-o"></i>
						   Solicitações
							</a>
                        </li>

                       

                        <?php //$orcamentos=conta('cadastro_visita',"WHERE id AND status='2' ");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/orcamentos">
								<span class="label label-primary pull-right"> <?php// echo $orcamentos;?></span>
								<i class="fa fa-circle-o"></i>
								Orçamentos
						   </a>
                        </li>
                        
                        
                 <?php// $autorizacao=conta('cadastro_visita',"WHERE id AND aprovacao_comercial='3'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/autorizacoes">
								<span class="label pull-right bg-green"> <?php //echo $autorizacao;?></span><i class="fa fa-circle-o"></i>
								Autoriza&ccedil;&otilde;es
						   </a>
                        </li>
						
				<?php// $naoAutorizado=conta('cadastro_visita',"WHERE id AND aprovacao_comercial='2'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/nao-autorizado">
								<span class="label pull-right bg-green"> <?php// echo $naoAutorizado;?></span><i class="fa fa-circle-o"></i>
								Não Autorizado
						   </a>
                        </li>

                <?php// $followup = conta('cadastro_visita',"WHERE id AND status='3'"); ?>
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/followup">
								<span class="label label-primary pull-right"> <?php// echo $followup;?></span>
								<i class="fa fa-circle-o"></i>
								Follow Up
							</a>
                        </li>
                        
                 <?php //$aprovados=conta('cadastro_visita',"WHERE id AND status='4' ");?>
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/orcamento-aprovados">
								<span class="label label-primary pull-right"> <?php //echo $aprovados;?></span>
								<i class="fa fa-circle-o"></i>
								Aprovados
						   </a>
                        </li>
                        
               <?php //$cancelados = conta('cadastro_visita',"WHERE id AND status='17'"); ?>
						
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/orcamento-cancelados">
								<span class="label label-danger pull-right"> <?php //echo $cancelados;?></span>
								<i class="fa fa-circle-o"></i>
								Cancelados
							</a>
                        </li>
						
					
                    </ul>
                </li>
		
		<?php }?>
		
		
		<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado  -->

<!--CLIENTES-->

	<?php if($usuario['nivel']=='2'  //2 - Atendimento ao Cliente
			|| $usuario['nivel']=='1'  //1 - Operacional 
			|| $usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			|| $usuario['nivel']=='5'  // 5 - Gerencial 
			|| $usuario['nivel']=='8' //8 - Vendas 
			|| $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
			 
			){  
	?>	
            <li class="treeview">
               
                <a href="#">
					<i class="fa fa-user"></i>
					</i> <span>Clientes</span> <i class="fa fa-angle-left pull-right"></i>
				</a>

                <ul class="treeview-menu">
					
                    <?php //$ativos = conta('cliente',"WHERE id");?>
					
                    <li>
                        <a href="painel.php?execute=suporte/cliente/clientes">
							 <span class="label label-primary pull-right"> <?php //echo $ativos;?></span>
							<i class="fa fa-circle-o"></i>
							Visualizar 
						 </a>
					 </li>
					     <?php //$ativos = conta('cliente',"WHERE id AND email=''");?>
					 <li>
                        <a href="painel.php?execute=suporte/cliente/clientes-email">
							 <span class="label label-primary pull-right"> <?php// echo $ativos;?></span>
							<i class="fa fa-circle-o"></i>
							S/Email 
						 </a>
					 </li>
					
					 <li>
                        <a href="painel.php?execute=suporte/cliente/clientes-correios">
							 </span>
						 <i class="fa fa-circle-o"></i>
							Correios 
						 </a>
					 </li>
					
					 <li>
                        <a href="painel.php?execute=suporte/cliente/clientes-tipo">
							</span>
						 <i class="fa fa-circle-o"></i>
							Tipo/Classifica&ccedil;&atilde;o 
						 </a>
					 </li>
                </ul>
            </li>
            
         <?php }?>
		
	
	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='2'  //2 - Atendimento ao Cliente
			 || $usuario['nivel']=='1'  //1 - Operacional 
			 || $usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			 || $usuario['nivel']=='6'  // 6 - Manifesto
			 || $usuario['nivel']=='8' //8 - Vendas 
			 || $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
			  || $usuario['nivel']=='11' //11 - Juridico
			){  
	?>	

<!-- CONTRATOS-->
                <li class="treeview">
                   
                    <a href="#">
						<i class="fa fa-align-justify"></i>
						</i> <span>Contratos</span> <i class="fa fa-angle-left pull-right"></i>
						<?php 
	
						// $contratos= conta('contrato',"WHERE id AND tipo='2'");
	
						 //if($contratos>0){
							//  echo //'<small class="label pull-right bg-green">'. $contratos .'</small>';  
						// }
						?>
					 </a>
       
                    <ul class="treeview-menu">

                       <?php 
					
						//$aprovados = conta('contrato',"WHERE id AND tipo='2' AND month(aprovacao)='$mesano[0]' AND Year(aprovacao)='$mesano[1]'");
	
						?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-aprovados">
							 <span class="label label-primary pull-right"> <?php// echo $aprovados;?></span>
							<i class="fa fa-circle-o"></i>
							Aprovados
							</a>
                        </li>
                        
                        <?php 
						 
						//$iniciando = conta('contrato',"WHERE id AND tipo='2' AND month(inicio)='$mesano[0]' AND Year(inicio)='$mesano[1]'");
	
						?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-iniciando">
							 <span class="label label-primary pull-right "> <?php //echo $iniciando;?></span>
							<i class="fa fa-circle-o"></i>
							Iniciando
							</a>
                        </li>

						<!-- 5 Contrato Ativo -->
                        <?php// $ativos = conta('contrato',"WHERE id AND tipo='2' AND status='5'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-ativos">
							 <span class="label label-primary pull-right bg-green"> <?php// echo $ativos;?></span>
							<i class="fa fa-circle-o"></i>
							 Ativos
							</a>
                        </li>
                        
       					<!-- 6 Contrato Suspensos-->
                        <?php// $suspensos = conta('contrato',"WHERE id AND status='6'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-suspensos">
							 <span class="label  pull-right bg-orange"> <?php// echo $suspensos;?></span>
							<i class="fa fa-circle-o"></i>
							Suspensos
							</a>
                        </li>
			
						<!-- 19 Contrato Suspensos Temporariamento-->
                        <?php// $suspensos = conta('contrato',"WHERE id AND status='19'");?>
						
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-suspensos-temporario">
							 <span class="label  pull-right bg-orange"> <?php// echo $suspensos;?></span>
							<i class="fa fa-circle-o"></i>
							Suspensos Temporário
							</a>
                        </li>
						
						<!-- 19 Contrato Suspensos Temporariamento-->
                        <?php 
						
							//$suspensos = conta('contrato',"WHERE data_suspensao>='$data1' AND data_suspensao<='$data2' AND status='5")?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-suspensos-reativados">
							 <span class="label  pull-right bg-orange"> <?php//echo $suspensos;?></span>
							<i class="fa fa-circle-o"></i>
							Suspensos Reativados
							</a>
                        </li>
                        
                        <!-- 7 	Contrato Rescindido-->
                        <?php// $rescisao = conta('contrato',"WHERE id AND status='7' AND consultor='$consultorId'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-rescindidos">
							 <span class="label label-primary pull-right bg-orange"> <?php //echo $rescisao;?></span>
							<i class="fa fa-circle-o"></i>
							Rescindidos
							</a>
                        </li>
                        
                        <!--// 9 Contrato Cancelado-->
                        <?php// $cancelamento = conta('contrato_cancelamento',"WHERE id AND status='Aguardando'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/solicitacao-cancelamento">
							 <span class="label label-danger pull-right">
							  <?php// echo $cancelamento;?>
							  </span>
							<i class="fa fa-circle-o"></i>
						    Cancelamentos
						   </a>
                        </li>
						
						<!--// 9 Contrato Cancelado-->
                        <?php //$cancelados = conta('contrato',"WHERE id AND status='9'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-cancelados">
							 <span class="label label-danger pull-right">
							  <?php// echo $cancelados;?>
							  </span>
							<i class="fa fa-circle-o"></i>
						   Cancelados
						   </a>
                        </li>
                        
                        
                        <!--// 10 Ação Judicialo-->
                        <?php// $judicial = conta('contrato',"WHERE id AND status='10'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-judicial">
							 <span class="label label-danger pull-right">
							  <?php //echo $judicial;?>
							  </span>
							<i class="fa fa-circle-o"></i>
						   A&ccedil;&atilde;o Judicial
						   </a>
                        </li>
						
						
						 <!--// aditivos-->
                        <?php 
						//$aditivos = conta('contrato_aditivo',"WHERE id");
						?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-aditivados">
							 <span class="label label-danger pull-right">
							  <?php// echo $aditivos;?>
							  </span>
							<i class="fa fa-circle-o"></i>
						   Aditivos
						   </a>
                        </li>
						
						  <?php 
				//$refaturar = conta('receber',"WHERE refaturar='1' AND  refaturamento_autorizacao='1'");
							?>
                        <small class="label label-warning pull-right">
                            <?php// echo $refaturar;?>
                        </small>
                        <li>
                            <a href="painel.php?execute=suporte/receber/receber-refaturamento-autorizados">
							  <i class="fa fa-circle-o"></i>
							Refaturamento
							</a>
                        </li>
						
						
						
						 <!--// aditivos-->
                        <?php //$locacao = conta('contrato',"WHERE id AND cobrar_locacao='1'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-locacao">
							 <span class="label label-danger pull-right">
							  <?php //echo $locacao;?>
							  </span>
							<i class="fa fa-circle-o"></i>
						   Locação
						   </a>
                        </li>
                        
                       <li><a href="painel.php?execute=suporte/mapas/contrato-rota">
                          <span class="label label-primary pull-right"></span>
                          <i class="fa fa-circle-o"></i> Por Rota</a>
                         </li>
                        
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-renovacao">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o"></i>
						   Renova&ccedil;&atilde;o
						   </a>
                        </li>
						
						
						 <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-renovacao-eduardo">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o"></i>
						   Simular
						   </a>
                        </li>
						
						<?php //$autorizacao=conta('cadastro_visita',"WHERE id AND aprovacao_juridico='3'");?>
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/autorizacoes-juridico">
								<span class="label pull-right bg-green"> <?php //echo $autorizacao;?></span><i class="fa fa-circle-o"></i>
								Autoriza&ccedil;&otilde;es Juridico
						   </a>
                        </li>
						
					  <?php //$autorizacao=conta('contrato_aditivo',"WHERE id AND aprovacao_comercial='3'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-aditivo-autorizacoes-comercial">
								<span class="label pull-right bg-green"> <?php// echo $autorizacao;?></span>
								<i class="fa fa-circle-o"></i>
								Aditivo Autorizaçõeas
						   </a>
                        </li>
						
						 <?php// $autorizacao=conta('contrato_aditivo',"WHERE id AND aprovacao_comercial='1'");?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-aditivo-autorizados">
								<span class="label pull-right bg-green"> <?php// echo $autorizacao;?></span>
								<i class="fa fa-circle-o"></i>
								Aditivo Autorizados
						   </a>
                        </li>
                     
						  <li>
                              <a href="painel.php?execute=suporte/receber/receber-desconto-autorizados">
							  <i class="fa fa-circle-o"></i>
							  Desconto
							  </a>
                            </li>
			                        
                    </ul>
                </li>

	<?php }?>
		
	
	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='1'  //1 - Operacional 
			|| $usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			|| $usuario['nivel']=='5'  // 5 - Gerencial 
			|| $usuario['nivel']=='6'  // 6 - Manifesto
			|| $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
			){  
	?>	

<!--ORDEM DE SERVIÇO-->
                <li class="treeview">

                    <a href="#">
						<i class="fa fa-file-text"></i>
						</i> <span>Ordem de Servi&ccedil;o</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
                
                    <ul class="treeview-menu">

                        <?php 
	
							//$ordem = conta('contrato_ordem',"WHERE id AND data='$hoje'"); 
	
						?>

                        <li>
                            <a href="painel.php?execute=suporte/ordem/gerar-ordem">
							 <span class="label label-primary pull-right"><?php// echo $ordem;?> </span>
							<i class="fa fa-circle-o text-aqua"></i>
							Gerar Ordem
						   </a>
                        </li>
                        
                        <?php

						$ordemGerada=conta('contrato_ordem',"WHERE id AND status='12' AND data='$hoje'"); 
	
						?>
                        <li>
                            <a href="painel.php?execute=suporte/ordem/emissao-ordem">
							 <span class="label label-primary pull-right"> <?php// echo $ordemGerada;?></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Emiss&atilde;o de Ordem
							</a>
                        </li>
                        
  
                       <?php
				
						//$ordemRealizada=conta('contrato_ordem',"WHERE id AND status='13' AND data='$hoje'"); 
	
						?>
						
                        <li>
                            <a href="painel.php?execute=suporte/ordem/ordem-realizadas">
							 <span class="label label-primary pull-right"> <?php //echo $ordemRealizada;?></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Rentabilidade
							</a>
                        </li>
                        
                        
						
		<?php if($usuario['nivel']>='1'  // 5 - Gerencial 
			){ 
		?>
						 <?php
						 
				 ///	$ordemEmAberto=conta('contrato_ordem',"WHERE id AND status='12' AND data='$hoje'"); 
							
						?>
                        <li>
                            <a href="painel.php?execute=suporte/ordem/ordem-emaberto">
							 <span class="label label-primary pull-right"> <?php// echo $ordemEmAberto;?></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Em Aberto
							</a>
                        </li>
		<?php 
			}
		?>
                         <li>
                            <a href="painel.php?execute=suporte/ordem/ordem-transferidos">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Transferidas
							</a>
                        </li>
                        
                        <li>
                            <a href="painel.php?execute=suporte/ordem/ordem-canceladas">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Canceladas
							</a>
                        </li>
			                      
                        <li>
                            <a href="painel.php?execute=suporte/ordem/ordem-naocoletada">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							N&atilde;o Coletada
							</a>
                        </li>
						
						  <li>
                            <a href="painel.php?execute=suporte/ordem/ordem-zeradas">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Zeradas
							</a>
                        </li>
                        
                        <?php 
						//$autorizacao=conta('cadastro_visita',"WHERE id AND aprovacao_operacional='3'");
						?>
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/autorizacoes-rota">
								<span class="label pull-right bg-green"> <?php// echo $autorizacao;?></span>
								<i class="fa fa-circle-o"></i>
								Autoriza&ccedil;&otilde;es de Rota
						   </a>
                        </li>
						
					<?php 
						//$autorizacao=conta('contrato_aditivo',"WHERE id AND aprovacao_operacional='3'");
						
						?>
                        <li>
                            <a href="painel.php?execute=suporte/contrato/contrato-aditivo-autorizacoes-rota">
								<span class="label pull-right bg-green"> <?php //echo $autorizacao;?></span>
								<i class="fa fa-circle-o"></i>
								Aditivo Autoriza&ccedil;&otilde;es 
						   </a>
                        </li>
						
						   <?php// $aprovados=conta('cadastro_visita',"WHERE id AND status='4' ");?>
                        <li>
                            <a href="painel.php?execute=suporte/orcamento/orcamento-aprovados">
								<span class="label label-primary pull-right"> <?php //echo $aprovados;?></span>
								<i class="fa fa-circle-o"></i>
								Aprovados
						   </a>
                        </li>
                        
    
                       <li>
                         <a href="painel.php?execute=suporte/mapas/contrato-rota">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Ajuste de Rota
							</a>
                        </li>
                        
                        
                        <li>
                            <a href="painel.php?execute=suporte/ordem/rota-semanal">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Rota Semanal
							</a>
                        </li>
                
      				
                      <!--<li>
                         <a href="painel.php?execute=suporte/ordem/gerar-ordem-infectante">
							<span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Gerar Ordem Infectante 
							</a> 
                        </li>-->
           
                      <!--<li>
                         <a href="painel.php?execute=suporte/ordem/emissao-baixa">
							<span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Baixas 
							</a> 
                        </li>-->
                
                    </ul>
                </li>
                
      <?php }?>
		
	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='1'  //1 - Operacional 
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			 || $usuario['nivel']=='6'  // 6 - Manifesto
			 || $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
			){  
	?>	

                <li class="treeview">

                    <a href="#">
						<i class="fa fa-file-text-o"></i>
						</i> <span>Manifestos</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
                

                    <ul class="treeview-menu">
                         <li><a href="painel.php?execute=suporte/manifesto/contrato-manifestos"><i class="fa fa-circle-o"></i>Contratos</a>
                            </li>

                        <?php 
					 
							//$ordem = conta('contrato_ordem',"WHERE id AND manifesto='M' AND data='$hoje'"); 
						?>

                        <li>
                            <a href="painel.php?execute=suporte/manifesto/manifesto-agendados">
							 <span class="label label-primary pull-right"><?php //echo $ordem;?> </span>
							<i class="fa fa-circle-o text-aqua"></i>
							Emiss&atilde;o
						   </a>
                         </li>

                        <?php //$ordem = conta('contrato_ordem',"WHERE id AND status='13'"); ?>
                        <li>
                            <a href="painel.php?execute=suporte/manifesto/manifesto-realizados">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Baixados
							</a>
                        </li>

                        <?php //$ordem = conta('contrato_ordem',"WHERE id AND status='15'"); ?>
                        <li>
                            <a href="painel.php?execute=suporte/manifesto/ordem-canceladas">
							 <span class="label label-primary pull-right"></span>
							<i class="fa fa-circle-o text-aqua"></i>
							Canceladas
							</a>
                        </li>
                    </ul>
                </li>
                
         <?php }?>
		
	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='1'  //1 - Operacional 
			 || $usuario['nivel']=='9'  // 9 - Manutenção / Almoxarifado
			  || $usuario['nivel']=='3'  //  3 - Faturamento
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			|| $usuario['nivel']=='2'  //2 - Atendimento ao Cliente
		){  
	?>	

<!--VEICULOS-->
            <li class="treeview">
               <a href="#">
                <i class="fa fa-car"></i>
               </i> <span>Veiculos</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
                  
         
				 <ul class="treeview-menu">
                     <li><a href="painel.php?execute=suporte/veiculo/veiculos"><i class="fa fa-circle-o"></i>Ve&iacute;culos</a>
                       </li>
                      <li><a href="painel.php?execute=suporte/veiculo/liberacoes"><i class="fa fa-circle-o"></i>Libera&ccedil;&atilde;o</a>
                      </li>
                
				  </ul>
                   <ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i>Manutenção<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
                      <li><a href="painel.php?execute=suporte/veiculo/manutencoes"><i class="fa fa-circle-o"></i>Manutenção</a>
                       </li>
						   
					  <li>
						  <a href="painel.php?execute=suporte/veiculo/manutencao-responsavel">
						  <i class="fa fa-circle-o"></i>
						 Responsável
						  </a>
						 </li>
						
						</ul>
                	</li>
             	 </ul>
             	 	   
        <ul class="treeview-menu">
						 
            <li>
				<a href="painel.php?execute=suporte/veiculo/abastecimentos"><i class="fa fa-circle-o"></i>Abastecimentos
				</a>
             </li>
	
       	  </ul>

  			
                   
                   <ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Abastecimento Interno<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						 <a href="painel.php?execute=suporte/veiculo/combustivel">
						 <i class="fa fa-circle-o"></i>
						 Estoque
						 </a>
						 </li>

						 <li>
						  <a href="painel.php?execute=suporte/veiculo/combustivel-retirada">
						  <i class="fa fa-circle-o"></i>
						  Abastecimento
						  </a>
						 </li>
						   
						  <li>
						  <a href="painel.php?execute=suporte/veiculo/combustivel-reposicao">
						  <i class="fa fa-circle-o"></i>
						  Reposi&ccedil;&atilde;o
						  </a>
						 </li>
						
						</ul>
                	</li>
             	 </ul>

				<ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Lavagem<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						 <a href="painel.php?execute=suporte/veiculo/lavagens">
						 <i class="fa fa-circle-o"></i>
						 Lavagens
						 </a>
						 </li>

						 <li>
						  <a href="painel.php?execute=suporte/veiculo/lavagem-tipos">
						  <i class="fa fa-circle-o"></i>
						  Tipo
						  </a>
						 </li>
						   
						  <li>
						  <a href="painel.php?execute=suporte/veiculo/lavagem-executores">
						  <i class="fa fa-circle-o"></i>
						  Executores
						  </a>
						 </li>
						
						</ul>
                	</li>
             	 </ul>
                   
             	 
             	 <ul class="treeview-menu">
					 
			 <li>
				 <a href="painel.php?execute=suporte/veiculo/tacografos"><i class="fa fa-circle-o"></i>Tacógrafos
				 </a>
              
			</li>
  
                     <li><a href="painel.php?execute=suporte/veiculo/aterros"><i class="fa fa-circle-o"></i>Aterro</a>
                     </li>
					
					 <li><a href="painel.php?execute=suporte/veiculo/motoristas"><i class="fa fa-circle-o"></i>Motorista/Coletor</a>
                      </li>
                   </ul>

			
 
              </li>
                   
                   
        <li class="treeview">
              <a href="#">
                <i class="fa fa-ambulance"></i>
               </i> <span>Ocorrencias</span> <i class="fa fa-angle-left pull-right"></i>
       			<?php 
	 
				// $pedido = conta('rota_ocorrencia',"WHERE data='$hoje'");
				  // if( $pedido>0){
					//  echo '<small class="label pull-right bg-red">'. $pedido .'</small>';  
				 //  }
                ?>
              </a>
              <ul class="treeview-menu">
                <li>
                <a href="painel.php?execute=suporte/veiculo/ocorrencias">
                 <span class="label label-primary pull-right"> <?php //echo $pedido;?></span>
                <i class="fa fa-circle-o text-aqua"></i>
                Ocorrencias
                </a>
                </li>
                 <li>
                <a href="painel.php?execute=suporte/veiculo/ocorrencia-tipo">
                <i class="fa fa-circle-o text-aqua"></i>
                Tipos 
                </a>
                </li>
               </ul>
            </li>
                    
        <?php }?>

<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='1'  //1 - Operacional 
			 || $usuario['nivel']=='9'  // 9 - Manutenção / Almoxarifado
			  || $usuario['nivel']=='3'  //  3 - Faturamento
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			|| $usuario['nivel']=='2'  //2 - Atendimento ao Cliente
		){  
	?>	

<!--Motorista-->
            <li class="treeview">
               <a href="#">
                <i class="fa fa-car"></i>
               </i> <span>Motorista</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
                  
         
				 <ul class="treeview-menu">
                     <li><a href="painel.php?execute=suporte/motorista/motoristas"><i class="fa fa-circle-o"></i>Motorista/Coletor</a>
                      </li>
                      <li><a href="painel.php?execute=suporte/motorista/negligencias"><i class="fa fa-circle-o"></i>Premiação</a>
                      </li>
                            
                      <li><a href="painel.php?execute=suporte/motorista/motivo-negligencia"><i class="fa fa-circle-o"></i>Tipos Negligências</a>
                       </li>
                    
                   </ul>
                     
        <?php }?>

	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='5'  // 5 - Gerencial 
			 || $usuario['nivel']=='8'  // 8 - Vendas 
			){  
	?>	

		<!--VENDAS-->
		
			<li class="treeview">
			<a href="#">
               <i class="fa fa-calculator"></i>
               </i> <span>Vendas</span> <i class="fa fa-angle-left pull-right"></i>
              
              </a>
			
			<ul class="treeview-menu">

				<li>
					<a href="painel.php?execute=suporte/vendas/mensal">
               			<i class="fa fa-circle-o"></i>
                		Por Consultor
                	</a>
					
				</li>
				
				<li>
					<a href="painel.php?execute=suporte/vendas/comissao-pos-venda">
              		 <i class="fa fa-circle-o"></i>
              		 Comissões Pós-Venda
					</a>
				</li>
				
				
				<li>
					<a href="painel.php?execute=suporte/vendas/comissao-consultor">
               		<i class="fa fa-circle-o"></i>
              		 Comissões Vendedores
               		 </a>
				</li>
				
				
				<li>
					<a href="painel.php?execute=suporte/vendas/contrato-consultor">
               		<i class="fa fa-circle-o"></i>
               		Contratos por Consultor
               		</a>
				</li>
		

				</ul>
			</li>

		 <?php }?>
		
		<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			 || $usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			){  
	?>	
<!--FATURAMENTO-->
                <li class="treeview">
                    <a href="#">
					<i class="fa fa-file-pdf-o"></i>
					</i> <span>Faturamento</span> <i class="fa fa-angle-left pull-right"></i>
				  </a>

                    <ul class="treeview-menu">
                        <li>
                            <a href="painel.php?execute=suporte/faturamento/construir">
							<i class="fa fa-circle-o text-aqua"></i>
							Construção
						 </a>
                        </li>

                        <?php 
							
							  // $faturados = conta('receber',"WHERE emissao='$hoje'");
							?>
                        <small class="label label-warning pull-right">
                            <?php //echo $faturados;?>
                        </small>
                        <li>
                            <a href="painel.php?execute=suporte/faturamento/faturados">
							  <i class="fa fa-circle-o"></i>
							Faturados
							</a>
                        </li>
						
						
						  <?php 
							// $hoje = date("Y-m-d");
							// $notas = conta('receber',"WHERE pagamento='$hoje' AND link=''");
							?>
                        <small class="label label-warning pull-right">
                            <?php //echo $notas;?>
                        </small>
                        <li>
                            <a href="painel.php?execute=suporte/receber/nfe-pagamento">
							  <i class="fa fa-circle-o"></i>
							Nfe/Pagamento
							</a>
                        </li>
						  <?php 
							 $dispensa = conta('receber',"WHERE dispensa='1' AND dispensa_autorizacao='1'");
							?>
                        <small class="label label-warning pull-right">
                            <?php// echo $dispensa;?>
                        </small>
                        <li>
                            <a href="painel.php?execute=suporte/receber/receber-dispensa-autorizados">
							  <i class="fa fa-circle-o"></i>
							Dispensa
							</a>
                        </li>
         
                    </ul>
                    
         <?php }?>
		
	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança 
4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			 || $usuario['nivel']=='4'  // 4 - Compras/Financeiro
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			){  
	?>	
<!--CONTAS A RECEBER-->

              <li class="treeview">
                       
                        <a href="#">
						<i class="fa fa-money"></i>
							<span>Contas a Receber</span><i class="fa fa-angle-left pull-right"></i>
					 </a>
                        <ul class="treeview-menu">
                           
                            <li>
                                <a href="painel.php?execute=suporte/receber/receber">
								 <i class="fa fa-circle-o"></i>
								 Receita
								 </a>
                            </li>

						   <?php 
							/*
							 $mes = date('m/Y');
							 $mesano = explode('/',$mes);					   
							 $nota = conta('receber',"WHERE Month(nota_emissao)='$mesano[0]' AND 
												Year(nota_emissao)='$mesano[1]'",'valor');
												
							*/
							?>
						  <small class="label pull-right bg-green"><?php //echo $nota;?></small>
						  <li>
						  <a href="painel.php?execute=suporte/receber/receber-nfe">
						  <i class="fa fa-circle-o"></i>
						 NFe
						  </a>
						  </li> 

                            <?php 
							/*
							   $dataHoje = date("Y-m-d", strtotime("-3 day"));
							   $TotalEmAberto = conta('receber',"WHERE vencimento<='$dataHoje' AND status='Em Aberto'");
							   $TotalProtesto = conta('receber',"WHERE vencimento<='$dataHoje' AND status='Protesto'");
							   $TotalSerasa = conta('receber',"WHERE vencimento<='$dataHoje' AND status='Serasa'");
							   $Totaljuridico = conta('receber',"WHERE vencimento<='$dataHoje' AND status='Juridico'");
							*/
							?>
                            <small class="label label-danger pull-right">
                                <?php //echo $TotalEmAberto;?>
                            </small>
                            
                            <li>
                              <a href="painel.php?execute=suporte/receber/receber-vencidos">
							  <i class="fa fa-circle-o"></i>
							  Vencidos
							  </a>
                            </li>
                            
                            <?php 
							  // $serasa = conta('receber',"WHERE serasa='1'");
							?>
                            <small class="label label-danger pull-right">
                                <?php// echo $serasa;?>
                            </small>
                            
                             <li>
                              <a href="painel.php?execute=suporte/receber/receber-vencidos-serasa">
							  <i class="fa fa-circle-o"></i>
							  Serasa
							  </a>
                            </li>
                            
                             <?php 
							   //$juridico = conta('receber',"WHERE juridico='1'");
							?>
                            <small class="label label-danger pull-right">
                                <?php //echo $juridico;?>
                            </small>
                            
                             <li>
                              <a href="painel.php?execute=suporte/receber/receber-vencidos-juridico">
							  <i class="fa fa-circle-o"></i>
							  Juridico
							  </a>
                            </li>
							
							
							 <?php 
							  // $protesto = conta('receber',"WHERE protesto='1'");
							?>
                            <small class="label label-danger pull-right">
                                <?php //echo $protesto;?>
                            </small>
                            
                             <li>
                              <a href="painel.php?execute=suporte/receber/receber-vencidos-protesto">
							  <i class="fa fa-circle-o"></i>
							  Protesto
							  </a>
                            </li>
                            
                            
                             <?php 
							   //$recuperacao = conta('receber',"WHERE recuperacao_credito='1'");
							?>
                            <small class="label  pull-right bg-orange">
                                <?php //echo $recuperacao;?>
                            </small>
                            
                             <li>
                              <a href="painel.php?execute=suporte/receber/receber-recuperacao-credito">
							  <i class="fa fa-circle-o"></i>
							  Recupera&ccedil;&atilde;o
							  </a>
                            </li>
                            
                            <?php 
							  // $dispensa = conta('receber',"WHERE dispensa='1'");
							?>
                            <small class="label  pull-right bg-orange">
                                <?php //echo $dispensa;?>
                            </small>
                            
                             <li>
                              <a href="painel.php?execute=suporte/receber/receber-dispensa-credito">
							  <i class="fa fa-circle-o"></i>
							  Dispensa
							  </a>
                            </li>
							
							  <?php 
							  // $desconto = conta('receber',"WHERE desconto_autorizar='1' AND desconto_autorizacao='1'");
							?>
                            <small class="label  pull-right bg-orange">
                                <?php //echo $desconto;?>
                            </small>
                            
                             <li>
                              <a href="painel.php?execute=suporte/receber/receber-desconto-autorizados">
							  <i class="fa fa-circle-o"></i>
							  Desconto
							  </a>
                            </li>
							
							  <li>
                              <a href="painel.php?execute=suporte/receber/contrato-suspenso-pagamento">
							  <i class="fa fa-circle-o"></i>
							  Suspensos
							  </a>
                            </li>


                            <li><a href="painel.php?execute=suporte/receber/receber-pagos"><i class="fa fa-circle-o"></i>Quitados</a>
                            </li>
							
							 <?php// $inadimplentes = $TotalEmAberto + $Totaljuridico + $TotalProtesto + $TotalSerasa;?>
                 					
                            <small class="label label-danger pull-right">
                                <?php //echo $inadimplentes ;?>
                            </small>
                            
                            <li>
                              <a href="painel.php?execute=suporte/receber/inadimplentes">
							  <i class="fa fa-circle-o"></i>
							  Inadimplentes
							  </a>
                            </li>
                            
                          

                        </ul>
                    </li>
         
        </li>
        
         <?php }?>
		
		<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='4'  // 4 - Compras/Financeiro
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			){  
	?>	

                <li class="treeview">
                    <a href="#">
						<i class="fa fa-calculator"></i>
					   </i> <span>Movimenta&ccedil;&atilde;o</span> <i class="fa fa-angle-left pull-right"></i>
					</a>

                    <ul class="treeview-menu">

                        <li>
                            <a href="painel.php?execute=suporte/movimentacao/movimentacao"><i class="fa fa-circle-o"></i>Saldo
							</a>
                        </li>
                        <li>
                            <a href="painel.php?execute=suporte/movimentacao/transferencias"><i class="fa fa-circle-o"></i> Transfêrencias
							</a>
                            <li>
                                <a href="painel.php?execute=suporte/pagar/bancos"><i class="fa fa-circle-o"></i> Bancos
							</a>
                            </li>
                            <li>
                              <a href="painel.php?execute=suporte/pagar/formapag"><i class="fa fa-circle-o"></i> Forma de Pagamento</a>
                            </li>
                            <li><a href="painel.php?execute=suporte/pagar/pagar-auditoria"><i class="fa fa-circle-o"></i> Despesas Auditoria</a>
                            </li>
                            <li><a href="painel.php?execute=suporte/receber/receber-auditoria"><i class="fa fa-circle-o"></i> Receita Auditoria</a>
                            </li>
                    </ul>
                    
                  </li>
                  
               <?php }?>
		
	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
	4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
	9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='4'  // 4 - Compras/Financeiro
			  || $usuario['nivel']=='5'  // 5 - Gerencial 
			){  
	?>	

	<!--CONTA A PAGAR-->
                    <li class="treeview">
                       
                        <a href="#">
							<i class="fa fa-credit-card"></i>
							<span>Contas a Pagar</span><i class="fa fa-angle-left pull-right"></i>
							 <?php 
							//$despesas = conta('pagar',"WHERE vencimento<='$hoje' AND status='Em Aberto'");
							   if( $despesas>0){
								  echo '<small class="label pull-right bg-red">'. $despesas .'</small>';  
							   }
							?>
						  </a>
                    


                        <ul class="treeview-menu">
							
                            <?php 
							   if( $despesas>0){
								  echo '<small class="label pull-right bg-red">'. $despesas .'</small>';  
							   }
							?>

                            <li>
                                <a href="painel.php?execute=suporte/pagar/despesas">
								  <i class="fa fa-circle-o"></i>
								  Despesas
							  </a>
                            </li>
							
							
							 <?php 
				
							  // $lancamentos = conta('pagar',"WHERE emissao='$hoje'  AND status='Em Aberto'");
							   if( $lancamentos>0){
								  echo '<small class="label pull-right bg-blue">'. $lancamentos .'</small>';  
							   }
							?>
				
							 <li>
                                <a href="painel.php?execute=suporte/pagar/lancamentos">
								  <i class="fa fa-circle-o"></i>
								  Lançamentos
							  </a>
                            </li>
							
							 <?php 
							 
							   // $programacao = conta('pagar',"WHERE programacao='$hoje' AND status='Em Aberto'");
							   if( $programacao>0){
								  echo '<small class="label pull-right bg-green">'. $programacao .'</small>';  
							   }
							?>
							
							 <li>
                                <a href="painel.php?execute=suporte/pagar/programacoes">
								  <i class="fa fa-circle-o"></i>
								  Programação
							  </a>
                            </li>
							
                            <li>
                                <a href="painel.php?execute=suporte/pagar/pagar-pagos">
								  <i class="fa fa-circle-o"></i>
								  Quitadas
								  </a>
                            </li>
							
                            <li>
                                <a href="painel.php?execute=suporte/pagar/pagar-contas">
								  <i class="fa fa-circle-o"></i>
								  Centro de Custo
								  </a>
                            

                            </li>
							
                            <li>
                                <a href="painel.php?execute=suporte/pagar/pagar-grupos">
								  <i class="fa fa-circle-o"></i>
								  Categoria
							  	</a>
                              </li>
							
							
							 <li>
                                <a href="painel.php?execute=suporte/pagar/empresas">
								  <i class="fa fa-circle-o"></i>
								  Empresas
							  	</a>
                              </li>
							
							
                        </ul>
                    </li>

 		<?php }?>

	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
	4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
	9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='2'  // 2 - Atendimento ao Cliente
			 || $usuario['nivel']=='4'  // 9 - 4 - Compras/Financeiro 
			 || $usuario['nivel']=='9'  // 9 - Manutenção / Almoxarifado
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			){  
	?>	

<!--ESTOQUE-->
            <li class="treeview">
            
				 <a href="#">
					<i class="fa fa-stack-overflow"></i>
				   </i> <span>Estoque</span> <i class="fa fa-angle-left pull-right"></i>
				    <?php 
						//$etiquetaTotal= conta('estoque_etiqueta_retirada',"WHERE id AND status='Em Aberto'");
						if($etiquetaTotal<>'0'){
							echo '<small class="label pull-right bg-green">'. $etiquetaTotal .'</small>';  
						}
					?>
				 </a>
				 
		
                  <ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Equipamentos<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						 <a href="painel.php?execute=suporte/estoque/equipamentos">
						 <i class="fa fa-circle-o"></i>
						 Estoque
						 </a>
						 </li>

						 <li>
						  <a href="painel.php?execute=suporte/estoque/equipamento-retirada-emaberto">
						  <i class="fa fa-circle-o"></i>
						  Retirada em Aberto
						  </a>
						 </li>
						   
						   
						  <li>
						  <a href="painel.php?execute=suporte/estoque/equipamento-manutencoes">
						  <i class="fa fa-circle-o"></i>
						  Manutenção
						  </a>
						 </li>
						   
						   
						 <li>
						  <a href="painel.php?execute=suporte/estoque/equipamento-retirada">
						  <i class="fa fa-circle-o"></i>
						  Retirada
						  </a>
						 </li>
						   
						  <li>
						  <a href="painel.php?execute=suporte/estoque/equipamento-reposicao">
						  <i class="fa fa-circle-o"></i>
						  Reposi&ccedil;&atilde;o
						  </a>
						 </li>
						   
						    <li>
						  <a href="painel.php?execute=suporte/estoque/contrato-equipamento">
						  <i class="fa fa-circle-o"></i>
						 Contrato 
						  </a>
						 </li>
						
						</ul>
                	</li>
              </ul>
              
              <ul class="treeview-menu">
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Etiquetas<i class="fa fa-angle-left pull-right"></i></a>
                   <ul class="treeview-menu">
                      <li>
                      <a href="painel.php?execute=suporte/estoque/etiquetas">
                      <i class="fa fa-circle-o">
                      </i>
                      Estoque
                      </a>
                      </li>
					   
					   
					   <li>
						  <a href="painel.php?execute=suporte/estoque/etiqueta-retirada-emaberto">
						  <i class="fa fa-circle-o"></i>
						  Retirada em Aberto
							    <?php 
						//$etiquetaTotal= conta('estoque_etiqueta_retirada',"WHERE id AND status='Em Aberto'");
						if($etiquetaTotal<>'0'){
							echo '<small class="label pull-right bg-green">'. $etiquetaTotal .'</small>';  
						}
					?>
						  </a>
					 </li>
                      
                      <li>
                      <a href="painel.php?execute=suporte/estoque/etiqueta-retirada">
                      <i class="fa fa-circle-o"></i>
						  Retirada
                    
                      </a>
                     </li>
                      <li>
                      <a href="painel.php?execute=suporte/estoque/etiqueta-reposicao">
                      <i class="fa fa-circle-o"></i>Reposi&ccedil;&atilde;o
                      </a>
                     </li>
                     
                      
						  <li>
						  <a href="painel.php?execute=suporte/estoque/contrato-etiquetas">
						  <i class="fa fa-circle-o"></i>
						  Contrato Etiquetas
						  </a>
						 </li>
					   
					   	  <li>
						  <a href="painel.php?execute=suporte/estoque/contrato-etiquetas-negativas">
						  <i class="fa fa-circle-o"></i>
						  Saldo Negativo
						  </a>
						 </li>
						 
                     
             		</ul>
                </li>
        	 </ul>

				 <ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Material<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						 <a href="painel.php?execute=suporte/estoque/materiais">
						 <i class="fa fa-circle-o"></i>
						 Estoque
						 </a>
						 </li>

						 <li>
						  <a href="painel.php?execute=suporte/estoque/material-retirada">
						  <i class="fa fa-circle-o"></i>
						  Retirada
						  </a>
						 </li>
						   
						  <li>
						  <a href="painel.php?execute=suporte/estoque/material-reposicao">
						  <i class="fa fa-circle-o"></i>
						  Reposição
						  </a>
						 </li>
					  
						 <li>
						  <a href="painel.php?execute=suporte/estoque/material-tipos">
						  <i class="fa fa-circle-o"></i>
						  Tipos
						  </a>
						 </li>
						
						</ul>
                	</li>
           		 </ul>

 				<ul class="treeview-menu">
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Fabricação<i class="fa fa-angle-left pull-right"></i></a>
                   <ul class="treeview-menu">
                      <li>
                      <a href="painel.php?execute=suporte/estoque/equipamento-fabricacao">
                      <i class="fa fa-circle-o">
                      </i>
                      Conteiner 
                      </a>
                      </li>
					   
					 
             		</ul>
                </li>
        	 </ul>

				 
         </li>
               
  	<?php }?>

<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
9 - Manutenção / Almoxarifado  10 Ambiental e Patrimonial-->

<!--COMPRAS-->

	<?php if($usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			 || $usuario['nivel']=='4'  // 4 - Compras/Financeiro 
			 || $usuario['nivel']=='5'   // 5 - Gerencial 
			 
			 || $usuario['nivel']=='7'  // 7 - DP/RH 
			 || $usuario['nivel']=='9'  // 9 - Manutenção / Almoxarifado
			 || $usuario['nivel']=='8' //8 - Vendas 
			 || $usuario['nivel']=='2' //2 - Atendimento ao Cliente
			 || $usuario['nivel']=='1' //2 - Operacional
			 || $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
		   ){  
	?>	
                    <li class="treeview">
                       
                        <a href="#">
							<i class="fa fa-credit-card"></i>
							<span>Compras</span><i class="fa fa-angle-left pull-right"></i>
							 <?php 
					
							  // $solicitando = conta('estoque_compras',"WHERE id AND status='solicitando'");
							   if( $solicitando>0){
								  echo '<small class="label pull-right bg-red">'. $solicitando .'</small>';  
							   }
							?>
						  </a>
						
						
						 <ul class="treeview-menu">
                           
                            <li>
                                <a href="painel.php?execute=suporte/compras/aba-compras">
								  <i class="fa fa-circle-o"></i>
								  Compras
							  </a>
                      
                            </li>
                      
                            <li>
                                <a href="painel.php?execute=suporte/compras/requisicoes">
								  <i class="fa fa-circle-o"></i>
								  Requisições
							  </a>
                      
                            </li>
							 
							 
							  <li>
                                <a href="painel.php?execute=suporte/compras/fornecedores">
								  <i class="fa fa-circle-o"></i>
								  Fornecedores
							  </a>
                      
                            </li>
                        </ul>
						
						
                    </li>

 		<?php }?>


<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
9 - Manutenção / Almoxarifado  10 Ambiental e Patrimonial-->

<!--COMPRAS-->

	<?php if($usuario['nivel']=='4'  // 4 - Compras/Financeiro 
			 || $usuario['nivel']=='5'  // 5 - Gerencial 
			 || $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
		   ){  
	?>	
                    <li class="treeview">
                       
                        <a href="#">
							<i class="fa fa-credit-card"></i>
							<span>Patrimônio</span><i class="fa fa-angle-left pull-right"></i>
						 
						  </a>
						
						
						 <ul class="treeview-menu">
                           
                            <li>
                                <a href="painel.php?execute=suporte/estoque/patrimonio">
								  <i class="fa fa-circle-o"></i>
								  Patrimônio
							  </a>
                      
                            </li>
                       
                        </ul>
						
						
                    </li>

 		<?php }?>

	<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
	4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
	9 - Manutenção / Almoxarifado -->


	<?php if($usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			 || $usuario['nivel']=='4'  // 4 - Compras/Financeiro 
			 || $usuario['nivel']=='5'   // 5 - Gerencial 
			 || $usuario['nivel']=='7'  // 7 - DP/RH 
			 || $usuario['nivel']=='9'  // 9 - Manutenção / Almoxarifado
			 || $usuario['nivel']=='2' //2 - Atendimento ao Cliente
			 || $usuario['nivel']=='1' //1 - Operacional 
			 || $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
			 || $usuario['nivel']=='11' //11 - Juridico
			){  
	?>	


<!--FUNCIONARIO-->
		<li class="treeview">
                       
                        <a href="#">
							
							<i class="fa fa-user-plus"></i>
							
							<span>Funcionário</span><i class="fa fa-angle-left pull-right"></i>
							 <?php 
							 //  $funcioanario = conta('funcionario',"WHERE id");
								echo '<small class="label pull-right bg-green">'. $funcioanario .'</small>';  
							?>
						  </a>
      
                        <ul class="treeview-menu">
                            <?php 
								echo '<small class="label pull-right bg-green">'. $funcioanario .'</small>';  
							?>

                            <li>
                                <a href="painel.php?execute=suporte/funcionario/funcionarios">
								  <i class="fa fa-circle-o"></i>
								  Funcionários
							  </a>
                            </li>
							
					 
							
							  <li>
                                <a href="painel.php?execute=suporte/funcionario/funcoes">
								  <i class="fa fa-circle-o"></i>
								  Funções
							  </a>
                            </li>
							
					 
                       	</li>
           		 </ul>
			
				<ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Divergências<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						  <a href="painel.php?execute=suporte/funcionario/divergencias">
								  <i class="fa fa-circle-o"></i>
						 Divergências
						 </a>
						 </li>
						   <li>
                                <a href="painel.php?execute=suporte/funcionario/divergencia-motivo">
								  <i class="fa fa-circle-o"></i>
								  Motivo
							  </a>
                       	</li>
						
						</ul>
                	</li>
             	 </ul>

				<ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Suspensões<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						  <a href="painel.php?execute=suporte/funcionario/suspensoes">
								  <i class="fa fa-circle-o"></i>
						 Suspensões
						 </a>
						 </li>
						   <li>
                                <a href="painel.php?execute=suporte/funcionario/suspensao-motivo">
								  <i class="fa fa-circle-o"></i>
								  Motivo
							  </a>
                       	</li>
						
						</ul>
                	</li>
             	 </ul>

				<ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Advertências<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						  <a href="painel.php?execute=suporte/funcionario/advertencias">
							<i class="fa fa-circle-o"></i>
						 	Advertências
						 </a>
						 </li>
						   <li>
                                <a href="painel.php?execute=suporte/funcionario/advertencia-motivo">
								  <i class="fa fa-circle-o"></i>
								  Motivo
							  </a>
                       	</li>
						
						</ul>
                	</li>
             	 </ul>

				<ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Acidentes<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						  <a href="painel.php?execute=suporte/funcionario/acidentes">
							<i class="fa fa-circle-o"></i>
						 	Acidentes
						 </a>
						 </li>
						 
						</ul>
                	</li>
             	 </ul>

				<ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Multas<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						  <a href="painel.php?execute=suporte/funcionario/multas">
							<i class="fa fa-circle-o"></i>
						 	Multas
						 </a>
						 </li>
						   <li>
                                <a href="painel.php?execute=suporte/funcionario/multa-motivo">
								  <i class="fa fa-circle-o"></i>
								  Infrações
							  </a>
                       	</li>
						
						</ul>
                	</li>
             	 </ul>


				<ul class="treeview-menu">
					  <li>
					  <a href="#"><i class="fa fa-circle-o">
					  </i> Diárias<i class="fa fa-angle-left pull-right"></i>
					  </a>
                  
					   <ul class="treeview-menu">
						 <li>
						  <a href="painel.php?execute=suporte/funcionario/diarias">
							<i class="fa fa-circle-o"></i>
						 	Diárias
						 </a>
						 </li>
						   <li>
                                <a href="painel.php?execute=suporte/funcionario/diaria-motivo">
								  <i class="fa fa-circle-o"></i>
								  Motivo
							  </a>
                       	</li>
						
						</ul>
                	</li>
             	 </ul>
 
               </li>

 		<?php }?>

		<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
	4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
	9 - Manutenção / Almoxarifado -->


	<?php if($usuario['nivel']=='3'  // 3 - Faturamento / Cobrança
			 || $usuario['nivel']=='4'  // 4 - Compras/Financeiro 
			 || $usuario['nivel']=='5'   // 5 - Gerencial 
			 || $usuario['nivel']=='7'  // 7 - DP/RH 
			 || $usuario['nivel']=='9'  // 9 - Manutenção / Almoxarifado
			 || $usuario['nivel']=='2' //2 - Atendimento ao Cliente
			 || $usuario['nivel']=='1' //1 - Operacional 
			 || $usuario['nivel']=='10' //10 - Ambiental e Patrimonial
			  || $usuario['nivel']=='11' //11 - Juridico
			){  
	?>	


<!--FUNCIONARIO-->
		<li class="treeview">
                       
                        <a href="#">
							
							<i class="fa fa-user-plus"></i>
							
							<span>Qualidade</span><i class="fa fa-angle-left pull-right"></i>
							 <?php 
							//   $qualidade = conta('qualidade',"WHERE id AND status='0'");
								echo '<small class="label pull-right bg-green">'. $qualidade .'</small>';  
							?>
						  </a>
      
                        <ul class="treeview-menu">
                            <?php 
								echo '<small class="label pull-right bg-green">'. $qualidade .'</small>';  
							?>

                            <li>
                                <a href="painel.php?execute=suporte/qualidade/qualidade">
								  <i class="fa fa-circle-o"></i>
								  Qualidade
							  </a>
                            </li>
			
					 
                       	</li>
           		 </ul>
		
      </li>

 		<?php }?>
		
		<!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
		4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
		9 - Manutenção / Almoxarifado -->

		<?php if($usuario['nivel']=='5'  // 5 - Gerencial
				 
			){  
		?>	
                    <li class="treeview">
                        <a href="#">
							<i class="fa fa-user-plus"></i>
							 </i> <span>Cadastros</span> <i class="fa fa-angle-left pull-right"></i>
						 </a>
                    

                        <ul class="treeview-menu">
							
					    <li>
                         <a href="painel.php?execute=suporte/cadastro/empresa"><i class="fa fa-circle-o"></i>Empresa</a>
                       </li>
                           
                        <li>
						 <a href="painel.php?execute=suporte/usuario/usuarios"><i class="fa fa-circle-o"></i>Usu&aacute;rios</a>
						 </li>
                           
                      <li>
           				<a href="painel.php?execute=suporte/cadastro/contratotiporesiduos"><i class="fa fa-circle-o">
						</i>Tipo de Res&iacute;duos
						</a>
                      </li>
                      
                       <li>
          				<a href="painel.php?execute=suporte/cadastro/tipocoletas"><i class="fa fa-circle-o">
						</i>Tipo de Coleta
						</a>
                      </li>

                     <li>
         				 <a href="painel.php?execute=suporte/cadastro/contratotipos"><i class="fa fa-circle-o">
						</i>Tipo de contrato</a>
                         </li>

                       <li>
          				<a href="painel.php?execute=suporte/cadastro/atendentes"><i class="fa fa-circle-o">
						</i>Atendentes</a>
                        </li>

                       <li>
                        <a href="painel.php?execute=suporte/cadastro/consultores"><i class="fa fa-circle-o">
						</i>Consultores</a>
                      </li>
							
					 <li>
          				<a href="painel.php?execute=suporte/cadastro/pos-venda"><i class="fa fa-circle-o">
						</i>Pos-Venda</a>
                      </li>


                       <li>
          				<a href="painel.php?execute=suporte/cadastro/indicacoes"><i class="fa fa-circle-o"></i>Indica&ccedil;&otilde;es</a>
                       </li>

                      <li>
         				<a href="painel.php?execute=suporte/cadastro/classificacoes"><i class="fa fa-circle-o"></i>Classifica&ccedil;&otilde;es</a>
                      </li>

                       <li>
                         <a href="painel.php?execute=suporte/cadastro/coletores"><i class="fa fa-circle-o"></i>Coletores</a>
                       </li>
                        
					<?php if($usuario['nivel']=='5'){//    5 - Gerencial ?>	
                      <li>
                         <a href="painel.php?execute=suporte/cadastro/rotas"><i class="fa fa-circle-o"></i>Rotas</a>
                       </li>
						<?php } ?>	       
						<li>
                         <a href="painel.php?execute=suporte/cadastro/cancelamentos"><i class="fa fa-circle-o"></i>Motivos de Cancelamento</a>
                       </li>
							
						<li>
                         <a href="painel.php?execute=suporte/cadastro/motivonaocoletados"><i class="fa fa-circle-o"></i>Motivos N&atilde;o Coletado</a>
                       </li>
							
						<li>
                         <a href="painel.php?execute=suporte/cadastro/motivodivergencias"><i class="fa fa-circle-o"></i>Motivos de Diverg&ecirc;ncia</a>
                       </li>
							
							
						<li>
                         <a href="painel.php?execute=suporte/cadastro/motivorefaturamento"><i class="fa fa-circle-o"></i>Motivos de Refaturamento</a>
                       </li>
							
							
							<li>
                         <a href="painel.php?execute=suporte/cadastro/motivodispensas"><i class="fa fa-circle-o"></i>Motivos de Dispensa</a>
                       </li>

      
                        </ul>
                    </li>
                    

		<?php }?>

        <!-- 1 - Operacional  2 - Atendimento ao Cliente / Comercial  3 - Faturamento / Cobrança
	4 - Compras/Financeiro  5 - Gerencial  6 - Manifesto  7 - DP/RH  8 - Vendas  
	9 - Manutenção / Almoxarifado -->

	<?php if($usuario['nivel']=='5'  // 5 - Gerencial
			  || $usuario['nivel']=='3'  //  3 - Faturamento
			){  
	?>	
        <!--inteRacoes-->                 
				<li class="treeview">
				 <a href="#">
							<i class="fa fa-hand-pointer-o"></i>
							<span>Intera&ccedil;&otilde;es</span><i class="fa fa-angle-left pull-right"></i>
							 <?php 
							//   $hoje = date("Y-m-d");
							//   $interacao = conta('interacao',"WHERE data>='$hoje'");
							   if($interacao>0){
								  echo '<small class="label pull-right bg-blue">'. $interacao .'</small>';  
							   }
							?>
					  </a>

					<ul class="treeview-menu">
					
						<li><a href="painel.php?execute=suporte/interacao/interacoes"><i class="fa fa-circle-o"></i>Intera&ccedil;&otilde;es</a>
						</li>
						
						<li><a href="painel.php?execute=suporte/interacao/logins"><i class="fa fa-circle-o"></i>Logins</a>
						</li>
						
						<li><a href="painel.php?execute=suporte/interacao/manual"><i class="fa fa-circle-o"></i>Manual</a>
						</li>
						
					</ul>
				</li>
	<?php }?>

                <li>
                     <a href="logoff.php">
				         <i class="fa fa-sign-out"></i></i> <span>Sair</span>
			         </a>
                    
               </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>