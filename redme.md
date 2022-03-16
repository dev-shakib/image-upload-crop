## laravel setup

**step 1**


master index.php in to blade file make sure all the links are correctly set

**step 2**

in index.php at the bottom of the script look at the last function there is a ajax call in it. you just have to change the url of it with laravel route (post route).
you can see on data we have a image variable it contain the cropped image (base64 string).


**step 3**

now copy all from the upload.php and paste it on your controller and replace all `$_POST['image']` with `$request->image`

**step 4**

change the `folderPath` variable to your folder path.




