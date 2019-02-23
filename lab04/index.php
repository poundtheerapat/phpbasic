<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <script src="jquery-3.3.1.min.js"></script>
  <script src="script.js"></script>
  <title>Lab04:BasicPHP</title>
  <link rel="icon" type="image/png" sizes="1000x1000" href="../images/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
  <link rel='stylesheet' href='https://unpkg.com/bulma@0.7.4/css/bulma.min.css'>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
    integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
  <link rel='stylesheet' href="mystyle.css">

<body>
  <section class="hero is-primary">
    <div class="hero-body">
      <div class="columns">
        <div class="column is-12">
          <div class="container content">
            <h1 class="title"><B>Lab04:</B> Basic PHP</h1>
            <h2 class="subtitle">
              by Pound
            </h3>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-3">
          <aside class="is-medium menu">
            <p class="menu-label">
              Sample CSV..
            </p>
            <div id="csvtemplate">
              <div class="field">
                <label class="label">CSV Template ♥</label>
                <div class="control">
                  <p class="help">You can download CSV template by clicking here!</p><br>
                </div>
                <div class="field">
                  <a class = "button is-danger is-rounded" href="template.csv">Download Sleep time CSV Forms</a>
                </div>                
                <div class="control">
                  <label class="label"> ** Input should be in 24 Hour!**
                  <br>[0-24 hours]</p><br>
                </div>

              </div>
            </div>
          </aside>
        </div>

        <!-- main -->
        <div class="column is-9">
          <div class="content is-medium">
            <h3 class="title is-2">.꒰-﹏-๑꒱‧*ZZzzz｡</h3>
            <p>This sleep calculator will tell you about your's sleep rate is good enough!?!</p>
            <p class="help">First, You must fill all of this forms for use it!!</p>
            <div class="box">
              <h4 id="const" class="title is-3">Sleep Calculator</h4>
              <article class="message is-primary">
                <div class="message-body">
                  <?php
                  // define variables and set to empty values
                      $nameErr =  $ageErr = $picErr = $csvErr =  "";
                      $name = $age = "";
                      $DisplayForm = TRUE;
                      $DisplayPic = FALSE;
                      $DisplayCSV = FALSE;
                      $target_dir = "";
                      
                      $checkToDisplay = TRUE;
                      if ($_SERVER["REQUEST_METHOD"] == "POST") {
                          if (empty($_POST["name"])) {
                            $nameErr = "Name is required";
                            $checkToDisplay = FALSE;
                          }else {
                            $name = ($_POST["name"]);
                            // check if lastname only contains letters
                            if (!preg_match("/^[a-zA-Z]*$/",$name)) {
                              $nameErr = "Only letters allowed"; 
                            }
                          }
                          if (empty($_POST["age"])) {
                              $ageErr = "Age is required";
                              $checkToDisplay = FALSE;
                          }else{
                            $age = ($_POST["age"]);
                            // check if tel only contains number
                            if (!preg_match("/^[0-9]*$/",$age)) {
                              $age = "";
                              $ageErr = "Only numeric allowed"; 
                            }
                          }
                          if($checkToDisplay){
                              $DisplayForm = FALSE;
                          }
                        }
                      if(isset($_FILES['fileToUpload'])){
                      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                      $uploadOk = 1;
                      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        // Check file size
                        if ($_FILES["fileToUpload"]["size"] > 600000) {
                            $picErr = "Sorry, your file is too large.";
                            $uploadOk = 0;
                        }
                        // Allow certain file formats
                        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                            $picErr = "Sorry, only JPG, JPEG, PNG files are allowed.";
                            $uploadOk = 0;
                        }
                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                            $picErr = "Sorry, your file was not uploaded.";
                        // if everything is ok, try to upload file
                        } else {
                            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                $DisplayPic = TRUE;
                            }
                        }
                      }
                      if(isset($_FILES['fileCSVToUpload'])){
                          $target_CSVfile = $target_dir . basename($_FILES["fileCSVToUpload"]["name"]);
                          $uploadCSVOk = 1;
                          $csvFileType = strtolower(pathinfo($target_CSVfile,PATHINFO_EXTENSION));
    
                          // Allow certain file formats
                          if($csvFileType != "csv") {
                              $csvErr = "Sorry, only CSV file are allowed.";
                              $uploadCSVOk = 0;
                          }
                          // Check if $uploadOk is set to 0 by an error
                          if ($uploadCSVOk == 0) {
                              $csvErr = "Sorry, your file was not uploaded.";
                          // if everything is ok, try to upload file
                          } else {
                              if (move_uploaded_file($_FILES["fileCSVToUpload"]["tmp_name"], $target_CSVfile)) {
                                  $csvFile = fopen($target_CSVfile,"r");
                                  $word = fread($csvFile,filesize($target_CSVfile));
                                  fclose($csvFile);
                                  $array = preg_split("/[\s,]+/",$word);
                                  $allSleepTime = 0;
                                  $dayCount = 0;
                                  $score ="";
                                  for($i = 2;$i < count($array);$i=$i+2){
                                      $temp = $i;
                                      $Start_Sleep_Time = $array[$temp];
                                      $Finish_Sleep_Time = $array[$temp+1];
                                      if($Start_Sleep_Time>$Finish_Sleep_Time){
                                        $sleepTime = intval($Start_Sleep_Time)-intval($Finish_Sleep_Time);
                                      }
                                      if($Finish_Sleep_Time>=$Start_Sleep_Time){
                                        $sleepTime = intval($Finish_Sleep_Time)-intval($Start_Sleep_Time);
                                      }
                                      $dayCount++;
                                      $allSleepTime += $sleepTime;
                                  }
                                  $avg = $allSleepTime/$dayCount;

                                  if($avg>=8){
                                    $score="Good";
                                  }
                                  if($avg<8 && $avg>=4){
                                    $score ="Moderate";
                                  }
                                  if($avg<4){
                                    $score = "Fair";
                                  }
                                  $DisplayCSV = TRUE;
                                  
                              }
                          }
                        }if($DisplayPic && !$DisplayForm && $DisplayCSV){
                          ?>
                          <div id="allgrid">
                              <div id="header">
                                  <h1><B><?php echo $_POST["name"] ?></B>'s Sleep time</h1>
                              </div>
                              <div id="img">
                          <img src="<?php echo $_FILES["fileToUpload"]["name"];?>" height="200" width="200">
                          </div>
                          <div id="paragraph">
                          <br>
                          <p><B>Name :</B> <?php echo $_POST["name"] ?></p>
                          <p><B>Age : </B><?php echo $_POST["age"] ?></p>
                          <p><B>Average Sleep time :</B> <?php echo $avg ?>  hours</p>
                          <p><B>Rate score : </B><?php echo $score ?></p>
                          <p><B>Total Sleep Hours : </B><?php echo $allSleepTime ?>  hours</p> 
                          
                          
                  
                          <br>
                          </div>
                          </div>
                          <?php
                          }
                          if($DisplayForm || !$DisplayPic || !$DisplayCSV){
                          ?>
                          <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                          <!-- Name -->
                          <div class="level-left">
                          <label class="label">Name</label>
                          </div>
                            <div class="control">
                              <input class="input is-primary" type="text" placeholder="Text input" name="name">
                              <p class="help is-danger"><?php echo $nameErr;?></p>
                          </div>
                          <!-- Age -->
                          <div class="level-left">
                          <label class="label">Age</label>
                          </div>
                            <div class="control">
                              <input class="input is-primary" type="text" placeholder="Text input" name="age">
                              <p class="help is-danger"><?php echo $ageErr;?></p>
                          </div>
                          
                          <!-- Image -->
                          <label class="label">Image</label>
                          <div class="level-left">
                          <div class="field">
                              <div class="file is-info has-name">
                                <label class="file-label">
                                  <input class="file-input" type="file" name="fileToUpload" id="imageFile">
                                  <span class="file-cta">
                                  <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                  </span>
                                    <span class="file-label">
                                      Image file…
                                    </span>
                                  </span>
                                  <span class="file-name" id="imageFilename">
                                    No file chosen
                                  </span>
                                </label>
                              </div>
                           </div>
                          </div>
                          <div class="level-left">
                            <span class="error"><?php echo $picErr;?></span>
                          </div>
                          <br>

                            <!-- CSVFile -->
                            <label class="label">CSV File</label>
                            
                            <div class="level-left">
                            <div class="field">
                              <div class="file is-primary has-name">
                                <label class="file-label">
                                  <input class="file-input" type="file" name="fileCSVToUpload" id="csvFile">
                                  <span class="file-cta">
                                  <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                  </span>
                                    <span class="file-label">
                                      CSV file…
                                    </span>
                                    
                                  </span>
                                  <span class="file-name" id="csvfilename">
                                    No file chosen
                                  </span>
                                </label>
                              </div>
                             </div>
                            </div>
                            <div class="level-left">
                            <span class="error"><?php echo $csvErr;?></span>
                            </div>  
                           <br>
                           <div class="level-left">
                              <div class="field is-grouped">
                                <p class="control">
                                <input type="submit" name="submit" class="button is-success">
                                </p>
                                <p class="control">
                                <input type="reset" name="reset" class="button is-danger">
                                </p>
                              </div>
                          </div>
                          </form>
                          <?php
                          }
                          ?>
                </div>
              </article>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="style.js"></script>

  <!-- footer -->
  <footer class="footer">
    <section class="section">
      <div class="container">
      </div>
    </section>
  </footer>
</body>

</html>