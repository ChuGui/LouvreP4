jQuery(function($)
{
    var $selectDay = $("tbody a");
    var $infoNbTicket=$("#infoNbTicket");
    console.log($selectDay);

    $(".datepicker").on("click", "tbody a", function(e)
    {
        var $day = $selectDay.val();
        var $month = $selectDay.parent().attr('data-month');
        var $year = $selectDay.parent().attr('data-year');
        console.log("Le jour est" + $day);
        console.log("Le mois est" + $month);
        console.log("L'année est" + $year);

        /*$.ajax(
            {
                type: "GET",

                url: "path('louvre_ticket_homepage')"
            })*/
    });

});

