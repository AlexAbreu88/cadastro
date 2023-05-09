// Tipo 01 por ID
// function mouseOver() {
//     document.getElementById("demo").style.color = "red";
// }

// function mouseOut() {
//     document.getElementById("demo").style.color = "black";
// }

// Tipo 02

var icone_facebook = document.querySelector(".icone_facebook");

icone_facebook.addEventListener("mouseover", function () {
    var legenda = document.querySelector("#legenda_facebook");
    legenda.classList.remove("esconder");
});

icone_facebook.addEventListener("mouseout", function () {
    var legenda = document.querySelector("#legenda_facebook");
    legenda.classList.add("esconder");
});

var icone_instagram = document.querySelector(".icone_instagram");

icone_instagram.addEventListener("mouseover", function () {
    var legenda = document.querySelector("#legenda_instagram");
    legenda.classList.remove("esconder");
});

icone_instagram.addEventListener("mouseout", function () {
    var legenda = document.querySelector("#legenda_instagram");
    legenda.classList.add("esconder");
});

var icone_linkedin = document.querySelector(".icone_linkedin");

icone_linkedin.addEventListener("mouseover", function () {
    var legenda = document.querySelector("#legenda_linkedin");
    legenda.classList.remove("esconder");
});

icone_linkedin.addEventListener("mouseout", function () {
    var legenda = document.querySelector("#legenda_linkedin");
    legenda.classList.add("esconder");
});

var icone_twitter = document.querySelector(".icone_twitter");

icone_twitter.addEventListener("mouseover", function () {
    var legenda = document.querySelector("#legenda_twitter");
    legenda.classList.remove("esconder");
});

icone_twitter.addEventListener("mouseout", function () {
    var legenda = document.querySelector("#legenda_twitter");
    legenda.classList.add("esconder");
});

var icone_whatsapp = document.querySelector(".icone_whatsapp");

icone_whatsapp.addEventListener("mouseover", function () {
    var legenda = document.querySelector("#legenda_whatsapp");
    legenda.classList.remove("esconder");
});

icone_whatsapp.addEventListener("mouseout", function () {
    var legenda = document.querySelector("#legenda_whatsapp");
    legenda.classList.add("esconder");
});

var icone_youtube = document.querySelector(".icone_youtube");

icone_youtube.addEventListener("mouseover", function () {
    var legenda = document.querySelector("#legenda_youtube");
    legenda.classList.remove("esconder");
});

icone_youtube.addEventListener("mouseout", function () {
    var legenda = document.querySelector("#legenda_youtube");
    legenda.classList.add("esconder");
});

// salvar página em PDF

function downloadPDF() {
    const item = document.querySelector(".conteudo");

    var opt = {
        margin: 1,
        filename: "curriculo_html.pdf",
        html2canvas: { sclae: 2 },
        jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
    };
    html2pdf().set(opt).from(item).save();
}