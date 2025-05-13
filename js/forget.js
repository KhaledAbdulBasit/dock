// إضافة تأثيرات حركية
function addAnimationEffects() {
    const inputs = document.querySelectorAll('input');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.add('pulse');
        });
        
        input.addEventListener('blur', function() {
            this.classList.remove('pulse');
        });
    });
}

// استدعاء الدالة لإضافة التأثيرات
document.addEventListener('DOMContentLoaded', addAnimationEffects);

// وظيفة إعادة تعيين كلمة المرور
function resetPassword() {
    const email = document.getElementById('email').value;
    const name = document.getElementById('name').value;
    const recoveryCode = document.getElementById('recovery-code').value;
    
    // التحقق من إدخال البيانات
    if (email && name && recoveryCode) {
        alert("تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني");
        
        // تغيير حالة الزر
        const resetBtn = document.getElementById('reset-btn');
        resetBtn.textContent = "تم الإرسال";
        resetBtn.style.backgroundColor = "#28a745";
        resetBtn.disabled = true;
        
        // محاكاة إرسال البيانات إلى الخادم
        console.log(معلومات استعادة كلمة المرور: ${email}, ${name}, ${recoveryCode});
    } else {
        alert("الرجاء إدخال كافة المعلومات المطلوبة");
    }
}

// وظيفة الرجوع للصفحة الرئيسية
function goBack() {
    alert("جاري العودة إلى الصفحة الرئيسية");
    // يمكن استبدالها بـ:
    // window.location.href = "index.html";
}
