$(document).ready(function () {
    var id;
    var toggle = false;
    var selectedElement;

    function highlightOn(element) {
        element.addClass("selected-row");
        element.removeClass("hover");
    }

    function highlightOff(element) {
        element.removeClass("selected-row");
        element.addClass("hover");
    }

    $(".click").click(function () {
        var clickedId = $(this).children("#id").first().text();
        var clickedElement = $(this);

        if (toggle) {
            if (clickedId == id) {
                clearTable();
                highlightOff(clickedElement);
                selectedElement = clickedElement;
            } else {
                id = clickedId;
                drawTable();
                highlightOn(clickedElement);
                highlightOff(selectedElement);
                selectedElement = clickedElement;
            }
        } else {
            id = clickedId;
            drawTable();
            highlightOn(clickedElement);
            selectedElement = clickedElement;
        }
    });

    function drawTable() {
        toggle = true;
        $.ajax({
            type: "POST",
            url: "/pw/pizza/mvc/public/expiredOrders/viewOrderAjax",
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                var string = '';
                string += '<div id="list" class="row"><h3>Order ' + id + '</h3></div>';
                $.each(data.pizzerias, function (index) {
                    string += '<div class="row table-head summary-head">' +
                        '<div class="col-xs-2">Pizzeria:</div>' +
                        '<div class="col-xs-2">' + index + '</div>' +
                        '<div class="col-xs-2">' + this.cost + ' zl</div>' +
                        '<div class="col-xs-2">' + this.phoneNumber + '</div>' +
                        '<div class="col-xs-3">' + this.address + '</div>' +
                        '</div>';
                    $.each(data.pizza, function (index2) {
                        if (this.pizzeria == index) {
                            string += '<div class="row hover pizza-head">' +
                                '<div class="col-xs-2">Pizza <span id="pizzaId">' + index2 + '</span></div>' +
                                '<div class="col-xs-2">' + this.sliceCount + ' slices</div>' +
                                '<div class="col-xs-1">' + this.cost + ' zl</div></div>';
                            $.each(data.summaryTable, function () {
                                if (this.pizzaId == index2) {
                                    string += '<div class="row hover" title="' + this.sliceId + '">' +
                                        '<div class="col-xs-2">' + this.topping + '</div>' +
                                        '<div class="col-xs-2 conflict">';
                                    if (index == 'conflict') {
                                        string += this.pizzeria;
                                    }
                                    string += '</div><div class="col-xs-1">' + this.cost + ' zl</div>' +
                                        '<div class="col-xs-2 col-xs-offset-1">' + this.nickname + '</div></div>';
                                }
                            });
                        }
                    });
                });
                $("#table").html(string);
            }
        });
    }

    function clearTable() {
        $("#table").html('');
        toggle = false;
    }
});