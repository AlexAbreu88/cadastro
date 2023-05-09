<?php

error_log(print_r($_POST, true));

// Conectar ao banco de dados SQLite3
$db = new SQLite3('db/myDatabase.db');
$db->busyTimeout(5000); // Define o tempo limite para 5 segundos

// Recebe os dados do formulário pelo método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['coluna']) && isset($_POST['ordem']) && isset($_POST['valor'])) {
        pesquisar_usuario($_POST['coluna'], $_POST['ordem'], $_POST['valor']);
    }
}

function pesquisar_usuario($coluna, $ordem, $valor)
{
    // Conecta ao banco de dados SQLITE3
    $db = new SQLite3('db/myDatabase.db');
    // Verifica se os valores são válidos
    if ($coluna != '' && ($ordem == 'ASC' || $ordem == 'DESC') && $valor != '') {
        // Prepara uma consulta SQL com filtro
        $sql = "SELECT * FROM curriculos WHERE $coluna = :valor ORDER BY $coluna $ordem";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':valor', $valor);
    } else {
        // Prepara uma consulta SQL sem filtro
        $sql = "SELECT * FROM curriculos";
        $stmt = $db->prepare($sql);
    }
    // Exibe a consulta SQL e os valores dos parâmetros no log de erros do PHP
    error_log("SQL: $sql");
    error_log("Parâmetros: coluna=$coluna, ordem=$ordem, valor=$valor");
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
