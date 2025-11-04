<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susu Admin - Verify OTP</title>

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
        
        .otp-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .otp-card {
            max-width: 450px;
            width: 100%;
            background-color: var(--bg-100);
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .otp-card h1 .fa-users-cog {
             color: var(--accent-200);
        }
        
        .otp-input-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 500;
            border: 1px solid var(--bg-300);
            border-radius: 5px;
        }
        
        .otp-input:focus {
            border-color: var(--accent-100);
            box-shadow: 0 0 0 0.25rem rgba(113, 196, 239, 0.25);
            outline: none;
        }
        
        /* Hides the number input spinners in Chrome, Safari, Edge, Opera */
        .otp-input::-webkit-outer-spin-button,
        .otp-input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        /* Hides the number input spinners in Firefox */
        .otp-input[type=number] {
          -moz-appearance: textfield;
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
        
        #resend-link.disabled {
            color: var(--text-200);
            pointer-events: none;
            text-decoration: none;
        }

    </style>
</head>
<body>

    <div class="container otp-container">
        <div class="otp-card">
            <div class="text-center mb-4">
                <h1 class="h2 fw-bold"><i class="fas fa-users-cog me-2"></i>Susu Admin</h1>
                <h2 class="h4 mt-3">Enter Verification Code</h2>
                <p class="text-muted">A 6-digit code has been sent to<br><span class="fw-bold">ad***@susu.com</span></p>
            </div>
            
            <!-- OTP Form -->
            <form action="index.php" method="post">
                <div class="otp-input-group mb-4">
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Verify Code</button>
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="mb-0 text-muted">Didn't receive the code?</p>
                <a href="#" id="resend-link" class="text-decoration-none fw-bold" style="color: var(--accent-200);">
                    Resend Code <span id="timer"></span>
                </a>
            </div>

            <div class="text-center mt-3">
                <a href="#" class="text-decoration-none small" style="color: var(--text-200);">
                    <i class="fas fa-arrow-left me-1"></i> Back to Login
                </a>
            </div>
        </div>
    </div>


    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.0 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            const inputs = $(".otp-input");

            inputs.on('input', function(e) {
                const currentInput = $(this);
                const nextInput = currentInput.next('.otp-input');
                
                if (currentInput.val().length === 1 && nextInput.length > 0) {
                    nextInput.focus();
                }
            });

            inputs.on('keydown', function(e) {
                const currentInput = $(this);
                const prevInput = currentInput.prev('.otp-input');
                
                if (e.key === 'Backspace' && currentInput.val().length === 0 && prevInput.length > 0) {
                    prevInput.focus();
                }
            });

            // Handle paste
            inputs.on('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
                const pasteDigits = pasteData.replace(/\D/g, ''); // Remove non-digits

                if (pasteDigits.length === inputs.length) {
                    inputs.each(function(i) {
                        $(this).val(pasteDigits[i]).trigger('input');
                    });
                    inputs.last().focus();
                }
            });

            // Resend Timer
            function startTimer(duration, display, link) {
                let timer = duration, minutes, seconds;
                link.addClass('disabled'); // Disable link at start

                const interval = setInterval(function () {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.text("(" + seconds + "s)");

                    if (--timer < 0) {
                        clearInterval(interval);
                        display.text("");
                        link.removeClass('disabled'); // Re-enable link
                    }
                }, 1000);
            }

            const sixtySeconds = 60,
                timerDisplay = $('#timer'),
                resendLink = $('#resend-link');
                
            startTimer(sixtySeconds, timerDisplay, resendLink);
            
            resendLink.on('click', function(e) {
                e.preventDefault();
                if (!$(this).hasClass('disabled')) {
                    // Place your resend code logic here
                    // For demo, we just restart the timer
                    startTimer(sixtySeconds, timerDisplay, resendLink);
                }
            });
        });
    </script>
</body>
</html>