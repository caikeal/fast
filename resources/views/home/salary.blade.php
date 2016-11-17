@extends('home.app')
@section('head')
    <title>薪资查询</title>
@endsection
@section('moreCss')
    <link rel="stylesheet" href="{{env('APP_URL')}}/css/home/selectTime.css">
    <style>
        body {
            background-color: #fff;
        }

        .meta-info-list {
            font-size: 13px;
            color: #6e6e6e;
        }
        .meta-info-list ul>li{
            margin-top: 20px;
            border-top: 1px solid #e2e2e2;
        }
        .meta-info-list .meta-head {
            padding: 10px 0;
            margin: 0 1rem;
        }
    </style>
@endsection
@section('content')
    <!-- 所属公司logo -->
    <div class="am-g am-center am-u-sm-centered">
        <img class="logo am-u-sm-centered" src="{{env('APP_URL')}}/images/logo.png">
    </div>
    <form class="am-form am-form-horizontal" style="margin-bottom: 20px;margin-top: 20px;margin-right: 1rem;">
        <div class="am-form-group">
            <div class="am-u-sm-8">
                <input type="month" class="am-text-center am-form-field am-round kbtn"
                       placeholder="查询月份" value="{{ $now }}" id="beginTime"/>
            </div>
            <button class="am-btn am-btn-primary am-u-sm-4 am-round" id="searchSalary" type="button"><span
                        class="am-icon-search"></span>搜索
            </button>
            <div id="datePlugin"></div>
        </div>
    </form>
    <!-- collapse -->
    <section data-am-widget="accordion" id="accordion" class="am-accordion am-accordion-gapped am-no-layout"
             data-am-accordion="{  }">

    </section>

    <section class="meta-info-list">
        <ul>
        </ul>
    </section>
@endsection
@section('moreScript')
    <script src="{{env('APP_URL')}}/js/home/formatDate.js"></script>
    <script src="{{env('APP_URL')}}/js/home/iscroll.js"></script>
    <script>
        //
        window.onload = function () {
            //初始化
            var str = "{{ $now }}";
            var d = new Date();
            if (d.getMonth() < 9) {
                var str = d.getFullYear() + "0" + (d.getMonth() + 1);
            } else {
                var str = d.getFullYear() + "" + (d.getMonth() + 1);
            }

            var url = "{{url('salary/details')}}";
            $.post(url, {
                type: 1,
                time: str,
                _token: $("meta[name=csrf-token]").attr("content")
            }, function (res) {
                if (res.status == 1) {
                    var data = res.data;
                    var meta = res.meta;
                    createToggleSection(data, "accordion");

                    if (meta instanceof Array) {
                        $(".meta-info-list").hide();
                    } else {
                        $(".meta-info-list").show();
                    }
                    if ((meta instanceof Object) && meta.hasOwnProperty('balance')){
                        createMetaBalance(meta.balance);
                    }
                } else {
                    alert("没有该月工资记录！");
                }
            }, 'json');
        };

        $("#searchSalary").click(function () {
            var _this = this;
            var url = "{{url('salary/details')}}";
            var salaryTime = format($("#beginTime").val());
            $.post(url, {
                type: 1,
                time: salaryTime,
                _token: $("meta[name=csrf-token]").attr("content")
            }, function (res) {
                if (res.status == 1) {
                    var data = res.data;
                    var meta = res.meta;
                    $("#accordion>dl").remove();
                    createToggleSection(data, "accordion");

                    $(".meta-info-list ul>li").remove();
                    if (meta instanceof Array) {
                        $(".meta-info-list").hide();
                    } else {
                        $(".meta-info-list").show();
                    }
                    if ((meta instanceof Object) && meta.hasOwnProperty('balance')){
                        createMetaBalance(meta.balance);
                    }
                } else {
                    $("#accordion>dl").remove();
                    $(".meta-info-list ul>li").remove();
                    alert("没有该月工资记录！");
                }
            }, 'json');
        });

        //添加点击绑定事件
        var bindingAccordionToggle = function (insertNode) {
            $("#"+insertNode).on("click", ".am-accordion-title", function () {
                if (!$(this).parent().hasClass('am-active')) {
                    var hasIt = true;
                }

                if (hasIt) {
                    $(this).parent().addClass('am-active');
                } else {
                    $(this).parent().removeClass('am-active');
                }

                $(this).siblings().collapse('toggle');
            });
        }

        var createToggleSection = function (data, insertNode) {
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
                }
                obj = document.getElementById(insertNode);
                obj.appendChild(dl);
            }
            //加样式 am-table am-table-striped am-table-hover
            $("#"+insertNode+" dl").addClass("am-accordion-item");
            $("#"+insertNode+" dl:first").addClass("am-active");
            $("#"+insertNode+" dt").addClass("am-accordion-title");
            $("#"+insertNode+" dd").addClass("am-accordion-bd");
            $("#"+insertNode+" dd").addClass("am-collapse");
            $("#"+insertNode+" dd:first").addClass("am-in");
            $("#"+insertNode+" table").addClass("am-table");
            $("#"+insertNode+" table").addClass("am-table-striped");
            $("#"+insertNode+" table").addClass("am-table-hover");
            bindingAccordionToggle(insertNode);
        }
        var createMetaBalance = function (data) {
            var insertPoint = $(".meta-info-list ul");
            var tpl = null;
            for (var i=0; i<data.length; i++) {
                var li = document.createElement('li');
                var divHead = document.createElement('div');
                $(divHead).addClass("meta-head");
                var span = document.createElement('span');
                var nodeTitle = document.createTextNode(data[i].date+"补充项");
                span.appendChild(nodeTitle);
                divHead.appendChild(span);
                li.appendChild(divHead);

                var divBody = document.createElement('div');
                $(divBody).addClass("mete-body");
                var section = document.createElement('section');
                $(section).addClass("am-accordion am-accordion-gapped am-no-layout");
                $(section).attr('id', 'accordion'+(i+1));
                $(section).attr('data-am-widget', 'accordion');
                $(section).attr('data-am-accordion', '{}');
                divBody.appendChild(section);
                li.appendChild(divBody);
                insertPoint.append(li);
                createToggleSection(data[i].details, 'accordion'+(i+1));
            }
        }
    </script>
@endsection