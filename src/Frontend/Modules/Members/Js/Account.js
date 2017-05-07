jsFrontend.Members.Account = {
    init: function () {
        try {
            $('.inputEditor').each(function () {
                CKEDITOR.replace(
                    $(this).attr('id'),
                    {
                        customConfig: '/src/Frontend/Core/Js/ckeditor/config.full.js'
                    }
                );
            });
        } catch (e) {

        }
    }
};

$(jsFrontend.Members.Account.init);
