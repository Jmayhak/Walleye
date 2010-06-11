$(document).ready(function() {
    getAllPrayerRequests();
    $('#prayer-request-button').click(function() {
        var keyvalue = {
            name : $('#name').val(),
            email : $('#email').val(),
            message : $('#message').val()
        };
        $.post('/api/prayer/insert', keyvalue, function(xml) {
            //show success via growl
            getAllPrayerRequests();
        });
    });
});

function getAllPrayerRequests() {
    $.get('/api/prayer/getAll', function(xml) {
        var html = '';
        $(xml).find("prayer-request").each(function() {
            html += "<div><div class='head'>";
            html += $(this).find('name').text();
            html += ' ';
            html += $(this).find('date').text();
            html += "</div>";
            html += "<div class='content'>";
            html += $(this).find('message').text();
            html += "</div>";
        });
        $('#prayer-requests').html(html);
    });
}