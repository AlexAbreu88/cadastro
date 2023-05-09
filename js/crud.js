//Declaração de Variáveis
var nome = document.getElementById("nome");
var email = document.getElementById("e-mail");
var addCV = document.getElementById("add_cv");
var editCV = document.getElementById("edit_cv");
var removeCV = document.getElementById("excluir_cv");
var addEspecial = document.getElementById("addEspecializacao");
var removeEspecial = document.getElementById("removeEspecializacao");
var addExp = document.getElementById("addExperiencia");
var removeExp = document.getElementById("removeExperiencia");
var usuarioLogado = document.querySelector('#usuario_logado');
var escolaridade = document.querySelector('#nivel-escolaridade');
var pretensaoSalarial = document.querySelector('#pretensao_salarial');
var seletorTitulo = document.querySelector('#tipo_curriculo');
var curriculoId;
var curriculoTitulo;
const idUsuarioLogado = document.querySelector('#user_id').value;
const nomeUsuarioLogado = document.querySelector('#user_username').value;
const diaLocal = document.querySelector('#current_date');

seletorTitulo.addEventListener("change", function () {
    if (this.value > 0) {
        editCV.disabled = false;
        removeCV.disabled = false;
    } else {
        editCV.disabled = true;
        removeCV.disabled = true;
    }
});
function cadastroCurriculo() {
    // Validar campos estáticos
    if (!escolaridade || !pretensaoSalarial) {
        alert('Por favor, preencha todos os campos obrigatórios!');
        return;
    }
    // Coletar valores dos campos dinâmicos
    const especializacoes = [];
    let especializacoesValidas = true;
    const inputsEspecializacoes = document.querySelectorAll('.cursos_especializacoes input');
    for (let i = 0; i < inputsEspecializacoes.length; i += 2) {
        const inputTitulo = inputsEspecializacoes[i];
        const inputDescricao = inputsEspecializacoes[i + 1];
        if (!inputTitulo.value || !inputDescricao.value) {
            especializacoesValidas = false;
        }
        especializacoes.push({
            titulo: inputTitulo.value,
            descricao: inputDescricao.value
        });
    }
    const experiencias = [];
    let experienciasValidas = true;
    const inputsExperiencias = document.querySelectorAll('.exp_profissional input');
    for (let i = 0; i < inputsExperiencias.length; i += 2) {
        const inputTitulo = inputsExperiencias[i];
        const inputDescricao = inputsExperiencias[i + 1];
        if (!inputTitulo.value || !inputDescricao.value) {
            experienciasValidas = false;
        }
        experiencias.push({
            titulo: inputTitulo.value,
            descricao: inputDescricao.value
        });
    }
    // Validar campos dinâmicos
    if (!especializacoesValidas || !experienciasValidas) {
        alert('Por favor, preencha todos os campos obrigatórios!');
        return;
    }
    // Cria um objeto XMLHttpRequest
    const xhr2 = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr2.open('POST', 'crud.php');
    xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Envia os dados do formulário
    xhr2.send(`action=cadastroCurriculo&escolaridade=${escolaridade.value}&pretensaoSalarial=${pretensaoSalarial.value}&diaLocal=${diaLocal.textContent}&id_user=${idUsuarioLogado}&especializacoes=${JSON.stringify(especializacoes)}&experiencias=${JSON.stringify(experiencias)}&tipoCurriculo=${seletorTitulo.value}`);
    // Quando a solicitação for concluída
    xhr2.onload = function () {
        if (xhr2.status != 200) {
            alert(`Erro ${xhr2.status}: ${xhr2.statusText}`);
        } else {
            // Processa a resposta do script PHP
            console.log(xhr2.response);
            alert('Currículo registrado com sucesso!');
            // Recarregar a página
            location.reload();
        }
    };
}
function editarCurriculo() {
    // Coletar valores dos campos fixos
    curriculoId = seletorTitulo.value;
    curriculoTitulo = seletorTitulo.options[seletorTitulo.selectedIndex].text;
    // Coletar valores dos campos dinâmicos
    const especializacoes = [];
    let especializacoesValidas = true;
    const inputsEspecializacoes = document.querySelectorAll('.cursos_especializacoes input');
    for (let i = 0; i < inputsEspecializacoes.length; i += 2) {
        const inputTitulo = inputsEspecializacoes[i];
        const inputDescricao = inputsEspecializacoes[i + 1];
        if (!inputTitulo.value || !inputDescricao.value) {
            especializacoesValidas = false;
        }
        especializacoes.push({
            titulo: inputTitulo.value,
            descricao: inputDescricao.value
        });
    }
    const experiencias = [];
    let experienciasValidas = true;
    const inputsExperiencias = document.querySelectorAll('.exp_profissional input');
    for (let i = 0; i < inputsExperiencias.length; i += 2) {
        const inputTitulo = inputsExperiencias[i];
        const inputDescricao = inputsExperiencias[i + 1];
        if (!inputTitulo.value || !inputDescricao.value) {
            experienciasValidas = false;
        }
        experiencias.push({
            titulo: inputTitulo.value,
            descricao: inputDescricao.value
        });
    }
    // Checar valores dos objetos antes de enviar para o crud.php
    console.log('Valor do escolaridade: ' + escolaridade.value);
    console.log('Valor do pretensaoSalarial: ' + pretensaoSalarial.value);
    console.log('Valor do diaLocal: ' + diaLocal.textContent);
    console.log('Valor do idUsuarioLogado: ' + idUsuarioLogado);
    console.log('Valor do especializacoes: ' + JSON.stringify(especializacoes));
    console.log('Valor do experiencias: ' + JSON.stringify(experiencias));
    console.log('Valor do curriculoID: ' + curriculoId);
    console.log('Valor do curriculoTitulo: ' + curriculoTitulo);
    // Cria um objeto XMLHttpRequest
    const xhr2 = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr2.open('POST', 'crud.php');
    xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Envia os dados do formulário
    xhr2.send(`action=editarCurriculo&escolaridade=${escolaridade.value}&pretensaoSalarial=${pretensaoSalarial.value}&diaLocal=${diaLocal.textContent}&id_user=${idUsuarioLogado}&especializacoes=${JSON.stringify(especializacoes)}&experiencias=${JSON.stringify(experiencias)}&curriculoID=${curriculoId}&curriculoTitulo=${curriculoTitulo}`);
    // Quando a solicitação for concluída
    xhr2.onload = function () {
        if (xhr2.status != 200) {
            alert(`Erro ${xhr2.status}: ${xhr2.statusText}`);
        } else {
            // Processa a resposta do script PHP
            console.log(xhr2.response);
            alert('Currículo editdado com sucesso!');
            // Recarregar a página
            location.reload();
        }
    };
}
function excluirCurriculo() {
    // Exibir mensagem de confirmação
    if (confirm('Tem certeza de que deseja excluir este registro?')) {
        //Obter o ID do currículo
        curriculoId = seletorTitulo.value;
        // Cria um objeto XMLHttpRequest
        const xhr2 = new XMLHttpRequest();
        // Abre uma conexão com o script PHP
        xhr2.open('POST', 'crud.php');
        xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        // Envia os dados do formulário
        xhr2.send(`action=excluirCurriculo&curriculoID=${curriculoId}`);
        // Quando a solicitação for concluída
        xhr2.onload = function () {
            if (xhr2.status != 200) {
                alert(`Erro ${xhr2.status}: ${xhr2.statusText}`);
            } else {
                // Processa a resposta do script PHP
                console.log(xhr2.response);
                alert('Registro excluído com sucesso');
                // Recarregar a página
                location.reload();
            }
        };
    }
}
function buscarCurriculo() {
    // Fazer requisição AJAX para o arquivo PHP
    const xhr3 = new XMLHttpRequest();
    xhr3.open('POST', 'crud.php');
    xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr3.onload = function () {
        if (xhr3.status === 200) {
            // Coletar dados retornados pelo PHP
            // console.log(xhr3.responseText);
            const curriculos = JSON.parse(xhr3.responseText);
            // Popular elemento select com os títulos dos currículos
            seletorTitulo.innerHTML = '';
            // Criar opção "Selecione um currículo"
            var option = document.createElement('option');
            option.value = 0;
            option.textContent = 'Selecione um currículo';
            seletorTitulo.appendChild(option);
            // Adicionar opções com os títulos dos currículos
            // console.log(curriculos);
            curriculos.forEach(curriculo => {
                var option2 = document.createElement('option');
                option2.value = curriculo.id;
                option2.textContent = curriculo.titulo;
                seletorTitulo.appendChild(option2);
            });
        } else {
            alert('Erro ao carregar currículos!');
        }
    };
    xhr3.send('action=buscarCurriculo');
    usuarioLogado.innerHTML = nomeUsuarioLogado;
}
function buscarDadosCurriculo() {
    limparDadosObjetos();
    var valorSelecao = seletorTitulo.value;
    // Fazer requisição AJAX para o arquivo PHP
    const xhr3 = new XMLHttpRequest();
    xhr3.open('POST', 'crud.php');
    xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr3.onload = function () {
        if (xhr3.status === 200) {
            // Coletar dados retornados pelo PHP
            const curriculos = JSON.parse(xhr3.responseText);
            if (valorSelecao > 0) {
                const curriculoSelecionado = curriculos.find(curriculo => curriculo.id == valorSelecao);
                pretensaoSalarial.value = curriculoSelecionado.pretensao_salarial;
                escolaridade.value = curriculoSelecionado.escolaridade;
                // Adicionar especializações ao currículo
                const divEspecializacoes = document.querySelector('.cursos_especializacoes');
                curriculoSelecionado.especializacoes.forEach(especializacao => {
                    const labelTitulo = document.createElement('label');
                    labelTitulo.textContent = 'Título: ';
                    labelTitulo.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    const inputTitulo = document.createElement('input');
                    inputTitulo.type = 'text';
                    inputTitulo.value = especializacao.titulo;
                    inputTitulo.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    divEspecializacoes.appendChild(labelTitulo);
                    divEspecializacoes.appendChild(inputTitulo);

                    const labelDescricao = document.createElement('label');
                    labelDescricao.textContent = 'Descrição: ';
                    labelDescricao.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    const inputDescricao = document.createElement('input');
                    inputDescricao.type = 'text';
                    inputDescricao.value = especializacao.descricao;
                    inputDescricao.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    divEspecializacoes.appendChild(labelDescricao);
                    divEspecializacoes.appendChild(inputDescricao);
                });
                const divExperiencias = document.querySelector('.exp_profissional');
                curriculoSelecionado.experiencias.forEach(experiencia => {
                    const labelTitulo = document.createElement('label');
                    labelTitulo.textContent = 'Título: ';
                    labelTitulo.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    const inputTitulo = document.createElement('input');
                    inputTitulo.type = 'text';
                    inputTitulo.value = experiencia.titulo;
                    inputTitulo.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    divExperiencias.appendChild(labelTitulo);
                    divExperiencias.appendChild(inputTitulo);

                    const labelDescricao = document.createElement('label');
                    labelDescricao.textContent = 'Descrição: ';
                    labelDescricao.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    const inputDescricao = document.createElement('input');
                    inputDescricao.type = 'text';
                    inputDescricao.value = experiencia.descricao;
                    inputDescricao.classList.add('dinamico'); // Adicionar classe 'dinamico'
                    divExperiencias.appendChild(labelDescricao);
                    divExperiencias.appendChild(inputDescricao);
                });
            } else {
                limparDadosObjetos();
            }
        } else {
            alert('Erro ao carregar currículos!');
        }
    };
    xhr3.send('action=buscarDadosCurriculo');
}
function limparDadosObjetos() {
    escolaridade.value = 0;
    pretensaoSalarial.value = "";
    // Remover elementos dinâmicos de especializações
    const divEspecializacoes = document.querySelector('.cursos_especializacoes');
    const elementosDinamicosEspecializacoes = divEspecializacoes.querySelectorAll('.dinamico');
    elementosDinamicosEspecializacoes.forEach(elemento => {
        divEspecializacoes.removeChild(elemento);
    });
    const divExperiencias = document.querySelector('.exp_profissional');
    const elementosDinamicosExperiencias = divExperiencias.querySelectorAll('.dinamico');
    elementosDinamicosExperiencias.forEach(elemento => {
        divExperiencias.removeChild(elemento);
    });
}
function limparTabelas() {
    // Cria um objeto XMLHttpRequest
    const xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('POST', 'crud.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Envia a solicitação
    xhr.send(`action=limparTabelas`);
    // Quando a solicitação for concluída
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa a resposta do script PHP
            console.log(xhr.response);
        }
    };
}
// Chamar a função buscarCurriculo ao carregar a página
window.addEventListener('load', buscarCurriculo);