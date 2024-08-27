<script>
var vm = new Vue({
  el: "#vue-app",
  delimiters: ['[[', ']]'],
  data: {
    account_type: "personal",
    plans: [],
    selected_plan: ""
  },
  mounted: function() {
    this.getPlans();
    // Toggle password input
    const showPasswordBtns = document.querySelectorAll(".show-password");
    showPasswordBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const inputElm = btn.parentElement.previousElementSibling;
            if(inputElm.type === "password") inputElm.type = "text";
            else inputElm.type = "password" 
        })
    })
  },
  watch: {
    account_type: function() {
      this.changeAccountType();
    }
  },
  methods: {
    checkForParams() {
      const urlParams = new URLSearchParams(location.search);
      const selectedPlan = urlParams.get("plan");
      const accountTypeParam = urlParams.get("account_type");

      return {accountTypeParam, selectedPlan}
    },
    checkLocalStorage() {
      // Check for returned data from backend
      if({!! json_encode(inp_value(null, 'account_type') == 'corporate' || old('account_type') == 'corporate') !!}) {
        this.account_type = "corporate"
        return {account_type: "corporate", selectedPlan: {!! json_encode(inp_value(null, 'plan')) !!}}
      } else if ({!! json_encode(inp_value(null, 'account_type') == 'personal' || old('account_type') == 'personal') !!}) {
        this.account_type = "personal"
        return {account_type: "personal", selectedPlan: {!! json_encode(inp_value(null, 'plan')) !!}}
      }

      document.getElementById("records").value = localStorage.getItem('soundAndTagRecords') || '{}';
      const accountTypeStorage = localStorage.getItem('soundAndTagAccountType');

      const {accountTypeParam, selectedPlan} = this.checkForParams();

      const account_type = accountTypeParam || accountTypeStorage || this.account_type;
      this.account_type = account_type;

      return {account_type, selectedPlan};
    },
    getPlans() {
      const vue = this;
      // if ((account_type === "personal" && personalPlans.length) ||
      //   (account_type === "corporate" && corporatePlans.length))
      //   return

      let {account_type, selectedPlan} = this.checkLocalStorage();

      let url = `{{route('api.getPlansByType', ':account_type')}}`;
      url = url.replace(":account_type", account_type);

      axios.get(url)
        .then(function(response) {
          vue.plans = response.data;
          // Get selected plan_id
          vue.selected_plan = selectedPlan ?? ""
        }).catch(function(err) {
          console.log(err);
        });
    },
    changeAccountType() {
      const vue = this;
      this.selected_plan = ""

      let url = `{{route('api.getPlansByType', ':account_type')}}`;
      url = url.replace(":account_type", this.account_type);

      axios.get(url)
        .then(function(response) {
          console.log(response.data)
          vue.plans = response.data;
        }).catch(function(err) {
          console.log(err);
        });
    }
  }
});
</script>