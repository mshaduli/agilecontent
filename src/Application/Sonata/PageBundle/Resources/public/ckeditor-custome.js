$(document).ready(function(){
     CKEDITOR.config.allowedContent = true;
    $('.inline-edit-save').bind("click", function(){
       
        var blockid = $(this).attr('id');
            blockid = blockid.substr(17, blockid.length);
        var content = CKEDITOR.instances['cms-block-'+blockid].getData();
        var request = jQuery.ajax({
                url: '/app_dev.php/admin/sonata/page/page/CKEditorSave',
                type: "POST",
                data: {
                        content : content,
                        block_id : blockid
                },
                dataType: "html"
        });

        request.done(function(msg) {
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

function CKEditorSave(obj){
       
        var blockid = $(obj).attr('data-id');
        var content = CKEDITOR.instances['cms-block-'+blockid].getData();
        alert(blockid+":"+content);
        var request = jQuery.ajax({
                url: '/app_dev.php/admin/sonata/page/page/CKEditorSave',
                type: "POST",
                data: {
                        content : content,
                        block_id : blockid
                },
                dataType: "html"
        });

        request.done(function(msg) {
            console.log('Changes Saved');
                //jQuery("#log").html( msg ).show().delay(800).fadeOut();
                return true;
        });

        request.error(function(jqXHR, textStatus) {
                console.log( "Request failed: " + textStatus );
                return true;
        });
        return true;
        
    }