jQuery(document).ready(function(){
	jQuery("#gridusuario").jqGrid({ //#list é o id da tabela para receber o jqgrid
		datatype: 'local',//na linha abaixo jogo os dados pro jqgrid 
		colNames:['Id','Perfil','Usuário','Cargo'], 
		colModel :[ {name:'id', index:'id', width:'50px', sorttype: 'int', align:'left'},
			   		{name:'perfil', index:'perfil', width:'100px', search:true, align:'left'}, 
		    		{name:'usuario', index:'usuario', width:'200px', align:'left'},
		    		{name:'cargo', index:'cargo', width:'100px', align:'left'}
				  ],
		width:'800',
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
		ondblClickRow: function(id){
			location.href = location.href.slice(0,location.href.indexOf('usuario'))+'usuario/edita/id/'+id
		}
	});
	jQuery("#gridusuario").jqGrid('navGrid','#paginacao',{edit:false,add:false,del:false});
});
