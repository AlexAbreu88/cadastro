<?php
$token = $_GET['token'];

$db = new SQLite3('db/myDatabase.db');

// Verifica se o token é válido
$stmt = $db->prepare('SELECT * FROM tokens WHERE token = :token AND expires_at > :now');
$stmt->bindValue(':token', $token, SQLITE3_TEXT);
$stmt->bindValue(':now', date('Y-m-d H:i:s'), SQLITE3_TEXT);
$result = $stmt->execute();
$tokenData = $result->fetchArray();

if ($tokenData) {
    // Token válido, atualiza a coluna email_verificado do usuário
    $stmt = $db->prepare('UPDATE users SET email_verificado = "SIM" WHERE id = :user_id');
    $stmt->bindValue(':user_id', $tokenData['user_id'], SQLITE3_INTEGER);
    $stmt->execute();
    // var_dump($stmt->execute());

    //Exclui o token do banco de dados
    $stmt = $db->prepare('DELETE FROM tokens WHERE id = :id');
    $stmt->bindValue(':id', $tokenData['id'], SQLITE3_INTEGER);
    $stmt->execute();

    echo 'Email verificado com sucesso!<br>';
    echo 'Podes fechar esta página e efetuar o Login no sistema!';
} else {
    // Token inválido ou expirado
    echo 'Link inválido ou expirado!';
}
