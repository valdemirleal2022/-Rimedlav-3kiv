<?php 

	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			header('Location: painel.php?execute=suporte/403');
		}	
	}


	if ( isset( $_POST[ 'relatorio-pdf' ] ) ) {
		$_SESSION[ 'dataInicio' ] = $_POST[ 'data1' ];
		$_SESSION[ 'dataFinal' ] = $_POST[ 'data1' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];

		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-conferencia-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'data1' ] = $_POST[ 'data1' ];
		$_SESSION[ 'data2' ] = $_POST[ 'data1' ];
		header( 'Location: ../admin/suporte/relatorio/relatorio-conferencia-excel.php' );
	}


	if(isset($_POST['pesquisar_numero'])){
		$ordemId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($ordemId)){
			echo '<div class="alert alert-warning">Número Inválido!</div><br />';
		}else{
			header('Location: painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$ordemId);
		}
	}

	if (!isset( $_SESSION[ 'dataroteiro' ] ) ) {
		$dataroteiro = date( "Y/m/d" );
		$_SESSION[ 'dataroteiro' ] = $dataroteiro;
	} else {
		$dataroteiro = $_SESSION[ 'dataroteiro' ];
		$rotaId = $_SESSION[ 'rotaColeta' ];
	}


	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data1'];
		$rotaId=$_POST['rota'];
		$total = conta('contrato_ordem',"WHERE id AND data='$dataroteiro' AND rota='$rotaId'");
		$leitura = read('contrato_ordem',"WHERE id  AND data='$dataroteiro'  AND rota='$rotaId'
									ORDER BY data DESC, hora ASC");
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

	$empresa = mostra('empresa');

	
?>


<div class="content-wrapper">
  <section class="content-header">
       <h1>Rota com Geolocalição</h1>
         <ol class="breadcrumb">
           <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li>Rota</a></li>
            <li><a href="#">Geolocalição</a></li>
         </ol>
 </section>
 
  <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
     
 <?php

	$ordemTotal = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND rota='$rotaId'" );
	$ordemEmaberto = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' AND 
									rota='$rotaId' AND status='12'" );
	$ordemRealizada = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' 
									AND rota='$rotaId' AND status='13'" );
	$ordemCancelada = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro' 
									AND rota='$rotaId' AND status='15'" );
	$ordemBaixada = $ordemRealizada+$ordemCancelada
 ?>

 <div class="box-header"> 
         <div class="info-box bg-blue">
                <span class="info-box-icon"><i class="ion ion-pie-graph"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Ordem de Serviço</span>
                  <span class="info-box-number"><?php echo $ordemTotal;?></span>
                  <div class="progress">
                    <?php
					 $percentual=($ordemBaixada/$ordemTotal)*100;
					 $percentual=intval($percentual);
					?>	
                    <div class="progress-bar" style="width: <?php echo $percentual;?>%"></div>
                  </div>
                  <span class="progress-description">
                     <?php echo 'Coletadas '.$percentual. '%  || Realizadas : '. $ordemRealizada . '   || Em Aberto  : '. $ordemEmaberto;?>
                  </span>
           </div><!-- /.info-box bg-red -->
 </div> <!-- /.box-header -->
          

      <div class="box-header">
        
                  <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        
                         <div class="form-group pull-left">
                            <select name="rota" class="form-control input-sm">
                                <option value="">Selecione Rota</option>
                                <?php 
                                    $readBanco = read('contrato_rota',"WHERE id ORDER BY nome");
                                    if(!$readBanco){
                                        echo '<option value="">Não temos Bancos no momento</option>';	
                                    }else{
                                        foreach($readBanco as $mae):
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
                         	<button class="btn btn-sm btn-default pull-right" type="submit" name="pesquisar">
                        	 <i class="fa fa-search"></i></button>
                         </div><!-- /.input-group -->
                          
                           <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-pdf"><i class="fa fa-file-pdf-o"></i></button>
                            </div>  <!-- /.input-group -->
                            
                            <div class="form-group pull-left">
                                <button class="btn btn-sm btn-default pull-right" type="submit" name="relatorio-excel"><i class="fa fa-file-excel-o"></i></button>
                            </div>   <!-- /.input-group -->
                            
                    </form> 
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
             </div><!-- /row-->  
              
       </div><!-- /box-header-->   
   
     <div class="box-body table-responsive">
     
     
    <?php
 	
	if($leitura){
			echo '<table class="table table-hover">
					<td align="center">N</td>
					<td align="center">Id</td>
					<td align="center">Hora</td>
					<td>Nome</td>
					<td>Bairro</td>
					<td align="center">Tipo de Coleta</td>
					<td align="center">Coletado</td>
					<td align="center">Posição</td>
					<td align="center">Status</td>
					<td align="center" colspan="5">Gerenciar</td>
					
				</tr>';
				$contador=1;
		foreach($leitura as $mostra):
		 	echo '<tr>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				
				// mostra apenas se tiver latitude
				if(!empty($cliente['latitude'])){
					echo '<td>'.$contador++.'</td>';
				}else{
					echo '<td> * </td>';
				}
				echo '<td>'.$mostra['id'].'</td>';
				echo '<td align="center">'.$mostra['hora'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td align="left">'.substr($cliente['nome'],0,17).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';


				$tipoColetaId = $mostra['tipo_coleta1'];
                $coleta = mostra('contrato_tipo_coleta',"WHERE id ='$tipoColetaId'");
				
				echo '<td>'.$coleta['nome'].'</td>';
				echo '<td align="center">'.$mostra['quantidade1'].'</td>';
		
				$status='';
				$horaatual=date("H:i");
				if($horaatual>$mostra['hora']){
					$status='Atrasado'; 
				}
				if(!empty($mostra['hora_coleta'])){
					$status=$mostra['hora_coleta']; 
				}
				
				echo '<td align="left">'.$status.'</td>';
				
				$statusId = $mostra['status'];
                $status = mostra('contrato_status',"WHERE id ='$statusId'");
		
				echo '<td align="center">'.$status['nome'].'</td>';
		
		
				echo '<td align="center">
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemEditar='.$mostra['id'].'">
			  				<img src="ico/editar.png" alt="Editar" title="Editar Ordem" class="tip" />
              			</a>
				      </td>';
			  
				 echo '<td align="center">
						<a href="painel.php?execute=contrato/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
			  				<img src="../admin/ico/termino.png" alt="Realizado" title="Coleta Realizado" class="tip" />
              			</a>
				 </td>';
		
				if(!empty($cliente['latitude'])){
						echo '<td align="center">
							<img src="ico/mapa.png" alt="Mapa" title="Mapa" class="tip" />
							 
						  </td>';
					  }else{
						echo '<td align="center">
							-
						  </td>';
					};
					  
				 
			echo '</tr>';
		 endforeach;
		 echo '</table>';
		 
		}
	?>
 </div>
 
    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 </section><!-- /.content -->

  <section class="content">

    <div class="row">
      <div class="col-md-12">
         <div class="box box-default">
					
			 <div class="box-body table-responsive">
					
            
<div id="mp-container" class="mp-container">
	<div id="ss-container" class="ss-container">
		<div id="map_canvas"> 
			
    		<div id="map"> <!-- area de carregar o mapa -->
					<span style="color:Gray;">Carregando Mapa...</span>		
            </div><!-- fim da area -->
			
				<script type="text/javascript">
					var locations =[
							<?php 
				
										$mostraRota = mostra('contrato_rota',"WHERE id ='$rotaId'");
										$nome=$mostraRota['nome'];
										$latitude=$mostraRota['latitude'];
										$longitude=$mostraRota['longitude'];
										$datageo=date('d/m/Y H:i:s',strtotime($mostraRota['data']));
										$foto = $mostraRota['fotoperfil'];
						
										if($foto != '' && file_exists('../../../uploads/rotas/'.$foto)){
											$icone='../../../uploads/rotas/'.$foto;
										  }else{
											$icone="../../../site/images/autor.png";
										}
											
										$lapt = $latitude;
										$long = $longitude;
										if(!empty($latitude)){
											echo "['<div class=info><h4>$nome</h4><br><img src=$icone></a><br><p>$datageo<br>$telefone<br></p></div>', $lapt, $long],";
										}
						
										$icone="suporte/mapas/images/editar.png";
										$hora="00:00";
										$data=$dataroteiro;
										$nome="Empresa";
						
										// pegar latituto e longitude atualizado em 04/08/2017
										$endereco = url($empresa['endereco'].', '.$empresa['numero'].' - '.$edit['empresa'].' - '.$empresa['cidade'].' - '.$empresa['cep']);
										$geo=geo($endereco);
										$lapt = $geo[0];
										$long = $geo[1];
										
										$hora=substr($ordem1,0,2);
										$hora='99';
										
										echo "['<div class=info><h4>$nome</h4><br><a href=https://www.cleansistemas.com.br/admin/painel.php?execute=contrato/ordem/ordem-editar&ordemEditar=$ordemId><img src=$icone></a><br><p>$endereco<br>$data<br>$hora</p>$hora</p></div>', $lapt, $long],";
														
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
									foreach($leitura as $ordem):
									
										$ordemId=$ordem['id'];
										$clienteId=$ordem['id_cliente'];
									
								    	$cliente = mostra('cliente',"WHERE id ='$clienteId'");
										$nome=substr($cliente['nome'],0,30);
										$endereco = $cliente['endereco'].', '.$cliente['numero'].$cliente['complemento'].' - '.$cliente['bairro'];
					
										$lapt = $cliente['latitude'];
										$long = $cliente['longitude'];
									
									
										if(!empty($lapt)){
				
											$mostraRota = mostra('contrato_rota',"WHERE id ='$rotaId'");
											$nomeRota=$mostraRota['nome'];
											$hora=$ordem['hora'];

											if(empty($ordem1)){
													$ordem1=$ordem['hora'];
												}elseif(empty($ordem2)){
													$ordem2=$ordem['hora'];
												}elseif(empty($ordem3)){
													$ordem3=$ordem['hora'];
												}elseif(empty($ordem4)){
													$ordem4=$ordem['hora'];
												}elseif(empty($ordem5)){
													$ordem5=$ordem['hora'];
												}elseif(empty($ordem6)){
													$ordem6=$ordem['hora'];
												}elseif(empty($ordem7)){
													$ordem7=$ordem['hora'];
												}elseif(empty($ordem8)){
													$ordem8=$ordem['hora'];
												}elseif(empty($ordem9)){
													$ordem9=$ordem['hora'];
												}elseif(empty($ordem10)){
													$ordem10=$ordem['hora'];
												}elseif(empty($ordem11)){
													$ordem11=$ordem['hora'];
												}elseif(empty($ordem12)){
													$ordem12=$ordem['hora'];
												}elseif(empty($ordem13)){
													$ordem13=$ordem['hora'];
												}elseif(empty($ordem14)){
													$ordem14=$ordem['hora'];
												}elseif(empty($ordem15)){
													$ordem15=$ordem['hora'];
												}elseif(empty($ordem16)){
													$ordem16=$ordem['hora'];
												}elseif(empty($ordem17)){
													$ordem17=$ordem['hora'];
												}elseif(empty($ordem18)){
													$ordem18=$ordem['hora'];
												}elseif(empty($ordem19)){
													$ordem19=$ordem['hora'];
												}elseif(empty($ordem20)){
													$ordem20=$ordem['hora'];
											}

											$data=converteData($ordem['data']);
											$nome=substr($cliente['nome'],0,30);
											$icone="suporte/mapas/images/editar.png";

											echo "['<div class=info><h4>$nome</h4><br><a href=https://www.cleansistemas.com.br/admin/painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar=$ordemId><img src=$icone></a><br><p>$endereco<br>$data<br>$hora<br>$nomeRota </p></div>', $lapt, $long],";

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
      anchor: new google.maps.Point(10,13),
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
      maxWidth: 1100,
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
  
 	</div>
    </div>
</div>


  </div><!-- /.box-body table-responsive -->

    </div><!-- /.box -->
  </div><!-- /.col-md-12 -->
 </div><!-- /.row -->
 </section><!-- /.content -->
  
</div><!-- /.content-wrapper --> 

