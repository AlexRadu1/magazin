// Header

const navToggle = document.querySelector(".nav-toggle");
const nav = document.querySelector(".navbar-main");
const searchBar = document.querySelector(".search-bar");
const searchToggle = document.querySelector(".search-trigger");

navToggle.addEventListener("click", (e) => {
  e.stopPropagation();
  nav.classList.toggle("nav--visible");
});

searchToggle.addEventListener("click", () => {
  if (searchBar.style.display === "none") {
    searchBar.style.display = "block";
  } else {
    searchBar.style.display = "none";
  }
});

document.body.addEventListener("click", (event) => {
  if (!nav.contains(event.target) && event.target !== navToggle) {
    nav.classList.remove("nav--visible");
  }
});

// Index file

const popupViews = document.querySelectorAll(".popup-view");
const popupBtns = document.querySelectorAll(".popup-btn");
const closeBtns = document.querySelectorAll(".close-btn");

//javascript for quick view button
let popup = (popupClick) => {
  popupViews[popupClick].classList.add("active");
};

popupBtns.forEach((popupBtn, i) => {
  popupBtn.addEventListener("click", () => {
    popup(i);
  });
});

//javascript for close button
closeBtns.forEach((closeBtn) => {
  closeBtn.addEventListener("click", () => {
    popupViews.forEach((popupView) => {
      popupView.classList.remove("active");
    });
  });
});

//Change size ajax

$(".color-select").change(function () {
  let colorID = $(this).val();
  let prodID = $(this).siblings(".product-id").val();
  if (colorID) {
    $.ajax({
      url: "fetch_sizes.php",
      dataType: "Json",
      data: {
        id: colorID,
        prod_id: prodID,
      },
      success: function (data) {
        $(".size-select").empty();
        $.each(data, function (key, value) {
          if (value[1] > 0) {
            $(".size-select").append(
              '<option value="' + key + '">' + value[0] + "</option>"
            );
          } else {
            $(".size-select").append(
              '<option disabled value="' +
                key +
                '">' +
                value[0] +
                "- Out of stock</option>"
            );
          }
        });
      },
    });
  }
});

const allImages = document.querySelectorAll(".option img");
const mainImageContainer = document.querySelector(".main_image");

window.addEventListener("DOMContentLoaded", () => {
  allImages[0].classList.add("active");
});
allImages.forEach((image) => {
  image.addEventListener("click", (event) => {
    event.preventDefault();
    mainImageContainer.querySelector("img").src = image.src;
    resetActiveImg();
    image.classList.add("active");
  });
});

function resetActiveImg() {
  allHoverImages.forEach((img) => {
    img.classList.remove("active");
  });
}

$(".color-select").change(function () {
  let colorID = $(this).val();
  let prodID = $(this).siblings(".product-id").val();
  if (colorID) {
    $.ajax({
      url: "fetch_sizes.php",
      dataType: "Json",
      data: {
        id: colorID,
        prod_id: prodID,
      },
      success: function (data) {
        $(".size-select").empty();
        $.each(data, function (key, value) {
          if (value[1] > 0) {
            $(".size-select").append(
              '<option value="' + key + '">' + value[0] + "</option>"
            );
          } else {
            $(".size-select").append(
              '<option disabled value="' +
                key +
                '">' +
                value[0] +
                "- Out of stock</option>"
            );
          }
        });
      },
    });
  }
});

// Product details

const imgs = document.querySelectorAll(".img-select a");
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
  imgItem.addEventListener("click", (event) => {
    event.preventDefault();
    imgId = imgItem.dataset.id;
    slideImage();
  });
});

function slideImage() {
  const displayWidth = document.querySelector(
    ".img-showcase img:first-child"
  ).clientWidth;

  document.querySelector(".img-showcase").style.transform = `translateX(${
    -(imgId - 1) * displayWidth
  }px)`;
}

window.addEventListener("resize", slideImage);

//  Orders
const openModalButtons = document.querySelectorAll("[data-modal-target]");
const closeModalButtons = document.querySelectorAll("[data-close-button]");
const overlay = document.getElementById("overlay");

openModalButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const modal = document.querySelector(button.dataset.modalTarget);
    openModal(modal);
  });
});

overlay.addEventListener("click", () => {
  const modals = document.querySelectorAll(".modal.active");
  modals.forEach((modal) => {
    closeModal(modal);
  });
});

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

// Checkout

//TODO : fix this

$(document).ready(function () {
  $("input[name='date_facturare']").change(function () {
    if ($(this).attr("id") == "fact2") {
      $(".showDiv").show();
    } else {
      $(".showDiv").hide();
    }
  });
  $("input[name='payment_method']").change(function () {
    if ($(this).attr("id") == "card") {
      $(".showDiv1").show();
    } else {
      $(".showDiv1").hide();
    }
  });
});
