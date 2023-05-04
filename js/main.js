// Acoes de inclusao
document.getElementById('incluirDados')
    .addEventListener('click', incluirDados);

document.getElementById('consultarDados')
    .addEventListener('click', loadAjaxConsulta);

document.getElementById('limparDados')
    .addEventListener('click', clearTable);

// Eventos
document.getElementById('cancelar')
    .addEventListener('click', closeModal);

document.getElementById('modalClose')
    .addEventListener('click', closeModal);

document.getElementById('salvar')
    .addEventListener('click', updateDados);




