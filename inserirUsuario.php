<?php

require_once("conexao.php");

function executaInclusao()
{

    $aListaUsuarios = array(
        array("joao@email.com", "3456"),
        array("pedro@email.com", "173456"),
        array("maria@email.com", "1993456"),
        array("rita@email.com", "112456"),
        array("felipe@email.com", "15786")
    );

    /** @var PDO $pdo */
    $pdo = getConexao();
    require_once("Bcrypt.php");

    // inicia excluindo todos
    $query = "delete from `usuario`";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    foreach ($aListaUsuarios as $aDadosUsuario) {
        $email = $aDadosUsuario[0];
        $senha = $aDadosUsuario[1];

        $senha_banco_dados = Bcrypt::hash($senha);

        $query = "INSERT INTO `usuario` (email, senha)
                        VALUES (:email, :senha)";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_banco_dados);

        $stmt->execute();
    }

    // array("joao@email.com"  , "3456"),
    // listando usuarios
    $aDados = getDadosFromBancoDados();

    echo "<pre>" . print_r($aDados, true) . "</pre>";

    $stmt = null;
    $pdo = null;
}

function getDadosFromBancoDados()
{
    /** @var PDO $pdo */
    $pdo = getConexao();

    $query = "select * from `usuario`";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $aDados = array();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $aDados[] = $result;
    }

    $stmt = null;
    $pdo = null;

    return $aDados;
}

executaInclusao();
