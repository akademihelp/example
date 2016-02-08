<?php
/*
* Bulutfon Api
* Dahili Listesi
*/
$token      = ""; // Bulutfon panelinden alcağınız master token
$url        = 'https://api.bulutfon.com/extensions?access_token='.$token;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//execute the session
$curl_response = curl_exec($curl);
//finish off the session
curl_close($curl);
$result = json_decode($curl_response, true);
$extensions = $result['extensions'];


function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
  $url = 'https://s.gravatar.com/avatar/';
  $url .= md5( strtolower( trim( $email ) ) );
  $url .= "?s=$s&d=$d&r=$r";
  if ( $img ) {
    $url = $url . '"';
    foreach ( $atts as $key => $val )
      $url .= ' ' . $key . '="' . $val . '"';

  }
  return $url;
}

?>
<html>
<head>
  <title>Bulutfon Dahili Görüntüleme</title>
  <link href='https://fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic,900,900italic,300italic,300,100italic,100' rel='stylesheet' type='text/css'>
  <style>

    html,body {
      background: #c1dae9;
      width: 100%;
      height: 100%;
    }
    .title {
      width: 270px;
      margin: 0 auto;
      font-family: Lato,sans-serif;
      color:#fff;
      text-align: center;
      padding:10px;
    }
    #extensions {
      width: 270px;
      padding:20px;
      background-color: #3d3f4b;
      -webkit-border-radius: 15px;
      -moz-border-radius: 15px;
      border-radius: 15px;
      margin:0 auto;
    }
    .extension {
      margin-bottom:45px;
    }
    .extension:last-child {
      margin-bottom: 20px;
    }
    .extension img {
      width: 50px;
      height: 50px;
      -webkit-border-radius: 100%;
      -moz-border-radius: 100%;
      border-radius: 100%;
      border:3px solid #64666e;
      float: left;
    }
    .extension .name {
      color:#fff;
      font-family: Lato,sans-serif;
      padding:5px 0 0 70px;
    }
    .extension .name span {
      display: block;
      font-weight: 400;
      color:#8a8d97;
      font-size:11px;
      padding-top:7px;
      text-indent:20px;
      position: relative;
    }
    .extension .name span:before {
      position: absolute;
      content:'';
      width: 10px;
      height: 10px;
      -webkit-border-radius: 100%;
      -moz-border-radius: 100%;
      border-radius: 100%;
      left:0px;
      top:9px;
    }
    .extension .name .online:before {
      background: #80b969 ;
    }
    .extension .name .offline:before {
      background: #c66747;
    }
  </style>
</head>
<body>
  <div class="title">DAHİLİ LİSTESİ</div>
  <div id="extensions">
    <?php
    foreach($extensions as $extension){
    ?>
    <div class="extension">
      <img src="<?php echo get_gravatar($extension['email']); ?>">
      <div class="name">
        <?php
          echo $extension['caller_name'];
          if ($extension['registered']){
            echo '<span class="online">' . $extension['number'] . ' - ' . $extension['email'] . '</span>';
          } else {
            echo '<span class="offline">' . $extension['number'] . ' - ' . $extension['email'] . '</span>';
          }

        ?>

      </div>
    </div>
    <?php } ?>
  </div>
</body>
</html>