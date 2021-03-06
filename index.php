<?php
include("conn.php");
$getir = new IPTVClass();

$getir->funcControl('shell_exec');
$getir->funcControl('exec');
$getir->funcControl('system');


$getir->Head("IPTV Player");
$getir->Style();

if(!isset($_GET['git'])) {
$sayfa = 'index';
} elseif(empty($_GET['git'])) {

if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2) == "tr") {
$getir->Error("Sayfa Bulunamadı");
} else {
$getir->Error("Page Not Found");
}

} else {
$sayfa = strip_tags($_GET['git']);
}

switch ($sayfa) {

case 'postlgn':
if($_POST) {

$name = strip_tags($_POST["mail"]);
$pass = sha1(md5($_POST['pass']));

$query  = $db->query("SELECT * FROM admin_list WHERE admin_usrname =" . $db->quote($name) . " AND admin_passwd = " . $db->quote($pass). "",PDO::FETCH_ASSOC);
if ( $say = $query -> rowCount() ){
if( $say > 0 ){
echo('<script>document.cookie = "user_id='.strip_tags($_POST["mail"]).'";
location.replace("index.php?git=iptv")</script>');
}

} else {
echo('<script>location.replace("index.php")</script>');
}

} else {
  $getir->Error("Non-POST");
}
break;

case 'index':
echo '<style>
@media (max-width:800px) {
body {
  background-image: url("https://source.unsplash.com/1080x1920/?turkey,türkiye,atatürk,şehir,manzara,deniz");
  background-repeat: no-repeat;
}
.manzara {
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0, 0.4); /* Black w/opacity/see-through */
  color: white;
  font-weight: bold;
  text-align: center;
  padding: 15px;
}
.form-control {
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0, 0.4); /* Black w/opacity/see-through */
  width: 100%;
}
}
@media (min-width:800px) {
body {
  background-image: url("https://source.unsplash.com/1920x1080/?turkey,türkiye,atatürk,şehir,manzara,deniz");
  background-repeat: no-repeat;
}
.manzara {
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0, 0.4); /* Black w/opacity/see-through */
  color: white;
  box-shadow: 3px solid #f1f1f1;
  z-index: 2;
  position: absolute;
  text-align: center;
  top: 50%;
  font-weight: bold;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
}
.form-control {
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0, 0.4); /* Black w/opacity/see-through */
  width: 100%;
}

.container  {
border-radius: 20px;
}

}
</style>

<body class="container mx-auto text-center">
<div class="manzara">
    <form class="container form-signin" action="index.php?git=postlgn" method="post">
	<br><br>
  <h1 class="h3 mb-3 font-weight-normal">IPTV Panel By AliCan</h1>
  <br>
  <label for="inputEmail" class="sr-only">Username</label>
  <input type="text" name="mail" class="form-control" placeholder="Username" required autofocus>
  <br>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" name="pass" class="form-control" placeholder="Password" required>
  <br>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button><br>
  <a class="btn btn-lg btn-danger btn-block" href="index.php?git=resetpass">Reset Password</a>
  <p class="mt-5 mb-3 text-muted">&copy; 2019-2020</p>
</form></div>
</body>';
break;

case 'resetpass':
echo '<body class="container mx-auto text-center">
<br>
<div class="card-body">
<form data-role="validator" class="form-signin" action="index.php?git=preset" method="post">
<label for="inputPassword" class="sr-only">E-Mail</label>
<input type="text" name="email" class="form-control" placeholder="E-Mail" required><br>
<label for="inputEmail" class="sr-only">Token</label>
<input type="password" data-role="keypad" name="token" class="form-control" placeholder="Token" required><br>
<button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
  <br>
</form>
</div>
</body>';
break;

case 'preset':
if(isset($_POST["token"]) && isset($_POST["email"])) {
$email = $_POST["email"];
$token = sha1(md5($_POST["token"]));
$stmt = $db->query("SELECT * FROM admin_list WHERE admin_email = ".$db->quote(strip_tags($email))." AND admin_token = ".$db->quote(strip_tags($token))."");
if ($stmt->rowCount() > 0) {
$str = "0123456789";
$str = str_shuffle($str);
$str = substr($str, 0, 5);
$password = sha1(md5($str));
$db->query("UPDATE admin_list SET admin_passwd = ".$db->quote(strip_tags($password))." WHERE admin_email = ".$db->quote(strip_tags($email))."");
echo '<body class="container">
<br>
<div class="card-body">
<h4>Yeni şifren: '.$str.'</h4>
<hr></hr>
<p>NOT : Şifrenizi Kopyalayın ve <b>BİR YERE KAYDEDİN!</b></p>
<br>
<a class="btn btn-lg btn-primary btn-block" href="index.php">Index</button>
</div></body>';
exit();
 } else {
echo '<body class="container">
<br>
<div class="card-body">
<b>Lütfen link yapınızı kontrol ediniz! / Please control link type</b>
<a class="btn btn-lg btn-primary btn-block" href="index.php">Index</button>
</div></body>';
exit();
}

} else {
echo '<body class="container">
<br>
<div class="card-body">
<b>Something a fault! / Bir şeyler hatalı!</b>
</div></body>';
exit();
    }

break;

case 'iptv':
$getir->logincheck();

$stmt = $db->prepare('SELECT * FROM admin_list WHERE admin_usrname = :iddegeri');
$stmt->execute(array(':iddegeri' => $_COOKIE["user_id"]));
while($row = $stmt->fetch()) {
if(strip_tags($row["admin_yetki"]) == "admin") {
$_SESSION["yetki"] == "admin";
echo('<script>document.cookie = "yetki='.md5("admin").'";</script>');
} elseif(strip_tags($row["admin_yetki"]) == "sus") {
die("Hesabınız Bloklanmıştır");
} elseif($_COOKIE["yetki"] == md5("gold")) { 
echo('<script>document.cookie = "yetki='.md5("gold").'";</script>');
} else {
$_SESSION["yetki"] == "uye";
echo('<script>document.cookie = "yetki='.md5("uye").'";</script>');
}
if(isset($_COOKIE["yetki"])) {
} else {
echo '<script>location.reload();</script>';
}

}
?>
<script language="JavaScript" type="text/javascript">
function delpost(id)
{
  if (confirm("Silmek istediğinize emin misiniz ?\n" + "ID :" + id + ""))
  {
      window.location.href = 'index.php?git=deleteiptv&id=' + id;
  }
}
function delpost2(id)
{
  if (confirm("Silmek istediğinize emin misiniz ?\n" + "Name :" + id + ""))
  {
      window.location.href = 'index.php?git=dfile&name=' + id;
  }
}
</script>
<?php
$getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
if(isset($_GET["phpinfo"])) {
if($_COOKIE["yetki"] == md5("uye")) {
die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
} else {
}
die('
<br><br>
<body>
<table class="container table">
  <tr>
    <th>PHP Version:</th>
    <td>'.phpversion().'</td>
  </tr>
  <tr>
    <th>User-Agent:</th>
    <td>'.strip_tags($_SERVER['HTTP_USER_AGENT']).'</td>
  </tr>
  <tr>
    <th>Accept-Language:</th>
    <td><div class="kisalt">'.strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']).'</div></td>
  </tr>
  <tr>
    <th>Cookie :</th>
    <td><div class="kisalt">'.strip_tags($_SERVER['HTTP_COOKIE']).'</div></td>
  </tr>
  <th>Server Software :</th>
  <td><div class="kisalt">'.strip_tags($_SERVER['SERVER_SOFTWARE']).'</div></td>
