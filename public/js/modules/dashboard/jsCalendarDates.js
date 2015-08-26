var srcByDate = "/dashboard/dates/getcitaspendientes?iType=1";
var srcbyHour = "/dashboard/dates/getcitaspendientes?iType=2";

$( document ).ready(function(){
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaDay'
        },
        editable: false,
        droppable: false,
        eventLimit: true, // allow "more" link when too many events
        events:{
            url: srcByDate,
            error: function() {
                $('#script-warning').show();
            }
        },
        loading: function(bool) {
            $('#loading').toggle(bool);
        },   
        dayClick: function(date, jsEvent, view) {
            $('#calendar').fullCalendar( 'removeEventSource', srcByDate )
            $('#calendar').fullCalendar( 'addEventSource', srcbyHour  ); 
            $('#calendar').fullCalendar( 'changeView', 'agendaDay' );     
            $('#calendar').fullCalendar( 'gotoDate', date );
        },
        eventClick: function (event) {
            showDates(event.IDS);
            return false;
        }                 
    }).on('click', '.fc-button-month', function(){
        $('#calendar').fullCalendar( 'removeEventSource', srcbyHour );
        $('#calendar').fullCalendar( 'addEventSource', srcByDate );
    }).on('click', '.fc-button-agendaDay', function() {      
        $('#calendar').fullCalendar( 'removeEventSource', srcByDate )
        $('#calendar').fullCalendar( 'addEventSource', srcbyHour );
    });

    $('#iFrameSearch').load(function(){
        $("#loader1").hide();
        $('#iFrameSearch').show();
    });    
});

function showDates(sDates){
    $("#loader1").show();
    $('#iFrameSearch').hide();
    $('#iFrameSearch').attr('src','/dashboard/dates/getlistdates?strInput='+sDates);
    $("#myLargeModalLabel").modal("show");
}