const openModalLogin = () => {
    document.getElementById('modal-login').classList.add('active');
    document.getElementById('modal-footer-login').classList.add('active');
};

const closeModalLogin = () => {
    document.getElementById('modal-login').classList.remove('active');
};

const updateDadosLogin = () => {
    console.log("logando no sistema...");
    logarSistema();
};

function logarSistema() {
    var oDados = {
        "email": document.querySelector("#email").value,
        "senha": document.querySelector("#senha").value
    };

    $.ajax({
        url: "login.php?login=LOGAR_SISTEMA",
        type: "POST",
        async: true,
        data: oDados,
        success: function (response) {

            console.log("Retorno Login(AJAX):" + JSON.stringify(response));

            // debugger;
            response = JSON.parse(response);

            if (response.login) {
                window.location.href = "Home.php?login=USUARIO_LOGADO";
            } else {
                window.location.href = "login.php";
            }
        }
    })
}

// Acoes do modal login
// document.getElementById('loginSistema')
//.addEventListener('click', openModalLogin);

// Eventos
document.getElementById('cancelarLogin')
    .addEventListener('click', closeModalLogin);

document.getElementById('modalCloseLogin')
    .addEventListener('click', closeModalLogin);

document.getElementById('salvarLogin')
    .addEventListener('click', updateDadosLogin);