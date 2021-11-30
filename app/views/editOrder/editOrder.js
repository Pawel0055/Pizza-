$(document).ready(function(){
    refresh();

    $("#addPizza").click(function () {
        $.ajax({
            type: "POST",
            url: "/pw/pizza/mvc/public/editOrder/addPizza",
            data: {
                sliceCount: $("#sliceCount").val(),
                slices: $("#slices").val()
            },
            dataType: "json",
            success: function (data) {
                swal({
                    title: 'Order no longer exists',
                    type: 'error'
                }).then(function () {
                    window.location.assign("/pw/pizza/mvc/public");
                });
            },
            async: false
        });
        refresh();
    });

    $("#table").on("click", ".click", function(){
        var id = $(this).attr("title");
        window.location.assign("/pw/pizza/mvc/public/editOrder/edit/" + id);
    });
    $("#table").on("click", ".delete", function(){
        var id = $(this).siblings().children("#pizzaId").text();
        swal({
            title: 'Delete pizza?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then(function() {
            $.ajax({
                type: "POST",
                url: "/pw/pizza/mvc/public/editOrder/deletePizzaJS",
                data: {
                    id: id
                },
                async: false
            });
            refresh();
        });
    });
    $("#table").on("mouseenter", ".delete", function() {
        $(this).parent().addClass("delete-row");
        $(this).parent().removeClass("hover");
    });
    $("#table").on("mouseleave", ".delete", function() {
        $(this).parent().removeClass("delete-row");
        $(this).parent().addClass("hover");
    });

    function refresh() {
        $.ajax({
            type: "POST",
            url: "/pw/pizza/mvc/public/editOrder/refreshAjax",
            dataType: "json",
            success: function (data) {
                var string = '';
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
                                '<div class="col-xs-1">' + this.cost + ' zl</div>' +
                                '<div class="col-xs-1 col-xs-offset-6 delete">delete</div></div>';
                            $.each(data.summaryTable, function () {
                                if (this.pizzaId == index2) {
                                    string += '<div class="row hover click" title="' + this.sliceId + '">' +
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
            },
            /*error: function (error) {
                console.log(error.responseText);
            }*/
        });
    }

    window.setInterval(refresh, 2000);
});