<head>
    <meta charset="iso-8859-1">
</head>

	 <div class="row">
     
     		<?php
			 $mes = date('m/Y');
			 $mesano = explode('/',$mes);					   
			 $receita = soma('receber',"WHERE status='Em Aberto' AND Month(vencimento)='$mesano[0]' AND 
									Year(vencimento)='$mesano[1]'",'valor');
			 $receitaquitada = soma('receber',"WHERE status<>'Em Aberto' AND Month(pagamento)='$mesano[0]' AND 
									Year(pagamento)='$mesano[1]'",'valor');
			 $despesas= soma('pagar',"WHERE status='Em Aberto' AND Month(vencimento)='$mesano[0]' AND 
									Year(vencimento)='$mesano[1]'",'valor');
			 $despesaquitada = soma('pagar',"WHERE status<>'Em Aberto' AND Month(pagamento)='$mesano[0]' AND 
									Year(pagamento)='$mesano[1]'",'valor');
			?> 

            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo (int)$receita;?></h3>
                  <p>Receita Prevista</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="../../inc/painel.php?execute=suporte/receber/receber" class="small-box-footer">Relat&oacute;rio <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo (int)$receitaquitada;?></h3>
                  <p>Receitas Quitadas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i> 
                </div>
                <a href="../../inc/painel.php?execute=suporte/receber/receber-pagos" class="small-box-footer">Relat&oacute;rio  <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo (int)$despesas;?></h3>
                  <p>Despesas Prevista</p>
                </div>
                <div class="icon">
                  <i class="ion ion-calculator"></i>
                </div>
                <a href="../../inc/painel.php?execute=suporte/pagar/despesas" class="small-box-footer">Relat&oacute;rio <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo (int)$despesaquitada;?></h3>
                  <p>Despesas Quitadas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-card"></i>
                </div>
                <a href="../../inc/painel.php?execute=suporte/pagar/pagar-pagos" class="small-box-footer">Relat&oacute;rio <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
   </div><!-- /.row --> 
    
