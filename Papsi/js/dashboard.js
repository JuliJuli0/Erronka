// =========================
// 🚪 SAIOA ITXI (LOGOUT)
// =========================
function logout() {
  window.location.href = "php/logout.php";
}

// =========================
// 📦 DATUAK (BD)
// =========================
let paketeak = [];

// =========================
// 🔄 PHP-TIK DATUAK KARGATU
// =========================
function kargatuPaketeak() {
  fetch("php/get_paketeak.php")
    .then(res => res.json())
    .then(data => {
      paketeak = data;
      renderPaketeak(); 
    });
}

// =========================
// 🔁 PAKETEAK PANTAILAN ERAKUTSI
// =========================
function renderPaketeak() {
  const colZain = document.getElementById("col-zain");
  const colBanatzen = document.getElementById("col-banatzen");
  const colEntregatuak = document.getElementById("col-entregatuak");

  colZain.innerHTML = "";
  colBanatzen.innerHTML = "";
  colEntregatuak.innerHTML = "";

  paketeak.forEach(p => {
    const card = document.createElement("div");
    card.className = "card";

    let estadoClass = "";
    let estadoTexto = "";

    if (p.egoera === "zain") {
      estadoClass = "pending";
      estadoTexto = "Zain";
    } else if (p.egoera === "banatzen") {
      estadoClass = "progress";
      estadoTexto = "Banatzen";
    } else if (p.egoera === "entregatua") {
      estadoClass = "done";
      estadoTexto = "Entregatua";
      card.classList.add("completed");
    }

    let btn = "";

    if (p.egoera === "zain") {
      btn = `<button class="btn ok" onclick="aldatuEgoera('${p.pakete_id}','banatzen')">Banatu</button>`;
    } else if (p.egoera === "banatzen") {
      btn = `<button class="btn ok" onclick="aldatuEgoera('${p.pakete_id}','entregatua')">Entregatu</button>`;
    }

    card.innerHTML = `
      <span class="status ${estadoClass}">${estadoTexto}</span>
      <h3>📦 ${p.hartzailea}</h3>
      <p class="helbidea">${p.helbidea}</p>

      <div class="mapa-container">
        <iframe src="https://maps.google.com/maps?q=${encodeURIComponent(p.helbidea)}&output=embed"></iframe>
      </div>

      <div class="buttons">
        <button class="btn view" onclick="ikusiGehiago('${p.hartzailea}','${p.pakete_id}','${p.helbidea}')">
          Ikusi
        </button>
        ${btn}
      </div>
    `;

    // Paketea dagokion zutabean sartu
    if (p.egoera === "zain") colZain.appendChild(card);
    else if (p.egoera === "banatzen") colBanatzen.appendChild(card);
    else colEntregatuak.appendChild(card);
  });

  eguneratuStats(); 
}

// =========================
// 🔄 EGOERA ALDATU (DB EGUNERATU)
// =========================
function aldatuEgoera(id, estado) {
  fetch("php/update_estado.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `id=${encodeURIComponent(id)}&estado=${encodeURIComponent(estado)}`
  })
  .then(res => res.text())
  .then(data => {
    console.log("Zerbitzariaren erantzuna:", data);
    if (data.trim() === "ok") {
      kargatuPaketeak(); // Datuak freskatu aldaketa ikusteko
    } else {
      alert("Errorea eguneratzerakoan: " + data);
    }
  })
  .catch(err => console.error("Fetch errorea:", err));
}

// =========================
// 📊 ESTATISTIKAK EGUNERATU
// =========================
function eguneratuStats() {
  let zain = 0;
  let banatzen = 0;
  let entregatuak = 0;

  paketeak.forEach(p => {
    if (p.egoera === "zain") zain++;
    else if (p.egoera === "banatzen") banatzen++;
    else if (p.egoera === "entregatua") entregatuak++;
  });

  document.getElementById("Zain").textContent = zain;
  document.getElementById("banatzen").textContent = banatzen;
  document.getElementById("entregados").textContent = entregatuak;
}

// =========================
// 🧾 POPUP-A ERAKUTSI
// =========================
function ikusiGehiago(izena, id, helbidea) {
  document.getElementById("m-izena").textContent = izena;
  document.getElementById("m-dni").textContent = id;
  document.getElementById("m-helbidea").textContent = helbidea;

  document.getElementById("m-mapa").innerHTML =
    `<iframe src="https://maps.google.com/maps?q=${encodeURIComponent(helbidea)}&output=embed"></iframe>`;

  document.getElementById("popup").classList.add("show");
}

function itxiPopup() {
  document.getElementById("popup").classList.remove("show");
}

// =========================
// 🚀 HASIERATZEA (INIT)
// =========================
document.addEventListener("DOMContentLoaded", kargatuPaketeak);

// =========================
// 📜 HISTORIALERA JOAN
// =========================
function joanHistorialera() {
  window.location.href = "historiala.php";
}