<h1>envia Email</h1>
<?php
echo mail("jorgeleygpa@gmail.com.readnotify.com", "teste", "teste");
echo mail("jorgeley@gmail.com", "teste", "teste");
mail("jorgeley@gmail.com.readnotify.com", "Visita Confirmada", $msg, 'MIME-Version: 1.0' . "\r\n"
                    . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"
                    . 'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n");

?>
