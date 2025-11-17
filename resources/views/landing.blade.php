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
    </style>
</head>
<body>

<div class="container">
    <h2>Get Your Free PDF</h2>
    <p>Enter your details to receive your downloadable guide instantly.</p>

    <form id="leadForm">
        <input type="text" name="name" placeholder="Your full name" required>
        <input type="email" name="email" placeholder="Your email address" required>
        <button type="submit">Download PDF</button>
    </form>

    <div id="message"></div>
</div>

<script>
document.getElementById('leadForm').addEventListener('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch('/submit', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('message').innerHTML = "Redirecting to download...";
            setTimeout(() => {
                window.location = data.download_url;
            }, 800);
        } else {
            document.getElementById('message').innerHTML = "Something went wrong.";
        }
    });
});
</script>

</body>
</html>
