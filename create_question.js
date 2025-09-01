function confirmCancel() {
    const isEditing = <?= isset($_SESSION['editing']) && $_SESSION['editing'] ? 'true' : 'false' ?>;
    
    if (isEditing) {
        if (confirm("Are you sure you want to cancel editing? Any unsaved changes will be lost.")) {
            window.location.href = "edit_quiz.php";
        }
    } else {
        if (confirm("Are you sure you want to cancel creating this quiz? All questions will be discarded.")) {
            window.location.href = "deleteCreating_question.php";
        }
    }
}

function validateForm() {
    const question = document.querySelector('[name="question"]').value.trim();
    const options = ['A', 'B', 'C', 'D'].map(opt => document.querySelector(`[name="option${opt}"]`).value.trim());
    const explanation = document.querySelector('[name="explanation"]').value.trim();
    const answer = document.querySelector('[name="answer"]:checked');

    if (!question || options.includes('') || !explanation || !answer) {
        alert("All fields must be filled and one correct answer selected.");
        return false;
    }

    document.getElementById('loading').style.display = 'block';
    return true;
}

function navigateToQuestion(questionNumber) {
    // First validate the form
    if (!validateForm()) {
        return false;
    }

    // Create a hidden form to change the current question
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'create_question.php';
    
    // Add all current form data
    const questionInput = document.createElement('input');
    questionInput.type = 'hidden';
    questionInput.name = 'question';
    questionInput.value = document.querySelector('[name="question"]').value;
    form.appendChild(questionInput);
    
    ['A', 'B', 'C', 'D'].forEach(opt => {
        const optInput = document.createElement('input');
        optInput.type = 'hidden';
        optInput.name = `option${opt}`;
        optInput.value = document.querySelector(`[name="option${opt}"]`).value;
        form.appendChild(optInput);
    });
    
    const answerInput = document.createElement('input');
    answerInput.type = 'hidden';
    answerInput.name = 'answer';
    const checked = document.querySelector('[name="answer"]:checked');
    answerInput.value = checked ? checked.value : '';
    form.appendChild(answerInput);
    
    const explanationInput = document.createElement('input');
    explanationInput.type = 'hidden';
    explanationInput.name = 'explanation';
    explanationInput.value = document.querySelector('[name="explanation"]').value;
    form.appendChild(explanationInput);
    
    // Add navigation target
    const navigateInput = document.createElement('input');
    navigateInput.type = 'hidden';
    navigateInput.name = 'navigate_to';
    navigateInput.value = questionNumber;
    form.appendChild(navigateInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Add event listener to form submission
document.querySelector('form').addEventListener('submit', function(e) {
    // For regular next/previous/submit buttons, we don't need to do anything special
    // as they already trigger the form submission
    if (!e.submitter || (!e.submitter.name && !e.submitter.getAttribute('onclick'))) {
        // This handles the case where submission might be triggered programmatically
        return validateForm();
    }
    return true;
});
