<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		
		$dataRota=$_POST['data1'];
		$rotaId = $_POST['rota'];
		$_SESSION[ 'dataRota' ]=$dataRota ;
		$_SESSION[ 'rotaRoteiro' ]=$rotaId ;
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-contrato-rota-pdf");';
		echo '</script>';
		
	}

	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		
		$dataRota=$_POST['data1'];
		$rotaId = $_POST['rota'];
		$_SESSION[ 'dataRota' ]=$dataRota ;
		$_SESSION[ 'rotaRoteiro' ]=$rotaId ;
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-contrato-rota-excel.php' );
	}

	if(isset($_POST['pesquisar'])){
		
		$dataRota=$_POST['data1'];
		$rotaId = $_POST['rota'];
		$_SESSION[ 'dataRota' ]=$dataRota ;
		$_SESSION[ 'rotaRoteiro' ]=$rotaId ;
		
	}

	$trocarRota = 0;
	
	if(isset($_POST['trocar_rota'])){
		
		$dataRota=$_POST['data1'];
		$rotaId = $_POST['rota'];
		$rota2 = $_POST['rota2'];
		
		$trocarRota = 1;
		
		$_SESSION[ 'dataRota' ]=$dataRota ;
		$_SESSION[ 'rotaRoteiro' ]=$rotaId ;
		
	}

	if (!isset( $_SESSION[ 'dataRota' ] ) ) {
		$dataRota = date( "Y/m/d" );
	} else {
		$dataRota = $_SESSION[ 'dataRota' ];
	}

	if (isset( $_SESSION[ 'rotaRoteiro' ] ) ) {
		$rotaId = $_SESSION[ 'rotaRoteiro' ];
	}

	$dia_semana = diaSemana($dataRota);
	$numero_semana = numeroSemana($dataRota);
	
	//DOMINGO
	if ( $numero_semana == 0 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId' OR domingo_rota2='$rotaId' 
										ORDER BY domingo_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId' OR domingo_rota2='$rotaId' ");
	}

	if ( $numero_semana == 1 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId' OR segunda_rota2='$rotaId' 
										ORDER BY segunda_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId' OR segunda_rota2='$rotaId'");
	}

	// TERCA
	if ( $numero_semana == 2 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND terca='1' 
										AND terca_rota1='$rotaId' OR terca_rota2='$rotaId' 
											ORDER BY terca_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND terca='1' 
											AND terca_rota1='$rotaId' OR terca_rota2='$rotaId'");
	}

	//QUARTA
	if ( $numero_semana == 3 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND quarta='1' 
										AND quarta_rota1='$rotaId' OR quarta_rota2='$rotaId' 
										ORDER BY quarta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quarta='1' 
										AND  quarta_rota1='$rotaId' OR quarta_rota2='$rotaId'");
	}
	
	//QUINTA
	if ( $numero_semana == 4 ) {

		$leitura = read( 'contrato', "WHERE id AND status='5' AND quinta='1' 
										AND quinta_rota1='$rotaId' OR quinta_rota2='$rotaId' 
										ORDER BY quinta_hora2, quinta_hora1 ASC");
		
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quinta='1' 
										AND  quinta_rota1='$rotaId' OR quinta_rota2='$rotaId'");
	}
	
	//SEXTA
	if ( $numero_semana == 5 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sexta='1' 
										AND sexta_rota1='$rotaId' OR sexta_rota2='$rotaId'
										ORDER BY sexta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sexta='1' 
										AND  sexta_rota1='$rotaId' OR sexta_rota2='$rotaId'");
	}
	
	//SABADO
	if ( $numero_semana == 6 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sabado='1' 
										AND sabado_rota1='$rotaId' OR sabado_rota2='$rotaId' 
										ORDER BY sabado_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sabado='1' 
										AND  sabado_rota1='$rotaId' OR sabado_rota2='$rotaId' ");
	}


$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];


?>

