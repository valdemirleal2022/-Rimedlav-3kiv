<?php 

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}
	
	$data1 = date("Y-m-d");
	$data2 = date("Y-m-d");

	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$consultorId = $_POST['consultor'];
		$indicacaoId = $_POST['indicacao'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		$_SESSION['indicacao']=$indicacaoId;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-acompanhamento-visitas-");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];
		
		$consultorId = $_POST['consultor'];
		$indicacaoId = $_POST['indicacao'];
		
		$_SESSION['inicio']=$data1;
		$_SESSION['fim']=$data2;
		$_SESSION['consultor']=$consultorId;
		$_SESSION['indicacao']=$indicacaoId;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-orcamentos-excel.php' );
	}


   	$data1 = date("Y-m-d", strtotime("-30 day"));
	$data2 = date("Y-m-d");
   
	if(isset($_POST['pesquisar'])){
		
		$data1 = $_POST[ 'inicio' ];
		$data2 = $_POST[ 'fim' ];

		if(!empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId' ORDER BY interacao DESC");
		}
		
		if(!empty($indicacaoId)){
			$total = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId'");
			$leitura = read('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId' ORDER BY interacao DESC");
		}
		
		if(!empty($indicacaoId) AND !empty($consultorId)){
			$total = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId' AND consultor='$consultorId'");
			$leitura = read('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND indicacao='$indicacaoId' AND consultor='$consultorId' ORDER BY interacao DESC");
		}
	}

$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">

  <section class="content-header">
       <h1>Visitas</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#">Visitas</a></li>
         </ol>
 </section>
 
<section class="content">

  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
   
        <div class="box-body table-responsive">

           	<div class="box-header">       
                  <div class="col-xs-10 col-md-9 pull-right">
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                         <div class="form-group pull-left">
                               <input type="date" name="inicio" value="<?php echo date('Y-m-d',strtotime($data1)) ?>" class="form-control input-sm" >
                            </div>   <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <input type="date" name="fim" value="<?php echo date('Y-m-d',strtotime($data2)) ?>" class="form-control input-sm" >
                            </div>  <!-- /.input-group -->
                        
                           <div class="form-group pull-left">
								<select name="consultor" class="form-control input-sm">
									<option value="">Selecione o Consultor</option>
									<?php 
										$readContrato = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
										if(!$readContrato){
											echo '<option value="">Nao registro no momento</option>';	
											}else{
											foreach($readContrato as $mae):
												if($consultorId == $mae['id']){
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
						<select name="indicacao" class="form-control input-sm">
							<option value="">Selecione o Indicação</option>
							<?php 
							$readContrato = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
							if(!$readContrato){
								echo '<option value="">Nao registro no momento</option>';	
							}else{
								foreach($readContrato as $mae):
									if($indicacaoId == $mae['id']){
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
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar" title="Pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o" title="Relatório PDF"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o" title="Relaório Excel"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                 </div><!-- /col-xs-10 col-md-7 pull-right-->
                  
        </div> <!-- /.box-header -->
        
  
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
			
				
			$visitas = conta('cadastro_visita',"WHERE id AND status='0' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$orcamento = conta('cadastro_visita',"WHERE id AND status='2' AND data>='$data1' AND data<='$data2' AND consultor='$consultorId'");
			$propostas = conta('cadastro_visita',"WHERE id AND status='3' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
			$aprovados = conta('cadastro_visita',"WHERE id AND status='4' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
			$cancelados = conta('cadastro_visita',"WHERE id AND status='17' AND data>='$data1' AND data<='$data2'  AND consultor='$consultorId'");
				
            ?> 
            
				<div class="col-md-3">
				  <div class="box box-widget widget-user-2">
					<div class="widget-user-header bg-aqua">

					  <div class="widget-user-image">
						<img class="img-circle" src="<?php echo $foto ?>" alt="Foto">
					  </div>

					  <h5 class="widget-user-desc"><?php echo $nome; ?></h5>
					  <h6 class="widget-user-desc">Consultor</h6>
					</div><!-- /.widget-user-header bg-aqua-->
					
					<div class="box-footer no-padding">
				  
					  <ul class="nav nav-stacked">
					  
						<li><a href="#">Visitas<span class="pull-right badge bg-blue">
						 <?php echo $visitas ?>
						</span></a></li>
						
						<li><a href="#">Orçamentos<span class="pull-right badge bg-yellow">
						<?php echo $orcamento ?>
						</span></a></li>
						
						<li><a href="#">Propostas<span class="pull-right badge bg-green">
						<?php echo  $propostas ?>
						</span></a></li>
						
						<li><a href="#">Aprovados<span class="pull-right badge bg-blue">
						 <?php echo $aprovados ?>
						</span></a></li>
						  
						<li><a href="#">Cancelados<span class="pull-right badge bg-red">
						 <?php echo $cancelados ?>
						</span></a></li>
					  	
					  	 <?php $total=$visitas+$orcamento+$propostas+$aprovados+$cancelados?>
					  	 
					   <li><a href="#">Total<span class="pull-right badge bg-green">
						 <?php echo $total ?>
						</span></a></li>

					  </ul>
					  
					</div><!-- /.box-footer no-padding-->
					
				  </div><!-- /.box box-widget widget-user-2 -->
				</div><!-- /.col-md-3 -->

     <?php 
		endforeach;
		}
	  ?> 
	  
</div><!-- /.box-body table-responsive -->
    
        <div class="box-footer">
            <?php echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
			?> 
       </div><!-- /.box-footer-->
       
    </div><!-- /.box box-default -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 
 </section><!-- /.content -->
</div><!-- /.content-wrapper -->