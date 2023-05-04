<?php

require_once 'conexao.php';
abstract class ManutencaoPadrao
{

    protected abstract function executaInclusao();
    protected abstract function executaAlteracao();
    protected abstract function buscaDadosAlteracao();

    // Novos metodos
    protected abstract function getNomeTabela();
    protected abstract function getNomeColunaChave();

    public function __construct()
    {
        $this->processaDados();
    }

    protected function executaConsulta()
    {
        $aDados = $this->getDadosFromBancoDados();

        echo json_encode($aDados);
    }

    protected function executaExclusao()
    {
        $registro = json_decode($_POST[$this->getNomeTabela()], true);

        $chave_id = $registro[$this->getNomeColunaChave()];

        $query = "DELETE FROM `" . $this->getNomeTabela() . "` WHERE `" . $this->getNomeColunaChave() . "` = :" . $this->getNomeColunaChave();

        /** @var PDO $pdo */
        $pdo = getConexao();

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':' . $this->getNomeColunaChave(), $chave_id, PDO::PARAM_INT);

        $stmt->execute();

        $stmt = null;
        $pdo = null;
    }

    protected function getDadosFromBancoDados($chave_id = false)
    {
        /** @var PDO $pdo */
        $pdo = getConexao();

        $query = $this->getQueryPadrao($chave_id);

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $aDados = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $aDados[] = $result;
            if ($chave_id) {
                $aDados = $result;
            }
        }

        $stmt = null;
        $pdo = null;

        return $aDados;
    }

    protected function processaDados()
    {
        if (isset($_POST["acao"])) {
            $acao = $_POST["acao"];

            switch ($acao) {
                case "EXECUTA_CONSULTA":
                    $this->executaConsulta();
                    break;
                case "EXECUTA_INCLUSAO":
                    $this->executaInclusao();
                    break;
                case "EXECUTA_ALTERACAO":
                    $this->executaAlteracao();
                    break;
                case "BUSCA_DADOS_ALTERACAO":
                    $this->buscaDadosAlteracao();
                    break;
                case "EXECUTA_EXCLUSAO":
                    $this->executaExclusao();
                    break;
            }
        } else {
            echo json_encode(array("mensagem" => "Funcao invalida!"));
        }
    }

    protected function getQueryPadrao($chave_id = false)
    {
        if ($chave_id) {
            $query = "SELECT * FROM `" . $this->getNomeTabela() . "` WHERE `" . $this->getNomeColunaChave() . "` = " . $chave_id;

            return $query;
        }

        $campo    = isset($_POST["campo"]) ? $_POST["campo"] : false;
        $operador = isset($_POST["operador"]) ? $_POST["operador"] : false;
        $valor    = isset($_POST["valor"]) ? $_POST["valor"] : false;

        $query = "SELECT * FROM `" . $this->getNomeTabela() . "`";
        if ($campo && $operador && $valor) {

            if ($campo == $this->getNomeColunaChave()) {
                // Transforma o valor para inteiro
                $valor = (int)$valor;

                // Corrige o erro de valor de texto em campo numerico
                if ($valor == 0) {
                    $valor = -1;
                    $operador = "menor";
                }
            }

            if ($operador == "maior") {
                $operador = ">";
            } else if ($operador == "menor") {
                $operador = "<";
            } else if ($operador == "igual") {
                // Se for a chave e um campo inteiro
                if ($campo == $this->getNomeColunaChave()) {
                    $operador = "=";
                } else {
                    $operador = " like ";
                    $valor = "'%" . $valor . "%'";
                }
            }

            $query = "SELECT * FROM `" . $this->getNomeTabela() . "` WHERE " . $campo . $operador . $valor;
        }

        return $query;
    }
}
