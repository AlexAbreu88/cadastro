<?php

require_once 'conexao.php';
abstract class ConsultaPadrao
{

    protected abstract function getColunasTabela();
    protected abstract function getModalDados();
    protected abstract function getColunasCabecalhoTabela();
    protected abstract function getColunasConsultaTabela();

    public function __construct()
    {
        $logado = isset($_GET["login"]) ? $_GET["login"] : 999;
        if ($logado == "USUARIO_LOGADO") {
            $this->carregaDados();
        } else {
            // redirecionar para pagina de login
            header("Location: login.php");
        }
    }

    protected function getNomeTabela()
    {
        return "NOME TABELA INVALIDO!";
    }

    protected function getDados()
    {
        /** @var PDO $pdo */
        $pdo = getConexao();

        $query = "SELECT * FROM `" . $this->getNomeTabela() . "`";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        // percorrer os dados e colocar num array
        $aDados = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $aDados[] = $result;
        }

        return $aDados;
    }

    protected function carregaCabecalho()
    {
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
                <link rel="stylesheet" href="css/header.css">
                
                <script src="js/jquery.min.js" defer></script>
                
                <title>' . ucfirst($this->getNomeTabela()) . '</title>
            </head>
            <body>';

        $html .= $this->getHeaders();

        return $html;
    }

    protected function carregaDados()
    {
        $html_tabela = "<hr style='margin-top: 15px;'>";

        $html_tabela .= $this->carregaCabecalho();

        $html_tabela .= "<main>";

        $html_tabela .= "<hr>";

        $html_tabela .= $this->getFiltrosConsulta();

        $html_tabela .= $this->getAcoesInclusao();

        // Lista de Contatos em HTML com os dados do banco de dados(tabela html)
        $html_tabela .= "<table id=\"tableDados\" class=\"records\">";

        $html_tabela .= "<caption><h1>" . ucfirst($this->getNomeTabela()) . "</h1></caption>";

        $html_tabela .= "<thead>";
        // iniciando linha
        $html_tabela .= "    <tr>";

        // colunas cabecalho
        $html_tabela .= $this->getColunasCabecalhoTabela();

        // fechando linha
        $html_tabela .= "    </tr>";
        $html_tabela .= "</thead>";

        // dados do corpo da tabela
        $html_tabela .= "<tbody>";

        // Colunas
        $html_tabela .= $this->getColunasTabela();

        $html_tabela .= "</tbody>";

        $html_tabela .= "</table>";

        $html_tabela .= $this->getModalDados();

        $html_tabela .= $this->getModalLogin();

        $html_tabela .= $this->getFooter();

        echo $html_tabela;
    }

    protected function getModalLogin()
    {
        return '<div class="modal" id="modal-login">
                    <div class="modal-content" style="width: 40%">
                        <header class="modal-header">
                            <h2>Login</h2>
                            <span class="modal-close" id="modalCloseLogin">&#10006;</span>
                        </header>
                        <form id="form" class="modal-form" style="justify-content: center;">
                            <input type="email" id="emailLogin" class="modal-field" placeholder="E-mail..." required value="gelvazio@email.com">
                            
                            <input type="password" id="senha" class="modal-field" placeholder="Senha..." required value="123456">
                        </form>
                        <footer class="modal-footer" id="modal-footer-login" style="width: 100%">
                            <button id="salvarLogin" class="button green">Entrar</button>
                            <button id="cancelarLogin" class="button blue">Cancelar</button>
                        </footer>
                    </div>
                </div>';
    }

    protected function getAcoesInclusao()
    {
        return '<section class="acoes">
                    <button type="button" class="button blue mobile" id="incluirDados">Incluir</button>
                </section>';
    }

    // FUNCOES ADICIONADAS NOVAS.....
    // FUNCOES ADICIONADAS NOVAS.....
    // FUNCOES ADICIONADAS NOVAS.....
    // FUNCOES ADICIONADAS NOVAS.....
    protected function getHeaders()
    {
        $logado = isset($_GET["login"]) ? $_GET["login"] : 999;
        $usuario_logado = "";
        if ($logado == "USUARIO_LOGADO") {
            $usuario_logado = "?login=USUARIO_LOGADO";
        }

        return "<header class=\"header\">
                    <ul class=\"menu\">
                        <li><a href='Home.php" . $usuario_logado . "'>Home</a></li>
                        <li><a href='ConsultaCliente.php" . $usuario_logado . "'>Clientes</a></li>
                        <li><a href='ConsultaContato.php" . $usuario_logado . "'>Contatos</a></li>
                        <!--<li><a href='consulta-produto-simples.php'>Produtos</a></li>
                        <li><a href='consulta-venda-simples.php'>Vendas</a></li>                        
                        <li>Config
                            <ul>
                                <li>Admin</li>
                                <li>Usuarios</li>
                                <li>Relatorios</li>
                            </ul>
                        </li> -->
                        <li><a href='login.php'>Sair</a></li>
                    </ul>
                </header>";
    }

    protected function getFooter()
    {
        return '</main>
                <footer class="footer">
                    Copyright &copy; Prof. Gelvazio Camargo
                </footer>
            </body>
            ' . $this->getScriptFooter() . '
            </html>';
    }

    protected function getScriptFooter()
    {
        return '<script src="js/' . $this->getNomeTabela() . '.js" defer></script>
                <script src="js/login.js" defer></script>
                <script src="js/main.js" defer></script>';
    }

    protected function getFiltrosConsulta()
    {
        $aListaOpcoes = "";

        foreach ($this->getColunasConsultaTabela() as $opcao) {
            $aListaOpcoes .= "<option style='color: black;' value='" . $opcao . "'>" . $opcao . "</option>";
        }

        return '
            <style>
                select {
                    width: 85%;
                    height: 40px;
                    border-radius: 5px;
                    background-color: var(--primary-color);
                    font-size: 15px;
                    text-transform: uppercase;
                }
                
                select > option {
                    padding: 5px;
                }
            
                select  option {
                    font-size: 18px;
                }
                
                button {
                    border-radius: 5px;
                }
            </style>
            <section class="acoes">
                    <select name="campo" id="campo">
                        ' . $aListaOpcoes . '
                    </select>
                    <select name="operador" id="operador">
                        <option value="maior" selected>Maior</option>
                        <option value="menor">Menor</option>
                        <option value="igual">Igual</option>
                    </select>
                    <input style="width: 200px;" type="text" id="valor" name="valor" placeholder="Informe o filtro...">
                    <button type="button" class="button green" id="consultarDados">Consultar</button>
                    <button type="button" class="button red" id="limparDados">Limpar</button>
                    <button type="button" class="button green" id="loginSistema">Login</button>
                </section>';
    }
}
