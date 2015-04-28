jQuery(document).ready(function(){
	jQuery("#gridprojeto").jqGrid({ //#list é o id da tabela para receber o jqgrid
		datatype: 'local',//na linha abaixo jogo os dados pro jqgrid 
		colNames:['Id','Nome','Vencimento'], 
		colModel :[ 	{name:'id', index:'id', width:'10px', sorttype: 'int', align:'left'},
			   	{name:'nome', index:'nome', width:'200px', search:true, align:'left'}, 
		    		{name:'vencimento', index:'vencimento', width:'50px', align:'left'}
				  ],
		width:'800',
		height:'200',
		scroll:false,
		pager: '#paginacao', 
		rowNum:10, 
		rowList:[10,20,30], 
		sortname: 'id', 
		sortorder: "desc", 
		viewrecords: true, 
		imgpath: 'js/jqgrid/css/images', 
		caption: 'duplo clique para abrir',
		ondblClickRow: function(id){
			location.href = location.href.slice(0,location.href.indexOf('projeto'))+'projeto/edita/id/'+id
		}
	});
	jQuery("#gridprojeto").jqGrid('navGrid','#paginacao',{edit:false,add:false,del:false});
});
