<?php
if (! empty($_POST["upload"])) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        $targetPath = "uploads/" . $_FILES['userImage']['name'];
        if (move_uploaded_file($_FILES['userImage']['tmp_name'], $targetPath)) {
            $uploadedImagePath = $targetPath;
        }
    }
}
?>
<html>
<head>
<title>Upload and Crop Image using PHP and jQuery</title>
<link rel="stylesheet" href="jquery.Jcrop.min.css" type="text/css" />
<script src="jquery.min.js"></script>
<script src="jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="style.css">
<style>
input#crop {
    visibility: hidden;
}
#cropped_img {
    margin-top: 40px;
    width: 400px;
}
/* full custom section width */
.custom-img-upload {
	width: 650px;
	margin: 0 auto;
    text-align: center;
}
/* section bg color  */
.custom-img-upload .bgcolor {
    width: 100%;
    height: 150px;
    background-color: #21202F;
    border-radius: 4px;
    margin-bottom: 30px;
	display: flex;
	justify-content: center;
	align-items: center;
}
/* full input area  */
.custom-img-upload #uploadFormLayer {
   background: #fff;
   border-radius: 8px;
}
/* input file browse button */
.custom-img-upload .inputFile {
	padding: 15px 0;
	padding-left: 15px;
    cursor: pointer;
}
/* for submit button  */
.custom-img-upload  .btnSubmit {
    padding: 18px 25px;
    border: 0;
    background: #FFD600;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    cursor: pointer;
}
/* input crop button  */
.custom-img-upload input#crop {
	padding: 12px 40px 12px 40px;
	background: #FFD600;
	border: none;
	color: #282727;
	border-radius: 100px;
	cursor: pointer;
	font-weight: bold;
}
</style>
</head>
<body>
<?php
// $imagePath = "initial-image.jpeg";
if (! empty($uploadedImagePath)) {
    $imagePath = $uploadedImagePath;
}
?>

<div class="custom-img-upload">
    <div class="bgColor">
        <form id="uploadForm" action="" method="post"
            enctype="multipart/form-data">

            <div id="uploadFormLayer">
                <input name="userImage" id="userImage" type="file"
                    class="inputFile"> 
                <input type="submit"
                    name="upload" value="Submit" class="btnSubmit">
            </div>
        </form>
    </div>
    <div>
        <img src="<?php echo @$imagePath; ?>" id="cropbox" class="img" /><br />
    </div>
    <div>
        <img src="#" id="cropped_img" style="display: none;">
    </div>

    <div id="btn">
        <input type='button' id="crop" value='CROP'>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var size;
    $('#cropbox').Jcrop({
      boxWidth: 650,   //Maximum width you want for your bigger images
      boxHeight: 400,  //Maximum Height for your bigger images
      aspectRatio: 0,
      onSelect: function(c){
       
       size = {x:c.x,y:c.y,w:c.w,h:c.h};
       $("#crop").css("visibility", "visible");     
      }
    });
 
    $("#crop").click(function(){
        var img = $("#cropbox").attr('src');
        $("#cropped_img").show();
        
        // $("#cropped_img").attr('src','image-crop.php?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+img);
        // send a get request to image-crop.php
        // and pass the values of x, y, w, h, img
        // to the file
        $.ajax({
            url: 'image-crop.php',
            method: 'get',
            data: {
                x: size.x,
                y: size.y,
                w: size.w,
                h: size.h,
                img: img
            }
        }).then(function(response){
            $("#cropped_img").attr('src', response);

        });

        $(".jcrop-holder").hide();
        console.log(img)
    }); 
});
</script>
</body>
</html>