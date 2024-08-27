<script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
<script>
    let token = "{{Session::get('web_auth_token')}}";
    const config = {
        headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
    };
    axios.defaults.headers.common = config.headers;
    axios.defaults.baseURL = "{{config('app.url')}}";
    axios.defaults.withCredentials = true;
    const UNAUTHORIZED = 401;
    axios.interceptors.response.use(
        response => response,
        error => {
            const {status} = error.response;
            if (status === UNAUTHORIZED) {
                swal(
                    {
                        title:"{{__('global.session_expired')}}",
                        text:"{{__('global.refresh_page')}}",
                        type:"error",
                        confirmButtonClass:"primary-btn",
                    }, function () {
                        location.href="{{route('web.get_assure_login')}}";
                    });
                return Promise.reject(error);
            }
            else {
                return Promise.reject(error);
            }
        }
    );
</script>
