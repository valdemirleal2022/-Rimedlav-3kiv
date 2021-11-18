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
		
		$rotaId = $_POST['rota'];
		
		echo '<script type="text/javascript">';
		echo 'window.open("painel.php?execute=suporte/relatorio/relatorio-conferencia-coletados-pdf");';
		echo '</script>';
	}


	if ( isset( $_POST[ 'relatorio-excel' ] ) ) {
		$_SESSION[ 'data1' ] = $_POST[ 'data1' ];
		$_SESSION[ 'data2' ] = $_POST[ 'data1' ];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
		
		$rotaId = $_POST['rota'];
		
		header( 'Location: ../admin/suporte/relatorio/relatorio-conferencia-coletados-excel.php' );
	}


	if(isset($_POST['pesquisar_numero'])){
		$ordemId=strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		if(empty($ordemId)){
			echo '<div class="alert alert-warning">Número Inválido!</div><br />';
		}else{
			header('Location: painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$ordemId);
		}
		
		$rotaId = $_POST['rota'];
	}

	if(isset($_POST['pesquisar'])){
		$dataroteiro=$_POST['data1'];
		$rotaId = $_POST['rota'];
		$_SESSION[ 'rotaColeta' ] = $_POST['rota'];
	}


	if (!isset( $_SESSION[ 'dataroteiro' ] ) ) {
		$dataroteiro = date( "Y/m/d" );
		$_SESSION[ 'dataroteiro' ] = $dataroteiro;
	} else {
		$dataroteiro = $_SESSION[ 'dataroteiro' ];
	}

	if (isset( $_SESSION[ 'rotaColeta' ] ) ) {
		$rotaId = $_SESSION[ 'rotaColeta' ];
	}


	$pag = (empty($_GET['pag']) ? '1' : $_GET['pag']);
	$maximo = '50';
	$inicio = ($pag * $maximo) - $maximo;

	$total = conta( 'contrato_ordem', "WHERE id AND data='$dataroteiro'" );
	$leitura = read( 'contrato_ordem', "WHERE id AND data='$dataroteiro' 
						ORDER BY data DESC, hora ASC" );

	if(!empty($rotaId)){
		$total = conta('contrato_ordem',"WHERE id AND data='$dataroteiro' AND rota='$rotaId'");
		$leitura = read('contrato_ordem',"WHERE id  AND data='$dataroteiro'  AND rota='$rotaId'
									ORDER BY data DESC, hora ASC");
	}

	$_SESSION[ 'url' ] = $_SERVER[ 'REQUEST_URI' ];

?>

<div class="content-wrapper">
  <section class="content-header">
       <h1>Rotas da Coletas</h1>
         <ol class="breadcrumb">
           <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Ordem</a></li>
            <li><a href="painel.php?execute=suporte/mapas/ordem-rota">Rotas das Coletas</a></li>
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
               

        </div> <!-- /.box-header -->
 
     
      <div class="box-header">
              
                <div class="row">
                     <div class="col-xs-6 col-md-3 pull-left">
                       <form name="form-pesquisa" method="post" class="form-inline " role="form">
                             <div class="input-group">
                                  <input type="text" name="numero" class="form-control input-sm" placeholder="numero">
                                  <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" name="pesquisar_numero" type="submit"><i class="fa fa-search"></i></button>                                                     
                                  </div><!-- /.input-group -->
                             </div><!-- /input-group-->
                         </form> 
                  </div><!-- /col-md-3-->
                   
                  <div class="col-xs-10 col-md-5 pull-right">
                    
                     <form name="form-pesquisa" method="post" class="form-inline" role="form">
                       
                        <div class="form-group pull-left">
                            <input name="data1" type="date" value="<?php echo date('Y-m-d',strtotime($dataroteiro)) ?>" class="form-control input-sm" >
                        </div><!-- /.input-group -->
                        
                         <div class="form-group pull-left">
                            <select name="rota" class="form-control input-sm">
                                <option value="">Selecione Rota</option>
                                <?php 
                                    $readBanco = read('contrato_rota',"WHERE id");
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
                  </div><!-- /col-xs-10 col-md-7 pull-right-->
             </div><!-- /row-->  
              
       </div><!-- /box-header-->   
   
     <div class="box-body table-responsive">

    <?php
		 
	$enderecoPartida='Avenida Guilherme Maxwel, 156 - Bonsucesso, Rio de Janeiro';
	$enderecoChegada='Avenida Guilherme Maxwel, 256 - Bonsucesso, Rio de Janeiro';
	$enderecoAterro='Estrada Santa Rosa, s/n - Chapero, Seropédica';
		 
	$endereco1='';
	$endereco2='';
	$endereco3='';
	$endereco4='';
	$endereco5='';
	$endereco6='';
	$endereco7='';
	$endereco8='';	
	$endereco9='';
	$endereco10='';
	$endereco11='';
	$endereco12='';
	$endereco13='';
	$endereco14='';
	$endereco15='';
	$endereco16='';	
	$endereco17='';
	$endereco18='';
	$endereco19='';
	$endereco20='';
	$endereco22='';	
	$endereco20='';
	$endereco22='';	
 		 
	if($leitura){
			echo '<table class="table table-hover">
					<td align="center">-</td>
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
		
				$contador=0;
				$letras='BCDEFGHIJKLMNOPQRSTUVWABCDEFGHIJKLMNOPQRSTUVW';
		
		foreach($leitura as $mostra):
		 	echo '<tr>';
				 
				echo '<td>'.substr($letras,$contador,1).'</td>';
				$contador++;

				echo '<td>'.$mostra['id'].'</td>';
				echo '<td align="center">'.$mostra['hora'].'</td>';
		
				$clienteId = $mostra['id_cliente'];
				$cliente = mostra('cliente',"WHERE id ='$clienteId'");
				echo '<td align="left">'.substr($cliente['nome'],0,17).'</td>';
				echo '<td align="left">'.substr($cliente['bairro'],0,15).'</td>';

				if(empty($endereco1)){
					$endereco1 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'];
				}elseif(empty($endereco2)){
					$endereco2 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'];
				}elseif(empty($endereco3)){
					$endereco3 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['cep'];
				}elseif(empty($endereco4)){
					$endereco4 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco5)){
					$endereco5 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco6)){
					$endereco6 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco7)){
					$endereco7 = $cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco8)){
					$endereco8 = $cliente['endereco'].','.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco8)){
					$endereco8 = $cliente['endereco'].', '.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco9)){
					$endereco9 = $cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco10)){
					$endereco10 = $cliente['endereco'].', '.$cliente['numero'].' -'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco11)){
					$endereco11 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco12)){
					$endereco12 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco13)){
					$endereco13 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco14)){
					$endereco14 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco15)){
					$endereco15 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco16)){
					$endereco16 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco17)){
					$endereco17 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco18)){
					$endereco18 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco19)){
					$endereco19 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco20)){
					$endereco20 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco21)){
					$endereco21 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco22)){
					$endereco22 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco23)){
					$endereco23 = url($cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade']);
				}elseif(empty($endereco24)){
					$endereco24 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
			   }elseif(empty($endereco25)){
					$endereco25 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco26)){
					$endereco26 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco27)){	
					$endereco27 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco28)){
					$endereco28 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco29)){
					$endereco29 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
				}elseif(empty($endereco30)){
					$endereco30 = $cliente['endereco'].','.$cliente['numero'].'-'.$cliente['bairro'].','.$cliente['cidade'];
			   }

						
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
						<a href="painel.php?execute=suporte/ordem/ordem-editar&ordemBaixar='.$mostra['id'].'">
			  				<img src="ico/baixar.png" alt="Realizado" title="Coleta Realizado" class="tip" />
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
 
