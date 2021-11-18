<?php 

//atualizado em 26/03/2014.
function pagehome(){
		$url = $_GET['url'];
		$url = explode('/', $url);
		$url[0] = ($url[0] == NULL ? 'index' : $url[0]);
		if(file_exists('site/'.$url[0].'.php')){
			require_once('site/'.$url[0].'.php');	
		}elseif(file_exists('site/'.$url[0].'/'.$url[1].'php')){
			require_once('site/'.$url[0].'/'.$url[1].'php');		
		}else{
			require_once('site/404.php');	
		}
}

//FUNÃ‡ÃƒO PARA GERAR RESUMOS
function resumos($string, $palavras = '100'){
	$string = strip_tags($string);
	$contar = strlen($string);
	
	if($contar <= $palavras){
		return $string;	
	}else{
		$strPos = strrpos(substr($string,0,$palavras),' ');
		return substr($string,0,$strPos);	
	}
}

//FUNÃ‡ÃƒO PARA VALIDAR E-MAILS
function email($email){
	 $er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
    if (preg_match($er, $email)){
    return true;
    } else {
    return false;
    }
}


//FUNÃ‡ÃƒO PARA VALIDAR CPF
function cpf($cpf){
	$cpf = preg_replace('/[^0-9]/', '', (string) $cpf);
	// Valida tamanho
	if (strlen($cpf) != 11)
		return false;
	// Calcula e confere primeiro dígito verificador
	for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
		$soma += $cpf{$i} * $j;
	$resto = $soma % 11;
	if ($cpf{9} != ($resto < 2 ? 0 : 11 - $resto))
		return false;
	// Calcula e confere segundo dígito verificador
	for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
		$soma += $cpf{$i} * $j;
	$resto = $soma % 11;
	return $cpf{10} == ($resto < 2 ? 0 : 11 - $resto);
}

function cnpj($cnpj){
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;
	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
		return false;
	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
}

//FUNÃ‡ÃƒO PARA DATA EM TIMESTAMP
function timedata($data){
	$timestamp = explode(" ",$data);
	$UrlData = $timestamp[0];
	$UrlTime = $timestamp[1];
	
		$setData = explode('/',$UrlData);
		$dia = $setData[0];
		$mes = $setData[1];
		$ano = $setData[2];
		if(!$UrlTime){
			$UrlTime = date('H:i:s');	
		}
		$resultado = $ano.'-'.$mes.'-'.$dia.' '.$UrlTime;
		return $resultado;
	
}

//FUNÃ‡ÃƒO PARA GERAR URL AMIGÃVEL
function url($url){
	$string=$url;
    $table = array(
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
        'Ç'=>'C', 'ç'=>'c', 'r'=>'r',
    );
    // Traduz os caracteres em $string, baseado no vetor $table
    $string = strtr($string, $table);
    // converte para minúsculo
    $string = strtolower($string);
    // remove caracteres indesejáveis (que não estão no padrão)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    // Remove múltiplas ocorrências de hífens ou espaços
    $string = preg_replace("/[\s-]+/", " ", $string);
    // Transforma espaços e underscores em hífens
    $string = preg_replace("/[\s_]/", "-", $string);
    // retorna a string
	$url=$string;
    return $url;
}

	
//FUNÃ‡ÃƒO DE RETORNO
	function setaurl(){
		echo URL;	
	}	