<div class="content-wrapper">
	
    <section class="content-header">
		
        <h1>Contrato por Rota</h1>
		
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i>Contrato</a>
            </li>
            <li>Contrato por Rota</a>
            </li>
        </ol>

    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
					
					 <div class="box-header">
           
                         <div class="col-xs-6 col-md-8 pull-left">
        
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
                                
                                 <div class="form-group pull-left">         
										<input type="text" name="dia" value="<?php echo $dia_semana; ?>" class="form-control input-sm" disabled>
								</div> <!-- /.input-group -->

                                 <div class="form-group pull-left">
										<input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataRota)) ?>" class="form-control input-sm" >
								</div><!-- /.input-group -->
                                
							   <div class="form-group pull-left">
									  <select name="rota"  class="form-control input-sm" >
										<option value="">Selecione tipo de coleta</option>
										<?php 
										$rotaRead = read('contrato_rota',"WHERE id ORDER BY nome ASC");
											if(!$rotaRead){
											echo '<option value="">Nao temos tipo de coleta no momento</option>';	
										}else{
											foreach($rotaRead as $mae):
												if($rotaId == $mae['id']){
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
									  <select name="rota2"  class="form-control input-sm" >
										<option value="">Selecione tipo de coleta</option>
										<?php 
										$rotaRead = read('contrato_rota',"WHERE id ORDER BY nome ASC");
											if(!$rotaRead){
											echo '<option value="">Nao temos tipo de coleta no momento</option>';	
										}else{
											foreach($rotaRead as $mae):
												if($rota2 == $mae['id']){
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
								<button class="btn btn-sm btn-default pull-right" type="submit" name="trocar_rota" title="Pesquisar">Trocar Rota</button>
							 </div><!-- /.input-group -->
 
                              </form>
                         </div>  <!-- /col-xs-6 col-md-5 pull-right-->
                    </div>   <!-- /box-header-->

                    <div class="box-header">
           
                         <div class="col-xs-6 col-md-8 pull-right">
        
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
                                
                                 <div class="form-group pull-left">         
										<input type="text" name="dia" value="<?php echo $dia_semana; ?>" class="form-control input-sm" disabled>
								</div> <!-- /.input-group -->

                                 <div class="form-group pull-left">
										<input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataRota)) ?>" class="form-control input-sm" >
								</div><!-- /.input-group -->
                                
							   <div class="form-group pull-left">
									  <select name="rota"  class="form-control input-sm" >
										<option value="">Selecione tipo de coleta</option>
										<?php 
										$rotaRead = read('contrato_rota',"WHERE id ORDER BY nome ASC");
											if(!$rotaRead){
											echo '<option value="">Nao temos tipo de coleta no momento</option>';	
										}else{
											foreach($rotaRead as $mae):
												if($rotaId == $mae['id']){
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
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf" title="Relatório PDF"><i class="fa fa-file-pdf-o"></i></button>
								</div>  <!-- /.input-group -->

								<div class="form-group pull-left">
									<button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel" title="Relatório Excel"><i class="fa fa-file-excel-o"></i></button>
								</div>   <!-- /.input-group -->

                              </form>
                         </div>  <!-- /col-xs-6 col-md-5 pull-right-->
                    </div>   <!-- /box-header-->
					
					
					

    <div class="box-body table-responsive">

    <?php
 	
	if($leitura){
					
		echo '<table class="table table-hover">	
					<tr class="set">
					<td align="center">N</td>
					<td align="center">Hora</td>
					<td align="center">Rota</td>
					<td>Nome</td>
					<td>Bairro</td>
					<td>Endereco</td>
					<td align="center">Coleta</td>
					<td align="center">Frequencia</td>
					<td align="center">S</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		
		$contador=1;
		
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
				
				$clienteId = $mostra['id_cliente'];	
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");

				 
				 echo '<td>'.$contador++.'</td>';
			 
		
				$contratoId = $mostra['id'];	
				
				if ( $numero_semana == 0 ) {
					
					$hora = $mostra['domingo_hora1'];
					$rota = $mostra['domingo_rota1'];
					
					if($trocarRota=='1'){
						if($mostra['domingo_rota1']==$rotaId){
							$cad['domingo_rota1'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
						if($mostra['domingo_rota2']==$rotaId){
							$cad['domingo_rota2'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
					}
					
				}
		
				if ( $numero_semana == 1 ) {
					$hora = $mostra['segunda_hora1'];
					$rota = $mostra['segunda_rota1'];
					
					if($trocarRota=='1'){
						if($mostra['segunda_rota1']==$rotaId){
							$cad['segunda_rota1'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
						if($mostra['segunda_rota2']==$rotaId){
							$cad['segunda_rota2'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
					}
				}
		
				if ( $numero_semana == 2 ) {
					$hora = $mostra['terca_hora1'];
					$rota = $mostra['terca_rota1'];
					
					if($trocarRota=='1'){
						if($mostra['terca_rota1']==$rotaId){
							$cad['terca_rota1'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
						if($mostra['terca_rota2']==$rotaId){
							$cad['terca_rota2'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
					}
					
				}
		
				if ( $numero_semana == 3 ) {
					$hora = $mostra['quarta_hora1'];
					$rota = $mostra['quarta_rota1'];
					
					if($trocarRota=='1'){
						if($mostra['quarta_rota1']==$rotaId){
							$cad['quarta_rota1'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
						if($mostra['quarta_rota2']==$rotaId){
							$cad['quarta_rota2'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
					}
					
				}
		
				if ( $numero_semana == 4 ) { //QUINTA
					$hora = $mostra['quinta_hora1'];
					$rota = $mostra['quinta_rota1'];
					
					if($mostra['quinta_rota2']==$rotaId){
						$hora = $mostra['quinta_hora2'];
						$rota = $mostra['quinta_rota2'];
					}
					
					if($trocarRota=='1'){
						if($mostra['quinta_rota1']==$rotaId){
							$cad['quinta_rota1'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
						if($mostra['quinta_rota2']==$rotaId){
							$cad['quinta_rota2'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
					}
					
				}
		
				if ($numero_semana == 5 ) {
					
					$hora = $mostra['sexta_hora1'];
					$rota = $mostra['sexta_rota1'];
					
					if($mostra['sexta_rota2']==$rotaId){
						$hora = $mostra['sexta_hora2'];
						$rota = $mostra['sexta_rota2'];
					}
					
					if($trocarRota=='1'){
						if($mostra['sexta_rota1']==$rotaId){
							$cad['sexta_rota1'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
						if($mostra['sexta_rota2']==$rotaId){
							$cad['sexta_rota2'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
					}

				}
		
		
				if ( $numero_semana == 6 ) {
					$hora = $mostra['sabado_hora1'];
					$rota = $mostra['sabado_rota1'];
					
					if($trocarRota=='1'){
						if($mostra['sabado_rota1']==$rotaId){
							$cad['sabado_rota1'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
						if($mostra['sabado_rota2']==$rotaId){
							$cad['sabado_rota2'] = $rota2;
							update('contrato',$cad,"id = '$contratoId'");	
						}
					}
				}
				

				echo '<td align="center">'.$hora.'</td>';

				$rota = mostra('contrato_rota',"WHERE id ='$rota'");
				echo '<td>'.$rota['nome'].'</td>';
		
				$clienteId = $mostra['id_cliente'];	
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td>'.substr($cliente['nome'],0,20).'</td>';
				echo '<td>'.substr($cliente['bairro'],0,12).'</td>';
		
				$endereco = tirarAcentos($cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].', '.$cliente['cidade']);
		
				echo '<td>'.substr($endereco,0,40).'</td>';

				$contratoId = $mostra['id'];
				$contratoColeta = mostra('contrato_coleta',"WHERE id_contrato  ='$contratoId'");
		
				$tipoColetaId = $contratoColeta['tipo_coleta'];
				$tipoColeta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				echo '<td>'.$tipoColeta['nome'].'</td>';
		
				$frequenciaId = $mostra['frequencia'];
				$frequencia= mostra('contrato_frequencia',"WHERE id ='$frequenciaId'");
				if ($frequencia ) {
					echo '<td>'.$frequencia['nome'].'</td>';
				}else{
				   echo '<td colspan="1"><span class="badge bg-red">!</span></td>';
				}
		
				// 5 Contrato Ativo 
				// 6 Contrato Suspensos
				// 9 Contrato Cancelado
				// 10 Ação JUDICIAL
				// 19 Contrato Suspenso Temporario
			
				if($mostra['status']==5) {
					echo '<td align="center"><img src="ico/contrato-ativo.png" 
											 title="Contrato Ativo" />  </td>';
				}elseif($mostra['status']==6){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($mostra['status']==7){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
											 title="Contrato Suspenso" /> </td>';
				}elseif($mostra['status']==9){
					echo '<td align="center"><img src="ico/contrato-suspenso.png" 
										 title="Contrato Rescindo" /> </td>';
				}elseif($mostra['status']==19){
					echo '<td align="center"><img src="ico/contrato-suspenso-temporario.png" 
											 title="Contrato Suspenso Temporario" /> </td>';
				}elseif($mostra['status']==10){
					echo '<td align="center"><img src="ico/juridico.png" 
										 title="Contrato no Juridico" /> </td>';
				}else{
					echo '<td align="center"><span class="badge bg-red">ERRO !</span></td>';
				}
								  
				echo '<td align="center">
							<a href="painel.php?execute=suporte/cliente/cliente-editar&clienteEditar='.$cliente['id'].'">
								<img src="ico/editar-cliente.png" alt="Editar Cliente" title="Editar Cliente" />
							</a>
						  </td>';
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Contrato" class="tip" />
              			</a>
				      </td>';
		
			
	
			echo '</tr>';
		 endforeach;
		 
		 echo '<tfoot>';
          echo '<tr>';
             echo '<td colspan="17">' . 'Total de registros : ' .  $total . '</td>';
          echo '</tr>';
         echo '</tfoot>';
		 
		 echo '</table>';
		 
	
		
		}
		
		?>

	  </div><!-- /.box-body table-responsive -->

    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
		
</section><!-- /.content -->
  
<section class="content">
	
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
            
			<div id="mp-container" class="mp-container">
				<div id="ss-container" class="ss-container">
					
					<div id="map_canvas"> 
    					<div id="map"> <!-- area de carregar o mapa -->
							<span style="color:Gray;">Carregando Mapa...</span>		
          		  	   </div><!-- map -->
				
						<script type="text/javascript">
							var locations =[
									<?php 
								
								$ordem1='';	
								$ordem2='';	
								$ordem3='';	
								$ordem4='';	
								$ordem5='';
								$ordem6='';	
								$ordem7='';	
								$ordem8='';	
								$ordem9='';	
								$ordem10='';	
								$ordem11='';	
								$ordem12='';	
								$ordem13='';	
								$ordem14='';	
								$ordem15='';
								$ordem16='';
								$ordem17='';
								$ordem18='';
								$ordem19='';
								$ordem20='';
								$ordem16='';
								$ordem17='';
								$ordem18='';
								$ordem19='';
								$ordem20='';
								$ordem21='';
								$ordem22='';
								$ordem23='';
								$ordem24='';
								$ordem25='';
								$ordem26='';
								$ordem27='';
								$ordem28='';
								$ordem29='';
								$ordem30='';
								$ordem31='';
								$ordem32='';
								$ordem33='';
								$ordem34='';
								$ordem35='';
								$ordem36='';
								$ordem37='';
								$ordem38='';
								$ordem39='';
								$ordem40='';
								$ordem41='';
								$ordem42='';
								$ordem43='';
								$ordem44='';
								$ordem45='';
								$ordem46='';
								$ordem47='';
								$ordem48='';
								$ordem49='';
								$ordem50='';
								$ordem51='';
								$ordem52='';
								$ordem53='';
								$ordem54='';
								$ordem55='';
								$ordem56='';
								$ordem57='';
								$ordem58='';
								$ordem59='';
								$ordem60='';
								$ordem61='';
								$ordem62='';
								$ordem63='';
								$ordem64='';
								$ordem65='';
								$ordem66='';
								$ordem67='';
								$ordem68='';
								$ordem69='';
								$ordem70='';
								$ordem71='';
								$ordem72='';
								$ordem73='';
								$ordem74='';
								$ordem75='';
								$ordem76='';
								$ordem77='';
								$ordem78='';
								$ordem79='';
								$ordem70='';


								$contador=1;
																				
								if($leitura){
									foreach($leitura as $contrato):
									
										$contratoId=$contrato['id'];
										$clienteId=$contrato['id_cliente'];
									
								    	$cliente = mostra('cliente',"WHERE id ='$clienteId'");

										$endereco = $cliente['endereco'].', '.$cliente['numero'].$cliente['complemento'].' - '.$cliente['bairro'];
					
										$lapt = $cliente['latitude'];
										$long = $cliente['longitude'];
									
										if ( $numero_semana == 0 ) {
											$horaColeta = $contrato['domingo_hora1'];
										}
									
										if ( $numero_semana == 1 ) {
											$horaColeta = $contrato['segunda_hora1'];
										}
									
								
										if ( $numero_semana == 2 ) {
											$horaColeta = $contrato['terca_hora1'];
										}

										if ( $numero_semana == 3 ) {
											$horaColeta = $contrato['quarta_hora1'];
										}
									
										if ( $numero_semana == 4 ) {
											$horaColeta = $contrato['quinta_hora1'];
										}
									
										if ( $numero_semana == 5 ) {
											$horaColeta = $contrato['sexta_hora1'];

										}
									
										if ( $numero_semana == 6 ) {
											$horaColeta = $contrato['sabado_hora1'];
										}

										
										if(!empty($lapt)){
											
											$mostraRota = mostra('contrato_rota',"WHERE id ='$rotaId'");
											$nomeRota=$mostraRota['nome'];
													
											    if(empty($ordem1)){
													$ordem1=$horaColeta ;
												}elseif(empty($ordem2)){
													$ordem2=$horaColeta;
												}elseif(empty($ordem3)){
													$ordem3=$horaColeta;
												}elseif(empty($ordem4)){
													$ordem4=$horaColeta;
												}elseif(empty($ordem5)){
													$ordem5=$horaColeta;
												}elseif(empty($ordem6)){
													$ordem6=$horaColeta;
												}elseif(empty($ordem7)){
													$ordem7=$horaColeta;
												}elseif(empty($ordem8)){
													$ordem8=$horaColeta;
												}elseif(empty($ordem9)){
													$ordem9=$horaColeta;
												}elseif(empty($ordem10)){
													$ordem10=$horaColeta;
												}elseif(empty($ordem11)){
													$ordem11=$horaColeta;
												}elseif(empty($ordem12)){
													$ordem12=$horaColeta;
												}elseif(empty($ordem13)){
													$ordem13=$horaColeta;
												}elseif(empty($ordem14)){
													$ordem14=$horaColeta;
												}elseif(empty($ordem15)){
													$ordem15=$horaColeta;
												}elseif(empty($ordem16)){
													$ordem16=$horaColeta;
												}elseif(empty($ordem17)){
													$ordem17=$horaColeta;
												}elseif(empty($ordem18)){
													$ordem18=$horaColeta;
												}elseif(empty($ordem19)){
													$ordem19=$horaColeta;
												}elseif(empty($ordem20)){
													$ordem20=$horaColeta;
												}elseif(empty($ordem21)){
													$ordem21=$horaColeta;
												}elseif(empty($ordem22)){
													$ordem22=$horaColeta;
												}elseif(empty($ordem23)){
													$ordem23=$horaColeta;
												}elseif(empty($ordem24)){
													$ordem24=$horaColeta;
												}elseif(empty($ordem25)){
													$ordem25=$horaColeta;
												}elseif(empty($ordem26)){
													$ordem26=$horaColeta;
												}elseif(empty($ordem27)){
													$ordem27=$horaColeta;
												}elseif(empty($ordem28)){
													$ordem28=$horaColeta;
												}elseif(empty($ordem29)){
													$ordem29=$horaColeta;
												}elseif(empty($ordem30)){
													$ordem30=$horaColeta;
												}elseif(empty($ordem31)){
													$ordem31=$horaColeta;
												}elseif(empty($ordem32)){
													$ordem32=$horaColeta;
												}elseif(empty($ordem33)){
													$ordem33=$horaColeta;
												}elseif(empty($ordem33)){
													$ordem33=$horaColeta;
												}elseif(empty($ordem34)){
													$ordem34=$horaColeta;
												}elseif(empty($ordem35)){
													$ordem35=$horaColeta;
												}elseif(empty($ordem36)){
													$ordem36=$horaColeta;
												}elseif(empty($ordem37)){
													$ordem37=$horaColeta;
												}elseif(empty($ordem38)){
													$ordem38=$horaColeta;
												}elseif(empty($ordem39)){
													$ordem39=$horaColeta;
												}elseif(empty($ordem40)){
													$ordem40=$horaColeta;
												}elseif(empty($ordem41)){
													$ordem41=$horaColeta;
												}elseif(empty($ordem42)){
													$ordem42=$horaColeta;
												}elseif(empty($ordem43)){
													$ordem43=$horaColeta;
												}elseif(empty($ordem43)){
													$ordem43=$horaColeta;
												}elseif(empty($ordem44)){
													$ordem44=$horaColeta;
												}elseif(empty($ordem45)){
													$ordem45=$horaColeta;
												}elseif(empty($ordem46)){
													$ordem46=$horaColeta;
												}elseif(empty($ordem47)){
													$ordem47=$horaColeta;
												}elseif(empty($ordem48)){
													$ordem48=$horaColeta;
												}elseif(empty($ordem49)){
													$ordem49=$horaColeta;
												}elseif(empty($ordem50)){
													$ordem50=$horaColeta;
												}elseif(empty($ordem51)){
													$ordem51=$horaColeta;
												}elseif(empty($ordem52)){
													$ordem52=$horaColeta;
												}elseif(empty($ordem53)){
													$ordem53=$horaColeta;
												}elseif(empty($ordem53)){
													$ordem53=$horaColeta;
												}elseif(empty($ordem54)){
													$ordem54=$horaColeta;
												}elseif(empty($ordem55)){
													$ordem55=$horaColeta;
												}elseif(empty($ordem56)){
													$ordem56=$horaColeta;
												}elseif(empty($ordem57)){
													$ordem57=$horaColeta;
												}elseif(empty($ordem58)){
													$ordem58=$horaColeta;
												}elseif(empty($ordem59)){
													$ordem59=$horaColeta;
												}elseif(empty($ordem60)){
													$ordem60=$horaColeta;
												}elseif(empty($ordem61)){
													$ordem61=$horaColeta;
												}elseif(empty($ordem62)){
													$ordem62=$horaColeta;
												}elseif(empty($ordem53)){
													$ordem53=$horaColeta;
												}elseif(empty($ordem63)){
													$ordem63=$horaColeta;
												}elseif(empty($ordem64)){
													$ordem64=$horaColeta;
												}elseif(empty($ordem65)){
													$ordem65=$horaColeta;
												}elseif(empty($ordem66)){
													$ordem66=$horaColeta;
												}elseif(empty($ordem67)){
													$ordem67=$horaColeta;
												}elseif(empty($ordem68)){
													$ordem68=$horaColeta;
												}elseif(empty($ordem69)){
													$ordem69=$horaColeta;
												}elseif(empty($ordem70)){
													$ordem70=$horaColeta;
													
													break;
											}

											 		
											$nome=substr($cliente['nome'],0,20);
											$icone="suporte/mapas/images/editar.png";

											echo "['<div class=info><h4>$nome</h4><br><a href=https://www.cleansistemas.com.br/admin/painel.php?execute=suporte/contrato/contrato-editar&contratoEditar=$contratoId><img src=$icone></a><br><p>$endereco<br>$horaColeta<br>$nomeRota<br>$contador</p></div>', $lapt, $long],";
											
											$contador++;

										} // apenas se tiver latitude
									
							endforeach;
							
					 }else{
					echo "<h3 align='center'><font color='#ff0000'>No Content Found</font></h3>";
					}
				?>
			];
					
	
	var iconURLPrefix = 'suporte/mapas/images/';
    var icons = [  

	 <?php


		if(!empty($ordem1)){
			$marker="marker01.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem2)){
			$marker="marker02.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem3)){
			$marker="marker03.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem4)){
			$marker="marker04.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem5)){
			$marker="marker05.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem6)){
			$marker="marker06.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem7)){
			$marker="marker07.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem8)){
			$marker="marker08.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem9)){
			$marker="marker09.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem10)){
			$marker="marker10.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem11)){
			$marker="marker11.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem12)){
			$marker="marker12.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem13)){
			$marker="marker13.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem14)){
			$marker="marker14.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem15)){
			$marker="marker15.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem16)){
			$marker="marker16.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem17)){
			$marker="marker17.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem18)){
			$marker="marker18.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem19)){
			$marker="marker19.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem20)){
			$marker="marker20.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem21)){
			$marker="marker21.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem22)){
			$marker="marker22.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem23)){
			$marker="marker23.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem24)){
			$marker="marker24.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem25)){
			$marker="marker25.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem26)){
			$marker="marker26.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem27)){
			$marker="marker27.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem28)){
			$marker="marker28.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem29)){
			$marker="marker29.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem30)){
			$marker="marker30.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem31)){
			$marker="marker31.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem32)){
			$marker="marker32.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem33)){
			$marker="marker33.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem34)){
			$marker="marker34.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem35)){
			$marker="marker35.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem36)){
			$marker="marker36.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem37)){
			$marker="marker37.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem38)){
			$marker="marker38.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem39)){
			$marker="marker39.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem40)){
			$marker="marker40.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem41)){
			$marker="marker41.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem42)){
			$marker="marker42.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem43)){
			$marker="marker43.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem44)){
			$marker="marker44.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem45)){
			$marker="marker45.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem46)){
			$marker="marker46.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem47)){
			$marker="marker47.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem48)){
			$marker="marker48.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem49)){
			$marker="marker49.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem50)){
			$marker="marker50.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem51)){
			$marker="marker51.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem52)){
			$marker="marker52.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem53)){
			$marker="marker53.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem54)){
			$marker="marker54.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem55)){
			$marker="marker55.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem56)){
			$marker="marker56.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem57)){
			$marker="marker57.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem58)){
			$marker="marker58.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem59)){
			$marker="marker59.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem60)){
			$marker="marker60.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem61)){
			$marker="marker61.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem62)){
			$marker="marker62.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem63)){
			$marker="marker63.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem64)){
			$marker="marker64.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem65)){
			$marker="marker65.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem66)){
			$marker="marker66.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem67)){
			$marker="marker67.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem68)){
			$marker="marker68.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem69)){
			$marker="marker69.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem70)){
			$marker="marker70.png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}

	 ?>

    ]
    var icons_length = icons.length;
    var shadow = {
      anchor: new google.maps.Point(15,13),
      url: iconURLPrefix + 'msmarker.shadow.png'
    };
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 4,
      center: new google.maps.LatLng(<?php echo $lapt ;?>, <?php echo $long ;?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
	  disableDefaultUI: true,
      panControl: false,
      zoomControlOptions: {
      position: google.maps.ControlPosition.LEFT_BOTTOM
      }
    });
    var infowindow = new google.maps.InfoWindow({
      maxWidth: 700,
	  maxHeight: 750
    });
    var marker;
    var markers = new Array();
    var iconCounter = 0;
    for (var i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2], locations[i][3], locations[i][4], locations[i][5]),
        map: map,
        icon : icons[iconCounter],
        shadow: shadow
      });
      markers.push(marker);
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      iconCounter++;
      if(iconCounter >= icons_length){
      	iconCounter = 0;
      }
    }

    function AutoCenter() {
      var bounds = new google.maps.LatLngBounds();
      $.each(markers, function (index, marker) {
        bounds.extend(marker.position);
      });
      map.fitBounds(bounds);
    }
    AutoCenter();
  </script> 
  
						
 
 	</div><!-- map_canvas-->
				
   </div><!-- ss-container-->
		 
 </div><!-- mp-container-->
		 

  	 </div> <!-- /.box box-default-->
	  
    </div><!-- /.col-md-12 -->
   </div><!-- /.row--> 
 </section><!-- /.content -->

</div><!-- /.content-wrapper --> 

