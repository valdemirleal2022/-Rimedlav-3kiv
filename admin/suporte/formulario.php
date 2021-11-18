<?php
	if(function_exists(ProtUser)){
		if(!ProtUser($_SESSION['autUser']['id'])){
			echo '<h1>Erro, você não tem permissão para acessar essa página!</h1>';	
			header('Location: painel.php');		
		}	
	}


	if ($edit['domingo'] == "1") {
				$edit['domingo'] = "checked='CHECKED'";
			} else {
				$edit['domingo'] = "";
	 }
?>




<div class="content-wrapper">

  <section class="content-header">
          <h1>Formulario</h1>
          <ol class="breadcrumb">
            <li><a href="vendas/painel.php?execute=painel"><i class="fa fa-home"></i> Home</a></li>
            <li>Site</li>
            <li><a href="vendas/painel.php?execute=suporte/cliente/cliente-receber">
              	Cliente</a>
            </li>
            <li class="active">Receita</li>
          </ol>
  </section>


   <section class="content">
         <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><small><?php echo $edit['descricao'];?></small></h3>
             	<div class="box-tools">
            		<small>
            		 <?php if($acao=='cadastrar') echo 'Cadastrando';?>
                     <?php if($acao=='deletar') echo 'Deletando';?>
                     <?php if($acao=='atualizar') echo 'Alterando';?>
                     teste...
                    </small>
          		</div><!-- /box-tools-->
      	  </div><!-- /.box-header with-border -->
          
           <div class="box-body">
             <div class="row">
                <div class="col-xs-10 col-md-3 pull-right">
                   <form name="form-pesquisa" method="post" class="form-inline " role="form">
                     <div class="input-group">
                       <input type="text" name="pesquisa" class="form-control input-sm" placeholder="Pesquisar">
                     	<div class="input-group-btn">
                   			<button class="btn btn-sm btn-default" name="nome" type="submit"><i class="fa fa-search"></i></button>											
                   		</div><!-- /.input-group -->
                   	  </div><!-- /input-group-->
                  </form> 
                  </div><!-- /col-xs-10-->
            </div>
        </div>

           <form role="form" action="" class="formulario" method="post">
                      			   
                <div class="box-body">

                 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome</label>
                             <input name="nome"  class="form-control" type="text" value="<?php echo $edit['nome'];?>"/>
                        </div><!-- /form-groups-->
                     </div>   <!-- /col-md-6-->
                     
                        <div class="col-md-6">
                         <div class="form-group">
                            <label>Descrição</label>
                            <input name="descricao"  class="form-control" type="text" value="<?php echo $edit['descricao'];?>" />
                       </div><!-- /form-groups-->
                    </div>   <!-- /col-md-6-->
                  
                    <div class="col-md-6">  
                      <div class="form-group">
                        <label>Telefone</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                          </div>
                         <input name="telefone" OnKeyPress="formatar('## ####-####', this)" class="form-control" type="text" value="<?php echo $edit['telefone'];?>" />
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div><!-- /.col-md-6 -->
                    
                     <div class="col-md-6">  
                      <div class="form-group">
                        <label>Celular</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                          </div>
                         <input name="celular"  class="form-control" type="text" value="<?php echo $edit['celular'];?>" OnKeyPress="formatar('## #####-####', this)" />
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div><!-- /.col-md-6 -->
                    
                    
                   <div class="col-md-6">  
                      <div class="form-group">
                        <label>CNPJ Mask</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                          </div>
                         <input name="cnpj" id="cnpj" class="form-control" type="text" value="<?php echo $edit['cnpj'];?>" OnKeyPress="formatar('##.###.###/####-##', this)" />
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div><!-- /.col-md-6 -->
                    
                    
                      <div class="col-md-6">  
                      <div class="form-group">
                        <label>CPF</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                          </div>
                         <input name="cpf" id="celular" class="form-control" type="text" value="<?php echo $edit['cpf'];?>" OnKeyPress="formatar('###.###.###-##', this)" />
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div><!-- /.col-md-6 -->
                    
                    
                     <div class="col-md-6">  
                      <div class="form-group">
                        <label>Valor</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-money"></i>
                          </div>
                         <input name="valor" id="dinheiro" class="form-control dinheiro" type="text" value="<?php echo $edit['valor'];?>" />
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div><!-- /.col-md-6 -->
                    
                 
                  <div class="col-md-6">  
                      <div class="form-group">
                        <label>Comissão</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                          </div>
                         <input name="comissao_fechamento" class="form-control percentual" type="text" value="<?php echo $edit['comissao'];?>" />
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div><!-- /.col-md-6 -->
                   
               
                    <div class="col-md-6">  
                      <div class="form-group">
                        <label>Data</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                         <input name="data"  class="form-control" type="date" value="<?php echo $edit['data'];?>" />
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div><!-- /.col-md-6 -->
                    
                     <div class="col-md-6">  
                         <div class="checkbox">
                          <label>
                            <input type="checkbox"> Check me out
                          </label>
                        </div>
                      </div><!-- /.col-md-6 -->
                      
   
             </div> <!-- /.box-body -->

     

         <div class="box-footer">
           <a href="javascript:window.history.go(-1)"><input type="button" value="Voltar" class="btn btn-warning"></a>
           	 <?php 
                if($acao=="atualizar"){
                    echo '<input type="submit" name="atualizar" value="Atualizar" class="btn btn-primary" />';	
                }
                if($acao=="deletar"){
                    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" />';	
                }
                if($acao=="cadastrar"){
                    echo '<input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary" />';	
                }
			 
			 	
                   if($_SESSION['autUser']['nivel']==5){	//Gerencial 
				  	   
					    echo '<input type="submit" name="deletar" value="Deletar"  class="btn btn-danger" onClick="return confirm(\'Confirma Exclusão do Registro ?\')" />';
					 
					}
			 
			  if($acao=="enviar"){
				echo '<input type="submit" name="enviar-comunicado" value="Enviar Comunicado" class="btn btn-primary" />';
				echo '<input type="submit" name="enviar-atualizacao" value="Enviar Atualização" class="btn btn-primary" />';     
				}
			 ?>  
   		 </div><!-- /.box-footer -->
    </form>
    
        </div><!-- /.box box-default -->  
      </section><!-- /.content -->
 
       <section class="content">
        <div class="box box-warning">  
          <div class="box-body">  
    
       		 <div class="well">Texto comum</div>
                <h2 class="page-header">Progress Bars</h2>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#aba_1" data-toggle="tab">Tab 1</a></li>
                          <li><a href="#aba_2" data-toggle="tab">Tab 2</a></li>
                          <li><a href="#aba_3" data-toggle="tab">Tab 3</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="aba_1">
                            <b>How to use:</b>
                            <p>Exactly like the original bootstrap tabs except you should use
                              the custom wrapper <code>.nav-tabs-custom</code> to achieve this style.</p>
                            A wonderful serenity has taken possession of my entire soul,
                            like these sweet mornings of spring which I enjoy with my whole heart.
                            I am alone, and feel the charm of existence in this spot,
                            which was created for the bliss of souls like mine. I am so happy,
                            my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
                            that I neglect my talents. I should be incapable of drawing a single stroke
                            at the present moment; and yet I feel that I never was a greater artist than now.
                          </div><!-- /.tab-pane -->
                          <div class="tab-pane" id="aba_2">
                            The European languages are members of the same family. Their separate existence is a myth.
                            For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                            in their grammar, their pronunciation and their most common words. Everyone realizes why a
                            new common language would be desirable: one could refuse to pay expensive translators. To
                            achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                            words. If several languages coalesce, the grammar of the resulting language is more simple
                            and regular than that of the individual languages.
                          </div><!-- /.tab-pane -->
                          <div class="tab-pane" id="aba_3">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                            It has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                            sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                            like Aldus PageMaker including versions of Lorem Ipsum.
                          </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                      </div><!-- nav-tabs-custom -->
                    </div><!-- /.col -->
                  </div> <!-- /.row -->
                  
             </div><!-- /.box-body --> 
    	 </div><!-- /.box box-default -->  
      </section><!-- /.content -->
      
      <section class="content">
        <div class="box box-warning">  
		  <div class="box-body">
          
        <!-- START ALERTS AND CALLOUTS -->
          <h2 class="page-header">Alerts and Callouts</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="box box-default">
                <div class="box-header with-border">
                  <i class="fa fa-warning"></i>
                  <h3 class="box-title">Alerts</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    Danger alert preview. This alert is dismissable. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.
                  </div>
                  <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> Alert!</h4>
                    Info alert preview. This alert is dismissable.
                  </div>
                  <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                    Warning alert preview. This alert is dismissable.
                  </div>
                  <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
                    Success alert preview. This alert is dismissable.
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
             </div><!-- /.col -->
          </div><!-- /.row -->
            
 <div>
 