//FUNÃ‡ÃƒO PARA ENVIOS DE E-MAIL
	function enviaEmail($assunto,$mensagem,$remetente,$nomeremetente,$destino,$nomedestino, $reply = NULL, $replyname = NULL){
	
	require_once('email/class.phpmailer.php');
	
	$mail = new PHPMailer();
	$mail->Mailer = "mail";
	$mail->SMTPAuth = true;
	$mail->IsHTML(true);
	$mail->SMTPSecure = 'ssl';
	$mail->CharSet = 'UTF-8';
	
	$mail->Host = MAILHOST;
	$mail->Port = MAILPORT;	
	$mail->Username = MAILUSER;	
	$mail->Password = MAILPASS;	
	
	$mail->From = utf8_encode($remetente);
	$mail->FromName = utf8_encode($nomeremetente);
	
	if($reply != NULL){
		$mail->AddReplyTo($reply,$replyname);	
	}
	
	$mail->Subject = utf8_encode($assunto);	
	$mail->Body =  utf8_encode($mensagem);
	$mail->AddAddress($destino,$nomedestino);
	
	if($mail->Send()){
		
		 // registra a quantidade de email enviado
		registraEmail(); 
		
		return true;
		
	}else{
		return false;	
	}
}


	
//FUNÃ‡ÃƒO PARA PÃGINAÃ‡ÃƒO 
function pag($tabela, $cond, $maximos, $link, $pag, $width = NULL, $maxlinks = 5){
		$leitura = read("$tabela","$cond");
		$total = count($leitura);
		if($total > $maximos){
			$paginas = ceil($total/$maximos);
			echo '<div class="box-footer clearfix">';	
			if($width){
				echo '<ul class="pagination pagination-sm no-margin pull-right" style="width:'.$width.'">';
			}else{
				echo '<div class="paginacao">';
				echo '<ul class="pagination pagination-sm no-margin pull-right">';
			}
			echo '<li><a href="'.$link.'1">&laquo;</a></li>';
			for($i = $pag - $maxlinks; $i <= $pag - 1; $i++){
				if($i >= 1){
					echo '<li><a href="'.$link.$i.'">'.$i.'</a></li>';
				}
			}
			echo '<li class="active"><a href="'.$link.$i.'">'.$i.'</a></li>';
			for($i = $pag + 1; $i <= $pag + $maxlinks; $i++){
				if($i <= $paginas){
					echo '<li><a href="'.$link.$i.'">'.$i.'</a></li>';
				}
			}
			echo '<li><a href="'.$link.$paginas.'">&raquo;</a></li>';
			echo '</ul>';
			echo '</div><!--/pag-->';
		}
	}
	
	
//FUNÃ‡ÃƒO PARA UPLOAD DE IMAGENS
	function uploadImg($tmp, $nome, $width, $pasta){
		$ext = substr($nome,-3);
		
		switch($ext){
			case 'jpg' : $img = imagecreatefromjpeg($tmp); break;
			case 'png' : $img = imagecreatefrompng ($tmp); break;
			case 'gif' : $img = imagecreatefromgif ($tmp); break;
		}
		
		$x = imagesx($img);
		$y = imagesy($img);
		$height = ($width*$y) / $x;
		$nova = imagecreatetruecolor($width, $height);
		
		imagealphablending($nova,false);
		imagesavealpha($nova,true);
		imagecopyresampled($nova,$img,0,0,0,0,$width,$height,$x,$y);
		
		switch($ext){
			case 'jpg' : imagejpeg($nova, $pasta.$nome, 100); break;
			case 'png' : imagepng ($nova,$pasta.$nome); break;
			case 'gif' : imagegif ($nova, $pasta.$nome); break;	
		}
		imagedestroy($img);
		imagedestroy($nova);
					
	}
	
//FUNÃ‡ÃƒO PARA PROTEÃ‡ÃƒO
	function ProtUser($user, $nivel = NULL){
		if($nivel != NULL){
			$leitura = read('usuarios',"WHERE id = $user");	
			if($leitura){
			foreach($leitura as $user);
	if($user['nivel'] <= $nivel && $user['nivel'] != '0' && $user['nivel'] <= '3'){
				return true;				
				}else{
				return false;	
				}	
			}else{
				return false;	
			}
		}else{
			return true;		
		}
	}

//FUNÃ‡ÃƒO PARA VISITAS

