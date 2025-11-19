document.addEventListener('contextmenu', e => e.preventDefault());

// ========================== SECURITY QUESTION WITH INPUT PERSISTENCE =========================

// Create or get error box (uniform style)
function getErrorBoxForAnswer(inputElem) {
  const id = `answer_error_${inputElem.name}`; // unique per input
  let box = document.getElementById(id);
  if (box) return box;

  box = document.createElement("div");
  box.id = id;
  box.className = "error-box";
  box.style.cssText = `
    background:#ffe5e5;
    border:1px solid #ff6b6b;
    color:#b30000;
    padding:6px 10px;
    margin-top:6px;
    font-size:12px;
    border-radius:5px;
    display:none;
  `;
  inputElem.parentNode.appendChild(box);
  inputElem.setAttribute("aria-describedby", box.id);
  return box;
}

// Show error message
function showAnswerError(inputElem, message) {
  const box = getErrorBoxForAnswer(inputElem);
  box.textContent = message;
  box.style.display = "block";
  inputElem.classList.add("is-invalid");
  inputElem.classList.remove("is-valid");
  inputElem.setAttribute("aria-invalid", "true");
}

// Clear error message
function clearAnswerError(inputElem) {
  const box = getErrorBoxForAnswer(inputElem);
  box.textContent = "";
  box.style.display = "none";
  inputElem.classList.remove("is-invalid");
  inputElem.classList.add("is-valid");
  inputElem.removeAttribute("aria-invalid");
}

// Validate all 3 security questions
function validateSecurityAnswers() {
  let valid = true;
  for (let i = 1; i <= 3; i++) {
    const question = document.querySelector(`select[name="a${i}_question"]`);
    const answer = document.querySelector(`input[name="a${i}_answer"]`);

    if (!question.value) {
      showAnswerError(answer, "❌ Please select a question.");
      valid = false;
    } else if (!answer.value.trim()) {
      showAnswerError(answer, "❌ Please enter your answer.");
      valid = false;
    } else {
      clearAnswerError(answer);
    }

    // Save current values in sessionStorage
    sessionStorage.setItem(`a${i}_question`, question.value);
    sessionStorage.setItem(`a${i}_answer`, answer.value);
  }
  return valid;
}

// Restore input values on page load
function restoreSecurityAnswers() {
  for (let i = 1; i <= 3; i++) {
    const question = document.querySelector(`select[name="a${i}_question"]`);
    const answer = document.querySelector(`input[name="a${i}_answer"]`);
    if (!question || !answer) continue;

    const savedQ = sessionStorage.getItem(`a${i}_question`);
    const savedA = sessionStorage.getItem(`a${i}_answer`);

    if (savedQ) question.value = savedQ;
    if (savedA) answer.value = savedA;
  }
}

// Attach validation on form submit and restore on load
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registerForm");
  if (!form) return;

  restoreSecurityAnswers(); // Restore saved values

  form.addEventListener("submit", (e) => {

      const validAnswer = JSON.parse(localStorage.getItem("validAnswer"));

    if (!validateSecurityAnswers() || !validAnswer) {
      e.preventDefault();
      // focus first invalid answer
      const firstInvalid = form.querySelector(".is-invalid");
      if (firstInvalid) firstInvalid.focus();
    } else {
      // Optional: Clear saved values if submit is successful
      for (let i = 1; i <= 3; i++) {
        sessionStorage.removeItem(`a${i}_question`);
        sessionStorage.removeItem(`a${i}_answer`);
      }
      localStorage.setItem("validAnswer", "true");
    }
  });

    // Live save + auto-clear "please select question" error
    for (let i = 1; i <= 3; i++) {
    const question = document.querySelector(`select[name="a${i}_question"]`);
    const answer = document.querySelector(`input[name="a${i}_answer"]`);
    if (!question || !answer) continue;

    // When question changes
    question.addEventListener("change", () => {
      sessionStorage.setItem(`a${i}_question`, question.value);

      // Clear only question-related error
      if (question.value) {
        const box = document.getElementById(`answer_error_${answer.name}`);
        if (box && box.textContent.includes("Please select a question")) {
          box.textContent = ""; 
          box.style.display = "none";
          question.classList.add("is-valid");
          question.classList.remove("is-invalid");
        }
      }
    });

    // When answer is typed
    answer.addEventListener("input", () => {
      sessionStorage.setItem(`a${i}_answer`, answer.value);

      // Clear only answer-related error
      if (answer.value.trim()) {
        const box = document.getElementById(`answer_error_${answer.name}`);
        if (box && box.textContent.includes("Please enter your answer")) {
          box.textContent = ""; 
          box.style.display = "none";
          answer.classList.add("is-valid");
          answer.classList.remove("is-invalid");
        }
      }
    });
  }


});