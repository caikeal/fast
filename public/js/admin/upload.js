$.extend(WebUploader.Uploader.options, {
	// 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
	// swf:'js/webuploader-0.1.5/Uploader.swf',
    swf: 'http://cdn.staticfile.org/webuploader/0.1.0/Uploader.swf',
    // 选择文件的按钮。可选。
	// 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick:{
		innerHTML:'<i class="fa fa-cloud-upload"></i> 上传',
    },
	method:'POST',
	//只允许一个文件
	fileNumLimit :'1',
	// 只允许选择图片文件。
	accept: {
        title: 'excel',
        extensions: 'xls,xlsx',
        mimeTypes: 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    },
    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
    resize: false
});

$(".upload-ctrl").each(function(index,eles){
	if($("#picker"+index).length) {
		// 初始化Web Uploader
		window["uploader" + index] = WebUploader.create({
			pick: {
				id: '#picker' + index,
				list: '#thelist' + index,
			},
			fileVal: "excel",
		});

		// 当有文件被添加进队列的时候
		window["uploader" + index].on('fileQueued', function (file) {
			$(this.options.pick.list).append('<div id="' + file.id + '" class="item">' +
					'<h4 class="info">' + file.name + '</h4>' +
					'<p class="state">等待上传...</p>' +
					'</div>');
		});

		// 文件上传过程中创建进度条实时显示。
		window["uploader" + index].on('uploadProgress', function (file, percentage) {
			var $li = $('#' + file.id),
					$percent = $li.find('.progress .progress-bar');

			// 避免重复创建
			if (!$percent.length) {
				$percent = $('<div class="progress progress-striped active">' +
						'<div class="progress-bar" role="progressbar" style="width: 0%">' +
						'</div>' +
						'</div>').appendTo($li).find('.progress-bar');
			}

			$li.find('p.state').text('上传中');

			$percent.css('width', percentage * 100 + '%');
		});

		//文件上传前加入头信息
		window["uploader" + index].on('uploadBeforeSend', function (object, data, headers) {
			var tid = $(eles).attr("data-task");
			var type=$("#type").attr("data-type");
			data['task_id'] = tid;
			data['type'] = type;
			headers["X-CSRF-TOKEN"] = $('meta[name="csrf-token"]').attr('content');
		});

		window["uploader" + index].on('uploadSuccess', function (file, response) {
			$('#' + file.id).find('p.state').text('已上传');
			$(this.options.pick.id).find('.webuploader-pick').addClass('webuploader-pick-disable');
			$(this.options.pick.id).find('.webuploader-pick').next().addClass('webuploader-pick-disable');
			//todo html response
		});

		window["uploader" + index].on('uploadError', function (file, reason) {
			console.log(reason);
			$('#' + file.id).find('p.state').text("上传失败");
			if ($(this.options.pick.list + " >.item").length > 2) {
				$($(this.options.pick.list + " >.item")[0]).remove();
			}
			this.reset();
			// $('#picker1').find('.webuploader-pick').addClass('webuploader-pick-disable');
			// $('#picker1').find('.webuploader-pick').next().addClass('webuploader-pick-disable');
		});

		window["uploader" + index].on('uploadComplete', function (file) {
			$('#' + file.id).find('.progress').fadeOut();
		});
		//end上传按钮结束
	}
});