function contavisitas($times = 2){
		$selMes = date('m');
		$selAno = date('Y');
		if(empty($_SESSION['startView']['sessao'])){
			$_SESSION['startView']['sessao'] = session_id();
			$_SESSION['startView']['ip'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['startView']['url'] = $_SERVER['PHP_SELF'];
			$_SESSION['startView']['time_end'] = time() + $times;
			$readViews = read('visitas',"WHERE mes = '$selMes' AND ano = '$selAno'");
			if(!$readViews){
				$createViews = array('mes' => $selMes, 'ano' => $selAno);	
				create('visitas',$createViews);
			}else{
				foreach($readViews as $views);
				if(empty($_COOKIE['startView'])){
					$updateViews = array(
						'visitas' => $views['visitas']+1,
						'pageviews' => $views['pageviews']+1,
					);
					update('visitas',$updateViews,"mes = '$selMes' AND ano = '$selAno'");
					setcookie('startView',time(),time()+60*60*24,'/');
				}else{
					$updateVisitas = array('visitas' => $views['visitas']+1);
					update('visitas',$updateVisitas,"mes = '$selMes' AND ano = '$selAno'");
				}
			}
		}else{
			$readPageViews = read('visitas',"WHERE mes = '$selMes' AND ano = '$selAno'");
			if($readPageViews){
				foreach($readPageViews as $rpgv);
				$updatePageViews = array('pageviews' => $rpgv['pageviews']+1);
				update('visitas',$updatePageViews,"mes = '$selMes' AND ano = '$selAno'");
			    }
			}
	}

//FUNÃ‡ÃƒO PARA AUTOR

	function autor($autorId, $campo = NULL){
		$autorId = mysql_real_escape_string($autorId);
		$readAutor = read('usuarios',"WHERE id = '$autorId'");		
		if($readAutor){
			foreach($readAutor as $autor);
			if(!$autor['fotoperfil']):			
				$novoavatar  = 'http://www.plentyperfect.com/wp-content/uploads/2012/06/gravatar.jpg';
				$autor['foto'] = $novoavatar;
			endif;
			if(!$campo){
				return $autor;	
			}else{
				return $autor[$campo];
			}
		}else{
			echo 'Erro ao ler autor';
		}
	}


	function avatar($autorId, $campo = NULL){
		$autorId = mysql_real_escape_string($autorId);
		$readAutor = read('cliente',"WHERE id = '$autorId'");		
		if($readAutor){
			foreach($readAutor as $autor);
			if(!$autor['logo']):			
				$novoavatar  = 'http://www.plentyperfect.com/wp-content/uploads/2012/06/gravatar.jpg';
				$autor['foto'] = $novoavatar;
			endif;
			if(!$campo){
				return $autor;	
			}else{
				return $autor[$campo];
			}
		}else{
			echo 'Erro ao ler cliente';
		}
	}	
	
//FUNÃ‡ÃƒO PARA TOP 05
function top($topicoId){
		$topicoId = mysql_real_escape_string($topicoId);
		$readArtigo = read('noticias',"WHERE id = '$topicoId'");
		
		foreach($readArtigo as $artigo);
			$views = $artigo['visitas'];
			$views = $views +1;
			$dataViews = array(
				'visitas' => $views
			);
			update('noticias',$dataViews,"id = '$topicoId'");
}	

//latitude
function geo($endereco){
	
	$endereco = url($endereco);
	$endereco = str_replace(' ','+',$endereco);
	$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$endereco.'&key=AIzaSyC4b2zbrrO2vKHiMYGQDKip9o_zS05dNUU');

	$output= json_decode($geocode);
	$latitude = $output->results[0]->geometry->location->lat;
	$longitude = $output->results[0]->geometry->location->lng;
	
	return array($latitude,$longitude);
	
}	


function converteData($data){
       if (strstr($data, "/")){//verifica se tem a barra /
           $d = explode ("/", $data);//tira a barra
           $rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
		   if($rstData <= '30/12/1899'){
   			 $rstData= '-';
		   }
           return $rstData;
       }
       else if(strstr($data, "-")){
          $data = substr($data, 0, 10);
          $d = explode ("-", $data);
          $rstData = "$d[2]/$d[1]/$d[0]"; 
		//  if($rstData <= '30/12/1899'){
//   			 $rstData= '-';
//		   }
          return $rstData;
       }
       else{
           return '';
      }
}


