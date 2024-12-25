let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;
const animationDuration = 6000; // Время для полного цикла анимации (6 секунд)

function changeSlide() {
    // Убираем класс 'active' с текущего слайда
    slides[currentSlide].classList.remove('active');

    // Переходим к следующему слайду
    currentSlide = (currentSlide + 1) % totalSlides;

    // Добавляем класс 'active' следующему слайду
    slides[currentSlide].classList.add('active');
}

// Начальная активация первого слайда
slides[0].classList.add('active');

// Переключаем слайды каждые 6 секунд (с учётом анимации)
setInterval(changeSlide, animationDuration);


