jsBackend.Members = {
    init: function () {
        jsBackend.Members.initToggleOnCheck();
        jsBackend.Members.initSwitchAddressPrimary();
    },
    initToggleOnCheck: function () {
        $('label[data-toggle-on-check]').each(function () {
            var $toggle = $($(this).data('toggle-on-check'));
            var $checkbox = $(this).find('input[type="checkbox"]');

            $toggle.hide();

            if ($checkbox.is(':checked')) {
                $toggle.show();
            }

            $checkbox.bind('change', function () {
                if ($(this).is(':checked')) {
                    $toggle.fadeIn();
                    return;
                }

                $toggle.fadeOut();
            });
        });
    },
    initSwitchAddressPrimary: function () {
        $('.jsSwitchAddressPrimary').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                data:
                {
                    fork: {
                        module: 'Members',
                        action: 'SetAddressPrimary'
                    },
                    id: $this.data('address-id'),
                    primary: $this.data('address-primary')
                },
                success: function(json, textStatus)
                {
                    var $addresses = $this.closest('.jsAddresses').find('.jsAddress');
                    var $address = $this.closest('.jsAddress');

                    if (json.data.primary) {
                        $addresses
                            .removeClass('panel-primary panel-default')
                            .addClass('panel-default')
                            .find();
                        $addresses.find('.jsSwitchAddressPrimary[data-address-primary="0"]').hide();
                        $addresses.find('.jsSwitchAddressPrimary[data-address-primary="1"]').show();
                    }

                    $address
                        .removeClass('panel-primary panel-default')
                        .addClass(json.data.primary?'panel-primary':'panel-default');

                    $address.find(
                        '.jsSwitchAddressPrimary[data-address-primary="' + (json.data.primary?'1':'0') + '"]'
                    ).hide();
                    $address.find(
                        '.jsSwitchAddressPrimary[data-address-primary="' + (json.data.primary?'0':'1') + '"]'
                    ).show();
                }
            });
        });
    }
};

$(jsBackend.Members.init);
