<script>
    var vm = new Vue({
        el: "#vue-app",
        delimiters: ['[[', ']]'],
        data: {
            user: {
              email: "",
              image: "",
              name: "",
              video: ""
            },
            records: [],
            shareUrl: ""
        },
        mounted: function (){
            this.getProfile();
        },
        methods: {
          getProfile() {
              const vue = this;
              const userId = location.pathname.split("/").slice(-2, -1)[0];
              let url = "{{route('api.tryingUserProfile', ':userId')}}"
              url = url.replace(":userId", userId);

              axios.get(url)
              .then(function(response){
                  if (response && response.status === 200) {
                    const data = response.data.data;
                    let video;
                    if(data.user.video) {
                      video = `{{ storage_asset('_video') }}`;
                      video = video.replace('_video', data.user.video);
                    } else video = null

                    console.log(video)

                    vue.records = [{
                      name: "First Name",
                      text: data.records[0].first_name,
                      url: data.records[0].first_name_full_url,
                    }, {
                      name: "Last Name",
                      text: data.records[0].last_name,
                      url: data.records[0].last_name_full_url,
                    }];
                    vue.user = {
                      email: data.user.email,
                      image: data.user.image,
                      name: data.user.name,
                      video
                    }
                  }
              }).catch(function (err) {
                  console.log(err);
              });
          },
          detectMobile() {
            const regex = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone/i
            return regex.test(navigator.userAgent);
          },
          copyFormatted(id, imgClass) {
            var container = document.createElement('div')

            var htmlEditor = CodeMirror.fromTextArea(document.getElementById(id), {
              mode: 'text/html'
            })

            container.innerHTML = htmlEditor.getValue();
            container.style.position = 'fixed'
            container.style.pointerEvents = 'none'
            container.style.opacity = 0
            if(this.detectMobile()) {
                container.querySelector("img").style.maxWidth = "65%"
                container.querySelector("img").style.maxHeight = "65%"
            }

            document.body.appendChild(container)

            window.getSelection().removeAllRanges()

            var range = document.createRange()
            range.selectNode(container)
            window.getSelection().addRange(range)

            document.execCommand('copy')
            document.querySelector(`.${imgClass}`).style.mixBlendMode = "overlay";
            setTimeout(() => {
              document.querySelector(`.${imgClass}`).style.mixBlendMode = "normal";
            }, 1300)

            document.body.removeChild(container)
          },
          copyShareURL(text, e) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            e.currentTarget.title="{{__('global.copied')}}";
            $(e.currentTarget).tooltip("show");
          }
        }
    });
</script>
