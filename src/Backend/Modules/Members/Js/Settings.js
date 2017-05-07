jsBackend.Members.Settings = {
    init: function () {
        jsBackend.Members.Settings.initSources();
    },
    initSources: function () {
        $('#sources').multipleTextbox({
            emptyMessage: utils.string.ucfirst(jsBackend.locale.msg('NoSources')),
            addLabel: utils.string.ucfirst(jsBackend.locale.lbl('Add')),
            removeLabel: utils.string.ucfirst(jsBackend.locale.lbl('Delete')),
            canAddNew: true
        });
    }
};

$(jsBackend.Members.Settings.init);
