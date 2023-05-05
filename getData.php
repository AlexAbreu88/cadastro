<?php
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Executa uma consulta SQL
$results = $db->query('SELECT * FROM myTable');
// Armazena os resultados em um array
$data = array();
while ($row = $results->fetchArray()) {
    $data[] = $row;
}
// Retorna os dados como JSON
echo json_encode($data);
?>