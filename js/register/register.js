import { loadFormState, saveFormState } from "./localStorage.js";
import { bindValidation } from "./events.js";
export default function initRegister(){

    // localStorage.clear(); // clear local storage

    const form = document.getElementById("register-form");
    form.noValidate = true;
    if (!form) return;

    bindValidation(form);
    loadFormState(form);

    form.addEventListener("input", () => saveFormState(form));
    form.addEventListener("change", () => saveFormState(form));

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        form.querySelectorAll("[data-validate]").forEach(field => field.dispatchEvent(new Event("input")));

        const invalidFields = [...form.querySelectorAll(".is-invalid")];

        if (invalidFields.length > 0) {
            // Focus the first invalid field
            invalidFields[0].focus();
            return; // stop form submission
        }

        const data = new FormData(form);
        const result = await submitRegister(data);
        console.log(result);
        if (result.success) {
            window.location.href = "login.php";
            localStorage.clear();
        }
    });

    async function submitRegister(data){
        const url = "/php/register_submit.php";
        const res = await fetch(url, { method: "POST", body: data });
        const json = await res.json();
        return json;
    }


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