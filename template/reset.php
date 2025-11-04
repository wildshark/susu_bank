<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Susu Admin - Reset Password</title>

    <!-- Bootstrap 5.0 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom CSS -->
    <style>
    :root {
        --primary-100: #d4eaf7;
        --primary-200: #b6ccd8;
        --primary-300: #3b3c3d;
        --accent-100: #71c4ef;
        --accent-200: #00668c;
        --text-100: #1d1c1c;
        --text-200: #313d44;
        --bg-100: #fffefb;
        --bg-200: #f5f4f1;
        --bg-300: #cccbc8;
    }

    body {
        background-color: var(--bg-200);
        color: var(--text-100);
        font-family: 'Roboto', sans-serif;
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--bg-100);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease-in-out;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid var(--primary-200);
        border-top: 5px solid var(--accent-100);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .reset-container {
        min-height: 100vh;
    }

    .reset-image-section {
        background: url('https://images.unsplash.com/photo-1591696205602-2f950c417cb9?q=80&w=2070&auto=format&fit=crop') center center;
        background-size: cover;
    }

    .reset-form-section {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-100);
    }

    .reset-form-container {
        max-width: 400px;
        width: 100%;
    }

    .reset-form-container h1 .fa-users-cog {
        color: var(--accent-200);
    }

    .btn-primary {
        background-color: var(--accent-200);
        border-color: var(--accent-200);
        padding: 10px;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #005670;
        border-color: #005670;
    }

    .form-control:focus {
        border-color: var(--accent-100);
        box-shadow: 0 0 0 0.25rem rgba(113, 196, 239, 0.25);
    }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <div class="container-fluid">
        <div class="row reset-container">
            <!-- Left Side: Password Reset Form -->
            <div class="col-12 col-md-6 reset-form-section">
                <div class="reset-form-container p-4">
                    <div class="text-center mb-4">
                        <h1 class="h2 fw-bold"><i class="fas fa-users-cog me-2"></i>Susu Admin</h1>
                        <h2 class="h4">Forgot Your Password?</h2>
                        <p class="text-muted">No problem. Enter your email address below and we'll send you a link to
                            reset it.</p>
                    </div>

                    <!-- Reset Form -->
                    <form action="index.php" method="post" id="resetForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="you@example.com" required>
                            </div>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </div>
                    </form>

                    <!-- Bootstrap Alert for feedback -->
                    <div id="alert-placeholder">
                        <!-- Example Success Alert:
                        <div class="alert alert-success mt-3" role="alert">
                           <i class="fas fa-check-circle me-2"></i> If an account with that email exists, a reset link has been sent.
                        </div>
                        -->
                    </div>

                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none" style="color: var(--accent-200);">
                            <i class="fas fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Image -->
            <div class="col-md-6 d-none d-md-block reset-image-section">
                <!-- Background image is set via CSS -->
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.0 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Custom JS -->
    <script>
    // Preloader function
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        preloader.style.opacity = '0';
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 500);
    });

    // Demo script to show alert on form submission
    document.getElementById('resetForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Stop actual submission for demo

        const alertPlaceholder = document.getElementById('alert-placeholder');
        const email = document.getElementById('email').value;

        let alertHtml = '';
        if (email) {
            // Show success alert
            alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                       <i class="fas fa-check-circle me-2"></i> If an account with that email exists, a reset link has been sent.
                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
        } else {
            // Show error/warning alert
            alertHtml = `
                    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                       <i class="fas fa-exclamation-triangle me-2"></i> Please enter a valid email address.
                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
        }

        alertPlaceholder.innerHTML = alertHtml;
    });
    </script>
</body>

</html>