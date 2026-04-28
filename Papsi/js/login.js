// ==========================================
// 🛡️ PASAHITZA EGIAZTATZEKO
// ==========================================

document.querySelector("form").addEventListener("submit", function(event) {
    const pass = document.getElementById("password").value;
    const errorDiv = document.getElementById("pass-error");
    let mezuak = [];

    if (pass.length < 8) {
        mezuak.push("Gutxienez 8 karaktere behar dira.");
    }

    if (!/[A-Z]/.test(pass)) {
        mezuak.push("Letra maiuskula bat behar du.");
    }

    if (!/[0-9]/.test(pass)) {
        mezuak.push("Zenbaki bat behar du.");
    }

    if (mezuak.length > 0) {
        event.preventDefault(); 
        errorDiv.style.display = "block";
        errorDiv.innerHTML = mezuak.join("<br>");
    } else {
        errorDiv.style.display = "none";
    }
});