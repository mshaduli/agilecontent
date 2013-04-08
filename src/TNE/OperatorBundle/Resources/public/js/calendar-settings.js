$(function () {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('.calendar_holder').fullCalendar({
			header: {
				left: 'prev, next',
				center: 'title',
				right: 'month'
			},
			lazyFetching:true,
            timeFormat: {
                    // for agendaWeek and agendaDay
                    agenda: 'h:mmt', // 5:00 - 6:30

                    // for all other views
                    '': 'h:mmt'            // 7p
            },
            dayRender: function( date, cell ) {
//                cell.append($('<input type="checkbox" checked/ style="padding:5px">'));
//                cell.append($('<input type="text" placeholder="500" style="width:60%;"/>'));
                  cell.find($("div")).html(cell.find($("div")).html() + '<center><br/> Available <input type="checkbox" checked style="padding:5px"> <br/> <br/> <input class="span3" type="text" value="295"></center>');
            },
            dayClick: function (date, allDay, jsEvent, view) {  
                console.log(view);
//            $("#dialog").dialog('open');     
//            $("#name").val("(event name)");
//            $("#date-start").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
//            $("#date-end").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
//            $("#time-start").val($.fullCalendar.formatDate(date, 'hh:mmtt'));
//            $("#time-end").val($.fullCalendar.formatDate(date, 'hh:mmtt')); 
    },
			eventSources: [
                    {
                        url: Routing.generate('fullcalendar_loader'), 
						type: 'POST',
                        error: function() {
                           //alert('There was an error while fetching Google Calendar!');
                        }
                    }
			]
		});
});
