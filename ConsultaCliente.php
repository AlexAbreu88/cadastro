<?php

require_once 'ConsultaPadrao.php';
class ConsultaCliente extends ConsultaPadrao {

    protected function getNomeTabela(){
        return "cliente";
    }

    protected function getColunasTabela(){
        $html_colunas_tabela = '';

        // busca os dados do banco de dados
        $aDados = $this->getDados();

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
                $html_colunas_tabela .= $this->getAcoesCliente($cliente_id);

                // finaliza linha
                $html_colunas_tabela .= "</tr>";
            }
        } else {
            // Retorna uma coluna vazia
            $html_colunas_tabela .= "<tr><td colspan=\"7\" align='center'>Sem dados para exibir</td></tr>";
        }

        return $html_colunas_tabela;
    }

    protected function getAcoesCliente($cliente_id) {
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

    protected function getModalDados(){
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

    protected function getColunasCabecalhoTabela(){
        $html_tabela = "    <th>Id</th>";
        $html_tabela .= "    <th>Nome</th>";
        $html_tabela .= "    <th>Telefone</th>";
        $html_tabela .= "    <th>E-mail</th>";
        $html_tabela .= "    <th>Cidade</th>";
        $html_tabela .= "    <th colspan='2'>Ações</th>";

        return $html_tabela;
    }
    
    // NOVA PROGRAMACAO FILTROS
    protected function getColunasConsultaTabela(){
        return array("cliente_id", "nome", "telefone", "email", "cidade");
    }
}
// 189 linhas - inicio
new ConsultaCliente();
