$(document).ready(function () {
    let maskLength = 20;
    var maskList = $.masksSort($.masksLoad("/local/front/files/js/lib/inputmask.multi/data/phone-codes.json"), ['#'], /[0-9]|#/, "mask");
    var maskOpts = {
        inputmask: {
            definitions: {
                '#': {
                    validator: "[0-9]",
                    cardinality: 1
                }
            },
            //clearIncomplete: true,
            showMaskOnHover: false,
            autoUnmask: true
        },
        match: /[0-9]/,
        replace: '#',
        list: maskList,
        listKey: "mask",
        onMaskChange: function(maskObj, completed) {
            if (completed) {
                var hint = maskObj.cc;
                let flag = 'flag-' + hint;
                $('.modal-flag').attr('class', 'modal-flag').addClass(flag);
                maskLength = maskObj.mask.replace(/[^\d#]/g, '').length;
            } else {
                $('.modal-flag').attr('class', 'modal-flag');
            }
            $(this).attr("placeholder", $(this).inputmask("getemptymask"));
        }
    };

    $('input[type="tel"]').inputmasks(maskOpts);
    $('input[type="tel"]').on('keyup', function () {
        if ($(this).val().length === maskLength) {
            $(this).closest('.modal-body').find('button').removeAttr('disabled');
        } else {
            $(this).closest('.modal-body').find('button').attr('disabled', 'disabled');
        }
    });

    $('input[data-code="PHONE"]').inputmasks(maskOpts);
});