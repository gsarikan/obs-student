<?php 
  

  $user_name = $_SESSION["userName"];
  $user_token = $_SESSION["key"];
  
  $users_json=getApi($user_token,'http://127.0.0.1:8000/users/?format=json');
  $students_json=getApi($user_token,'http://127.0.0.1:8000/students/?format=json');
  $advisor_json=getApi($user_token,'http://127.0.0.1:8000/advisors/?format=json');
  $courses_json=getApi($user_token,'http://127.0.0.1:8000/courses/?format=json');
  $offered_courses_json=getApi($user_token,'http://127.0.0.1:8000/offered_courses/?format=json');  
  $register_notes=getApi($user_token,'http://127.0.0.1:8000/register_notes/?format=json');
  $registers=getApi($user_token,'http://127.0.0.1:8000/registers/?format=json');
  $student= array();
    
  //kullanıcı bilgileri
  for($i=0;$i<count($users_json["results"]);$i++)
  {
    if($users_json["results"][$i]["username"]==$user_name)
    { 
        $student["id"]=$users_json["results"][$i]["id"];
        $student["email"]=$users_json["results"][$i]["email"];
        $student["first_name"]=$users_json["results"][$i]["first_name"];
        $student["last_name"]=$users_json["results"][$i]["last_name"];   
        $student["username"]=$users_json["results"][$i]["username"];   
    }
  }

  // öğrenci bilgileri
  for($i=0;$i<count($students_json["results"]);$i++)
  {
    if($students_json["results"][$i]["user"]==$student["id"])
    {
      $student["gender"]=$students_json["results"][$i]["gender"];
      $student["number"]=$students_json["results"][$i]["number"];
      $student["active_record_semester"]=$students_json["results"][$i]["active_record_semester"];
      $student["birthdate"]=$students_json["results"][$i]["birthdate"];
      $student["phone"]=$students_json["results"][$i]["phone"];
      $student["department"]=$students_json["results"][$i]["department"];
      $student["image"]=$students_json["results"][$i]["image"]; 
      $student["student_id"]=$students_json["results"][$i]["id"];
      $student["join_year"]=$students_json["results"][$i]["join_year"];
    }
  }


  // danışman bilgileri
  for($i=0;$i<count($advisor_json);$i++)
  {
    if($advisor_json["results"][$i]["year"]==$student["join_year"])
    {
      $advisorID["ID"]= $advisor_json["results"][$i]["lecturer"];
            $advisor=getApi($user_token,'http://127.0.0.1:8000/lecturers/'.$advisorID["ID"].'/?format=json');
            $advisor=getApi($user_token,'http://127.0.0.1:8000/users/'.$advisor["user"].'/?format=json');
    }
  }


  


 

?>