</head>
<body>
<table width="301" height="160" border="0" cellspacing="0" class="tabela">
  <tr>
    <td height="34" colspan="2" valign="top">Este script formata qualquer tipo de campo.<br>
Veja o Exemplo abaixo</td>
  </tr>
  <tr>
    <td width="32" height="24">Data:</td>
    <td width="265"><input type="text" name="data" maxlength="10" OnKeyPress="formatar('##/##/####', this)" ></td>
  </tr>
  <tr>
    <td height="24">Tel.:</td>
    <td><input type="text" name="tel" maxlength="12" OnKeyPress="formatar('##-####-####', this)" ></td>
  </tr>
  <tr>
    <td height="24">Cep:</td>
    <td><input type="text" name="cep" maxlength="9" OnKeyPress="formatar('#####-###', this)" ></td>
  </tr>
  <tr>
    <td height="24">CPF:</td>
    <td><input type="text" name="cpf" maxlength="14" OnKeyPress="formatar('###.###.###-##', this)" ></td>
  </tr>
  <tr>
    <td height="24">Hora:</td>
    <td><input type="text" name="hora" maxlength="5" OnKeyPress="formatar('##:##', this)" ></td>
  </tr>
   <tr>
    <td height="24">Valor 1:</td>
    <td><input type="text" class="dinheiro"  ></td>
  </tr>

   <tr>
    <td height="24">Valor 2 :</td>
    <td><input type="text" class="dinheiro"  ></td>
  </tr>
  

   <tr>
    <td height="24">Comissão :</td>
    <td><input type="text" class="percentual"  ></td>
  </tr>
  
  <tr>
    <td colspan="2" valign="bottom">e assim por diante...<br />
&eacute; um codigo bem simples e de grande utilidade. </td>
  </tr>
</table>
    
<script>



	
//$(".percentual").maskMoney({
//	prefix:'% ',
//	allowNegative: true, 
//	thousands:'.',
//	decimal:',', 
//	affixesStay: false
// });
//	
//$(".dinheiro").maskMoney({
//	prefix:'R$ ',
//	allowNegative: true, 
//	thousands:'.',
//	decimal:',', 
//	affixesStay: false
// });
//
//</script>

        
<?php $mensagem='teste de mensagem';?>
<script type="text/javascript">
	toastr.info('<?php echo $mensagem; ?>');
	
</script>

 
  </div><!-- /.box box-default -->
 </section><!-- /.content -->
 


