<?php
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Cria a tabela myTable
$db->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, usuario TEXT, email TEXT, senha TEXT)');
// Obtém os dados do formulário
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
// Criptografa a senha usando a função password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Prepara uma declaração SQL para inserir os dados
$stmt = $db->prepare('INSERT INTO users (usuario, email, senha) VALUES (:name, :email, :password)');
// Vincula os valores aos parâmetros da declaração SQL
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
// Executa a declaração SQL
$result = $stmt->execute();
// Verifica se a inserção foi bem-sucedida
if ($result) {
    echo 'Dados inseridos com sucesso!';
} else {
    echo 'Erro ao inserir dados!';
}
?>