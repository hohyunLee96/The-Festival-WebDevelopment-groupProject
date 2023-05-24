

function redirect(url) {

    window.location = url;
}




function redirectToPurchaseTicketPage() {

    let btns = document.querySelectorAll('.buyTicket');

    btns.forEach((btn) => {

        btn.addEventListener('click', function (event) {

            var availableEventId = parseInt($(this).parent().find('#availableEventId').text());

            $.ajax({
                url: "http://localhost/api/AvailableEvents/retrieveAvailableEventData?id=" + availableEventId,
                type: "GET",
                dataType: "JSON",
                success: function (jsonStr) {
                    var eventTypeId = jsonStr[2];
                    var url = '';
                    if (eventTypeId == 1) {
                        url = 'http://localhost/festival/history/ticketSelection';
                    }
                    else if (eventTypeId == 2) {
                        //dance ticket selection url

                    }
                    redirect(url);


                },

            });
        });
    });





}


redirectToPurchaseTicketPage();