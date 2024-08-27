<script>
    $('.ajax-form').on('submit', function () {
       event.preventDefault();
        let api_url = $(this).attr('action');
        const form = this;
        const data = $(form).serialize()
        axios.put(api_url,data
        ).then(function (response) {
            if (response.data.status == 1){
                $(form).find($('div.right span')).html(response.data.message);
                $(form).find($('div.right')).removeClass('d-none');
                $(form).find($('div.wrong')).addClass('d-none');
            }
            else {
                $(form).find($('div.right span')).html("{{__('admin.server_error')}}");
                $(form).find($('div.wrong')).removeClass('d-none');
                $(form).find($('div.right')).addClass('d-none');
            }
        }).catch(function (err){
            $(form).find($('div.wrong span')).html("{{__('admin.server_error')}}");
            $(form).find($('div.wrong')).removeClass('d-none');
            $(form).find($('div.right')).addClass('d-none');
        });

    });
</script>
