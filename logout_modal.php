<!-- logout_modal.php -->
<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease-out;
    overflow-y: auto;
  }

  .modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 40px;
    width: 380px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    transform: translateY(-20px);
    opacity: 0;
    animation: slideIn 0.3s ease-out forwards;
    border-top: 4px solid #dc3545;
  }

  .modal-content p {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 30px;
    line-height: 1.5;
  }

  .modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
  }

  .modal-buttons button {
    margin: 0;
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    min-width: 120px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .confirm-btn {
    background-color: #dc3545;
    color: white;
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
  }

  .confirm-btn:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(220, 53, 69, 0.4);
  }

  .confirm-btn:active {
    transform: translateY(0);
  }

  .cancel-btn {
    background-color: #f8f9fa;
    color: #495057;
    border: 1px solid #dee2e6;
  }

  .cancel-btn:hover {
    background-color: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  .cancel-btn:active {
    transform: translateY(0);
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideIn {
    from { 
      transform: translateY(-20px);
      opacity: 0;
    }
    to { 
      transform: translateY(0);
      opacity: 1;
    }
  }

  /* Responsive design */
  @media (max-width: 480px) {
    .modal-content {
      width: 90%;
      padding: 30px 20px;
      margin: 30% auto;
    }
    
    .modal-buttons {
      flex-direction: column;
      gap: 10px;
    }
    
    .modal-buttons button {
      width: 100%;
    }
  }
</style>

<!-- Modal HTML -->
<div id="logoutModal" class="modal">
  <div class="modal-content">
    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 20px;">
      <path d="M10 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4"></path>
      <polyline points="17 16 21 12 17 8"></polyline>
      <line x1="21" y1="12" x2="9" y2="12"></line>
    </svg>
    <p><strong>Are you sure you want to log out?</strong></p>
    <div class="modal-buttons">
      <button class="confirm-btn" onclick="confirmLogout()">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
          <path d="M10 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4"></path>
          <polyline points="17 16 21 12 17 8"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        Yes, Logout
      </button>
      <button class="cancel-btn" onclick="closeLogoutModal()">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="15" y1="9" x2="9" y2="15"></line>
          <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        Cancel
      </button>
    </div>
  </div>
</div>

<script>
  function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
  }

  function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.style.animation = 'fadeOut 0.3s ease-out';
    setTimeout(() => {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto'; // Re-enable scrolling
    }, 250);
  }

  function confirmLogout() {
    // Add a loading state
    const btn = document.querySelector('.confirm-btn');
    btn.innerHTML = '<span class="spinner"></span> Logging out...';
    btn.disabled = true;
    
    // Simulate a short delay before redirecting
    setTimeout(() => {
      window.location.href = 'logout.php';
    }, 800);
  }
</script>