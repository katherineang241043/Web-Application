// Small visual interaction for the blind box button.
const drawForm = document.getElementById("drawForm");
const blindBox = document.getElementById("blindBox");
const openButton = document.getElementById("openButton");

if (drawForm && blindBox && openButton) {
    drawForm.addEventListener("submit", function (event) {
        event.preventDefault();
        blindBox.classList.add("shake-box");
        openButton.disabled = true;
        openButton.textContent = "Opening...";

        setTimeout(function () {
            drawForm.submit();
        }, 900);
    });
}
