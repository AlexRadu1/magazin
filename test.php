<?php

$text_myadmin = "SELECT *, s.ID AS cod_status, c.ID AS cod_comanda 
FROM comenzi c 
INNER JOIN clienti cl ON c.cod_client=cl.ID 
INNER JOIN comenzi_status s ON c.cod_status=s.ID 
WHERE CONCAT(nume, ' ', prenume) LIKE '%ilie%' OR CONCAT(nume, ' ', prenume) LIKE '%andrei%' 
ORDER BY c.ID DESC";



$text = "SELECT *, s.ID AS cod_status, c.ID AS cod_comanda 
FROM comenzi c 
INNER JOIN clienti cl ON c.cod_client=cl.ID 
INNER JOIN comenzi_status s ON c.cod_status=s.ID 
WHERE CONCAT(nume, ' ', prenume) LIKE '%ilie%' OR CONCAT(nume, ' ', prenume) LIKE '%andrei%' 
ORDER BY c.ID DESC";

echo strcasecmp($text_myadmin, $text);
