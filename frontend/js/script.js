document.addEventListener("DOMContentLoaded", function () {
  var loginForm = document.getElementById("login");
  var registerForm = document.getElementById("register");
  var loginBtn = document.getElementById("loginBtn");
  var registerBtn = document.getElementById("registerBtn");

  function switchToLogin() {
      loginForm.style.left = "4px";
      registerForm.style.right = "-520px";
      loginBtn.classList.add("white-btn");
      registerBtn.classList.remove("white-btn");
      loginForm.style.opacity = 1;
      registerForm.style.opacity = 0;
  }

  function switchToRegister() {
      loginForm.style.left = "-510px";
      registerForm.style.right = "5px";
      loginBtn.classList.remove("white-btn");
      registerBtn.classList.add("white-btn");
      loginForm.style.opacity = 0;
      registerForm.style.opacity = 1;
  }

  // Initial state: show the login form
  switchToLogin();

  // Event listeners for switching between login and registration forms
  loginBtn.addEventListener("click", switchToLogin);
  registerBtn.addEventListener("click", switchToRegister);
});



   
   