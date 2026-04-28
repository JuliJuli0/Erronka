// =========================
// SAIOA ITXI (LOGOUT)
// =========================
function logout() {
  window.location.href = "php/logout.php";
}

// =========================
// DATUAK
// =========================
let paketeak = [];

function kargatuPaketeak() {
  fetch("php/get_paketeak.php")
    .then(res => res.json())
    .then(data => {
      paketeak = data;
      renderPaketeak();
    });
}

function renderPaketeak() {
  const colZain      = document.getElementById("col-zain");
  const colBanatzen  = document.getElementById("col-banatzen");
  const colEntregatuak = document.getElementById("col-entregatuak");

  colZain.innerHTML = "";
  colBanatzen.innerHTML = "";
  colEntregatuak.innerHTML = "";

  paketeak.forEach(p => {
    const card = document.createElement("div");
    card.className = "card";

    let estadoClass = "", estadoTexto = "";

    if (p.egoera === "zain") {
      estadoClass = "pending"; estadoTexto = "Zain";
    } else if (p.egoera === "banatzen") {
      estadoClass = "progress"; estadoTexto = "Banatzen";
    } else if (p.egoera === "entregatua") {
      estadoClass = "done"; estadoTexto = "Entregatua";
      card.classList.add("completed");
    }

    let btn = "";
    if (p.egoera === "zain") {
      btn = `<button type="button" class="btn ok" onclick="aldatuEgoera('${p.pakete_id}','banatzen','')">Banatu</button>`;
    } else if (p.egoera === "banatzen") {
      btn = `
        <button type="button" class="btn ok" onclick="entregaBaieztatu('${p.pakete_id}')">Entregatu</button>
        <button type="button" class="btn btn-red" onclick="arazoaBaieztatu('${p.pakete_id}')">Arazoa</button>
      `;
    }

    const mapUrl = `https://maps.google.com/maps?q=${encodeURIComponent(p.helbidea)}&output=embed`;

    card.innerHTML = `
      <span class="status ${estadoClass}">${estadoTexto}</span>
      <h3>📦 ${p.hartzailea}</h3>
      <p class="helbidea">${p.helbidea}</p>
      <div class="mapa-container">
        <iframe src="${mapUrl}"></iframe>
      </div>
      <div class="buttons">
        <button type="button" class="btn view" onclick="ikusiGehiago('${p.hartzailea}','${p.pakete_id}','${p.helbidea}')">Ikusi</button>
        ${btn}
      </div>
    `;

    if (p.egoera === "zain")          colZain.appendChild(card);
    else if (p.egoera === "banatzen") colBanatzen.appendChild(card);
    else                              colEntregatuak.appendChild(card);
  });

  eguneratuStats();
}

// =========================
// MODAL: ENTREGATU
// =========================
function entregaBaieztatu(id) {
  const modal  = document.getElementById("modal-entrega");
  const input  = document.getElementById("entrega-oharra");
  const errMsg = document.getElementById("entrega-error");

  input.value = "";
  input.style.borderColor = "#e2e8f0";
  errMsg.style.display = "none";
  modal.classList.add("show");
  input.focus();

  // Clonar botones para eliminar listeners viejos
  const oldOk     = document.getElementById("entrega-ok");
  const oldCancel = document.getElementById("entrega-cancel");
  const newOk     = oldOk.cloneNode(true);
  const newCancel = oldCancel.cloneNode(true);
  oldOk.parentNode.replaceChild(newOk, oldOk);
  oldCancel.parentNode.replaceChild(newCancel, oldCancel);

  newOk.addEventListener("click", () => {
    const testua = input.value.trim();
    if (testua === "") {
      input.style.borderColor = "#ef4444";
      errMsg.style.display = "block";
      return;
    }
    modal.classList.remove("show");
    aldatuEgoera(id, "entregatua", testua);
  });

  newCancel.addEventListener("click", () => {
    modal.classList.remove("show");
  });
}

// =========================
// MODAL: ARAZOA
// =========================
function arazoaBaieztatu(id) {
  const modal  = document.getElementById("modal-arazoa");
  const input  = document.getElementById("arazoa-oharra");
  const errMsg = document.getElementById("arazoa-error");

  input.value = "";
  input.style.borderColor = "#e2e8f0";
  errMsg.style.display = "none";
  modal.classList.add("show");
  input.focus();

  const oldOk     = document.getElementById("arazoa-ok");
  const oldCancel = document.getElementById("arazoa-cancel");
  const newOk     = oldOk.cloneNode(true);
  const newCancel = oldCancel.cloneNode(true);
  oldOk.parentNode.replaceChild(newOk, oldOk);
  oldCancel.parentNode.replaceChild(newCancel, oldCancel);

  newOk.addEventListener("click", () => {
    const arrazoia = input.value.trim();
    if (arrazoia === "") {
      input.style.borderColor = "#ef4444";
      document.getElementById("arazoa-error").style.display = "block";
      return;
    }
    modal.classList.remove("show");
    // Mandamos "biltegian" al PHP — el PHP lo detecta y pone egoera='zain'
    aldatuEgoera(id, "biltegian", arrazoia);
  });

  newCancel.addEventListener("click", () => {
    modal.classList.remove("show");
  });
}

// =========================
// EGOERA ALDATU
// =========================
function aldatuEgoera(id, estado, azalpena = "") {
  fetch("php/update_estado.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `id=${encodeURIComponent(id)}&estado=${encodeURIComponent(estado)}&azalpena=${encodeURIComponent(azalpena)}`
  })
  .then(res => res.text())
  .then(data => {
    if (data.trim() === "ok") {
      kargatuPaketeak();
    } else {
      alert("Errorea: " + data);
    }
  })
  .catch(err => console.error("Fetch errorea:", err));
}

// =========================
// ESTADISTIKAK
// =========================
function eguneratuStats() {
  let zain = 0, banatzen = 0, entregatuak = 0;
  paketeak.forEach(p => {
    if (p.egoera === "zain")           zain++;
    else if (p.egoera === "banatzen")  banatzen++;
    else if (p.egoera === "entregatua") entregatuak++;
  });
  document.getElementById("Zain").textContent       = zain;
  document.getElementById("banatzen").textContent   = banatzen;
  document.getElementById("entregados").textContent = entregatuak;
}

// =========================
// POPUP IKUSI
// =========================
function ikusiGehiago(izena, id, helbidea) {
  document.getElementById("m-izena").textContent   = izena;
  document.getElementById("m-dni").textContent     = id;
  document.getElementById("m-helbidea").textContent = helbidea;
  document.getElementById("m-mapa").innerHTML =
    `<iframe src="https://maps.google.com/maps?q=${encodeURIComponent(helbidea)}&output=embed"></iframe>`;
  document.getElementById("popup").classList.add("show");
}

function itxiPopup() {
  document.getElementById("popup").classList.remove("show");
}

document.addEventListener("DOMContentLoaded", kargatuPaketeak);

function joanHistorialera() {
  window.location.href = "historiala.php";
}
