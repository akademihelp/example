<?php
include 'inc/config.php';

$page           = 1; // Başlangıç değeri olarak sayfa numarasını 1 yapıyoruz.
$limit          = 4; // Her sayfada kaç veri gösterileceğini belirliyoruz.
if (isset($_GET['page'])){
    $page       = $_GET['page']; // Başka bir sayfaya tıklandıysa 1. sayfa yerine o sayfayı çektireceğiz.
}

// Ses Dosyasını Dinleme İşlemi
if(isset($_GET['download'])){
    $url    = 'https://api.bulutfon.com/call-records/'.$_GET['download'].'/stream?';
    header('Location: '.$url . http_build_query(array(
            'access_token' => $token,
        )));
}

$url            = 'https://api.bulutfon.com/cdrs?limit='.$limit.'&page='.$page.'&access_token='.$token;
$curl           = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$curl_response  = curl_exec($curl);
curl_close($curl);
$result         = json_decode($curl_response, true);
$cdrs           = $result['cdrs']; // Arama kayıtlarını değişkene atıyoruz.
$pages          = $result['pagination']; // Sayfalama ve diğer bilgileri pages değişkenine atıyoruz.


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulutfon Api CDR</title>
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900,900italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <!-- Main CSS -->
    <link href="css/main.css" rel="stylesheet">
</head>
<body>
<div id="container">
    <table>
        <tr>
            <th>Arayan</th>
            <th>Aranan</th>
            <th>Yönü</th>
            <th>Arama Tipi</th>
            <th>Tarih</th>
            <th>Ses Kaydı</th>
            <th>Kapanma</th>
        </tr>
        <?php
        foreach($cdrs as $cdr){
        ?>
        <tr>
            <td><?php echo $cdr['caller']; ?></td>
            <td><?php echo $cdr['callee']; ?></td>
            <?php
            if ($cdr['direction'] == "IN")
                echo '<td class="in">Gelen</td>';
            else
                echo '<td class="out">Giden</td>';
            ?>
            <td><?php echo $cdr['bf_calltype']; ?></td>
            <td><?php echo $cdr['call_time']; ?></td>
            <td>
                <?php
                if ($cdr['call_record']=="Var")
                    echo '<a target="_blank" href="?download='.$cdr['uuid'].'">İndir</a>';
                else
                    echo 'Yok';
                ?>
            </td>
            <td><?php echo $cdr['hangup_cause']; ?></td>
        </tr>
        <?php } ?>

    </table>
    <!-- Gelen verilere göre sayfalama yapıyoruz. -->
    <div class="pagination">
        <?php
        for ($i=1;$i<=$pages['total_pages'];$i++){
            if ($i==$page)
                echo '<a class="current">'.$i.'</a>';
            else
                echo '<a href="?page='.$i.'">'.$i.'</a>';
        }
        ?>
    </div>
</div>
</body>
</html>