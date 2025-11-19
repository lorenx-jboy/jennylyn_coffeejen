import { loadFormState, saveFormState } from "./localStorage.js";
import { bindValidation } from "./events.js";
export default function initRegister(){
    const form = document.getElementById("register-form");
    if (!form) return;

    bindValidation(form);
    loadFormState(form);

    form.addEventListener("input", () => saveFormState(form));
    form.addEventListener("change", () => saveFormState(form));

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        form.querySelectorAll("[data-validate]").forEach(field => field.dispatchEvent(new Event("input")));

        const invalidFields = [...form.querySelectorAll(".is-invalid")];

        if (invalidFields.length > 0) {
            // Focus the first invalid field
            invalidFields[0].focus();
            return; // stop form submission
        }

        alert("submit form")
      });


    //Eye toggle
    const toggles = document.querySelectorAll(".toggle-password-btn");

    toggles.forEach(btn => {
        btn.addEventListener("click", () => {
            // const input = document.querySelector(btn.getAttribute("toggle"));
            const input = document.querySelector(`input[name="${btn.dataset.type}"]`);
            const icon = btn.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });
    });


    //   test load
    //   window.addEventListener('load',()=>{
    //     form.querySelectorAll("[data-validate]").forEach(field => field.dispatchEvent(new Event("input")));
    //   });

}