function converteMes($data){
	$ex = explode("-", $data);
	$ano = $ex[0];
	$mes = $ex[1];
	$dia = $ex[2];
	switch ($mes){

	case 1: $mes = "JAN"; break;
	case 2: $mes = "FEV"; break;
	case 3: $mes = "MAR"; break;
	case 4: $mes = "ABR"; break;
	case 5: $mes = "MAI"; break;
	case 6: $mes = "JUN"; break;
	case 7: $mes = "JUL"; break;
	case 8: $mes = "AGO"; break;
	case 9: $mes = "SET"; break;
	case 10: $mes = "OUT"; break;
	case 11: $mes = "NOV"; break;
	case 12: $mes = "DEZ"; break;

	}
    return $mes;
}

function converteDia($data){
	$ex = explode("-", $data);
	$ano = $ex[0];
	$mes = $ex[1];
	$dia = $ex[2];
    return $dia;
}

function converteAno($data){
	$ex = explode("-", $data);
	$ano = $ex[0];
	$mes = $ex[1];
	$dia = $ex[2];
    return $ano;
}

function converteValor($numero) {
	$valor =number_format($numero, 2, ',', '.');
	return $valor;
 }

function converteData1(){
	$data1 = mktime(0, 0, 0, date('m') , 1 , date('Y'));
	$data2 = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
	$data1 = date('Y/m/d',$data1);
	$data2 = date('Y/m/d',$data2);
    return $data1;
}

function converteData2(){
	$data1 = mktime(0, 0, 0, date('m') , 1 , date('Y'));
	$data2 = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
	$data1 = date('Y/m/d',$data1);
	$data2 = date('Y/m/d',$data2);
    return $data2;
}

 


function atualizaSaldo($bancoId){
	
		if(empty($bancoId)){
			return;
		}
		
		$data= date("Y-m-d", strtotime("-15 day"));
		 
		//atualizar saldo
		$movimentacao = read('movimentacao',"WHERE data AND banco ='$bancoId' AND data>$data ORDER BY data ASC");
		if($movimentacao){
			 foreach($movimentacao as $edit):
				 $saldoinicio = $edit['saldo'];
				 $data  = $edit['data'];
				 $banco = $edit['banco'];
				  break; 
			endforeach;
		 }else{
			return;
		}
		$inicio=0;
	
		
	    while (strtotime($data) <= strtotime(date("Y/m/d"))) {
			
			$credito=0;
			$debito=0;
			
			$receber = read('receber',"WHERE pagamento = '$data' AND banco = '$banco'");
			if($receber){
			    foreach($receber as $edit):
					$credito=$credito+$edit['valor'];
				endforeach;
				
			}
			$transferencia = read('transferencia',"WHERE data = '$data' AND banco = '$banco'");
			if($transferencia){
			    foreach($transferencia as $edit):
					if($edit['credito']<>0){
						$credito=$credito+$edit['credito'];
					}
					if($edit['debito']<>0){
						$debito=$debito+$edit['debito'];
					}
				endforeach;
				
			}
			
			$pagar = read('pagar',"WHERE pagamento = '$data' AND banco = '$banco'");
			if($pagar){
			    foreach($pagar as $edit):
					$debito=$debito+$edit['valor'];
				endforeach;
			}

			if($incio=0){
				$saldo=$saldoinicio;
				$inicio=1;
			}else{
				$saldo=$saldoinicio+$credito-$debito;
			}

			$movimentacao = mostra('movimentacao',"WHERE data='$data' AND banco = '$banco'");
			if($movimentacao){
				$cad['credito']	= $credito;
				$cad['debito']	= $debito;
				$cad['saldo']	= $saldo;
				$id	= $movimentacao['id'];
				update('movimentacao',$cad,"id = '$id'");
			}else{
				$cad['data']	= $data;
				$cad['banco']	= $banco;
				$cad['credito']	= $credito;
				$cad['debito']	= $debito;
				$cad['saldo']	= $saldo;
				create('movimentacao',$cad);
			}
			$saldoinicio=$saldo;
			$data= date ("Y-m-d", strtotime("+1 day", strtotime($data)));
		}
} 

