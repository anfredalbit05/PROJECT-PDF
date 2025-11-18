<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Your Free PDF</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            width: 95%;
            max-width: 420px;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
            font-size: 26px;
            color: #222;
        }

        p {
            margin-top: 0;
            color: #666;
        }

        input {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
        }

        button:hover {
            background: #0b5ed7;
        }

        #message {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }

                /* Loader animation */
        .loader {
            width: 40px;
            height: 40px;
            border: 4px solid #ddd;
            border-top: 4px solid #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Disabled button */
        button:disabled {
            background: #9bbcf9;
            cursor: not-allowed;
        }

        /* Full screen overlay */
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.45);
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
            backdrop-filter: blur(2px);
        }

        #loadingOverlay p {
            color: white;
            margin-top: 12px;
            font-size: 18px;
            font-weight: bold;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 99999;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 350px;
            text-align: left;
        }

        .modal-content h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .modal-content button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
        }

        .cancel {
            background: #777;
        }


    </style>
</head>
<body>

<div class="container">
    <h2>Get Your Free PDF</h2>
    <p>Enter your details to receive your downloadable guide instantly.</p>

    <form id="leadForm">
        <input type="text" name="name" placeholder="Your full name" required>
        <input type="email" name="email" placeholder="Your email address" required>
        <button type="submit" id="submitBtn">
            Download PDF
        </button>

<div id="loadingOverlay">
    <div class="loader"></div>
    <p>Processing your request...</p>
</div>

    </form>

    <div id="message"></div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Your Details</h3>
        <p><strong>Name:</strong> <span id="confirmName"></span></p>
        <p><strong>Email:</strong> <span id="confirmEmail"></span></p>

        <button id="confirmBtn">Confirm</button>
        <button id="cancelBtn" class="cancel">Cancel</button>
    </div>
</div>

<script>
const form = document.getElementById('leadForm');
const loadingOverlay = document.getElementById('loadingOverlay');
const message = document.getElementById('message');
const submitBtn = document.getElementById('submitBtn');

const confirmModal = document.getElementById('confirmModal');
const confirmName = document.getElementById('confirmName');
const confirmEmail = document.getElementById('confirmEmail');
const confirmBtn = document.getElementById('confirmBtn');
const cancelBtn = document.getElementById('cancelBtn');

// Handle normal submit → SHOW CONFIRMATION MODAL
form.addEventListener('submit', function(e) {
    e.preventDefault();

    let name = form.name.value;
    let email = form.email.value;

    confirmName.textContent = name;
    confirmEmail.textContent = email;

    confirmModal.style.display = 'flex';
});

// If cancel → close modal
cancelBtn.onclick = () => {
    confirmModal.style.display = 'none';
};

// If confirm → send form to Laravel
confirmBtn.onclick = () => {

    confirmModal.style.display = 'none';
    message.innerHTML = "";
    loadingOverlay.style.display = 'flex';
    submitBtn.disabled = true;

    let formData = new FormData(form);

    fetch('/submit', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {

        loadingOverlay.style.display = 'none';

        if (data.error === 'email_exists') {
            message.style.color = "red";
            message.innerHTML = "Email already use. Please use a different one.";
            submitBtn.disabled = false;
            return;
        }

        if (data.success) {
            message.style.color = "green";
            submitBtn.disabled = false;

            setTimeout(() => {
                window.location = data.download_url;
            }, 800);
        }
    })
    .catch(() => {
        loadingOverlay.style.display = 'none';
        message.style.color = "red";
        message.innerHTML = "Server error.";
        submitBtn.disabled = false;
    });
};
</script>



</body>
</html>

