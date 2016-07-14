@extends('home.app')
@section('head')
    <title>社保进度查询</title>
@endsection
@section('moreCss')
    <link rel="stylesheet" href="{{env('APP_URL')}}/css/home/selectTime.css">
    <style>
        body {
            background-color: #fff;
        }
    </style>
@endsection
@section('back')
    <a href="javascript :history.back(-1);" class="am-icon-chevron-left" style="float:left; color: #fff;" id="btn-back"></a>
    @endsection
    @section('content')
            <!-- 所属公司logo -->
    <div class="am-g am-center am-u-sm-centered">
        <img class="logo am-u-sm-centered" src="{{env('APP_URL')}}/images/logo.png">
    </div>
    <!-- collapse -->
    <section data-am-widget="accordion" id="accordion" class="am-accordion am-accordion-gapped am-no-layout"
             data-am-accordion="{  }">

    </section>
@endsection
@section('moreScript')
    <script src="{{env('APP_URL')}}/js/home/iscroll.js"></script>
    <script>
        window.onload = function () {
            var url = "{{url('insurance/details')}}";
            $.post(url, {
                type: 4,
                id: {{ $id }},
                _token: $("meta[name=csrf-token]").attr("content")
            }, function (res) {
                if (res.status==1) {
                    var data=res.data;
                    var categorylen = data.length;
                    for (var i = 0; i < categorylen; i++) {
                        // console.log(data[i]);
                        var dl = document.createElement("dl");
                        var dt = document.createElement("dt");
                        var dd = document.createElement("dd");
                        var table = document.createElement("table");
                        var tbody = document.createElement("tbody");
                        var node = document.createTextNode(data[i].category);
                        dl.appendChild(dt);
                        dl.appendChild(dd);
                        dt.appendChild(node);
                        dd.appendChild(table);
                        table.appendChild(tbody);
                        var detailslen = data[i].details.length;
                        for (var j = 0; j < detailslen; j++) {
                            // console.log(data[i].details.length);
                            var tr = document.createElement("tr");
                            var th = document.createElement("th");
                            var td = document.createElement("td");
                            var name = document.createTextNode(data[i].details[j].name);
                            var v = document.createTextNode(data[i].details[j].v);
                            tr.appendChild(th);
                            tr.appendChild(td);
                            th.appendChild(name);
                            td.appendChild(v);
                            tbody.appendChild(tr);
                            obj = document.getElementById("accordion");
                            obj.appendChild(dl);
                        }

                    }
                    //加样式 am-table am-table-striped am-table-hover
                    $("dl").addClass("am-accordion-item");
                    $("dl:first").addClass("am-active");
                    $("dt").addClass("am-accordion-title");
                    $("dd").addClass("am-accordion-bd");
                    $("dd").addClass("am-collapse");
                    $("dd:first").addClass("am-in");
                    $("table").addClass("am-table");
                    $("table").addClass("am-table-striped");
                    $("table").addClass("am-table-hover");
                } else {
                    alert("没有该月社保办理进度记录！");
                }
            }, 'json');
            //添加点击绑定事件
        };

        $("#accordion").on("click", ".am-accordion-title", function () {
            if (!$(this).parent().hasClass('am-active')){
                var hasIt = true;
            }

            if (hasIt){
                $(this).parent().addClass('am-active');
            }else{
                $(this).parent().removeClass('am-active');
            }

            $(this).siblings().collapse('toggle');
        });
    </script>
@endsection