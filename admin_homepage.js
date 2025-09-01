document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("adminHome-form");
    const commentButtons = document.querySelectorAll(".commentBtn");
    const leaderboardButtons = document.querySelectorAll(".leaderboardBtn");
    const quizRows = document.querySelectorAll(".quiz-row");
    const rangeLabel = document.getElementById("rangeLabel");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

//To view comment based on the quiz_id
    commentButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const quizId = btn.dataset.id;
            window.location.href = `quiz_comment.html?id=${quizId}`;
        });
    });
// To view leaderboard based on the quiz_id
    leaderboardButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const quizId = btn.dataset.id;
            window.location.href = `quiz_leaderboard.html?id=${quizId}`;
        });
    });

    // Index for quizzes shown, 3 per page
    let startIndex = 0;
    const itemsPerPage = 3;
    // Rendering to 3 quizzes per page
    function renderTable() {
        quizRows.forEach((row, index) => {
            row.style.display = (index >= startIndex && index < startIndex + itemsPerPage) ? '' : 'none';
        });

        const end = Math.min(startIndex + itemsPerPage, quizRows.length);
        rangeLabel.innerText = `${startIndex + 1}-${end} of ${quizRows.length}`;

        prevBtn.disabled = startIndex === 0;
        nextBtn.disabled = startIndex + itemsPerPage >= quizRows.length;
    }
    // Move right
    prevBtn.addEventListener("click", () => {
        if (startIndex - itemsPerPage >= 0) {
            startIndex -= itemsPerPage;
            renderTable();
        }
    });
    // Move left
    nextBtn.addEventListener("click", () => {
        if (startIndex + itemsPerPage < quizRows.length) {
            startIndex += itemsPerPage;
            renderTable();
        }
    });

    renderTable();
});
