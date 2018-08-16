<?php
	$Examres = WEB::getInstance("examres");
	$myPage = new page();
	$get = $myPage->get();
	$School = WEB::getInstance("school");
	global $conn, $exam_results;
	$User = WEB::getInstance("user");
	$user_id = $User->login_status(); //Login status
	$Combination = WEB::getInstance("combination");
?>

<?php
//Here am going to create the page structure, results viewed or ask user of student code
$lerror = array(); //This variable will keep errors that will be shown on the page


//checking if student code is set
if(isset($_POST['subt']) || isset($get['student'])){
	//Here the form was submitted with our web

	//checking if the code was set
	if(!empty($_POST['regcode'])  || !empty($get['student'])){

		$reg_code = mysqli_real_escape_string($conn, !empty($_POST['regcode'])?$_POST['regcode']:$get['student']);
		
		//Sanitizing code
		//We will trim spaces and remove - /
		$reg_code = str_ireplace(' ', '', str_ireplace('-', '', str_ireplace('/', '', $reg_code)));		
		
		//removing S3 and P6
		$reg_code = str_ireplace("S3", '', str_ireplace("P6", '', str_ireplace("S6", '', $reg_code)));
		
		//Validating the student's code
		$reg_code = strtoupper(mysqli_real_escape_string($conn, $reg_code));

		//Student combinatin
		$comb = $Examres->code2comb($reg_code); 

		if($Examres->validatecode($reg_code)){
			//Reg code is correct now
			//we are going to check for DB existence
			$data = $marks = $Examres->check($reg_code);

			if(!empty($data['id']) && !empty($data['meta'])){
				//Here we can merge
				$data = array_merge($marks['id'], $marks['meta'], $marks['marks']);
				$marks = $data;
			}

			//Check scrapping and getting marks
			if($marks || (($comb == "OLC" || $comb == "PRI") && $Examres->check_admission_scrap($reg_code) && $marks )){
				//Here the user exists in the DB
				//Let's set variable to display results
				$display_results =1;
				
				//Logging the view
				$Examres->logview($reg_code);
			}
			else{
				//Here we're going to scrap data and put in the DB
				$data = $Examres->scrap($reg_code);
				
				if($data){
					//Here the scrap was successful
					//Let's set variable to display results
					//Let's set the marks variable so as to display the data
					$display_results =1;
					
					$marks = $data;
					
					//Here we are going to insert data
					$classcode = $Examres->classcode($reg_code);
					
					//Logging who inserted data
					$Examres->resin($reg_code, 'anonymous', 'check');
					
					$Examres->insert($classcode, $marks['meta'], $marks['marks']);
					//Logging the view
					$Examres->logview($reg_code);
					
					}
				else{
					//Maybe the code is invalid
					$lerror = array_merge($lerror, array(array('en'=>"Student code is invalid", 'kin'=>"inumero iranga umunyeshuri yanditse nabi cyangwa ntibaho", 'value'=>4)));
				}
			}
			}else if($Examres->validateclass($reg_code)){
				$page->redirect("exam-results?class=$reg_code");
			}
		else{
			$lerror = array_merge($lerror, array(array('en'=>"Please enter student code", 'kin'=>"Inumero iranga umunyeshuri yanditse nabi cyangwa ntibaho", 'value'=>3)));
			}
		}
	else{
		//Here am going to set the error message to be shown and ask the parser to inlude the form with error
		$lerror = array_merge($lerror, array(array('en'=>"Please enter student code", 'kin'=>"Shyiramo inumero iranga umunyeshuri")));
		}
	}
?>