</tr>
<th>System Root :</th>
<td><div class="kisalt">'.strip_tags($_SERVER['SystemRoot']).'</div></td>
</tr>
  <tr>
  <th>Document Root :</th>
  <td><div class="kisalt">'.strip_tags($_SERVER['DOCUMENT_ROOT']).'</div></td>
</tr>
<tr>
  <th>Server Post:</th>
  <td><div class="kisalt">'.strip_tags($_SERVER['SERVER_PORT']).'</div></td>
</tr>
<tr>
<th>Request :</th>
<td><div class="kisalt">'.strip_tags($_SERVER['SERVER_PROTOCOL']).'</div></td>
</tr>
</table>
</body>');
} else {

}
echo '<body>';
if($_COOKIE["yetki"] == md5("uye")) {
} else {
echo '
<br><br>
<div class="container h-50 p-4" style="width:90%;">
<button onclick="javascript:location.reload();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Refresh</button>
<br><br>
<b>M3U8 File List</b>';
$_DIR = opendir("m3u");
$_DIR2 = opendir("log");
echo '
<table class="table">
<thead>
<tr>
<th>Filename</th>
<th></th>
</tr></thead><tbody>
';
while (($_DIRFILE = readdir($_DIR)) !== false){
if(!is_dir($_DIRFILE)){
echo '<tr><td><div class="kisalt"><span class="mif-file-empty"></span> '.strip_tags($_DIRFILE).'</td>';
echo '<td><a class="btn btn-danger" href="index.php?git=dfile&name='.strip_tags($_DIRFILE).'">Delete</a></td></tr>';
}
}
closedir($_DIR);

echo '</tbody></table>';
}
if($_COOKIE["yetki"] == md5("uye")) {
} else {
echo '<table class="table container">
<thead>
<tr>
<th>Log Filename</th>
<th></th>
</tr></thead><tbody>
';
while (($_DIRFILE2 = readdir($_DIR2)) !== false){
if(!is_dir($_DIRFILE2)){
echo '<tr><td><div class="kisalt"><span class="mif-file-empty"></span> '.strip_tags($_DIRFILE2).'</td>';
echo '<td><a class="btn btn-danger"  href="index.php?git=dlog&name='.strip_tags($_DIRFILE2).'">Delete</a></td></tr>';
}
}
closedir($_DIR2);

echo '</tbody></table>';
}

echo '<div class="mt-5 container">
<b>Private IPTV List</b>
<table class="table">
<thead>
<tr>
<th>ID</th>
<th>Type</th>
<th>Status</th>
<th></th>
<th></th>
</tr></thead><tbody>';
$stmt2 = $db->prepare('SELECT * FROM private_iptv');
$stmt2->execute();
while($row2 = $stmt2->fetch()) {
if(strip_tags($row2["private_sahip"]) == strip_tags($_COOKIE["user_id"])) {
echo '<tr><td><div class=kisalt">'.strip_tags($row2["private_id"]).'</div></td>';
echo '<td><div class="kisalt">'.strip_tags($row2["private_name"]).'</div></td>';

if(strip_tags($row["private_active"]) == "0") {
  echo '<td><div class="progress kisalt">
  <a data-text="Panelden Kapalı" class="progress-bar bg-warning" role="progressbar" data-text="Online" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></a>
</div></td>';
} else {
  echo '<td>
  <div class="progress">
  <a data-text="Açık" class="progress-bar bg-success" role="progressbar" data-text="Offline" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></a>
</div></td>';
}
  echo '
  <td><a class="btn btn-danger" href="index.php?git=watch&id='.strip_tags($row2["private_id"]).'" target="_blank">Watch</a></td>
  <td><a class="btn btn-danger" href="index.php?git=deleteprivip&id='.strip_tags($row2["private_id"]).'" target="_blank">Delete</a></td>';
} else {
}
}

echo '</tbody></table></div>

<div class="mt-5 container">
<b>IPTV List</b>

<table class="table table-responsive">
<thead>
<tr>
<th>ID</th>
<th>Type</th>
<th>Name</th>
<th>Status</th>
<th></th>
<th></th>
</tr></thead><tbody>';

