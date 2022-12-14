layui.define([], function (exports) {
    let plugin_filename = 'tinymce.min.js';
    let plugin_tinymce  = layui.cache.modules['tinymce'];
    let plugin_base_url = plugin_tinymce.substr(0, plugin_tinymce.lastIndexOf('.'));

    /**
     * 对象
     */
    let ojb = {
        // 编辑器加载
        render: function (options, callback) {
            options = options === undefined ? {} : options;
            let option = initOptions(options,callback);
            let editor = ojb.get(option.elem);
            if (editor) {
                editor.destroy();
            }

            return tinymce.init(option);
        },
        // 编辑器对象
        get: function (elem) {
            initTinymce();

            if (elem && /^#|\./.test(elem)) {
                let id = elem.substr(1);
                return tinymce.editors[id];
            } else {
                return false;
            }
        },
        // 编辑器重载
        reload: function (elem, option, callback) {
            let options = {};

            if(typeof elem == 'string'){
                option.elem = elem;
                options = $.extend({}, option)
            } else if (typeof elem == 'object' && typeof elem.elem == 'string'){
                options = $.extend({}, elem);
                callback = option
            }

            let optionCache = layui.sessionData('layui-tinymce')[options.elem];
            delete optionCache.init_instance_callback;
            $.extend(optionCache,options);
            return ojb.render(optionCache,callback)
        }
    };

    /**
     * 判空工具
     */
    function isset(value) {
        return typeof value !== 'undefined' && value !== null
    }

    /**
     * 初始编辑器
     */
    function initTinymce() {
        if (typeof tinymce == 'undefined') {
            $.ajax({
                url: plugin_base_url + '/' + plugin_filename,
                dataType: 'script',
                cache: true,
                async: false,
            });
        }
    }

    /**
     * 初始化参数
     */
    function initOptions(option, callback) {
        option.elem     = isset(option.elem)     ? option.elem : '#content';
        option.readonly = isset(option.readonly) ? option.readonly : 0;
        option.suffix   = isset(option.suffix)   ? option.suffix : (plugin_filename.indexOf('.min')>-1 ? '.min' : '');
        option.base_url = isset(option.base_url) ? option.base_url : plugin_base_url;
        option.language = isset(option.language) ? option.language : 'zh_CN';
        option.selector = isset(option.selector) ? option.selector : option.elem;
        option.resize   = isset(option.resize)   ? option.resize : false;
        option.branding = isset(option.branding) ? option.branding : false;
        option.elementpath  = isset(option.elementpath) ? option.elementpath : false;
        option.convert_urls = isset(option.convert_urls) ? option.convert_urls : false;
        option.height       = isset(option.height)  ? option.height : 600;
        option.menu         = isset(option.menu)    ? option.menu : false;
        option.menubar      = isset(option.menubar) ? option.menubar : false;
        option.attach       = isset(option.attach)  ? option.attach : 'attach image media';

        option.plugins = isset(option.plugins) ? option.plugins : `code preview fullpage searchreplace autolink
                    directionality visualblocks visualchars fullscreen link template charmap hr pagebreak 
                    nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern help emoticons 
                    table autosave paste print codesample ` + option.attach;

        option.toolbar = isset(option.toolbar) ? option.toolbar : [
            `code
                    | bold italic underline strikethrough forecolor backcolor subscript superscript
                    | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent
                    | emoticons charmap wordcount | fullscreen`,
            `formatselect
                    | fontselect | fontsizeselect | table hr blockquote codesample link | `+ option.attach +`
                    | anchor removeformat print | searchreplace`
        ];

        option.init_instance_callback = isset(option.init_instance_callback) ? option.init_instance_callback : function(inst) {
            if(typeof callback == 'function') callback(option,inst)
        };

        option.images_upload_url     = isset(option.images_upload_url) ? option.images_upload_url : false;

        option.urlconverter_callback = isset(option.urlconverter_callback) ? option.urlconverter_callback : false;

        option.attach_upload_callback = isset(option.attach_upload_callback) ? option.attach_upload_callback : function(callback) {
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shadeClose: true,
                content:
                    '<div style="padding:10px 40px;">'  +
                        '<button class="layui-btn" id="imageAttachBtn" style="margin:8px 0; background-color: #3e526a;"><i class="layui-icon layui-icon-picture"></i> 图片选择</button>' +
                        '<br/>' +
                        '<button class="layui-btn" id="videoAttachBtn" style="margin:8px 0; background-color: #3e526a;"><i class="layui-icon layui-icon-play"></i> 视频选择</button>' +
                    '</div>',
                success: function (layero, index) {
                    $(layero).find('#imageAttachBtn').click(function () {
                        layer.close(index);
                        waitUtil.uploader({
                            type: 'image',
                            limit: 20
                        }).then((res) => {
                            let urls = [];
                            res.forEach(function (item) {
                                urls.push(item.url);
                            });
                            callback(urls);
                        });
                    });

                    $(layero).find('#videoAttachBtn').click(function () {
                        layer.close(index);
                        waitUtil.uploader({
                            type: 'video',
                            limit: 20
                        }).then((res) => {
                            let urls = [];
                            res.forEach(function (item) {
                                urls.push(item.url);
                            });
                            callback(urls);
                        });
                    });
                }
            });
        };

        option.images_upload_callback = isset(option.images_upload_callback) ? option.images_upload_callback : function(callback) {
            waitUtil.uploader({
                type: 'image',
                limit: 20
            }).then((res) => {
                let urls = [];
                res.forEach(function (item) {
                    urls.push(item.url);
                });
                callback(urls);
            });
        };

        option.video_upload_callback = isset(option.video_upload_callback) ? option.video_upload_callback : function(callback) {
            waitUtil.uploader({
                type: 'video',
                limit: 20
            }).then((res) => {
                let urls = [];
                res.forEach(function (item) {
                    urls.push(item.url);
                });
                callback(urls);
            });
        };

        option.file_picker_callback  = isset(option.file_picker_callback) ? option.file_picker_callback : function(callback, value, meta) {
            let pathname = window.location.pathname.split('/')[1];

            let fileUrl;
            let fileType;
            switch(meta.filetype){
                case 'image':
                    fileUrl  = '/'+pathname+'/upload/temporary?type=image';
                    fileType = '.png, .jpg, .jpeg, .gif, .ico, .bmp';
                    break;
                case 'media':
                    fileUrl  = '/'+pathname+'/upload/temporary?type=video';
                    fileType = '.mp4, .mp3, .avi, .flv, .rmvb, .mov';
                    break;
                default:
                    fileUrl = '/'+pathname+'/upload/temporary?type=image'
            }

            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', fileType);
            input.click();
            input.onchange = function () {
                let file = this.files[0];
                let xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', fileUrl);
                xhr.onload = function() {
                    let json;
                    if (xhr.status !== 200) {
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    callback(json.data.url, {title: file.name});
                };
                formData = new FormData();
                formData.append('file', file, file.name );
                xhr.send(formData);
            };
        };

        option.images_upload_handler = isset(option.images_upload_handler) ? option.images_upload_handler : function(blobInfo, successFun, failFun) {
            let pathname = window.location.pathname.split('/')[1];
            let reqUrl = '/'+pathname+'/upload/temporary?type=image';

            let xhr, formData;
            let file = blobInfo.blob();
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST',  option.images_upload_url ? option.images_upload_url : reqUrl);
            xhr.onload = function() {
                let json;
                if (xhr.status !== 200) {
                    failFun('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.data.url != 'string') {
                    failFun('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                successFun(json.data.url);
            };
            formData = new FormData();
            formData.append('file', file, file.name );
            xhr.send(formData);
        };

        layui.sessionData('layui-tinymce', {
            key: option.selector,
            value: option
        });

        return option;
    }

    exports('tinymce', ojb);
});