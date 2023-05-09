let tamanhoFonte = 14;
function aumentarFonte() {
    tamanhoFonte += 2;
    document.body.style.fontSize = tamanhoFonte + "px";
}

function diminuirFonte() {
    tamanhoFonte -= 2;
    document.body.style.fontSize = tamanhoFonte + "px";
}