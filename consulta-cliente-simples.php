<?php

$enunciado = 'Criar uma consulta de clientes igual a que acabamos de fazer.
<br>
    Para isso, no arquivo "conexao.php", adicione antes do "return $pdo", o trecho de codigo abaixo:<br>
<code>
    <i>
        $query = "CREATE TABLE IF NOT EXISTS cliente (cliente_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nome TEXT, telefone TEXT, email TEXT, cidade TEXT)";
        <br>
        $pdo->exec($query);
    </i>
</code>
<br>
A classe deve ficar como abaixo:<br>
<code>
    function getConexao(){
    
        try {
            $pdo = new PDO(\'sqlite:db/contato.sqlite3\');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    
        $query = "CREATE TABLE IF NOT EXISTS contato (contato_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nome TEXT, sobrenome TEXT, endereco TEXT, telefone TEXT, email TEXT, nascimento TEXT)";
        $pdo->exec($query);
    
        $query = "CREATE TABLE IF NOT EXISTS cliente (cliente_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nome TEXT, telefone TEXT, email TEXT, cidade TEXT)";
        $pdo->exec($query);
    
        return $pdo;
    }
</code>';

//echo $enunciado;

echo "<hr>";


require_once 'conexao.php';

function getDados(){
    /** @var PDO $pdo */
    $pdo = getConexao();

    $query = "SELECT * FROM `cliente`";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    // percorrer os dados e colocar num array
    $aDados = array();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $aDados[] = $result;
    }

    return $aDados;
}

function getColunasTabela(){
    $html_colunas_tabela = '';

    // busca os dados do banco de dados
    $aDados = getDados();

    if (count($aDados)) {
        foreach ($aDados as $aContato) {
            // inicia linha
            $html_colunas_tabela .= "<tr>";

            $cliente_id = $aContato["cliente_id"];
            $nome       = $aContato["nome"];
            $telefone   = $aContato["telefone"];
            $email      = $aContato["email"];
            $cidade     = $aContato["cidade"];

            // Colunas
            $html_colunas_tabela .= "<td>$cliente_id</td>";
            $html_colunas_tabela .= "<td>$nome</td>";
            $html_colunas_tabela .= "<td>$telefone</td>";
            $html_colunas_tabela .= "<td>$email</td>";
            $html_colunas_tabela .= "<td>$cidade</td>";

            // Adiciona as acoes da tabela
            $html_colunas_tabela .= getAcoesCliente($cliente_id);

            // finaliza linha
            $html_colunas_tabela .= "</tr>";
        }
    } else {
        // Retorna uma coluna vazia
        $html_colunas_tabela .= "<tr><td colspan=\"7\" align='center'>Sem dados para exibir</td></tr>";
    }

    return $html_colunas_tabela;
}

function getAcoesCliente($cliente_id) {
    // Lista de alteracoes
    // 0 - Adicionar o header 'Ações'
    // 1 - Adicionar a classe css de botao 'button.css' com a pasta de css
    // 2 - Adicionar o codigo abaixo em cada linha
    // 3 - Adicionar o codigo js de alteracao via ajax
    // 4 - criar o arquivo "ajax_contato.php"
    // 5 - Criar as acoes do ajax
    // 6 - Criar a programacao via php de exclusao/alteracao
    // 7 - Criar a programacao via php de inclusao de dados

    $html_acao = '<td>
                        <button type="button" class="button green" 
                            onclick="editarCliente(' . $cliente_id . ')">Editar</button>
                   </td>
                   <td>
                        <button type="button" class="button red" 
                            onclick="excluirCliente(' . $cliente_id . ')">Excluir</button>
                    </td>';

    return $html_acao;
}

function carregaCabecalho() {
    $html = '<!DOCTYPE html>
            <html lang="pt-BR">
            
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="preconnect" href="https://fonts.gstatic.com">
                <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,400;0,700;1,200;1,800&display=swap"
                      rel="stylesheet">
            
                <!--ARQUIVOS CSS -->
                <link rel="stylesheet" href="css/button.css">
                <link rel="stylesheet" href="css/main.css">
                <link rel="stylesheet" href="css/button.css">
                <link rel="stylesheet" href="css/records.css">
                <link rel="stylesheet" href="css/modal.css">
                
                <!--ARQUIVOS SCRIPT -->
                <script src="js/jquery.min.js" defer></script>
                <script src="js/cliente.js" defer></script>
            
                <title>Cliente</title>
            </head>
            <body>';

    return $html;
}

//    document.getElementById('id').value           = oCliente.cliente_id;
//    document.getElementById('nome').value         = oCliente.nome;
//    document.getElementById('telefone').value     = oCliente.telefone;
//    document.getElementById('email').value        = oCliente.email;
//    document.getElementById('cidade').value       = oCliente.cidade;

function getAcoesInclusao(){
    return '<section class="acoes">
                <button type="button" class="button blue mobile" id="cadastrarCliente">Incluir</button>
                <button type="button" class="button green" id="consultarDadosCliente">Consultar</button>
                <button type="button" class="button red" id="limparDadosCliente">Limpar Consulta</button>
            </section>';
}

function getModalClientes(){
    return '<div class="modal" id="modal">
            <div class="modal-content">
                <header class="modal-header">
                    <h2>Novo Cliente</h2>
                    <span class="modal-close" id="modalClose">&#10006;</span>
                </header>
                <form id="form" class="modal-form">
                    <input type="hidden" id="cliente_id" class="modal-field" placeholder="Id">
                    
                    <input type="text" id="nome" data-index="new" class="modal-field" placeholder="Nome do Cliente" required value="Joao">
                    <input type="text" id="telefone" class="modal-field" placeholder="Telefone..." required value="(47)98854-7844">
                    <input type="email" id="email" class="modal-field" placeholder="E-mail..." required value="joao@email.com">
                    <input type="text" id="cidade" class="modal-field" placeholder="Cidade..." required value="Rio do Sul">
                </form>
                <footer class="modal-footer" id="modal-footer">
                    <button id="salvar" class="button green">Salvar</button>
                    <button id="cancelar" class="button blue">Cancelar</button>
                </footer>
            </div>
        </div>';
}

function carregaClientes(){
    $html_tabela = carregaCabecalho();

    $html_tabela .= '<a href="executa_insert_clientes.php" >Inserir Varios Clientes</a>';

    $html_tabela .= "<hr>";
    $html_tabela .= getAcoesInclusao();

    // Lista de Contatos em HTML com os dados do banco de dados(tabela html)
    $html_tabela .= "<table border='1' id=\"tableDados\">";
    //CONTINUAR DAQUI - FAZER A CONSULTA EM PHP

    $html_tabela .= "<caption><h1>Clientes</h1></caption>";

    $html_tabela .= "<thead>";
    // iniciando linha
    $html_tabela .= "    <tr>";

    //$query = "CREATE TABLE IF NOT EXISTS cliente
    // (
    //cliente_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    // nome TEXT,
    // telefone TEXT,
    // email TEXT,
    // cidade TEXT)";

    // colunas cabecalho
    $html_tabela .= "    <th>Id</th>";
    $html_tabela .= "    <th>Nome</th>";
    $html_tabela .= "    <th>Telefone</th>";
    $html_tabela .= "    <th>E-mail</th>";
    $html_tabela .= "    <th>Cidade</th>";
    $html_tabela .= "    <th colspan='2'>Ações</th>";

    // fechando linha
    $html_tabela .= "    </tr>";
    $html_tabela .= "</thead>";

    // dados do corpo da tabela
    $html_tabela .= "<tbody>";

    // Colunas
    $html_tabela .= getColunasTabela();

    $html_tabela .= "</tbody>";

    $html_tabela .= "</table>";

    $html_tabela .= getModalClientes();

    echo $html_tabela;
}

carregaClientes();
