// Header

const navToggle = document.querySelector(".nav-toggle");
const nav = document.querySelector(".navbar-main");
const hamburger = document.querySelector(".hamburger");
const searchBar = document.querySelector(".search-bar");
const searchToggle = document.querySelector(".search-trigger");

navToggle.addEventListener("click", (e) => {
  e.stopPropagation();
  nav.classList.toggle("nav--visible");
  if (nav.classList.contains("nav--visible")) {
    hamburger.classList.remove("fa-bars");
    hamburger.classList.add("fa-circle-xmark");
  } else {
    hamburger.classList.remove("fa-circle-xmark");
    hamburger.classList.add("fa-bars");
  }
});

searchToggle.addEventListener("click", () => {
  if (searchBar.style.display === "none") {
    searchBar.style.display = "block";
  } else {
    searchBar.style.display = "none";
  }
});

// Sidebar

const arrows = document.querySelectorAll(".arrow");

arrows.forEach((arrow) => {
  arrow.addEventListener("click", (e) => {
    const ulElement = e.target.parentElement.nextElementSibling;
    if (ulElement.style.display === "none" || ulElement.style.display === "") {
      ulElement.style.display = "flex";
      ulElement.style.flexDirection = "column";
      e.target.classList.remove("fa-solid", "fa-caret-down");
      e.target.classList.add("fas", "fa-caret-up");
    } else {
      ulElement.style.display = "none";
      e.target.classList.remove("fas", "fa-caret-up");
      e.target.classList.add("fa-solid", "fa-caret-down");
    }
  });
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

// Product details

const imgs = document.querySelectorAll(".img-select a");
const imgBtns = [...imgs];
let imgId = 1;

imgBtns.forEach((imgItem) => {
  imgItem.addEventListener("click", (e) => {
    e.preventDefault();
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
if ($("body").is(".prod-page")) {
  window.addEventListener("resize", slideImage);
}

document.addEventListener("click", (e) => {
  let handle;
  if (e.target.matches(".handle")) {
    handle = e.target;
  } else {
    handle = e.target.closest(".handle");
  }
  if (handle != null) onHandleClick(handle);
});

const throttleProgressBar = throttle(() => {
  document.querySelectorAll(".progress-bar").forEach(calculateProgressBar);
}, 250);

window.addEventListener("resize", throttleProgressBar);

document.querySelectorAll(".progress-bar").forEach(calculateProgressBar);

function onHandleClick(handle) {
  const progressBar = handle
    .closest(".product-imgs")
    .querySelector(".progress-bar");
  const slider = handle.closest(".img-select").querySelector(".slider");
  const sliderIndex = parseInt(
    getComputedStyle(slider).getPropertyValue("--slider-index")
  );
  const progressBarItemCount = progressBar.children.length;

  if (handle.classList.contains("left-handle")) {
    if (sliderIndex - 1 < 0) {
      slider.style.setProperty("--slider-index", progressBarItemCount - 1);
      progressBar.children[sliderIndex].classList.remove("active");
      progressBar.children[progressBarItemCount - 1].classList.add("active");
    } else {
      slider.style.setProperty("--slider-index", sliderIndex - 1);
      progressBar.children[sliderIndex].classList.remove("active");
      progressBar.children[sliderIndex - 1].classList.add("active");
    }
  }

  if (handle.classList.contains("right-handle")) {
    if (sliderIndex + 1 >= progressBarItemCount) {
      slider.style.setProperty("--slider-index", 0);
      progressBar.children[sliderIndex].classList.remove("active");
      progressBar.children[0].classList.add("active");
    } else {
      slider.style.setProperty("--slider-index", sliderIndex + 1);
      progressBar.children[sliderIndex].classList.remove("active");
      progressBar.children[sliderIndex + 1].classList.add("active");
    }
  }
}

function calculateProgressBar(progressBar) {
  progressBar.innerHTML = "";
  const slider = progressBar.closest(".product-imgs").querySelector(".slider");
  const itemCount = slider.children.length;
  const itemsPerScreen = parseInt(
    getComputedStyle(slider).getPropertyValue("--items-per-screen")
  );
  let sliderIndex = parseInt(
    getComputedStyle(slider).getPropertyValue("--slider-index")
  );
  const progressBarItemCount = Math.ceil(itemCount / itemsPerScreen);

  if (sliderIndex >= progressBarItemCount) {
    slider.style.setProperty("--slider-index", progressBarItemCount - 1);
    sliderIndex = progressBarItemCount - 1;
  }

  for (let i = 0; i < progressBarItemCount; i++) {
    const barItem = document.createElement("div");
    barItem.classList.add("progress-item");
    if (i === sliderIndex) {
      barItem.classList.add("active");
    }
    progressBar.append(barItem);
  }
}

function throttle(cb, delay = 1000) {
  let shouldWait = false;
  let waitingArgs;
  const timeoutFunc = () => {
    if (waitingArgs == null) {
      shouldWait = false;
    } else {
      cb(...waitingArgs);
      waitingArgs = null;
      setTimeout(timeoutFunc, delay);
    }
  };

  return (...args) => {
    if (shouldWait) {
      waitingArgs = args;
      return;
    }

    cb(...args);
    shouldWait = true;
    setTimeout(timeoutFunc, delay);
  };
}

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

// Checkout

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
