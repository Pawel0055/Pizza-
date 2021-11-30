var deleteTriggered = false;
$(document).ready(function () {
    refresh();

    $(".test").on("mouseenter", ".delete", function () {
        $(this).parent().addClass("delete-row");
        $(this).parent().removeClass("click hover");
    });
    $(".test").on("mouseleave", ".delete", function () {
        $(this).parent().removeClass("delete-row");
        $(this).parent().addClass("click hover");
    });
    $(".test").on("click", ".delete", function () {
        // deleteTriggered = true;
        var id = $(this).siblings("#id").text();
        var thisRef = $(this);
        swal({
            title: 'Delete order?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then(function() {
            $.ajax({
                url: "/pw/pizza/mvc/public/orders/deleteAjax",
                data: {
                    id: id
                },
                type: "POST",
                async: false
            });
            refresh();
        });
        deleteTriggered = false;
    });
    $(".test").on("click", ".click", function () {
        if (!deleteTriggered) {
            var id = $(this).children("#id").first().text();
            window.location.assign("/pw/pizza/mvc/public/orders/editJS/" + id);
        }
        deleteTriggered = false;
    });
    $("#newOrder").click(function () {
        var hours = $("#hours").val();
        $.ajax({
            url: "/pw/pizza/mvc/public/orders/addNewAjax",
            data: {
                hours: hours
            },
            type: "POST",
            dataType: "json",
            async: false
        });
        refresh();
    });

    function refresh() {
        $.ajax({
            type: "POST",
            url: "/pw/pizza/mvc/public/orders/refreshAjax",
            dataType: "json",
            success: function (data) {
                var string = '';
                $.each(data, function() {
                    string += '<div class="row hover click table-row">' +
                        '<div id="id" class="col-xs-2">' + this.id + '</div>' +
                        '<div class="col-xs-4">' + this.date + '</div>' +
                        '<div class="col-xs-3">' + this.deadline + '</div>' +
                        '<div class="col-xs-2">' + this.count + '</div>' +
                        '<div class="col-xs-1 delete">delete</div></div>';
                });
                $("#table").html(string);
            }
        });
    }
    window.setInterval(refresh, 2000);
});