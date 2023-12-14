<?php 
include "conn.php";

//Funktion um die ID und Bilder zu fetchen und in ein Array zu packen.
function getImages($conn) {
    //SQL Query wird definiert und ausgeführt.
    $query = "SELECT * FROM `content`";
    $stmt = $conn->prepare($query);
    $stmt->execute();
 
    //Array wird erstellt.
    $images = array();
    //Die ID und die Bilddaten werden in das Array gepackt.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $images[$row['ID']] = 'data:image/jpeg;base64,' . base64_encode($row['image']);
    }
    return $images;
 }
 

//Submit Abfragen
if(isset($_POST['hochladen'])) {
    // die datei wird gefetcht.
    $file = $_FILES['fileToUpload'];
    $target_fileType = substr($file["type"], strpos($file["type"], "/")+1);

    // Die Datei in die datenbank hochladen, wenn die Datei eine jpeg, jpg, png, gif und nicht über 500mb gross ist.

    if(($file['error'] === 0) && ($target_fileType == "jpg") || ($target_fileType == "png") || ($target_fileType == "jpeg") || ($target_fileType == "gif") && ($file["size"] < 500000)) {
        // Die Bilddatei vorbereiten
        $image = file_get_contents($file['tmp_name']);
        // Das SQL Query wird gesetzt und vorbereitet.
        $query = "INSERT INTO `content` (image) VALUES (?)";
        $stmt = $conn->prepare($query);
        // Der SQL Query wird ausgeführt.
        $stmt->execute([$image]);
    }
}
if((isset($_POST['delete'])) && (isset($_POST['imgToDelete']))){
    // Bilder, welche zu löschen sind, werden gefetcht
    $array = $_POST['imgToDelete']; 
    //Schlaufe, pro ausgewähltes Bild, ein Query zum löschen vom Bild vorbereiten und dann ausführen.
    for($i = 0; $i < sizeof($array); $i++){
        $query = "DELETE FROM content WHERE `content`.`ID` = $array[$i]";
        $stmt = $conn->prepare($query);
        $stmt->execute();
    }
}
?>

<!doctype html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bildergalerie</title>
        <style>
            body{
                background: linear-gradient(to left top, black, white);
                max-width: 1250px;
                margin: auto;
                padding: 10px;
                font-family: Arial, Helvetica, sans-serif;
            }
            .image-gallery {
                text-align:center;
            }
            .image-gallery img {
                padding: 3px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
                border: 1px solid #FFFFFF;
                border-radius: 4px;
                margin: 20px;
            }
            #displayed-image {
                width: 300px;
                height: 300px;
            }
            #div1{
                text-align: center;
                width: auto;
                height: auto;
                margin-left: auto;
                margin-right: auto;
            }
            .imageclass {
                border: 2px solid grey;
                margin: 3px 3px 3px 3px;
            }
            #title {
                text-align: center;
                color: black;
            }
            #text {font-size: 150%;}
            .image-container{
                text-align: center;
                width: 100%;
            }
            .image-container img {
                width: 25%;
                height: auto;
            }
            .image-container .btn {
                position:relative;
                transform: translate(-156%, -44%);
            }
            .image-container .btn:hover {
                background-color: black;
            }
        </style>
    </head>
    <body>
        <form action="#" method="post" enctype="multipart/form-data">
            <div id="div1">
                <p id="text">Datei zum Hochladen auswählen:</p>
                <input type="file" name="fileToUpload" id="fileToUpload"> <br><br>
                <input type="submit" value="hochladen" name="hochladen">
                <input type="submit" value="löschen" name="delete">
            </div>

            <br>
            <br>

            <div id="title">
                <h3 style="font-size: 300%; margin: 4px 4px 4px 4px;">Bildergalerie</h3>
                <br>
            </div>
          
            <div class="image-container">
                <div style="position:sticky">
                    <?php
                    // bilder holen und in ein Array $images speichern
                    $images = getImages($conn);
                    // alle bilder anzeigen
                    foreach ($images as $id => $image) {
                        echo '<img class="imageclass" src="' . $image . '" alt="bild" width="300" height="300">';
                        echo '<input type="checkbox" value="' . $id . '" class="btn" name="imgToDelete[]">';
                    }
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>

<?php 
$conn = NULL;
?>