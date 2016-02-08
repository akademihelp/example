<?php
/*
* Bulutfon Api
* SMS Gönderme
*/
$token    = ""; // Bulutfon panelinden alcağınız master token
$title    = "TUNC"; // Bulutfon üzerinden onaylattığınız sms başlığı

if ($_POST){ // Formun post edilip edilmediğinin kontrolü
  $receivers    = $_POST['receivers']; // Formdan gelen alıcı listesi
  $message      = $_POST['message']; // Formdan gelen mesaj alanı
  if ($receivers == "" || $message == ""){ // Formdan gelen verilerin boş olup olmadığını kontrol ediyoruz.
    echo "Lütfen tüm alanları doldurunuz.";
  } else {
    $ch = curl_init();  // Curl oturumunu başlattık 
    curl_setopt($ch,CURLOPT_URL,'https://api.bulutfon.com/messages'); // SMS gönderimi için kullanacağımız api adresi
    curl_setopt($ch,CURLOPT_POST, 1); // Burada curl post kullanacagımızı belirttik 1 yerine  true de denebilir
    curl_setopt($ch,CURLOPT_POSTFIELDS,'title='.$title.'&access_token='.$token.'&receivers='.$receivers.'&content='.$message); //  Burada ise göndereceğimiz parametreleri belirtiyoruz.
    curl_exec($ch); // Curl calıştır.
    curl_close($ch); // Curl oturumunu kapat
  }
}
?>
<html>
<head>
  <title>Bulutfon SMS Gönderme</title>
</head>
<body>
  <!-- Form -->
  <form action="#" method="post">
    <table>
      <tr>
        <td valign="top" width="150">Alıcı Listesi</td>
        <td>
          <input type="text" name="receivers"><br>
          <small>Birden fazla alıcı girmek için araya virgül (,) işareti koyunuz. Lütfen numaraları ülke kodu ile birlikte giriniz ör: 908508850000</small>
        </td>
      </tr>
      <tr>
        <td valign="top">Mesaj</td>
        <td>
          <textarea name="message"></textarea>
        </td>
      </tr>
      <tr>
        <td></td>
        <td><button type="submit">Gönder</button></td>
      </tr>
    </table>
  </form>
</body>
</html>