#!/bin/bash
export JAVA_CMD=java
rm tudo.min.js
echo "jquery..."
yui-compressor --type js --charset utf-8 jquery-1.11.2.min.js > tudo.min.js
echo "jquery-ui..."
yui-compressor --type js --charset utf-8 ./jquery-ui-1.10.4/ui/minified/jquery-ui.min.js >> tudo.min.js
echo "bootstrup..."
yui-compressor --type js --charset utf-8 bootstrap.min.js >> tudo.min.js
#echo "ckeditor..."
#yui-compressor --type js --charset utf-8 ./ckeditor/ckeditor.js >> tudo.min.js
#echo "jqgrid.locale..."
#yui-compressor --type js --charset utf-8 ./tonytomov-jqGrid-6659334/js/i18n/grid.locale-pt-br.js >> tudo.min.js
#echo "jqgrid..."
#yui-compressor --type js --charset utf-8 ./tonytomov-jqGrid-6659334/jquery.jqGrid.js >> tudo.min.js
echo "perfilgrid..."
yui-compressor --type js --charset utf-8 perfil.grid.js >> tudo.min.js
echo "usuariogrid..."
yui-compressor --type js --charset utf-8 usuario.grid.js >> tudo.min.js
echo "projetogrid..."
yui-compressor --type js --charset utf-8 projeto.grid.js >> tudo.min.js
echo "tarefagrid..."
yui-compressor --type js --charset utf-8 tarefa.grid.js >> tudo.min.js
