 Aloha.ready( function() {
                            //var $ = Aloha.jQuery;
                            $('.editable').aloha();                            
                     });
                     
                     Aloha.ready(function() {
                    Aloha.require( ['aloha', 'aloha/jquery'], function( Aloha, jQuery) {

                                    // save all changes after leaving an editable
                                                  Aloha.bind('aloha-editable-deactivated', function(){ 
                                                                
                                                                var bloc_obj = Aloha.activeEditable.obj[0];
                                                                var container = $(bloc_obj).filter('div').attr('id');
                                                                var blockId = $('#'+container).attr('data-id');
                                                                //var content = Aloha.activeEditable.getContents();
                                                                var content = $.trim($('#'+container).html());
                                                                alert($('#'+container+' div div').text());
                                                                var contentId = Aloha.activeEditable.obj[0].id;
                                                                var pageId = '{{ page.id }}';

                                                                // textarea handling -- html id is "xy" and will be "xy-aloha" for the aloha editable
                                                                if ( contentId.match(/-aloha$/gi) ) {
                                                                        contentId = contentId.replace( /-aloha/gi, '' );
                                                                }

                                                                var request = jQuery.ajax({
                                                                        url: '/app_dev.php/admin/sonata/page/page//aloha',
                                                                        type: "POST",
                                                                        data: {
                                                                                id: 1,
                                                                                content : content,
                                                                                contentId : contentId,
                                                                                block_id : blockId
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
});