$(document).ready(function () {
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
            } else {
                $('.modal-flag').attr('class', 'modal-flag');
            }
            $(this).attr("placeholder", $(this).inputmask("getemptymask"));
        }
    };

    $('input[type="tel"]').inputmasks(maskOpts);
});