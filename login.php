<?php
// Inicia uma sessão
session_start();
// Obtém os dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Prepara uma declaração SQL para selecionar o usuário pelo nome de usuário
$stmt = $db->prepare('SELECT * FROM users WHERE usuario = :username');
// Vincula o valor do nome de usuário ao parâmetro da declaração SQL
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
// Executa a declaração SQL
$result = $stmt->execute();
// Verifica se o usuário existe
if ($row = $result->fetchArray()) {
    // Verifica se a senha está correta usando a função password_verify()
    if (password_verify($password, $row['senha'])) {
        // Armazena informações sobre o usuário conectado na sessão
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_username'] = $row['usuario'];
        // Retorna uma mensagem de sucesso
        echo 'Login bem-sucedido!';
    } else {
        // Retorna uma mensagem de erro
        echo 'Senha incorreta!';
    }
} else {
    // Retorna uma mensagem de erro
    echo 'Usuário não encontrado!';
}
?>