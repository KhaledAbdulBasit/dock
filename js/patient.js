function findNearby(type) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            const query = encodeURIComponent(type === 'hospital' ? 'hospital' : 'pharmacy');
            const mapsUrl = `https://www.google.com/maps/search/${query}/@${lat},${lon},15z`;

            window.open(mapsUrl, '_blank');
        }, function (error) {
            alert("حدث خطأ أثناء تحديد الموقع. تأكد من تفعيل الـ GPS.");
        });
    } else {
        alert("متصفحك لا يدعم خاصية تحديد الموقع.");
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // #1 - المنزلق الأول (articlesWrapper)
    const wrapper = document.getElementById('articlesWrapper');
    if (wrapper) {
        const leftArrow = document.querySelector('.arrow.left');
        const rightArrow = document.querySelector('.arrow.right');

        if (leftArrow) leftArrow.onclick = () => wrapper.scrollBy({ left: -300, behavior: 'smooth' });
        if (rightArrow) rightArrow.onclick = () => wrapper.scrollBy({ left: 300, behavior: 'smooth' });
    }

    // #2 - كاروسيل بالمسار (carouselTrack)
    const track = document.getElementById('carouselTrack');
    if (track) {
        const cards = Array.from(track.children);
        if (cards.length > 0) {
            const cardWidth = cards[0].getBoundingClientRect().width + 24; // عرض البطاقة + الهامش
            const prevButton = document.querySelector('.prev-button');
            const nextButton = document.querySelector('.next-button');

            let currentIndex = 0;
            let autoSlideInterval;
            let isDragging = false;
            let startPos = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;

            // الانتقال إلى شريحة محددة
            function goToSlide(index) {
                track.style.transform = `translateX(${-cardWidth * index}px)`;
                currentIndex = index;
            }

            // الانتقال إلى الشريحة التالية
            function nextSlide() {
                if (currentIndex === cards.length - 1) {
                    // الرجوع للشريحة الأولى بسلاسة
                    track.style.transition = 'none';
                    goToSlide(0);
                    setTimeout(() => {
                        track.style.transition = 'transform 0.6s cubic-bezier(0.22, 1, 0.36, 1)';
                    }, 10);
                } else {
                    goToSlide(currentIndex + 1);
                }
            }

            // الانتقال إلى الشريحة السابقة
            function prevSlide() {
                if (currentIndex === 0) {
                    // الانتقال للشريحة الأخيرة بسلاسة
                    track.style.transition = 'none';
                    goToSlide(cards.length - 1);
                    setTimeout(() => {
                        track.style.transition = 'transform 0.6s cubic-bezier(0.22, 1, 0.36, 1)';
                    }, 10);
                } else {
                    goToSlide(currentIndex - 1);
                }
            }

            // إعداد التغيير التلقائي للشرائح
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 4000); // تغيير الشريحة كل 4 ثوان
            }

            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }

            // أحداث الأزرار
            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    prevSlide();
                    resetAutoSlide();
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', () => {
                    nextSlide();
                    resetAutoSlide();
                });
            }

            // أحداث اللمس للهواتف
            track.addEventListener('touchstart', touchStart);
            track.addEventListener('touchmove', touchMove);
            track.addEventListener('touchend', touchEnd);

            // أحداث الماوس للحاسوب
            track.addEventListener('mousedown', mouseStart);
            window.addEventListener('mousemove', mouseMove);
            window.addEventListener('mouseup', mouseEnd);
            window.addEventListener('mouseleave', mouseEnd);

            function touchStart(e) {
                isDragging = true;
                startPos = e.touches[0].clientX;
                prevTranslate = currentTranslate;
                clearInterval(autoSlideInterval);
            }

            function mouseStart(e) {
                e.preventDefault();
                isDragging = true;
                startPos = e.clientX;
                prevTranslate = currentTranslate;
                clearInterval(autoSlideInterval);
            }

            function touchMove(e) {
                if (isDragging) {
                    const currentPosition = e.touches[0].clientX;
                    currentTranslate = prevTranslate + (currentPosition - startPos);
                    track.style.transform = `translateX(${currentTranslate}px)`;
                }
            }

            function mouseMove(e) {
                if (isDragging) {
                    const currentPosition = e.clientX;
                    currentTranslate = prevTranslate + (currentPosition - startPos);
                    track.style.transform = `translateX(${currentTranslate}px)`;
                }
            }

            function touchEnd() {
                handleDragEnd();
            }

            function mouseEnd() {
                handleDragEnd();
            }

            function handleDragEnd() {
                if (!isDragging) return;

                isDragging = false;
                const movedBy = currentTranslate - prevTranslate;

                // إذا تحرك بما يكفي في أي من الاتجاهين، قم بتغيير الشريحة
                if (movedBy < -100) {
                    nextSlide();
                } else if (movedBy > 100) {
                    prevSlide();
                } else {
                    goToSlide(currentIndex);
                }

                startAutoSlide();
            }

            // التنقل باستخدام لوحة المفاتيح (تم تصحيح الاتجاهات)
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    nextSlide();
                    resetAutoSlide();
                } else if (e.key === 'ArrowLeft') {
                    prevSlide();
                    resetAutoSlide();
                }
            });

            // تهيئة الكاروسيل
            startAutoSlide();

            // إيقاف التغيير التلقائي عندما تكون الصفحة غير مرئية
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    clearInterval(autoSlideInterval);
                } else {
                    startAutoSlide();
                }
            });
        }
    }

    // باقي الكود كما هو...
});
