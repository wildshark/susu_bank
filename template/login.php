<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Login</title>

    <!-- Bootstrap 5.0 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

        .login-container {
            min-height: 100vh;
        }

        .login-image-section {
            background: url('https://images.unsplash.com/photo-1554224155-1696413565d3?q=80&w=2070&auto=format&fit=crop') center center;
            background-size: cover;
        }

        .login-form-section {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-100);
        }
        
        .login-form-container {
            max-width: 400px;
            width: 100%;
        }
        
        .login-form-container h1 .fa-users-cog {
             color: var(--accent-200);
        }
        
        .btn-primary {
            background-color: var(--accent-200);
            border-color: var(--accent-200);
            padding: 10px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #005670; /* A slightly darker shade for hover */
            border-color: #005670;
        }
        
        .form-control:focus {
            border-color: var(--accent-100);
            box-shadow: 0 0 0 0.25rem rgba(113, 196, 239, 0.25);
        }
        
        .password-toggle { 
            cursor: pointer; 
            color: var(--text-200);
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row login-container">
            <!-- Left Side: Image -->
            <div class="col-md-6 d-none d-md-block login-image-section">
                <!-- Background image is set via CSS -->
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-12 col-md-6 login-form-section">
                <div class="login-form-container p-4">
                    <div class="text-center mb-4">
                        <h1 class="h2 fw-bold"><i class="fas fa-users-cog me-2"></i>Susu Admin</h1>
                        <p class="text-muted">Please sign in to continue</p>
                    </div>
                    
                    <!-- Login Form -->
                    <form action="index.php" method="post" enctype="application/x-www-form-urlencoded">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                             <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                <span class="input-group-text password-toggle" onclick="togglePassword('password')"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="rememberMe" name="remember_me">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none" style="color: var(--accent-200);">Forgot Password?</a>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="_submit" value="login" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap 5.0 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Custom JS -->
    <script>
        // Password visibility toggle function
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            // Toggle the type attribute
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            // Toggle the icon
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>

</body>
</html>