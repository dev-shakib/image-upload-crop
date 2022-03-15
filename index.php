<!DOCTYPE html>
<html>
<head>
    <title>PHP Crop Image Before Upload using Cropper JS</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
</head>
<style type="text/css">
img {
  display: block;
  max-width: 100%;
}
.modal-lg{
  max-width: 1000px !important;
}
.upload-form {
	width: 100%;
	height: 150px;
	background-color: #21202F;
	border-radius: 4px;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	max-width: 500px;
	margin: auto;
	color: #fff;
}
.upload-form h1 {
	font-size: 26px;
	margin-bottom: 18px;
}
.upload-form .custom-file-label {
	height: auto;
	border: none;
}
.upload-form .custom-file-label::after {
	color: #282828;
	background-color: #FFD600;
}
.preview {
    overflow: hidden;
    width: 250px; 
    height: 250px;
    margin-left: 10px;
    border: 1px solid #FFD600;
}
.upload-modal .actions input {
    border-color: #FFD600;
}
.upload-modal .btn-primary {
    color: #21202F !important;
    background-color: #FFD600 !important;
    border-color: #FFD600 !important;
}
.upload-modal .btn-secondary {
    color: #fff !important;
    background-color: #21202F !important;
    border-color: #21202F !important;
}
.upload-modal .btn,
.upload-modal .form-control:focus {
	box-shadow: none !important;
}

.lds-hourglass {
  display: inline-block;
  position: relative;
}
.lds-hourglass:after {
  content: " ";
  display: block;
  border-radius: 50%;
  width: 0;
  height: 0;

  box-sizing: border-box;
  border: 10px solid white;
  border-color: white transparent white transparent;
  animation: lds-hourglass 1.2s infinite;
}

@keyframes lds-hourglass {
  0% {
    transform: rotate(0);
    animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
  }
  50% {
    transform: rotate(900deg);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }
  100% {
    transform: rotate(1800deg);
  }
}

</style>
<div class="container">
    <div class="upload-form">
      <h1>Image Cropper</h1>
      <form method="post">
        <div class="input-group">
          <div class="custom-file">
            <input type="file" name="image" class="image custom-file-input">
            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
          </div>
        </div>
      </form>
    </div>
</div>

<div class="modal fade upload-modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container">
            <div class="row">
                <div class="col-md-8">
                    <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                    <div class="d-flex justify-content-center">
                      <div class="actions input-group w-50 my-3">
                        <input id="height" type="text" value="" class="form-control rounded mr-2" placeholder="Width">
                        <input id="width" type="text" value="" class="form-control rounded mr-2" placeholder="Height">
                        <div class="">
                          <button id="resize" class="btn btn-primary">Resize</button>
                        </div>  
                        <button id="zoomin" class="btn btn-primary ml-2">+</button>
                        <button id="zoomout" class="btn btn-primary ml-2  ">-</button>
                      </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="preview"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="crop">Crop</button>
      </div>
    </div>
  </div>
</div>

</div>
</div>
<script>

var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;
var height = '';
var width = '';
  
$("body").on("change", ".image", function(e){
    var files = e.target.files;
    var done = function (url) {
      image.src = url;
      $modal.modal('show');
    };
    var reader;
    var file;
    var url;

    if (files && files.length > 0) {
      file = files[0];

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
});

$modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
    aspectRatio: 0,
    viewMode: 2,
    preview: '.preview',
    data: {
      height: 300,
      width: 300,
      x: 0,
      y:0,
    }
    });

    $("#resize").click(function(){
      height = Number($("#height").val());
      width = Number($("#width").val());
      cropper.setData({
        height: height,
        width: width
      });
    });

    // zoom in
    $("#zoomin").click(function(){
      cropper.zoom(0.1);
    });
    // zoom out
    $("#zoomout").click(function(){
      cropper.zoom(-0.1);
    });

}).on('hidden.bs.modal', function () {
   cropper.destroy();
   cropper = null;
   height = '';
   width = '';
});



$("#crop").click(function(){

    canvas = cropper.getCroppedCanvas();

    // add loader to crop
    $(this).text('')
    $(this).addClass('lds-hourglass');

    canvas.toBlob(function(blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
         reader.readAsDataURL(blob); 
         reader.onloadend = function() {
            var base64data = reader.result;  
            
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "upload.php",
                data: {image: base64data},
                success: function(data){
                    console.log(data);
                    $modal.modal('hide');
                    $("#crop").removeClass('lds-hourglass');
                    $("#crop").text('Crop');
                    alert(data);
                }
              });
         }
    });
})

</script>
</body>
</html>