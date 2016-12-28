<?php

$user_name=$_SESSION["userName"];
$token=$_SESSION["key"];
$users_json=getApi($token,'http://127.0.0.1:8000/users/?format=json');
$students_json=getApi($token,'http://127.0.0.1:8000/students/?format=json');
$departments_json=getApi($token,'http://127.0.0.1:8000/departments/?format=json');
$faculties_json=getApi($token,'http://127.0.0.1:8000/faculties/?format=json');
$register_json=getApi($token,'http://127.0.0.1:8000/registers/?format=json');
$offered_course_json=getApi($token,'http://127.0.0.1:8000/offered_courses/?format=json');
$courses_json=getApi($token,'http://127.0.0.1:8000/courses/?format=json');
$register_notes_json=getApi($token,'http://127.0.0.1:8000/register_notes/?format=json');

for($i=0;$i<$users_json["count"];$i++){
    if($users_json["results"][$i]["username"]==$user_name){
        $array=explode("/", $users_json["results"][$i]["url"]);
        $user_id=$array[count($array)-2];
        $first_name=$users_json["results"][$i]["first_name"];
        $last_name=$users_json["results"][$i]["last_name"];
    }
}
for($i=0;$i<$students_json["count"];$i++){
    if($students_json["results"][$i]["user"]==$user_id){
        $department_id=$students_json["results"][$i]["department"];
        $number=$students_json["results"][$i]["number"];
        $image=$students_json["results"][$i]["image"];
        $active_record_semester=$students_json["results"][$i]["active_record_semester"];
        $array=explode("/", $students_json["results"][$i]["url"]);
        $student_id=$array[count($array)-2];
    }
}
for($i=0;$i<$departments_json["count"];$i++){
    if($departments_json["results"][$i]["id"]==$department_id){
        $faculty_id=$departments_json["results"][$i]["faculty"];
        $department_name=$departments_json["results"][$i]["department_name"];
    }
}
for($i=0;$i<$faculties_json["count"];$i++){
    $array=explode("/", $faculties_json["results"][$i]["url"]);
    if($array[count($array)-2]==$faculty_id){
        $faculty_name=$faculties_json["results"][$i]["faculty_name"];
    }
}


for($i=1;$i<=$active_record_semester;$i++)
	$courses_id[$i]=array();

$k=1;

for($i=0;$i<$register_json["count"];$i++){
		if($register_json["results"][$i]["student"]==$student_id){
			$offered_course_id=$register_json["results"][$i]["offered_course"];			
			for($j=0;$j<$offered_course_json["count"];$j++){			
				if($offered_course_json["results"][$j]["id"]==$offered_course_id){
					$ders_donem=$offered_course_json["results"][$j]["semester"];
					for($x=0;$x<$offered_course_json["count"];$x++){
						if($offered_course_json["results"][$j]["course"]==$offered_course_json["results"][$x]["course"]){
							if($offered_course_json["results"][$x]["semester"]<$ders_donem)
								$ders_donem=$offered_course_json["results"][$x]["semester"];
						}
						
					}
					
					$l=$ders_donem;
					//$l=$offered_course_json["results"][$j]["semester"];
					if(in_array($offered_course_json["results"][$j]["course"],$courses_id[$l])==false){
                        for($m=0;$m<$register_notes_json["count"];$m++){
                            if($register_json["results"][$i]["id"]==$register_notes_json["results"][$m]["register"]&& $register_notes_json["results"][$m]["success"]==true)
                                array_push($courses_id[$l],$offered_course_json["results"][$j]["course"]);
                        }
                        
					}
				}
				
			}
		}
	}

for($i=1;$i<=$active_record_semester;$i++){
	$harf_notu[$i]=array();
}

