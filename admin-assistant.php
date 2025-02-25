<?php
/*
Plugin Name: KasbNo Admin Assistant
Description: دستیار ادمین کسپ نو با طراحی مدرن و امکانات پیشرفته.
Version: 2.0
Author: KasbNo Team
*/

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) exit;

// افزودن فایل‌های CSS و JS
// لود کردن CSS و JS برای ادمین
function kasbno_admin_assistant_enqueue_assets() {
    // لود کردن CSS
    wp_enqueue_style('kasbno-assistant-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    
    // لود کردن JS
    wp_enqueue_script('kasbno-assistant-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'kasbno_admin_assistant_enqueue_assets');


// افزودن منوی دستیار ادمین
function kasbno_admin_assistant_menu() {
    add_menu_page(
        'دستیار ادمین',
        'دستیار ادمین',
        'manage_options',
        'kasbno-admin-assistant',
        'kasbno_admin_assistant_page',
        'dashicons-admin-generic',
        2
    );
}
add_action('admin_menu', 'kasbno_admin_assistant_menu');

// محتوای صفحه دستیار ادمین
function kasbno_admin_assistant_page() {
    echo '<div class="kasbno-container">';
    echo '<h1>دستیار ادمین کسپ نو</h1>';
    
    // پیام خوش‌آمدگویی
    $current_user = wp_get_current_user();
    echo '<h2 class="welcome-msg">خوش آمدید، ' . esc_html($current_user->display_name) . '!</h2>';

    // آمار بازدید
    $views_today = get_option('kasbno_views_today', 0);
    $views_yesterday = get_option('kasbno_views_yesterday', 0);
    echo '<div class="card">';
    echo '<h3>آمار بازدید</h3>';
    echo '<div class="progress-bar"><span style="width: ' . ($views_today * 2) . '%;"></span>امروز: ' . esc_html($views_today) . '</div>';
    echo '<div class="progress-bar"><span style="width: ' . ($views_yesterday * 2) . '%;"></span>دیروز: ' . esc_html($views_yesterday) . '</div>';
    echo '</div>';

    // To-Do List
    echo '<div class="card">';
    echo '<h3>لیست کارها</h3>';
    $todo_list = get_option('kasbno_admin_todo', []);
    echo '<ul class="todo-list">';
    foreach ($todo_list as $index => $task) {
        echo '<li><input type="checkbox" class="todo-checkbox" data-index="' . $index . '" /> ' . esc_html($task) . 
             ' <button class="todo-delete" data-index="' . $index . '">حذف</button></li>';
    }
    echo '</ul>';
    echo '<input type="text" id="new-task" placeholder="کار جدید..." />';
    echo '<button id="add-task" class="button button-primary">افزودن کار</button>';
    echo '</div>';

    echo '</div>';
}

// ثبت بازدیدها
function kasbno_count_visits() {
    if (is_admin()) return;
    $today = date('Y-m-d');
    $views_today = get_option('kasbno_views_today', 0);
    $last_update = get_option('kasbno_views_last_update', '');
    if ($today != $last_update) {
        update_option('kasbno_views_yesterday', $views_today);
        update_option('kasbno_views_today', 1);
        update_option('kasbno_views_last_update', $today);
    } else {
        update_option('kasbno_views_today', $views_today + 1);
    }
}
add_action('wp_head', 'kasbno_count_visits');
