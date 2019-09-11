$(function() {
    $("#file_upload").uploadify({
        'swf'             : SCOPE.uploadify_swf,
        'uploader'        : SCOPE.image_upload,
        'buttonText'      : '图片上传',
        'fileTypeDesc'    : 'Image Files',
        'fileTypeExts'    : '*.gif; *.jpg; *.png;*.webp',
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
$(function() {
    $("#file_upload_product").uploadify({
        'swf'             : SCOPE.uploadify_swf,
        'uploader'        : SCOPE.image_upload,
        'buttonText'      : '图片上传',
        'fileTypeDesc'    : 'Image Files',
        'fileTypeExts'    : '*.gif; *.jpg; *.png;*.webp',
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
$(function() {
    $("#file_upload_img").uploadify({
        'swf'             : SCOPE.uploadify_swf,
        'uploader'        : SCOPE.image_upload,
        'buttonText'      : '图片上传',
        'fileTypeDesc'    : 'Image Files',
        'fileTypeExts'    : '*.gif; *.jpg; *.png;*.webp',
        'fileObjName'     : 'file',
        'onUploadSuccess' : function(file, data, response) {
            //console.log(file);
            //console.log(data); {"status":1,"message":"success","data":"\/upload\\20170611\\1c365af70093b2ecab7ccca9e645b300.jpg"}
            //console.log(response); true
            if(response){
                var obj =JSON.parse(data);
                $("#upload_org_code").attr("src",obj.data);
                $("#upload_image_product").attr("value",obj.data);
                $("#upload_org_code").show();

            }
        }
    });
});
$(function() {
    $("#file_upload_img_mobile").uploadify({
        'swf'             : SCOPE.uploadify_swf,
        'uploader'        : SCOPE.image_upload,
        'buttonText'      : '图片上传',
        'fileTypeDesc'    : 'Image Files',
        'fileTypeExts'    : '*.gif; *.jpg; *.png;*.webp',
        'fileObjName'     : 'file',
        'onUploadSuccess' : function(file, data, response) {
            //console.log(file);
            //console.log(data); {"status":1,"message":"success","data":"\/upload\\20170611\\1c365af70093b2ecab7ccca9e645b300.jpg"}
            //console.log(response); true
            if(response){
                var obj =JSON.parse(data);
                $("#upload_org_code_mobile").attr("src",obj.data);
                $("#upload_image_product_mobile").attr("value",obj.data);
                $("#upload_org_code_mobile").show();

            }
        }
    });
});
