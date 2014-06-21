jQuery(document).ready(function ($) {

    var AddEvent = function (target, event, method) {
        if (target.addEventListener) {
            target.addEventListener(event, method);
            return;
        }
        if (target.attachEvent) {
            target.attachEvent('on' + event, method);
            return;
        }
        target['on' + event] = method;
    }

    AddEvent(window, 'message', function (m) {
        if (m.origin != $('#np_config-nibl-url').val()) {
            //don't handle this message.  It's not from nibl
            return;
        }

        var d = $.parseJSON(m.data);

        switch (d.message) {
            case 'niblDomainSecret':
                $('#np_config-nibl-app-secret').val(d.data.Result.DomainSecret);
                np_config_update();
                alert("nibl successfully configured");
                break;
            case 'niblValidationKey':
                $('#np_config-nibl-key').val(d.data.Result);
                np_config_update();
                break;
            case 'niblError':
                alert(d.data.ResponseMessage);
                break;
        }
    });

    $('#np_action_submit').click(function () { np_create_action(); });
    $('#np_item_submit').click(function () { np_create_item(); });

    $('#np-pluginconflink').click(function (s) { np_config_toggle_setting_visibility(); });
    $('#np_config-active').change(function () { np_config_active(); });
    $('#np_config_submit').click(function () { np_config_update(); alert("Settings Saved"); });

    function np_create_action() {
        nibl_action_title = $('#np_action_title').val();
        nibl_action_description = $('#np_action_description').val();
        nibl_action_price = $('#np_action_price').val();
        nibl_action_payment_model = $('#np_action_payment_model').val();
        nibl_site_url = $('#np_action_site_url').val();
        if ($('#np_action_inherit').is(':checked')) {
            nibl_action_inherit = "Inherit";
        }
        else {
            nibl_action_inherit = "DoNotInherit";
        }

        url = '/wp-admin/admin-ajax.php';
        if (nibl_action_title == "") {
            $("#np_action_display_message").text("You must have a title to create an action.");
        }
        else if (nibl_action_description == "") {
            $("#np_action_display_message").text("You must have a description to create an action.");
        }
        else if (nibl_action_price == "") {
            $("#np_action_display_message").text("You must have a price to create an action.");
        }
        else {
            $.post(ajaxurl,
                {
                    "action": "np_create-action",
                    "nibl_action_title": nibl_action_title,
                    "nibl_action_description": nibl_action_description,
                    "nibl_action_price": nibl_action_price,
                    "nibl_action_payment_model": nibl_action_payment_model,
                    "nibl_action_inherit": nibl_action_inherit,
                    "nibl_site_url": nibl_site_url
                },
			    function () {
			        $("#np_action_display_message").text("To put this action in a blog just select if from the list of links on the blog post page.");
                    $('#np_action_title').val('');
                    $('#np_action_description').val('');
                    $('#np_action_price').val('');
                    $('#np_action_inherit').attr('checked', false);
			    }
		    );
        }

        return false;
    }

    function np_create_item() {
        nibl_item_title = $('#np_item_title').val();
        nibl_item_description = $('#np_item_description').val();
        nibl_item_price = $('#np_item_price').val();
        nibl_item_sku = $('#np_item_sku').val();
        nibl_item_tax_exempt = $('#np_item_tax_exempt').is(':checked');
        nibl_site_url = $('#np_item_site_url').val();
        if ($('#np_action_inherit').is(':checked')) {
            nibl_action_inherit = "Inherit";
        }
        else {
            nibl_action_inherit = "DoNotInherit";
        }

        url = '/wp-admin/admin-ajax.php';
        if (nibl_item_title == "") {
            $("#np_item_display_message").text("You must have a title to create an item.");
        }
        else if (nibl_item_description == "") {
            $("#np_item_display_message").text("You must have a description to create an item.");
        }
        else if (nibl_item_price == "") {
            $("#np_item_display_message").text("You must have a price to create an item.");
        }
        else {
            $.post(ajaxurl,
                {
                    "action": "np_create-item",
                    "nibl_item_title": nibl_item_title,
                    "nibl_item_description": nibl_item_description,
                    "nibl_item_price": nibl_item_price,
                    "nibl_item_sku": nibl_item_sku,
                    "nibl_item_inherit": nibl_item_inherit,
                    "nibl_item_tax_exempt": nibl_item_tax_exempt,
                    "nibl_site_url": nibl_site_url
                },
			    function () {
			        $("#np_item_display_message").text("To put this item in a blog just select if from the list of links on the blog post page.");
			        $('#np_item_title').val('');
			        $('#np_item_description').val('');
			        $('#np_item_price').val('');
			        $('#np_item_sku').val('');
			        $('#np_item_tax_exempt').attr('checked', false);
			        $('#np_action_inherit').attr('checked', false);
			    }
		    );
        }

        return false;
    }

    function np_config_toggle_setting_visibility() {
        $('#np_config_row').slideToggle('fast');
        np_config_is_user_logged_in();
    }

    function np_config_active() {
        var active_Val = $('#np_config-active').val();
        var permalinks = $('#np_permalink-setting').val();
        if (permalinks == "") {
            alert("nibl cannot be enabled while Permalinks are disabled");
            $('#np_config-active').val(0);
            $.post(ajaxurl,
            {
                "action": "np_config-active",
                "np_config-nibl_active": 0
            });
        }
        else {
            $.post(ajaxurl,
            {
                "action": "np_config-active",
                "np_config-nibl_active": active_Val
            },
			function () {
			    if (active_Val == 0) {
			        alert("nibl Deactivated");
			    }
			    else if (active_Val == 1) {
			        alert("nibl Activated");
			    }
			}
		);
        }
    }

    function np_config_update() {

        nibl_URL = $('#np_config-nibl-url').val();
        nibl_key = $('#np_config-nibl-key').val();
        nibl_app_secret = $('#np_config-nibl-app-secret').val();
        nibl_verify_ssl = $('#np_config-nibl-verify-ssl').is(':checked');

        if (nibl_URL.lastIndexOf("/") == nibl_URL.length - 1) {
            nibl_URL = nibl_URL.substring(0, nibl_URL.length - 1);
            $('#np_config-nibl-url').val(nibl_URL);
        }

        if (nibl_URL.indexOf("http") == -1) {
            nibl_URL = "http://" + nibl_URL;
            $('#np_config-nibl-url').val(nibl_URL);
        }

        url = '/wp-admin/admin-ajax.php';
        $.post(ajaxurl, {
            "action": "np_config-update",
            "np_config-nibl_URL": nibl_URL,
            "np_config-nibl_key": nibl_key,
            "np_config-nibl_app_secret": nibl_app_secret,
            "np_config-nibl_verify_ssl": nibl_verify_ssl
        });

        return false;
    }
});