<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Rock+Salt" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <title>Php taskA</title>
  </head>
    
  <body>
    <header>
        <div class="cover-image">
            <h1>File Uploader</h1>
        </div> 
       <nav role='navigation'>
            <a class ="links" href="index.html">Upload</a>
            <a class ="links" href="download.php">Download</a>
       </nav>  
     </header>

    <main class="content">
      <div class="wrap" id="download">
        <h6>Click to download the files below</h6>

        <?php
        include('class.uploader.php');
        date_default_timezone_set("Canada/Pacific");
            $uploader = new Uploader();

            $my_file = 'files.txt';
            $file = fopen($my_file, 'a+') or die('Cannot open file:  '.$my_file);

            if(isset($_POST['upload'])){
                if(isset($_FILES['filename'])){
                    $tmpName = $_FILES["filename"]["name"];
                    $originName = reset($_FILES["filename"]);
                    if($_POST['displayName'] != ""){
                        $editedName = $_POST['displayName'];
                        if(!strpos($editedName, ' ')){
                            $editedName = $editedName . "." . pathinfo($originName, PATHINFO_EXTENSION);
                            $oriAndEdited = $originName . " " . $editedName;
                            fwrite($file, $oriAndEdited."\r\n");

                            save($editedName);
                        }else{
                            echo'<p style = "text-align:center; color:red";>Please do not enter the file name with space.<p>';
                        }

                    }
                    else{
                        if($_FILES["filename"]["size"] > 1024){
                            save($tmpName);
                        }else{
                            echo '<p style = "text-align: center; color: red;">File size should larger than 1KB.</p>';
                        }
                    }

                }else{
                    echo "Please choose a file.";
                }
            }
            echo'<table class="table"><form action = "download.php" method = "get">
                 <thread>
                    <tr><th scope="col">#</th>
                        <th scope="col">Filename</th>
                        <th scope="col">Download Here&nbsp<i class="fas fa-download"></i></th>
                    </tr>
                 </thead>
                 <tbody>';

            if(is_dir('uploads')){
                $filenames = scandir('uploads');
                $num = 0;
                foreach ($filenames as $key => $value){
                    if($value != '.' && $value != '..'){
                        $num++;
                        echo '<tr>
                                <th scope="row">'.$num.'</th>
                                <td>'.$value.'</td>
                                 <td><input type = "submit" class = "ups" name = "download" value = "'.$value.'"> </td></tr>';
                        if(isset($_GET["download"]) && $_GET["download"] == $value){
                            $oriN = getOriFileName($value, $my_file);
                            $of_ef_dddt_ex = $oriN . "_" .pathinfo($value)["filename"] . "_" .date("M-j-Y-H:i"). "." . pathinfo($value)["extension"]; 
                            //check file size between 1KB to 1MB
                            if((filesize("uploads/".$value) > 1024 ) && (filesize("uploads/".$value) < 1048576)){
                                download($of_ef_dddt_ex);
                            }else{
                                echo "the file size should between 1KB to 1MB.";
                            }
                        }
                    }
                }
            }

            echo "</tbody></form></table>";
            fclose($file);
            //Get the origin file name 
            function getOriFileName($name, $my_file){
                $result = "";
                if ($my_file) {
                    //array stored lines in txt file(original filename+ edited filename)
                    $lines = explode("\r\n", file_get_contents('./'.$my_file));
                    foreach($lines as $l){
                        $names = explode(" ", $l);
                        $fileNames[] = $names;
                    }
                    foreach($fileNames as $f){
                        if($f[0] != ""){
                            if(strcmp($name, $f[1]) == 0){
                                $result = pathinfo($f[0])["filename"];
                            }
                        }
                    }
                }
                return $result;
            }

            //function file download
            function download($file){
                header("Content-Length: " . $file);
                header('Content-Disposition: attachment; filename=' . $file);
                readfile($file);
                exit;
            }

            //function initialize Uploader
            function save($name){
                $uploader = new Uploader();
                $data = $uploader->upload($_FILES["filename"], array(
                    'limit' => 10,
                    'uploadDir' => 'uploads/', //Upload directory {String}
                    'title' => $name, //New file name {null, String, Array} *please read documentation in README.md
                    'maxSize' => '1MB',
                    'extensions' => array("php", "css", "js", "html"),
                ));

                if($data['hasErrors']){
                    $errors = $data['errors'];
                    print_r($errors);
                }
            }
        ?>

      </div>
    </main>
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
    $(function(){
      var header = $("header"),
      yOffset = 0,
      triggerPoint = 150;
      $(window).scroll(function(){
        yOffset = $(window).scrollTop();

        if(yOffset >= triggerPoint){
          header.addClass("minimized");
        }else{
          header.removeClass("minimized");
        }
      });
     });
    </script>
  </body>
</html>
