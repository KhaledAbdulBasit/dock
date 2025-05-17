<?php session_start(); 

include_once "../includes/database.php";

$doctor     = $_SESSION['doctor'];
$price      = $_SESSION['price'];
$appointment = $_SESSION['appointment'];
$type       = $_SESSION['type'];
$user_id = $_SESSION['user_id'];



/*
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("طريقة الطلب غير صحيحة.");
}*/

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'patient') {
    die("You must be logged in as a patient.");
}


if (!isset($_SESSION['doctor'], $_SESSION['price'], $_SESSION['appointment'], $_SESSION['type'])) {
    die("Consultation data is not available.");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];
    $mobile_number = $_POST['mobile_number'];
    
    // Insert payment details
    $sql = "INSERT INTO payments (card_number, expiry_date, cvv, mobile_number) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ssss", $card_number, $expiry_date, $cvv, $mobile_number);
    
    if ($stmt->execute()) {
        $doctor = $_SESSION['doctor'];
        $price = $_SESSION['price'];
        $appointment = $_SESSION['appointment'];
        $appointment_id = $_SESSION['appointment']['id'];
        $type = $_SESSION['type'];
        $patient_id = $_SESSION['user_id'];
        
        // Insert consultation
        $stmt = $conn->prepare("
            INSERT INTO consultations 
            (patient_id, doctor_id, type, appointment_id)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iisi", $patient_id, $doctor['id'], $type, $appointment_id);
        
        if ($stmt->execute()) {
            $stmt = $conn->prepare("UPDATE appointments SET Booking='Closed' WHERE id=?");
            $stmt->bind_param("i", $appointment_id);
      
            if ($stmt->execute()) {
                $success = "Data updated successfully.";
            } else {
                $errors[] = "An error occurred during the update.";
            }
            $stmt->close();

            // Remove consultation data from session after successful booking
            unset($_SESSION['doctor'], $_SESSION['price'], $_SESSION['appointment'], $_SESSION['type']);
        
            echo "<h2>Payment Successful!</h2>";
            echo "<p>Your consultation has been booked with Dr. " . htmlspecialchars($doctor['name']) . ".</p>";
            echo "<p>Appointment Time: " . htmlspecialchars($appointment['time']) . "</p>";
            echo "<p>Consultation Type: " . ($type === 'online' ? "Online" : "In Clinic") . "</p>";
            echo "<a href='../patient.php'>Return to Dashboard</a>";
            header("refresh:5;url=../patient.php");
        } else {
            echo "An error occurred during booking: " . $stmt->error;
        }
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="styles.css">
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
  height: 0; /* Make page scrollable */
}

@keyframes float {
  0% {
      transform: translateY(100vh) rotate(0deg);
  }
  100% {
      transform: translateY(-100px) rotate(360deg);
  }
}
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap');
        /* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Lato", sans-serif;
    line-height: 1.5;
    background-color: #f3f4f6;
    color: #1f2937;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

h1 {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 2rem;
}

h2 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Grid Layout */
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 1024px) {
    .checkout-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.left-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Card styles */
.card {
    background: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 120, 212, 0.5);
}

/* Order summary styles */
.order-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.item-name {
    font-weight: 500;
}

.item-quantity {
    font-size: 0.875rem;
    color: #6b7280;
}

.item-price {
    font-weight: 500;
}

