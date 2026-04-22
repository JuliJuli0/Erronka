const cont = document.getElementById("historiala");

// =========================
// 🔄 HISTORIALA KARGATU
// =========================
function kargatuHistoriala() {
    fetch("php/get_historiala.php")
        .then(res => res.json())
        .then(data => {
          cont.innerHTML = ""; 
          data.forEach(item => {
              const div = document.createElement("div");
              div.className = "card";
              div.innerHTML = `
                  <h3>📦 Paketea: ${item.pakete_id} - ${item.hartzailea}</h3>
                  <p>📝 ${item.azalpena}</p>
                  <p>🕒 ${item.data_ordua}</p>
              `;
              cont.appendChild(div);
          });
        })
        .catch(error => console.error("Errorea:", error));
}

// Orria kargatzean exekutatu funtzioa
document.addEventListener("DOMContentLoaded", kargatuHistoriala);

// =========================
// 🔙 DASHBOARD-ERA ITZULI
// =========================
function itzuliDashboardera() {
    window.location.href = "/reparto/dashboard.php";
}

// =========================
// 🔍 BILAKETA FILTRATU
// =========================
function filtratu() {
    const textua = document.getElementById("bilatu").value.toLowerCase();
    const cards = document.querySelectorAll("#historiala .card");
    
    cards.forEach(card => {
        const contenido = card.textContent.toLowerCase();
        // Testua badauka erakutsi, bestela ezkutatu
        card.style.display = contenido.includes(textua) ? "block" : "none";
    });
}

// =========================
// 📄 XML ESPORTAZIOA
// =========================
function exportatuXML() {
    const cards = document.querySelectorAll("#historiala .card");
    
    if (cards.length === 0) {
        alert("Ez dago daturik esportatzeko.");
        return;
    }

    let xmlContent = '<?xml version="1.0" encoding="UTF-8"?>\n';
    xmlContent += '<historiala>\n';

    cards.forEach(card => {
        const titulo = card.querySelector("h3").textContent; 
        const azalpena = card.querySelector("p:nth-of-type(1)").textContent; 
        const data = card.querySelector("p:nth-of-type(2)").textContent; 

        xmlContent += '  <entrega>\n';
        xmlContent += `    <titulua>${titulo}</titulua>\n`;
        xmlContent += `    <azalpena>${azalpena}</azalpena>\n`;
        xmlContent += `    <data>${data}</data>\n`;
        xmlContent += '  </entrega>\n';
    });

    xmlContent += '</historiala>';

    const blob = new Blob([xmlContent], { type: 'application/xml' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    
    link.href = url;
    link.download = 'historiala.xml'; 
    document.body.appendChild(link);
    link.click();
    
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}