(function ($) {
    $("table.wp-list-table.books").on("click", "a.submit_delete", function (e) {
        e.preventDefault();

        if (!confirm(library.confirm)) {
            return;
        }

        var self = $(this),
            id = self.data("id");

        wp.ajax
            .send("library-book-delete", {
                data: {
                    nonce: library.nonce,
                    id: id,
                },
            })
            .done(function (response) {
                self.closest("tr")
                    .css("background-color", "red")
                    .hide(400, function () {
                        $(this).remove();
                    });
            })
            .fail(function () {
                alert(library.error);
            });
    });
})(jQuery);