<?php
if((empty($lerror) && !empty($display_results) && $display_results==1 && $marks)){

	//Here we are getting school's information about the school
	$scode = $Examres->code2scode($reg_code);
	//Getting school's ID from regcode
	$scid = $School->code2school($scode);


    if($scid){
    	//Here the school's code is associated with the school in web
       	//Getting all school's data
       	$scdata = $School->getSchool($scid, "id");
       	$scname = $scdata['name'];
    }else{
    	$scname = false;
    }



	if(!empty($marks['meta'])){
		$marks = array_merge($marks['meta'], $marks['marks']);
		
        $marks = array_merge($marks, array('school'=>$scname));

	}

	// //Going to get the school's code and information
	// $scid = $School->code2school($reg_code); //Registration code to school ID

	// //Here we are getting school's information about the school
	// $scode = $Examres->code2scode($reg_code);

	//Here we display the results page;
	?>
	<div class="exam-results">
        <div class="meta-data">
            <ul class="person-info">
            	<div class="ex-meta-cont ">
	              	<li>Student Names: <?php echo $marks['name']; ?></li>
	                <?php if(!empty($marks['gender'])){ ?><li>Gender: <?php echo $marks['gender']; ?></li><?php } ?>
	                <li>Index Number: <?php echo $marks['code']; ?></li>
	                <?php if(!empty($marks['school'])){ ?> <li>School: <?php echo $marks['school']; ?></li> <?php } ?>
	            </div>
            </ul>
			<ul>
				<div class="ex-meta-cont ">
					<li>Aggregate: <?php echo $marks['aggregate']; ?></li>
	                <?php if($Examres->level($marks['code'])  == "S6"){ ?>
	                <li>Mention: <?php echo $marks['mention']; ?></li>
	                <?php }else{ ?>
	                	<li>Division: <?php echo $marks['division']; ?></li>
					<?php } ?>
	                <?php if(!empty($marks['grade'])){ ?><li>Grade: <?php echo $marks['grade']; ?> </li><?php } ?>
                </div>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="marks">
            <ul>
                <?php
				 	$classcode = $Examres->classcode($marks['code']);

				 	$codelevel = $Examres->level($reg_code);

				 	if($codelevel == 'S6'){
				 		$Examres->printmarks($data['marks']);
				 	}

				 	else if($classcode == "OLC"){
				  ?>
                	<li>Maths: <?php echo $marks['maths']; ?></li>
                    <li>Physics: <?php echo $marks['physics']; ?></li>
                    <li>English: <?php echo $marks['english']; ?></li>
                    <li>Kinyarwanda: <?php echo $marks['kinyarwanda']; ?></li>
                    <li>History: <?php echo $marks['history']; ?></li>
                    <li>Geography: <?php echo $marks['geography']; ?></li>
                    <li>Chemistry: <?php echo $marks['chemistry']; ?></li>
                    <li>Biology: <?php echo $marks['biology']; ?></li>
                    <li>Entrepreneurship: <?php echo $marks['entrepreneurship']; ?></li>
                   <?php
					}else if($classcode == 'PRI'){
						?>
                        <li>Maths: <?php echo $marks['maths']; ?></li>
                        <li>Science: <?php echo $marks['science']; ?></li>
                        <li>English: <?php echo $marks['english']; ?></li>
                        <li>Kinyarwanda: <?php echo $marks['kinyarwanda']; ?></li>
                        <li>Social Studies: <?php echo $marks['social_studies']; ?></li>
                   <?php } ?>                    
                </ul>
                
            </div>
            <?php
            	if( ($comb == 'PRI' || $comb == "OLC") && !empty($data['admission'])){
            ?>
	            <div class="admission">
	            	<div class="box-header">
	            		<h3>Your admission</h3>
	            	</div>
	            	<div class="box-cont">
	            		<?php
	            			//Looping through the data we have
	            			$adata = $data['admission'];
            				if($adata['school']){
            					?>
            						<li>School: <?php echo $adata['school']; ?></li>
            					<?php
	            			}else if($adata['combination']){
            					?>
            						<li>Combination: <?php echo $adata['combination']; ?></li>
            					<?php
	            			}else if($adata['headteacher']){
            					?>
            						<li>Head teacher: <?php echo $adata['headteacher']; ?></li>
            					<?php
	            			}else if($adata['phone']){
            					?>
            						<li>Contact phone: <?php echo $adata['phone']; ?></li>
            					<?php
	            			}else if($adata['location']){
            					?>
            						<li>Location: <?php echo $adata['location']; ?></li>
            					<?php
	            			}else if($adata['local_link']){
            					?>
            						<li><a href="<?php echo $page->getFile($adata['local_link'], $level) ?>">View admission letter(Babyeyi)</a></li>
            					<?php
	            			}

	            		?>
	            	</div>
	            </div>
            <?php } ?>
            <div class="mvat">
            	<div class="marks-cta">
            		<ul>
            			</ul>
            				<li>
            					<a class="fancy-btn" href="?class=<?php echo $Examres->code2class($reg_code)."&self=".str_ireplace($Examres->code2class($reg_code), '', $reg_code) ?>">View Class</a>
            				</li>
            			</li>
            			<li>
            				<?php
            					//Checking if user is logged in
            					
            					if($user_id){
            						// has saved current marks?
            						$marks_saved = $Examres->saved_marks($user_id, $reg_code);
            						if($marks_saved){
            							?>
            								<a class="fancy-btn" href="?save=<?php echo $reg_code; ?>">Saved</a>
            							<?php
            						}else{
            							?>
            								<a class="fancy-btn" href="?save=<?php echo $reg_code; ?>">Save Marks</a>
            							<?php
            						}
            					}else{
            						?>
            							<a class="fancy-btn" href="?save=<?php echo $reg_code; ?>">Save Marks</a>
            						<?php
            					}
            				?>
	            			
            			</li>
            		</ul>
            	</div>
            	<div class="marks-analysis">
            		<?php
            			$scname = !empty($scname)?$scname:$scode;
            			$scperflink = $School->perflink($scid);

            			//Checking if we know this school
            			if(!empty($scid)){
            				?>
            				<p><a href="<?php echo $scperflink; ?>"><?php echo ucwords($scname); ?> Exam performance</a></p>
            				<?php
            			}else{
            				//User should equip us with data on school to view its details
            				?>
            					<p>
            						You should provide some few information of school to get its performance analysis.
            						<a href="#">Provide as much as you can</a>
            					</p>
            				<?php
            			}    			
            		?>
            		

            	</div>
            </div>
    </div>
    <?php
}
else if(is_array($get) && array_key_exists("class", $get)){
	//This is the page too come when there is request to display class's marks
	//Here We have to validate if the class is correct
	$class = mysqli_real_escape_string($conn, $get['class']);
	$class = strtoupper($class);

	if(!empty($class)){
		$validateclass = $Examres->validateclass($class);

		if($validateclass){
			?>
			<?php
				//Display class - school's  summary
				//Getting schools' data

				$student_code = $Examres->classexcode($class); //Getting one student from this class for demonstartion
				$scode = $Examres->code2scode($student_code);
				$scid = $School->code2school($scode);

				//Getting current class's combination
				$ccomb = $Examres->code2comb($student_code);

				
				if($scid){
					//Here we have the school recognized
					$scdata = $School->getSchool($scid);	

					$scname = $scdata['name'];		

					//Getting number stident's marks stored in the database
					$nstudents = $Examres->nclass($student_code, 'student');
					?>
						<h1 class="page-title">Exam Results for <span><?php  echo $School->comb_name($ccomb); ?></span> - <span title="We thought <?php echo $scdata['code'] ?> is of this school" class='tooltipelem tooltip'><?php echo $scname ?></span> - <span><?php echo $scode; ?></span></h1>
					<?php
				}else{
					//The school code is not associated in the databasde
					?>
					<h1 class="page-title">Exam Results for <span title="Tell us school with this code" class='tooltipelem tooltip'>
						<?php echo $class; ?></span>
					</h1>
					<?php
				}
				//Here we're going to fetch the data of the class
				//First we check the class
				$num = $Examres->classnum($class);

				$fields = $Examres->resultsfields($class."001");

				//Printing format
				?>
				<div class='table-container'>
					<table class='exam-res-table rwd-table table'>
						<tr>
							<?php
								$classmarks = $Examres->getclassmarks($class);

								//Here we have to track the classmarks got to ensure that we have got the right class

								$lcount = 0; //Loop count
								$shortsubnames = array(); //We keep short subnames inside here

								$pformat = array('id', 'meta', 'marks');
								for($n=0; $n<count($pformat); $n++) {						
									foreach ($fields[$pformat[$n]] as $key => $value){
										//Getting short name of the course
										$subsname = $Examres->subpname($key, 'name', 'abbr');
										$subsname = !empty($subsname)?$subsname:$value; //Getting abbreviations from db

										$shortsubnames = array_merge($shortsubnames, array($key=>$subsname));
										?>
										<th title="<?php echo $value; ?>" class="marks-head"><?php echo $subsname; ?></th>
										<?php
									}
								}
							?>
						</tr>
						<?php
							//Printing marks
							$stcodes = array_keys($classmarks); //All student codes

						//Getting identification fields into 'meta' because this will adapt to the format of ::getMarks()
						$fields['meta'] = array_merge($fields['id'], $fields['meta']);
						unset($fields['id']); //Removing ID fields as they were put in meta to avoid duplicate data printing
						for($nm=0; $nm<count($stcodes); $nm++){
							//We're looping student codes
							$stmarks = $classmarks[$stcodes[$nm]];  //Getting current student's marks - in the loop

							//Here we're going to dernormalize the table put id in meta
							if(!empty($stmarks['id'])){
								$stmarks['meta'] = array_merge($stmarks['meta'], $stmarks['id']);							
							}

							$apformat = array('meta', 'marks'); //Defining the marks print order

							?> <tr> <?php

							//Looping through the print order
							for($nformat=0; $nformat<count($apformat); $nformat++){

								foreach($fields[$apformat[$nformat]] as $fkey=>$fvalue){
									$srec = !empty($stmarks[$apformat[$nformat]][$fkey])?$stmarks[$apformat[$nformat]][$fkey]:''; //This is a specific marks record
									if($fkey =='code'){
										//Here we have to remove the whole student code and put his sequential number in class
										?>
											<td data-th="<?php echo $shortsubnames[$fkey]; ?>" title="<?php echo $srec; ?>"><?php echo str_ireplace($class, '', $srec); ?></td>
										<?php

									}else{
										?>
											<td data-th="<?php echo $shortsubnames[$fkey]; ?>" title="<?php echo $fkey; ?>"><?php echo $srec; ?></td>
										<?php
									}
								}

							}
							?></tr><?php							
						}
						?>
					</table>
				</div>
				<div class="class-perfanalysis">
					<h2></h2>
				</div>
					
					<?php
					?>
					<?php

					if(isset($_POST['subt'])){
						//Here we have to validate data
						if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['sname'])){
							//Inserting data in DB
							$name = mysqli_real_escape_string($conn, $_POST['name']);
							$email = mysqli_real_escape_string($conn, $_POST['email']);

							$sname = !empty($_POST['sname'])?mysqli_real_escape_string($conn, $_POST['sname']):'';
							$maxnum = !empty($_POST['nstud'])?mysqli_real_escape_string($conn, $_POST['nstud']):'';

							//Let's put this request in DB
							$query = mysqli_query($conn, "INSERT INTO scrap_request(code, maxnum) VALUES(\"$class\", \"$sname\")") or die(mysqli_error($conn));

							$scrap_id = mysqli_insert_id($conn);

							$query = mysqli_query($conn, "INSERT INTO scrap_users(name, email, class_request, school_name) VALUES(\"$name\", \"$email\", '$scrap_id', \"$sname\")") or die(mysqli_error($conn));
							if($query){
								echo "Thanks for your time!";
							}
						}
					}else{
					?>
						<div class="register-form contib">
							<h2 class="page-title">Help Us to improve this page</h2>
							<p>It's our pleasure that you are here! We are sorry but this list might be not full, Please provide us with info to correct that.</p>
							<p>Please provide us with this info to advance the page</p>
							<div class="form">
								<form method="POST" action="<?php echo $exam_results.'?class='.$class; ?>">
									<input type="text" name="name" placeholder="Your name" required="required"> *
									<input type="email" name="email" placeholder="Your email" required="required"> *
									<input type="text" name="sname" placeholder="School name">
									<input type="number" name="nstud" min="0" max="300" placeholder="Number of students in class">
									<div class="center">
										<button class="submit" type="submit" name="subt">Help US</button>
									</div>
								</form>
							</div>
						</div>
					<?php
				}
				?>
			<?php
		}
		else{
			//Her the code's verification was wrong
			?>
				<h1 class="page-title">Class code <?php echo $class; ?> looks invalid</h1>
				<p>
					Dear User,
					We are sorry but the code you are using seems invalid.
					We have received this error and we are following it. If you have more to tell about this error or any information to help us display this code or else, please contact us <a href="<?php echo $contact;?>">Here</a>
				</p>
			<?php
		}
	}
	else{
		//Here the class is empty, means no value set in the get request
	}
}
else if(is_array($get) && array_key_exists('save', $get)){
	$reg_code = !empty($get['save'])?$get['save']:'';

	//Checking if the registrtion code is set
	if(empty($reg_code)){
		?>
			<p>Please go back and check the index number you want to save your marks!</p>
			<a href="<?php echo $exam_results; ?>">Check your performance!</a>
		<?php
	}else{ 
		//Checking if the regcode provided is in correct format
		if(!$Examres->validatecode($reg_code)){
			?>
				<h1><?php echo $reg_code; ?> looks invalid</h1>
				<p>You tried to save marks for <?php echo $reg_code; ?>, but the registration code looks wrongly typed. Check it, and try again.</p>

				<p>If you are sure the code is correct, please contact us <a href="<?php echo $contact."?subject=Can't save results of $reg_code"; ?>">here</a>  for the query, we'll handle it as soon as we can.</p>
			<?php	
		}else{
			?>
				<h1>Saving marks - <?php echo $reg_code; ?></h1>
				<?php
					//Checking if the user is logged in					
					if($user_id){

						//Carrying out save action
						$temp = $Examres->result_save($reg_code, $user_id);
						if($temp){
							//result is saved by the user
							?>
								<p>Your marks have been saved successfully</p>
								<p>You will be redirected to your page of results saves</p>
								<div class="marks-cta">
									<ul>
										<li>
											<a href="<?php echo($exam_results.'?student='.$reg_code) ?>" class="fancy-btn">Go Back to marks</a>
										</li>
										<li>
											<a href="<?php echo($profile) ?>" class="fancy-btn">Profile</a>
										</li>
									</ul>
								</div>
								<?php header( "refresh:10; url=$profile" ); ?>
							<?php
						}else{
							?>
								<h1>We had error saving your code!</h1>
								<p>Please check if your code is correct and valid</p>
							<?php
						}
					}else{
						?>
							You are not logged in to Website, Please <a href="<?php echo $login; ?>">login</a> or <a href="<?php echo $register; ?>">create a new account</a> if you are new to save your Natinal exam results.

							<div class="link-tabs tabs-horizontal">
								<a class="link-elem" href="<?php echo "$login"; ?>">Login</a>
								<a class="link-elem" href="<?php echo "$register"; ?>">Register</a> 
							</div>
						<?php
					}
				?>
			<?php
		}
		
	}
}
else{?>
		<h1 class="etitle"><em class="no-dec">Rwanda National exams results - REB, WDA, TTC</em></h1>
		<div class="exams-foreword">
			Welcome to <?php echo _SITE_NAME; ?>, Here you get your national exams results from Rwanda Education Board - REB, Workforce Development Authority - WDA and Teacher Training Colleges - TTC (UR-CE).
			We appreciate your work in national exams, that's why we help you to enjoy the results them with ease. <?php echo _SITE_NAME; ?> takes your marks more than marks, but a reason to smile, a key to future; Then smile and get your future with us.
		</div>
	 	<!-- <h2 class="etitle">Amanota Y'ibizamini bya Leta</h2>
		<div class="exams-foreword">
			<p>Ikaze kuri <?php  echo _SITE_NAME; ?></p>
		    <p>Tugushimiye ubwitange, umurava n'ishyaka wagiranye amasomo yawe; uyu munsi ni ibyishimo. Dushimishijwe n'intsinzi yawe</p>
		</div> -->

		<?php
			ini_set("allow_url_include", 1);
			if(isset($_POST['subt']) || isset($get['student'])){
		?>
			<div class="form-info">
			<?php
				//Here form was validated
				if(!empty($_POST['regcode']) || !empty($get['student'])){
					//Here we are going to validate the input;
					$reg_code = mysqli_real_escape_string($conn, !empty($_POST['regcode'])?$_POST['regcode']:$get['student']);
					//$reg_code = mysqli_real_escape_string($conn, $_GET['student']);
					echo "$reg_code";

					$reg_code = trim(strtoupper($reg_code));
					if($Examres->validatecode($reg_code) || $Examres->validateclass($reg_code)){
					//Determining the class
					if(preg_match('^OLC^', $reg_code)){
						$classtable = "ores";
						$classcode = urlencode("Senior 3");
						}else{
							$classtable = 'pres';
							$classcode = urlencode("Primary 6");
							}

						//Going to check DB if the user is grabbed
						$query = mysqli_query($conn, "SELECT * FROM $classtable WHERE code=\"$reg_code\"") or die(mysqli_error($conn));
						
						if(mysqli_num_rows($query)>=1){
							//Here The user is in the database
							echo "Conglatulations Brother";
							}										
					}
					else{
						echo "Your code seems invalid, please consider examples.";
						}
				}else{
					echo "Please enter your registration code!";	
					}
			?> </div> <?php
			}
			
		?>
		<?php include "modules/exam-results.php"; ?>

	<?php
	}//Closing the display for exa-results form entry
	?>
	<div class="opera-view-fix"></div>