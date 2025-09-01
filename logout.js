document.addEventListener("DOMContentLoaded", () => {
    // Logout logic
    const logoutBtn = document.getElementById("logout");
    logoutBtn.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent form submission or default action

        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "loginQuiz.html";
        }
    });
});
