<?php

function listarRegistros()
{
    $aDados = getDadosFromBancoDados();

    echo json_encode($aDados);
}

function loadAjaxUpdateRegistro()
{

    $registro = json_decode($_POST["usuario"], true);

    $acao = $_POST["acao"];

    if ($acao == "ALTERACAO") {
        alterarUsuario($registro);
    } else if ($acao == "INCLUSAO") {
        incluirUsuario($registro);
    } else if ($acao == "EXCLUSAO") {
        excluirUsuario($registro);
    } else {
        echo json_encode("Ação invalida!");
    }
}

function getPdoConnection()
{
    require_once '../ConexaoSQLitePadrao.php';

    $conexaoSQLite = new ConexaoSQLitePadrao;

    $pdo = $conexaoSQLite->getPdoConnection();

    return $pdo;
}

function excluirUsuario($registro)
{
    /** @var PDO $pdo */
    $pdo = getPdoConnection();

    $query = "DELETE FROM `usuario` WHERE `usuario_id` = :usuario_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':usuario_id', $registro["id"], PDO::PARAM_INT);
    $stmt->execute();
}

function incluirUsuario($registro)
{
    /** @var PDO $pdo */
    $pdo = getPdoConnection();

    $query = "INSERT INTO `usuario` (nome, email, senha)
                  VALUES(:nome, :email, :senha)";

    $stmt = $pdo->prepare($query);

    $stmt = setParam($stmt, $registro);

    $stmt->execute();
}

function alterarUsuario($registro)
{
    /** @var PDO $pdo */
    $pdo = getPdoConnection();

    $query = " UPDATE `usuario` SET
                      `nome`     = :nome,
                      `email`    = :email,
                      `senha`   = :senha
                WHERE `usuario_id` = :usuario_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':usuario_id', $registro['id']);

    $stmt = setParam($stmt, $registro);

    $stmt->execute();
}

function setParam($stmt, $registro)
{

    $stmt->bindParam(':nome', $registro['nome']);
    $stmt->bindParam(':email', $registro['email']);
    $stmt->bindParam(':senha', $registro['senha']);

    return $stmt;
}

function getDadosFromBancoDados()
{
    $pdo = getPdoConnection();

    $query = "SELECT usuario_id as id, nome, email, senha FROM `usuario`";

    $stmt = $pdo->prepare($query);

    $stmt->execute();
    $aDados = array();
    while ($aDadosColuna = $stmt->fetchObject()) {
        $aDados[] = $aDadosColuna;
    }

    return $aDados;
}

if (isset($_POST["funcao"])) {
    $funcao = $_POST["funcao"];

    switch ($funcao) {
        case "listarRegistros":
            listarRegistros();
            break;
        case "loadAjaxUpdateRegistro":
            loadAjaxUpdateRegistro();
            break;
    }
} else {
    echo json_encode(array("mensagem" => "Funcao invalida!"));
}