$list=array();
	for($i=1;$i<=$active_record_semester;$i++){
	foreach($courses_id[$i] as $value){
		for($j=0;$j<$offered_course_json["count"];$j++){
			if($value==$offered_course_json["results"][$j]["course"]){
				if(in_array($value,$list)==false){
					array_push($list,$value);
					$en_buyuk=$offered_course_json["results"][$j]["active_year"];
					$offered_course_id=$offered_course_json["results"][$j]["id"];
				for($x=0;$x<$offered_course_json["count"];$x++){
							
						if($offered_course_json["results"][$j]["course"]==$offered_course_json["results"][$x]["course"]){
							if($offered_course_json["results"][$j]["active_year"]<$offered_course_json["results"][$x]["active_year"]
								&&
								$en_buyuk<$offered_course_json["results"][$x]["active_year"]
                            ){
								$en_buyuk=$offered_course_json["results"][$x]["active_year"];
								$offered_course_id=$offered_course_json["results"][$x]["id"];
							}
						}											
				}				
				for($k=0;$k<$register_json["count"];$k++){
					if($register_json["results"][$k]["offered_course"]==$offered_course_id && $register_json["results"][$k]["student"]==$student_id){
						$register_id=$register_json["results"][$k]["id"];
						for($l=0;$l<$register_notes_json["count"];$l++){
							if($register_notes_json["results"][$l]["register"]==$register_id){
								$vize=intval($register_notes_json["results"][$l]["mid_exam"]);
								if($register_notes_json["results"][$l]["make_up_exam_status"]!="true"){
									$final=intval($register_notes_json["results"][$l]["final_exam"]);
									$ort=(((0.4)*$vize)+((0.6)*$final));
                                    $ort=round($ort,0);	
								}
								else
								{
									$but=intval($register_notes_json["results"][$l]["make_up_exam"]);
									$ort=(((0.4)*$vize)+((0.6)*$but));
                                    $ort=round($ort,0);
								}
								switch ($ort){
										case $ort==0:
											$harf="FF";
											break;
										case $ort>=90&&$ort<=100:
											$harf="AA";
											break;
										case $ort>=85&&$ort<=89:
											$harf="BA";
											break;
										case $ort>=80&&$ort<85:
											$harf="BB";
											break;
										case $ort>=70&&$ort<=79:
											$harf="CB";
											break;    
										case $ort>=60&&$ort<=69:
											$harf="CC";
											break;
										case $ort>=55&&$ort<=59:
											$harf="DC";
											break;
                                        case $ort>=50&&$ort<55:
											$harf="DD";
											break;
										case $ort>=45&&$ort<=49:
											$harf="FD";
											break;  	
										case $ort>=40&&$ort<45:
											$harf="FF";
											break;  
										case $ort>=30&&$ort<=39:
											$harf="FF";
											break;     
										case $ort>=20&&$ort<=29:
											$harf="FF";
											break;     
										case $ort>=1&&$ort<=19:
											$harf="FF";
											break;  										
								}
								array_push($harf_notu[$i],$harf);

							}
							
						}
					}
				}
			}
			}
		}
	}
}

$genel_kredi=0;
$genel_ortalama=0;
?>


