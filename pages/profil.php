<?php 
	
	$user_name = $_SESSION["userName"];
	$user_token = $_SESSION["key"];
	
	$users_json=getApi($user_token,'http://127.0.0.1:8000/users/?format=json');
	$students_json=getApi($user_token,'http://127.0.0.1:8000/students/?format=json');
	$departments_json=getApi($user_token,'http://127.0.0.1:8000/departments/?format=json');
	$faculties_json=getApi($user_token,'http://127.0.0.1:8000/faculties/?format=json');
	
	for($i=0;$i<$users_json["count"];$i++)
	{
		if($users_json["results"][$i]["username"]==$user_name)
		{
			$array=explode("/", $users_json["results"][$i]["url"]);
			$user_id=$array[count($array)-2];
			$email=$users_json["results"][$i]["email"];
			$first_name=$users_json["results"][$i]["first_name"];
			$last_name=$users_json["results"][$i]["last_name"];
			$user_id=$users_json["results"][$i]["id"];
		}
	}
	
	for($i=0;$i<$students_json["count"];$i++)
	{
		if($students_json["results"][$i]["user"]==$user_id)
		{
			$gender=$students_json["results"][$i]["gender"];
			$student_number=$students_json["results"][$i]["number"];
			$active_record_semester=$students_json["results"][$i]["active_record_semester"];
			$birthdate=$students_json["results"][$i]["birthdate"];
			$phone=$students_json["results"][$i]["phone"];
			$department_id=$students_json["results"][$i]["department"];
			$image_url=$students_json["results"][$i]["image"]; 
			$student_id=$users_json["results"][$i]["id"];
		}
	}
	
	for($i=0;$i<$departments_json["count"];$i++)
	{
		if(intval($departments_json["results"][$i]["department_code"])==intval($department_id))
		{
			$faculty_id=$departments_json["results"][$i]["faculty"];
			$department_name=$departments_json["results"][$i]["department_name"];
		}
	}
	
	for($i=0;$i<$faculties_json["count"];$i++)
	{
		$array=explode("/", $faculties_json["results"][$i]["url"]);
		if($array[count($array)-2]==$faculty_id)
		{
			$faculty_name=$faculties_json["results"][$i]["faculty_name"];
		}
	}

?>
 
