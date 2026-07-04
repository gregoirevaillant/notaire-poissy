const form = document.getElementById("cform");
const okBox = document.getElementById("ok");
const errorBox = document.getElementById("form-error");
const submitBtn = form.querySelector('button[type="submit"]');
const submitLabel = submitBtn.textContent;

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  errorBox.classList.remove("show");
  submitBtn.disabled = true;
  submitBtn.textContent = "Envoi en cours…";

  try {
    const response = await fetch("mail.php", {
      method: "POST",
      body: new FormData(form),
    });
    const result = await response.json();

    if (result.success) {
      form.classList.add("form-hidden");
      okBox.classList.add("show");
    } else {
      errorBox.textContent =
        result.error || "Une erreur est survenue. Merci de réessayer ou de nous contacter directement.";
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
