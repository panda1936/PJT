function connecter() {
    document.getElementById("connecter").style.left = "60px";
    document.getElementById("inscrire").style.left = "600px";
    document.getElementById("choix-connecter").style.borderBottom = "3px solid #1161ee";
    document.getElementById("choix-connecter").style.opacity = "1";
    document.getElementById("choix-inscrire").style.borderBottom = "none";
    document.getElementById("choix-inscrire").style.opacity = "0.7";
}
function inscrire() {
    document.getElementById("connecter").style.left = "-500px";
    document.getElementById("inscrire").style.left = "60px";
    document.getElementById("choix-inscrire").style.borderBottom = "3px solid #1161ee";
    document.getElementById("choix-inscrire").style.opacity = "1";
    document.getElementById("choix-connecter").style.borderBottom = "none";
    document.getElementById("choix-connecter").style.opacity = "0.7";
}