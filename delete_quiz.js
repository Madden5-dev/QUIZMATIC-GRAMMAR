// delete_quiz.js

// Function to search quizzes and highlight matching results
function searchQuiz() {
    const searchTerm = document.getElementById('searchBox').value.toLowerCase();
    const quizItems = document.querySelectorAll('.quiz-item');
    let found = false;
    
    quizItems.forEach(item => {
        const title = item.getAttribute('data-title');
        if (title.includes(searchTerm)) {
            item.style.display = 'flex';
            item.classList.add('highlight');
            setTimeout(() => item.classList.remove('highlight'), 1000);
            found = true;
        } else {
            item.style.display = 'none';
        }
    });

    // Show alert if no results found (from original delete_quiz.js)
    if (!found && searchTerm.trim() !== '') {
        alert("Quiz title not found.");
    }
}

// Function to confirm and delete a quiz (combined from both files)
function confirmDelete(quizTitle, quizId, element) {
    // Use the more detailed confirmation message from delete_quiz.php
    if (confirm(`Are you sure you want to delete "${quizTitle}" and all its questions?`)) {
        // Try both endpoints - first the one from delete_quiz.php, then the one from delete_quiz.js
        fetch(`delete_quiz_handler.php?quiz_id=${quizId}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                // If first endpoint fails, try the second one
                return fetch('delete_Apiquiz.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'quiz_id=' + encodeURIComponent(quizId)
                });
            }
            return response;
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                element.remove();
                // Use the more detailed success message from delete_quiz.php
                alert(`"${quizTitle}" has been deleted successfully.`);
            } else {
                alert('Error deleting quiz: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
}

// Function to go back (from original delete_quiz.js)
function goBack() {
    window.history.back();
}

// Add event listeners for the search box to trigger search on Enter key
document.addEventListener('DOMContentLoaded', function() {
    const searchBox = document.getElementById('searchBox');
    if (searchBox) {
        searchBox.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                searchQuiz();
            }
        });
    }
});