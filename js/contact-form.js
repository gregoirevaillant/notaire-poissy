const form = document.getElementById("cform");
const errorBox = document.getElementById("form-error");
const submitBtn = form.querySelector('button[type="submit"]');
const submitLabel = submitBtn.textContent;

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  errorBox.classList.remove("show");
  submitBtn.disabled = true;
  submitBtn.textContent = "Envoi en cours…";

  try {
    const data = new FormData(form);
    data.set("subject", "Nouveau message du site — " + data.get("sujet"));
    const response = await fetch("https://api.web3forms.com/submit", {
      method: "POST",
      body: data,
    });
    const result = await response.json();

    if (result.success) {
      window.location.href = "merci.html";
    } else {
      errorBox.textContent = "Une erreur est survenue. Merci de réessayer ou de nous contacter directement.";
      errorBox.classList.add("show");
    }
  } catch (err) {
    errorBox.textContent = "Impossible d'envoyer le message. Merci de nous contacter par téléphone ou email.";
    errorBox.classList.add("show");
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = submitLabel;
  }
});