.order-summary {
    border-top: 1px solid #e5e7eb;
    padding-top: 1rem;
    margin-top: 1rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.summary-row.total {
    font-size: 1.125rem;
    font-weight: 600;
    margin-top: 1rem;
}

/* Form styles */
.form-group {
    margin-bottom: 1rem;
    
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.25rem;
}

.input-icon {
    position: relative;
    width:300px;

    
}

.input-icon p{
    position:absolute;
    left :40px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}
.input-icon svg {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

input, textarea {
    width: 100%;
    padding: 0.625rem 0.75rem;
    padding-left: 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}

textarea {
    padding-left: 0.75rem;
    resize: vertical;
}

input:focus, textarea:focus {
    outline: none;
    border-color: hsl(217, 89%, 71%);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Payment methods styles */
.payment-methods {
    margin-bottom: 2rem;
}

.payment-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

@media (min-width: 640px) {
    .payment-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.payment-method {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    border: 1px solid hsl(216, 92%, 20%);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}

.payment-method:focus {
    background-color: #ddecfd;
}


.payment-method img {
    height: 2rem;
    max-width: 100%;
}

.text-method {
    font-weight: 600;
    font-size: 0.875rem;
}

.vodafone {
    width: 70px;
}

.etisalat {
    width: 70px;
}

.instapay img{
    width: 70px;
}
    


/* Pay button */
.pay-button {
    width: 100%;
    padding: 0.75rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
}

.pay-button:hover {
    background-color: #2563eb;
}

/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal.hidden {
    display: none;
}

.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    text-align: center;
    max-width: 400px;
    width: 90%;
}

.success-icon {
    color: #10b981;
    margin-bottom: 1rem;
}

.confirmation-details {
    margin-top: 1rem;
    padding: 1rem;
    background-color: #f9fafb;
    border-radius: 0.375rem;
}

.confirmation-email {
    font-weight: 500;
    margin-top: 0.25rem;
}

.modal-button {
    margin-top: 1.5rem;
    padding: 0.75rem 1.5rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 0.375rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.modal-button:hover {
    background-color: #2563eb;
}

.hidden {
    display: none;
}
    </style>
</head>
<body>
    <div class="icon-background" id="icon-background"></div>
    <div class="dummy-content"></div>
    <div class="container">
        <br>
        <br>
        <br>
        <br>
        
        <div class="checkout-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Order Summary -->
                <div class="card">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8Z"></path><path d="M19 12H5"></path><path d="M16 6V4"></path><path d="M8 6V4"></path></svg>
                        Consultation Summary
                    </h2>
                    <div class="order-items">
                        <div class="order-item">
                            <div>
                                <p class="item-name"><?= ucfirst($type)?> consultation</p>
                            </div>
                            <p class="item-price"><?= $price?> L.E</p>
                        </div>
                        
                    </div>
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span><?= $price?> L.E</span>
                        </div>
                        <div class="summary-row">
                            <span>VAT (14%)</span>
                            <span><?= $price * .14 ?> L.E</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span><?= $price + ($price * .14) ?> L.E</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="card">
                    <h2>DoctorDetails</h2>
                    <div class="form-group">
                        <label for="name">Name Of Doctor :</label>
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg><br>
                            <p>Dr.<?= $doctor['name']?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Specialization :</label>
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8Z"></path><path d="M19 12H5"></path><path d="M16 6V4"></path><path d="M8 6V4"></path></svg><br>
                            <p><?= $doctor['specialization']?></p>
                        </div>
                    </div> 
                    
                    <!-- <div class="form-group">
                        <label for="phone">Phone</label>
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg><br>
                            <p><?= $doctor['phone']?></p>
                        </div>
                    </div> -->
                    <!-- <div class="form-group">
                        <label for="address">Education</label>
                        <p>
                            <ul>
                            <?php
                                $items = explode('|', $doctor['education']);
                                foreach ($items as $item) {
                                    echo "<li>" . trim($item) . "</li>";
                                }?>
                            </ul></p>
                    </div> -->
                </div>
            </div>

            <!-- Right Column -->
            <div class="card payment-section">
                <h2>Payment Method</h2>
                <div class="payment-methods">
                    <div class="payment-grid">
                        <button class="payment-method active" data-method="visa">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa">
                        </button>
                        <button class="payment-method" data-method="mastercard">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard">
                        </button>
                        <button class="payment-method" data-method="amex">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="American Express">
                        </button>
                    </div>
                    <div class="payment-grid">
                        <button class="payment-method" data-method="fawry">
                            <img src="../img/fawry.jpg" alt="">
                        </button>
                        <button class="payment-method" data-method="vodafone">
                            <img src="../img/voda.jpg" alt="">
                        </button>
                        <button class="payment-method" data-method="etisalat">
                            <img src="../img/e&.jpg" alt="">
                        </button>
                        <button class="payment-method" data-method="instapay">
                            <img src="../img/instapay.jpg" alt="">
                        </button>
                    </div>
                </div>

                <form id="paymentForm" class="payment-form" method="POST">
                    <input type="hidden" name="method_id" value="">
                    <div id="cardFields">
                        <div class="form-group">
                            <label for="cardNumber">Card Number</label>
                            <div class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"></rect><line x1="2" x2="22" y1="10" y2="10"></line></svg>
                                <input type="text" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiryDate">Expiry Date</label>
                                <div class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                                    <input type="text" id="expiryDate" name="expiry_date" placeholder="MM/YY" maxlength="5">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <div class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input type="password" id="cvv" name="cvv" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="mobileFields" class="hidden">
                        <div class="form-group">
                            <label for="mobileNumber">Mobile Number</label>
                            <div class="input-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                <input type="tel" id="mobileNumber" name="mobile_number"  placeholder="01xxxxxxxxx" maxlength="11">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="pay-button">Pay 125.39L.E</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal hidden">
        <div class="modal-content">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="success-icon"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <h2 style="justify-content: center;">Payment Successful!</h2>
            <p>Your payment has been processed successfully.</p>
            <div class="confirmation-details">
            </div>
            <button onclick="closeModal()" class="modal-button">Make Another Payment</button>
        </div>
    </div>

    <script >
        // Form elements
const paymentForm = document.getElementById('paymentForm');
const cardFields = document.getElementById('cardFields');
const mobileFields = document.getElementById('mobileFields');
const successModal = document.getElementById('successModal');
const confirmationEmail = document.getElementById('confirmationEmail');
// Payment method buttons
const paymentMethods = document.querySelectorAll('.payment-method');
// Input fields
const cardNumber = document.getElementById('cardNumber');
const expiryDate = document.getElementById('expiryDate');
const mobileNumber = document.getElementById('mobileNumber');
// Format card number with spaces
cardNumber.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }
    e.target.value = formattedValue.trim();
});
// Format expiry date
expiryDate.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    if (value.length > 2) {
        value = value.slice(0, 2) + '/' + value.slice(2);
    }
    e.target.value = value;
});
// Format mobile number
mobileNumber.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/\D/g, '').slice(0, 11);
});
// Handle payment method selection
paymentMethods.forEach(method => {
    method.addEventListener('click', () => {
        // Remove active class from all methods
        paymentMethods.forEach(m => m.classList.remove('active'));
        // Add active class to selected method
        method.classList.add('active');
        const methodType = method.dataset.method;
        // Show/hide appropriate fields based on payment method
        if (['visa', 'mastercard', 'amex'].includes(methodType)) {
            cardFields.classList.remove('hidden');
            mobileFields.classList.add('hidden');
            cardNumber.required = true;
            expiryDate.required = true;
            mobileNumber.required = false;
        } else if (['vodafone', 'etisalat', 'fawry', 'instapay'].includes(methodType)) {
            cardFields.classList.add('hidden');
            mobileFields.classList.remove('hidden');
            cardNumber.required = false;
            expiryDate.required = false;
            mobileNumber.required = true;
        } else if (methodType === 'paypal') {
            cardFields.classList.add('hidden');
            mobileFields.classList.add('hidden');
            cardNumber.required = false;
            expiryDate.required = false;
            mobileNumber.required = false;
        }
    });
});
// Handle form submission
paymentForm.addEventListener('submit', (e) => {
    
    //e.preventDefault();
    
    // Check if the form is valid before proceeding
    if (!paymentForm.checkValidity()) {
        alert("Please fill in all required fields correctly.");
        return;
    }
    // Get email from form
    //const email = document.getElementById('email').value;
    //confirmationEmail.textContent = email;
    // Show success modal
    successModal.classList.remove('hidden');
});
// Close modal function
window.closeModal = function() {
    successModal.classList.add('hidden');
    paymentForm.reset();
};
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