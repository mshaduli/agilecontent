$(function() {
    
    var idAry  = location.href.split('/');
    var id =idAry[idAry.length-2];
    
    $('div[id^=calendar_holder_]').fullCalendar({
        header: {
            left: 'prev, next',
            center: 'title',
            right: ''
        },
        lazyFetching: false,
        timeFormat: {
            agenda: 'h:mmt', // 5:00 - 6:30

            // for all other views
            '': 'h:mmt'            // 7p
        },
        dayRender: function(date, cell) {
            var room_id = $(this.element).parent().parent().attr('data-room');
            var dateObj = new Date(date);
            var month = parseInt(dateObj.getMonth())+1;
            var newDate = dateObj.getFullYear() + "-" + (month > 9?month:'0'+month) + "-" + (dateObj.getDate() > 9?dateObj.getDate():'0'+dateObj.getDate());
            var Month = $("#calendar_holder_"+room_id).fullCalendar('getDate').getMonth();
           
            if(isNaN(Month))
                {
                   Month = new Date().getMonth(); 
                }
                
            var disabled = (Month == dateObj.getMonth())?'':' disabled="disabled"';
            
            cell.find($("div")).html(cell.find($("div")).html() + '<center><input id="room_calendar_'+room_id+'_' + newDate + '_date" name="room_calendar['+room_id+'][' + newDate + '][date]" class="span6" type="hidden" value="' + newDate + '" '+disabled+'><br/> \n\
                                                                        Available <input id="room_calendar_'+room_id+'_' + newDate + '_available" name="room_calendar['+room_id+'][' + newDate + '][available]" type="checkbox" checked style="padding:5px" '+disabled+'> <br/> <br/> \n\
                                                                        $<input id="room_calendar_'+room_id+'_' + newDate + '_rate" name="room_calendar['+room_id+'][' + newDate + '][rate]" class="span6" type="text" value="295" '+disabled+'></center>');
        },
        eventAfterAllRender:function(view)
        {
            
            var room_id = $(this.element).parent().parent().attr('data-room');
            var events = $("#calendar_holder_"+room_id).fullCalendar('clientEvents');
            if(events.length > 0)
            {
                $.each(events, function(idx, obj){
                    
                    var dateObj = new Date(obj.start);
                    var month = parseInt(dateObj.getMonth())+1;
                    var newDate = dateObj.getFullYear() + "-" + (month > 9?month:'0'+month) + "-" + (dateObj.getDate() > 9?dateObj.getDate():'0'+dateObj.getDate());
                    
                    var id = "#room_calendar_"+obj.room_id+'_'+newDate+'_';
                    
                    $(id+"rate").val(obj.rate);
                    $(id+"available").prop('checked', obj.available);
                    $(id+"date").val(newDate)
                });
            }

        },
                

        eventSources: [
            {
                url: Routing.generate('fullcalendar_loader'),
                type: 'POST',
                data:{
                    accommodation_id: id
                },
                error: function() {
                    //alert('There was an error while fetching Google Calendar!');
            }
            }
        ]
    });
});
