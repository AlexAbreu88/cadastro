<?php
// Obtém o filtro da URL
$filter = $_GET['filter'];
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Prepara uma declaração SQL para selecionar os dados com o filtro especificado
$stmt = $db->prepare('SELECT * FROM myTable WHERE name LIKE :filter');
// Vincula o valor do filtro ao parâmetro da declaração SQL
$stmt->bindValue(':filter', '%' . $filter . '%', SQLITE3_TEXT);
// Executa a declaração SQL
$result = $stmt->execute();
// Armazena os resultados em um array
$data = array();
while ($row = $result->fetchArray()) {
    $data[] = $row;
}
// Retorna os dados como JSON
echo json_encode($data);
?>