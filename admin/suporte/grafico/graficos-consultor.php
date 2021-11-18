<head>
    <meta charset="iso-8859-1">
</head>
    
       
 <div class="content-wrapper">
 
    <section class="content-header">
          <h1>Consultores <small>
          </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel</li>
          </ol>
        </section>
        
        
  <section class="content">
  
  <div class="row">
  
   		<?php 
  		$leitura = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
		if($leitura){
			foreach($leitura as $mostra):
				$consultorId=$mostra['id'];
				$foto='../uploads/consultores/'.$mostra['fotoperfil'];
				$nome = substr($mostra['nome'],0,9) ;
			
				if(empty($foto)){
					$foto=URL.'/site/images/autor.png';
				}
			
			$ativos = soma('contrato',"WHERE tipo='2' AND status='5' AND consultor='$consultorId'",'valor_mensal');
			$suspensos = soma('contrato',"WHERE tipo='2' AND status='6' AND consultor='$consultorId'",'valor_mensal');
			$protestados = soma('contrato',"WHERE tipo='2' AND status='7' AND consultor='$consultorId'",'valor_mensal');
			
			$vencidos=0;
			$data1 = date("Y-m-d", strtotime("-3 day"));
			$data2 = date("Y-m-d", strtotime("-180 day"));
			$leitura = read('receber',"WHERE vencimento>='$data2' AND vencimento<='$data1' 
															AND status='Em Aberto'");
			
			if($leitura){
				foreach($leitura as $receber):
					$contratoId = $receber['id_contrato'];
					$contrato = mostra('contrato',"WHERE id ='$contratoId'");
					if($contrato){
						if ($contrato['consultor']==$consultorId){
							$vencidos=$vencidos+$receber['valor'];;
						}
					}
				endforeach;
			}
			
				
            ?> 
            
				<div class="col-md-3">
				  <div class="box box-widget widget-user-2">
					<div class="widget-user-header bg-aqua">

					  <div class="widget-user-image">
						<img class="img-circle" src="<?php echo $foto ?>" alt="Foto">
					  </div>

					  <h3 class="widget-user-username"><?php echo $nome; ?></h3>
					  <h5 class="widget-user-desc">Consultor</h5>
					</div>
					<div class="box-footer no-padding">
				  
					  <ul class="nav nav-stacked">
					  
						<li><a href="#">Ativos<span class="pull-right badge bg-blue">
						R$ <?php echo converteValor($ativos) ?>
						</span></a></li>
						
						<li><a href="#">Suspensos<span class="pull-right badge bg-yellow">
						R$ <?php echo converteValor($suspensos) ?>
						</span></a></li>
						
						<li><a href="#">Protestados<span class="pull-right badge bg-green">
						R$ <?php echo converteValor($protestados) ?>
						</span></a></li>
						
						<li><a href="#">Atrasados<span class="pull-right badge bg-red">
						R$ <?php echo converteValor($vencidos) ?>
						</span></a></li>

					  </ul>
					</div>
				  </div><!-- /.widget-user -->
				</div><!-- /.col -->

     <?php 
		endforeach;
		}
	  ?> 
</div><!-- /.row -->
</div><!-- /.content-wrapper -->

