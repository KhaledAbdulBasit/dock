// ====== تأثير الإضاءة عند تحريك الفأرة ======
document.addEventListener("mousemove", (event) => {
    const lightEffect = document.body;
    const x = event.clientX / window.innerWidth * 100;
    const y = event.clientY / window.innerHeight * 100;
    lightEffect.style.background = radial-gradient(circle at ${x}% ${y}%, rgba(255, 255, 255, 0.2) 10%, transparent 50%);
});

// ====== تأثير ظهور العناصر بالتدريج عند التمرير ======
const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.2 });

document.querySelectorAll(".doctor-item, footer").forEach(item => {
    observer.observe(item);
});

// ====== تأثير عند الضغط على الأيقونات لفتح صفحة جديدة ======
document.querySelectorAll(".doctor-card").forEach(card => {
    card.addEventListener("click", () => {
        const specialty = card.getAttribute("data-specialty");
        window.location.href = ${specialty}.html;
    });
});

// ====== تأثير عند تمرير الماوس على الأيقونات ======
document.querySelectorAll(".doctor-card").forEach(card => {
    card.addEventListener("mouseenter", () => {
        card.style.transform = "scale(1.2) rotate(5deg)";
    });
    card.addEventListener("mouseleave", () => {
        card.style.transform = "scale(1) rotate(0deg)";
    });
})