'use strict';

const openModal = () => {
    document.getElementById('modal').classList.add('active');
    document.getElementById('modal-footer').classList.add('active');
};

const closeModal = () => {
    //clearFields();
    document.getElementById('modal').classList.remove('active');
};

function editarContato(contato_id){
    console.log("Buscando dados para alteracao do registro...");

    const contato = {
        contato_id: contato_id
    };

    var oDados = {"funcao":"loadAjaxUpdateRegistro",
                  "contato": JSON.stringify(contato),
                  "acao" : "BUSCA_DADOS_ALTERACAO"
                };

    $.ajax({
        url:"ajax_contato_aula.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){

            console.log("dados enviados:" + response);

            // Carrega os dados na tela
            const oContato = JSON.parse(response);

            oContato.id = oContato.contato_id;

            carregaCampos(oContato);

            // Abre o Modal
            openModal();
        }
    })
}

function excluirContato(contato_id){
    alert("Excluindo registro...");
    const contato = {
        contato_id: contato_id
    };

    loadAjaxUpdateRegistro(contato, "EXECUTA_EXCLUSAO");
}

const isValidFields = () => {
    return document.getElementById('form').reportValidity()
};

// Seta os valores nos campos do formulario
const carregaCampos = (oContato) => {
    document.getElementById('contato_id').value= oContato.id;
    document.getElementById('nome').value      = oContato.nome;
    document.getElementById('sobrenome').value = oContato.sobrenome;
    document.getElementById('endereco').value  = oContato.endereco;
    document.getElementById('telefone').value  = oContato.telefone;
    document.getElementById('email').value     = oContato.email;
    document.getElementById('nascimento').value= oContato.nascimento;
    document.getElementById('nome').dataset.index = oContato.index;
};

const updateDados = () => {
    if (isValidFields()) {
        const index = document.getElementById('nome').dataset.index;
        if (index == 'new') {
            const contato = {
                nome: document.getElementById('nome').value,
                sobrenome: document.getElementById('sobrenome').value,
                endereco: document.getElementById('endereco').value,
                telefone: document.getElementById('telefone').value,
                email: document.getElementById('email').value,
                nascimento: document.getElementById('nascimento').value
            };

            loadAjaxUpdateRegistro(contato, "EXECUTA_INCLUSAO");
        } else {
            const contato = {
                contato_id:document.getElementById('contato_id').value,
                nome: document.getElementById('nome').value,
                sobrenome: document.getElementById('sobrenome').value,
                endereco: document.getElementById('endereco').value,
                telefone: document.getElementById('telefone').value,
                email: document.getElementById('email').value,
                nascimento: document.getElementById('nascimento').value
            };

            loadAjaxUpdateRegistro(contato, "EXECUTA_ALTERACAO");
        }

        closeModal();
    }
};

function loadAjaxUpdateRegistro (oDadosContato, acao){

    var oDados = {"funcao":"loadAjaxUpdateRegistro", "contato": JSON.stringify(oDadosContato), "acao" : acao};

    $.ajax({
        url:"ajax_contato_aula.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){
            console.log("dados enviados:" + response);

            // Atualiza a consulta
            loadAjaxConsulta();
        }
    })
}

function loadAjaxConsulta (){
    var oDados = {
        "acao"    : "EXECUTA_CONSULTA",
        "campo"   : document.querySelector("#campo").value,
        "operador": document.querySelector("#operador").value,
        "valor"   : document.querySelector("#valor").value
    };

    $.ajax({
        url:"ajax_contato_aula.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){
            console.log("Retorno Consulta(AJAX):" + JSON.stringify(response));

            const aDados = JSON.parse(response);

            clearTable();

            aDados.forEach(createRow);
        }
    })
}

const clearTable = () => {
    const rows = document.querySelectorAll('#tableDados>tbody tr');
    rows.forEach(row => row.parentNode.removeChild(row));
};

const createRow = (contato, index) => {
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>${contato.contato_id}</td>
        <td>${contato.nome}</td>
        <td>${contato.sobrenome}</td>
        <td>${contato.endereco}</td>
        <td>${contato.telefone}</td>
        <td>${contato.email}</td>
        <td>${contato.nascimento}</td>
        <td>
            <button type="button" class="button green" onclick="editarContato(${contato.contato_id})">Editar</button>
        </td>
        <td>
            <button type="button" class="button red" onclick="excluirContato(${contato.contato_id})">Excluir</button>
        </td>
    `;
    document.querySelector('#tableDados>tbody').appendChild(newRow);
};

function incluirDados(){
    document.getElementById('contato_id').value = "";
    openModal();
}