$stmt2 = $db->prepare('SELECT * FROM public_iptv');
$stmt2->execute();
while($row2 = $stmt2->fetch()) {
if(strip_tags($row2["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
echo '<tr><td><div class=kisalt">'.strip_tags($row2["public_id"]).'</div></td>';
if(strip_tags($row2["video_stream"]) == "0") {
  echo '<td>Live</td>';
} else {
  echo '<td>Video</td>';
}
echo '<td><div class="kisalt">'.strip_tags($row2["public_tslink"]).'</div></td>';

if(strip_tags($row["public_active"]) == "0") {
echo '<td><b>Off</b></td>';
} else {
echo '<td><b>On</b></td>';
}

echo '<td><div class="btn-group">
  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
	IPTV
  </button>
  <ul class="dropdown-menu" role="menu">';
if($_COOKIE["yetki"] == md5("uye")) {
} else {
echo '<li><a class="dropdown-item" target="_blank" href="../watch.php?pubid='.strip_tags($row2["public_name"]).'&live='.strip_tags($row2["video_stream"]).'">M3U8 Link</a></li>
<li><a class="dropdown-item" target="_blank" href="../watch.php?pubid='.strip_tags($row2["public_name"]).'&live='.strip_tags($row2["video_stream"]).'&debug">Debug</a></li>';
}
echo '<li><a class="dropdown-item" target="_blank" href="../watch.php?pubid='.strip_tags($row2["public_name"]).'&live='.strip_tags($row2["video_stream"]).'&watchplayer=1">Watch</a></li>
    <li><a class="dropdown-item" target="_blank" href="../watch.php?pubid='.strip_tags($row2["public_name"]).'&live='.strip_tags($row2["video_stream"]).'&selcuk=1">SelcukWatch</a></li>';
echo '</ul></div></td>

<td><div class="btn-group">
<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
<span class="caret"></span>
<span class="sr-only">Toggle Dropdown</span>Other</button>
<ul class="dropdown-menu" role="menu">';
if($_COOKIE["yetki"] == md5("uye")) {
echo '<li><a class="dropdown-item" target="_blank" href="index.php?git=startcst1&id='.intval($row2["public_id"]).'">Start Custom Stream</a></li>';
} elseif($_COOKIE["yetki"] == md5("gold")) {
} else  {
echo '<li><a class="dropdown-item" target="_blank" href="index.php?git=getlog&id='.intval($row2["public_id"]).'">Logs</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=startrestream&id='.intval($row2["public_id"]).'">Start Restream.IO Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=startfacebook&id='.intval($row2["public_id"]).'">Start Facebook Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=starttwitch&id='.intval($row2["public_id"]).'">Start Twitch Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=startyt&id='.intval($row2["public_id"]).'">Start YouTube Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=startig&id='.intval($row2["public_id"]).'">Start Instagram Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=startcst1&id='.intval($row2["public_id"]).'">Start Custom Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=startrtmp&id='.intval($row2["public_id"]).'">Start RTMP Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=startiptv&id='.intval($row2["public_id"]).'">Start Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=stopiptv&id='.intval($row2["public_id"]).'">Stop Stream</a></li>';
}
echo '<li><a class="dropdown-item" target="_blank" href="index.php?git=editpubid&id='.strip_tags($row2["public_id"]).'">Edit Stream</a></li>
<li><a class="dropdown-item" target="_blank" href="index.php?git=deletepubid&id='.strip_tags($row2["public_id"]).'">Delete Stream</a></li>
</ul>
</div></td>';
} else {
}
}
echo '</tr></tbody></table></div>';
echo '
<div class="mt-5 container">
<b>IPTV Config</b>
<table class="table">
<thead>
<tr>
<th>ID</th>
<th>M3U8 Config</th>
<th>TS Config</th>
<th></th>
</tr></thead><tbody>';
$stmt3 = $db->prepare('SELECT * FROM iptv_config WHERE sahip = :iddegeri');
$stmt3->execute(array(':iddegeri' => $_COOKIE["user_id"]));
while($row2 = $stmt3->fetch()) {
echo '<tr><td><div class=kisalt">'.intval($row2["config_id"]).'</div></td>';
echo '<td><div class="kisalt">'.strip_tags($row2["ffmpeg_m3u8cfg"]).'</div></td>';
echo '<td><div class="kisalt">'.strip_tags($row2["ffmpeg_ts"]).'</div></td>';
echo '<td><a class="btn btn-danger" target="_blank" href="index.php?git=editcfg&id='.intval($row2["config_id"]).'">Edit</a></td>';
}
echo '</tr>
</tbody></table></div></div></div></body>';
  break;
  

  case 'editcfg':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
  die("<center class='mt-5'>Daha fazla seçenek için üyeliğinizi yükseltin</center>");
  } else {
      
  }
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  $stmt = $db->prepare('SELECT * FROM iptv_config WHERE config_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
  if($row["sahip"] == $_COOKIE["user_id"]) {
	  echo '<body>
  <form class="container" action="index.php?git=peditcfg" method="post">
  
  		    <div class="form-group">
      <label for="exampleFormControlInput1">Logo</label>
	  <textarea class="form-control" name="logokayit" placeholder="Logo">'.$row["logo"].'</textarea>
    </div>
	
		<div class="form-group">
      <label for="exampleFormControlInput1">RTMP Port</label>
	  <textarea class="form-control" name="rtmpport" placeholder="RTMP Port">'.$row["rtmp_port"].'</textarea>
    </div>
	
    <div class="form-group">
      <label for="exampleFormControlInput1">Config TS</label>
	  <textarea class="form-control" name="ffmpegts" placeholder="IPTV Config(TS)">'.$row["ffmpeg_ts"].'</textarea>
    </div>
    <div class="form-group">
      <label for="exampleFormControlInput1">Config M3U8</label>
	  <textarea class="form-control" name="ffmpegm3u8" placeholder="IPTV Config(M3U8)">'.$row["ffmpeg_m3u8cfg"].'</textarea>
    </div>
	
			<div class="form-group">
      <label for="exampleFormControlInput1">Config FLV</label>
	  <textarea class="form-control" name="ffmpegflv" placeholder="IPTV Config(FLV)">'.$row["ffmpeg_flv"].'</textarea>
    </div>
	    <div class="form-group">
      <label for="exampleFormControlInput1">Twitter Token</label>
	  <textarea class="form-control" name="twittertoken" placeholder="Twitter Token">'.$row["twitter_tkn"].'</textarea>
    </div>
	
		<div class="form-group">
      <label for="exampleFormControlInput1">Facebook Token</label>
	  <textarea class="form-control" name="facebooktkn" placeholder="Facebook Token">'.$row["facebook_tkn"].'</textarea>
    </div>
			<div class="form-group">
      <label for="exampleFormControlInput1">Youtube Token</label>
	  <textarea class="form-control" name="youtubetkn" placeholder="YouTube Token">'.$row["youtube_tk"].'</textarea>
    </div>
    
    	<div class="form-group">
      <label for="exampleFormControlInput1">Instagram Token</label>
	  <textarea class="form-control" name="instagramtk" placeholder="Instagram Token">'.$row["instagram_tk"].'</textarea>
    </div>
		    <div class="form-group">
      <label for="exampleFormControlInput1">Twitch Token</label>
	  <textarea class="form-control" name="twitchtoken" placeholder="Twitch Token">'.$row["twitch_tkn"].'</textarea>
    </div>
	
			    <div class="form-group">
      <label for="exampleFormControlInput1">Restream Token</label>
	  <textarea class="form-control" name="restreamtoken" placeholder="Restream.IO Token">'.$row["restream_tkn"].'</textarea>
    </div>
	
      <input type="hidden" name="ffmpegid" value="'.intval($row["config_id"]).'" class="form-control">
    <button type="submit" style="width: 100%;" class="btn btn-primary">Guncelle</button><br><br>
  </form>
  </body>';
  } else {
  die("NO");
  }
  }
  break;
  
  case 'peditcfg':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  $stmt = $db->prepare('SELECT * FROM iptv_config WHERE config_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
  if($row["sahip"] == $_COOKIE["user_id"]) {
  } else {
  die("NO");
  }
  }
  $update = $db->prepare("UPDATE iptv_config SET ffmpeg_m3u8cfg = :m3u8, rtmp_port = :rtmpport, logo = :logos, ffmpeg_ts = :ts, twitter_tkn = :twittertkn, restream_tkn = :restreamtkn, facebook_tkn = :facebooktkn, ffmpeg_flv = :flv, twitch_tkn = :twitchtkn, youtube_tk = :youtubetk, instagram_tk = :instagramtk WHERE config_id = :gonderid");
  $update->bindValue(':gonderid', intval($_POST["ffmpegid"]));
  $update->bindValue(':m3u8', strip_tags($_POST["ffmpegm3u8"]));
  $update->bindValue(':flv', strip_tags($_POST["ffmpegflv"]));
  $update->bindValue(':ts', strip_tags($_POST["ffmpegts"]));
  $update->bindValue(':twittertkn', strip_tags($_POST["twittertoken"]));
  $update->bindValue(':facebooktkn', strip_tags($_POST["facebooktkn"]));
  $update->bindValue(':youtubetk', strip_tags($_POST["youtubetkn"]));
  $update->bindValue(':instagramtk', strip_tags($_POST["instagramtk"]));
  $update->bindValue(':twitchtkn', strip_tags($_POST["twitchtoken"]));
  $update->bindValue(':restreamtkn', strip_tags($_POST["restreamtoken"]));
  $update->bindValue(':rtmpport', strip_tags($_POST["rtmpport"]));
  $update->bindValue(':logos', strip_tags($_POST["logokayit"]));
  $update->execute();
  if($update){
	 echo "<script LANGUAGE='JavaScript'>
    window.alert('Updated');
    window.location.href='index.php?git=iptv';
    </script>";
	} else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Not Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  break;
  
  case 'deleteprivip':
  $getir->logincheck();
  $stmt = $db->prepare('DELETE FROM private_iptv WHERE private_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  while($row2 = $stmt->fetch()) {
      if(strip_tags($row2["private_sahip"]) == strip_tags($_COOKIE["user_id"])) {
          
      } else {
          die("<script LANGUAGE='JavaScript'>
    window.alert('Not Updated');
    window.location.href='index.php?git=iptv';
    </script>");
    }
  }
  if($row = $stmt->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Not Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  break;
  

  case 'startiptv':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
  if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configts);
	} else {
    $getir->StartTSStream(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configts);
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartM3U8StreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configm3u8);
	} else {
    $getir->StartM3U8Stream(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configm3u8);
	}
  }
      } else {
      die("NO");
      }
}
  break;
  
   case 'startfacebook':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
  if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartFacebookTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($facebooktk));
	} else {
	$getir->StartFacebookTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($facebooktk));
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartFacebookTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($facebooktk));
	} else {
    $getir->StartFacebookTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($facebooktk));
	}
  }
  } else {
  die("NO");
  }
}
  break;
  
     case 'starttwitch':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
   if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartTwitchTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($twitchtk));
	} else {
	$getir->StartTwitchTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($twitchtk));
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartTwitchTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($twitchtk));
	} else {
    $getir->StartTwitchTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($twitchtk));
	}
  }
   } else {
   die("NO");
   }
}
  break;
  
       case 'startyt':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
   if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartYouTubeTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	} else {
	$getir->StartYouTubeTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartYouTubeTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	} else {
    $getir->StartYouTubeTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	}
  }
   } else {
       die("NO");
   }
}
  break;
  
         case 'startig':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
   if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartIGTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	} else {
	$getir->StartIGTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartIGTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	} else {
    $getir->StartIGTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($youtubetk));
	}
  }
   } else {
   die("NO");
   }
}
  break;

