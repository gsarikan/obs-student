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
$lecturers_json=getApi($token,'http://127.0.0.1:8000/lecturers/?format=json');


for($i=0;$i<$users_json["count"];$i++){
    if($users_json["results"][$i]["username"]==$user_name){
        $user_id=$users_json["results"][$i]["id"];
    }
}

for($i=0;$i<$students_json["count"];$i++){
    if($students_json["results"][$i]["user"]==$user_id){
        $active_record_semester=$students_json["results"][$i]["active_record_semester"];
        $student_id=$students_json["results"][$i]["id"];
    }
}


for($i=1;$i<=$active_record_semester;$i++){
	$courses_id[$i]=array();
}
$k=1;
for($i=0;$i<count($register_json["results"]);$i++){
    if($register_json["results"][$i]["student"]==$student_id){
        $offered_course_id=$register_json["results"][$i]["offered_course"];
        for($j=0;$j<count($offered_course_json["results"]);$j++){
			if($offered_course_json["results"][$j]["id"]==$offered_course_id){
				$l=$offered_course_json["results"][$j]["semester"];
				array_push($courses_id[$l],$offered_course_json["results"][$j]["course"]);
			}
		}
    }
}


function getNote($courseId,$donem){
        $user_name=$_SESSION["userName"];
        $token=$_SESSION["key"];
        $register_json=getApi($token,'http://127.0.0.1:8000/registers/?format=json');
        $offered_course_json=getApi($token,'http://127.0.0.1:8000/offered_courses/?format=json');
        $courses_json=getApi($token,'http://127.0.0.1:8000/courses/?format=json');
        $register_notes_json=getApi($token,'http://127.0.0.1:8000/register_notes/?format=json');
        $users_json=getApi($token,'http://127.0.0.1:8000/users/?format=json');
        $students_json=getApi($token,'http://127.0.0.1:8000/students/?format=json');

        
        $notes[]="";
        $harf="";
        $final=0;
        $but=0;
        $ort=0;
        $value=$courseId;
        for($i=0;$i<$users_json["count"];$i++){
            if($users_json["results"][$i]["username"]==$user_name){
                $user_id=$users_json["results"][$i]["id"];
            }
        }
        for($i=0;$i<$students_json["count"];$i++){
            if($students_json["results"][$i]["user"]==$user_id){
                $active_record_semester=$students_json["results"][$i]["active_record_semester"];
                $student_id=$students_json["results"][$i]["id"];
            }
        }
            for($j=0;$j<count($offered_course_json["results"]);$j++){
                if($value==$offered_course_json["results"][$j]["course"] && $donem==$offered_course_json["results"][$j]["semester"] ){
                    $offered_course_id=$offered_course_json["results"][$j]["id"];
                    for($k=0;$k<count($register_json["results"]);$k++){
                        if($register_json["results"][$k]["offered_course"]==$offered_course_id && $register_json["results"][$k]["student"]==$student_id){
                            $register_id=$register_json["results"][$k]["id"];
                            for($l=0;$l<count($register_notes_json["results"]);$l++){
                                if($register_notes_json["results"][$l]["register"]==$register_id){
                                    $vize=intval($register_notes_json["results"][$l]["mid_exam"]);
                                    
                                    if($register_notes_json["results"][$l]["make_up_exam_status"]!="true"){
                                        $final=intval($register_notes_json["results"][$l]["final_exam"]);
                                        $ort=(((0.4)*$vize)+((0.6)*$final));
                                        $ort=round($ort,0);
                                    }
                                    else
                                    {
                                        $final=intval($register_notes_json["results"][$l]["final_exam"]);
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
                                    $notes[0]=$vize;
                                    $notes[1]=$final;
                                    $notes[2]=$but;	
                                    $notes[3]=$ort;	
                                    $notes[4]=$harf;	 
                                    $notes[5]=$register_notes_json["results"][$l]["make_up_exam_status"];	
                                    
                                    return $notes;
                                }
                                
                            }
                        }
                    }
                }
            }
        }
	(isset($_POST['semester'])) ? $semester = $_POST['semester'] : $semester=$active_record_semester;
	
?>

<section id="main-content">
          <section class="wrapper">
		  <div class="row">
				<div class="col-lg-12">
					<h3 class="page-header"><i class="fa fa fa-bars"></i> SINAV SONUÇLARI</h3>
				</div>
			</div>
              <!-- page start-->
         

              <header class="panel-heading no-border">
                    <form action="" method="POST">      
                    
					<select class="form-control input-lg m-bot15" name="semester">
                         
						<?php for($i=1;$i<=$active_record_semester;$i++){  ?> 
                        
                             <option <?php if ($semester==$i) echo 'selected'; ?> value="<?php echo $i; ?>" ><?php echo $i; ?>. Yarıyıl</option>
                             
                        <?php } ?>
                    </select>
                            <input type="submit" class="btn btn-primary" name="submit" value="Görüntüle" />
                    </form>

                          </header>
                          <table class="table table-bordered">
                              <thead>
                              <tr>
                                  <th>Ders Kodu</th>
                                  <th>Ders Adı</th>
                                  <th>Öğretim Üyesi</th>
                                  <th>Vize</th>
                                  <th>Final</th>
                                  <th>Bütünleme</th>
                                  <th>İlan tarihi</th>
                                  <th>Ortalama</th>
                                  <th>Harf Notu</th>
                              </tr>
                              </thead>
                              <tbody>

                              <?php 
                             
							 
							 //(isset($_POST["semester"])) ? $i = $_POST["semester"] : $i=1;
                            //$i=$_POST['semester'];
							if(isset($_POST['semester'])){
                            $i=$_POST['semester'];
                              foreach($courses_id[$i] as $value){
		                            for($j=0;$j<count($courses_json["results"]);$j++){
			                            if($courses_json["results"][$j]["id"]==$value){
                                            $notes=getNote($courses_json["results"][$j]["id"],$i);
                            ?>
                                             
                              <tr>
                                  <td><?php  echo $courses_json["results"][$j]["code"];?></td>
                                  <td><?php  echo $courses_json["results"][$j]["name"];?></td>
                                  <td>
                                  <?php
                                  for($x=0;$x<count($courses_json["results"]);$x++){
                                    $array=explode("/", $courses_json["results"][$x]["url"]);
                                        if($array[count($array)-2]==$value){
                                            $lecturer_id=$courses_json["results"][$x]["lecturer"];
                                                for($y=0;$y<count($lecturers_json["results"]);$y++){
                                                    $array=explode("/", $lecturers_json["results"][$y]["url"]);
                                                        if($array[count($array)-2]==$lecturer_id){
                                                            $degree=$lecturers_json["results"][$y]["degree"];
                                                            $userid=$lecturers_json["results"][$y]["user"];
                                                            for($z=0;$z<count($users_json["results"]);$z++){
                                                                $array=explode("/", $users_json["results"][$z]["url"]);
                                                                if($array[count($array)-2]==$userid){
                                                                    echo $degree.$users_json["results"][$z]["first_name"]." ".$users_json["results"][$z]["last_name"];
                                                                }       
                                                            }
                                                        }
                                                }
                                        }
                                  } ?>
                                  
                                  </td>
                                  <td><?php  echo "$notes[0]"; ?></td>
                                  <td><?php  echo "$notes[1]"; ?></td>
                                  <td><?php if($notes[5]=="true"){ echo $notes[2];} ?></td>
                                  <td>2017</td>
                                  <td><?php  echo $notes[3]; ?></td>
                                  <td><?php  echo $notes[4]; ?></td>

                              </tr>
                              <?php  }}} }
							  
							  
							  ?>

                              </tbody>
                          </table>


              <!-- page end-->
          </section>
      </section>