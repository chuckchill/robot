var qiniuError = function (error) {
    switch (error.code){
        case 579:
            alert("上传成功回调失败");
            break;
        case 403:
            alert("权限不足，拒绝访问。");
            break;
        case 614:
            alert("目标资源已存在");
            break;
        default:
            alert(error)
            break

    }
}