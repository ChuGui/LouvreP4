jQuery(function($)
{
    var date = new Date();

    $(".js-datepicker").datepicker(
        {
            beforeShowDay: function(date)
            {
                var day = date.getDay();
                var d = date.getUTCDate();
                var month = date.getUTCMonth();
                if(day == 2 || day == 0 || (d ==30 && month == 3) || (d ==31 && month == 9) || (d ==24 && month == 11))
                {
                    return[false]
                }else{
                    return[true]
                }

            },
            minDate: date,
            altField: "#datepicker",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            dateFormat: 'dd-mm-yy',
            showOn: 'button',
            button
        }
    );
})