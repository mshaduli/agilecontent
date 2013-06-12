$(document).ready(function(){
     CKEDITOR.config.allowedContent = true;
    $('#ck-change-save').bind("click", function(){
        
        var dataobj = [];

        $.each(CKEDITOR.instances, function(k,v){
            dataobj.push({key:k, val:v.getData()})
        });
        
        var request = jQuery.ajax({
                url: '/app_dev.php/admin/sonata/page/page/CKEditorSave',
                type: "POST",
                data: { data1: dataobj},
                dataType: "html"
        });

        request.done(function(msg) {
            $('.alert').removeClass('alert');
            console.log('Changes Saved');
                //jQuery("#log").html( msg ).show().delay(800).fadeOut();
                return true;
        });

        request.error(function(jqXHR, textStatus) {
                console.log( "Request failed: " + textStatus );
                return true;
        });
        return true;
        
    });
});