#!/bin/bash
echo "ui-jqgrid..."
yui-compressor --type css --charset utf-8 ./jquery-ui-1.11.2.custom/jquery-ui.css > tudo.min.css
yui-compressor --type css --charset utf-8 ui.jqgrid.css >> tudo.min.css
#yui-compressor --type css --charset utf-8 grid.css >> tudo.min.css
echo "style..."
yui-compressor --type css --charset utf-8 style.css >> tudo.min.css
echo "bootrup-theme..."
yui-compressor --type css --charset utf-8 bootstrap-theme.css >> tudo.min.css 
echo "bootstrup..."
yui-compressor --type css --charset utf-8 bootstrap.css >> tudo.min.css
