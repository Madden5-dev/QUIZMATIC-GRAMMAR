function validateForm() {
  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value.trim();
  const email = document.getElementById("email").value.trim();
  const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

  if (username === "" || password === "" || email === "") {
    alert("Please fill in all fields.");
    return false;
  }

  if (!email.match(emailPattern)) {
    alert("Invalid email address.");
    return false;
  }

  return true; // Semua semakan lulus, borang boleh dihantar
}
