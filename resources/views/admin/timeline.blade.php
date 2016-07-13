@extends('admin.app')
@section('moreCss')
    <link rel="stylesheet" href="{{env("APP_URL")}}/css/admin/webuploader.css">
@endsection
@section('content')
    <div class="padding-md" id="type" data-type="1">
        <h3 class="header-text">
            工资上传
            <span class="sub-header">
                {{--19 Updates--}}
            </span>
        </h3>

        <div class="row">
            <div class="col-md-10">
                <div class="timeline-wrapper clearfix">
                    <div class="timeline-year">
                        {{$now->year.".".$now->month}}
                    </div>
                    @foreach($tasks as $k=>$task)
                        @if($task->deal_time<$nextMonthTime)
                            <div class="timeline-row alt">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                    </div><!-- ./timeline-icon -->
                                    <div class="timeline-item-inner">
                                        <div class="timeline-body">
                                            <div class="timeline-avatar">
                                                <img src="{{env("APP_URL")}}/{{$task->company->poster}}" alt="" class="img-circle">
                                            </div>
                                            <div class="timeline-content">
                                                <div class="font-16 font-semi-bold"><a
                                                            href="#">{{$task->company->name}}</a></div>
                                                <small class="block text-muted m-bottom-xs">{{date("Y-m-d",$task->deal_time)}}</small>
                                            </div>
                                            <div class="timeline-ctrl">
                                                @if($task->status==0)
                                                    <div id="uploader{{$k}}" class="upload-ctrl"
                                                         data-task="{{$task->id}}">
                                                        <!--用来存放文件信息-->
                                                        <div id="thelist{{$k}}" class="uploader-list"></div>
                                                        <div class="btns">
                                                            <div id="picker{{$k}}">选择文件</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="webuploader-container upload-ctrl">
                                                        <div class="webuploader-pick webuploader-pick-disable">
                                                            <i class="fa fa-cloud-upload"></i> 上传
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-5 col-md-5 col-sm-5 timeline-select">
                                                    <select name="c{{$task->company_id}}" class="form-control">
                                                        @foreach($task->salaryModels()->where("type",1)->get() as $salaryModel)
                                                            <option value="{{$salaryModel->id}}">{{$salaryModel->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <a class="btn btn-success timeline-btn download-base"
                                                   data-company="c{{$task->company_id}}">下载模版
                                                </a>

                                                <new-base-btn :company-id={{ $task->company_id }} @click="initModal"></new-base-btn>
                                            </div>
                                        </div><!-- ./timeline-body -->
                                    </div><!-- ./timeline-item-inner -->
                                </div><!-- ./timeline-item -->
                            </div><!-- ./timeline-row -->
                        @elseif($task->deal_time>=$nextMonthTime)
                            <div class="timeline-year bg-purple">
                                {{$next->year.".".$next->month}}
                            </div>
                        @endif
                        @if($task->deal_time>=$nextMonthTime)
                            <div class="timeline-row alt">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                    </div><!-- ./timeline-icon -->
                                    <div class="timeline-item-inner">
                                        <div class="timeline-body">
                                            <div class="timeline-avatar">
                                                <img src="{{env("APP_URL")}}/{{$task->company->poster}}" alt="" class="img-circle">
                                            </div>
                                            <div class="timeline-content">
                                                <div class="font-16 font-semi-bold"><a
                                                            href="#">{{$task->company->name}}</a></div>
                                                <small class="block text-muted m-bottom-xs">{{date("Y-m-d",$task->deal_time)}}</small>
                                            </div>
                                            <div class="timeline-ctrl">
                                                @if($task->status==0)
                                                    <div id="uploader{{$k}}" class="upload-ctrl"
                                                         data-task="{{$task->id}}">
                                                        <!--用来存放文件信息-->
                                                        <div id="thelist{{$k}}" class="uploader-list"></div>
                                                        <div class="btns">
                                                            <div id="picker{{$k}}">选择文件</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="webuploader-container upload-ctrl">
                                                        <div class="webuploader-pick webuploader-pick-disable">
                                                            <i class="fa fa-cloud-upload"></i> 上传
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-5 col-md-5 col-sm-5 timeline-select">
                                                    <select name="c{{$task->company_id}}" class="form-control">
                                                        @foreach($task->salaryModels()->where("type",1)->get() as $salaryModel2)
                                                            <option value="{{$salaryModel2->id}}">{{$salaryModel2->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="btn btn-success timeline-btn download-base"
                                                     data-company="c{{$task->company_id}}">下载模版
                                                </div>

                                                <new-base-btn :company-id={{ $task->company_id }} @click="initModal"></new-base-btn>
                                            </div>
                                        </div>
                                        <!-- ./timeline-body -->
                                    </div>
                                    <!-- ./timeline-item-inner -->
                                </div>
                                <!-- ./timeline-item -->
                            </div>
                            <!-- ./timeline-row -->
                        @endif
                    @endforeach
                </div>
                <!-- ./timeline-wrapper -->
            </div>
            <!-- ./col -->
        </div>
        <!-- ./row -->

        <!-- timeline-base-template -->
        <template id="timeline-btn-template"  style="display: none">
            <div class="timeline-new" @click="notify">
                <i class="fa fa-plus-circle"></i> 新建模版
            </div>
        </template>
        <!-- ./timeline-base-template -->

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            新建模板
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row creatCategroy">
                                <div class="col-md-12">
                                    <vue-input input-name="大类选项创建" :new-category.sync="bigCategorys" type="1"></vue-input>
                                </div>
                                <div class="clearfix" style="margin: 10px 0;"></div>
                                <div class="col-md-12">
                                    <vue-input input-name="小类选项创建" :new-category.sync="smallCategorys" type="2"></vue-input>
                                </div>
                            </div>
                            <div class="clearfix" style="margin: 10px 0;"></div>
                            <div class="row">
                                <div :class="['col-md-12',{'has-error':errors.titleErrors.isInvalid}]">
                                    <input type="text" name="base-title" v-model="baseTitle | nospace" class="form-control" placeholder="模版标题">
                                    <span class="help-block">@{{ errors.titleErrors.msg }}</span>
                                </div>
                            </div>

                            <br>

                            <div>
                                    <span class="help-block" style="color: red">
                                        <span style="font-weight: 800">*</span>
                                        模版中自动包含
                                        <span style="font-weight: 800">'姓名'、'身份证'、'发薪日'</span>，
                                        <span style="font-weight: 800"> 请勿</span>
                                        再次
                                        <span style="font-weight: 800">创建</span>
                                    </span>
                            </div>

                            <dynamic-selectors :dynamic-selectors.sync="selectors"
                                               :big-categorys="bigCategorys"
                                               :small-categorys="smallCategorys"
                                               :dynamic-errors.sync="errors.dynamicErrors">
                            </dynamic-selectors>
                        </div>

                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-info"
                                    data-target="#goModal" data-toggle="modal" @click="getRehearsal">预览模版
                            </button>
                            <button type="submit" class="btn btn-primary" @click="saveBase">
                            保存模板
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- /.modal -->

        <div class="modal fade" id="goModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">预览@{{ baseTitle }}模版</h4>
                    </div>
                    <div class="modal-body">
                        <section class="accordion-gapped">
                            <dl v-for="rehearsalItem in rehearsal" :class="[rehearsalItem.show?'active':'']">
                                <dt class="accordion-title" @click="toggleCats($index)">
                                @{{ rehearsalItem.bigSelect }}
                                </dt>
                                <dd v-show="rehearsalItem.show" transition="expand">
                                    <table class="table table-striped table-border">
                                        <tbody>
                                        <tr v-for="smallRehearsal in rehearsalItem.smallSelect"  track-by="$index">
                                            <th scope="row">@{{ smallRehearsal }}</th>
                                            <td>具体内容</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </dd>
                            </dl>
                        </section>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- /.Modal -->

        <!-- template -->
        <script type="text/x-template" id="dynamic-selectors-template">
            <div class="dynamic-table">
                <selector v-for="selector in dynamicSelectors"
                          :big-categorys="bigCategorys"
                          :small-categorys="smallCategorys"
                          :big-select.sync="selector.bigSelect"
                          :small-select.sync="selector.smallSelect"
                          :big-errors.sync="dynamicErrors[$index]['bigErrors']"
                          :small-errors.sync="dynamicErrors[$index]['smallErrors']">
                    <a class="text-muted delete-group" @click.prevent="deleteSelector(selector)">
                        <i class="fa fa-minus-circle"></i> 删除分组
                    </a>
                </selector>
                <div class="row">
                    <div class="col-md-12" style="padding-bottom: 30px;">
                        <h4>
                            <a id="addBig" class="add-group" @click.prevent="addSelector">
                                <i class="fa fa-plus-circle"></i> 新增分组
                            </a>
                        </h4>
                    </div>
                </div>
            </div>
        </script>

        <script type="text/x-template" id="selectors-template">
            <div class="addCategory well">
                <div class="category-section">
                    <span class="text-muted category-title">选择大类:</span>
                    <div class="category-content big-category-content">
                        <div :class="['big-selector', {'has-error': bigErrors.isInvalid}]">
                            <big-selector :big-select.sync="bigSelect" :big-categorys="bigCategorys"></big-selector>
                        </div>
                        <a class="text-muted add-small-category" @click="addSmallSelector">
                        <i class="fa fa-plus-circle"></i> 新增小类
                        </a>
                        <div style="float:right;">
                            <slot></slot>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="category-section">
                    <span class="text-muted category-title">选择小类:</span>
                    <div class="category-content">
                        <div :class="['small-selector', {'has-error':smallErrors[$index]['isInvalid']}]" v-for="small in smallSelect" track-by="$index">
                            <small-selector :small.sync="small" :small-categorys="smallCategorys">
                                <div class="delete-small" @click="deleteSmallSelector(small)">
                                <i class="fa fa-times close-cirle"></i>
                        </div>
                        </small-selector>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            </div>
        </script>

        <script type="text/x-template" id="big-selectors-template">
            <vue-select class="vue-select3" name="select3"
                        :options="bigCategorys" :model.sync="bigSelect"
                        :searchable="true" language="zh-CN" drop-node="body">
            </vue-select>
        </script>

        <script type="text/x-template" id="small-selectors-template">
            <vue-select class="vue-select3" name="select3"
                        :options="smallCategorys" :model.sync="small"
                        :searchable="true" language="zh-CN" drop-node="body">
            </vue-select>

            <slot></slot>
        </script>

        <script type="text/x-template" id="vue-input-template">
            <div class="input-group open">
                <input type="text" v-model="newText | nospace" v-el:need-input @input="findList(newText)" @blur="clearSearch" class="form-control">
                        <span class="input-group-btn">
                            <button @click="addToTexts" class="btn btn-primary">@{{ inputName }}</button>
                        </span>
                <ul class="dropdown-menu" aria-labelledby="dLabel" v-if="searchArr.length!=0">
                    <li>
                        <a herf="javascript:void(0);" style="color:#f8547a">存在如下类似：</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li v-for="search in searchArr">
                        <a herf="javascript:void(0);" style="color:#f8547a">@{{ search }}</a>
                    </li>
                </ul>
            </div>
        </script>
        <!-- /template -->

    </div>
    <!-- ./padding-md -->
@endsection
@section('moreScript')
    {{--侧边栏位置锁定--}}
    <script>
        !(function () {
            $(".main-menu .accordion>li").removeClass("active");
            $(".lock-place2").addClass("active");
        })($);
    </script>
    {{--文件上传--}}
    {{--<script src="{{env('APP_URL')}}/js/webuploader-0.1.5/webuploader.min.js"></script>--}}
    <script src="http://7xqxb2.com2.z0.glb.qiniucdn.com/webuploader.min.js"></script>
    <script>
        // 文件接收服务端。
        $.extend(WebUploader.Uploader.options, {
            server: "{{url('admin/salary/upload')}}"
        });
    </script>
    <script src="{{env('APP_URL')}}/js/admin/upload.js"></script>
    <script src="{{env('APP_URL')}}/js/admin/search.js"></script>
    <script type="text/javascript">
        //修复双层modal的bug
        $(document).ready(function(){
            $('#goModal').on('hidden.bs.modal', function (e) {
                $(this).parent().addClass("modal-open");
            })
        });

        //下载
        $(".download-base").on("click", function () {
            var bid = $(this).prev().children("select").val();
            if (bid == null) {
                alert("未选择模版！");
                return false;
            }
            var url = "{{url('admin/salary/download')}}?bid=" + bid;
            window.location.href = "{{url('admin/salary/download')}}?bid=" + bid;
        });

        //vue过滤器
        Vue.filter('nospace', {
            read: function (val) {
                return val.trim();
            },

            write: function(val){
                return val.trim();
            }
        });

        //vue组件
        Vue.component('new-base-btn', {
            template: '#timeline-btn-template',
            props: {
                companyId: {
                    type: Number,
                    required: true
                }
            },
            methods: {
                notify: function(){
                    var _this=this;
                    if(_this.companyId){
                        this.$dispatch('company-id', _this.companyId);
                    }
                    var url = "{{url('admin/salary/category')}}";
                    $.ajax(url, {
                        type: 'get',
                        dataType: 'json',
                        timeout: '120000',
                        data: {type: 1},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        if (data.status) {
                            _this.$dispatch('big-category', data.big);
                            _this.$dispatch('small-category', data.small);
                        } else {
                            alert("网络错误！");
                        }
                    }).fail(function () {
                        alert("网络错误！");
                    });
                }
            }
        });

        Vue.component('vue-input', {
            template: "#vue-input-template",
            props: {
                newCategory: {
                    twoWay: true
                },
                inputName: {
                    required: true
                },
                type: {
                    required: true
                }
            },
            data: function(){
                return {
                    newText: '',
                    searchArr: [],
                    solidInclude: [
                        {id: 0, text: '姓名'},
                        {id: 0, text: '身份证'},
                        {id: 0, text: '发薪日'}
                    ]
                }
            },
            methods: {
                addToTexts: function(){
                    var self = this;
                    if (this.newText == ""){
                        alert("请勿添加空白！");
                        return false;
                    }
                    //判断是否在newCategory中，保证不重复创建
                    if (this.isInNewCat(this.newText)) {
                        alert("已经存在！");
                        return false;
                    }
                    //调用接口,发送post请求给后台
                    url = "{{url('admin/salary/category')}}";
                    $.ajax(url, {
                        type: 'post',
                        dataType: 'json',
                        timeout: '120000',
                        data: {name: self.newText, level: self.type, type: 1},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        if (data.status) {
                            var datum = {id: data.cid, text: self.newText, level: self.type};
                            self.newCategory.push(datum);
                            self.newText = '';
                            alert("添加成功！");
                        } else {
                            alert("网络错误！");
                        }
                    }).fail(function () {
                        alert("网络错误！");
                    }).complete();

                },

                isInNewCat: function(info){
                    var tpl = [];
                    tpl = this.newCategory.concat(this.solidInclude);
                    for(var i = 0; i < tpl.length; i++){
                        if (info == tpl[i].text){
                            return true;
                            break;
                        }
                    }
                    return false;
                },

                findList: function(info){
                    this.searchArr = [];
                    var tpl = [];
                    tpl = this.newCategory.concat(this.solidInclude);
                    if (!info) {
                        return true;
                    }

                    for(var i = 0; i < tpl.length; i++){
                        if (tpl[i].text.indexOf(info) >= 0){
                            this.searchArr.push(tpl[i].text);
                        }
                    }

                    return true;
                },

                clearSearch: function(){
                    this.searchArr = [];

                    return true;
                }
            }
        });

        Vue.component('vue-select', {
            replace: true,
            inherit: false,
            template: "<select class='form-control' v-model='model' :name='name' style='width: 100%;'>"
            +   "<option v-if='optionsType === \"values\"' v-for='val in options' :value='val'>@{{val}}</option>"
            +   "<option v-if='optionsType === \"options\"' v-for='opt in options' :value='opt.id'>@{{opt.text}}</option>"
            +   "<optgroup v-if='optionsType === \"groups\"' v-for='group in options' :label='group.label'>"
            +     "<option v-for='opt in group.options' :value='opt.id'>@{{opt.text}}</option>"
            +   "</optgroup>"
            + "</select>",
            props: {
                options: {
                    type: Array,
                    required: true
                },
                model: {
                    required: true,
                    twoWay: true
                },
                searchable: {
                    type: Boolean,
                    required: false,
                    default: false
                },
                matchValue: {
                    type: Boolean,
                    required: false,
                    default: true
                },
                name: {
                    type: String,
                    required: false,
                    default: ""
                },
                language: {
                    type: String,
                    required: false,
                    default: ""
                },
                theme: {
                    type: String,
                    required: false,
                    default: "bootstrap"
                },
                dropNode: {
                    type: String,
                    required: false,
                    default: "body"
                }
            },
            data: function() {
                return {
                    optionsType: "unknown"
                }
            },
            beforeCompile: function() {
                this.isChanging = false;
                this.control = null;
                this.optionsType = this.getOptionsType();
            },
            watch: {
                "options": function(val, oldVal) {
                    // console.debug("options.change");
                    this.optionsType = this.getOptionsType();
                    var found = this.inOptions(this.model);
                    var newValue = (found ? this.model : null);
                    this.control.removeData("data");  // remove the cached options data
                    // note that setting the model will automatically changed in the "change"
                    // event of the select2 control
                    this.control.val(newValue).trigger("change");
                },
                "model": function(val, oldVal) {
                    //console.debug("model.change");
                    if (! this.isChanging) {
                        this.isChanging = true;
                        this.control.val(val).trigger("change");
                        this.isChanging = false;
                    }
                }
            },
            ready: function() {
                var language = this.language;
                if (language === null || language === "") {
                    if (this.$language) {
                        language = this.$language;
                    } else {
                        language = DEFAULT_LANGUAGE;
                    }
                }
                var args = {
                    theme: this.theme,
                    language: this.getLanguageCode(language),
                    dropdownParent: $(this.dropNode),
                };
                if (! this.searchable) {
                    args.minimumResultsForSearch = Infinity;  // hide the search box
                } else {
                    if (this.matchValue) {
                        args.matcher = matcher;
                    }
                }
                this.control = $(this.$el);
                this.control.select2(args);
                var me = this;
                this.control.on("change", function(e) {
                    //console.debug("control.change");
                    if (! me.isChanging) {
                        me.isChanging = true;
                        me.model = Number(me.control.val());
                        me.$nextTick(function () {
                            me.isChanging = false;
                        });
                    }
                });
            },
            methods: {
                /**
                 * Gets the type of the `options` property of this component.
                 *
                 * The `options` property of this component may have the following types:
                 * - "values": the `options` is an array of strings, e.g., `[value1, value2, value3]`;
                 * - "options": the `options` is an array of options, e.g., `[{text: 'name1', id: 'val1'}, {text: 'name2', id: 'val2'}]`;
                 * - "groups": the `options` is an array of option groups, e.g.,
                 *   `[{label: 'group1', options: [{text: 'name1', id: 'val1'}, {text: 'name2', id: 'val2'}]},
                 *     {label: 'group2', options: [{text: 'name3', id: 'val3'}, {text: 'name4', id: 'val4'}]}]`;
                 *
                 * @param options
                 *    the new options.
                 * @return
                *    the string representing the type of the `options` property of this
                 *    component.
                 */
                getOptionsType: function() {
                    if (this.options.length === 0) {
                        return "values";
                    }
                    var el = this.options[0];
                    if (typeof el == "string" || el instanceof String) {
                        return "values";
                    } else if (typeof el.text !== "undefined") {
                        return "options";
                    } else if (typeof el.label !== "undefined") {
                        return "groups";
                    } else {
                        return "unknown";
                    }
                },

                /**
                 * Tests whether a specified value exists in the options.
                 *
                 * @param value
                 *    the value to test.
                 * @return
                *    true if the specified value exists in the options; false otherwise.
                 */
                inOptions: function(value) {
                    var type = this.getOptionsType();
                    var list = this.options;
                    var i, j;
                    switch (type) {
                        case "values":
                            for (i = 0; i < list.length; ++i) {
                                if (value === list[i]) {
                                    return true;
                                }
                            }
                            break;
                        case "options":
                            for (i = 0; i < list.length; ++i) {
                                if (value === list[i].id) {
                                    return true;
                                }
                            }
                            break;
                        case "groups":
                            for (i = 0; i < list.length; ++i) {
                                var options = list[i].options;
                                for (j = 0; j < options.length; ++j) {
                                    if (value === options[j].id) {
                                        return true;
                                    }
                                }
                            }
                            break;
                        default:
                            break;
                    }
                    return false;
                },

                /**
                 * Gets the language code from the "language-country" locale code.
                 *
                 * The function will strip the language code before the first "-" of a
                 * locale code. For example, pass "en-US" will returns "en". But for some
                 * special locales, the function reserves the locale code. For example,
                 * the "zh-CN" for the simplified Chinese and the "zh-TW" for the
                 * traditional Chinese.
                 *
                 * @param locale
                 *    A locale code.
                 * @return
                *    the language code of the locale.
                 */
                getLanguageCode: function(locale) {
                    if (locale === null || locale.length === 0) {
                        return "en";
                    }
                    if (locale.length <= 2) {
                        return locale;
                    } else {
                        switch (locale) {
                            case "pt-BR":
                            case "zh-CN":
                            case "zh-TW":
                                return locale;
                            default:
                                // reserve only the first two letters language code
                                return locale.substr(0, 2);
                        }
                    }
                }
            }
        });

        Vue.component('small-selector', {
            template: '#small-selectors-template',
            props: {
                smallCategorys: [],
                small: 0,
            }
        });

        Vue.component('big-selector', {
            template: '#big-selectors-template',
            props: {
                bigCategorys: [],
                bigSelect: 0
            }
        });

        Vue.component('selector', {
            template: '#selectors-template',
            props: {
                bigCategorys: [],
                smallCategorys: [],
                bigSelect: 0,
                smallSelect: [],
                bigErrors: {},
                smallErrors: []
            },
            methods: {
                addSmallSelector: function(){
                    var addTpl = 0;
                    if (this.smallCategorys[0]) {
                        addTpl = this.smallCategorys[0]['id'];
                    }
                    this.smallSelect.push(addTpl);
                    this.smallErrors.push({isInvalid: false, msg: ""});
                },
                deleteSmallSelector: function(small){
                    this.smallSelect.$remove(small);
                }
            }
        });

        Vue.component('dynamic-selectors', {
            template: '#dynamic-selectors-template',
            props: {
                dynamicSelectors: [],
                bigCategorys: [],
                smallCategorys: [],
                dynamicErrors: []
            },
            methods: {
                addSelector: function(){
                    var addBigTpl = 0;
                    var addSmallTpl = 0;
                    if (this.bigCategorys[0]) {
                        addBigTpl = this.bigCategorys[0]['id'];
                    }
                    if (this.bigCategorys[0]) {
                        addSmallTpl = this.smallCategorys[0]['id'];
                    }
                    this.dynamicSelectors.push({bigSelect: addBigTpl, smallSelect: [addSmallTpl]});
                    this.dynamicErrors.push({bigErrors: {isInvalid: false, msg: ''}, smallErrors: [{isInvalid: false, msg: ''}]});
                },
                deleteSelector: function(selector){
                    this.dynamicSelectors.$remove(selector);
                }
            }
        });

        var vm = new Vue({
            el: '#type',
            data: {
                companyId: 0,
                bigCategorys: [],
                smallCategorys: [],
                selectors: [],
                errors: {
                    dynamicErrors: [],
                    titleErrors: {isInvalid: false, msg: ''}
                },
                rehearsal: [],
                baseTitle: '',
                type: 1
            },
            methods: {
                initModal: function () {
                    $("#myModal").modal('show');
                },

                getRehearsal: function(){
                    this.rehearsal = [];
                    var self = this;
                    var bigCats = this.getCategorys(this.bigCategorys);
                    var smallCats = this.getCategorys(this.smallCategorys);
                    for (var i = 0; i < self.selectors.length; i++) {
                        var tplBigCat = '';
                        var tplSmallCats = [];
                        var tplShow = false;
                        if (i===0) {
                            tplShow = true;
                        }
                        tplBigCat = bigCats[self.selectors[i]['bigSelect']];
                        for (var j = 0; j < self.selectors[i]['smallSelect'].length; j++) {
                            tplSmallCats.push(smallCats[self.selectors[i]['smallSelect'][j]]);
                        }
                        self.rehearsal.push({bigSelect: tplBigCat, smallSelect: tplSmallCats, show: tplShow});
                    }

                    return true;
                },

                getCategorys: function(cats) {
                    var tplCats = {};
                    for (var i = 0; i < cats.length; i++) {
                        tplCats[cats[i]['id']] = cats[i]['text'];
                    }

                    return tplCats;
                },

                toggleCats: function(index){
                    if (this.rehearsal[index].show) {
                        this.rehearsal[index].show = false;
                    }else{
                        for (var i = 0; i < this.rehearsal.length; i++) {
                            this.rehearsal[i].show = false;
                        }
                        this.rehearsal[index].show = true;
                    }
                },

                saveBase: function(){
                    var errorsNum = 0;
                    this.errors.dynamicErrors = [];
                    this.errors.titleErrors = {isInvalid: false, msg: ''};
                    var self = this;

                    if (this.baseTitle == '') {
                        this.errors.titleErrors.isInvalid = true;
                        this.errors.titleErrors.msg = '模板标题必填！';
                        errorsNum++;
                    }

                    if (this.selectors.length == 0) {
                        this.errors.titleErrors.isInvalid = true;
                        this.errors.titleErrors.msg = '分组至少一项！';
                        errorsNum++;
                    }

                    //错误分析
                    var selectors = this.selectors;
                    var allCats = [];
                    for (var i = 0; i < selectors.length; i++) {
                        var tplSmallErrors = [];
                        var tplBigErrors = {};
                        allCats.push(selectors[i]['bigSelect']);

                        if (!selectors[i]['bigSelect']) {
                            tplBigErrors = {isInvalid: true, msg: '必须选择！'};
                            errorsNum++;
                        }else if(selectors[i]['smallSelect'].length==0){
                            tplBigErrors = {isInvalid: true, msg: '小类至少一项！'};
                            errorsNum++;
                        }else if(this.checkRepeat(selectors[i]['bigSelect'], selectors)){
                            tplBigErrors = {isInvalid: true, msg: '有重复选项！'};
                            errorsNum++;
                        }else{
                            tplBigErrors = {isInvalid: false, msg: ''};
                        }

                        for (var j = 0; j < selectors[i]['smallSelect'].length; j++) {
                            allCats.push(selectors[i]['smallSelect'][j]);
                            if (!selectors[i]['smallSelect'][j]) {
                                tplSmallErrors.push({isInvalid: true, msg: '必须选择！'});
                                errorsNum++;
                            }else if(this.checkRepeat(selectors[i]['smallSelect'][j], selectors)){
                                tplSmallErrors.push({isInvalid: true, msg: '有重复选项！'});
                                errorsNum++;
                            }else{
                                tplSmallErrors.push({isInvalid: false, msg: ''});
                            }
                        }

                        this.errors.dynamicErrors.push({bigErrors: tplBigErrors, smallErrors: tplSmallErrors});

                    }

                    if (errorsNum) {
                        return false;
                    }

                    var url = "{{url('admin/salary/base')}}";
                    $.ajax(url, {
                        type: 'post',
                        dataType: 'json',
                        timeout: '120000',
                        data: {cid: self.companyId, title: self.baseTitle, type: self.type, category: allCats},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        if (data.ret_num === 0) {
                            self.companyId = 0;
                            self.bigCategorys = [];
                            self.smallCategorys = [];
                            self.selectors = [];
                            self.errors = {
                                dynamicErrors: [],
                                titleErrors: {isInvalid: false, msg: ''}
                            };
                            self.rehearsal = [];
                            self.baseTitle = '';
                            alert(data.ret_msg);
                            window.location.href = data.data.url;
                        } else {
                            alert("网络错误！");
                        }
                    }).fail(function (errors) {
                        errorInfo = errors.responseJSON;
                        if (errorInfo.title){
                            self.errors.titleErrors = {isInvalid: true, msg: errorInfo.title};
                        }else{
                            alert("网络错误！");
                        }
                    }).complete();
                },

                checkRepeat: function(checkVal, selectors){
                    var tplAll = 0;
                    for (var i = 0; i < selectors.length; i++) {
                        if (checkVal == selectors[i]['bigSelect']) {
                            tplAll++;
                        }

                        for (var j = 0; j < selectors[i]['smallSelect'].length; j++) {
                            if (checkVal == selectors[i]['smallSelect'][j]) {
                                tplAll++;
                            }
                        }
                    }

                    if (tplAll == 1 || tplAll == 0) {
                        return false;
                    }else{
                        return true;
                    }
                }
            },
            events: {
                'company-id': function (companyId) {
                    this.companyId=companyId;
                },
                'big-category': function (bigCategory) {
                    this.bigCategorys=bigCategory;
                },
                'small-category': function (smallCategory){
                    this.smallCategorys=smallCategory;
                }
            }
        });

    </script>
@endsection