// Obtém o formulário pelo seu ID
var form = document.getElementById('loginForm');
// Adiciona um ouvinte de evento para o envio do formulário
form.addEventListener('submit', function (event) {
    // Impede o comportamento padrão do formulário (recarregar a página)
    event.preventDefault();
    // Cria um objeto FormData a partir do formulário
    var formData = new FormData(form);
    // Cria um objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('POST', 'login.php');
    // Envia os dados do formulário
    xhr.send(formData);
    // Quando a solicitação for concluída
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa a resposta do script PHP
            var response = JSON.parse(xhr.response);
            console.log(response.message);
            if (response.userType == 0) {
                parent.window.location.href = 'curriculo.php';
            } else if (response.userType == 1) {
                parent.window.location.href = 'admin.php';
            }
        }
    };
});