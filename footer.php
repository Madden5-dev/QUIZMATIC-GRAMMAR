<!DOCTYPE html>
<html>
<head>
    <title>Footer Example</title>
    <style>
        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #0066d6, #004ba0);
            padding: 30px 5%;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            flex-wrap: wrap;
        }

        .footer-left {
            flex: 0 0 150px; /* Fixed width for logo */
        }

        .footer-center {
            flex: 1;
            text-align: center;
            min-width: 250px;
            padding: 0 20px;
        }

        .footer-right {
            flex: 0 0 200px;
            text-align: right;
        }

        .contact-info h4 {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
        }

        .contact-icon {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            fill: white;
        }

        .contact-link {
            color: white;
            text-decoration: none;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        /* LOGO STYLING */
        .footer-logo {
            width: 240px; /* Adjust logo size here */
            height: auto;
        }

        .feedback-section h4 {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .feedback-button {
            display: inline-block;
            background-color: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .feedback-button:hover {
            background-color: rgba(255,255,255,0.3);
        }

        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .footer-left, .footer-center, .footer-right {
                flex: 1;
                width: 100%;
                text-align: center;
            }
            
            .footer-logo {
                width: 100px; /* Smaller logo for mobile */
                margin-bottom: 15px;
            }
            
            .contact-item {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<div class="footer-container">
    <!-- Left: Logo -->
    <div class="footer-left">
        <img src="logo_footer.png" alt="QUIZMATIC GRAMMAR" class="footer-logo">
    </div>

    <!-- Center: Contact Information -->
    <div class="footer-center contact-info">
        <h4>Contact Information</h4>
        <div class="contact-item">
            <svg class="contact-icon" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span>Muhammad Fariz Fadhli</span>
        </div>
        <div class="contact-item">
            <svg class="contact-icon" viewBox="0 0 24 24">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            <a href="#" onclick="openGmail()" class="contact-link">farizfadhli454@gmail.com</a>
        </div>
        <div class="contact-item">
            <svg class="contact-icon" viewBox="0 0 24 24">
                <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>
            </svg>
            <span>+6013-650 1848</span>
        </div>
    </div>

    <!-- Right: Feedback -->
    <div class="footer-right feedback-section">
        <h4>Have any suggestions or feedback?</h4>
        <a href="feedback.php" class="feedback-button">Send Feedback</a>
    </div>
</div>

<script>
function openGmail() {
    const email = "farizfadhli454@gmail.com";
    const subject = encodeURIComponent("Help / Inquiry");
    const body = encodeURIComponent("Hi Admin,\n\nI would like to ask about...");
    const gmailURL = `https://mail.google.com/mail/?view=cm&fs=1&to=${email}&su=${subject}&body=${body}`;
    window.open(gmailURL, '_blank');
}
</script>

</body>
</html>