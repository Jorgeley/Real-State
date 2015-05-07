var Cidades = function(){
    "use strict";
    this.urlXML = new String();
    this.requisicao = new XMLHttpRequest();
    this.dadosXML = null;
    this.comboEstados = new String();
    this.comboCidades = new String();
    
    this.setUrlXML = function(url){
        this.urlXML = url;
    };
    
    this.setDadosXML = function(dados){
        this.dadosXML = dados;
    };
    
    this.setComboEstados = function(cbo){
        this.comboEstados = cbo;
    };
    
    this.setComboCidades = function(cbo){
        this.comboCidades = cbo;
    };
    
    this.carregaEstados = function(dados){
        var self = this;    
        var estados = dados.childNodes.item(2).getElementsByTagName("estado");
        var selectEstados = document.getElementById(this.comboEstados);
        
        for(var i in estados){
            var nomeEstado = estados[i].getElementsByTagName("nome")[0];
            nomeEstado = nomeEstado.firstChild.nodeValue;
            var idEstado = estados[i].getElementsByTagName("idestado")[0];
            idEstado = idEstado.firstChild.nodeValue;
            
            var novoOption = document.createElement("option");
            novoOption.text = nomeEstado;
            novoOption.setAttribute("value", idEstado);
            selectEstados.appendChild(novoOption);
        }
    };
    
    this.carregaCidades = function(id){
        document.getElementById(this.comboCidades).innerHTML = "";
        var cidades = this.dadosXML;
        cidades = cidades.childNodes.item(2).getElementsByTagName("cidade");
        for(var i in cidades){
            var idEstado = cidades[i].getElementsByTagName("idestado")[0];
            idEstado = idEstado.firstChild.nodeValue;
            if(idEstado == id){
                var nomeCidade = cidades[i].getElementsByTagName("nome")[0];
                nomeCidade = nomeCidade.firstChild.nodeValue;
                var idCidade = cidades[i].getElementsByTagName("idcidade")[0];
                idCidade = idCidade.firstChild.nodeValue;
                var novoOption = document.createElement("option");
                novoOption.text = nomeCidade;
                novoOption.setAttribute("value", idCidade);
                var optionCidades = document.getElementById(this.comboCidades);
                optionCidades.appendChild(novoOption);
            }
        }
    };
    
    this.carregaXML = function(){
        var self = this;
        this.requisicao.open("GET", this.urlXML, true);
        this.requisicao.send();
        this.requisicao.onreadystatechange = function(){
            var situacaoRequisicao = self.requisicao.readyState;
            var statusRequisicao = self.requisicao.status;
            if(situacaoRequisicao == 4 && statusRequisicao == 200){
                self.setDadosXML(self.requisicao.responseXML);
                self.carregaEstados(self.dadosXML);
            }
        };
    };
}