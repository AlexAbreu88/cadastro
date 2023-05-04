<?php

require_once 'conexao.php';

function getDados() {
    /** @var PDO $pdo */
    $pdo = getConexao();

    $query = "SELECT * FROM `contato`";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    // percorrer os dados e colocar num array
    $aDados = array();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $aDados[] = $result;
    }

    return $aDados;
}

function getColunasTabela(){
    $html_colunas_tabela = '';

    // busca os dados do banco de dados
    $aDados = getDados();

    if(count($aDados)){
        foreach ($aDados as $aContato){
            // inicia linha
            $html_colunas_tabela .= "<tr>";

            $contato_id = $aContato["contato_id"];
            $nome       = $aContato["nome"];
            $sobrenome  = $aContato["sobrenome"];
            $endereco   = $aContato["endereco"];
            $telefone   = $aContato["telefone"];
            $email      = $aContato["email"];
            $nascimento = $aContato["nascimento"];

            // Colunas
            $html_colunas_tabela .= "<td>$contato_id</td>";
            $html_colunas_tabela .= "<td>$nome</td>";
            $html_colunas_tabela .= "<td>$sobrenome</td>";
            $html_colunas_tabela .= "<td>$endereco</td>";
            $html_colunas_tabela .= "<td>$telefone</td>";
            $html_colunas_tabela .= "<td>$email</td>";
            $html_colunas_tabela .= "<td>$nascimento</td>";

            // Adiciona as acoes da tabela
            $html_colunas_tabela .= getAcoesContato($contato_id);

            // finaliza linha
            $html_colunas_tabela .= "</tr>";
        }
    } else {
        // Retorna uma coluna vazia
        $html_colunas_tabela .= "<tr><td colspan=\"7\" align='center'>Sem dados para exibir</td></tr>";
    }

    return $html_colunas_tabela;
}

function getAcoesContato($contato_id){
    // Lista de alteracoes
    // 0 - Adicionar o header 'Ações'
    // 1 - Adicionar a classe css de botao 'button.css' com a pasta de css
    // 2 - Adicionar o codigo abaixo em cada linha
    // 3 - Adicionar o codigo js de alteracao via ajax
    // 4 - criar o arquivo "ajax_contato.php"
    // 5 - Criar as acoes do ajax
    // 6 - Criar a programacao via php de exclusao/alteracao
    // 7 - Criar a programacao via php de inclusao de dados

    // Parte 2 - Adicionar o modal de insercao/alteracao
    $html_acao = '<td>
                        <button type="button" class="button green" onclick="editarContato(' . $contato_id . ')">Editar</button>
                   </td>
                   <td>
                        <button type="button" class="button red" onclick="excluirContato(' . $contato_id . ')">Excluir</button>
                    </td>';

    return $html_acao;
}

function carregaCabecalho(){
    $html = '<!DOCTYPE html>
            <html lang="pt-BR">
            
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="preconnect" href="https://fonts.gstatic.com">
                <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,400;0,700;1,200;1,800&display=swap"
                      rel="stylesheet">
            
                <link rel="stylesheet" href="css/main.css">
                <link rel="stylesheet" href="css/button.css">
                <link rel="stylesheet" href="css/records.css">
                <link rel="stylesheet" href="css/modal.css">
                
                <script src="js/jquery.min.js" defer></script>
                <script src="js/contato.js" defer></script>
            
                <title>Contato</title>
            </head>
            <body>';

    return $html;
}

function getAcoesInclusao(){
    return '<section class="acoes">
                <button type="button" class="button blue mobile" id="cadastrarCliente">Incluir</button>
                <button type="button" class="button green" id="consultarDadosCliente">Consultar</button>
                <button type="button" class="button red" id="limparDadosCliente">Limpar Consulta</button>
            </section>';
}

function getModalContatos(){
    return '<div class="modal" id="modal">
            <div class="modal-content">
                <header class="modal-header">
                    <h2>Novo Contato</h2>
                    <span class="modal-close" id="modalClose">&#10006;</span>
                </header>
                <form id="form" class="modal-form">
                    <input type="hidden" id="id" class="modal-field" placeholder="Id">
                    <input type="text" id="nome" data-index="new" class="modal-field" placeholder="Nome do Cliente" required value="Joao">
                    <input type="text" id="sobrenome" class="modal-field" placeholder="Sobrenome..." required value="da Silva">
                    <input type="text" id="endereco" class="modal-field" placeholder="Endereço..." required value="Estrada Porto Rico">
                    <input type="text" id="telefone" class="modal-field" placeholder="Telefone..." required value="(47)98854-7844">
                    <input type="email" id="email" class="modal-field" placeholder="E-mail..." required value="joao@email.com">
                    <input type="text" id="nascimento" class="modal-field" placeholder="Data Nascimento..." required value="1986/07/19">
                </form>
                <footer class="modal-footer" id="modal-footer">
                    <button id="salvar" class="button green">Salvar</button>
                    <button id="cancelar" class="button blue">Cancelar</button>
                </footer>
            </div>
        </div>';
}

function carregaContatos(){
    $html_tabela = carregaCabecalho();

    $html_tabela .= "<header>
                        <h1 class=\"header-title\"> Contatos</h1>
                    </header>";
    
    $html_tabela .= getAcoesInclusao();

    // Lista de Contatos em HTML com os dados do banco de dados(tabela html)
    $html_tabela .= "<table border='1' id=\"tableDados\">";

    $html_tabela .= "<thead>";
    // iniciando linha
    $html_tabela .= "    <tr>";

    // colunas cabecalho
    $html_tabela .= "    <th>Id</th>";
    $html_tabela .= "    <th>Nome</th>";
    $html_tabela .= "    <th>Sobrenome</th>";
    $html_tabela .= "    <th>Endereco</th>";
    $html_tabela .= "    <th>Telefone</th>";
    $html_tabela .= "    <th>E-mail</th>";
    $html_tabela .= "    <th>Nascimento</th>";
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

    $html_tabela .= getModalContatos();

    echo $html_tabela;
}

carregaContatos();
