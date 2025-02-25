jQuery(document).ready(function($) {
    console.log('AdminPro Plus نسخه 1.5 فعال شد!');
    // افکت کلیک برای دکمه‌ها با لرزش و صدا
    $('.adminpro-btn').on('click', function() {
        var audio = new Audio('https://www.myinstants.com/media/sounds/click-sound-4-184105.mp3');
        audio.play();
        $(this).effect('shake', { times: 3 }, 200).animate({ opacity: 0.7 }, 100).animate({ opacity: 1 }, 100);
    });
    
    // افکت چرخش، بزرگ‌نمایی و پرش به کارت‌ها هنگام هاور
    $('.adminpro-card').hover(
        function() { $(this).css('transform', 'rotate(2deg) scale(1.05)').css('transition', 'all 0.3s ease'); },
        function() { $(this).css('transform', 'rotate(0deg) scale(1)'); }
    );
    
    // پیام موفقیت با انیمیشن
    $('form').on('submit', function() {
        setTimeout(function() {
            alert('تغییرات با موفقیت ذخیره شد!');
            $('.adminpro-card').effect('highlight', { color: '#e0f8e0' }, 2000); // سبز ملایم برای هیدلایت
        }, 500);
    });
});