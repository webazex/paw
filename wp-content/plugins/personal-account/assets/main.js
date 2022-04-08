$(document).ready(function (){
    console.log(jQuery.fn.jquery);
    $('#speedRF').submit(
        function (e){
            e.preventDefault();
            let formData = $(this).serialize();
            let actionUrl = $(this).data('sender');
            sendAjaxForm(formData, actionUrl)
        }
    );
    function sendAjaxForm(formData,actionUrl){
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData, // serializes the form's elements.
            success: function(responce)
            {
               console.log($.parseJSON(responce));
            }
        });
    }
});