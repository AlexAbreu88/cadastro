<?php

error_log(print_r($_POST, true));

// Conectar ao banco de dados SQLite3
$db = new SQLite3('db/myDatabase.db');
$db->busyTimeout(5000); // Define o tempo limite para 5 segundos

// Criar tabela curriculos se ela ainda não existir
$db->exec('CREATE TABLE IF NOT EXISTS curriculos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT,
    escolaridade TEXT,
    pretensao_salarial INTEGER,
    dia_local TEXT,
    user_id INTEGER
)');

// Criar tabela minha_tabela_especializacoes se ela ainda não existir
$db->exec('CREATE TABLE IF NOT EXISTS minha_tabela_especializacoes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo_especializacao TEXT,
    descricao_especializacao TEXT,
    curriculo_id INTEGER
)');


// Criar tabela minha_tabela_experiencias se ela ainda não existir
$db->exec('CREATE TABLE IF NOT EXISTS minha_tabela_experiencias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo_experiencia TEXT,
    descricao_experiencia TEXT,
    curriculo_id INTEGER
)');

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'cadastroCurriculo') {
        // Gerar título do currículo
        $titulo = 'Currículo ' . date('Y-m-d H:i:s');
        // Coletar dados do formulário
        $escolaridade = $_POST['escolaridade'];
        $pretensaoSalarial = $_POST['pretensaoSalarial'];
        $diaLocal = $_POST['diaLocal'];
        $user_id = $_POST['id_user'];
        $especializacoes = json_decode($_POST['especializacoes']);
        $experiencias = json_decode($_POST['experiencias']);
        var_dump($escolaridade);
        var_dump($pretensaoSalarial);
        var_dump($diaLocal);
        var_dump($user_id);
        var_dump($especializacoes);
        var_dump($experiencias);
        // Inserir dados no banco de dados
        $stmt = $db->prepare('INSERT INTO curriculos (titulo, escolaridade, pretensao_salarial, dia_local, user_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->bindParam(1, $titulo);
        $stmt->bindParam(2, $escolaridade);
        $stmt->bindParam(3, $pretensaoSalarial);
        $stmt->bindParam(4, $diaLocal);
        $stmt->bindParam(5, $user_id);
        // Executa a declaração SQL
        $result = $stmt->execute();
        // Verifica se a inserção foi bem-sucedida
        if ($result) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir dados!';
        }
        // Inserir especializações em tabela separada
        if (!empty($especializacoes)) {
            foreach ($especializacoes as $especializacao) {
                // Atribuir valores às variáveis
                $titulo_especializacao = $especializacao->titulo;
                $descricao_especializacao = $especializacao->descricao;
                $stmt = $db->prepare('INSERT INTO minha_tabela_especializacoes (titulo_especializacao, descricao_especializacao, curriculo_id) VALUES (?, ?, ?)');
                $stmt->bindParam(1, $titulo_especializacao);
                $stmt->bindParam(2, $descricao_especializacao);
                $stmt->bindParam(3, $curriculo_id);
                // Executa a declaração SQL
                $resulta = $stmt->execute();
                // Verifica se a inserção foi bem-sucedida
                if ($resulta) {
                    echo 'Dados inseridos com sucesso!';
                } else {
                    echo 'Erro ao inserir dados!';
                }
            }
        }
        // Inserir experiências profissionais em tabela separada
        if (!empty($experiencias)) {
            foreach ($experiencias as $experiencia) {
                // Atribuir valores às variáveis
                $titulo_experiencia = $experiencia->titulo;
                $descricao_experiencia = $experiencia->descricao;
                $stmt = $db->prepare('INSERT INTO minha_tabela_experiencias (titulo_experiencia, descricao_experiencia, curriculo_id) VALUES (?, ?, ?)');
                $stmt->bindParam(1, $titulo_experiencia);
                $stmt->bindParam(2, $descricao_experiencia);
                $stmt->bindParam(3, $curriculo_id);
                // Executa a declaração SQL
                $resultb = $stmt->execute();
                // Verifica se a inserção foi bem-sucedida
                if ($resultb) {
                    echo 'Dados inseridos com sucesso!';
                } else {
                    echo 'Erro ao inserir dados!';
                }
            }
        }
        echo 'Formulário enviado com sucesso!';
    } elseif ($_POST['action'] === 'buscarCurriculo') {
        // Chamar a função de busca
        $curriculos = buscarDados($db);
        // Retornar os resultados em formato JSON
        echo json_encode($curriculos);
    } elseif ($_POST['action'] === 'editarCurriculo') {
        // Coletar dados do formulário
        $escolaridade = $_POST['escolaridade'];
        $pretensaoSalarial = $_POST['pretensaoSalarial'];
        $diaLocal = $_POST['diaLocal'];
        $user_id = $_POST['id_user'];
        $especializacoes = json_decode($_POST['especializacoes']);
        $experiencias = json_decode($_POST['experiencias']);
        $curriculo_id = $_POST['curriculoID'];
        $titulo = $_POST['curriculoTitulo'];
        var_dump($escolaridade);
        var_dump($pretensaoSalarial);
        var_dump($diaLocal);
        var_dump($user_id);
        var_dump($especializacoes);
        var_dump($experiencias);
        var_dump($curriculo_id);
        var_dump($titulo);
        $stmt = $db->prepare('UPDATE curriculos SET titulo = ?, escolaridade = ?, pretensao_salarial = ?, dia_local = ? WHERE id = ?');
        $stmt->bindParam(1, $titulo);
        $stmt->bindParam(2, $escolaridade);
        $stmt->bindParam(3, $pretensaoSalarial);
        $stmt->bindParam(4, $diaLocal);
        $stmt->bindParam(5, $curriculo_id);
        // Executa a declaração SQL
        $resulte = $stmt->execute();
        // Verifica se a atualização foi bem-sucedida
        if ($resulte) {
            echo 'Dados atualizados com sucesso!';
        } else {
            echo 'Erro ao atualizar dados!';
        }
        // Atualizar especializações em tabela separada
        if (!empty($especializacoes)) {
            foreach ($especializacoes as $especializacao) {
                // Atribuir valores às variáveis
                $titulo_especializacao = $especializacao->titulo;
                $descricao_especializacao = $especializacao->descricao;
                $stmt = $db->prepare('UPDATE minha_tabela_especializacoes SET titulo_especializacao = ?, descricao_especializacao = ? WHERE curriculo_id = ?');
                $stmt->bindParam(1, $titulo_especializacao);
                $stmt->bindParam(2, $descricao_especializacao);
                $stmt->bindParam(3, $curriculo_id);
                // Executa a declaração SQL
                $resultf = $stmt->execute();
                // Verifica se a atualização foi bem-sucedida
                if ($resultf) {
                    echo 'Dados atualizados com sucesso!';
                } else {
                    echo 'Erro ao atualizar dados!';
                }
            }
        }
        // Atualizar experiências profissionais em tabela separada
        if (!empty($experiencias)) {
            foreach ($experiencias as $experiencia) {
                // Atribuir valores às variáveis
                $titulo_experiencia = $experiencia->titulo;
                $descricao_experiencia = $experiencia->descricao;
                $stmt = $db->prepare('UPDATE minha_tabela_experiencias SET titulo_experiencia = ?, descricao_experiencia = ? WHERE curriculo_id = ?');
                $stmt->bindParam(1, $titulo_experiencia);
                $stmt->bindParam(2, $descricao_experiencia);
                $stmt->bindParam(3, $curriculo_id);
                // Executa a declaração SQL
                $resultg = $stmt->execute();
                // Verifica se a atualização foi bem-sucedida
                if ($resultg) {
                    echo 'Dados atualizados com sucesso!';
                } else {
                    echo 'Erro ao atualizar dados!';
                }
            }
        }
        echo 'Formulário editado com sucesso!';
    } elseif ($_POST['action'] === 'excluirCurriculo') {
        $curriculo_id = $_POST['curriculoID'];
        var_dump($curriculo_id);
        
        // Excluir registro da tabela curriculos
        $stmt = $db->prepare('DELETE FROM curriculos WHERE id = ?');
        $stmt->bindParam(1, $curriculo_id);
        $result = $stmt->execute();
        // Verificar se a exclusão foi bem-sucedida
        if ($result) {
            echo 'Dados excluídos com sucesso!';
        } else {
            echo 'Erro ao excluir dados!';
        }
        // Excluir registros da tabela minha_tabela_especializacoes
        $stmt = $db->prepare('DELETE FROM minha_tabela_especializacoes WHERE curriculo_id = ?');
        $stmt->bindParam(1, $curriculo_id);
        $result = $stmt->execute();
        // Verificar se a exclusão foi bem-sucedida
        if ($result) {
            echo 'Dados excluídos com sucesso!';
        } else {
            echo 'Erro ao excluir dados!';
        }
        // Excluir registros da tabela minha_tabela_experiencias
        $stmt = $db->prepare('DELETE FROM minha_tabela_experiencias WHERE curriculo_id = ?');
        $stmt->bindParam(1, $curriculo_id);
        $result = $stmt->execute();
        // Verificar se a exclusão foi bem-sucedida
        if ($result) {
            echo 'Dados excluídos com sucesso!';
        } else {
            echo 'Erro ao excluir dados!';
        }
    } elseif ($_POST['action'] === 'limparTabelas') {
        // Limpar tabela curriculos
        $stmt = $db->prepare('DELETE FROM curriculos');
        $stmt->execute();
        // Limpar tabela minha_tabela_especializacoes
        $stmt = $db->prepare('DELETE FROM minha_tabela_especializacoes');
        $stmt->execute();
        // Limpar tabela minha_tabela_experiencias
        $stmt = $db->prepare('DELETE FROM minha_tabela_experiencias');
        $stmt->execute();
        echo 'Tabelas limpas com sucesso!';
    } elseif ($_POST['action'] === 'buscarDadosCurriculo') {
        // Chamar a função de busca
        $curriculos = buscarDados($db);
        // Retornar os resultados em formato JSON
        echo json_encode($curriculos);
    }
}