function atualizaSaldo2($bancoId,$data){
	
		if(empty($bancoId)){
			return;
		}
		
		$data= date ("Y-m-d", strtotime("-1 day", strtotime($data)));
		 
		//atualizar saldo
		$movimentacao = read('movimentacao',"WHERE data AND banco ='$bancoId' AND data>$data ORDER BY data ASC");
		if($movimentacao){
			 foreach($movimentacao as $edit):
				 $saldoinicio = $edit['saldo'];
				 $data  = $edit['data'];
				 $banco = $edit['banco'];
				  break; 
			endforeach;
		 }else{
			return;
		}
		$inicio=0;
		
	    while (strtotime($data) <= strtotime(date("Y/m/d"))) {
			$credito=0;
			$debito=0;
			$receber = read('receber',"WHERE pagamento = '$data' AND banco = '$banco'");
			if($receber){
			    foreach($receber as $edit):
					$credito=$credito+$edit['valor'];
				endforeach;
				
			}
			$transferencia = read('transferencia',"WHERE data = '$data' AND banco = '$banco'");
			if($transferencia){
			    foreach($transferencia as $edit):
					if($edit['credito']<>0){
						$credito=$credito+$edit['credito'];
					}
					if($edit['debito']<>0){
						$debito=$debito+$edit['debito'];
					}
				endforeach;
				
			}
			$pagar = read('pagar',"WHERE pagamento = '$data' AND banco = '$banco'");
			if($pagar){
			    foreach($pagar as $edit):
					$debito=$debito+$edit['valor'];
				endforeach;
			}

			if($incio=0){
				$saldo=$saldoinicio;
				$inicio=1;
			}else{
				$saldo=$saldoinicio+$credito-$debito;
			}

			$movimentacao = mostra('movimentacao',"WHERE data='$data' AND banco = '$banco'");
			if($movimentacao){
				$cad['credito']	= $credito;
				$cad['debito']	= $debito;
				$cad['saldo']	= $saldo;
				$id	= $movimentacao['id'];
				update('movimentacao',$cad,"id = '$id'");
			}else{
				$cad['data']	= $data;
				$cad['banco']	= $banco;
				$cad['credito']	= $credito;
				$cad['debito']	= $debito;
				$cad['saldo']	= $saldo;
				create('movimentacao',$cad);
			}
			$saldoinicio=$saldo;
			$data= date ("Y-m-d", strtotime("+1 day", strtotime($data)));
		}
}


function interacao($interacao,$contrato){
	if(empty($contrato)){
		return;
	}
	$cad['data']= date('Y/m/d H:i:s');
	$cad['id_contrato']	= $contrato;
	$cad['interacao']	= $interacao;
	$cad['usuario']	=  $_SESSION['autUser']['nome'];
	create('interacao',$cad);
}

function titulo($titulo){
  echo '<title>' .$titulo. '</title>';
  echo '<h1>' .$titulo. '</h1>';  

}

function statusCliente($clienteId){
	$clienteId=$clienteId;
	$status='3';	 
	$leitura1 = read('servico',"WHERE id_cliente='$clienteId' AND tipo='2' AND situacao='5' ORDER BY servico ASC");			
	if($leitura1){
		foreach($leitura1 as $servico):
				$servicoId=$servico['id'];
				$leitura2 = read('servico_inseto',"WHERE id_servico='$servicoId'");
				foreach($leitura2 as $inseto):
					if($inseto['vencimento']>date("Y-m-d")){
						$status='2';	 
					}	
				endforeach;
		endforeach;
	}else{
		$leitura1 = read('servico',"WHERE id_cliente='$clienteId' AND tipo='1' AND situacao='4' ORDER BY servico ASC");
		if($leitura1){
			$status='1';
		 }else{
			$status='0';
		}	 
	}
	
		$cad['status'] = $status;
		update('cliente',$cad,"id = '$clienteId'");

	return $status;
}