case 'startcst1':
$getir->logincheck();
if($_COOKIE["yetki"] == md5("uye")) {
die("<center>Daha fazla seçenek için üyeliğinizi yükseltin</center>");
} else {
}
$getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
echo '<body>
<form class="container" action="index.php?git=startcst" method="post">
  
<div class="form-group">
<label for="exampleFormControlInput1">Link</label>
<input class="form-control" name="link" placeholder="Link">
</div>

<div class="form-group">
<label for="exampleFormControlInput1">Token</label>
<input class="form-control" name="token" placeholder="Token">
</div>

<input type="hidden" name="id" value="'.intval($_GET["id"]).'">
<button type="submit" style="width: 100%;" class="btn btn-primary">Gonder</button><br><br>
</form></body>';
break;

case 'startcst':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_POST["id"])));
  if($row = $stmt->fetch()) {
   if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartCstTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($_POST["link"]), strip_tags($_POST["token"]));
	} else {
	$getir->StartCstTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($_POST["link"]), strip_tags($_POST["token"]));
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartCstTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($_POST["link"]), strip_tags($_POST["token"]));
	} else {
    $getir->StartCstTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($_POST["link"]), strip_tags($_POST["token"]));
	}
  }
   } else {
   die("NO");
   }
}
  break;
  
    case 'startrtmp':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
   if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartOtherStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($rtmpport));
	} else {
	$getir->StartOtherStreamLin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($rtmpport));
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartOtherStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($rtmpport));
	} else {
    $getir->StartOtherStreamLin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($rtmpport));
	}
  }
   } else {
   die("NO");
   }
}
  break;
  case 'startrestream':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
   if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartRestreamTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($restreamtk));
	} else {
	$getir->StartRestreamTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($restreamtk));
	}
  } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$getir->StartRestreamTSStreamWin(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($restreamtk));
	} else {
    $getir->StartRestreamTSStreamLinux(strip_tags($row["public_name"]), strip_tags($row["public_tslink"]), strip_tags($row["public_tslink"]), $configflv, strip_tags($restreamtk));
	}
  }
   } else {
   die("NO");
   }
}
  break;
  case 'getlog':
  echo '<script>
  function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
	}
	window.onload = timedRefresh(5000);
