<html>
<head>
<title></title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript">

$(function() {

    //here you have the control over the body of the iframe document
    var iBody = $("#iView").contents().find("body");

    //here you have the control over any element (#myContent)
    var myContent = iBody.find("#myContent");

});

</script>
</head>
<body>
  <iframe src="https://video-app-6161-8881-dev.twil.io?passcode=72456261618881" id="iView" style="width:200px;height:70px;border:dotted 1px red" frameborder="0"></iframe>
</body>
</html>
 