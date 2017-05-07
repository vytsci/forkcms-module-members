jsFrontend.Members.Requisites = {
    init: function () {
        jsFrontend.Members.Requisites.initControlTerms();
        jsFrontend.Members.Requisites.initControlEditButton();
        jsFrontend.Members.Requisites.initControlFieldBusinessEntityType();
    },
    initControlTerms: function () {
        var $terms = $('#terms');
        var $btnSubmit = $terms.closest('form').find('*[type="submit"]');

        $terms.on('change', function () {
            $btnSubmit.prop('disabled', !$(this).prop('checked'))
        }).trigger('change');
    },
    initControlEditButton: function () {
        var $button = $('#edit-button');
        var $form = $button.closest('form');

        $button.on('click', function () {
            $form.find('*[readonly],*[disabled]:not(button[type="submit"])').prop('readonly', false).prop('disabled', false);
            $button.remove();
            $('html, body').scrollTop($form.offset().top);
        });
    },
    initControlFieldBusinessEntityType: function () {
        $('#businessEntityType').on('keyup keydown', function () {
            $(this).val($(this).val().toUpperCase());
        });
    }
};

$(jsFrontend.Members.Requisites.init);
