<?php
// Obtém os dados do formulário
$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Prepara uma declaração SQL para atualizar os dados
$stmt = $db->prepare('UPDATE myTable SET name = :name, email = :email WHERE id = :id');
// Vincula os valores aos parâmetros da declaração SQL
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
// Executa a declaração SQL
$result = $stmt->execute();
// Verifica se a atualização foi bem-sucedida
if ($result) {
    echo 'Dados atualizados com sucesso!';
} else {
    echo 'Erro ao atualizar dados!';
}
?>
