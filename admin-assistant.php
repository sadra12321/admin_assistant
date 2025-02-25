<?php
/*
Plugin Name: AdminPro Plus
Description: پلاگین حرفه‌ای برای مدیریت سایت با امکانات جذاب و رنگی
Version: 1.3
Author: Grok (xAI)
*/

// جلوگیری از دسترسی مستقیم به فایل PHP
if (!defined('ABSPATH')) {
    exit('دسترسی مستقیم مجاز نیست!');
}

// ثبت استایل‌ها و اسکریپت‌ها
function adminpro_plus_enqueue_assets() {
    wp_enqueue_style('adminpro-plus-style', plugins_url('css/adminpro-plus.css', __FILE__));
    wp_enqueue_script(
        'adminpro-plus-script',
         plugins_url('js/adminpro-plus.js', __FILE__),
          array('jquery'),
           '1.3', 
           ['defer',true]
        );
    wp_enqueue_style('fullcalendar-style', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css');
    wp_enqueue_script('fullcalendar-script', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js', array('jquery'), '5.11.3', true);
}
add_action('admin_enqueue_scripts', 'adminpro_plus_enqueue_assets');

// منوی اصلی پلاگین
function adminpro_plus_register_menu() {
    add_menu_page(
        'AdminPro Plus',
        'AdminPro Plus',
        'manage_options',
        'adminpro-plus',
        'adminpro_plus_dashboard',
        'dashicons-admin-customizer',
        2
    );
}
add_action('admin_menu', 'adminpro_plus_register_menu');

// محتوای داشبورد
function adminpro_plus_dashboard() {
    if (isset($_POST['adminpro_note'])) {
        update_option('adminpro_notes', sanitize_textarea_field($_POST['adminpro_note']));
    }
    if (isset($_POST['adminpro_task'])) {
        $tasks = sanitize_textarea_field($_POST['adminpro_task']);
        update_option('adminpro_tasks', $tasks);
        $task_lines = explode("\n", $tasks);
        $events = [];
        $current_date = date('Y-m-d');
        foreach ($task_lines as $task) {
            if (trim($task)) {
                $events[] = array('title' => trim($task), 'date' => $current_date);
            }
        }
        update_option('adminpro_events', $events);
    }
    
    $notes = get_option('adminpro_notes', '');
    $tasks = get_option('adminpro_tasks', '');
    $events = get_option('adminpro_events', []);
    ?>
    <div class="wrap adminpro-plus-wrap">
        <h1 class="adminpro-title">به AdminPro Plus خوش اومدی!</h1>
        <div class="adminpro-grid">
            <div class="adminpro-card animate-bounce">
                <h2>کارهای روزانه</h2>
                <form method="post">
                    <textarea name="adminpro_task" rows="5" placeholder="کارهای امروزتو بنویس (هر خط یه کار)"><?php echo esc_textarea($tasks); ?></textarea>
                    <button type="submit" class="adminpro-btn green">ذخیره و همگام‌سازی</button>
                </form>
            </div>
            <div class="adminpro-card animate-slide">
                <h2>یادداشت‌ها</h2>
                <form method="post">
                    <textarea name="adminpro_note" rows="5" placeholder="یادداشت‌هاتو اینجا بنویس..."><?php echo esc_textarea($notes); ?></textarea>
                    <button type="submit" class="adminpro-btn purple">ذخیره</button>
                </form>
            </div>
            <div class="adminpro-card animate-pulse">
                <h2>آمار بازدید</h2>
                <p>پست‌های منتشرشده: <span class="highlight"><?php echo wp_count_posts()->publish; ?></span></p>
                <p>کاربران سایت: <span class="highlight"><?php echo count_users()['total_users']; ?></span></p>
                <p>نظرات امروز: <span class="highlight"><?php echo wp_count_comments()->approved; ?></span></p>
                <?php if (function_exists('ga_chart_views')): ?>
                    <p>بازدید امروز: <span class="highlight"><?php echo ga_chart_views('today'); ?></span></p>
                <?php else: ?>
                    <p>بازدید امروز: <span class="highlight">نیاز به Google Analytics</span></p>
                <?php endif; ?>
            </div>
            <div class="adminpro-card animate-fade">
                <h2>تقویم</h2>
                <div id="adminpro-calendar"></div>
            </div>
            <div class="adminpro-card animate-fade">
                <h2>میانبرهای سریع</h2>
                <a href="<?php echo admin_url('post-new.php'); ?>" class="adminpro-btn blue">پست جدید</a>
                <a href="<?php echo admin_url('edit-comments.php'); ?>" class="adminpro-btn orange">نظرات</a>
                <a href="<?php echo admin_url('users.php'); ?>" class="adminpro-btn red">کاربران</a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('adminpro-calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fa',
                events: <?php echo json_encode($events); ?>,
                eventColor: '#ff6f61',
                height: 'auto'
            });
            calendar.render();
        });
    </script>
    <?php
}

// جلوگیری از دسترسی مستقیم به فایل‌های استاتیک
function adminpro_plus_restrict_direct_access() {
    $request_uri = $_SERVER['REQUEST_URI'];
    if (preg_match('/wp-content\/plugins\/adminpro-plus\/(css|js)\/.*\.(css|js)$/', $request_uri)) {
        wp_die('دسترسی مستقیم به این فایل مجاز نیست!');
    }
}
add_action('init', 'adminpro_plus_restrict_direct_access');