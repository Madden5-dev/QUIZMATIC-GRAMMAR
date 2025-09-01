document.querySelectorAll(".answer-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    alert("Redirect to quiz page...");
    // Here you can redirect to actual quiz page with query string etc.
  });
});
