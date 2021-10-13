<!doctype html>
<html lang="zh-Hans">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Fresns" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>上传文件</title>
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
</head>

<body>
    <form id="upload_form" method="post" action="http://upload.qiniup.com/" enctype="multipart/form-data">
        <div class="input-group mb-2">
            <input id="key" name="key" type="hidden" value="{{$resource_key}}">
            <input id="token" name="token" type="hidden" value="{{$upload_token}}">
            <input id="file_type" name="file_type" type="hidden" value="{{ $file_type }}">
            <input id="x_var_1" name="x:var_1" type="hidden" value="我是自定义变量演示">
            <input id="file" class="form-control" name="file" type="file" accept="*/*" onchange="loadImage(this)"/>
            <input id="submitButton" class="btn btn-outline-secondary" type="submit"  value="上传" />
            <input id="ext" name="ext" type="hidden" value="{{ $file_ext }}"/>
            <input id="size" name="size" type="hidden" value="{{ $file_size }}"/>
            <input id="table_type" name="table_type" type="hidden" value="{{ $table_type }}"/>
            <input id="table_name" name="table_name" type="hidden" value="{{ $table_name }}"/>
            <input id="table_field" name="table_field" type="hidden" value="{{ $table_field }}"/>
            <input id="fil_suffix" name="fil_suffix" type="hidden" value=""/>
        </div>
        <label class="form-label">
            支持的扩展名：{{ $file_ext }}
            <br>支持的最大尺寸：{{ $file_size }} MB
        </label>
    </form>
    <br>
    上传token: {{$upload_token}}
    <br>
    测试防盗链链接: {{$download_url}}
    <br>

    <!-- table 测试使用 -->
    <table class="table table-striped" style="margin-top: 30px">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">key</th>
            <th scope="col">hash</th>
            <th scope="col">mimeType</th>
        </tr>
        </thead>
        <tbody>
        @foreach($file_arr as $idx => $file)
            <tr>
                <th scope="row">{{$idx + 1}}</th>
                <td><a href="http://file.fresns.org/{{$file['key']}}" target="_blank">{{$file['key']}}</a></td>
                <td>{{$file['hash']}}</td>
                <td>{{$file['mimeType']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script src="/static/js/jquery-3.6.0.min.js"></script>
    <script src="/static/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    var isCanSubmit = true;
    $(document).ready(function () {
        $("#submitButton").click(function (event) {
            //stop submit the form, we will post it manually.
            event.preventDefault();
            if(!isCanSubmit){
                alert("您上传的文件格式或大小不对,请重新上传！");
                return false
            }
            // Get form
            var form = $('#upload_form')[0];
            var data = new FormData(form);
            data.append("custom1", "custom test");

            $("#submitButton").prop("disabled", true);
            $.ajax({
                url: 'http://upload.qiniup.com/',
                type: 'post',
                enctype: 'multipart/form-data',
                data: data,
                processData: false,  // Important!
                contentType: false,
                cache: false,
                timeout: 600000,
                beforeSend: function (request) {
                    // return request.setRequestHeader('Content-Type', "application/json");
                },
                success: function (res) {
                    console.log("upload success ", res)
                    uploadCallback(res)
                },
                error: function (e){
                    console.log("upload error", e)
                }
            });
        });
    });

    function loadImage(img) {
        var filePath = img.value;
        var fileExt = filePath.substring(filePath.lastIndexOf(".") + 1).toLowerCase();
        $('#fil_suffix').val(fileExt)
        console.log(fileExt);
        if (!checkFileExt(fileExt)) {
            isCanSubmit = false
            alert("您上传的文件格式不对,请重新上传！");
            img.value = "";
            return;
        }else{
            isCanSubmit = true;
        }
        var size = $("#size").val()
        if (img.files && img.files[0]) {  
            var fileSize = ((img.files[0].size / 1024)/1024).toFixed(0);
            console.log(fileSize);
            if(parseInt(fileSize) > parseInt(size)){
                isCanSubmit = false;
                alert('你选择的文件大小' + fileSize + "M" + ',请重新选择文件上传');
                img.value = "";
                return;
            }else{
                isCanSubmit = true;
            }
            
        }
        console.log(img.files[0]);
        var fileName = img.files[0].name;
        getToken(fileName);

    }

    function getToken(name){
        var type = $('#file_type').val();
        var newKey = $('#key').val() + '/' + name;
        $.ajax({
            url: "/api/qiniu/getToken",
            type: 'get',
            data: {
                file_type: type,
                key: newKey,
            },
            beforeSend: function (request) {
                //预加载动作
            },
            success: function (res) {
                if (res.code == 0) {
                    $('#key').val(newKey);
                    $('#token').val(res.data.token);
                } else {
                    
                }
            }
        });
    } 
    
    function checkFileExt(ext) {
        var regular = $("#ext").val();
        if(regular){
            regular = regular.split(',')
        }
        console.log(regular);
        if(regular.indexOf(ext) == -1){
            return false;
        }
        return true;
    }

    /**
     * 上传结果回写
      * @param qiNiuUploadResult
     *  // {
        //     "name": "1-min.jpg",
        //     "size": 20093,
        //     "width": 1200,
        //     "height": 794,
        //     "format": "jpeg",
        //     "key": "uHpHi6d9Vm",
        //     "qiNiuUuid": "392d64a7-e5ea-4cb3-8230-fbd201d62270",
        //     "fileType": null,
        //     "hash": "FiVVwPr93QbhgCea6iWvdZ0ajjXL"
        // }
     */
    function uploadCallback(qiNiuUploadResult){
        var key = $("#key").val()
        var token = $("#token").val()
        var x_var_1 = $("#x_var_1").val()
        var file_type = $("#file_type").val()
        var table_type = $("#table_type").val()
        var table_name = $("#table_name").val()
        var table_field = $("#table_field").val()
        var fil_suffix = $("#fil_suffix").val()
        var callbackUuid = getQueryString("callback");

        var appendParams = {
            key: key,
            token: token,
            x_var_1: x_var_1,
            file_type: file_type,
            callbackUuid: callbackUuid,
            table_type: table_type,
            table_name: table_name,
            table_field: table_field,
            fil_suffix: fil_suffix,
        }

        var params = {
            appendParams: appendParams,
            qiNiuUploadResult: qiNiuUploadResult,
        }

        $.ajax({
            url: "/api/qiniu/uploadCallback",
            type: 'post',
            data: JSON.stringify(params),
            beforeSend: function (request) {
                return request.setRequestHeader('Content-Type', "application/json");
            },
            success: function (res) {

            },
            error: function (e){
                console.log("upload error", e)
            }
        });
    }


    // 获取url参数
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return decodeURIComponent(r[2]);
        };
        return null;
    }

</script>

</html>