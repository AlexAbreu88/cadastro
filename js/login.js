// Obtém o formulário pelo seu ID
var form = document.getElementById('loginForm');
// Adiciona um ouvinte de evento para o envio do formulário
form.addEventListener('submit', function (event) {
    // Impede o comportamento padrão do formulário (recarregar a página)
    event.preventDefault();
    // Cria um objeto FormData a partir do formulário
    var formData = new FormData(form);

    // console.log(formData);
    // Cria um objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    // debugger;
    xhr.open('POST', 'login.php');
    // Envia os dados do formulário
    xhr.send(formData);
    // Quando a solicitação for concluída
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa a resposta do script PHP
            console.log(xhr.response);
            // console.log('Redirecionando para curriculo.php');
            parent.window.location.href = 'curriculo.php';
        }
    };
});

function sumirH1() {
    parent.document.getElementById('meuH1').style.display = 'none';
    parent.history.pushState(null, '', '#curriculo');
    parent.document.title = "Currículo";
}