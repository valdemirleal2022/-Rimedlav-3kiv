<?php

	if ( function_exists( ProtUser ) ) {
		if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
			header( 'Location: painel.php?execute=suporte/403' );
		}
	}


	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data1'];
		$rotaId = $_POST['rota'];
	}

	if (!isset( $_SESSION[ 'dataroteiro' ] ) ) {
		$dataroteiro = date( "Y/m/d" );
	} else {
		$dataroteiro = $_SESSION[ 'dataroteiro' ];
	}

	$dia_semana = diaSemana($dataroteiro);
	$numero_semana = numeroSemana($dataroteiro);
	
	
	//DOMINGO

	if ( $numero_semana == 0 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId' ORDER BY domingo_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND domingo='1' 
										AND domingo_rota1='$rotaId'");
	}

	if ( $numero_semana == 1 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId' ORDER BY segunda_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND segunda='1' 
										AND segunda_rota1='$rotaId'");
	}

	// TERCA
	if ( $numero_semana == 2 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND terca='1' AND 
											terca_rota1='$rotaId' ORDER BY terca_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND terca='1' 
											AND terca_rota1='$rotaId'");
	}

	//QUARTA
	if ( $numero_semana == 3 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND quarta='1' 
										AND quarta_rota1='$rotaId' ORDER BY quarta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quarta='1' 
										AND  quarta_rota1='$rotaId'");
	}
	
	//QUINTA
	if ( $numero_semana == 4 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND quinta='1' 
										AND quinta_rota1='$rotaId' ORDER BY quinta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  quinta='1' 
										AND  quinta_rota1='$rotaId'");
	}
	
	//SEXTA
	if ( $numero_semana == 5 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sexta='1' 
										AND sexta_rota1='$rotaId' ORDER BY sexta_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sexta='1' 
										AND  sexta_rota1='$rotaId'");
	}
	
	//SABADO
	if ( $numero_semana == 5 ) {
		$leitura = read( 'contrato', "WHERE id AND status='5' AND sabado='1' 
										AND sabado_rota1='$rotaId' ORDER BY sabado_hora1 ASC");
		$total = conta( 'contrato', "WHERE id AND status='5' AND  sabado='1' 
										AND  sabado_rota1='$rotaId'");
	}

	$dia_semana = diaSemana( $dataroteiro );
	$numero_semana = numeroSemana( $dataroteiro );


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
           
                         <div class="col-xs-6 col-md-8 pull-right">
        
                            <form name="form-pesquisa" method="post" class="form-inline " role="form">
                                
                                  <div class="form-group pull-left">         
										<input type="text" name="dia" value="<?php echo $dia_semana; ?>" class="form-control input-sm" disabled>
									 </div> <!-- /.input-group -->


                                 
                                 <div class="form-group pull-left">
										<input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
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
					<td>Endereço</td>
					<td align="center">Coleta</td>
					<td align="center" colspan="5">Gerenciar</td>
				</tr>';
		
		$contador=1;
		foreach($leitura as $mostra):
	 			
		 	echo '<tr>';
				
				$clienteId = $mostra['id_cliente'];	
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
		
				// pegar latituto e longitude atualizado em 04/08/2017
				$endereco = $cliente['endereco'].', '.$cliente['numero'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'];
					 
				$geo=geo($endereco);
				$cad['latitude'] = $geo[0];
				$cad['longitude'] = $geo[1];
		
				// mostra apenas se tiver latitude
				if(!empty($cad['latitude'])){
					echo '<td>'.$contador++.'</td>';
				}else{
					echo '<td> * </td>';
				}
		
				$contratoId = $mostra['id'];	
				
				if ( $numero_semana == 0 ) {
					$hora = $mostra['domingo_hora1'];
					$rota = $mostra['domingo_rota1'];
				}
				if ( $numero_semana == 1 ) {
					$hora = $mostra['segunda_hora1'];
					$rota = $mostra['segunda_rota1'];
				}
		
				if ( $numero_semana == 2 ) {
					$hora = $mostra['terca_hora1'];
					$rota = $mostra['terca_rota1'];
				}
		
				if ( $numero_semana == 3 ) {
					$hora = $mostra['quarta_hora1'];
					$rota = $mostra['quarta_rota1'];
				}
				if ( $numero_semana == 4 ) {
					$hora = $mostra['quinta_hora1'];
					$rota = $mostra['quinta_rota1'];
				}
				if ( $numero_semana == 5 ) {
					$hora = $mostra['sexta_hora1'];
					$rota = $mostra['sexta_rota1'];

				}
				if ( $numero_semana == 6 ) {
					$hora = $mostra['sabado_hora1'];
					$rota = $mostra['sabado_rota1'];
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
						
								$contador=1;
																				
								if($leitura){
									foreach($leitura as $contrato):
									
										$contratoId=$contrato['id'];
										$clienteId=$contrato['id_cliente'];
									
								    	$cliente = mostra('cliente',"WHERE id ='$clienteId'");
										$nome=substr($cliente['nome'],0,30);
										$endereco = $cliente['endereco'].', '.$cliente['numero'].$cliente['complemento'].' - '.$cliente['bairro'];
					
										$lapt = $cliente['latitude'];
										$long = $cliente['longitude'];
									
										if ( $numero_semana == 0 ) {
											$hora = $mostra['domingo_hora1'];
											$rota = $mostra['domingo_rota1'];
										}
										if ( $numero_semana == 1 ) {
											$hora = $mostra['segunda_hora1'];
											$rota = $mostra['segunda_rota1'];
										}

										if ( $numero_semana == 2 ) {
											$hora = $mostra['terca_hora1'];
											$rota = $mostra['terca_rota1'];
										}

										if ( $numero_semana == 3 ) {
											$hora = $mostra['quarta_hora1'];
											$rota = $mostra['quarta_rota1'];
										}
										if ( $numero_semana == 4 ) {
											$hora = $mostra['quinta_hora1'];
											$rota = $mostra['quinta_rota1'];
										}
										if ( $numero_semana == 5 ) {
											$hora = $mostra['sexta_hora1'];
											$rota = $mostra['sexta_rota1'];

										}
										if ( $numero_semana == 6 ) {
											$hora = $mostra['sabado_hora1'];
											$rota = $mostra['sabado_rota1'];
										}

									
										if(!empty($lapt)){
				
											$mostraRota = mostra('contrato_rota',"WHERE id ='$rotaId'");
											$nomeRota=$mostraRota['nome'];
											$hora=$ordem['hora'];

											if(empty($ordem1)){
													$ordem1=$hora ;
												}elseif(empty($ordem2)){
													$ordem2=$hora;
												}elseif(empty($ordem3)){
													$ordem3=$hora;
												}elseif(empty($ordem4)){
													$ordem4=$hora;
												}elseif(empty($ordem5)){
													$ordem5=$hora;
												}elseif(empty($ordem6)){
													$ordem6=$hora;
												}elseif(empty($ordem7)){
													$ordem7=$hora;
												}elseif(empty($ordem8)){
													$ordem8=$hora;
												}elseif(empty($ordem9)){
													$ordem9=$hora;
												}elseif(empty($ordem10)){
													$ordem10=$hora;
												}elseif(empty($ordem11)){
													$ordem11=$hora;
												}elseif(empty($ordem12)){
													$ordem12=$hora;
												}elseif(empty($ordem13)){
													$ordem13=$hora;
												}elseif(empty($ordem14)){
													$ordem14=$hora;
												}elseif(empty($ordem15)){
													$ordem15=$hora;
												}elseif(empty($ordem16)){
													$ordem16=$hora;
												}elseif(empty($ordem17)){
													$ordem17=$hora;
												}elseif(empty($ordem18)){
													$ordem18=$hora;
												}elseif(empty($ordem19)){
													$ordem19=$hora;
												}elseif(empty($ordem20)){
													$ordem20=$hora;
													break;
											}

											$data=converteData($dataroteiro['data']);
											$nome=substr($cliente['nome'],0,30);
											$icone="suporte/mapas/images/editar.png";

											echo "['<div class=info><h4>$nome</h4><br><a href=https://www.cleansistemas.com.br/admin/painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar=$contratoId><img src=$icone></a><br><p>$endereco<br>$data<br>$hora<br>$nomeRota </p></div>', $lapt, $long],";

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
	 
		 if(!empty($latitude)){
			echo 'iconURLPrefix + "geo.png",';
		 }
		 echo 'iconURLPrefix + "marker00.png",';

		if(!empty($ordem1)){
			$hora=substr($ordem1,0,2);
			$hora='01';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem2)){
			$hora=substr($ordem2,0,2);
			$hora='02';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem3)){
			$hora=substr($ordem3,0,2);
			$hora='03';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem4)){
			$hora=substr($ordem4,0,2);
			$hora='04';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem5)){
			$hora=substr($ordem5,0,2);
			$hora='05';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem6)){
			$hora=substr($ordem6,0,2);
			$hora='06';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem7)){
			$hora=substr($ordem7,0,2);
			$hora='07';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem8)){
			$hora=substr($ordem8,0,2);
			$hora='08';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem9)){
			$hora=substr($ordem9,0,2);
			$hora='09';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem10)){
			$hora=substr($ordem10,0,2);
			$hora='10';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem11)){
			$hora=substr($ordem11,0,2);
			$hora='11';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem12)){
			$hora=substr($ordem12,0,2);
			$hora='12';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem13)){
			$hora=substr($ordem13,0,2);
			$hora='13';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem14)){
			$hora=substr($ordem14,0,2);
			$hora='14';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem15)){
			$hora=substr($ordem15,0,2);
			$hora='15';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem16)){
			$hora=substr($ordem16,0,2);
			$hora='16';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem17)){
			$hora=substr($ordem17,0,2);
			$hora='17';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem18)){
			$hora=substr($ordem18,0,2);
			$hora='18';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem19)){
			$hora=substr($ordem19,0,2);
			$hora='19';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem20)){
			$hora=substr($ordem20,0,2);
			$hora='20';
			$marker="marker". $hora .".png";
			echo 'iconURLPrefix + "' . $marker. '",';
		}
		if(!empty($ordem21)){
			$hora=substr($ordem21,0,2);
			$hora='21';
			$marker="marker". $hora .".png";
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
  
						
						
	  </div><!-- map -->
 	</div><!-- map_canvas-->
				
   </div><!-- ss-container-->
		 
 </div><!-- mp-container-->
		 

   </div> <!-- /.box box-default-->
    </div><!-- /.col-md-12 -->
   </div><!-- /.row--> 
 </section><!-- /.content -->


</div><!-- /.content-wrapper --> 

