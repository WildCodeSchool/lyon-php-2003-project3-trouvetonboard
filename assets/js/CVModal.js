function fade(element) {
    let op = 1;// initial opacity
    const timer = setInterval(() => {
        if (op <= 0.1) {
            clearInterval(timer);
            element.style.display = 'none';
        }
        element.style.opacity = op;
        element.style.filter = `alpha(opacity=${op * 100})`;
        op -= op * 0.1;
    }, 5);
}

function unfade(element) {
    let op = 0.1;// initial opacity
    element.style.display = 'block';
    const timer = setInterval(() => {
        if (op >= 1) {
            clearInterval(timer);
        }
        element.style.opacity = op;
        element.style.filter = `alpha(opacity=${op * 100})`;
        op += op * 0.1;
    }, 10);
}

// Get the modal
const modal = document.getElementById('CVAdvisor');

// Get the button that opens the modal
const showBtn = document.getElementById('showCVButton');

// Get the <span> element that closes the modal
const closeBtn = document.getElementsByClassName('close')[0];

// Get the button that closes the modal
const closeBtnBis = document.getElementById('hideCVButton');

// When the user clicks the "Voir mon CV" button, open the modal
// eslint-disable-next-line func-names
showBtn.onclick = function () {
    unfade(modal);
};

// When the user clicks on "x", close the modal
// eslint-disable-next-line func-names
closeBtn.onclick = function () {
    fade(modal);
};

// When the user clicks on "Fermer", close the modal
// eslint-disable-next-line func-names
closeBtnBis.onclick = function () {
    fade(modal);
};

// When the user clicks anywhere outside of the modal, close it
// eslint-disable-next-line func-names
window.onclick = function (event) {
    if (event.target === modal) {
        fade(modal);
    }
};