function buscarDados($db)
{
    // Preparar a consulta SQL para buscar currículos
    $stmt = $db->prepare('SELECT * FROM curriculos');
    // Executar a consulta
    $result = $stmt->execute();
    // Obter os resultados
    $resultados = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        // Adicionar especializações ao currículo
        $stmtEspecializacoes = $db->prepare('SELECT titulo_especializacao, descricao_especializacao FROM minha_tabela_especializacoes WHERE curriculo_id = ?');
        $stmtEspecializacoes->bindParam(1, $row['id']);
        $resultEspecializacoes = $stmtEspecializacoes->execute();
        $especializacoes = [];
        while ($rowEspecializacao = $resultEspecializacoes->fetchArray(SQLITE3_ASSOC)) {
            $especializacoes[] = [
                'titulo' => $rowEspecializacao['titulo_especializacao'],
                'descricao' => $rowEspecializacao['descricao_especializacao']
            ];
        }
        $row['especializacoes'] = $especializacoes;

        // Adicionar experiências ao currículo
        $stmtExperiencias = $db->prepare('SELECT titulo_experiencia, descricao_experiencia FROM minha_tabela_experiencias WHERE curriculo_id = ?');
        $stmtExperiencias->bindParam(1, $row['id']);
        $resultExperiencias = $stmtExperiencias->execute();
        $experiencias = [];
        while ($rowExperiencia = $resultExperiencias->fetchArray(SQLITE3_ASSOC)) {
            $experiencias[] = [
                'titulo' => $rowExperiencia['titulo_experiencia'],
                'descricao' => $rowExperiencia['descricao_experiencia']
            ];
        }
        $row['experiencias'] = $experiencias;

        // Adicionar currículo aos resultados
        $resultados[] = $row;
    }
    // Retornar os resultados
    return $resultados;
}
