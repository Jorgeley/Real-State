#!/bin/bash
cd vendor/doctrine/orm/bin
php doctrine.php orm:schema-tool:update --force --dump-sql
cd -
