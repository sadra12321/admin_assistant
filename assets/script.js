jQuery(document).ready(function($) {
    console.log('AdminPro Plus نسخه 1.2 فعال شد!');
    // افکت کلیک برای دکمه‌ها
    $('.adminpro-btn').on('click', function() {
        $(this).animate({ opacity: 0.7 }, 100).animate({ opacity: 1 }, 100);
    });
    
    // افکت چرخش به کارت‌ها هنگام هاور
    $('.adminpro-card').hover(
        function() { $(this).css('transform', 'rotate(2deg) scale(1.05)'); },
        function() { $(this).css('transform', 'rotate(0deg) scale(1)'); }
    );
    
    // پیام موفقیت بعد از ذخیره
    $('form').on('submit', function() {
        setTimeout(function() {
            alert('تغییرات با موفقیت ذخیره شد!');
        }, 500);
    });
});