</script>';
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
   if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br><br>';
	$myfile = fopen('log/'.strip_tags($row["public_name"]).'-mylog.log', "r") or die("<center><b>Unable to open file!</b></center>");
	echo '<pre style="width:100%;height:100%" class="container form-control">'.fread($myfile,filesize('log/'.strip_tags($row["public_name"]).'-mylog.log'));'</pre>';
  } else {
	echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
	<br><br>';
	$myfile = fopen('log/'.strip_tags($row["public_name"]).'-mylog.log', "r") or die("<center><b>Unable to open file!</b></center>");
	echo '<pre style="width:100%;height:100%" class="container form-control">'.fread($myfile,filesize('log/'.strip_tags($row["public_name"]).'-mylog.log'));'</pre>';
  }
   } else {
   die("NO");
   }
}
  break;
  
  case 'stopiptv':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
  die("NO");
  } else {
  }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
  if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  if($row["video_stream"] == "1") {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	unlink('log/'.strip_tags($row["public_name"]).'-mylog.log');
    $getir->StopFFMPEG();
    } else {
    echo '<button onclick="javascript:history.back();" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Back</button>
    <br>';
	unlink('log/'.strip_tags($row["public_name"]).'-mylog.log');
    $getir->StopFFMPEG();
  }
  } else {
  die("NO");
  }
}
  break;

  case 'editpubid':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  function get_tiny_url($url)  {  
	$ch = curl_init();  
	$timeout = 5;  
	curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
	$data = curl_exec($ch);  
	curl_close($ch);  
	return $data;  
    }
  $stmt = $db->prepare('SELECT * FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
  if(strip_tags($row["public_sahip"]) == strip_tags($_COOKIE["user_id"])) {
  $new_url = get_tiny_url('http://'.$_SERVER["HTTP_HOST"].'/watch.php?pubid='.strip_tags($row["public_name"]).'&live=0&selcuk=1');
  $new_url2 = "Yakında";
  echo '<body>
  <form class="container" action="index.php?git=peditpubid" method="post">
    <div class="form-group">
      <label for="exampleFormControlInput1">IPTV Name</label>
      <input type="text" name="iptvname" value="'.strip_tags($row["public_name"]).'" class="form-control" placeholder="IPTV Name" readonly>
    </div>
		  <div class="form-group">
    <label for="exampleFormControlSelect1">IPTV Type | '.strip_tags($row["video_stream"]).'</label>
    <select class="form-control" name="iptvstrorvid" id="exampleFormControlSelect1">
      <option value="0">Stream</option>
      <option value="1">Video</option>
    </select>
  </div>
  
  		  <div class="form-group">
    <label for="exampleFormControlSelect1">IPTV Status | '.strip_tags($row["public_active"]).'</label>
    <select class="form-control" name="iptvclsopn" id="exampleFormControlSelect1">
      <option value="0">Close</option>
      <option value="1">Open</option>
    </select>
  </div>

    <div class="form-group">
      <label for="exampleFormControlInput1">IPTV Link</label>
      <input type="text" name="iptvlink" value="'.strip_tags($row["public_tslink"]).'" class="form-control" placeholder="IPTV Link">
    </div>
      <input type="hidden" name="iptvid" value="'.strip_tags($row["public_id"]).'" class="form-control">
    <button type="submit" style="width: 100%;" class="btn btn-primary">Guncelle</button>
  </form><br>
  <center><b>SelcukWatch Player : '.strip_tags($new_url).'</b></center><br>
  <center><b>M3U8 Player : '.strip_tags($new_url2).'</b></center>
  </body>';
} else {
die("NO");
}
  }
  break;

  case 'peditpubid':
  $getir->logincheck();
  $update = $db->prepare("UPDATE public_iptv SET public_name = :iptvadi, video_stream = :iptvtype, public_active = :iptvactive, public_tslink = :iptvlink, public_sahip = :iptvsahip WHERE public_id = :iptvid");
  $update->bindValue(':iptvid',  intval($_POST["iptvid"]));
  $update->bindValue(':iptvadi', strip_tags($_POST["iptvname"]));
  $update->bindValue(':iptvtype', strip_tags($_POST["iptvstrorvid"]));
  $update->bindValue(':iptvactive', strip_tags($_POST["iptvclsopn"]));
  $update->bindValue(':iptvlink', htmlentities($_POST["iptvlink"]));
  $update->bindValue(':iptvsahip', strip_tags($_COOKIE["user_id"]));
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  break;

  case 'deletepubid':
  $getir->logincheck();
  $stmt = $db->prepare('DELETE FROM public_iptv WHERE public_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  if($row = $stmt->rowCount()) {
	  if($row["video_stream"] == "0") {
	$dosyaadi = "".dirname(__FILE__)."/m3u/".base64_decode(strip_tags($row["public_name"])).".m3u8";
	  } else {
	$dosyaadi = "".dirname(__FILE__)."/m3u/".base64_decode(strip_tags($row["public_name"])).".ts";
	  }
if (!unlink($dosyaadi)) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($dosyaadi)." File Unsuccesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
  }
  
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Not Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  break;
  
  case 'dfile':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  $dosyaadi = "".dirname(__FILE__)."/m3u/".strip_tags($_GET["name"])."";
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
  if (!unlink($dosyaadi)) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Unsuccesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Succesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  } else {
    if (!unlink($dosyaadi)) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Unsuccesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Succesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  }
  break;

  case 'dlog':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  $dosyaadi = "".dirname(__FILE__)."/log/".strip_tags($_GET["name"])."";
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
  if (!unlink($dosyaadi)) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Unsuccesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Succesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  } else {
    if (!unlink($dosyaadi)) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Unsuccesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('".strip_tags($_GET["name"])." Succesfully Deleted');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  }
  break;

  case 'watch':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  echo '
    			  <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
			  <link href="https://unpkg.com/@videojs/themes@1/dist/city/index.css" rel="stylesheet"/>
			 
			  
			  <script src="https://unpkg.com/video.js/dist/video.js"></script>
			  <script src="https://unpkg.com/videojs-flash/dist/videojs-flash.js"></script>
			  <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>';
  