</div><!-- / box-body table-responsive -->  

 

 	 </div><!-- /box box-default -->
  	</div><!-- /.col-md-12 -->
  </div><!-- /.row -->
 </section><!-- /.content -->

<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
          <div class="box-header">
		   <div class="col-md-8" id="mapa"></div>	
		   <div class="col-md-4" id="trajeto-texto" ></div>
         
          </div> <!-- /.box-title-->  	
      </div> <!-- /.box box-default-->
     </div><!-- /.col-md-12 -->
     </div><!-- /.row--> 
  </section><!-- /.content -->
 
<section class="content">
  <div class="row">
   <div class="col-md-12">  
     <div class="box box-default">
          <div class="box-header">
		   <div class="col-md-8" id="mapa2"></div>	
		   <div class="col-md-4" id="trajeto-texto2" ></div>
            	
          </div> <!-- /.box-title-->  	
      </div> <!-- /.box box-default-->
     </div><!-- /.col-md-12 -->
     </div><!-- /.row--> 
  </section><!-- /.content -->
 
  
</div><!-- /.content-wrapper --> 


<script>
	var endereco1 = "<?php echo $endereco1;?>";
	var endereco2 = "<?php echo $endereco2;?>";
	var endereco3 = "<?php echo $endereco3;?>";
	var endereco4 = "<?php echo $endereco4;?>";
	var endereco5 = "<?php echo $endereco5;?>";
	var endereco6 = "<?php echo $endereco6;?>";
	var endereco7 = "<?php echo $endereco7;?>";
	var endereco8 = "<?php echo $endereco8;?>";
	var endereco9 = "<?php echo $endereco9;?>";
	var endereco10 = "<?php echo $endereco10;?>";
	var endereco11 = "<?php echo $endereco11;?>";
	var endereco12 = "<?php echo $endereco12;?>";
	var endereco13 = "<?php echo $endereco13;?>";
	var endereco14 = "<?php echo $endereco14;?>";
	var endereco15 = "<?php echo $endereco15;?>";
	var endereco16 = "<?php echo $endereco16;?>";
	var endereco17 = "<?php echo $endereco17;?>";
	var endereco18 = "<?php echo $endereco18;?>";
	var endereco19 = "<?php echo $endereco19;?>";
	var endereco20 = "<?php echo $endereco20;?>";
	var endereco21 = "<?php echo $endereco21;?>";
	var endereco22 = "<?php echo $endereco22;?>";
	var endereco23 = "<?php echo $endereco23;?>";
	var endereco24 = "<?php echo $endereco24;?>";
	var endereco25 = "<?php echo $endereco25;?>";
	var endereco26 = "<?php echo $endereco26;?>";
	var endereco27 = "<?php echo $endereco27;?>";

	var enderecoPartida = "<?php echo $enderecoPartida;?>";
	var enderecoChegada = "<?php echo $enderecoChegada;?>";
	var enderecoAterro = "<?php echo $enderecoAterro;?>";

	//Começando pelo google.maps.DirectionsService, o seu funcionamento é bem simples: Basta nós passarmos um objeto google.maps.DirectionsRequest, o qual irá conter o ponto de origem e o ponto de destino, e o meio de transporte, que podemos definir entre carro, a pé, bicicleta ou transporte público, que ele irá nos retornar um objeto google.maps.DirectionsResult, o qual contém as informações da rota, e o google.maps.DirectionsStatus, que por sua vez define o estado final da nossa requisição, ou seja, ele pode indicar sucesso (DirectionsStatus.OK), sem resultados (DirectionsStatus.ZERO_RESULTS), erro (DirectionsStatus.INVALID_REQUEST ou DirectionsStatus.REQUEST_DENIED), etc.
	//Já o google.maps.DirectionsRenderer, basicamente, fica responsável por renderizar o resultado fornecido pelo google.maps.DirectionsService. 	

	var map;
	var directionsDisplay; // Instanciaremos ele mais tarde, que será o nosso google.maps.DirectionsRenderer
	var directionsService = new google.maps.DirectionsService();

	function initialize() {
		directionsDisplay = new google.maps.DirectionsRenderer(); // Instanciando...
		var latlng = new google.maps.LatLng( -18.8800397, -47.05878999999999 );
		var options = {
			zoom: 5,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP

		};
		map = new google.maps.Map( document.getElementById( "mapa" ), options );
		directionsDisplay.setMap( map ); // Relacionamos o directionsDisplay com o mapa desejado 
		directionsDisplay.setPanel( document.getElementById( "trajeto-texto" ) ); // Aqui faço a definição
	}

	initialize();

	var request = { // Novo objeto google.maps.DirectionsRequest, contendo:
		origin: enderecoPartida, // origem
		destination: enderecoChegada, // origem
		travelMode: google.maps.TravelMode.DRIVING // meio de transporte, nesse caso, de carro
	};



	var request = {
		origin: enderecoPartida,
		destination: enderecoChegada,

		//Para isso, utilizaremos o DirectionsWaypoints, que são alguns pontos pré-definidos no meio do nosso trajeto. Veja que continuaremos a ter os dois pontos que vimos até agora, mas aqui só iremos adicionar alguns pontos no meio desses dois. 

		waypoints: [
				<?php echo (!empty($endereco1) ? '{location: endereco1},' : '') ;?>
				<?php echo (!empty($endereco2) ? '{location: endereco2},' : '') ;?>
				<?php echo (!empty($endereco3) ? '{location: endereco3},' : '') ;?>
				<?php echo (!empty($endereco4) ? '{location: endereco4},' : '') ;?>
				<?php echo (!empty($endereco5) ? '{location: endereco5},' : '') ;?>
				<?php echo (!empty($endereco6) ? '{location: endereco6},' : '') ;?>
				<?php echo (!empty($endereco7) ? '{location: endereco7},' : '') ;?>
				<?php echo (!empty($endereco8) ? '{location: endereco8},' : '') ;?>
				<?php echo (!empty($endereco9) ? '{location: endereco9},' : '') ;?>
				<?php echo (!empty($endereco10) ? '{location: endereco10},' : '') ;?>
				<?php echo (!empty($endereco11) ? '{location: endereco12},' : '') ;?>
				<?php echo (!empty($endereco12) ? '{location: endereco12},' : '') ;?>
				<?php echo (!empty($endereco13) ? '{location: endereco13},' : '') ;?>
				<?php echo (!empty($endereco14) ? '{location: endereco14},' : '') ;?>
				<?php echo (!empty($endereco15) ? '{location: endereco15},' : '') ;?>
				<?php echo (!empty($endereco16) ? '{location: endereco16},' : '') ;?>
				<?php echo (!empty($endereco17) ? '{location: endereco17},' : '') ;?>
				<?php echo (!empty($endereco18) ? '{location: endereco18},' : '') ;?>
				<?php echo (!empty($endereco19) ? '{location: endereco19},' : '') ;?>
				<?php echo (!empty($endereco20) ? '{location: endereco20},' : '') ;?>
				<?php echo (!empty($endereco21) ? '{location: endereco21},' : '') ;?>
				<?php echo (!empty($endereco23) ? '{location: endereco23},' : '') ;?>
			{location: enderecoAterro}
		],

		travelMode: google.maps.TravelMode.DRIVING
	};

	directionsService.route( request, function ( result, status ) {
		if ( status == google.maps.DirectionsStatus.OK ) { // Se deu tudo certo
			directionsDisplay.setDirections( result ); // Renderizamos no mapa o resultado
		}
	} );

	directionsDisplay.setOptions( {
		polylineOptions: {
			strokeWeight: 4,
			strokeOpacity: 1,
			strokeColor: 'red'
		}
	} );
	
	
	//Começando pelo google.maps.DirectionsService, o seu funcionamento é bem simples: Basta nós passarmos 
	
	var map2;
	var directionsDisplay2; // Instanciaremos ele mais tarde, que será o nosso google.maps.DirectionsRenderer
	var directionsService2 = new google.maps.DirectionsService();

	function initialize2() {
		directionsDisplay2 = new google.maps.DirectionsRenderer(); // Instanciando...
		var latlng = new google.maps.LatLng( -18.8800397, -47.05878999999999 );
		var options = {
			zoom: 5,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP

		};
		map2 = new google.maps.Map( document.getElementById( "mapa2" ), options );
		directionsDisplay2.setMap( map2 ); // Relacionamos o directionsDisplay com o mapa desejado 
		directionsDisplay2.setPanel( document.getElementById( "trajeto-texto2" ) ); // Aqui faço a definição
	}

	initialize2();

	var request = { // Novo objeto google.maps.DirectionsRequest, contendo:
		origin: enderecoPartida, // origem
		destination: enderecoChegada, // origem
		travelMode: google.maps.TravelMode.DRIVING // meio de transporte, nesse caso, de carro
	};



	var request = {
		origin: enderecoPartida,
		destination: enderecoChegada,

		//Para isso, utilizaremos o DirectionsWaypoints, que são alguns pontos pré-definidos no meio do nosso trajeto. Veja que continuaremos a ter os dois pontos que vimos até agora, mas aqui só iremos adicionar alguns pontos no meio desses dois. 

		waypoints: [
				<?php echo (!empty($endereco23) ? '{location: endereco23},' : '') ;?>
				<?php echo (!empty($endereco24) ? '{location: endereco24},' : '') ;?>
				<?php echo (!empty($endereco25) ? '{location: endereco25},' : '') ;?>
				<?php echo (!empty($endereco26) ? '{location: endereco26},' : '') ;?>
				<?php echo (!empty($endereco23) ? '{location: endereco27},' : '') ;?> 

				{location: enderecoAterro}
		],

		travelMode: google.maps.TravelMode.DRIVING
	};

	directionsService2.route( request, function ( result, status ) {
		if ( status == google.maps.DirectionsStatus.OK ) { // Se deu tudo certo
			directionsDisplay2.setDirections( result ); // Renderizamos no mapa o resultado
		}
	} );

	directionsDisplay2.setOptions( {
		polylineOptions: {
			strokeWeight: 4,
			strokeOpacity: 1,
			strokeColor: 'red'
		}
	} );
	
	
</script>

<style>
	#mapa {
		width: 750px;
		height: 500px;
		float: left
	}
	
	#trajeto-texto {
		font-size: 12px;
		width: 300px;
		height: 500px;
		float: right;
		overflow: scroll
	}
	
	#mapa2 {
		width: 750px;
		height: 500px;
		float: left
	}
	
	#trajeto-texto2 {
		font-size: 12px;
		width: 300px;
		height: 500px;
		float: right;
		overflow: scroll
	}
</style>

