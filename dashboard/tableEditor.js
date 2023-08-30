// Import jQuery and jQuery UI Resizable
// Make sure to include jQuery and jQuery UI libraries in your HTML

$(document).ready(function() {
    // Make table rows, columns, and table itself resizable
    $("#editable-table tr").resizable({ handles: "s" }); // Resizable rows
    $("#editable-table td").resizable({ handles: "e" }); // Resizable columns
    $("#editable-table").resizable(); // Resizable table

    // Save table modifications as a CSS file
    $("#save-button").click(function() {
        // Collect the CSS properties after resizing
        var tableCss = $("#editable-table").attr("style");

        // Send the CSS to the server to save as sold.css
        $.post("save_table_css.php", { css: tableCss }, function(response) {
            console.log(response); // Handle the server response if needed
        });
    });
});