<!--main content start-->
      <section id="main-content">
          <section class="wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="page-header"><i class="fa fa fa-bars"></i> DERS SEÇME</h3>
           
        </div>
      </div> 
            <div>
                <div><?=$student["first_name"]." ".$student["last_name"]; ?></div>
                <div><?=$student["active_record_semester"]?>. Yarıyıl</div>
                <div>2.00</div>
            </div>
                
                 <div class="pull-right">
                    
                            <span style="padding-right:15px;"> Danışman:  <?php echo $advisor["first_name"]." ".$advisor["last_name"]; ?> | Email: <a href="mailto:<?php echo $advisor["email"] ?>"><?php echo $advisor["email"] ?></a>   </span>
                                <a class="btn btn-success" data-toggle="modal" href="#myModal">
                                  Onayla
                                </a>
                                <?php
                                 
                                 ?>
                              <!-- Modal -->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                              <h4 class="modal-title">Modal Tittle</h4>
                                          </div>
                                          <div class="modal-body">

                                              Body goes here...

                                          </div>
                                          <div class="modal-footer">
                                              <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                              <button class="btn btn-success" type="button">Save changes</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                 </div>
                 <div class="clearfix" style="margin:15px;"></div>
               
            <div class="row">
                  <div class="col-sm-6">
                      
                      <section class="panel">
                          <header class="panel-heading">
                              Ders Ekleme
                          </header>
                          
                          <table class="table table-striped table-advance table-hover">
                           <tbody>
                              <tr>
                                 <th>Ders Adı </th> 
                                 <th>Kredi </th> 
                                 <th>ACTS </th> 
                                 <th>Ders Saati</th>
                                 <th>Ekle</th>
                              </tr>
                              <?php 
                              $dateYear= date('Y');
                              $dateMonth = date('m');
                              if($dateMonth<10 and $dateMonth>8)
                                $donem= 1;
                              else
                                $donem = 2;

                              // bölüme ait dersler
                              for($i=0;$i<count($offered_courses_json["results"]);$i++)
                              {
                                if(
                                    $offered_courses_json["results"][$i]["active_year"]==$dateYear  //açılan dersin yılı 
                                    and
                                    $offered_courses_json["results"][$i]["semester"]<=$student["active_record_semester"] //
                                  ){ 
                                
                                $course=getApi($user_token,'http://127.0.0.1:8000/courses/'.$offered_courses_json["results"][$i]["course"].'/?format=json');   

                                if($course["department"]==$student["department"]){
                                
                                  for ($j=0; $j < count($registers["results"]); $j++) { 
                                     if(
                                      $registers["results"][$j]["offered_course"]==$offered_courses_json["results"][$i]["id"] 
                                      and 
                                      $registers["results"][$j]["student"]==$student["student_id"]
                                      ) // daha önce alınan dersler
                                     {

                                        for ($k=0; $k <count($register_notes["results"]); $k++) { 
                                          if($register_notes["results"][$k]["register"]==$registers["results"][$j]["id"])
                                            $register_note=getApi($user_token,'http://127.0.0.1:8000/register_notes/'.$register_notes["results"][$k]["id"].'/?format=json'); 
                                        }
                                           
                                        echo "<div>";
                                        echo "ders:".$course["name"]." -vize: ".$register_note["mid_exam"]."- final : ".$register_note["final_exam"];
                                        echo "</div>";

                                     }
                                      


                                  }



                              ?>
                              <tr>
                                 <td><?php echo $offered_courses_json["results"][$i]["semester"];?><?php echo $course["name"];?></td>
                                 <td><?php echo $course["credit"];?></td>
                                 <td><?php echo $course["ects"];?></td>
                                 <td><?php echo $course["lab_hour"]+$course["course_hour"];?></td> 
                                 <td>
                                  <div class="btn-group">
                                      <a class="btn btn-primary"><i class="icon_plus_alt2"></i></a> 
                                  </div>
                                  </td>
                              </tr>

                              <?php  
                                  } // department kontrol
                                } //year kontrol
                              } 
                              ?>
                             
                                                       
                           </tbody>
                        </table>
                      </section>
                  </div>
                  
                  <div class="col-sm-6"> 
                      <section class="panel">
                          <header class="panel-heading">
                              Seçilen Dersler
                          </header>
                          
                          <table class="table table-striped table-advance table-hover">
                           <tbody>
                              <tr>
                                  <th>Ders Adı </th> 
                                 <th>Kredi </th> 
                                 <th>ACTS </th> 
                                 <th>Ders Saati</th>
                                 <th>Kaldır</th>
                              </tr>
                              <tr>
                                 <td>Azure ve Bulut Bilişim</td>
                                 <td>3</td>
                                 <td>5</td>
                                 <td>2+2</td> 
                                 
                                 <td>
                                  <div class="btn-group">
                                      <a class="btn btn-danger"><i class="icon_plus_alt2"></i></a> 
                                  </div>
                                  </td>
                              </tr>
                               
                                                       
                           </tbody>
                        </table>
                      </section>

                      <div class="alert alert-info fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="icon-remove"></i>
                            </button>
                            <strong>Toplam: 7 Ders </strong> 35 AKTS Seçildi
                        </div>

                                        
                        <div class="alert alert-block alert-danger fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button">
                                    <i class="icon-remove"></i>
                                </button>
                                <strong>Toplam: 7 Ders </strong> 45 AKTS Seçildi - Maksimum AKTS: 42
                        </div>

                  </div>
                  
                   
              </div>               
              


          </section>
      </section>
      <!--main content end-->
  </section>