<script>
    $(document).on('change', 'div.checkbox input[type=checkbox]:visible', function () {
        let all = $('div.checkbox input[type=checkbox]:visible');
        if ($(this).attr('data-value') == '-1'){
            all.prop('checked', $(this).prop('checked'));
        }
        else {
            if (!$(this).prop('checked')){
                $('#checkbox--1').prop('checked', false);
            }
            let all_length = all.length - 1;
            let checked_length = $('div.checkbox input[type=checkbox]:visible:checked').length;
            if (all_length == checked_length){
                all.prop('checked', true);
            }
        }
        let checked = $('div.checkbox input[type=checkbox]:visible:checked');
        let checked_vals = [];
        checked.each(function (){
            let val = $(this).attr('data-value');
            if (parseInt(val) !== -1) {checked_vals.push(val);}
        });
        $('input[name=bulk_delete]').val(JSON.stringify(checked_vals));
    });
    $(document).on('submit', '.bulk-delete-form,.delete-form', function () {
        event.preventDefault();
        let form = $(this);
        swal(
            {
                title:"{{__('admin.are_you_sure')}}",
                text:"{{__('admin.action_undone')}}",
                type:"error",
                showCancelButton:!0,
                confirmButtonClass:"btn-danger",
                confirmButtonText:"{{__('admin.do_it')}}",
                cancelButtonText: "{{__('admin.cancel')}}",
                closeOnConfirm: false
            }, function (confirm) {
                if (confirm) {
                    form.submit();
                }
            });
    });
</script>
