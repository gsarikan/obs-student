<?php
	
	include "/functions.php";
	
	$user_name = $_SESSION["userName"];
	$user_token = $_SESSION["key"];
	
	$users_json=getApi($user_token,'http://127.0.0.1:8000/users/?format=json');
	$students_json=getApi($user_token,'http://127.0.0.1:8000/students/?format=json');
	
	for($i=0;$i<$users_json["count"];$i++)
	{
		if($users_json["results"][$i]["username"]==$user_name)
		{
			$array=explode("/", $users_json["results"][$i]["url"]);
			$user_id=$array[count($array)-2];
			$first_name=$users_json["results"][$i]["first_name"];
			$last_name=$users_json["results"][$i]["last_name"];
		}
	}
	
	for($i=0;$i<$students_json["count"];$i++)
	{
		if($students_json["results"][$i]["user"]==$user_id)
		{
			$image_url=$students_json["results"][$i]["image"];
		}
	}
	
?>


<!DOCTYPE html>
<html lang="tr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
		<meta name="author" content="GeeksLabs">
		<meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
		<link rel="shortcut icon" href="img/favicon.png">

		<title>ÇOMÜ - OBS</title>

		<!-- Bootstrap CSS -->    
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- bootstrap theme -->
		<link href="css/bootstrap-theme.css" rel="stylesheet">
		<!--external css-->
		<!-- font icon -->
		<link href="css/elegant-icons-style.css" rel="stylesheet" />
		<link href="css/font-awesome.min.css" rel="stylesheet" />
		<!-- Custom styles -->
		<link href="css/style.css" rel="stylesheet">
		<link href="css/style-responsive.css" rel="stylesheet" />

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
			<script src="js/lte-ie7.js"></script>
		<![endif]-->
	
		<script src="js/jquery.js"></script>
	</head>


  
<body>
<!-- container section start -->
	<section id="container" class="">
    <!--header start-->  
		<header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
            </div>

            <!--logo start-->
            <a href="index.php" class="logo">ÇOMÜ <span class="lite">OBS</span></a>
            <!--logo end-->
            
            <div class="top-nav notification-row">                
                <!-- notificatoin dropdown start-->
                <ul class="nav pull-right top-menu"> 
                    <li id="mail_notificatoin_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="icon-envelope-l"></i>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-blue"></div>
                            <li>
                                <p class="blue" align="center">Toplam 1 Duyuru Var</p>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="subject">
					<span class="from">Mühendislik Fakültesi</span>
                                    </span>
                                    Formal Diller İptal Olmuştur!
                                </a>
                            </li>
                           
                            <li>
                                <a href="?path=duyurular" align="center">Tüm Duyuruları Gör</a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- alert notification end-->
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="profile-ava">
                                <img src="<?php echo $image_url; ?>" height="34px"; width="34px"; >
                            </span>
                            <span class="username"> <?php echo "$first_name" ." ". "$last_name" ?> </span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li class="eborder-top">
                                <a href="?path=profil"><i class="icon_profile"></i> Öğrenci Profili </a>
                            </li>
                            <li class="eborder-top">
                                <a href="?path=parola_degistir"><i class="icon_key_alt"></i> Parola Değiştir </a>
                            </li> 
                            <li>
                                <a href="logout.php"><i class="icon_close_alt"></i> Çıkış Yap </a>
                            </li> 
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!-- notificatoin dropdown end-->
            </div>
		</header> 
	</section>
</body>
