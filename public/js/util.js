var Util = App.Util = {};


// some util api
Util.exists = function(selector) {
    return $(selector).length > 0;
};
Util.detectError = function(ret) {
	return ret.error ? ret.error : ( ret.msg ? ret.msg : ret.code);
};

Util.monthNames = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];

Util.getNumberLength = function(data) {
	return data.toString().length;
};
Util.isCanvasSupported = function (){
  var elem = document.createElement('canvas');
  return !!(elem.getContext && elem.getContext('2d'));
};
Util.getBase64Image = function(img, replace) {
    replace = _.isEmpty(replace) ? false : true;//更多选项
    // Create an empty canvas element
    var canvas = document.createElement("canvas");
    canvas.width = img.width;
    canvas.height = img.height;

    // Copy the image contents to the canvas
    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);

    // Get the data-URL formatted image
    // Firefox supports PNG and JPEG. You could check img.src to
    // guess the original format, but be aware the using "image/jpg"
    // will re-encode the image.
    var dataURL = canvas.toDataURL("image/png");

    if(replace) {
    	return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
    } else {
    	return dataURL;
    }
};
Util.getBase64FromImageUrl = function(url, fn) {
    var img = new Image();
    img.onload = function () {
    	fn(Util.getBase64Image(this), false);
    };
    img.src = url;
};