<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
           * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            /* Allow scrolling */
            overflow-x: hidden;
            overflow-y: auto;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4eff9 100%);
        }

        .icon-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .medical-icon {
            position: absolute;
            opacity: 0.15;
            animation: float 20s linear infinite;
        }

        /* Add dummy content for scrolling demonstration */
        .dummy-content {
            height: 0را; /* Make page scrollable */
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
            }
        }
        :root {
            --primary-color: #0078d4;
            --primary-hover: #006cbe;
            --secondary-color: #f3f3f3;
            --text-color: #333;
            --error-color: #d83b01;
            --success-color: #107c10;
            --border-color: #d1d1d1;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px var(--shadow-color);
            overflow: hidden;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-container {
            padding: 30px;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.2);
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .form-group .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            user-select: none;
        }

        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            transition: var(--transition);
        }

        .btn:hover {
            background-color: var(--primary-hover);
        }

        .btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: rgba(0, 120, 212, 0.1);
        }

        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            color: var(--success-color);
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .verification-code {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .verification-code input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        .verification-code input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.2);
        }

        .timer {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .resend {
            text-align: center;
            margin-top: 15px;
        }

        .resend a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .resend a:hover {
            text-decoration: underline;
        }

        .password-requirements {
            margin-top: 10px;
            font-size: 13px;
            color: #666;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .requirement span {
            margin-left: 5px;
        }

        .requirement.valid {
            color: var(--success-color);
        }

        .requirement.invalid {
            color: #666;
        }

        .check-icon {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .valid .check-icon {
            background-color: var(--success-color);
            color: white;
        }

        .invalid .check-icon {
            background-color: #ccc;
            color: white;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive styles */
        @media (max-width: 480px) {
            .container {
                box-shadow: none;
                border-radius: 0;
            }

            .verification-code input {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="icon-background" id="icon-background"></div>
    <div class="dummy-content"></div>
    <div class="container">
        <div class="header">
            <h1>Forgot Password</h1>
            <p>Enter your email to reset your password</p>
        </div>
        <div class="form-container">
            <!-- Step 1: Email Input -->
            <div class="form-step active" id="step-1">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" placeholder="Enter your email address" autocomplete="email">
                    <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>
                <button class="btn" id="send-code-btn">Send Reset Code</button>
                <a href="index.php" class="back-link">Back to Login</a>
            </div>

            <!-- Step 2: Verification Code -->
            <div class="form-step" id="step-2">
                <p style="text-align: center; margin-bottom: 20px;">We've sent a verification code to your email</p>
                <div class="verification-code">
                    <input type="text" maxlength="1" class="code-input" data-index="0">
                    <input type="text" maxlength="1" class="code-input" data-index="1">
                    <input type="text" maxlength="1" class="code-input" data-index="2">
                    <input type="text" maxlength="1" class="code-input" data-index="3">
                    <input type="text" maxlength="1" class="code-input" data-index="4">
                    <input type="text" maxlength="1" class="code-input" data-index="5">
                </div>
                <div class="timer" id="timer">Code expires in: <span id="countdown">05:00</span></div>
                <div class="error-message" id="code-error">Invalid verification code</div>
                <button class="btn" id="verify-code-btn">Verify Code</button>
                <div class="resend">
                    <span>Didn't receive the code?</span>
                    <a href="#" id="resend-code">Resend Code</a>
                </div>
                <button class="btn btn-secondary" id="back-to-email">Back</button>
            </div>

            <!-- Step 3: New Password -->
            <div class="form-step" id="step-3">
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="new-password" placeholder="Enter new password">
                        <span class="toggle-password" id="toggle-new-password">Show</span>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" id="length-requirement">
                            <div class="check-icon">✓</div>
                            <span>At least 8 characters</span>
                        </div>
                        <div class="requirement" id="uppercase-requirement">
                            <div class="check-icon">✓</div>
                            <span>At least one uppercase letter</span>
                        </div>
                        <div class="requirement" id="lowercase-requirement">
                            <div class="check-icon">✓</div>
                            <span>At least one lowercase letter</span>
                        </div>
                        <div class="requirement" id="number-requirement">
                            <div class="check-icon">✓</div>
                            <span>At least one number</span>
                        </div>
                        <div class="requirement" id="special-requirement">
                            <div class="check-icon">✓</div>
                            <span>At least one special character</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="confirm-password" placeholder="Confirm new password">
                        <span class="toggle-password" id="toggle-confirm-password">Show</span>
                    </div>
                    <div class="error-message" id="password-error">Passwords do not match</div>
                </div>
                <button class="btn" id="reset-password-btn">Reset Password</button>
                <button class="btn btn-secondary" id="back-to-code">Back</button>
            </div>

            <!-- Step 4: Success Message -->
            <div class="form-step" id="step-4">
                <div style="text-align: center; padding: 20px 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--success-color); margin-bottom: 20px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <h2 style="color: var(--success-color); margin-bottom: 10px;">Password Reset Successful</h2>
                    <p style="color: #666; margin-bottom: 20px;">Your password has been reset successfully.</p>
                    <a href="index.php" class="btn">Login with New Password</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const emailInput = document.getElementById('email');
        const sendCodeBtn = document.getElementById('send-code-btn');
        const verifyCodeBtn = document.getElementById('verify-code-btn');
        const resetPasswordBtn = document.getElementById('reset-password-btn');
        const backToEmailBtn = document.getElementById('back-to-email');
        const backToCodeBtn = document.getElementById('back-to-code');
        const resendCodeBtn = document.getElementById('resend-code');
        const emailError = document.getElementById('email-error');
        const codeError = document.getElementById('code-error');
        const passwordError = document.getElementById('password-error');
        const countdownEl = document.getElementById('countdown');
        const newPasswordInput = document.getElementById('new-password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        const toggleNewPassword = document.getElementById('toggle-new-password');
        const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
        const codeInputs = document.querySelectorAll('.code-input');
        
        // Password requirement elements
        const lengthReq = document.getElementById('length-requirement');
        const uppercaseReq = document.getElementById('uppercase-requirement');
        const lowercaseReq = document.getElementById('lowercase-requirement');
        const numberReq = document.getElementById('number-requirement');
        const specialReq = document.getElementById('special-requirement');

        // Form steps
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        const step3 = document.getElementById('step-3');
        const step4 = document.getElementById('step-4');

        // Variables
        let countdownInterval;
        let timeLeft = 300; // 5 minutes in seconds

        // Functions
        function validateEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show the requested step
            step.classList.add('active');
        }

        function startCountdown() {
            clearInterval(countdownInterval);
            timeLeft = 300; // Reset to 5 minutes
            updateCountdown();
            
            countdownInterval = setInterval(() => {
                timeLeft--;
                updateCountdown();
                
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    // Handle expired code
                }
            }, 1000);
        }

        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function validatePassword(password) {
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            lengthReq.className = hasLength ? 'requirement valid' : 'requirement invalid';
            uppercaseReq.className = hasUppercase ? 'requirement valid' : 'requirement invalid';
            lowercaseReq.className = hasLowercase ? 'requirement valid' : 'requirement invalid';
            numberReq.className = hasNumber ? 'requirement valid' : 'requirement invalid';
            specialReq.className = hasSpecial ? 'requirement valid' : 'requirement invalid';
            
            return hasLength && hasUppercase && hasLowercase && hasNumber && hasSpecial;
        }

        function togglePasswordVisibility(inputElement, toggleElement) {
            if (inputElement.type === 'password') {
                inputElement.type = 'text';
                toggleElement.textContent = 'Hide';
            } else {
                inputElement.type = 'password';
                toggleElement.textContent = 'Show';
            }
        }

        // Event Listeners
        sendCodeBtn.addEventListener('click', () => {
            const email = emailInput.value.trim();
            
            if (!validateEmail(email)) {
                emailError.style.display = 'block';
                return;
            }
            
            emailError.style.display = 'none';
            
            // In a real application, you would send an API request here
            // For demo purposes, we'll just move to the next step
            showStep(step2);
            startCountdown();
            
            // Focus on first code input
            codeInputs[0].focus();
        });

        verifyCodeBtn.addEventListener('click', () => {
            // Get the verification code
            let code = '';
            codeInputs.forEach(input => {
                code += input.value;
            });
            
            if (code.length !== 6) {
                codeError.style.display = 'block';
                return;
            }
            
            codeError.style.display = 'none';
            
            // In a real application, you would verify the code with an API
            // For demo purposes, we'll just move to the next step
            showStep(step3);
            clearInterval(countdownInterval);
        });

        resetPasswordBtn.addEventListener('click', () => {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (!validatePassword(newPassword)) {
                return;
            }
            
            if (newPassword !== confirmPassword) {
                passwordError.style.display = 'block';
                return;
            }
            
            passwordError.style.display = 'none';
            
            // In a real application, you would send the new password to an API
            // For demo purposes, we'll just show the success message
            showStep(step4);
        });

        backToEmailBtn.addEventListener('click', () => {
            showStep(step1);
            clearInterval(countdownInterval);
        });

        backToCodeBtn.addEventListener('click', () => {
            showStep(step2);
            startCountdown();
        });

        resendCodeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // In a real application, you would resend the code
            startCountdown();
            
            // Show a temporary success message
            const resendEl = document.querySelector('.resend');
            const originalContent = resendEl.innerHTML;
            resendEl.innerHTML = '<span style="color: var(--success-color);">Code resent successfully!</span>';
            
            setTimeout(() => {
                resendEl.innerHTML = originalContent;
            }, 3000);
        });

        toggleNewPassword.addEventListener('click', () => {
            togglePasswordVisibility(newPasswordInput, toggleNewPassword);
        });

        toggleConfirmPassword.addEventListener('click', () => {
            togglePasswordVisibility(confirmPasswordInput, toggleConfirmPassword);
        });

        // Handle code input behavior
        codeInputs.forEach((input, index) => {
            input.addEventListener('keyup', (e) => {
                // If a digit was entered
                if (/^\d$/.test(e.key)) {
                    // Move focus to next input if available
                    if (index < codeInputs.length - 1) {
                        codeInputs[index + 1].focus();
                    }
                }
                // Handle backspace
                else if (e.key === 'Backspace') {
                    // Clear current input
                    input.value = '';
                    
                    // Move focus to previous input if available
                    if (index > 0) {
                        codeInputs[index - 1].focus();
                    }
                }
            });
            
            // Handle paste event
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').trim();
                
                // If pasted data is a 6-digit number
                if (/^\d{6}$/.test(pastedData)) {
                    // Distribute digits to inputs
                    codeInputs.forEach((input, i) => {
                        input.value = pastedData[i] || '';
                    });
                }
            });
        });

        // Password validation on input
        newPasswordInput.addEventListener('input', () => {
            validatePassword(newPasswordInput.value);
        });

        // Check password match on input
        confirmPasswordInput.addEventListener('input', () => {
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                passwordError.style.display = 'block';
            } else {
                passwordError.style.display = 'none';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconBackground = document.getElementById('icon-background');
            const icons = [
                '❤️', '🩺', '💊', '💉', '🧬', '🦠', '🧪', '🩸', '🫀', '🫁', '🧠', '👨‍⚕️', '👩‍⚕️', '🏥'
            ];
            
            // Create more floating icons for a fuller effect
            for (let i = 0; i < 50; i++) {
                const icon = document.createElement('div');
                icon.className = 'medical-icon';
                icon.textContent = icons[Math.floor(Math.random() * icons.length)];
                icon.style.left = `${Math.random() * 100}%`;
                icon.style.fontSize = `${Math.random() * 20 + 20}px`;
                icon.style.animationDuration = `${Math.random() * 30 + 10}s`;
                icon.style.animationDelay = `${Math.random() * 5}s`;
                iconBackground.appendChild(icon);
            }
        });
    </script>
</body>
</html>