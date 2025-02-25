jQuery(document).ready(function($) {
    $('#add-task').on('click', function() {
        var newTask = $('#new-task').val();
        if (newTask) {
            $('.todo-list').append('<li>' + newTask + ' <button class="todo-delete">حذف</button></li>');
            $('#new-task').val('');
        }
    });

    $('.todo-list').on('click', '.todo-delete', function() {
        $(this).parent().remove();
    });
});
