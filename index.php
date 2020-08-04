<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport'
          content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>X O</title>
    <link rel='icon' href='img/icon.png'>
    <link rel='stylesheet' href='css/bootstrap.min.css'>
    <link rel='stylesheet' href='fonts/awesome/css/all.min.css'>
    <link rel='stylesheet' href='css/css.css'>


    <script src='js/jquery-3.3.1.min.js'></script>
    <script src='js/popper.min.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery.fullscreen.js'></script>
    <script src='js/js.js'></script>
</head>
<body style='height: 100vh'>
<header class='container font-weight-bold h4 p-2'>
    <div class='row pt-3'>
        <div class='col-3 col-md-8 col-lg-8 text-lg-left'>
            <h4 class='font-weight-bold'>
                <span class='d-none d-md-inline-block d-lg-inline-block'>Created By : Programer Mahmoud Mohamed</span>
                <a target='_blank' href='https://www.facebook.com/profile.php?id=100009734383434'>
                    <i class="fab fa-facebook-f"></i>
                </a>
            </h4>
        </div>
        <div class='col-7 col-md-4 col-lg-4 text-right'>
            <label><span>X</span> <code>دور الاعب</code></label>
        </div>
    </div>
</header>
<main class='container position-relative pl-0 pr-0' >
    <div class='row no-gutters'>
        <div class='col' data-value=''></div>
        <div class='col' data-value=''></div>
        <div class='col' data-value=''></div>
    </div>
    <div class='row no-gutters'>
        <div class='col' data-value=''></div>
        <div class='col' data-value=''></div>
        <div class='col' data-value=''></div>
    </div>
    <div class='row no-gutters'>
        <div class='col' data-value=''></div>
        <div class='col' data-value=''></div>
        <div class='col' data-value=''></div>
    </div>
    <div class='position-fixed'>
        <p class='h1' dir='rtl'><span></span>مبروك الاعب<code> X </code><span>هو الفائز</span></p>
    </div>
    <span class='position-fixed'>X</span>
</main>
<footer class='container ml-auto mr-auto text-center  mb-3'>
    <a href='http://mahmoud-mohamed.eb2a.com/' target='_blank' class='btn mt-3 btn-warning font-weight-bold '>
        <span>My Web Site</span>
        <i class="fab fa-optin-monster "></i>
    </a>
    <button class='btn btn-primary font-weight-bold mt-3'>Full Screen</button>
    <button class='btn btn-danger font-weight-bold mt-3' style='display: none'>Play Again</button>
</footer>

<audio src='sound/playSound.mp3' id='soundPlay'  autoplay class='position-fixed' style='width:300px;bottom: 0px;right: 0px' id='soundPlay' controls loop></audio>
<audio src='sound/play.wav' id='soundClick'></audio>
<audio src='sound/gamOver.wav' id='soundGameFinish'></audio>
</body>
</html>

<?php
ini_set('display_errors',0);
$cn='';
function connect(){
    global $cn;
//    $cn=new PDO('mysql:host=localhost;dbname=xo','root','',
    $cn=new PDO('mysql:host=sql208.hostkda.com;dbname=hkda_22997827_visitor_xo','hkda_22997827','5zgk3py7',
        array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'));
}
function disconnect(){
    global $cn;
    $cn='';
}
$ip=$_SERVER['REMOTE_ADDR'];

function getCity(){
    $ip=$_SERVER['REMOTE_ADDR'];

    // set IP address and API access key
    $access_key = 'dba7d01207297b27dacbf6af85e89e20';

    // Initialize CURL:
    $ch = curl_init('http://api.ipapi.com/'.$ip.'?access_key='.$access_key.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Store the data:
    $json = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response:
    $api_result = json_decode($json, true);
    // Output the "calling_code" object inside "location"

    return ($api_result['country_name'].' '.$api_result['city']);
}
$city=@getCity();
$osAndBrowser=$_SERVER['HTTP_USER_AGENT'];


connect();
$stmt=$cn->prepare("select * from visitor where visitorIP=?");
$stmt->execute(array($ip));
$result=$stmt->fetchAll();
if ($stmt->rowCount()>0)
{
    $stmt=$cn->prepare("UPDATE visitor SET visitorOsAndBrowser=?,visitorCounterVisit=?,visitorLastDate= CURRENT_TIMESTAMP WHERE id=?");
    $stmt->execute(array($osAndBrowser,($result[0]['visitorCounterVisit']+1),$result[0]['id']));

    $stmt=$cn->prepare("INSERT INTO visitordate(visitorId) 
            VALUES (?)");
    $stmt->execute(array($result[0]['id']));
}else{
    $stmt=$cn->prepare("INSERT INTO visitor
    (visitorIP, visitorCity, visitorOsAndBrowser, visitorCounterVisit)
     VALUES (?,?,?,1)");
    $stmt->execute(array($ip,$city,$osAndBrowser));
    $lastInsertId=$cn->lastInsertId();
    $stmt=$cn->prepare("INSERT INTO visitordate(visitorId) 
            VALUES (?)");
    $stmt->execute(array($lastInsertId));
}
disconnect();


?>

