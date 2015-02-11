jQuery(document).ready(function(){
	jQuery("#gridperfil").jqGrid({ //#list é o id da tabela para receber o jqgrid
		datatype: 'local',//na linha abaixo jogo os dados pro jqgrid 
		colNames:['Id','Perfil','Usuário','Cargo','Login'], 
		colModel :[ {name:'id', index:'id', width:'10px', sorttype: 'int', align:'left'},
			   		{name:'perfil', index:'perfil', width:'500px', search:true, align:'left'}, 
		    		{name:'usuario', index:'usuario', width:'200px', align:'left'},
		    		{name:'cargo', index:'cargo', width:'150px', align:'left'},
		    		{name:'login', index:'login', width:'150px', align:'left'}
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
		caption: 'Perfis de Usuários com seus Recursos',
		grouping:true,
	   	groupingView : {
	   		groupField : ['perfil'],
			groupColumnShow : [false],
			groupText : ['<b>{0}</b>'],
			groupCollapse : false,
			groupOrder: ['asc'],
			groupSummary : [true]
	   	},
		ondblClickRow: function(id){
			location.href = location.href.slice(0,location.href.indexOf('perfil'))+'perfil/edita/id/'+id
		}
	});
	jQuery("#gridperfil").jqGrid('navGrid','#paginacao',{edit:false,add:false,del:false});
});