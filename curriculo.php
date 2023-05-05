<?php
// Inicia uma sessão
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Redireciona o usuário para a página de login
    header('Location: index.php');
    exit;
}
// Inclui o conteúdo do arquivo profile.html
require_once('curriculo.html');
