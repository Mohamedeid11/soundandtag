<script src="{{asset('plugins/morris/morris.min.js')}}"></script>
<script src="{{asset('plugins/raphael/raphael-min.js')}}"></script>
<script>
    !(function (e) {
        "use strict";
        var a = function () {};
        (a.prototype.createAreaChart = function (e, a, r, o, t, i, l, n) {
            Morris.Area({ element: e, pointSize: 3, lineWidth: 1, data: o, xkey: t, ykeys: i, labels: l, resize: !0, gridLineColor: "#3d4956", hideHover: "auto", lineColors: n, parseTime: false,});
        }),
            (a.prototype.createBarChart = function (e, a, r, o, t, i) {
                Morris.Bar({ element: e, data: a, xkey: r, ykeys: o, labels: t, gridLineColor: "#3d4956", barSizeRatio: 0.4, resize: !0, hideHover: "auto", barColors: i });
            }),
            (a.prototype.createDonutChart = function (e, a, r) {
                Morris.Donut({ element: e, data: a, resize: !0, colors: r, backgroundColor: "#2f3e47", labelColor: "#fff" });
            }),
            (a.prototype.init = function () {
                const data = JSON.parse('{!! $hits !!}');
                this.createAreaChart(
                    "morris-area-example",
                    0,
                    0,
                    data,
                    "y",
                    ["a", "b"],
                    ["{{__('admin.this_year')}}", "{{__('admin.last_year')}}"],
                    ["#00a3ff", "#04a2b3"]
                );
                // this.createBarChart(
                //     "morris-bar-example",
                //     [
                //         { y: "2009", a: 100, b: 90 },
                //         { y: "2010", a: 75, b: 65 },
                //         { y: "2011", a: 50, b: 40 },
                //         { y: "2012", a: 75, b: 65 },
                //         { y: "2013", a: 50, b: 40 },
                //         { y: "2014", a: 75, b: 65 },
                //         { y: "2015", a: 100, b: 90 },
                //         { y: "2016", a: 90, b: 75 },
                //     ],
                //     "y",
                //     ["a", "b"],
                //     ["Series A", "Series B"],
                //     ["#04a2b3", "#00a3ff"]
                // );
                // this.createDonutChart(
                //     "morris-donut-example",
                //     [
                //         { label: "Download Sales", value: 12 },
                //         { label: "In-Store Sales", value: 30 },
                //         { label: "Mail-Order Sales", value: 20 },
                //     ],
                //     ["#dcdcdc", "#e66060", "#04a2b3"]
                // );
            }),
            (e.Dashboard = new a()),
            (e.Dashboard.Constructor = a);
    })(window.jQuery),
        (function (e) {
            "use strict";
            window.jQuery.Dashboard.init();
        })();

</script>
