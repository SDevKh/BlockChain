var modal = document.getElementById("contact-modal");
var btn = document.getElementById("contact-btn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

var startmodal2 = document.getElementById("signup-modal");
var startbtn2 = document.getElementById("button2");
var span = document.getElementsByClassName("close2")[0];
startbtn2.onclick = function() {
    startmodal2.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

var startmodal2 = document.getElementById("signup-modal");
var startbtn2 = document.getElementById("button3");
var span = document.getElementsByClassName("close2")[0];
startbtn2.onclick = function() {
    startmodal2.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

var startmodal2 = document.getElementById("signup-modal");
var startbtn2 = document.getElementById("button4");
var span = document.getElementsByClassName("close2")[0];
startbtn2.onclick = function() {
    startmodal2.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

var modals = document.querySelectorAll('.modal2, .modal3, .prodmodal');
var buttons = document.querySelectorAll('.login-btn, .signup-btn, .product-btn');
var spans = document.querySelectorAll('.close1, .close2, .close3');

buttons.forEach((button, index) => {
  button.onclick = function() {
    modals[index].style.display = "block";
  }
})

spans.forEach((span, index) => {
  span.onclick = function() {
    modals[index].style.display = "none";
  }
})

const questions = document.querySelectorAll('.question');

questions.forEach((question) => {
  question.addEventListener('click', () => {
    const answer = question.nextElementSibling;
    const arrow = question.querySelector('.arrow');
    console.log(answer, arrow);
    answer.classList.toggle('active');
    arrow.classList.toggle('active');

    if (answer.classList.contains('active')) {
      answer.style.maxHeight = answer.scrollHeight + 'px';
    } else {
      answer.style.maxHeight = null;
    }
  });
});