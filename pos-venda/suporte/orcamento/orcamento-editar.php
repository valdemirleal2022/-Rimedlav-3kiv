<?php

if(function_exists(ProtUser)){
	if(!ProtUser($_SESSION['autConsultor']['id'])){
		header('Location: painel.php');	
	}	
}

if ( !empty( $_GET[ 'orcamentoEditar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoEditar' ];
    $acao = "atualizar";
}

if ( !empty( $_GET[ 'orcamentoBaixar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoBaixar' ];
    $acao = "baixar";
}

if ( !empty( $_GET[ 'orcamentoDesfazer' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoDesfazer' ];
    $acao = "desfazer";
}

if ( !empty( $_GET[ 'orcamentoAprovar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoAprovar' ];
    $acao = "aprovar";
	$readonly = "readonly";
    $disabled = 'disabled="disabled"';
}


if ( !empty( $_GET[ 'orcamentoCancelar' ] ) ) {
    $orcamentoId = $_GET[ 'orcamentoCancelar' ];
    $acao = "cancelar";
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

if ( !empty( $orcamentoId ) ) {
    $readorcamento = read( 'cadastro_visita', "WHERE id = '$orcamentoId'" );
    if ( !$readorcamento ) {
        header( 'Location: painel.php?execute=suporte/error' );
    }
    foreach ( $readorcamento as $edit );

    $clienteId = $edit[ 'id' ];
    $readCliente = read( 'cadastro_visita', "WHERE id = '$clienteId'" );
    foreach ( $readCliente as $cliente );
}

if ( !empty( $_GET[ 'clienteId' ] ) ) {
    $acao = "cadastrar";
    $edit[ 'interacao' ] = date( 'Y/m/d H:i:s' );
    $edit[ 'status' ] = 1;
    $edit[ 'tipo' ] = 1;
    $clienteId = $_GET[ 'clienteId' ];
    $readCliente = read( 'cadastro_visita', "WHERE id = '$clienteId'" );
    foreach ( $readCliente as $cliente );
}


$_SESSION[ 'url2' ] = $_SERVER[ 'REQUEST_URI' ];

$consultorId=$_SESSION['autConsultor']['id'];

?><head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>
  




<div class="content-wrapper">
    <section class="content-header">
        <h1>Orçamento</h1>
        <ol class="breadcrumb">
            <li><a href="painel.php?execute=painel"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a href="#">Consultor</a>
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
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['tipo']= 1;
		$cad['status']= 3;
		$cad['situacao'] =1;
		update('cadastro_visita',$cad,"id = '$orcamentoId'");	
		header("Location: ".$_SESSION['url']);
	}
		
	if(isset($_POST['followup'])){
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['tipo']= 1;
		$cad['status']= 3;
		$cad['situacao'] =1;
		update('cadastro_visita',$cad,"id = '$orcamentoId'");	
		header("Location: ".$_SESSION['url']);
	}
		
	if(isset($_POST['cadastrar'])){
	    $cad['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
		$cad['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
		$cad['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
		$cad['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
		$cad['orc_quantidade']= strip_tags(trim(mysql_real_escape_string($_POST['orc_quantidade'])));
		
		$cad['orc_valor_unitario']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_unitario'])));
		$cad['orc_valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['orc_valor_unitario']));
		
		$cad['orc_valor_extra']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_extra'])));
		$cad['orc_valor_extra'] = str_replace(",",".",str_replace(".","",$cad['orc_valor_extra']));
		
		$cad['orc_valor']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor'])));
		$cad['orc_valor'] = str_replace(",",".",str_replace(".","",$cad['orc_valor']));
		
		$cad['orc_forma_pag']= strip_tags(trim(mysql_real_escape_string($_POST['orc_forma_pag'])));
		
		$cad['orc_comodato']= strip_tags(trim(mysql_real_escape_string($_POST['orc_comodato'])));
		
		$cad['orc_observacao']= mysql_real_escape_string($_POST['orc_observacao']);
		
		$cad['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
		$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
		$cad['interacao']= date('Y/m/d H:i:s');
		$cad['orc_solicitacao']= date('Y/m/d H:i:s');
		$cad['status'] =1;
		create('cadastro_visita',$cad);	
		header("Location: ".$_SESSION['url']);
	}
		
	if(isset($_POST['atualizar'])){
		
		$cad['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
		$cad['nome_fantasia']=strip_tags(trim(mysql_real_escape_string($_POST['nome_fantasia'])));
		$cad['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
		$cad['numero']= strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
		$cad['complemento']=strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
		$cad['bairro']=strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
		$cad['cep']=strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
		$cad['telefone']=strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
		$cad['contato']=strip_tags(trim(mysql_real_escape_string($_POST['contato'])));
		$cad['email']=strip_tags(trim(mysql_real_escape_string($_POST['email'])));
		$cad['cnpj']=strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
		$cad['inscricao']=strip_tags(trim(mysql_real_escape_string($_POST['inscricao'])));
		$cad['cpf']=strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
		$cad['empresa_atual']=strip_tags(trim(mysql_real_escape_string($_POST['empresa_atual'])));
		
		$cad['orc_residuo'] = mysql_real_escape_string($_POST['orc_residuo']);
		$cad['orc_frequencia'] = mysql_real_escape_string($_POST['orc_frequencia']);
		$cad['orc_dia'] = mysql_real_escape_string($_POST['orc_dia']);
		$cad['orc_equipamento']= strip_tags(trim(mysql_real_escape_string($_POST['orc_equipamento'])));
		$cad['orc_quantidade']= strip_tags(trim(mysql_real_escape_string($_POST['orc_quantidade'])));
		
$cad['orc_valor_unitario']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_unitario'])));
$cad['orc_valor_unitario'] = str_replace(",",".",str_replace(".","",$cad['orc_valor_unitario']));
		
		$cad['orc_valor_extra']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor_extra'])));
		$cad['orc_valor_extra'] = str_replace(",",".",str_replace(".","",$cad['orc_valor_extra']));
		
		$cad['orc_valor']= strip_tags(trim(mysql_real_escape_string($_POST['orc_valor'])));
		$cad['orc_valor'] = str_replace(",",".",str_replace(".","",$cad['orc_valor']));
		
		$cad['orc_forma_pag']= strip_tags(trim(mysql_real_escape_string($_POST['orc_forma_pag'])));
		
		$cad['orc_comodato']= strip_tags(trim(mysql_real_escape_string($_POST['orc_comodato'])));
		
		$cad['orc_observacao']= $_POST['orc_observacao'];
		
		$cad['orc_data']= strip_tags(trim(mysql_real_escape_string($_POST['orc_data'])));
		$cad['orc_hora']= strip_tags(trim(mysql_real_escape_string($_POST['orc_hora'])));
		
		if(!empty($_POST['aprovacao_comercial'])){
			$cad['aprovacao_comercial']= strip_tags(trim(mysql_real_escape_string($_POST['aprovacao_comercial'])));
		}
		if(!empty($_POST['aprovacao_operacional'])){
			$cad['aprovacao_operacional']= strip_tags(trim(mysql_real_escape_string($_POST['aprovacao_operacional'])));
		}
		
		
		update('cadastro_visita',$cad,"id = '$orcamentoId'");	
		header("Location: ".$_SESSION['url']);
	}
		
	if(isset($_POST['enviar'])){
		
		if($edit['aprovacao_comercial']=='1'){
			
			if($edit['aprovacao_operacional']=='1'){
			
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
				$msg .="<img src='http://www.cleanambiental.com.br/wpc/site/images/header-logo.png'><br/><br/><br/>";


				$msg .= "Proposta Comercial<br /><br />";
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
				enviaEmail($assunto,$msg,MAILUSER,SITENOME,$consultor['email'],$consultor['nome']);
				header("Location: ".$_SESSION['url']);
			 }else{ 
			  echo '<div class="alert alert-warning">Proposta ainda não liberada pelo operacional!</div>'.'<br>';
			}
		 }else{ 
			echo '<div class="alert alert-warning">Proposta ainda não liberada pelo comercial!</div>'.'<br>';
		}	
	}
	
		
	if(isset($_POST['aprovar'])){
		
	   if($edit['aprovacao_comercial']=='1'){
			
		if($edit['aprovacao_operacional']=='1'){
		
			$cad['email']	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$cad['email'] = strtolower($cad['email']);
			$cad['nome']= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$cad['endereco']= strip_tags(trim(mysql_real_escape_string($_POST['endereco'])));
			$cad['numero'] = strip_tags(trim(mysql_real_escape_string($_POST['numero'])));
			$cad['complemento']= strip_tags(trim(mysql_real_escape_string($_POST['complemento'])));
			$cad['bairro']		= strip_tags(trim(mysql_real_escape_string($_POST['bairro'])));
			$cad['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$cad['cnpj'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cnpj'])));
			$cad['cpf']   	= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = 4;
		
			if(empty($cad['nome'])){
					echo '<div class="alert alert-warning">O Nome do cliente é obrigatório!</div>'.'<br>';
				 }elseif(empty($cad['cep'])){
					echo '<div class="alert alert-warning">O CEP do cliente é obrigatório!</div>'.'<br>';
				 }elseif(!email($cad['email'])){
					echo '<div class="alert alert-warning">Desculpe o e-mail informado é inválido!</div>'.'<br>';
				 }elseif(!empty($cad['cnpj']) && !cnpj($cad['cnpj']) ){
						echo '<div class="alert alert-warning">Desculpe o CNPJ informado é inválido!</div>'.'<br>';
				 }elseif(!empty($cad['cpf']) && !cpf($cad['cpf']) ){
						echo '<div class="alert alert-warning">Desculpe o CPF informado é inválido!</div>'.'<br>';
				  }else{
					update('cadastro_visita',$cad,"id = '$orcamentoId'");	
					header("Location: ".$_SESSION['url']);
			}
		 }else{ 
			  echo '<div class="alert alert-warning">Proposta ainda não liberada pelo operacional!</div>'.'<br>';
			}
		 }else{ 
			echo '<div class="alert alert-warning">Proposta ainda não liberada pelo comercial!</div>'.'<br>';
		}	
	}
			
	if(isset($_POST['cancelar'])){
			$cad['interacao']= date('Y/m/d H:i:s');
			$cad['status'] = 17;
			$cad['motivo_cancelamento']= strip_tags(trim(mysql_real_escape_string($_POST['motivo_cancelamento'])));
			if(empty($cad['motivo_cancelamento'])){
				echo '<div class="alert alert-warning">Motivo Cancelamento é obrigatório!</div>'.'<br>';
			}else{
				update('cadastro_visita',$cad,"id = '$orcamentoId'");	
				header("Location: ".$_SESSION['url']);
			}
		}
		
	if(isset($_POST['deletar'])){
	    $readDeleta = read('contrato',"WHERE id = '$orcamentoId'");
        if(!$readDeleta){
			echo '<div class="msgError">Desculpe, o registro nao existe</div><br />';	
		}else{
			delete('cadastro_visita',"id = '$orcamentoId'");
			header("Location: ".$_SESSION['url']);
		}
	}
		
	?>

      <form name="formulario" action="" class="formulario" method="post" enctype="multipart/form-data">

         <div class="box-header with-border">
              <h3 class="box-title">Dados da Visita</h3>
         </div><!-- /.box-header -->
                
		<div class="box-body">
        	<div class="row">
          
           <div class="form-group col-xs-12 col-md-2"> 
               <label>Id</label>
               <input name="id" type="text" value="<?php echo $edit['id'];?>" class="form-control" disabled />
            </div> 
            
            <div class="form-group col-xs-12 col-md-3">  
               	<label>Interação</label>
   				<input name="interacao" type="text" value="<?php echo date('d/m/Y H:i:s',strtotime($edit['interacao']));?>" readonly class="form-control" /> 
			</div>
         
         	 <div class="form-group col-xs-12 col-md-2">
               <label>Data do Cadastro </label>
               <input name="data" type="text" value="<?php echo date('d/m/Y',strtotime($edit['data']));?>" class="form-control" readonly/>
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
                  <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>">
            </div>
             <div class="form-group col-xs-12 col-md-6">  
                 <label>Nome Fantasia</label>
                  <input name="nome_fantasia"  class="form-control" type="text" value="<?php echo $edit['nome_fantasia'];?>">
            </div>
            
     		<div class="form-group col-xs-12 col-md-2">
               <label>CEP </label>
                <input name="cep" id="cep" value="<?php echo $edit['cep'];?>"  class="form-control" />  
           </div>
			<div class="form-group col-xs-12 col-md-4">   
                <label>Endereco</label>
                <input name="endereco" id="endereco" value="<?php echo $edit['endereco'];?>" class="form-control" />  
            </div>
           <div class="form-group col-xs-12 col-md-1">   
                <label>Numero </label>
                <input name="numero"  value="<?php echo $edit['numero'];?>" class="form-control" />  
            </div> 
             <div class="form-group col-xs-12 col-md-2">   
                <label>Complemento </label>
                <input name="complemento"  value="<?php echo $edit['complemento'];?>" class="form-control" />  
            </div> 
      		<div class="form-group col-xs-12 col-md-3">   
                <label>Bairro</label>
                <input name="bairro" id="bairro" value="<?php echo $edit['bairro'];?>" class="form-control" />           					
            </div> 
            <div class="form-group col-xs-12 col-md-3">   
                <label>Telefone</label>
                <input name="telefone" value="<?php echo $edit['telefone'];?>" class="form-control" />           					
            </div> 
            <div class="form-group col-xs-12 col-md-4">   
                <label>Contato</label>
                <input name="contato"  value="<?php echo $edit['contato'];?>" class="form-control" />           					
            </div> 
             <div class="form-group col-xs-12 col-md-5">   
                <label>Email</label>
                <input name="email"  value="<?php echo $edit['email'];?>" class="form-control" />           					
            </div> 

           <div class="form-group col-xs-12 col-md-4">  
                <label>CPF </label>
                <input name="cpf" type="text"  value="<?php echo $edit['cpf'];?>"  class="form-control" OnKeyPress="formatar('###.###.###-##', this)" />
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
                <label>CNPJ </label>
                <input type="text" name="cnpj" value="<?php echo $edit['cnpj'];?>"   class="form-control" OnKeyPress="formatar('##.###.###/####-##', this)" />
           </div>
           
           <div class="form-group col-xs-12 col-md-4">  
             <label>Inscrição</label>
             <input type="text" name="inscricao"  value="<?php echo $edit['inscricao'];?>" class="form-control" />
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
                                <select name="atendente"  disabled class="form-control"/>
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
                                <select name="consultor" class="form-control" disabled  />
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
                                <select name="indicacao"  disabled  class="form-control"/>
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
                                <input type="date" name="orc_data" value="<?php echo $edit['orc_data'];?>" class="form-control">
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
                                <input type="text" name="orc_residuo" value="<?php echo $edit['orc_residuo'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-4">
                                <label>Freqüencia da Coleta</label>
                                <input type="text" name="orc_frequencia" value="<?php echo $edit['orc_frequencia'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-8">
                                <label>Dia da Semana</label>
                                <input type="text" name="orc_dia" value="<?php echo $edit['orc_dia'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Tipo de Equipamento </label>
                                <input type="text" name="orc_equipamento" value="<?php echo $edit['orc_equipamento'];?>" class="form-control"/>
                            </div>
                            
                              <div class="form-group col-xs-12 col-md-3">
                                <label>Quantidade Mínima Diária </label>
                                <input type="text" name="orc_quantidade" value="<?php echo $edit['orc_quantidade'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-3">
                                <label>Valor Unitário R$ </label>
                                <input type="text" name="orc_valor_unitario" value="<?php echo converteValor($edit['orc_valor_unitario']) ;?>" class="form-control"/>
                            </div>

							 <div class="form-group col-xs-12 col-md-3">
								  <label>Valor Extra Unitário R$  </label>
								   <input type="text" name="orc_valor_extra" value="<?php echo converteValor($edit['orc_valor_extra']);?>" class="form-control"/>
							 </div>

							<div class="form-group col-xs-12 col-md-3">
                                <label>Valor Mensal R$ </label>
                                <input type="text" name="orc_valor" value="<?php echo converteValor($edit['orc_valor']) ;?>" class="form-control"/>
                            </div>
                            
                            <div class="form-group col-xs-12 col-md-3">
                                <label>Equipamento por Comodato R$ </label>
                                <input type="text" name="orc_comodato" value="<?php echo $edit['orc_comodato'] ;?>" class="form-control"/>
                            </div>

                              <div class="form-group col-xs-12 col-md-6">
                                <label>Forma de Pagamento </label>
                                <input type="text" name="orc_forma_pag" value="<?php echo $edit['orc_forma_pag'];?>" class="form-control"/>
                            </div>

                            <div class="form-group col-xs-12 col-md-12">
							  <label>Observação</label>
								<textarea name="orc_observacao" rows="3" cols="80" class="form-control"/><?php echo htmlspecialchars($edit['orc_observacao']);?>
								</textarea>
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
								<select name="aprovacao_comercial" class="form-control" <?php echo $disabled;?> >
								  <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_comercial'] == '1') echo' selected="selected"';?> value="1" disabled>Aprovado</option>
								  <option <?php if($edit['aprovacao_comercial'] == '2') echo' selected="selected"';?> value="2" disabled>Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_comercial'] == '3') echo' selected="selected"';?> value="3">Solicitar Aprovação</option>
								 </select>
							</div><!-- /.row -->
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_comercial_observacao" value="<?php echo $edit['aprovacao_comercial_observacao'];?>" class="form-control" readonly  />
                            </div>
		                       
                        </div>  <!-- /.row -->
                 </div>  <!-- /.box-body -->    
                     
                  <div class="box-body">
                       <div class="row">  
						   
                             <div class="form-group col-xs-3">
								<label>Aprovação Operacional</label>
								<select name="aprovacao_operacional" class="form-control" <?php echo $disabled;?> >
								  <option value="">Selecione solicitação</option>
								  <option <?php if($edit['aprovacao_operacional'] == '1') echo' selected="selected"';?> value="1" disabled>Aprovado</option>
								  <option <?php if($edit['aprovacao_operacional'] == '2') echo' selected="selected"';?> value="2" disabled>Nao! Ver Observação</option>
								  <option <?php if($edit['aprovacao_operacional'] == '3') echo' selected="selected"';?> value="3">Solicitar Rota</option>
								 </select>
							</div><!-- /.row -->
                           
                             <div class="form-group col-xs-12 col-md-2">  
                               <label>Rota </label>
							  <select name="rota"  class="form-control"  disabled >
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
                                <input type="text" name="hora" value="<?php echo $edit['hora'];?>" class="form-control" readonly  />
                            </div>
                            
                            <!-- aprovacao_comercial-->
							<div class="form-group col-xs-12 col-md-12">
                                <label>Observação </label>
                                <input type="text" name="aprovacao_operacional_observacao" value="<?php echo $edit['aprovacao_operacional_observacao'];?>" class="form-control" readonly  />
                            </div>
                            
                            
           <div class="form-group col-xs-12 col-md-5">
              <label>Motivo Cancelamento</label>
              <select name="motivo_cancelamento" class="form-control"/>
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

               <div class="box-footer">
                    <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"> </a>

                <?php 
				   
				if($acao=="desfazer"){
					echo '<input type="submit" name="desfazer" value="Desfazer Cancelamento" class="btn btn-success" />';
	
                }
                 
               if($acao=="atualizar"){
                  echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';
                }
				
				if($acao=="enviar"){
                  echo '<input type="submit" name="enviar" value="Enviar Proposta Email" class="btn btn-primary" />';
                }
				
                if($acao=="cancelar"){
                    echo '<input type="submit" name="cancelar" value="Cancelar" class="btn btn-danger" />';
                }
                 
				if($acao=="aprovar"){
					echo '<input type="submit" name="aprovar" value="Aprovar" class="btn btn-success" />';
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
				 <small> Negociaçao  </small>
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
					  
							//	echo '<td align="center">
//                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaEditar='.$mostra['id'].'">
//                                            <img src="../admin/ico/editar.png" alt="Editar" title="Editar" class="tip" />
//                                        </a>
//                                      </td>';
//  
							//	
//								echo '<td align="center">
//                                        <a href="painel.php?execute=suporte/visita/visita-negociacao-editar&agendaBaixar='.$mostra['id'].'">
//                                            <img src="../admin/ico/baixar.png" alt="Baixar" title="Baixar" class="tip" />
//                                        </a>
//                                      </td>';  
		
                              echo '</tr>';
                      endforeach;
                        echo '</table>';
                      }
                   ?>   
 	
		 </div>
      </div>
	</section><!-- /.content -->

	 <section class="content">
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
           