<?php
/*
 * Bulutfon Api
 * Gelen Faxlar
 */
include 'inc/config.php';

$url    = 'https://api.bulutfon.com/outgoing-faxes/'.$_GET['id'].'?access_token='.$token;
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$curl_response = curl_exec($curl);
curl_close($curl);
$result = json_decode($curl_response, true);
$fax = $result['fax'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gönderilen Faxlar</title>
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900,700,600,300,200&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

    <!-- Main CSS -->
    <link href="css/main.css" rel="stylesheet">
    <!-- Font CSS -->
    <link href="css/fonts.css" rel="stylesheet">
</head>
<body>
<!-- Container -->
<div id="detail">
    <h3><?php echo $fax['title']; ?></h3>
    <small>
        <?php
        $yeni   = strtotime($fax['created_at']);
        $son    = date('Y-m-d H:i:s', $yeni);
        echo 'Fax '.$fax['did'].' üzerinden '.$son.' tarihinde gönderildi.';

        ?>
    </small>

    <div>Alıcı <span>Durum</span></div>
    <ul>
        <?php
        foreach ($fax['recipients'] as $rec) {
            if ($rec['state']=="SENT")
                echo '<li>'.$rec['number'].' <span class="sent">GÖNDERİLDİ</span></li>';
            else
                echo '<li>'.$rec['number'].' <span class="error">GÖNDERİLEMEDİ</span></li>';
        }
        ?>
    </ul>
</div>
</body>
</html>