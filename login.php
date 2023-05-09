<?php
// Inicia uma sessão
session_start();
// Obtém os dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];
// Conecta ao banco de dados SQLITE3
$db = new SQLite3('db/myDatabase.db');
// Prepara uma declaração SQL para selecionar o usuário pelo nome de usuário
$stmt = $db->prepare('SELECT * FROM users WHERE nome_usuario = :nome_usuario');
if ($stmt === false) {
    die('Erro ao preparar a declaração SQL: ' . $db->lastErrorMsg());
}
// Vincula o valor do nome de usuário ao parâmetro da declaração SQL
$result = $stmt->bindValue(':nome_usuario', $username, SQLITE3_TEXT);
if ($result === false) {
    die('Erro ao vincular o valor do nome de usuário: ' . $stmt->lastErrorMsg());
}
// Executa a declaração SQL
$result = $stmt->execute();
// Verifica se o usuário existe
if ($row = $result->fetchArray()) {
    // Verifica se a senha está correta usando a função password_verify()
    if (password_verify($password, $row['senha'])) {
        // Verifica se o email do usuário foi verificado
        if ($row['email_verificado'] == "SIM") {
            // Armazena informações sobre o usuário conectado na sessão
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_username'] = $row['nome_usuario'];
            $_SESSION['user_type'] = $row['tipo_usuario'];
            // Retorna uma mensagem de sucesso com o tipo de usuário
            echo json_encode(['message' => 'Login bem-sucedido!', 'userType' => $row['tipo_usuario']]);
        } else {
            // Retorna uma mensagem de erro
            echo json_encode(['message' => 'Por favor verifique seu email antes de fazer login!']);
        }
    } else {
        // Retorna uma mensagem de erro
        echo json_encode(['message' => 'Senha incorreta!']);
    }
} else {
    // Retorna uma mensagem de erro
    echo json_encode(['message' => 'Usuário não encontrado!']);
}