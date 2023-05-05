<?php
// Obtém os dados do formulário
$id = $_POST['id'];
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Prepara uma declaração SQL para excluir os dados
$stmt = $db->prepare('DELETE FROM myTable WHERE id = :id');
// Vincula o valor do ID ao parâmetro da declaração SQL
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
// Executa a declaração SQL
$result = $stmt->execute();
// Verifica se a exclusão foi bem-sucedida
if ($result) {
    echo 'Dados excluídos com sucesso!';
} else {
    echo 'Erro ao excluir dados!';
}