<section id="main-content">
			
	<section class="wrapper">
		<div class="row">
			 
		</div>
        <div class="row">
                <!-- profile-widget -->
            <div class="col-lg-12">
                <div class="profile-widget profile-widget-info">
                    <div class="panel-body">
                        <div class="col-lg-2 col-sm-2">
                            <h4> <?php echo "$first_name"." "."$last_name" ?> </h4>               
                            <div class="follow-ava">
                                <img src="<?php echo $image_url; ?>" >
                            </div>
                            <h6>Öğrenci</h6>
                        </div>
                            <h1>Çanakkale Onsekiz Mart Üniversitesi</h1>
                            <h2>Öğrenci Bilgi Sistemi</h2>   
                    </div>
                </div>
            </div>
        </div>
              <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading tab-bg-info">
                        <ul class="nav nav-tabs">
                                  
							<li class="active"> 
                                <a data-toggle="tab" href="#profile">
                                    <i class="icon-home"></i>
                                        <strong>Profil</strong>
                                </a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#edit-profile">
                                    <i class="icon-envelope"></i>
                                        <strong>Profil Düzenle</strong>
                                </a>
                            </li>
								  
                        </ul>
                    </header>
					<div class="panel-body">
						<div class="tab-content">                      
						<!-- profile -->
							<div id="profile" class="tab-pane active">
								<section class="panel">
									<div class="panel-body bio-graph-info">
										<h1>Öğrenci Bilgileri</h1>
										<div class="row">
											<div class="bio-row">
												<span class="span-class">Ad : </span> <?php echo "$first_name" ?>
											</div>
											<div class="bio-row">
												<span class="span-class">Soyad : </span> <?php echo "$last_name" ?>
											</div>
											<div class="bio-row">
												<span class="span-class">Cinsiyet : </span> <?php if ($gender=="M") echo "Erkek"?> <?php if ($gender=="F") echo "Kız"?>
											</div>
											<div class="bio-row">
												<span class="span-class">Doğum Tarihi :</span> <?php echo "$birthdate" ?>
											</div>
											<div class="bio-row">
												<span class="span-class">Email Adresi :</span> <?php echo "$email" ?>
											</div>                      
											<div class="bio-row">
												<span class="span-class">Telefon Numarası :</span> <?php echo "$phone" ?>
											</div>
											<div class="bio-row">
												<span class="span-class">Fakülte :</span> <?php echo "$faculty_name" ?>
											</div>
											<div class="bio-row">
												<span class="span-class">Bölüm :</span> <?php echo "$department_name" ?>
											</div>
											<div class="bio-row">
												<span class="span-class">Öğrenci Numarası :</span> <?php echo "$student_number" ?>
											</div>										  
											<div class="bio-row">
												<span class="span-class">Aktif Kayıt Dönemi :</span> <?php echo "$active_record_semester" ?>
											</div>                   
										</div>
									</div>
								</section>
								<section>
									<div class="row">                                              
									</div>
								</section>
							</div>
                <!-- edit-profile -->
                <div id="edit-profile" class="tab-pane">
                    <section class="panel">                                          
                        <div class="panel-body bio-graph-info">
                            <h1> Öğrenci Bilgileri Güncelleme</h1>
                                <form class="form-horizontal" role="form" id="updateProfile" name="updateProfile" action="javascript:updateProfile();" method="post" >                                                  
									<input type="hidden" name="user_id" id="user_id" value="<?php echo "$user_id" ?>"> 
                                    <input type="hidden" name="student_id" id="student_id" value="<?php echo "$student_id" ?>">
                                                   							 
									<div class="form-group">	    
										<label class="col-lg-2 control-label" ><strong>Güncel Email :</strong></label>             
											<div class="col-lg-6">
                                                <input type="text" class="form-control" name="email" value="<?php echo "$email" ?>" id="email" placeholder="Email Adresi">
                                            </div>  
                                    </div>
												  									  
									<div class="form-group">  
										<label class="col-lg-2 control-label"><strong>Güncel Telefon Bilgileri :</strong></label>
											<div class="col-lg-6">
                                                <input type="text" maxlength="11" class="form-control" name="phone" id="phone" value="<?php echo "$phone" ?>" placeholder=" Telefon Numarası">
                                            </div>                                           	
                                    </div>
												
									<div id="formMessage" class="alert alert-block alert-danger fade in">
									</div>
									
									<div> 
										<button type="submit" class="btn btn-primary">Güncelle</button> 
										<button type="button" class="btn btn-danger" onclick="window.location.reload();" >İptal</button> 
									</div> 
												    
                                </form>
                        </div>
                    </section>
                </div>
						</div>
					</div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
	
</section>
	  
	  
	<script type="text/javascript">
        $("#formMessage").hide();
		$("#successFormMessage").hide();
		
		function isValidPhone(phone) 
		{             
			var result = phone.match(/^[{0,1}[0-9]{1}[{0,1}[0-9]{10}$/);
			if (!result) 
				return false;
			else
				return true;
		}
		
		function isValidEmail(email)   
		{  
			var result = email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/);
			if (!result)  
				return false; 
			else
				return true;
		}
		
		function updateProfile()
		{
			var email = $("#email").val(); 
			var phone  = $("#phone").val();
			
            $(function()
			{
                if(email == "" ||  phone == "")
				{ 
					$("#formMessage").show(); 
					$("#formMessage").css("margin-top","10px");
					$("#formMessage").html("Alanları Boş Bırakmayınız!");
                }
				else if( !isValidEmail(email) )
				{
					$("#formMessage").show(); 
					$("#formMessage").css("margin-top","10px");
					$("#formMessage").html("Geçerli Bir Email Adresi Giriniz!");
				}
				else if( !isValidPhone(phone) )
				{
					$("#formMessage").show(); 
					$("#formMessage").css("margin-top","10px");
					$("#formMessage").html("Geçerli Bir Telefon Numarası Giriniz!");
				}
				else
				{ 
                    $.ajax({
                        url:"controller/updateProfile.php",
                        data:$("#updateProfile").serialize(),
                        type:"post",
                        dataType:"json",
                        success:function(data)
						{   
                            if(data.status == 0)
							{
                                $("#formMessage").show(); 
                                $("#formMessage").css("margin-top","10px");
                                $("#formMessage").html(data.error);
                            } 
                            else 
							{
                                window.location.reload();
                            }
                        }
                    });
					window.location.reload();
                }
            });
        }
    </script>