function registraEmail(){

			$emailEnviado = mostra('email_contador',"WHERE id");
			$contadorContador=$emailEnviado['contador'];
			$contadorData=$emailEnviado['data'];
			$contadorId=$emailEnviado['id'];
			if($contadorContador==0){
				$cad2['data']= date('Y/m/d H:i:s');
				$cad2['contador']=$contadorContador+1;
			  }else{
				$cad2['contador']=$contadorContador+1;
			}
			update('email_contador',$cad2,"id = '$contadorId'");
			
			$horaInicial=$contadorData;
			$horaFinal=date('Y/m/d H:i:s');

			 $horaInicial  = strtotime($horaInicial);
			 $horaFinal    = strtotime($horaFinal);
			 $totalHora = ($horaFinal - $horaInicial) / 60; 
			 
		//	 echo 'Tempo Total :'. $totalHora;
			 
			 if($totalHora>60){
					$cad2['data']= date('Y/m/d H:i:s');
					$cad2['contador']=0;
					update('email_contador',$cad2,"id = '$contadorId'");
			 }
}



function diaSemana($data){ // escreve extenso do dia da semana

$diasemana_numero = date('w', strtotime($data));

switch($diasemana_numero){

	case 0: 
		$diasemana_numero = "Domingo";
			break;
	case 1: 
		$diasemana_numero = "Segunda-Feira";
			break;

	case 2: 
		$diasemana_numero = "Terça-Feira";
			break;

	case 3 :
		$diasemana_numero = "Quarta-Feira";
			break;

	case 4: 
		$diasemana_numero = "Quinta-Feira";
			break;

	case 5:
		$diasemana_numero = "Sexta-Feira"; 
			break;

	case 6: 
		$diasemana_numero = "Sábado";
			break;

	}

return $diasemana_numero;
}


function numeroSemana($data){ // Traz o numero do dia da semana   
	$diasemana_numero = date('w', strtotime($data));
	return $diasemana_numero;
}


function numeroDia($data){ // Traz o dia da data
	$diasemana_numero = date('d', strtotime($data));
	return $diasemana_numero;
}

function numeroMes($data){ // Traz o dia da data
	$numeroMes = date('m', strtotime($data));
	return $numeroMes;
}

function somarDia($data,$dia){ // Somar Dia
	$numeroDia = $dia;
	$dataSomar = $data;
	$dataSomar = date("Y-m-d", strtotime("$dataSomar +" . $numeroDia."days"));
	return $dataSomar;
}


function limpaNumero($valor){
 $valor = trim($valor);
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace("/", "", $valor);
 $valor = str_replace("=", "", $valor);	
 return $valor;
}

function somarMes($data,$mes){ // Somar Dia
	$numeroMes = $mes;
	$dataSomar = $data;
	$dataSomar = date("Y-m-d", strtotime("$dataSomar + $numeroMes month"));
	return $dataSomar;
}

function diminuirMes($data,$mes){ // Somar Dia
	$numeroMes = $mes;
	$dataSomar = $data;
	$dataSomar = date("Y-m-d", strtotime("$dataSomar - $numeroMes month"));
	return $dataSomar;
}

function tirarAcentos($string){
    $table = array(
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'Í'=>'I',
        'ô'=>'o', 'õ'=>'o', 'ó'=>'o', 'ø'=>'o', 'ú'=>'u', 'Ã'=>'A', '£'=>' ',
        'Ç'=>'C', 'ç'=>'c', 'º'=>'o', 'ª'=>'a', '³'=>'3', 'ñ'=>'n',
		'/'=>'', "'\'"=>'', '-'=>' ', "'"=>'', '°'=>'o', 'ª'=>'a',
    );
    // Traduz os caracteres em $string, baseado no vetor $table
    $string = strtr($string, $table);
    
     // remove caracteres indesejáveis (que não estão no padrão)
   //	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    // Remove múltiplas ocorrências de hífens ou espaços
   // $string /= preg_replace("/[\s-]+/", " ", $string);
	    // Transforma espaços e underscores em hífens
   // $string = p//reg_replace("/[\s_]/", "-", $string);
    // retorna a string
//$url=$string;
    return $string;
 }


