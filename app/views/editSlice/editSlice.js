$(document).ready(function () {
    var selected;
    var id = $(document).find("#toppingId").first().attr("title");

    function highlight() {
        selected = $(".test").find(".id[title=" + id + "]").parent();
        selected.addClass("selected-row");
    }

    function highlightOn(element) {
        element.addClass("selected-row");
        element.removeClass("hover");
    }

    function highlightOff(element) {
        element.removeClass("selected-row");
        element.addClass("hover");
    }

    highlight();

    $("#table-items").on("click", ".click", function () {
        id = $(this).find(".id").first().text();
        highlightOn(selected);
        selected = $(this);
        highlightOff(selected);
    });

    $("#submit").click(function () {
        $.ajax({
            type: "POST",
            url: "/pw/pizza/mvc/public/editSlice/edit",
            data: {
                toppingId: id,
                nickname: $("#nickname").val()
            },
            async: false
        });
        window.location.href = "/pw/pizza/mvc/public/editOrder";
    });
    $(".checkbox-click").click(function () {
        var checkedList = [];

        // Search form for checked checkboxes
        $(this).parent().parent().parent().parent().find(".checkbox-click").each(function () {
            if (this.checked) {
                checkedList.push(this.value);
            }
        })
        $.ajax({
            type: "POST",
            url: "/pw/pizza/mvc/public/editSlice/checkIngredientsAjax",
            data: {
                ingredientList: checkedList
            },
            dataType: "json",
            success: function (data) {
                var string = ''
                $.each(data.foundTable, function () {
                    var row = this;
                    string += '<div class="row click">' +
                        '<div class="id col-xs-1" title="' + row.id + '">' + row.id + '</div>' +
                        '<div class="col-xs-3">' + row.name + '</div>' +
                        '<div class="col-xs-2">' + row.cost + ' zl</div>' +
                        '<div class="col-xs-2">' + row.pizzeria + '</div>' +
                        '<div class="col-xs-4">';
                    $.each(data.ingredientTable, function () {
                        if (this.toppingId == row.id) {
                            string += this.name + ', ';
                        }
                    });
                    string += '</div></div>';
                });
                $("#table-items").html(string);
                highlight();
            }
        });
    });
});