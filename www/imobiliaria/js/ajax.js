//envia requisições ajax por GET ou POST
function ajax(url,tipo,dados,div){
	$(document).ready(function(){
		$(document)
                    .ajaxStart(function(){
                            //$('div.jqmWindow').jqmHide();
                            $('#aguarde').show();
                    })
                    .ajaxStop(function(){
                        $('#aguarde').hide(); 
                    });
		$.ajax({
			type: tipo,
			url: url,
			data: dados,
			datatype: "html",
			success: function(data){ $('#page-bgbtm').html(data); }
		});
	});
}