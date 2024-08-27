<script>
    $('.smart-toggle').on('change', function () {
        let api_url = $(this).attr('data-value');
        let bodyFormData = new FormData();
        bodyFormData.append('_token', "{{csrf_token()}}");
        let element = $(this);
        swal(
            {
                title:"{{__('admin.are_you_sure')}}",
                text:"{{__('admin.change_status')}}",
                type:"warning",
                showCancelButton:!0,
                confirmButtonClass:"btn-warning",
                confirmButtonText:"{{__('admin.do_it')}}",
                cancelButtonText: "{{__('admin.cancel')}}",
                closeOnConfirm: false
            }, function (confirm) {
                if (confirm){
                    axios.post(api_url, bodyFormData).then(response => {
                        if (response && response.status === 200){
                            swal(
                                {
                                    title:"{{__('admin.success')}}",
                                    text:response.data.message,
                                    type:"success",
                                    confirmButtonClass:"btn-success",
                                })
                        }
                        else {
                            element.next($('.toggle-group')).click();
                            swal(
                                {
                                    title:"{{__('admin.oops')}}",
                                    text:"{{__('admin.server_error')}}",
                                    type:"error",
                                    confirmButtonClass:"btn-danger",
                                });
                        }
                    });
                }
                else {
                    element.next($('.toggle-group')).click();
                }
            });
        return false;
    });
</script>
