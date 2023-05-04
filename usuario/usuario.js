'use strict';

const openModal = () => document.getElementById('modal')
    .classList.add('active');

const closeModal = () => {
    //clearFields();
    document.getElementById('modal').classList.remove('active');
};

const getLocalStorage = () => JSON.parse(localStorage.getItem('db_client')) ?? [];

const setLocalStorage = (dbClient) => localStorage.setItem("db_client", JSON.stringify(dbClient));

// CRUD - create read update delete
const deleteClient = (index) => {
    const dbClient = readClient();
    dbClient.splice(index, 1);
    setLocalStorage(dbClient);
};

const updateClient = (index, client) => {
    const dbClient = readClient();
    dbClient[index] = client;
    setLocalStorage(dbClient);
};

const readClient = () => getLocalStorage();

const createClient = (client) => {
    const dbClient = readClient();
    dbClient.push(client);
    setLocalStorage(dbClient)
};

const isValidFields = () => {
    return document.getElementById('form').reportValidity()
};

//Interação com o layout

const clearFields = () => {
    const fields = document.querySelectorAll('.modal-field');
    fields.forEach(field => field.value = "");
    document.getElementById('nome').dataset.index = 'new'
};

const saveClient = () => {
    if (isValidFields()) {
        const index = document.getElementById('cadastro-nome').dataset.index;
        if (index == 'new') {
            const client = {
                nome: document.getElementById('cadastro-nome').value,
                email: document.getElementById('cadastro-email').value,
                celular: document.getElementById('cadastro-senha').value
            };
            createClient(client);

            updateClientAjax(client, "INCLUSAO");
        } else {
            const client = {
                id: document.getElementById('cadastro-id').value,
                nome: document.getElementById('cadastro-nome').value,
                email: document.getElementById('cadastro-email').value,
                senha: document.getElementById('cadastro-senha').value
            };
            updateClient(index, client);

            updateClientAjax(client, "ALTERACAO");
        }

        updateTable();
        closeModal();
    }
};

const createRow = (client, index) => {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${client.id}</td>
        <td>${client.nome}</td>
        <td>${client.email}</td>
        <td>${client.senha}</td>
        <td>
            <button type="button" class="button green" id="edit-${index}">Editar</button>
            <button type="button" class="button red" id="delete-${index}" >Excluir</button>
        </td>
    `;
    document.querySelector('#tableClient>tbody').appendChild(newRow);
};

const clearTable = () => {
    const rows = document.querySelectorAll('#tableClient>tbody tr');
    rows.forEach(row => row.parentNode.removeChild(row));
};

const updateTable = () => {
    const dbClient = readClient();
    clearTable();
    dbClient.forEach(createRow);
};

const fillFields = (client) => {
    document.getElementById('cadastro-id').value = client.id;
    document.getElementById('cadastro-nome').value = client.nome;
    document.getElementById('cadastro-email').value = client.email;
    document.getElementById('cadastro-senha').value = client.senha;
    document.getElementById('cadastro-nome').dataset.index = client.index;
};

const editClient = (index) => {
    const client = readClient()[index];
    client.index = index;
    fillFields(client);
    openModal()
};

const editDelete = (event) => {
    if (event.target.type == 'button') {

        const [action, index] = event.target.id.split('-');

        if (action == 'edit') {
            editClient(index)
        } else {
            const client = readClient()[index];
            const response = confirm(`Deseja realmente excluir o usuário ${client.nome}`);
            if (response) {
                loadAjaxUpdateRegistro(client, "EXCLUSAO");

                deleteClient(index);

                updateTable()
            }
        }
    }
};

// Funcoes AJAX
function loadAjaxConsultaCliente(funcao) {
    var oDados = { "funcao": funcao };
    $.ajax({
        url: "ajax_usuario.php",
        type: "POST",
        async: true,
        data: oDados,
        success: function (response) {
            const aDados = JSON.parse(response);
            aDados.forEach(loadClients);
        }
    })
}

function loadAjaxUpdateRegistro(Client, acao) {

    var oDados = { "funcao": "loadAjaxUpdateRegistro", "usuario": JSON.stringify(Client), "acao": acao };

    $.ajax({
        url: "ajax_usuario.php",
        type: "POST",
        async: true,
        data: oDados,
        success: function (response) {
            console.log("dados enviados:" + response);
            consultarDadosUsuario();
        }
    })
}

function loadClients(Client, Key) {
    const dbClient = Client;

    createClient(dbClient);

    updateTable();

    console.log("Client(AJAX):" + JSON.stringify(dbClient));
}

const limpaDadosUsuario = () => {
    localStorage.setItem("db_client", "[]");

    updateTable()
};

const consultarDadosUsuario = () => {
    localStorage.setItem("db_client", "[]");

    loadAjaxConsultaCliente("listarRegistros");
};

const updateClientAjax = (Client, acao) => {
    localStorage.setItem("db_client", "[]");

    loadAjaxUpdateRegistro(Client, acao);
};

// Eventos
document.getElementById('cadastrarUsuario')
    .addEventListener('click', openModal);

document.getElementById('modalClose')
    .addEventListener('click', closeModal);

document.getElementById('salvar')
    .addEventListener('click', saveClient);

document.querySelector('#tableClient>tbody')
    .addEventListener('click', editDelete);

document.getElementById('cancelar')
    .addEventListener('click', closeModal);

document.getElementById('limparDadosUsuario')
    .addEventListener('click', limpaDadosUsuario);

document.getElementById('consultarDadosUsuario')
    .addEventListener('click', consultarDadosUsuario);