//atualizado em 18/08/2017
function proximaColeta($numeroContrato,$ultimaData) {
	
	$contratoId=$numeroContrato;
	$data=$ultimaData;

	$contratoColeta = mostra( 'contrato', "WHERE id AND id='$contratoId'" );
	$frequencia = $contratoColeta[ 'frequencia' ];
	
	// SEMANAL
	if ( $frequencia == 1 ) {
		
		$ehdiaColeta = '0';  
		
		while (true) {
		
			$data = date("Y-m-d", strtotime("$data + 1 days"));
			
			if($contratoColeta['domingo']==1){
				if (numeroSemana($data)==0) { // DOMINGO
					$ehdiaColeta = '1';
				}
			}
			if($contratoColeta['segunda']==1){
				if (numeroSemana($data)==1) { // SEGUNDA
					$ehdiaColeta = '1';
				}
			}
			if($contratoColeta['terca']==1){
				if (numeroSemana($data)==2) { // TERCA
					$ehdiaColeta = '1';
				}
			}
			if($contratoColeta['quarta']==1){
				if (numeroSemana($data)==3) { // QUARTA
					$ehdiaColeta = '1';
				}
			}
			if($contratoColeta['quinta']==1){
				if (numeroSemana($data)==4) { // QUINTA
					$ehdiaColeta = '1';
				}
			}
			if($contratoColeta['sexta']==1){
				if (numeroSemana($data)==5) { // QUARTA
					$ehdiaColeta = '1';
				}
			}
			if($contratoColeta['sabado']==1){
				if (numeroSemana($data)==6) { // SABADO
					$ehdiaColeta = '1';
				}
			}
		
			if($ehdiaColeta == '1'){
				//echo 'Data Retorno : '.  converteData($data).'<br/>';
				break;
				
			}
	
		}
		
	} // SEMANAL
	
	
	
	// QUINZENAL
	if ( $frequencia == 2 ) {
		
		$ehdiaColeta = false;  
		$data = date( "Y-m-d", strtotime ( " $data +10 days " ) );

		while ( !$ehdiaColeta ):

			$data = date( "Y-m-d", strtotime ( " $data + 1 days " ) );

			if($contratoColeta['quinzenal']=='1'){ 
				if(numeroDia($data)>22){
					$ultimaColeta = $data;
					$ehMes = false;
					while ( !$ehMes ):
						$data = date("Y-m-d", strtotime("$data + 1 days"));
						if(numeroMes($data) <> numeroMes($ultimaColeta)){
							$ehMes = true;
						}
					endwhile;
				}
			}
		
			if($contratoColeta['quinzenal']=='2'){ 
				if(numeroDia($data)<6){
					$ehMes = false;
					while ( !$ehMes ):
						$data = date("Y-m-d", strtotime("$data + 1 days"));
						if(numeroDia($data)>6){
							$ehMes = true;
						}
					endwhile;
				}
			}
	
	
			if($contratoColeta['domingo']==1){
				if (numeroSemana($data)==0) { // DOMINGO
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['segunda']==1){
				if (numeroSemana($data)==1) { // SEGUNDA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['terca']==1){
				if (numeroSemana($data)==2) { // TERCA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quarta']==1){
				if (numeroSemana($data)==3) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quinta']==1){
				if (numeroSemana($data)==4) { // QUINTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sexta']==1){
				if (numeroSemana($data)==5) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sabado']==1){
				if (numeroSemana($data)==6) { // SABADO
					$ehdiaColeta = true;
				}
			}

		endwhile;


	} // QUINZENAL
	
	
	// MENSAL
	if ( $frequencia == 3 ) {
		
		$ehdiaColeta = false;  
		$data = date( "Y-m-d", strtotime ( " $data + 25 days " ) );

		while ( !$ehdiaColeta ):
		
			$data = date( "Y-m-d", strtotime ( " $data + 1 days " ) );
		
			if($contratoColeta['quinzenal']=='4'){  // 1a semana
				while (numeroDia($data)>8 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='5'){ // 2a semana
				while (numeroDia($data)<8 or numeroDia($data)>14 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='6'){ // 3a semana
				while (numeroDia($data)<15 or numeroDia($data)>22 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='7'){ // 4a semana
				while (numeroDia($data)<21):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
		
			if($contratoColeta['domingo']==1){
				if (numeroSemana($data)==0) { // DOMINGO
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['segunda']==1){
				if (numeroSemana($data)==1) { // SEGUNDA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['terca']==1){
				if (numeroSemana($data)==2) { // TERCA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quarta']==1){
				if (numeroSemana($data)==3) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quinta']==1){
				if (numeroSemana($data)==4) { // QUINTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sexta']==1){
				if (numeroSemana($data)==5) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sabado']==1){
				if (numeroSemana($data)==6) { // SABADO
					$ehdiaColeta = true;
				}
			}

		endwhile;


	} // MENSAL
	
	
	// MENSAL
	if ( $frequencia == 5 ) {
		
		$ehdiaColeta = false;  
		$data = date( "Y-m-d", strtotime ( " $data + 55 days " ) );

		while ( !$ehdiaColeta ):
		
			$data = date( "Y-m-d", strtotime ( " $data + 1 days " ) );
		
			if($contratoColeta['quinzenal']=='4'){  // 1a semana
				while (numeroDia($data)>8 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='5'){ // 2a semana
				while (numeroDia($data)<8 or numeroDia($data)>14 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='6'){ // 3a semana
				while (numeroDia($data)<15 or numeroDia($data)>22 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='7'){ // 4a semana
				while (numeroDia($data)<21):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
		
			if($contratoColeta['domingo']==1){
				if (numeroSemana($data)==0) { // DOMINGO
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['segunda']==1){
				if (numeroSemana($data)==1) { // SEGUNDA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['terca']==1){
				if (numeroSemana($data)==2) { // TERCA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quarta']==1){
				if (numeroSemana($data)==3) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quinta']==1){
				if (numeroSemana($data)==4) { // QUINTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sexta']==1){
				if (numeroSemana($data)==5) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sabado']==1){
				if (numeroSemana($data)==6) { // SABADO
					$ehdiaColeta = true;
				}
			}

		endwhile;


	} // MENSAL
	
	
	
	// MENSAL
	if ( $frequencia == 6 ) {
		
		$ehdiaColeta = false;  
		$data = date( "Y-m-d", strtotime ( " $data + 85 days " ) );

		while ( !$ehdiaColeta ):
		
			$data = date( "Y-m-d", strtotime ( " $data + 1 days " ) );
		
			if($contratoColeta['quinzenal']=='4'){  // 1a semana
				while (numeroDia($data)>8 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='5'){ // 2a semana
				while (numeroDia($data)<8 or numeroDia($data)>14 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='6'){ // 3a semana
				while (numeroDia($data)<15 or numeroDia($data)>22 ):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
			if($contratoColeta['quinzenal']=='7'){ // 4a semana
				while (numeroDia($data)<21):
					$data = date("Y-m-d", strtotime("$data + 1 days"));
				endwhile;
			}
		
			if($contratoColeta['domingo']==1){
				if (numeroSemana($data)==0) { // DOMINGO
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['segunda']==1){
				if (numeroSemana($data)==1) { // SEGUNDA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['terca']==1){
				if (numeroSemana($data)==2) { // TERCA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quarta']==1){
				if (numeroSemana($data)==3) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['quinta']==1){
				if (numeroSemana($data)==4) { // QUINTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sexta']==1){
				if (numeroSemana($data)==5) { // QUARTA
					$ehdiaColeta = true;
				}
			}
			if($contratoColeta['sabado']==1){
				if (numeroSemana($data)==6) { // SABADO
					$ehdiaColeta = true;
				}
			}

		endwhile;


	} // MENSAL
	
	
	//retorna proxima coleta
    return $data;
	
}


?>























