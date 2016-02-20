<?php
/*
 * Bulutfon Api
 * Gelen Faxlar
 */
include 'inc/config.php';

// Dosyayı base64 kodlama biçimine dönüştürmemiz için gerekli yardımcı fonksiyon
function prepareFaxAttachment($path) {
    $type = mime_content_type($path);
    $basename = basename($path, pathinfo($path, PATHINFO_EXTENSION));
    $data = file_get_contents($path);
    $base64 = 'data:'. $type . ';name:'. $basename .';base64:' . base64_encode($data);
    return $base64;
}

if (@$_POST){
    $title          = $_POST['title']; // Formdan gelen fax başlığı
    $receivers      = $_POST['receivers']; // Formdan gelen alıcı listesi
    $did            = $_POST['did']; // Formdan gelen gönderilen numara
    
    $dir            = 'files/'; // Dosyamızın kopyalanacağı dizin. Bu dizinin izinleri 777 olacak şekilde ayarlanmalıdır. 
    $file           = $dir.basename($_FILES['file']['name']); // Kopyalama işlemi öncesi gelen dosyanın nereye hangi isimle kayıt olacağını belirliyoruz.
    if (move_uploaded_file($_FILES['file']['tmp_name'],$file)){ // Kopyalam işlemi
        $file       = prepareFaxAttachment($file); // Base64 dönüşümü
    } else {
        die("Dosya aktarımı sırasında hata oluştu.");
    }
    $url            = 'https://api.bulutfon.com/outgoing-faxes?access_token='.$token; // Fax gönderme işlemini yapacağımız servis
    // Elimizdeki verileri bir dizi değişkene aktararak post etmeye hazır hale getiriyoruz.
    $data           = array("title" => $title, "receivers" => $receivers, "did" => $did, "attachment" => $file);
    $data_string    = json_encode($data); // Bu dataları JSON formatına çeviriyoruz.
    $curl           = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );
    $curl_response = curl_exec($curl);
    curl_close($curl);
    $result     = json_decode($curl_response, true);
    var_dump($result);
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fax Gönder</title>
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900,700,600,300,200&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

    <!-- Main CSS -->
    <link href="css/main.css" rel="stylesheet">
    <!-- Font CSS -->
    <link href="css/fonts.css" rel="stylesheet">
</head>
<body>
<div class="title">Bulutfon Api Fax Gönderme</div>
<!-- Container -->
<div id="container">
    <form action="#" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Fax Başlığı">
        <small>Gönderilecek faxın başlığı.</small>

        <input type="text" name="did" placeholder="Gönderilecek Numara">
        <small>Faxın gönderileceği numara.</small>

        <input type="text" name="receivers" placeholder="Alıcılar">
        <small>Virgül (,) ile ayrılarak birden fazla alıcı girilebilir.</small>

        <input type="file" name="file">
        <small>Göndermek istediğiniz dosyayı seçin.</small>

        <button type="submit">Gönder <i class="flaticon-arrow487"></i></button>
    </form>
</div>
<!-- Container Bitiş -->
</body>
</html>