echo '<div class="container">';
  $stmt = $db->prepare('SELECT * FROM private_iptv WHERE private_id = :iddegeri');
  $stmt->execute(array(':iddegeri' => intval($_GET["id"])));
  while($row = $stmt->fetch()) {
  if(strip_tags($row["private_sahip"]) == strip_tags($_COOKIE["user_id"])) {
    if(strip_tags($row["private_active"]) == "0") {
      echo '<div class="alert alert-warning" role="alert">This channel suspended by admin!</div>';
    } else {
      echo '<div class="alert alert-success" role="alert">This channel is online!</div>';
    }

echo '<body class="mx-auto">
<video id="video" class="video-js vjs-default-skin" data-aspect-ratio="hd" controls autoplay>
  </div>
  </body>';
 
?>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<!-- Or if you want a more recent alpha version -->
<!-- <script src="https://cdn.jsdelivr.net/npm/hls.js@alpha"></script> -->
<script>
  var video = document.getElementById('video');
  var videoSrc = '<?php echo strip_tags($row["private_iptv"]); ?>';
  if (Hls.isSupported()) {
    var hls = new Hls();
    hls.loadSource(videoSrc);
    hls.attachMedia(video);
    hls.on(Hls.Events.MANIFEST_PARSED, function() {
      video.play();
    });
  }

  else if (video.canPlayType('application/vnd.apple.mpegurl')) {
    video.src = videoSrc;
    video.addEventListener('loadedmetadata', function() {
      video.play();
    });
  }
</script>
<?php

if (!$fp = fopen(strip_tags($row["private_iptv"]), 'r')) {
echo '<script>console.log("'.strip_tags($row["private_iptv"]).' address not open");</script>';
}
$meta = stream_get_meta_data($fp);
$json = array_values($meta);
$json2 = json_encode($meta, true);
$data = json_decode($json2);
echo '
<br>
<div class="container card bg-light">
<pre>
<b>Coding Info</b><br>
Wrapper Type : '.$data->wrapper_type.'<br>
Stream Type : '.$data->stream_type.'<br>
URI : '.$data->uri.'<br><br><br>
<b>JSON Data Query</b><br>
'.$json2.'<br>
</pre>
<br><br><br>

</div>';
fclose($fp);
} else {
die("NO");
}
  }
  break;

  case 'pupdateiptv':
  $getir->logincheck();
  $update = $db->prepare("UPDATE iptv_list SET iptv_adi = :iptvadi, iptv_pic = :iptvpic, iptv_adres = :iptvadres, iptv_acik = :iptvacik WHERE iptv_id = :iptvid");
  $update->bindValue(':iptvid',  intval($_POST["iptvid"]));
  $update->bindValue(':iptvadi', strip_tags($_POST["iptvname"]));
  $update->bindValue(':iptvadres', strip_tags($_POST["iptvadres"]));
  $update->bindValue(':iptvpic', strip_tags($_POST["iptvpic"]));
  $update->bindValue(':iptvacik', strip_tags($_POST["iptvstr"]));
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Updated');
    window.location.href='index.php?git=iptv';
    </script>";
  }
  break;

  case 'm3ugenerate':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  if(empty($_GET["s"])) {

  } else {
    echo '<div class="alert alert-info" role="alert">File Location : <a href="http://'.$_SERVER['SERVER_NAME'].'/iptv/'.strip_tags(base64_decode($_GET["s"])).'">http://'.$_SERVER['SERVER_NAME'].'/iptv/'.strip_tags(base64_decode($_GET["s"])).'</a></div>';
  }
  echo '<body class="mx-auto">
  <form class="container" action="index.php?git=pm3ugenerate" method="post">
    <div class="form-group">
      <label for="exampleFormControlInput1">IPTV Dosya Adı</label>
      <input type="text" name="iptvfln" class="form-control" placeholder="Filename">
    </div>
    <div class="form-group">
    <label for="exampleFormControlTextarea1">IPTV Adresi</label>
    <input type="text" name="iptvname" class="form-control" placeholder="IPTV Adresi">
    </div>
    <div class="form-group">
    <label for="exampleFormControlTextarea1">IPTV Resmi</label>
    <input type="text" name="iptvpic" class="form-control" placeholder="IPTV Picture">
    </div>
    <button type="submit" style="width: 100%;" class="btn btn-primary">Ekle</button>
  </form>
  </body>';
  break;

  case 'pm3ugenerate':
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  $data = "m3u/".strip_tags($_POST["iptvfln"]).".m3u8";
  $fp = fopen($data, 'w');
  fwrite($fp, "#EXTM3U\n");
  fwrite($fp, "#EXTINF:-1,".strip_tags($_POST["iptvfln"])."\n");
  fwrite($fp, strip_tags($_POST["iptvname"]));
  fclose($fp);
  $update = $db->prepare("INSERT INTO iptv_list(iptv_adi, iptv_pic, iptv_adres, iptv_acik) VALUES (:iptvadi, :iptvpic, :iptvadres, :iptvacik) ");
  $update->bindValue(':iptvadi', strip_tags($_POST["iptvfln"]));
  $update->bindValue(':iptvadres', "http://".strip_tags($_SERVER['SERVER_NAME'])."/".strip_tags($data)."");
  $update->bindValue(':iptvpic',  strip_tags($_POST["iptvpic"]));
  $update->bindValue(':iptvacik', "1");
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='index.php?git=m3ugenerate&s=".strip_tags(base64_encode($data))."';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Updated');
    window.location.href='index.php?git=m3ugenerate';
    </script>";
    unlink($data);
  }
  break;

  case 'ipblock':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  echo '<body class="mx-auto">';
  echo '<center><button onclick="javascript:location.reload();" type="submit" style="right: 0px;width: 60%;padding: 10px;" class="btn btn-warning">Refresh</button>
  </center><div class="container"><br>
  <center><b>Your IP Address : '.strip_tags($getir->getIPAddress()).'</b><br>
  <br></center>
  <table class="container table">
  <thead>
  <tr>
  <th>ID</th>
  <th>Ülke</th>
  <th>Durum</th>
  <th>Reason</th>
  <th>IP Adresi</th>
  <th></th>
  </tr></head><tbody>';
  $sayfa = intval($_GET['page']);
  $sayfa_limiti  = 10;
  if($sayfa == '' || $sayfa == 1){
   $sayfa1 = 0;
  }else{
   $sayfa1 = ($sayfa * $sayfa_limiti) - $sayfa_limiti;
  }
  $satir_sayisi = $db->query("SELECT * FROM ip_block WHERE 1")->rowCount();
  $sql = "SELECT * FROM iptv_list LIMIT " . $sayfa1 . "," . $sayfa_limiti;
  $query = $db->prepare($sql);
  $query->execute();
  $stmt = $db->prepare('SELECT * FROM ip_block LIMIT '.$sayfa1.','.$sayfa_limiti);
  $stmt->execute();
  while($row = $stmt->fetch()) {
    $getirtr = file_get_contents("http://api.wipmania.com/".strip_tags($row["ip_adress"])."");
  echo '<tr>
  <th scope="row">'.intval($row["ip_id"]).'</th>';
  if(strip_tags($row["ip_block_active"]) == "0") {
    if (empty($getirtr))
    {
      echo '<td><img src="img/loc/xx.png" width="24" height="24"></td>';
    } else {
      $location = "img/loc/".strip_tags(mb_strtolower($getirtr)).".png";
      echo '<td><img src="'.strip_tags($location).'" width="25" height="15"></td>';
    }
    echo '<td><div class="progress kisalt">
    <a data-text="Panelden Kapalı" class="progress-bar bg-warning" role="progressbar" data-text="Online" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></a>
  </div></td>';
    echo '<td>'.strip_tags("No Reason").'</td>';
  echo '<td>'.strip_tags($row["ip_adress"]).'</td>
  <td><a href="index.php?git=remipblok&ip='.intval($row["ip_id"]).'">Ban</a></td>
  </tr>';
  } else {
    if (!$getirtr)
    {
      echo '<td><img src="img/loc/xx.png" width="24" height="24"></td>';
    } else {
      $location = "img/loc/".strip_tags(mb_strtolower($getirtr)).".png";
      echo '<td><img src="'.strip_tags($location).'" width="25" height="15"></td>';
    }
    echo '<td>
    <div class="progress">
    <a data-text="Açık" class="progress-bar bg-success" role="progressbar" data-text="Offline" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></a>
  </div></td>';
    echo '<td>'.strip_tags($row["ban_reason"]).'</td>';
  echo '<td>'.strip_tags($row["ip_adress"]).'</td>
  <td><a href="index.php?git=banip&ip='.intval($row["ip_id"]).'">Remove Ban</a></td>
  </tr>';
  }
  foreach($row as $row2){
  }
  $a = ceil($satir_sayisi / $sayfa_limiti);
  }
  echo '</tbody></table>';
  echo '<nav class="bg-dark text-white" aria-label="Page navigation example">
  <ul class="pagination justify-content-center">';
  for($b = 1 ; $b <= $a ; $b++){
  echo '<li class="page-item"><a class="page-link" href="index.php?git=ipblock&ip='.$b.'">'.$b.'</a></li>';
  }
  echo '</ul></nav></div></body>';
  break;

  case 'remipblok';
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  $update = $db->prepare("UPDATE ip_block SET ip_block_active = :iptvdrm WHERE ip_id = :iptvid");
  $update->bindValue(':iptvid', intval($_GET["ip"]));
  $update->bindValue(':iptvdrm', "1");
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='index.php?git=ipblock';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Updated');
    window.location.href='index.php?git=ipblock';
    </script>";
  }
  break;

  case 'banip';
  $getir->logincheck();
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  $update = $db->prepare("UPDATE ip_block SET ip_block_active = :iptvdrm WHERE ip_id = :iptvid");
  $update->bindValue(':iptvid', intval($_GET["ip"]));
  $update->bindValue(':iptvdrm', "0");
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='index.php?git=ipblock';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Updated');
    window.location.href='index.php?git=ipblock';
    </script>";
  }
  break;

  case 'addban':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  echo '<body class="mx-auto"><center>
  <b>Your IP : '.strip_tags($getir->getIPAddress()).'</b></center><br>
  <form class="container" action="index.php?git=paddban" method="post">
    <div class="form-group">
      <label for="exampleFormControlInput1">IP Adress</label>
      <input type="text" name="ipadres" class="form-control" placeholder="IP Adress">
    </div>
    <div class="form-group">
      <label for="exampleFormControlInput1">Reason</label>
      <input type="text" name="resn" class="form-control" placeholder="Your IP '.strip_tags($getir->getIPAddress()).'">
    </div>
    <button type="submit" style="width: 100%;" class="btn btn-primary">Add</button>
  </form>
  </body>';
  break;

  case 'startstream':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  echo '<body class="mx-auto">
  <center><b>Your IP : '.strip_tags($getir->getIPAddress()).'</b></center><br>
  <form class="container" action="index.php?git=pstartstream" method="post">
    <div class="form-group">
      <label for="exampleFormControlInput1">Stream Link</label>
      <input type="text" name="streamlink" class="form-control" placeholder="M3U8 Link">
    </div>
	  <div class="form-group">
    <label for="exampleFormControlSelect1">Type</label>
    <select class="form-control" name="streamorvid" id="exampleFormControlSelect1">
      <option value="0">Stream</option>
      <option value="1">Video</option>
    </select>
  </div>
    <button type="submit" style="width: 100%;" class="btn btn-primary">Add</button>
  </form>
  </body>';
  break;
  
  case 'addpriviptv':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  echo '<body class="mx-auto">
  <center><b>Private Stream IPTV</b>
  <hr></hr></center>
  <form class="container" action="index.php?git=paddpriviptv" method="post">
    <div class="form-group">
      <label for="exampleFormControlInput1">Private Name</label>
      <input type="text" name="privname" class="form-control" placeholder="Private Name">
    </div>
    <div class="form-group">
      <label for="exampleFormControlInput1">Private Picture</label>
      <input type="text" name="privpics" class="form-control" placeholder="Private Picture">
    </div>
	    <div class="form-group">
      <label for="exampleFormControlInput1">Private IPTV</label>
      <input type="text" name="priviptv" class="form-control" placeholder="Private IPTV">
    </div>
    <button type="submit" style="width: 100%;" class="btn btn-primary">Add</button>
  </form>
  </body>';
  break;
  
  case 'iplog':
  $getir->logincheck();
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
    if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  echo '<body class="mx-auto"><pre class="container">';
  	$stmt = $db->prepare('SELECT * FROM ip_logger ORDER BY id DESC');
	$stmt->execute();
	while($rowh = $stmt->fetch()) {
        echo '['.strip_tags($rowh["date"]).'] : '.strip_tags($rowh["ip"]).' / '.var_dump($rowh["browserinf"]).'<br>';
    }
    echo '</pre></body>';
  break;
  
  case 'user':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
  die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
  } else {
  }
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  echo '<div class="container">
  <table class="table">
  <thead>
  <tr>
  <th>ID</th>
  <th>Gravatar</th>
  <th>Username</th>
  <th>Type</th>
  <th></th>
  </tr></head><tbody>';
  $stmt = $db->prepare('SELECT * FROM admin_list');
  $stmt->execute();
  while($row = $stmt->fetch()) {
  echo '<tr>
  <th scope="row">'.intval($row["admin_id"]).'</th>
  <th><img data-role="gravatar" data-email="'.strip_tags($row["admin_email"]).'" data-size="30"></th>
  <th>'.strip_tags($row["admin_usrname"]).'</th>
  <th>'.strip_tags($row["admin_yetki"]).'</th>
  <th><a href="index.php?git=edituser&id='.intval($row["admin_id"]).'">Edit User</a></th>
  </tr>';
  }
  echo '</table>
    <br>
  <a href="index.php?git=adduser" type="submit" style="right: 0px;width: 100%;padding: 10px;" class="btn btn-warning">Add User</a>
  </div>';
  break;
  
  case 'adduser':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
  die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
  } else {
  }
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
    echo '<body>
  <form class="mt-5 container" action="index.php?git=paddusr" method="post">
    <div class="form-group">
        <label>Username</label>
        <input class="form-control" name="usrname" type="text" placeholder="Enter Username"/>
    </div>
        <div class="form-group">
        <label>Username</label>
        <input class="form-control" name="usremail" type="email" placeholder="Enter email"/>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input class="form-control" name="usrpass" type="password" placeholder="Enter Password"/>
    </div>
        <div class="form-group">
        <label>Token</label>
        <input class="form-control" name="usrtkn" type="password" placeholder="Enter Token"/>
    </div>
    <div class="form-group">
    <label for="usrtype">User Type</label>
    <select class="form-control" name="usrtype" id="usrtype">
    <option value="uye">User</option>
    <option value="admin">Admin</option>
    <option value="gold">Gold</option>
    </select>
    </div>
  
    <div class="form-group">
        <button class="button success">Ekle</button>
    </div>
  </form></body>';
  break;
  
  case 'paddusr':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
  die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
  } else {
  }
  $update = $db->prepare("INSERT INTO admin_list(admin_usrname, admin_email, admin_passwd, admin_token, admin_yetki) VALUES (:usrname, :usremail, :usrpass, :usrtkn, :usrtype)");
  $update->bindValue(':usrname', strip_tags($_POST["usrname"]));
  $update->bindValue(':usremail', strip_tags($_POST["usremail"]));
  $update->bindValue(':usrpass', sha1(md5($_POST["usrpass"])));
  $update->bindValue(':usrtkn', sha1(md5($_POST["usrtkn"])));
  $update->bindValue(':usrtype', strip_tags($_POST["usrtype"]));
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Added');
    window.location.href='index.php?git=user';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Added');
    window.location.href='index.php?git=user';
    </script>";
  }
  break;
  
  case 'edituser':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
  die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
  } else {
  }
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  $stmt = $db->prepare('SELECT * FROM admin_list WHERE admin_id = :usr');
  $stmt->execute(array(':usr' => intval($_GET["id"])));
  if($row = $stmt->fetch()) {
  echo '<body>
  <center><img data-role="gravatar" data-email="'.strip_tags($row["admin_email"]).'" data-size="150"></center>
  <form class="container" action="index.php?git=pupdusr" method="post">
  <input name="id" value="'.intval($_GET["id"]).'" type="hidden"/>
    <div class="form-group">
        <label>Username</label>
        <input class="form-control" name="usrname" value="'.strip_tags($row["admin_usrname"]).'" type="text" placeholder="Enter Username"/>
    </div>
        <div class="form-group">
        <label>Username</label>
        <input class="form-control" name="usremail" value="'.strip_tags($row["admin_email"]).'" type="email" placeholder="Enter email"/>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input class="form-control" name="usrpass" type="password" placeholder="Enter Password"/>
    </div>
        <div class="form-group">
        <label>Token</label>
        <input class="form-control" name="usrtkn" type="password" placeholder="Enter Token"/>
    </div>
    <div class="form-group">
    <label for="exampleFormControlSelect1">User Type</label>
    <select class="form-control" name="usrtype" id="exampleFormControlSelect1">
    <option value="uye">User</option>
    <option value="admin">Admin</option>
    <option value="gold">Gold</option>
    </select>
    </div>
  
    <div class="form-group">
        <button class="button success">Submit data</button>
    </div>
  </form></body>';
  }
  break;
  
  case 'pupdusr':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
  die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
  } else {
  }
  $getir->NavBar("https://metroui.org.ua/images/sb-bg-1.jpg");
  $update = $db->prepare("UPDATE admin_list SET admin_usrname = :usrname, admin_email = :usremail, admin_passwd = :usrpass, admin_token = :usrtkn, admin_yetki = :usrtype WHERE admin_id = :gonderid");
  $update->bindValue(':gonderid', intval($_POST["id"]));
  $update->bindValue(':usrname', strip_tags($_POST["usrname"]));
  $update->bindValue(':usremail', strip_tags($_POST["usremail"]));
  $update->bindValue(':usrtype', strip_tags($_POST["usrtype"]));
  $update->bindValue(':usrpass', sha1(md5($_POST["usrpass"])));
  $update->bindValue(':usrtkn', sha1(md5($_POST["usrtkn"])));
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Added');
    window.location.href='index.php?git=startstream';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Added');
    window.location.href='index.php?git=startstream';
    </script>";
  }
  break;
  
  case 'paddpriviptv':
  $getir->logincheck();
  $update = $db->prepare("INSERT INTO private_iptv(private_name, private_resim, private_iptv, private_active, private_sahip) VALUES (:streamname, :streampics, :streamiptv, :streamactive, :sahip)");
  $update->bindValue(':streamname', strip_tags($_POST["privname"]));
  $update->bindValue(':streampics', strip_tags($_POST["privpics"]));
  $update->bindValue(':streamiptv', strip_tags($_POST["priviptv"]));
  $update->bindValue(':streamactive', "1");
  $update->bindValue(':sahip', strip_tags($_COOKIE["user_id"]));
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Added');
    window.location.href='index.php?git=startstream';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Added');
    window.location.href='index.php?git=startstream';
    </script>";
  }
  break;
  
  case 'pstartstream':
  $getir->logincheck();
  $getdata = strip_tags(md5(rand(1000,9999)));
  $update = $db->prepare("INSERT INTO public_iptv(public_name, public_tslink, public_active, video_stream, public_sahip) VALUES (:streamname, :streamadress, :streamactive, :streamorvideo, :pubsahip)");
  $update->bindValue(':streamname', $getdata);
  $update->bindValue(':streamadress', strip_tags($_POST["streamlink"]));
  $update->bindValue(':streamactive', "1");
  $update->bindValue(':streamorvideo',  strip_tags($_POST["streamorvid"]));
  $update->bindValue(':pubsahip',  strip_tags($_COOKIE["user_id"]));
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Added');
    window.location.href='index.php?git=startstream';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Added');
    window.location.href='index.php?git=startstream';
    </script>";
  }
  break;

  case 'paddban':
  $getir->logincheck();
  if($_COOKIE["yetki"] == md5("uye")) {
      die("<center class='mt-5'>Sayfayı Görme Yetkiniz Yok</center>");
      } else {
      }
  $update = $db->prepare("INSERT INTO ip_block(ip_adress, ban_reason, ip_block_active) VALUES (:ipadress, :resns, :acceptban)");
  $update->bindValue(':ipadress', strip_tags($_POST["ipadres"]));
  $update->bindValue(':resns', strip_tags($_POST["resn"]));
  $update->bindValue(':acceptban', "1");
  $update->execute();
  if($row = $update->rowCount()) {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Added');
    window.location.href='index.php?git=ipblock';
    </script>";
  } else {
    echo "<script LANGUAGE='JavaScript'>
    window.alert('Unsuccesfully Added');
    window.location.href='index.php?git=ipblock';
    </script>";
  }
  break;

  case 'cikis':
  $getir->logincheck();
  die('<script>document.cookie = "user_id= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
  document.cookie = "capt= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
  document.cookie = "yetki= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
  location.replace("index.php")</script>');
  break;
}
?>
