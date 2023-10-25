$(document).ready(function () {
  (function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})()
})
const prevBtn = document.querySelector(".js-prev");
const nextBtn = document.querySelector(".js-next");
const progressBar = document.querySelector(".js-bar");
const circles = document.querySelectorAll(".js-circle");

let currentActive = 1;
// let statuss = 3;
// let kategori = 1;

const changeBarDisplay = function () {
  const actives = document.querySelectorAll(".active");

  if (window.innerWidth >= 375 && window.innerWidth < 84410) {
    progressBar.style.height = `${((actives.length - 1) / (circles.length - 1)) * 100
      }%`;
  } else {
    progressBar.style.width = `${((actives.length - 1) / (circles.length - 1)) * 100
      }%`;
  }
};

const updateCirlceState = function () {
  circles.forEach((circle, i) => {
    i < currentActive
      ? circle.classList.add("active")
      : circle.classList.remove("active");
  });

  changeBarDisplay();
};

const incrementCurrent = function () {
  currentActive++;

  currentActive > circles.length && (currentActive = circles.length);
};

// const decrementCurrent = function () {
//   currentActive--;

//   currentActive < 1 && (currentActive = 1);
// };

//nextBtn.addEventListener("click", () => {


if(kategori==2){
  for (let i = 1; i <= statuss; i++) {
    if(i==2){
      incrementCurrent();
      updateCirlceState();
    }
    if(i==6){
      incrementCurrent();
      updateCirlceState();
    }
  }
}else if(kategori=1){
  for (let i = 1; i < statuss; i++) {
    incrementCurrent();
    updateCirlceState();
  }
}



