$(function(){
   $('.slide').cycle({
     fx: 'fade',
     speed: 1000,
     timeout: 3000,
   })
});

    function consultacep(cep){
      cep = cep.replace(/\D/g,"")
      url="http://cep.correiocontrol.com.br/"+cep+".js"
      s=document.createElement('script')
      s.setAttribute('charset','utf-8')
      s.src=url	
      document.querySelector('head').appendChild(s)
    }
    function correiocontrolcep(valor){
      if (valor.erro) {
       alert('Cep não encontrado');       
        return;
      };
      document.getElementById('endereco').value=valor.logradouro
      document.getElementById('bairro').value=valor.bairro
      document.getElementById('cidade').value=valor.localidade
      document.getElementById('uf').value=valor.uf
    }


jQuery(function($){
   $("#telefone").mask("(99) 9999-9999");
   $("#celular").mask("(99) 99999-9999");
   $("#data").mask("99/99/9999");
   $("#cep").mask("99999-999");
});
