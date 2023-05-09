// Obtém uma referência ao elemento do formulário
var form = document.getElementById('form_usuario');
var usuarioId = document.querySelector('#user_id');
var CPF = document.querySelector('#CPF');
// Chama a função cadastroUsuario() passando a referência ao formulário como argumento
cadastroUsuario(form);

function cadastroUsuario(form) {
    // Adiciona um ouvinte de evento para o envio do formulário
    form.addEventListener('submit', function (event) {
        // Impede o comportamento padrão do formulário (recarregar a página)
        event.preventDefault();
        // Cria um objeto FormData a partir do formulário
        var formData = new FormData(form);
        // console.log(formData);
        // Valida os dados do formulário
        if (!validateForm(formData)) {
            return;
        }
        // Cria um objeto XMLHttpRequest
        var xhr = new XMLHttpRequest();
        // Abre uma conexão com o script PHP
        xhr.open('POST', 'crud_usuario.php');
        // Envia os dados do formulário
        xhr.send(formData);
        // Quando a solicitação for concluída
        xhr.onload = function () {
            if (xhr.status != 200) {
                alert(`Erro ${xhr.status}: ${xhr.statusText}`);
            } else {
                // Processa a resposta do script PHP
                console.log(xhr.response);
                alert('Currículo registrado com sucesso!');
                // Recarregar a página
                location.reload();
            }
        };
    });
}

function pesquisarUsuario() {
    var email = prompt('Digite o e-mail usado no registro de usuário:');
    if (!email) {
        return;
    }
    var formData = new FormData();
    formData.append('pesquisar', true);
    formData.append('email', email);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'crud_usuario.php');
    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // console.log(xhr.response);
            var data = JSON.parse(xhr.response);
            if (data.length > 0) {
                var usuario = data[0];
                form.elements['user_id'].value = usuario['id'];
                form.elements['name'].value = usuario['nome_usuario'];
                form.elements['CPF'].value = usuario['CPF'];
                form.elements['data_nascimento'].value = usuario['data_nascimento'];
                form.elements['sexo'].value = usuario['sexo'];
                form.elements['estado_civil'].value = usuario['estado_civil'];
                form.elements['tipo_usuario'].value = usuario['tipo_usuario'];
                form.elements['email'].value = usuario['email'];
                // Desabilita o campo de entrada do CPF
                document.getElementById('CPF').disabled = true;
            }
        };
    }
}

function editarUsuario(form, usuarioId) {
    var formData = new FormData(form);
    formData.append('editar', true);
    formData.set('user_id', usuarioId.value);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'crud_usuario.php');
    xhr.send(formData);
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            console.log(xhr.response);
            alert('Currículo alterado com sucesso!');
            // Recarregar a página
            location.reload();
        }
    };
}

function excluirUsuario(form, usuarioId) {
    // Exibir mensagem de confirmação
    if (confirm('Tem certeza de que deseja excluir este registro?')) {
        var formData = new FormData(form);
        formData.append('excluir', true);
        formData.set('user_id', usuarioId.value);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'crud_usuario.php');
        xhr.send(formData);
        xhr.onload = function () {
            if (xhr.status != 200) {
                alert(`Erro ${xhr.status}: ${xhr.statusText}`);
            } else {
                console.log(xhr.response);
                alert('Currículo excluído com sucesso!');
                // Recarregar a página
                location.reload();
            }
        };
    }
}

function validateForm(formData) {
    // Verifica se o campo nome está vazio
    if (!formData.get('name')) {
        alert('Por favor insira um nome.');
        return false;
    }
    // Verifica se o campo CPF está vazio
    if (!formData.get('CPF')) {
        alert('Por favor insira um CPF.');
        return false;
    }
    // Valida o CPF
    if (!validateCPF(formData.get('CPF'))) {
        alert('Por favor insira um CPF válido.');
        return false;
    }
    // Verifica se o campo data de nascimento está vazio
    if (!formData.get('data_nascimento')) {
        alert('Por favor insira uma data de nascimento.');
        return false;
    }
    // Verifica se o campo sexo está vazio
    if (!formData.get('sexo')) {
        alert('Por favor selecione um sexo.');
        return false;
    }
    // Verifica se o campo estado civil está vazio
    if (!formData.get('estado_civil')) {
        alert('Por favor selecione um estado civil.');
        return false;
    }
    // Verifica se o campo email está vazio
    if (!formData.get('email')) {
        alert('Por favor insira um email.');
        return false;
    }
    // Verifica se o campo senha está vazio
    if (!formData.get('password')) {
        alert('Por favor insira uma senha.');
        return false;
    }
    return true;
}

function formatCPF(cpf) {
    // Remove qualquer caractere não numérico
    cpf = cpf.replace(/\D/g, '');
    // Adiciona os pontos e o traço de formatação
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    return cpf;
}

var cpfField = document.getElementById('CPF');
cpfField.addEventListener('input', function () {
    this.value = formatCPF(this.value);
});

function validateCPF(cpf) {
    // Remove qualquer caractere não numérico
    cpf = cpf.replace(/\D/g, '');

    // Verifica se o CPF tem 11 dígitos
    if (cpf.length != 11) {
        return false;
    }

    // Verifica se o CPF é uma sequência de dígitos repetidos (inválido)
    if (/^(\d)\1+$/.test(cpf)) {
        return false;
    }

    // Calcula o primeiro dígito verificador
    var sum = 0;
    for (var i = 0; i < 9; i++) {
        sum += parseInt(cpf[i]) * (10 - i);
    }
    var remainder = sum % 11;
    var firstDigit = (remainder < 2) ? 0 : 11 - remainder;

    // Verifica se o primeiro dígito verificador é válido
    if (firstDigit != parseInt(cpf[9])) {
        return false;
    }

    // Calcula o segundo dígito verificador
    sum = 0;
    for (var i = 0; i < 10; i++) {
        sum += parseInt(cpf[i]) * (11 - i);
    }
    remainder = sum % 11;
    var secondDigit = (remainder < 2) ? 0 : 11 - remainder;

    // Verifica se o segundo dígito verificador é válido
    if (secondDigit != parseInt(cpf[10])) {
        return false;
    }

    return true;
}