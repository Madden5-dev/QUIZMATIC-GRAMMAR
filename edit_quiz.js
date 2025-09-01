// Function to load quiz data when Edit Quiz button is clicked
function loadQuizForEdit(quizId) {
    fetch('get_quiz_data.php?quiz_id=' + quizId)
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Populate form fields
            document.getElementById('quiz_id').value = data.quiz_id;
            document.getElementById('newTitle').value = data.quiz_title;
            document.getElementById('quiz_category').value = data.quiz_category;
            document.getElementById('quiz_difficulty').value = data.quiz_difficulty;
            document.getElementById('goToQuestionQuizId').value = data.quiz_id;

            // Show the update form
            const form = document.getElementById('updateForm');
            form.style.display = 'block';
            
            // Scroll to the form
            form.scrollIntoView({ behavior: 'smooth' });
        } else {
            showPopup("Failed to load quiz data: " + data.message);
        }
    })
    .catch(() => {
        showPopup("Error fetching quiz data");
    });
}

// Search quiz function
function searchQuiz() {
    const title = document.getElementById('searchTitle').value.trim();

    if (!title) {
        showPopup("Please enter a quiz title to search.");
        return;
    }

    fetch('edit_SearchApi_quiz.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'quiz_title=' + encodeURIComponent(title)
    })
    .then(res => res.json())
    .then(data => {
        const form = document.getElementById('updateForm');
        const goForm = document.getElementById('goToQuestionForm');

        if (data.success) {
            // Populate form fields
            document.getElementById('quiz_id').value = data.quiz_id;
            document.getElementById('newTitle').value = data.quiz_title;
            document.getElementById('quiz_category').value = data.quiz_category;
            document.getElementById('quiz_difficulty').value = data.quiz_difficulty;
            document.getElementById('goToQuestionQuizId').value = data.quiz_id;

            // Show the update form
            form.style.display = 'block';
            
            // Scroll to the form
            form.scrollIntoView({ behavior: 'smooth' });

            showPopup('Quiz found: "' + data.quiz_title + '"');
        } else {
            form.style.display = 'none';
            showPopup(data.message);
        }
    })
    .catch(() => {
        showPopup("Failed to fetch quiz data.");
    });
}

// Submit quiz update
function submitUpdate(event) {
    event.preventDefault();

    const quiz_id = document.getElementById('quiz_id').value;
    const newTitle = document.getElementById('newTitle').value.trim();
    const category = document.getElementById('quiz_category').value;
    const difficulty = document.getElementById('quiz_difficulty').value;

    if (!newTitle) {
        showPopup("Please enter a quiz title.");
        return;
    }

    fetch('update_quiz.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `quiz_id=${encodeURIComponent(quiz_id)}&new_title=${encodeURIComponent(newTitle)}&quiz_category=${encodeURIComponent(category)}&quiz_difficulty=${encodeURIComponent(difficulty)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showPopup("Quiz updated successfully!");
            // Refresh the page after 1.5 seconds
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showPopup("Update failed: " + data.message);
        }
    })
    .catch(() => {
        showPopup("Error updating quiz.");
    });

    return false;
}

// Popup functions
function showPopup(message) {
    const popup = document.getElementById('popupModal');
    const popupText = document.getElementById('popupText');
    popupText.innerText = message;
    popup.style.display = 'flex';
}

function closeModal() {
    document.getElementById('popupModal').style.display = 'none';
}
