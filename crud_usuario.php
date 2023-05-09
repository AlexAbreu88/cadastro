<?php
error_log(print_r($_POST, true));
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$domain = $_SERVER['HTTP_HOST'];

// Conectar ao banco de dados SQLite3
$db = new SQLite3('db/myDatabase.db');
$db->busyTimeout(5000); // Define o tempo limite para 5 segundos
// Recebe os dados do formulário pelo método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['editar'])) {
        var_dump($_POST);
        editar_usuario($_POST['user_id']);
    } elseif (isset($_POST['excluir'])) {
        excluir_usuario($_POST['user_id']);
    } elseif (isset($_POST['pesquisar'])) {
        pesquisar_usuario($_POST['email']);
    } else {
        cadastrar_usuario();
    }
}

function cadastrar_usuario()
{
    global $db;
    global $domain;
    // Cria a tabela users se ela ainda não existir
    $db->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        nome_usuario TEXT, 
        email TEXT, 
        senha TEXT,
        CPF TEXT,
        data_nascimento TEXT,
        sexo TEXT,
        estado_civil TEXT,
        tipo_usuario TEXT,
        email_verificado TEXT DEFAULT "NÃO"
    )');
    // Cria a tabela tokens
    $db->exec('CREATE TABLE IF NOT EXISTS tokens (id INTEGER PRIMARY KEY, user_id INTEGER, token TEXT, expires_at TEXT)');
    // Obtém os dados do formulário
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $CPF = $_POST['CPF'];
    $data_nascimento = $_POST['data_nascimento'];
    $sexo = $_POST['sexo'];
    $estado_civil = $_POST['estado_civil'];
    $tipo_usuario = $_POST['tipo_usuario'];
    var_dump($name);
    var_dump($email);
    var_dump($password);
    var_dump($CPF);
    var_dump($data_nascimento);
    var_dump($sexo);
    var_dump($estado_civil);
    var_dump($tipo_usuario);

    // Gera um token único
    $token = bin2hex(random_bytes(16));
    // Criptografa a senha usando a função password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Conecta ao banco de dados SQLITE3
    $db = new SQLite3('db/myDatabase.db');
    // Verifica se o CPF informado já foi cadastrado
    if (cpfExists($db, $CPF)) {
        echo 'Já existe um usuário com este CPF!';
    } else {
        // Prepara uma declaração SQL para inserir os dados
        $stmt = $db->prepare('INSERT INTO users (nome_usuario, email, senha, CPF, data_nascimento, sexo, estado_civil, tipo_usuario) VALUES (:name, :email, :password, :CPF, :data_nascimento, :sexo, :estado_civil, :tipo_usuario)');
        // Vincula os valores aos parâmetros da declaração SQL
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
        $stmt->bindValue(':CPF', $CPF, SQLITE3_TEXT);
        $stmt->bindValue(':data_nascimento', $data_nascimento, SQLITE3_TEXT);
        $stmt->bindValue(':sexo', $sexo, SQLITE3_TEXT);
        $stmt->bindValue(':estado_civil', $estado_civil, SQLITE3_TEXT);
        $stmt->bindValue(':tipo_usuario', $tipo_usuario, SQLITE3_TEXT);
        // Executa a declaração SQL
        $result = $stmt->execute();
        // Verifica se a inserção foi bem-sucedida
        if ($result) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir dados!';
        }
    }
    // Armazena o token no banco de dados
    $stmt = $db->prepare('INSERT INTO tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
    $stmt->bindValue(':user_id', $db->lastInsertRowID(), SQLITE3_INTEGER);
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', strtotime('+1 day')), SQLITE3_TEXT);
    $stmt->execute();
    // Enviar email através do PHPMailer
    $mail = new PHPMailer(true);
    try {
        $json = file_get_contents('smtp.json');
        $config = json_decode($json, true);
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->Port = 2525;
        // Remetente e destinatário
        $mail->setFrom('alx.abreu08@gmail.com', 'Dev. Adm. Alex Abreu');
        $mail->addAddress($email);
        // Conteúdo do email
        $mail->isHTML(true);
        $mail->Subject = 'Não RESPONDA!!! Verifique seu email';
        $mail->Body = 'Clique no link abaixo para verificar seu email e ativar o uso do sistema:<br><a href="http://' . $domain . '/cadastro/verifyEmail.php?token=' . $token . '">Verificar Email</a>';
        // Envia o email
        $mail->send();
        echo "Email enviado com sucesso!";
    } catch (Exception $e) {
        echo "Erro ao enviar email: {$mail->ErrorInfo}";
    }
}
function cpfExists($db, $cpf)
{
    $stmt = $db->prepare('SELECT * FROM users WHERE CPF = :cpf');
    $stmt->bindValue(':cpf', $cpf, SQLITE3_TEXT);
    $result = $stmt->execute();
    return ($result->fetchArray() !== false);
}
function editar_usuario($user_id)
{
    global $db;
    // Obtém os dados do formulário
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $data_nascimento = $_POST['data_nascimento'];
    $sexo = $_POST['sexo'];
    $estado_civil = $_POST['estado_civil'];
    $tipousuario = $_POST['tipo_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Verifica se o índice CPF está definido na matriz $_POST
    if (isset($_POST['CPF'])) {
        $CPF = $_POST['CPF'];
    } else {
        // Define um valor padrão para $CPF se o índice CPF não estiver definido
        $CPF = 'CPF Já Registrado';
        var_dump($CPF);
    }
    var_dump($user_id);
    var_dump($name);
    var_dump($data_nascimento);
    var_dump($sexo);
    var_dump($estado_civil);
    var_dump($tipousuario);
    var_dump($email);
    // var_dump($password);
    // Conecta ao banco de dados SQLITE3
    $db = new SQLite3('db/myDatabase.db');
    // Define a variável $hashed_password fora do bloco condicional
    $hashed_password = '';
    if (!empty($_POST['password'])) {
        // Criptografa a senha usando a função password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Atualiza a senha no banco de dados
        $stmt = $db->prepare('UPDATE users SET nome_usuario = :name, email = :email, senha = :password, data_nascimento = :data_nascimento, sexo = :sexo, estado_civil = :estado_civil, tipo_usuario = :tipo_usuario WHERE id = :id');
        $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
    } else {
        // Remove a senha da declaração SQL
        $stmt = $db->prepare('UPDATE users SET nome_usuario = :name, email = :email, data_nascimento = :data_nascimento, sexo = :sexo, estado_civil = :estado_civil, tipo_usuario = :tipo_usuario WHERE id = :id');
        // Vincula os valores aos parâmetros da declaração SQL
        $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':data_nascimento', $data_nascimento, SQLITE3_TEXT);
        $stmt->bindValue(':sexo', $sexo, SQLITE3_TEXT);
        $stmt->bindValue(':estado_civil', $estado_civil, SQLITE3_TEXT);
        $stmt->bindValue(':tipo_usuario', $tipousuario, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($result){
            if ($db->changes() > 0) {
                echo 'Dados alterados com sucesso!';
            } else {
                echo 'Dados não foram modificados!';
            }
        }
    }
}
function excluir_usuario($user_id)
{
    // Obtém os dados do formulário
    $user_id = $_POST['user_id'];
    // Conecta ao banco de dados SQLITE3
    $db = new SQLite3('db/myDatabase.db');
    // Prepara uma declaração SQL para excluir os dados
    $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
    // Vincula o valor do ID ao parâmetro da declaração SQL
    $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
    // Executa a declaração SQL
    $result = $stmt->execute();
    // Verifica se a exclusão foi bem-sucedida
    if ($result) {
        echo 'Dados excluídos com sucesso!';
    } else {
        echo 'Erro ao excluir dados!';
    }
}
function pesquisar_usuario($email)
{
    // Conecta ao banco de dados SQLITE3
    $db = new SQLite3('db/myDatabase.db');
    // Prepara uma consulta SQL
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email);
    // Executa a consulta SQL
    $results = $stmt->execute();
    // Armazena os resultados em um array
    $data = array();
    while ($row = $results->fetchArray()) {
        $data[] = $row;
    }
    // Retorna os dados como JSON
    echo json_encode($data);
}