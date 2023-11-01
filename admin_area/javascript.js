//Vars
const hamburger = document.querySelector(".hamburger");
const mobileMenu = document.querySelector(".mobile-menu");
const closeBtn = document.querySelector(".header-close-btn");
//orders
const openModalButtons = document.querySelectorAll("[data-modal-target]");
const closeModalButtons = document.querySelectorAll("[data-close-button]");
const overlay = document.getElementById("overlay");
//insert bills
const tbody = document.getElementById("table-body");
const productTable = document.getElementById("table_field");
//header
hamburger.addEventListener("click", () => {
  mobileMenu.classList.add("show-menu");
});

closeBtn.addEventListener("click", () => {
  mobileMenu.classList.remove("show-menu");
});

//insert bills
function loadRow() {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_products.php", true);
  xhr.onload = function () {
    if (this.status == 200) {
      let options = JSON.parse(this.responseText);
      let output = "";
      for (let i in options["prod"]) {
        output +=
          '<option value="' +
          options["prod"][i].ID +
          '">' +
          options["prod"][i].denumire +
          "</option>";
      }
      let output2 = "";
      for (let i in options["marimi"]) {
        output2 +=
          '<option value="' +
          options["marimi"][i].ID +
          '">' +
          options["marimi"][i].denumire +
          "</option>";
      }
      let output3 = "";
      for (let i in options["culori"]) {
        output3 +=
          '<option value="' +
          options["culori"][i].ID +
          '">' +
          options["culori"][i].denumire +
          "</option>";
      }
      let html = ``;
      html +=
        "<tr class='table-row'>" +
        '<td data-title="Produs"><div class="td-wrapper"><select name="txt_Produs[]" id="produs_input">' +
        '<option value="0">Select a category</option>' +
        output +
        "</select></div></td>" +
        '<td data-title="Cantitate"><div class="td-wrapper"><input type="text" name="txt_Cant[]" required="required"></div></td>' +
        "<td data-title='Marime'><div class='td-wrapper'>" +
        '<select name="txt_Marime[]">' +
        '<option value="0">Select a category</option>' +
        output2 +
        "</select>" +
        "</div></td>" +
        "<td data-title='Culoare'><div class='td-wrapper'>" +
        '<select name="txt_culoare[]">' +
        '<option value="0">Select a category</option>' +
        output3 +
        "</select>" +
        "</div></td>" +
        '<td data-title="Pret"><div class="td-wrapper"><input type="text" name="txt_Pret[]" required="required"></div></td>' +
        '<td data-title="Optiuni"><div class="td-wrapper"><input type="button" name="add"  class="add" value="Add">' +
        '<input type="button" name="copy" class="copy" value="Copy">' +
        '<input type="button" name="remove" class="remove" value="Remove"></div></td>' +
        "</tr>";
      $("#table-body").append(html);
    }
  };
  xhr.send();
}

productTable.addEventListener("click", (e) => {
  if (e.target.classList.contains("copy")) {
    const originalRow = e.target.closest("tr");
    const originalSelectProdus = originalRow.querySelector(".selectProdus");
    const originalSelectMarime = originalRow.querySelector(".selectMarime");
    const originalSelectCuloare = originalRow.querySelector(".selectCuloare");
    const clonedRow = originalRow.cloneNode(true);
    const clonedSelectProdus = clonedRow.querySelector(".selectProdus");
    const clonedSelectMarime = clonedRow.querySelector(".selectMarime");
    const clonedSelectCuloare = clonedRow.querySelector(".selectCuloare");

    clonedSelectProdus.value = originalSelectProdus.value;
    clonedSelectMarime.value = originalSelectMarime.value;
    clonedSelectCuloare.value = originalSelectCuloare.value;

    tbody.appendChild(clonedRow);
  }
  if (e.target.classList.contains("add")) {
    const originalRow = e.target.closest("tr");
    const clonedRow = originalRow.cloneNode(true);
    const inputCantitate = clonedRow.querySelector(".inputCantitate");
    const inputPret = clonedRow.querySelector(".inputPret");
    inputCantitate.value = null;
    inputPret.value = null;
    tbody.appendChild(clonedRow);
  }
  if (e.target.classList.contains("remove")) {
    if (tbody.childElementCount > 1) {
      e.target.closest("tr").remove();
    }
  }
});

//orders
openModalButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const modal = document.querySelector(button.dataset.modalTarget);
    openModal(modal);
  });
});

if (overlay) {
  overlay.addEventListener("click", () => {
    const modals = document.querySelectorAll(".modal.active");
    modals.forEach((modal) => {
      closeModal(modal);
    });
  });
}
closeModalButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const modal = button.closest(".modal");
    closeModal(modal);
  });
});

function openModal(modal) {
  if (modal == null) return;
  modal.classList.add("active");
  overlay.classList.add("active");
}

function closeModal(modal) {
  if (modal == null) return;
  modal.classList.remove("active");
  overlay.classList.remove("active");
}
