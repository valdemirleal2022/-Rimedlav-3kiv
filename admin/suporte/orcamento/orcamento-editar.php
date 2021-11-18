<?php

if ( function_exists( ProtUser ) ) {
    if ( !ProtUser( $_SESSION[ 'autUser' ][ 'id' ] ) ) {
        header( 'Location: painel.php?execute=suporte/403' );
    }
}

if ( !empty( $_GET[ 'orcamentoEditar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoEditar' ];
    $acao = "atualizar";
}
if ( !empty( $_GET[ 'orcamentoAprovar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoAprovar' ];
    $acao = "aprovar";
	$readonly = "readonly";
    $disabled = 'disabled="disabled"';
}
if ( !empty( $_GET[ 'orcamentoBaixar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoBaixar' ];
    $acao = "baixar";
}
if ( !empty( $_GET[ 'orcamentoDesfazer' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoDesfazer' ];
    $acao = "desfazer";
}
if ( !empty( $_GET[ 'orcamentoDeletar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoDeletar' ];
    $acao = "deletar";
    $readonly = "readonly";
    $disabled = 'disabled="disabled"';
}
if ( !empty( $_GET[ 'orcamentoEnviar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoEnviar' ];
    $acao = "enviar";
    $readonly = "readonly";
    $disabled = 'disabled="disabled"';
}

if ( !empty( $_GET[ 'orcamentoVisualizar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoVisualizar' ];
    $acao = "visualizar";
    $readonly = "readonly";
    $disabled = 'disabled="disabled"';
}

if ( !empty( $_GET[ 'autorizarOperacional' ] ) ) {
    $orcamentoId = $_GET[ 'autorizarOperacional' ];
    $acao = "autorizarOperacional";
	$readonly = "readonly";
    $liberacaoOperacional = "";
	$liberacaoComercial = 'disabled="disabled"';
    $disabled = 'disabled="disabled"';
}

if ( !empty( $_GET[ 'autorizarComercial' ] ) ) {
    $orcamentoId = $_GET[ 'autorizarComercial' ];
    $acao = "autorizarComercial";
	$readonly = "readonly";
    $liberacaoOperacional = 'disabled="disabled"';
	$liberacaoComercial = "";
    $disabled = 'disabled="disabled"';
}


if ( !empty( $_GET[ 'autorizarJuridico' ] ) ) {
    $orcamentoId = $_GET[ 'autorizarJuridico' ];
    $acao = "autorizarJuridico";
	$readonly = "readonly";
	$liberacaoComercial = 'disabled="disabled"';
    $liberacaoOperacional = 'disabled="disabled"';
	$liberacaoJuridico = "";
    $disabled = 'disabled="disabled"';
}


if ( !empty( $_GET[ 'autorizarDiretoria' ] ) ) {
    $orcamentoId = $_GET[ 'autorizarDiretoria' ];
    $acao = "autorizarDiretoria";
	$readonly = "autorizarDiretoria";
	$liberacaoComercial = 'disabled="disabled"';
    $liberacaoOperacional = 'disabled="disabled"';
	$liberacaoJuridico = 'disabled="disabled"';
    $disabled = 'disabled="disabled"';
}

if ( !empty( $_GET[ 'orcamentoAprovar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoAprovar' ];
    $acao = "aprovar";
}

if ( !empty( $_GET[ 'contratoIniciando' ] ) ) {
    $orcamentoId = $_GET[ 'contratoIniciando' ];
    $acao = "contratoIniciando";
}

if ( !empty( $orcamentoId ) ) {
    $readorcamento = read( 'cadastro_visita', "WHERE id = '$orcamentoId'" );
    if ( !$readorcamento ) {
        header( 'Location: painel.php?execute=suporte/error' );
    }
    foreach ( $readorcamento as $edit );
	
		 
    $clienteId = $edit[ 'id' ];
    $readCliente = read( 'cadastro_visita', "WHERE id = '$clienteId'" );
    foreach ( $readCliente as $cliente );
}else{
    $acao = "cadastrar";
    $edit[ 'interacao' ] = date( 'Y/m/d H:i:s' );
	$edit[ 'data' ] = date( 'Y/m/d' );
    $edit[ 'status' ] = 1;
    //$edit[ 'tipo' ] = 1;
}

$_SESSION[ 'url2' ] = $_SERVER[ 'REQUEST_URI' ];
$_SESSION[ 'url_orcamento' ] = $_SERVER[ 'REQUEST_URI' ];

$consultorId=$_SESSION['autConsultor']['id'];

// 0 - visita
// 1 - solicitacoes
// 2 - orcamento

?><head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>
  



<div class="content-wrapper">
    <section class="content-header">
        <h1>Orçamento</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a href="#">Orçamento</a>
            </li>
            <li class="active">Editar</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default">
            <div class="box-header with-border">
                <div class="box-tools">
                    <small>
                        <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                        <?php if($acao=='deletar') echo 'Deletando';?>
                        <?php if($acao=='atualizar') echo 'Alterando';?>
                    </small>
                </div><!-- /box-tools-->
            </div><!-- /.box-header with-border -->
            
    <div class="box-body">

    <?php 
		
	if(isset($_POST['desfazer'])){
		$edit['interacao']= date('Y/m/d H:i:s');
		//$edit['tipo']= 1;
		$edit['status']= 3;
		$edit['situacao'] =1;
		update('cadastro_visita',$edit,"id = '$orcamentoId'");	
		header("Location: ".$_SESSION['url']);
	}
		
	if(isset($_POST['followup'])){
		$edit['interacao']= date('Y/m/d H:i:s');
		//$edit['tipo']= 1;
		$edit['status']= 3;
		$edit['situacao'] =1;
		update('cadastro_visita',$edit,"id = '$orcamentoId'");	
		header("Location: ".$_SESSION['url']);
	}	
		
	if(isset($_POST['cadastrar'])){
		
	    $edit['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		$edit['nome_fantasia']=strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
		$edit['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
		$edit['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$edit['complemento']=strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
		$edit['bairro']=strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
		$edit['cep']=strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
		$edit['cidade']=strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
		$edit['uf']=strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
		$edit['referencia']=strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
		$edit['telefone']=strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
		$edit['celular']=strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
		$edit['contato']=strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
		$edit['email']=strip_tags(trim(mysql_real_escape_string($_POST['email'])));
		$edit['cnpj']=strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
		$edit['inscricao']=strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
		$edit['cpf']=strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
		
		$edit['empresa_atual']= strip_tags(trim(mysql_real_escape_string($_POST['empresa_atual'])));
		$edit['consultor']= strip_tags(trim(mysql_real_escape_string($_POST['consultor'])));
		$edit['atendente']= strip_tags(trim(mysql_real_escape_string($_POST['atendente'])));
		$edit['indicacao']  = mysql_real_escape_string($_POST['indicacao']);

		$edit['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
		$edit['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
		$edit['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
		$edit['turno'] = mysql_real_escape_string($_POST['turno']);
		$edit['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
		$edit['orc_quantidade']= strip_tags(trim(mysql_real_escape_string($_POST['orc_quantidade'])));
		
		$edit['orc_valor_unitario']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_unitario'])));
		$edit['orc_valor_unitario'] = str_replace(",",".",str_replace(".","",$edit['orc_valor_unitario']));
		
		$edit['orc_valor_extra']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_extra'])));
		$edit['orc_valor_extra'] = str_replace(",",".",str_replace(".","",$edit['orc_valor_extra']));
		
		$edit['orc_valor']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor'])));
		$edit['orc_valor'] = str_replace(",",".",str_replace(".","",$edit['orc_valor']));
		
		$edit['orc_forma_pag']= strip_tags(trim(mysql_real_escape_string($_POST['orc_forma_pag'])));
		
		$edit['orc_comodato']= strip_tags(trim(mysql_real_escape_string($_POST['orc_comodato'])));
		
		$edit['orc_observacao']= mysql_real_escape_string($_POST['orc_observacao']);
		$edit['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
		$edit['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
		 
		$edit['data']= date('Y/m/d');
		$edit['interacao']= date('Y/m/d H:i:s');
		
		$edit['status'] = 2;
		
		if(empty($edit['atendente'])){
				echo '<div class="alert alert-warning">O atendente  é obrigatório!</div>'.'<br>';
			}elseif(empty($edit['nome'])){
				$edit['nome']= strip_tags(trim($_POST['nome']));
				echo '<div class="alert alert-warning">O Nome do cliente é obrigatório!</div>'.'<br>';
			 }elseif(empty($edit['cep'])){
				$edit['cep']= strip_tags(trim($_POST['cep']));
				echo '<div class="alert alert-warning">O CEP do cliente é obrigatório!</div>'.'<br>';
			 }elseif(!email($edit['email'])){
				$edit['email']= strip_tags(trim($_POST['email']));
				echo '<div class="alert alert-warning">Desculpe o e-mail informado é inválido!</div>'.'<br>';
			 }elseif(!empty($edit['cnpj']) && !cnpj($edit['cnpj']) ){
					$edit['cnpj']= strip_tags(trim($_POST['cnpj']));
					echo '<div class="alert alert-warning">Desculpe o CNPJ informado é inválido!</div>'.'<br>';
			 }elseif(!empty($edit['cpf']) && !cpf($edit['cpf']) ){
					$edit['cpf']= strip_tags(trim($_POST['cpf']));
					echo '<div class="alert alert-warning">Desculpe o CPF informado é inválido!</div>'.'<br>';
				
		
			  }else{
				create('cadastro_visita',$edit);	
				header("Location: ".$_SESSION['url']);
		}
		
	}
		
	if(isset($_POST['atualizar'])){
	
		$edit['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		$edit['nome_fantasia']=strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
		
		$edit['cep']=strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
		$edit['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
		$edit['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$edit['complemento']=strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
		$edit['bairro']=strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
		$edit['cidade']=strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
		$edit['uf']=strip_tags(trim(mysql_real_escape_string($_POST['uf'])));
		$edit['referencia']=strip_tags(trim(mysql_real_escape_string($_POST['referencia'])));
		
		$edit['telefone']=strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
		$edit['celular']=strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
		$edit['contato']=strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
		$edit['email']=strip_tags(trim(mysql_real_escape_string($_POST['email'])));
		$edit['cnpj']=strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
		$edit['inscricao']=strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
		$edit['cpf']=strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
		
		$edit['empresa_atual'] = mysql_real_escape_string($_POST['empresa_atual']);
		$edit['consultor'] = strip_tags(trim(mysql_real_escape_string($_POST['consultor'])));
		$edit['atendente'] = strip_tags(trim(mysql_real_escape_string($_POST['atendente'])));
		$edit['indicacao'] = mysql_real_escape_string($_POST['indicacao']);

		$edit['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
		$edit['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
		$edit['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
		$edit['turno'] = mysql_real_escape_string($_POST['turno']);
		$edit['orc_equipamento'] = mysql_real_escape_string($_POST['orc_equipamento']);
		$edit['orc_quantidade'] = mysql_real_escape_string($_POST['orc_quantidade']);
		
		$edit['orc_valor_unitario']= mysql_real_escape_string($_POST['orc_valor_unitario']);
$edit['orc_valor_unitario']= str_replace(",",".",str_replace(".","",$edit['orc_valor_unitario']));
		
	$edit['orc_valor_extra']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_extra'])));
		$edit['orc_valor_extra'] = str_replace(",",".",str_replace(".","",$edit['orc_valor_extra']));
		
		$edit['orc_valor']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor'])));
		$edit['orc_valor'] = str_replace(",",".",str_replace(".","",$edit['orc_valor']));
		
		$edit['orc_forma_pag']= strip_tags(trim(mysql_real_escape_string($_POST['orc_forma_pag'])));
		
		$edit['orc_comodato']= strip_tags(trim(mysql_real_escape_string($_POST['orc_comodato'])));
		
		$edit['orc_observacao']= mysql_real_escape_string($_POST['orc_observacao']);
		$edit['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
		$edit['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
		
		$edit['data']= strip_tags(trim(mysql_real_escape_string($_POST['data'])));
		
		$edit['interacao']= date('Y/m/d H:i:s');
		$edit['usuario']	=  $_SESSION['autUser']['nome'];
		
		$edit['aprovacao_comercial']= strip_tags(trim($_POST['aprovacao_comercial']));
		$edit['aprovacao_comercial_observacao']= strip_tags(trim($_POST['aprovacao_comercial_observacao']));
		
		$edit['aprovacao_operacional']= strip_tags(trim($_POST['aprovacao_operacional']));
		$edit['rota']= strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
		$edit['hora']= strip_tags(trim(mysql_real_escape_string($_POST['hora'])));
	    $edit['aprovacao_operacional_observacao']= strip_tags($_POST['aprovacao_operacional_observacao']);
		
		$edit['aprovacao_juridico']= strip_tags(trim($_POST['aprovacao_juridico']));
		$edit['aprovacao_juridico_observacao']= strip_tags($_POST['aprovacao_juridico_observacao']);
		
		$edit['aprovacao_diretoria']= strip_tags(trim($_POST['aprovacao_diretoria']));
		$edit['aprovacao_diretoria_observacao']= strip_tags($_POST['aprovacao_diretoria_observacao']);
		
		if(empty($edit['atendente'])){
				echo '<div class="alert alert-warning">O atendente  é obrigatório!</div>'.'<br>';
			}elseif(empty($edit['nome'])){
				$edit['nome']= strip_tags(trim($_POST['nome']));
				echo '<div class="alert alert-warning">O Nome do cliente é obrigatório!</div>'.'<br>';
			 }elseif(empty($edit['cep'])){
				$edit['cep']= strip_tags(trim($_POST['cep']));
				echo '<div class="alert alert-warning">O CEP do cliente é obrigatório!</div>'.'<br>';
			 }elseif(!email($edit['email'])){
				$edit['email']= strip_tags(trim($_POST['email']));
				echo '<div class="alert alert-warning">Desculpe o e-mail informado é inválido!</div>'.'<br>';
			 }elseif(!empty($edit['cnpj']) && !cnpj($edit['cnpj']) ){
					$edit['cnpj']= strip_tags(trim($_POST['cnpj']));
					echo '<div class="alert alert-warning">Desculpe o CNPJ informado é inválido!</div>'.'<br>';
			 }elseif(!empty($edit['cpf']) && !cpf($edit['cpf']) ){
					$edit['cpf']= strip_tags(trim($_POST['cpf']));
					echo '<div class="alert alert-warning">Desculpe o CPF informado é inválido!</div>'.'<br>';
		 
		  }else{
			
			 $edit['inicio_contrato']= strip_tags($_POST['inicio_contrato']);
			
			update('cadastro_visita',$edit,"id = '$orcamentoId'");	
			header("Location: ".$_SESSION['url']);
		}
		
	}
		
	if(isset($_POST['enviar'])){
		
		if($edit['aprovacao_comercial']=="1"){
			
			if($edit['aprovacao_operacional']=="1"){
	
				$cad['interacao']= date('Y/m/d H:i:s');
				$cad['status']= 3;
				update('cadastro_visita',$cad,"id = '$orcamentoId'");	
				$consultorId = $edit['consultor'];
				$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");

				// email	
				$link = URL."/cliente/painel2.php?execute=suporte/orcamento/imprimir-proposta&orcamentoId=" . $orcamentoId;
				$link2 = URL."/orcamento-cancelado&orcamentoId=" . $orcamentoId;

				$assunto  = "Proposta Comercial : " . $cliente['nome'];

				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";


				$msg .= "Proposta Comercial<br /><br />";
				$msg .= "Para imprimir a proposta comercial completa clique no link IMPRIMIR PROPOSTA abaixo.<br/><br />";
				
				$msg .= "Para imprimir a proposta comercial completa clique no link IMPRIMIR PROPOSTA abaixo.<br/><br />";

				$msg .= "Proposta N&deg; : " . $edit['id'] . "<br />";
				$msg .= "Nome : " . $edit['nome'] . "<br />";
				$msg .= "Email : " . $edit['email'] . "<br />";
				$msg .= "Telefone : " . $edit['telefone'] . "<br />";

				$msg .= "Data do Orçamento : " . converteData($edit['orc_data']) . "<br />";

				$msg .= "Tipo de Resíduo : " . $edit['orc_residuo'] . "<br />";
				$msg .= "Freqüencia da Coleta : " . $edit['orc_frequencia'] . "<br />";
				$msg .= "Dia da Semana : " . $edit['orc_dia'] . "<br />";
				$msg .= "Tipo de Equipamento : " . $edit['orc_equipamento'] . "<br />";

				$msg .= "Valor Unitário : " . converteValor($edit['orc_valor_unitario']). "<br />";
				$msg .= "Valor Extra Unitário : " .  converteValor($edit['orc_valor_extra']) . "<br />";
				$msg .= "Valor Mensal R$ : " .  converteValor($edit['orc_valor']) . "<br />";

				$msg .= "Forma de Pagamento : " . $edit['orc_forma_pag'] . "<br />";
				$msg .= "Equipamento por Comodato :" . $edit['orc_comodato'] . "<br />";

				$msg .= "Observaçao da Coleta : " . nl2br($edit['orc_observacao']) . "<br /><br />";

				$msg .= SITENOME . "<br />";
				$msg .= "Consultor : " . $consultor['nome'] . "<br />";
				$msg .= "Email : " . $consultor['email'] . "<br />";
				$msg .= "Telefone : " . $consultor['telefone'] . "<br />";
				$msg .= "Data : " . date('d/m/Y'). "<br /><br />";
				$msg .= "<a href=" . $link . ">IMPRIMIR PROPOSTA</a> <br /><br />";
				
				$msg .= "Pense no ambiente antes de imprimir esta página. A natureza agradece!<br />";
				$msg .=  "</font>";

				enviaEmail($assunto,$msg,MAILUSER,SITENOME,$edit['email'],$edit['nome']);
				header("Location: ".$_SESSION['url']);
			  }else{ 
			   echo '<div class="alert alert-warning">Proposta ainda não liberada pelo operacional!</div>'.'<br>';
				echo $edit['aprovacao_operacional'];
			}
		 }else{ 
			echo '<div class="alert alert-warning">Proposta ainda não liberada pelo comercial!</div>'.'<br>';
			echo $edit['aprovacao_comercial'];
		}	
	}
		
	 
		
	if(isset($_POST['autorizarComercial'])){
		
		if($_POST['aprovacao_comercial']<>"3"){
			
			$cad['aprovacao_comercial']= strip_tags(trim($_POST['aprovacao_comercial']));
			$cad['aprovacao_comercial_observacao']= strip_tags(trim($_POST['aprovacao_comercial_observacao']));
			
			$cad['aprovacao_operacional']='3';
			
			$cad['interacao']= date('Y/m/d H:i:s');
			update('cadastro_visita',$cad,"id = '$orcamentoId'");	
			$consultorId = $edit['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");

			// email	
			if($cad['aprovacao_comercial']=='1'){
				$assunto  = "Proposta Autorizada : " . $cliente['nome'];
			}
			if($edit['aprovacao_comercial']=='2'){
				$assunto  = "Proposta Não Autorizada : " . $cliente['nome'];
			}

			$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
			$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";
			$msg .= "Proposta N&deg; : " . $edit['id'] . "<br />";
			$msg .= "Nome : " . $edit['nome'] . "<br />";
			$msg .= "Email : " . $edit['email'] . "<br />";
			$msg .=  "</font>";

			enviaEmail($assunto,$msg,MAILUSER,SITENOME,$consultor['email'],$consultor['nome']);
			header("Location: ".$_SESSION['url']);
		 }else{ 
			
			echo '<div class="alert alert-warning">Autorização ainda não marcada!</div>'.'<br>';
		}	
	}
		
	if(isset($_POST['autorizarOperacional'])){
		
		if($_POST['autorizarOperacional']!="3"  ){
			
			$cad['aprovacao_operacional']= strip_tags(trim($_POST['aprovacao_operacional']));
			$cad['rota']= strip_tags(trim(mysql_real_escape_string($_POST['rota'])));
			$cad['hora']= strip_tags(trim(mysql_real_escape_string($_POST['hora'])));
			$cad['aprovacao_operacional_observacao']= strip_tags($_POST['aprovacao_operacional_observacao']);
			
			$cad['interacao']= date('Y/m/d H:i:s');
		
			update('cadastro_visita',$cad,"id = '$orcamentoId'");	
			$consultorId = $edit['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");

				// email	
				if($cad['aprovacao_operacional']=='1'){
					$assunto  = "Rota Autorizada : " . $cliente['nome'];
				}
				if($cad['aprovacao_operacional']=='2'){
					$assunto  = "Rota Não Autorizada : " . $cliente['nome'];
				}

				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";
				$msg .= "Proposta N&deg; : " . $edit['id'] . "<br />";
				$msg .= "Nome : " . $edit['nome'] . "<br />";
				$msg .= "Email : " . $edit['email'] . "<br />";
				$msg .=  "</font>";

				enviaEmail($assunto,$msg,MAILUSER,SITENOME,$consultor['email'],$consultor['nome']);
				header("Location: ".$_SESSION['url']);
			}else{ 
			
			echo '<div class="alert alert-warning">Autorização Operacional ainda não marcada!</div>'.'<br>';
		}	
	}
		
	if(isset($_POST['aprovacao-autorizar-juridico'])){
		
		if($_POST['aprovacao_juridico']=="3" ){
			$pdf='../uploads/contrato-assinados/'.$edit['id'].'.pdf';
			if(file_exists($pdf)){
				 $cad['aprovacao_juridico']= strip_tags(trim($_POST['aprovacao_juridico']));
				 $cad['aprovacao_juridico_observacao']= strip_tags($_POST['aprovacao_juridico_observacao']);
				 $cad['inicio_contrato']= strip_tags($_POST['inicio_contrato']);
				 $cad['status'] = 4;
				 $cad['interacao']= date('Y/m/d H:i:s');
				 update('cadastro_visita',$cad,"id = '$orcamentoId'");	
				 header("Location: ".$_SESSION['url']);
			}else{ 
				echo '<div class="alert alert-warning">Contrato não Anexado!</div>'.'<br>';
			}
		}else{ 
			
			echo '<div class="alert alert-warning">Autorização Juridica ainda não marcada!</div>'.'<br>';
		}	
	}	
		
		
	if(isset($_POST['autorizarJuridico'])){
		
		if($_POST['aprovacao_juridico']=="1"  ){
	 
			/* Informa o nível dos erros que serão exibidos */
			 // error_reporting(E_ALL);

			/* Habilita a exibição de erros */
			  //ini_set("display_errors", 1);
  
		    $cad['aprovacao_juridico']= strip_tags(trim($_POST['aprovacao_juridico']));
		    $cad['aprovacao_juridico_observacao']= strip_tags($_POST['aprovacao_juridico_observacao']);
			$cad['interacao']= date('Y/m/d H:i:s');
			update('cadastro_visita',$cad,"id = '$orcamentoId'");	
			
			$consultorId = $edit['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
 
			if($cad['aprovacao_juridico']=='1'){
					
				$cli['email'] = strip_tags(trim(mysql_real_escape_string( $edit['email'])));
				$cli['email_financeiro'] =strip_tags(trim(mysql_real_escape_string($edit['email'])));
				$cli['nome']= strip_tags(trim(mysql_real_escape_string($edit['nome'])));
				$cli['cep'] = strip_tags(trim(mysql_real_escape_string($edit['cep'])));
				$cli['endereco'] = strip_tags(trim(mysql_real_escape_string($edit['endereco'])));
				$cli['numero'] = strip_tags(trim(mysql_real_escape_string($edit['numero'])));
				$cli['complemento']= strip_tags(trim(mysql_real_escape_string($edit['complemento'])));
			 
				$cli['bairro'] = strip_tags(trim(mysql_real_escape_string($edit['bairro'])));
				$cli['cidade'] = strip_tags(trim(mysql_real_escape_string($edit['cidade'])));
				$cli['uf'] = strip_tags(trim(mysql_real_escape_string($edit['uf'])));
				 
				$cli['referencia'] = strip_tags(trim(mysql_real_escape_string($edit['referencia'])));
				if(empty($cli['referencia'])){
					$cli['referencia'] = strip_tags(trim(mysql_real_escape_string($edit['orc_observacao'])));
			
				}
			
				$cli['celular'] = strip_tags(trim(mysql_real_escape_string($edit['celular'])));
		 
			    $cli['telefone']= strip_tags(trim(mysql_real_escape_string($edit['telefone'])));
				$cli['contato']= strip_tags(trim(mysql_real_escape_string($edit['contato'])));
				$cli['cnpj'] = strip_tags(trim(mysql_real_escape_string($edit['cnpj'])));
				$cli['cpf'] = strip_tags(trim(mysql_real_escape_string($edit['cpf'])));
				if(empty($cli['cpf'])){
					$cli['cpf'] ="-";
				}
			 
				$cli['data']= date('Y/m/d H:i:s');
			    print_r($cli);
				create('cliente',$cli);	
				
				$clienteId = $ultimoId;
				$con['id_cliente'] = $clienteId;
				$con['atendente'] = $edit['atendente'];
				$con['consultor'] = $edit['consultor'];
				$con['pos_venda'] = $edit['consultor'];
				$con['indicacao'] = $edit['indicacao'];
			 	
				$con['aprovacao']= date('Y/m/d'); 
			    $con['inicio'] = strip_tags(trim(mysql_real_escape_string($edit['inicio_contrato'])));
				$con['interacao']= date('Y/m/d H:i:s');
				$con['tipo'] = 2;
				$con['status'] = 4;
				
				 print_r($con);

				create('contrato',$con);	

				$contratoId=$ultimoId;
 
				$interacao='Contrato cadastrado';
				interacao($interacao,$contratoId);
					
				$assunto  = "Juridico Autorizada : " . $cliente['nome'];
					
				}
				if($edit['aprovacao_juridico']=='2'){
					$assunto  = "Juridico Não Autorizada : " . $cliente['nome'];
				}

				$msg = "<font size='2 px' face='Verdana, Geneva, sans-serif' color='#009c89'>";
				$msg .="<img src='https://www.cleansistemas.com.br/site/images/header-logo.png'><br/><br/><br/>";
				$msg .= "Proposta N&deg; : " . $edit['id'] . "<br />";
				$msg .= "Nome : " . $edit['nome'] . "<br />";
				$msg .= "Email : " . $edit['email'] . "<br />";
				$msg .=  "</font>";
			 
				enviaEmail($assunto,$msg,MAILUSER,SITENOME,$consultor['email'],$consultor['nome']);
			
				 header("Location: ".$_SESSION['url']);
			
				// header('Location: painel.php?execute=suporte/contrato/contrato-editar&contratoEditar='.$contratoId);
			
			}else{ 
			
			echo '<div class="alert alert-warning">Aprovação Juridica ainda não marcada!</div>'.'<br>';
		}	
	}	
		
		
	if(isset($_POST['autorizarDiretoria'])){
		
		if($_POST['autorizarOperacional']!="3"  ){
			
			$cad['aprovacao_diretoria']= strip_tags(trim($_POST['aprovacao_diretoria']));
			$cad['aprovacao_diretoria_observacao']= strip_tags($_POST['aprovacao_diretoria_observacao']);
			$cad['interacao']= date('Y/m/d H:i:s');
			update('cadastro_visita',$cad,"id = '$orcamentoId'");	
			$consultorId = $edit['consultor'];
			$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");

			 
			}else{ 
			
			echo '<div class="alert alert-warning">Autorização ainda não marcada!</div>'.'<br>';
		}	
	}	
		
			
	if(isset($_POST['cancelar'])){
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['status'] = 17;
		update('cadastro_visita',$cad,"id = '$orcamentoId'");	
		header("Location: ".$_SESSION['url']);
	}
		
	if(isset($_POST['deletar'])){
		delete('cadastro_visita',"id = '$orcamentoId'");	
		header("Location: ".$_SESSION['url']);
	}
		
		
	 function extensao($arquivo){
			$arquivo = strtolower($arquivo);
			$explode = explode(".", $arquivo);
			$arquivo = end($explode);
			return ($arquivo);
	 }

		
	if(isset($_POST['pdf-contrato-assinados'])){
		$arquivo = $_FILES['arquivo'];
		$tamanhoPermitido = 1024 * 1024 * 2; // 2Mb
		$diretorio = "../uploads/contrato-assinados/";
		if( $arquivo['error'] == UPLOAD_ERR_OK ){
				$extensao = extensao($arquivo['name']);
				if(in_array( $extensao, array("pdf") ) ){
					if ( $arquivo['size'] > $tamanhoPermitido ){
				        echo '<div class="alert alert-info">O arquivo enviado é muito grande!</div>'.'<br>';
					 }else{
						echo '<div class="alert alert-info">UPLOAD!</div>'.'<br>';	
						$novo_nome  = $edit['id'].".".$extensao;
						$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);
						if($enviou){
						  echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
						}else{
						  echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
						}
					}
			}else{
				echo '<div class="alert alert-info">Somente arquivos PDF são permitidos</div>'.'<br>';
				}
			}else{
				echo '<div class="alert alert-info">Você deve enviar um arquivo!</div>'.'<br>';
		}
	}
	
	if(isset($_POST['pdf-contrato-social'])){
		
		$arquivo = $_FILES['arquivo'];
		$tamanhoPermitido = 1024 * 1024 * 2; // 2Mb
		$diretorio = "../uploads/contrato-social/";
		if( $arquivo['error'] == UPLOAD_ERR_OK ){
			$extensao = extensao($arquivo['name']);
			if(in_array( $extensao, array("pdf") ) ){
				if ( $arquivo['size'] > $tamanhoPermitido ){
				      echo '<div class="alert alert-info">O arquivo enviado é muito grande!</div>'.'<br>';
				}else{
					echo '<div class="alert alert-info">UPLOAD!</div>'.'<br>';	
					$novo_nome  = $edit['id'].".".$extensao;
					$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);
					if($enviou){
						  echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
					}else{
						  echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
					}
				}
			}else{
					echo '<div class="alert alert-info">Somente arquivos PDF são permitidos</div>'.'<br>';
			}
		 }else{
			echo '<div class="alert alert-info">Você deve enviar um arquivo!</div>'.'<br>';
		}
	}
		
	if(isset($_POST['pdf-contrato-identidade-socio'])){
		
		$arquivo = $_FILES['arquivo'];
		$tamanhoPermitido = 1024 * 1024 * 2; // 2Mb
		$diretorio = "../uploads/contrato-identidade-socio/";
		if( $arquivo['error'] == UPLOAD_ERR_OK ){
			$extensao = extensao($arquivo['name']);
			if(in_array( $extensao, array("pdf") ) ){
				if ( $arquivo['size'] > $tamanhoPermitido ){
				      echo '<div class="alert alert-info">O arquivo enviado é muito grande!</div>'.'<br>';
				}else{
					echo '<div class="alert alert-info">UPLOAD!</div>'.'<br>';	
					$novo_nome  = $edit['id'].".".$extensao;
					$enviou = move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);
					if($enviou){
						  echo '<div class="alert alert-info">Upload de arquivo com sucesso!</div>'.'<br>';
					}else{
						  echo '<div class="alert alert-info">Falha ao enviar o arquivo!</div>'.'<br>';
					}
				}
			}else{
					echo '<div class="alert alert-info">Somente arquivos PDF são permitidos</div>'.'<br>';
			}
		 }else{
			echo '<div class="alert alert-info">Você deve enviar um arquivo!</div>'.'<br>';
		}
	}
		
	
		
	?>

      <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

        <div class="box-header with-border">
              <h3 class="box-title">Dados da Visita</h3>
         </div><!-- /.box-header -->
                
		<div class="box-body">
        	<div class="row">
          
            <div class="form-group col-xs-12 col-md-1"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
            <div class="form-group col-xs-12 col-md-2">  
               	<label>Interação</label>
   				<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div>
         
         	 <div class="form-group col-xs-12 col-md-2">
               <label>Data do Cadastro </label>
               <input name="data" type="text" value="<?php echo date('d/m/Y',strtotime($edit['data']));?>" class="form-control" readonly/>
            </div>
				
			<div class="form-group col-xs-1">
                <label>Ligação</label>
					<select name="ligacao" class="form-control" >
					  <option value="">...</option>
					  <option <?php if($edit['ligacao'] == '1') echo' selected="selected"';?> value="1">Sim</option>
					  <option <?php if($edit['ligacao'] == '0') echo' selected="selected"';?> value="0">Não</option>
					 </select>
           		 </label>
       		</div>
			
			<div class="form-group col-xs-1">
                <label>Atendida</label>
					<select name="atendida" class="form-control" >
					  <option value="">...</option>
					  <option <?php if($edit['atendida'] == '1') echo' selected="selected"';?> value="1">Sim</option>
					  <option <?php if($edit['atendida'] == '0') echo' selected="selected"';?> value="0">Não</option>
					 </select>
           		 </label>
       		</div>
				
			 
             <div class="form-group col-xs-12 col-md-5">
              <label>Empresa Atual</label>
              <select name="empresa_atual" <?php echo $disabled;?> class="form-control"/>
				<option value="">Selecione empresa atual</option>
					<?php 
					$leitura = read('cadastro_visita_empresa_atual',"WHERE id ORDER BY nome ASC");
					if(!$leitura){
								echo '<option value="">Nao temos empresa no momento</option>';	
					}else{
						foreach($leitura as $mae):
						if($edit['empresa_atual'] == $mae['id']){
							echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
						 }else{
							echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
						}
						endforeach;	
					}
					?> 
				 </select>
              </div>
          
           
            <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome</label>
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>" <?php echo $readonly;?>/>
            </div>
             <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome Fantasia</label>
                  <input name="nome_fantasia"  class="form-control" type="text" value="<?php echo $edit['nome_fantasia'];?>" <?php echo $readonly;?>/>
            </div>
            
     		<div class="form-group col-xs-12 col-md-2">
               <label>CEP </label>
                <input name="cep" id="cep" value="<?php echo $edit['cep'];?>" class="form-control" <?php echo $readonly;?>/>  
           </div>
			<div class="form-group col-xs-12 col-md-6">   
                <label>Endereco</label>
                <input name="endereco" id="endereco" value="<?php echo $edit['endereco'];?>" class="form-control" <?php echo $readonly;?>/>  
            </div>
           <div class="form-group col-xs-12 col-md-2">   
                <label>Numero </label>
                <input name="numero"  value="<?php echo $edit['numero'];?>" class="form-control" <?php echo $readonly;?>/>  
            </div> 
             <div class="form-group col-xs-12 col-md-2">   
                <label>Complemento </label>
                <input name="complemento"  value="<?php echo $edit['complemento'];?>" class="form-control" <?php echo $readonly;?>/> 
            </div> 
      		<div class="form-group col-xs-12 col-md-3">   
                <label>Bairro</label>
                <input name="bairro" id="bairro" value="<?php echo $edit['bairro'];?>" class="form-control" <?php echo $readonly;?>/>           					
            </div> 
			<div class="form-group col-xs-12 col-md-2">   
                <label>Cidade</label>
                <input name="cidade" id="cidade" value="<?php echo $edit['cidade'];?>" class="form-control" <?php echo $readonly;?>/>           					
            </div> 
			  <div class="form-group col-xs-12 col-md-1">   
                <label>UF </label>
                <input name="uf" id="uf" value="<?php echo $edit['uf'];?>"  class="form-control"  <?php echo $readonly;?>/>       
            </div>

           <div class="form-group col-xs-12 col-md-6">   
                <label>Referencia </label>
                <input name="referencia" value="<?php echo $edit['referencia'];?>" class="form-control" <?php echo $readonly;?>/>         
           </div>
		
		
            <div class="form-group col-xs-12 col-md-3">   
                <label>Telefone (Fixo)</label>
                <input name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control" <?php echo $readonly;?>/>          					
            </div> 
			
			  <div class="form-group col-xs-12 col-md-3">   
                <label>Celular</label>
                <input name="celular" value="<?php echo $edit['celular'];?>" class="form-control" <?php echo $readonly;?>/>          					
            </div> 
            <div class="form-group col-xs-12 col-md-3">   
                <label>Contato</label>
                <input name="contato"  value="<?php echo $edit['contato'];?>" class="form-control" <?php echo $readonly;?>/>          					
            </div> 
             <div class="form-group col-xs-12 col-md-3">   
                <label>Email</label>
                <input name="email"  value="<?php echo $edit['email'];?>" class="form-control" <?php echo $readonly;?>/>           					
            </div> 

           <div class="form-group col-xs-12 col-md-4">  
                <label>CPF </label>
                <input name="cpf" type="text" id="cpf"  value="<?php echo $edit['cpf'];?>"  class="form-control" <?php echo $readonly;?>/>
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>CNPJ </label>
                <input type="text" name="cnpj" id="cnpj" value="<?php echo $edit['cnpj'];?>"   class="form-control"   <?php echo $readonly;?>/>
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
             <label>Inscrição</label>
             <input type="text" name="inscricao"  value="<?php echo $edit['inscricao'];?>" class="form-control" <?php echo $readonly;?>/>
           </div> 

    
         	</div>  <!-- /.row -->
        </div>  <!-- /.box-body -->
                  
                  

		<div class="box-header with-border">
			<h3 class="box-title">Dados da Orçamento</h3>
		</div>
        <!-- /.box-header -->

          <div class="box-body">
             <div class="row">
                            <div class="form-group col-xs-12 col-md-4">
                                <label>Atendente </label>
                                <select name="atendente" class="form-control" <?php echo $disabled;?> />
										<option value="">Selecione o Atendente</option>
											<?php 
												$leitura = read('contrato_atendente',"WHERE id ORDER BY nome ASC");
												if(!$leitura){
													echo '<option value="">Nao temos atendente no momento</option>';	
												}else{
													foreach($leitura as $mae):
													   if($edit['atendente'] == $mae['id']){
															echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
														 }else{
															echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
														}
													endforeach;	
												}
											?> 
								 </select>
                            </div>
                            <div class="form-group col-xs-12 col-md-4">
                                <label>Consultor </label>
                                <select name="consultor" class="form-control" <?php echo $disabled;?> />
                            <option value="">Selecione o Consultor</option>
                                <?php 
                                    $leitura = read('contrato_consultor',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos consultor no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['consultor'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                     			 </select>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Indicaçao</label>
                                <select name="indicacao" class="form-control" <?php echo $disabled;?> />
                            <option value="">Selecione o Indicaçao</option>
                                <?php 
                                    $leitura = read('contrato_indicacao',"WHERE id ORDER BY nome ASC");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos indicacao no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['indicacao'] == $mae['id']){
                                                echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      			</select>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Data do Orçamento</label>
                                <input type="date" name="orc_data" value="<?php echo $edit['orc_data'];?>" class="form-control" <?php echo $readonly;?>/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Hora</label>
                                <select name="orc_hora" <?php echo $disabled;?> class="form-control"/>
                            <option value="">hora</option>
                                <?php 
                                    $leitura = read('contrato_hora',"WHERE nome");
                                    if(!$leitura){
                                        echo '<option value="">Nao temos hora no momento</option>';	
                                    }else{
                                        foreach($leitura as $mae):
                                           if($edit['orc_hora'] == $mae['nome']){
                                                echo '<option value="'.$mae['nome'].'"selected="selected">'.$mae['nome'].'</option>';
                                             }else{
                                                echo '<option value="'.$mae['nome'].'">'.$mae['nome'].'</option>';
                                            }
                                        endforeach;	
                                    }
                                ?> 
                      		</select>
                            </div>
                            <div class="form-group col-xs-12 col-md-4">
                                <label>Limite de Horário</label>
                                <input type="text" name="orc_limite" value="<?php echo $edit['orc_limite'];?>" class="form-control" <?php echo $readonly;?>/>
                            </div>
                            
				  </div>  <!-- /.row -->
			</div>  <!-- /.box-body -->
                  

			<div class="box-header with-border">
				   <h3 class="box-title">Dados da Orçamento</h3>
			</div>
             <!-- /.box-header -->

          	 <div class="box-body">
                  <div class="row">

                            <div class="form-group col-xs-12 col-md-12">
                                <label>Tipo de Resíduo</label>
                                <input type="text" name="orc_residuo" value="<?php echo $edit['orc_residuo'];?>" class="form-control" <?php echo $readonly;?>/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Freqüencia da Coleta</label>
                                <input type="text" name="orc_frequencia" value="<?php echo $edit['orc_frequencia'];?>" class="form-control"<?php echo $readonly;?>/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Dia da Semana</label>
                                <input type="text" name="orc_dia" value="<?php echo $edit['orc_dia'];?>" class="form-control" <?php echo $readonly;?>/>
                            </div>
					  
					 		 <div class="form-group col-xs-12 col-md-4">  
							<label>Turno </label>
							<select name="turno" class="form-control" <?php echo $readonly;?> >
								<option value="">Selecione o Turno</option>
								<option <?php if($edit['turno'] == '1') echo' selected="selected"';?> value="1">Manhã</option>
								<option <?php if($edit['turno'] == '2') echo' selected="selected"';?> value="2">Tarde</option>
								<option <?php if($edit['turno'] == '3') echo' selected="selected"';?> value="3">Noite</option>
							 </select>
						</div>  

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Tipo de Equipamento </label>
                                <input type="text" name="orc_equipamento" value="<?php echo $edit['orc_equipamento'];?>" class="form-control" <?php echo $readonly;?>/>
                            </div>
                            
                              <div class="form-group col-xs-12 col-md-3">
                                <label>Quantidade Mínima Diária </label>
                                <input type="text" name="orc_quantidade" value="<?php echo $edit['orc_quantidade'];?>" class="form-control" <?php echo $readonly;?>/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Valor Unitário R$ </label>
                                <input type="text" name="orc_valor_unitario" value="<?php echo converteValor($edit['orc_valor_unitario']) ;?>" class="form-control" <?php echo $readonly;?>/>
                            </div>

							 <div class="form-group col-xs-12 col-md-3">
								  <label>Valor Extra Unitário R$  </label>
								   <input type="text" name="orc_valor_extra" value="<?php echo converteValor($edit['orc_valor_extra']);?>" class="form-control" <?php echo $readonly;?>/>
							 </div>

							<div class="form-group col-xs-12 col-md-3">
                                <label>Valor Mensal R$ </label>
                                <input type="text" name="orc_valor" value="<?php echo converteValor($edit['orc_valor']) ;?>" class="form-control" <?php echo $readonly;?>/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Equipamento por Comodato R$  </label>
                                <input type="text" name="orc_comodato" value="<?php echo $edit['orc_comodato'] ;?>" class="form-control" <?php echo $readonly;?>/>
                            </div>

                            <div class="form-group col-xs-12 col-md-6">
                                <label>Forma de Pagamento </label>
                                <input type="text" name="orc_forma_pag" value="<?php echo $edit['orc_forma_pag'];?>" class="form-control" <?php echo $readonly;?>/>
                            </div>
                            
                                  
						  <div class="form-group col-xs-12 col-md-12">
							  <label>Observação</label>
								<textarea name="orc_observacao" rows="3" cols="80" class="form-control" <?php echo $readonly;?>/> <?php echo htmlspecialchars($edit['orc_observacao']);?></textarea>
						 </div>  

                           <div class="form-group col-xs-12 col-md-12">
							  <label>Motivo Cancelamento</label>
							  <select name="motivo_cancelamento" <?php echo $disabled;?> class="form-control"/>
								<option value="">Selecione motivo cancelamento</option>
									<?php 
									$leitura = read('cadastro_visita_motivo_cancelamento',"WHERE id ORDER BY nome ASC");
									if(!$leitura){
										echo '<option value="">Nao temos motivo no momento</option>';	
									}else{
										foreach($leitura as $mae):
										if($edit['motivo_cancelamento'] == $mae['id']){
											echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
										 }else{
											echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
										}
										endforeach;	
									}
									?> 
								 </select>
							  </div>
          

                    </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->
                       
                 <div class="box-header with-border">
                        <h3 class="box-title">Liberações</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                       <div class="row">  
                      
							 <div class="form-group col-xs-3">
								<label>Aprovação Comecial</label>
								<select name="aprovacao_comercial" class="form-control" <?php echo $liberacaoComercial;?> >
								 <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_comercial'] == '1') echo' selected="selected"';?> value="1" >Aprovado</option>
								  <option <?php if($edit['aprovacao_comercial'] == '2') echo' selected="selected"';?> value="2" >Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_comercial'] == '3') echo' selected="selected"';?> value="3">Solicitar Aprovação</option>
								 </select>
							</div><!-- /.row -->
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_comercial_observacao" value="<?php echo $edit['aprovacao_comercial_observacao'];?>" class="form-control" <?php echo $liberacaoComercial;?>  />
                            </div>
						   
						
                        </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->    

                     
                  <div class="box-body">
                       <div class="row">  
						   
                             <div class="form-group col-xs-3">
								<label>Liberação Operacional</label>
								<select name="aprovacao_operacional" class="form-control" <?php echo $liberacaoOperacional;?> >
								  <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_operacional'] == '1') echo' selected="selected"';?> value="1" <?php echo $liberacaoOperacional;?>>Liberado</option>
								  <option <?php if($edit['aprovacao_operacional'] == '2') echo' selected="selected"';?> value="2" <?php echo $liberacaoOperacional;?>>Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_operacional'] == '3') echo' selected="selected"';?> value="3">Solicitar Rota</option>
								 </select>
							</div><!-- /.row -->
                           
                         <div class="form-group col-xs-12 col-md-2">  
                               <label>Rota </label>
						  <select name="rota"  class="form-control"  <?php echo $liberacaoOperacional;?>>
									<option value="">Selecione Rota</option>
										<?php 
											$leitura = read('contrato_rota',"WHERE id ORDER BY nome ASC");
											if(!$leitura){
												echo '<option value="">Nao temos status no momento</option>';	
											}else{
												foreach($leitura as $mae):
												   if($edit['rota'] == $mae['id']){
														echo '<option value="'.$mae['id'].'"selected="selected">'.$mae['nome'].'</option>';
													 }else{
														echo '<option value="'.$mae['id'].'">'.$mae['nome'].'</option>';
													}
												endforeach;	
											}
										?> 
							  </select>
							</div>
                            
                             <div class="form-group col-xs-12 col-md-2">
                                <label>Hora </label>
                                <input type="text" name="hora" value="<?php echo $edit['hora'];?>" class="form-control" <?php echo $liberacaoOperacional;?>  />
                            </div>
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_operacional_observacao" value="<?php echo $edit['aprovacao_operacional_observacao'];?>" class="form-control" <?php echo $liberacaoOperacional;?>  />
                            </div>
			 
					       </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->    
                       
			<div class="box-body">
                  <div class="row">  
                      
							 <div class="form-group col-xs-3">
								<label>Autorização do Juridico</label>
								<select name="aprovacao_juridico" class="form-control" <?php echo $liberacaoJuridico;?> >
								 <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_juridico'] == '1') echo' selected="selected"';?> value="1" >Autorizado</option>
								  <option <?php if($edit['aprovacao_juridico'] == '2') echo' selected="selected"';?> value="2" >Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_juridico'] == '3') echo' selected="selected"';?> value="3">Solicitar Autorização</option>
								 </select>
							</div><!-- /.row -->
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_juridico_observacao" value="<?php echo $edit['aprovacao_juridico_observacao'];?>" class="form-control"  />
                            </div>
		                       
                        </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->    
					  
					  
			<div class="box-body">
                  <div class="row">  
                      
							 <div class="form-group col-xs-3">
								<label>Autorização Diretoria - Valores Fora da Tabela</label>
								<select name="aprovacao_diretoria" class="form-control" <?php echo $liberacaoDiretoria;?> >
								 <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_diretoria'] == '1') echo' selected="selected"';?> value="1" >Autorizado</option>
								  <option <?php if($edit['aprovacao_diretoria'] == '2') echo' selected="selected"';?> value="2" >Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_diretoria'] == '3') echo' selected="selected"';?> value="3">Solicitar Autorização</option>
								 </select>
							</div><!-- /.row -->
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_diretoria_observacao" value="<?php echo $edit['aprovacao_diretoria_observacao'];?>" class="form-control" <?php echo $liberacaoDiretoria;?>  />
                            </div>
		                       
                        </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->    

   				<div class="box-header with-border">
                        <h3 class="box-title">Inicio de Contrato</h3>
                 </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                       <div class="row">  
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-2">
                                <label>Data do Inicio do Contrato </label>
                                <input type="date" name="inicio_contrato" value="<?php echo $edit['inicio_contrato'];?>" class="form-control"  />
                            </div>
		                       
                        </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->    
					  
 
               <div class="box-footer">
                  <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>

                <?php 
        
               if($acao=="atualizar"){
				   
                echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
				 
                }
				   
				if($acao=="aprovar"){
					echo '<input type="submit" name="aprovacao-autorizar-juridico" value="Aprovar Orçamento e Autorização Jurídico" class="btn btn-success" />';
                }
	
				if($acao=="autorizarComercial"){
                  echo '<input type="submit" name="autorizarComercial" value="Autorizar Proposta" class="btn btn-success" />';
                }
				
				if($acao=="autorizarOperacional"){
                  echo '<input type="submit" name="autorizarOperacional" value="Autorizar Rota" class="btn btn-primary" />';
                }
				   
				if($acao=="autorizarJuridico"){
                  echo '<input type="submit" name="autorizarJuridico" value="Autorização do Juridico & Contrato Aprovado" class="btn btn-success" />';
                }
				   
				if($acao=="autorizarDiretoria"){
                  echo '<input type="submit" name="autorizarDiretoria" value="Autorização Diretoria" class="btn btn-success" />';
                }
				
				if($acao=="enviar"){
                  echo '<input type="submit" name="enviar" value="Enviar Email" class="btn btn-primary" />';
                }
				
                if($acao=="cancelar"){
					
                    echo '<input type="submit" name="cancelar" value="Cancelar" class="btn btn-danger" />';
					
				 	echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
            
                }
				   
				 if($acao=="deletar"){
                    echo '<input type="submit" name="deletar" value="Deletar" class="btn btn-danger" />';
                }
                 
				if($acao=="baixar"){
					echo '<input type="submit" name="cancelar" value="Cancelar" class="btn btn-danger" />';
					
					echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
                }
				   
				if($acao=="desfazer"){
					echo '<input type="submit" name="desfazer" value="Desfazer" class="btn btn-success" />';
	
                }
				   
                if($acao=="cadastrar"){
                   echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />'; 
                }
					
			 ?>

              </div>
              <!-- /.box-footer -->
           </form>

         </div>
       </div>
   </section><!-- /.content -->

 
  <section class="content">
	  
  	<div class="box box-warning">
     	 <div class="box-body">
             <div class="box-header">
            
				 <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&visitaId=<?PHP echo $orcamentoId; ?>" class="btnnovo">
					<img src="../admin/ico/novo.png" alt="Criar Novo" title="Criar Novo" class="tip" />
				 </a>	
				 <small> Negociação  </small>
          	</div><!-- /box-tools-->
         
			 <?php 
      		
             $leitura = read('cadastro_visita_negociacao',"WHERE id AND id_visita = '$orcamentoId' ORDER BY id ASC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Id</td>
                        <td align="center">Data</td>
						<td align="center">Retorno</td>
                        <td align="center">Descricao</td>
                        <td align="center">Consultor</td>
						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
                                echo '<td>'.$mostra['id'].'</td>';
                               echo '<td align="center">'.date('d/m/Y H:i:s',strtotime($mostra['interacao'])).'</td>';
								echo '<td>'.converteData($mostra['retorno']).'</td>';
                                echo '<td>'.$mostra['descricao'].'</td>';
								$consultorId = $mostra['consultor'];
								$consultor = mostra('contrato_consultor',"WHERE id ='$consultorId '");
								echo '<td>'.$consultor['nome'].'</td>';
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png" alt="Deletar" title="Deletar" class="tip" />
                                        </a>
                                      </td>';  
								
								echo '<td align="center">
                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaBaixar='.$mostra['id'].'">
                                            <img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
                                        </a>
                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
 	
		 </div>
      </div>
 
		<div class="box box-warning">
     	 <div class="box-body">
            
            	
	   <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				 <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
						 <div class="file-field">
							<div class="btn btn-default btn-sm">
								
							  <input type="file" name="arquivo">
								
							<br>
								
						 	 <input type="submit" name="pdf-contrato-assinados" value="Upload Contrato Assinado PDF" class="btn btn-primary" />
								
							</div>
						</div>
					</form>
				  </div> 
				
				<br>
				<br>
				<br>
				<br>
				<br>
				
				  <div class="form-group pull-right">
					  
					<?php
						$pdf='../uploads/contrato-assinados/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/contrato-assinados/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png "title="contrato-assinados" />
								</a>';	
						}
					?>
					  
					   <label>Contrato Assinado PDF</label>
				</div> 
	 
			</div>
		 </div>
		</div>

	   <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
						 <div class="file-field">
							<div class="btn btn-default btn-sm">
								
							  <input type="file" name="arquivo">
							
							  <br>
								
						 	  <input type="submit" name="pdf-contrato-social" value="Upload Contrato social PDF" class="btn btn-primary" />
								
							</div>
						</div>
					</form>
				  </div> 
				
				<br>
				<br>
				<br>
				<br>
				<br>
				
					<div class="form-group pull-right">
						 
					<?php
						$pdf='../uploads/contrato-social/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/contrato-social/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="contrato-social" />
								</a>';	
						}
					?>
						
						  <label>Contrato Social PDF </label>
				</div> 
	
			</div>
		 </div>
		</div>
	
	
	   <div class="col-md-4">   
          <div class="box">
         	<div class="box-header">
				
				  <div class="form-group pull-left">
					<form name="form" action="" method="post" enctype="multipart/form-data">
						 <div class="file-field">
							<div class="btn btn-default btn-sm">
								
							  <input type="file" name="arquivo">
							
							  <br>
								
						 	  <input type="submit" name="pdf-contrato-identidade-socio" value="Upload contrato-identidade-socio PDF" class="btn btn-primary" />
								
							</div>
						</div>
					</form>
				  </div> 
				
				<br>
				<br>
				<br>
				<br>
				<br>
				
					<div class="form-group pull-right">
						 
					<?php
						$pdf='../uploads/contrato-identidade-socio/'.$edit['id'].'.pdf';

						if(file_exists($pdf)){

							echo '<a href="../uploads/contrato-identidade-socio/'.$edit['id'].'.pdf" target="_blank">
									<img src="ico/pdf.png" title="Identidade Sócio" />
								</a>';	
						}
					?>
						
						  <label>Identidade Sócio PDF </label>
				</div> 
	
			</div>
		 </div>
		</div>
			 
    </div>
      </div>
	  
  	<div class="box box-warning">
     	 <div class="box-body">
             <div class="box-header">
				 
  <a href="painel.php?execute=suporte/orcamento/orcamento-cobranca-editar&clienteId=<?PHP echo $orcamentoId ?>" class="btnnovo">    
			  	<img src="../admin/ico/novo.png" title="Criar Novo" />
             	</a>	
             <small> Dados da Cobrança </small>
				
          	</div><!-- /box-header-->
         
			 <?php 
      		
       $leitura = read('cadastro_visita_cobranca',"WHERE id AND id_cliente= '$orcamentoId' ORDER BY id DESC");
                  if($leitura){
					echo '<table class="table table-hover">	 
						<tr class="set">
                        <td align="center">Nome</td>
                        <td align="center">Endereço</td>
						<td align="center">Bairro</td>
                        <td align="center">Cidade</td>
  						<td align="center" colspan="5">Gerenciar</td>
                    </tr>';
                       foreach($leitura as $mostra):
                            echo '<tr>';
					  
                                echo '<td>'.$mostra['nome'].'</td>';
                                echo '<td>'.$mostra['endereco'].'</td>';
					  			echo '<td>'.$mostra['bairro'].'</td>';
								echo '<td>'.$mostra['cidade'].'</td>';
					  
								echo '<td align="center">
                                        <a href="painel.php?execute=../admin/suporte/orcamento/orcamento-cobranca-editar&clienteEditar='.$mostra['id'].'">
                                            <img src="../admin/ico/editar.png" title="Editar"/>
                                        </a>
                                      </td>';
  
								echo '<td align="center">
                                        <a href="painel.php?execute=../admin/suporte/orcamento/orcamento-cobranca-editar&clienteDeletar='.$mostra['id'].'">
                                            <img src="../admin/ico/excluir.png" title="Deletar"/>
                                        </a>
                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
 	
	     </div>
      </div>
 
  	<div class="box box-warning">
     	 <div class="box-body">
        	<?php
             echo '<p align="center">'.$edit['nome'].', '.$edit['telefone'].' / '.$edit['contato'].'</p>';
             $address = $edit['endereco'].', '.$mostra['numero'].', '.$edit['cidade'].', '.$edit['cep'];
				echo '<p align="center">'.$edit['endereco'].', '.$edit['numero'].' / '.$edit['complemento'].
				' - '.$edit['bairro'].' - '.$edit['cep'].'</p>';
           	?>
         <iframe width='100%' height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" zomm="1" src="https://maps.google.com.br/maps?q=<?php echo $address; ?>&output=embed">
         </iframe>
  		 </div>
	 </div>
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
 
<script>
	
	$(document).ready(function() {
	
            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#endereco").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                $("#ibge").val("");
            }
		
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {
                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');
                //Verifica se campo cep possui valor informado.
                if (cep != "") {
                    //Expressao regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;
                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endereco").val("procurando... Aguarde ")
                        $("#bairro").val("")
                        $("#cidade").val("")
                        $("#uf").val("")
                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#endereco").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
								$("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado nao foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP nao encontrado.");
                            }
                        });
                    } //end if.
					
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
	
	
        });
	  
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor-texto');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
	  
	  
	   //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });
 
	
 </script>
