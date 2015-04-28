jQuery(document).ready(function(){
	jQuery("#gridtarefa").jqGrid({ //#list é o id da tabela para receber o jqgrid
		datatype: 'local',//na linha abaixo jogo os dados pro jqgrid 
		colNames:['Id','Projeto','Tarefa','Vencimento'], 
		colModel :[ {name:'id', index:'id', width:'10px', sorttype: 'int', align:'left'},
			   		{name:'projeto', index:'projeto', width:'500px', search:true, align:'left'}, 
		    		{name:'tarefa', index:'tarefa', width:'200px', align:'left'},
		    		{name:'vencimento', index:'vencimento', width:'150px', align:'left'}
				  ],
		width:'1800',
		height:'300',
		scroll:false,
		pager: '#paginacao', 
		rowNum:10, 
		rowList:[10,20,30], 
		sortname: 'id', 
		sortorder: "desc", 
		viewrecords: true, 
		imgpath: 'js/jqgrid/css/images', 
		caption: 'duplo clique para abrir',
		grouping:true,
	   	groupingView : {
	   		groupField : ['projeto'],
			groupColumnShow : [false],
			groupText : ['<b>{0}</b>'],
			groupCollapse : false,
			groupOrder: ['asc'],
			groupSummary : [true]
	   	},
		ondblClickRow: function(id){
			location.href = location.href.slice(0,location.href.indexOf('tarefa'))+'tarefa/edita/id/'+id
		}
	});
	jQuery("#gridtarefa").jqGrid('navGrid','#paginacao',{edit:false,add:false,del:false});
});
