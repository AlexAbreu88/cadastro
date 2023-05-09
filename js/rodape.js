const dataAtual = new Date();
const anoAtual = dataAtual.getFullYear();


// console.log(anoAtual);
var info_rodape = document.querySelector(".rodape");
info_rodape.innerHTML = anoAtual;
