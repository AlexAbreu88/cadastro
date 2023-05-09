<?php
// Inicia uma sessão
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Redireciona o usuário para a página de login
    header('Location: index.php');
    exit;
}
// Obtém os valores do nome de usuário e do ID do usuário da sessão
$userId = $_SESSION['user_id'];
$username = $_SESSION['user_username'];
?>
<!-- Inclua os valores do nome de usuário e do ID do usuário em elementos HTML ocultos -->
<input type="hidden" id="user_id" value="<?php echo $userId; ?>">
<input type="hidden" id="user_username" value="<?php echo $username; ?>">
<?php

// Inclui o conteúdo do arquivo curriculo_form.html
include('administrador.html');