<section id="main-content">
          <section class="wrapper">
		  
              <!-- page start------------------------------------------------------------------------------------------------------>
           
                <table id="table1" class="table table-bordered" back>
                            <td rowspan="9" style="width: 150px">
                                            <img  src="<?php echo $image; ?>" style="width:150px;">
                            </td>
                              <tr>
                                  <td rowspan="<2></2>">Akademik Birim:	</td>
                                  <td> <?php echo $faculty_name; ?></td>
                              </tr>
                              
                              <tr>
                                  <td>Bölüm</td>
                                  <td><?php echo $department_name; ?></td>
                                 
                              </tr>
                              <tr>
                                  <td>T.C. Kimlik No	</td>
                                  <td colspan="2">21583873300</td>
                              </tr>
                              <tr>
                                  <td>İsim	</td>
                                  <td colspan="2"><?php echo $first_name; ?></td>
                                
                              </tr>
                              <tr>
                                  <td>Soyad	</td>
                                  <td colspan="2"><?php echo $last_name; ?></td>
                                 
                              </tr>
                             
                              <tr>
                                  <td>Aktif mi	</td>
                                  <td colspan="2">Aktif</td>                                 
                              </tr>
                              <tr>
                                  <td>Öğrenci No	</td>
                                  <td colspan="2"><?php echo $number; ?></td>
                                  
                              </tr>
                              
                        </table>

            
                <?php for($i=1;$i<=$active_record_semester;$i++){ 
                  $donem_kredi=0;$sayac=0;$donem_ortalama=0; 
                ?>  
                  <div class="col-sm-6">
                      <section class="panel">
                          <header class="panel-heading no-border"><?php echo $i ?>.Yarıyıl</header>
                          <table class="table table-bordered">
                              <thead>
                              <tr>
                                  <th>Ders Kodu</th>
                                  <th>Ders Adı</th>
                                  <th>Kredi</th>
                                  <th>Akts</th>
                                  <th>Katsayı</th>
                                  <th>Başarı Puanı</th>
                                 
                              </tr>
                              </thead>
                              <tbody>
                                       
                         <?php foreach($courses_id[$i] as $value){
		                            for($j=0;$j<count($courses_json["results"]);$j++){
			                            $array=explode("/", $courses_json["results"][$j]["url"]);
			                            if($array[count($array)-2]==$value){
                                            switch ($harf_notu[$i][$sayac]){ 
                                                        case "AA":
                                                            $katsayi=4;
                                                            break;
                                                        case "BA":
                                                            $katsayi=3.5;
                                                            break;
                                                        case "BB":
                                                            $katsayi=3;
                                                            break;
                                                        case "CB":
                                                            $katsayi=2.5;
                                                            break;    
                                                        case "CC":
                                                            $katsayi=2;
                                                            break;
                                                        case "DC":
                                                            $katsayi=1.5;
                                                            break;
                                                        case "DD":
                                                            $katsayi=1;
                                                            break;
                                                        case "FD":
                                                            $katsayi=0.5;
                                                            break;  		
                                                        case"FF":
                                                            $katsayi=0;
                                                            break;  									
                                                } ?>
                              
                              <tr>
                                  <td><?php  echo $courses_json["results"][$j]["code"];?></td>
                                  <td><?php   echo $courses_json["results"][$j]["name"];?></td>
                                  <td><?php   echo $credit=$courses_json["results"][$j]["credit"];?></td>
                                  <td><?php   echo $ects=$courses_json["results"][$j]["ects"];?></td>
                                  <td><?php   echo $katsayi; ?></td>
                                  <td><?php echo $harf_notu[$i][$sayac]; ?></td>
                              </tr>
                            
                            <?php $sayac=$sayac+1;
                            if($sayac>=count($courses_id[$i])){ $sayac=0; }
                            $donem_kredi=$donem_kredi+$credit;
                            $donem_ortalama=$donem_ortalama+($credit*$katsayi);
                        }}} 
                            $genel_kredi=$donem_kredi+$genel_kredi;
                            $genel_ortalama=$donem_ortalama+$genel_ortalama;                        
                            ?>
                              </tbody>
                              
                               <tfoot>
                                <tr >
                                  <td></td>
                                  <td></td>
                                  <td><b>Ağırlıklı Ortalama</td>
                                  <td></td>
                                  <td></td>
                                  <td </td> 
                              </tr>
                                <tr>
                                  <td </td>
                                  <td><b>Dönem</td>
                                  <td><?php echo $donem_ortalama ?></td></td>
                                  <td><?php echo $donem_kredi ?></td>
                                  <td><?php echo $donem_kredi ?></td>
                                  <td><?php if($donem_kredi!=0) echo round($donem_ortalama/$donem_kredi,2); else echo 0; ?></td>
                              </tr>
                                <tr>
                                  
                                  <td> </td>
                                  <td><b>Genel</td>
                                  <td><?php echo $genel_ortalama ?></td></td>
                                  <td><?php echo $genel_kredi ?></td>
                                  <td><?php echo $genel_kredi ?></td>
                                  <td><?php if($genel_kredi!=0) echo round($genel_ortalama/$genel_kredi,2); else echo 0;?> </td>
                              </tr>
                              </tfoot>
                          </table>
                      </section>
                  </div>
                <?php } ?>
                  

              <!-- page end-------------------------------------------------------------------------------------------------------->
          </section>
      </section>

      
