$(function() {
    $("#file_upload").uploadify({
        'swf'             : SCOPE.uploadify_swf,
        'uploader'        : SCOPE.download_upload,
        'buttonText'      : '文件上传',
        'fileTypeDesc'    : 'Download Files',
        'fileTypeExts'    : '*.zip',
        'fileObjName'     : 'file',
        'fileSizeLimit'   : '50MB',
        'onUploadSuccess' : function(file, data, response) {
            // console.log(file);
            // console.log(data);
            // console.log(response);
            if(response){
                var obj =JSON.parse(data);
                $("#file_code").attr("name",obj.data);
                $("#file_upload_code").attr("value",obj.data);
                $("#file_code").show();

            }
        }
    });
});
$(function() {
    $("#file_upload_product").uploadify({
        'swf'             : SCOPE.uploadify_swf,
        'uploader'        : SCOPE.download_upload,
        'buttonText'      : '图片上传',
        'fileTypeDesc'    : 'Image Files',
        'fileTypeExts'    : '*.gif; *.jpg; *.png',
        'fileObjName'     : 'file',
        'onUploadSuccess' : function(file, data, response) {
            //console.log(file);
            //console.log(data); {"status":1,"message":"success","data":"\/upload\\20170611\\1c365af70093b2ecab7ccca9e645b300.jpg"}
            //console.log(response); true
            if(response){
                var obj =JSON.parse(data);
                $("#upload_org_code_img").attr("src",obj.data);
                $("#file_upload_image").attr("value",obj.data);
                $("#upload_org_code_img").show();

            }
        }
    });
});