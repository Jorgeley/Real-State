#!/bin/bash
rsync -ruvv -o --exclude-from=rsyncExcluidos.txt www/imobiliaria/* grupo-gpa@grupo-gpa.com:~/www/imobiliaria/ | grep -v newer
rsync -ruvv -o --exclude-from=rsyncExcluidos.txt imobiliaria/module/* grupo-gpa@grupo-gpa.com:~/imobiliaria/module/ | grep -v newer
git commit -a
git push origin master
