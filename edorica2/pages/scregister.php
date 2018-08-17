<?php

$myPage = new page();
$myComb = WEB::getInstance("combination");


//Including login class
include_once("scripts/login-class.php");
$myLogin = new login();

//Module class inclusion
$myModule = WEB::getInstance('module');

//Creating student registration class
$Register = new sRegister();

$mySchool = WEB::getInstance("school");

//regainVAlue function
include_once("pages/reg/reg_functions.php");

include_once("scripts/mail.php");
$myMail = new mail();

//I want it to be global that  can access it everywhere
$myUser = WEB::getInstance("user");
$userID = $myUser->id();

function validatephone($number){
	if(strlen($number)==10){}
	else if(strlen($number==13)){}
	//else echo $number;
	return true;
}

function chunkname($name){
	$parts = explode(" ", $name);

	$names = array();

	//Checking if the name is separated by space

	if(preg_match("^\w+\s+\w+^", $name)){
		$name = array("lname"=>$parts[count($parts)-1]);	
		$fname='';
		for($n=0; $n<count($parts)-1; $n++){
			$fname .= $parts[$n]." ";
		}
		$name = array_merge($name, array("fname" => $fname));

	}

	else{

		$name = array('fname' => $name, 'lname'=>'' );

	}

	return $name;
}
?>

<h1><em class="no-dec">Rwanda Online School Registration</em></h1>
<p><?php $myPage->printLink($home, _SITE_NAME); ?> provides easy and smart way of registering to schools and training centers.
    From its simplest you choose school and course you want to take.
    You tell a bit about you and that's all.
</p>
<div class="school-reg">
<?php
	//Checking step
	$getvars = $myPage->get();

	$routes = $myPage->routes;

	//Checking if the page is loaded from the school's page
	if($myPage->level>2 && count($routes)>2){
		//accessing the school name
		$current_name = str_ireplace("-", " ", $routes[1]);
	}

	if(isset($getvars['step']) && $getvars['step']!='' && $getvars['step']<5){

		$process = mysqli_real_escape_string($conn, $getvars['step']);

		}

	else $process=1;

	$getvars = !empty($getvars )?$getvars :array();

?>



<ul class="tab">
	<?php
	if(!empty($current_name) && $mySchool->is_school($current_name, "name") && !array_keys($getvars, 'final')){

		//Getting all information about school

		//$schoolName = ucwords($mySchool->is_school($current_name, "name")); is_school returns id on type name

		$schoolName = ucwords($current_name);

		$sq = mysqli_query($conn, "SELECT * FROM schools WHERE name=\"$schoolName\"") or die(mysqli_error($conn));

		$scdata = mysqli_fetch_assoc($sq);

		$scid = $id = $scdata['id'];

		$regLink = $mySchool->registerLink($id, 'id');							

		if(!$mySchool->smartreg($id)){
			?>
			<div class="modbox">
					<h3><a href="<?php echo $regLink; ?>"><?php echo ucwords($schoolName); ?> Does not support online registration</a></h3>
					<p>Thank you for using our website, We are sorry but this school does not use our online registration system.</p>
			</div>
			<?php
			exit();
		}

	?>
	<label class="tab1" for="1">
		<a href="<?php echo $mySchool->registerLink($id, 'id'); ?>"><span class="reg-label">1</span><h2 class="norm inline">School & Combination</h2></a>
	</label>

	<label class="tab2" for="2">
		<a href="<?php echo $mySchool->registerLink($id, 'id')."?step=2"; ?>">
	    	<span class="reg-label">2</span><h3 class="norm inline">Your Information</h3>
		</a>
	</label>                

	<label class="tab3" for="3">
		<a href="<?php echo $mySchool->registerLink($id, 'id').'?step=3'; ?>">
	    	<span class="reg-label">3</span>
	    	<h4 class="norm inline">Parents or Guardians</h4>
	    </a>
	</label>  

	<label class="tab4" for="4">
		<a href="<?php echo $mySchool->registerLink($id, 'id').'?step=4'; ?>">
	    	<span class="reg-label">4</span>
	    	<h5 class="norm inline">Academic Background</h5>
	    </a>
	</label>
</ul>                
<div class="contents">
	<div class="process-container p1">
	    <p class="process-des">Choose the combination you want and the school you want</p>

        <div class="regprocessbox">

	            <?php

					//Going to clean cookies of last session
		            $sclink = $mySchool->link($id);
		            echo "<p>Registering to <a href='$sclink'>$schoolName</a></p>";

					//Getting categories of school
					$cats = $mySchool->getcategory($scid);

					//Getting combinations taught in the school
					$scombs = $mySchool->combinations($scid);
					if(!$scombs){
						//Here no combination
						echo "<p>No combinations found on the school</p>";
						die();
					}

                ?>

         </div>

		<form method="POST" action="<?php echo $mySchool->registerLink($id, 'id'); ?>">
                <?php

				for($n=0; $n<count($cats); $n++){

					$catname = ucwords(str_ireplace("_", " ", $cats[$n]));

					//Getting the letter that represents classes in this category
					WEB::getInstance('category');
					$catLetter = category::catLetter($catname);

					//Getting combinations taught in the category
					$catcombs = $myComb->combs_taught($catname, 'pname');

					//Off which combinations taught in the category, which are also taught in the school 
					$combsInCat = array_values(array_intersect($catcombs, $scombs));
					?>

					<div class="cat_comb_cont">
						<p class="title category_title"><?php echo $catname; ?></p>
						<?php
						$already_chose_option='';

						//Looping through all combs taught in this category
						$ncatcombs = count($combsInCat); //Number of combs in cat taught in school
						for($tempCombs = 0; $tempCombs<$ncatcombs; $tempCombs++){
							//Storing current combination
							$ccomb = $combsInCat[$tempCombs];
							$displaymode = $myComb->displaymode($ccomb);
							$combname = $mySchool->comb_name($ccomb);
							//Checking classes taught in this combination in current school
							$classes = $mySchool->classcombs($ccomb, $scid);

							//Printing class contaier
							?>
								<div class="class-cont">
									<?php
									if($displaymode == 'full'){
										?>
										<div class="comb tipped">
											<p class="tooltip" title="<?php echo $combname; ?>"><span><?php echo $ccomb; ?></span></p>
										</div>
										<?php
									}else{
									?>
										<p class="comb"><?php echo $combname; ?></p>
									<?php
									}

							
							$classesCount = count($classes);
							if($classesCount==0){
								echo "<p class='class-choice'>No classes to register!</p>";
							}
							else{
								//Looping through classes
								for($i=0; $i<$classesCount; $i++){
									$cl = $classes[$i]; //Temporaral class level container
									$class_id = $catLetter."$cl$ccomb";//Temporal class ID 
									?>
										<div class="class-choice">
										<input  name="class" type="radio" <?php echo $already_chose_option== $catLetter."$cl"?"checked":''; ?> value="<?php echo $class_id; ?>" id="<?php echo "class$class_id"; ?>" />

										<label for="<?php echo "class$class_id"; ?>"><?php echo $catLetter.$cl; ?></label>

										</div>
									<?php
								}
							}

							//Closing class container
							?>
								</div>
							<!-- Class cont closing -->
							<?php

						}
						//Closing category container
						?>
					</div>
					<?php
				}
				?>
				<input name="p1" type="hidden" />

                <div class="align-right">
                	<button type="submit" name="submit" class="next-btn">Next Step</button>
                </div>
            </form>
            <?php

				if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submit'], $_POST['p1'])){
					//Handling first request
					if( isset($_POST['class']) && $_POST['class']!=''){
						//Sanitising class to avoid SQL injection

						$class = mysqli_real_escape_string($conn, $_POST['class']);

						

						//Calling sregister method to handle step 1 of registration

						if($Register->step1($schoolName, $class) ==1){

							$myPage->redirect($mySchool->registerLink($id, 'id')."?step=2");								

							};

						}

					else{

						echo "Please choose class and combination you want";

						}

				}
			?>

                </div>

                

            <div class="process-container p2">

                    <p class="process-des">Tell us basically about your self</p>

                    <?php

						$getvars = $myPage->get();

						if(isset($getvars['step']) && $getvars['step']!='' && $getvars['step']==2){

							

							if($Register->verify1()){

								//Here the user has done process 1\

								//Going to login or create account

								

								if(login_status()){

									//Here The User is logged in

									include_once("scripts/user.php");

									$myUser = new user();

									$userID = $myUser->id();

									

									//Getting user's phone, if it is already there

									$userdata = $myUser->info($userID);

									

									?>

                                    <div class="regprocessbox">

                                    	<p class="reg_rem"><?php echo $Register->reg_class()." in ".$schoolName;  ?></p>

                                        <p>Congratulations, Your Profile is alright!</p>

                                        <p>Please tell us a bit more</p>

                                    </div>

                                    <form method="post" action="<?php echo $mySchool->registerLink($id, 'id')."?step=2"; ?>" class="sregform sform">

                                    	 <p>I am,</p>

                                         <input name="role" value="student" type="radio" id="istudent" checked>

                                         <label for="istudent">Student</label>

                                         

                                         <input name="role" value="parent" type="radio" id="iparent">

                                         <label for="iparent">Parent</label>

                                         

                                         <input name="role" value="pos" type="radio" id="ipos">

                                         <label for="ipos">Parent of <?php echo $myUser->pfname($userID); ?></label>

                                         

                                         <label class="block">Fill in your contact phone Number</label>

                                    	 <input value="+250" class="inline_sform" readonly /><input name="tel" maxlength="10" value="<?php echo  isset($userdata['tel']) && $userdata['tel']!=''?str_ireplace("+250", '', $userdata['tel']):''; ?>" placeholder="788304561" type="tel" class="tel-input inline_bform" />

                                        <div class="btn-align">

                                        <div class="align-right"><button name="form2" class="next-btn" type="submit">Next</button></div>

                                        <div class="align-left"><button name="form2" class="prev-btn" type="button"><a href="<?php  echo $mySchool->registerLink($id, 'id')."?step=1"; ?>">Previous</a></button></div>

                                        </div>

                                    </form>

                                    <?php

									

									}

								else{

									//Ask The user for login or registration

									setcookie("pref_login_redirect", $mySchool->registerLink($id, 'id')."?step=2", time()+(5*60), "/");

									?>

                                    <div class="inline_login">

                                    	<h3>Login or <a class="link" href="<?php echo $register; ?>">Register</a></h3>

                                        <div class="req_login"><?php include($login_form); ?></div>

                                    </div>

                                    <?php

									 

									}

									

								//Processing form 2

								if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['form2']) ){

									//Checking if user role was submitted

									if(isset($_POST['role'])){

										$role = mysqli_real_escape_string($conn, $_POST['role']);

										

										

										//Checking phone

										if(isset($_POST) && $_POST['tel']!=''){

											$tel = mysqli_real_escape_string($conn, $_POST['tel']);

											

											//Adding country code

											$tel = "+250".$tel;

											

											if(strlen($tel) >=10 && strlen($tel) <= 15){

												//More Phone Number validation

												

												$telq = mysqli_query($conn, "UPDATE users SET tel='$tel' WHERE id = $userID") or die(mysqli_error($conn));

												//Going to set a cookie that will be used to validate step 2

												setcookie("step2", 1, time()+(60*100), "/");

												$myPage->redirect($mySchool->registerLink($id, 'id')."?step=3");

												}

											else if(strlen($tel) >= 15)  echo "<p class='warning-error'>Your phone number is too long!<br />It should be no more than 15 characters long</p>";

											else echo "<p class='warning-error'>Your phone number is short!<br />It should be at least 10 characters long</p>";

										}

										else echo "<p class='warning-error'>Please Enter your phone number!</p>";

										

										}

									else{

										//Warn The use to select the role

										echo "<p class='warning-error'>Please choose who u are!</p>";

										}

									}

								

								

								}

							else $myPage->redirect( $mySchool->registerLink($id, 'id'));

							}

								

					?>

            </div>

                

            <div class="process-container p3">

            	<div class="regprocessbox">

                    <p class="process-des">Tell us a bit about your parents and/or guardians</p>

                    <?php

					if($Register->verify2()){

						//Here step 2, was completed

						?>

                        <p></p>

                        

                        <?php

						//Processing form 3

						function validatename($name){

							//Checking Length

							if(strlen($name) >=3){

								if(preg_match("/^[a-zA-Z ]*$/", $name)){

									return true;

									}

								else echo "Special characters are not allowed";

								}

							else{

								echo "Name is too short";

								return false;

								}

							}

							

							

						

						

						?>

            

				</div>

                        <form method='post' action="<?php echo $mySchool->registerLink($id, 'id')."?step=3";?>" class="sform fieldset reg-form">

                        

                        <?php

						/*******************************/

						//Form validation

						if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['form3']) ){

							

							$father_field_error = '';

							//Validating father

							if(isset($_POST['fname']) && $_POST['fname']!=''){

								if(validatename($_POST['fname'])){

									$fname = mysqli_real_escape_string($conn, $_POST['fname']);

									

									//Checking phone

									if(isset($_POST['fphone'])){

										if(validatephone($_POST['fphone'])){

											$fphone = mysqli_real_escape_string($conn, $_POST['fphone']);

											}else $fphone= NULL;

										}else $fphone = NULL;

									

									//Checking e-mail

									if(isset($_POST['femail']) && $_POST['femail']!=''){

										if (filter_var( $_POST['femail'], FILTER_VALIDATE_EMAIL)){

											$femail = mysqli_real_escape_string($conn, $_POST['femail']);

											}else{

											$femail = NULL;

											echo "Invalid e-mail";

											}

										}

									else $femail=NULL;

									

									}

									else $fname=NULL;

									

									//Checking Guardian

									$gname= isset($gname)?$gname:NULL;

									$gemail = isset($gemail)?$gemail:NULL;;

									$gphone = isset($gphone)?$gphone:NULL;

									

									if(isset($_POST['mname']) && $_POST['mname']!=''){

											if(validatename($_POST['mname'])){

												$mname = mysqli_real_escape_string($conn, $_POST['mname']);

												

												//Checking phone

												if(isset($_POST['mphone'])){

													if(validatephone($_POST['mphone'])){

														$mphone = mysqli_real_escape_string($conn, $_POST['mphone']);

														}else $mphone= NULL;

													}else $mphone = NULL;

												

												//Checking e-mail

												if(isset($_POST['memail']) && $_POST['memail']!=''){

													if (filter_var($_POST['memail'], FILTER_VALIDATE_EMAIL)){

														$memail = mysqli_real_escape_string($conn, $_POST['memail']);

														}else{

														$memail = NULL;

														echo "Invalid e-mail";

														}

													}

												else $memail=NULL;

												

													

													}

													

													//Checking Guardian

													if(isset($_POST['gname']) && $_POST['gname']!=''){

														if(validatename($_POST['gname'])){

															$gname = mysqli_real_escape_string($conn, $_POST['gname']);

															

															//Checking phone

															if(isset($_POST['gphone'])){

																if(validatephone($_POST['gphone'])){

																	$gphone = mysqli_real_escape_string($conn, $_POST['gphone']);

																	}else $gphone= NULL;

																}else $gphone = NULL;

															

															///Checking relation

															

															

															//Checking e-mail

															if(isset($_POST['gemail']) && $_POST['gemail']!=''){

																if (filter_var($_POST['gemail'], FILTER_VALIDATE_EMAIL)){

																	$gemail = mysqli_real_escape_string($conn, $_POST['gemail']);

																	}else{

																	$gemail = NULL;

																	echo "Invalid e-mail";

																	}

																}

															else $gemail=NULL;

															

															

															

																

																}

														

														}

													else{

														$gname=NULL;

														$gemail =NULL;

														$gphone =NULL;

														} 

											

											

											/*******************************************************************************/

											

											//Checking if at least there's one parent's phone

											if(!empty($fphone) || !empty($mphone) || !empty($gphone) ){

												//Putting parents in the DB

												if(!empty($fname) && !empty($mname) ){

													//Putting the father in the database

													

													//Adding country codes in the database

													$fphone = !empty($fphone)?"+250".$fphone: NULL;

													$mphone = !empty($mphone)? "+250".$mphone: NULL;

													$gphone = !empty($gphone)? "+250".$gphone: NULL;

													

													

													if($Register->step3($userID, $fname, $femail, $fphone, "Father") && $Register->step3($userID, $mname, $memail, $mphone, "Mother") ){

														//Here father and mother were updated

														

														//Checking if guardian is set

														if(!empty($gname)){

															$Register->step3($userID, $gname, $gemail, $gphone, "Guardian");

															

															}

														else{

															echo "Guardian not specified";

															}

															

														$myPage->Redirect($mySchool->registerLink($id, 'id')."?step=4");

														}

													

													}

													else{

														echo "<p class='warning'>Error updating your parents</p>";

														}

												

												

												}

											else{

												echo "Enter at least one parent's phone number";

												}

											

											

											

											

											/*****************************************************************************/

											

											

											

											

											

											}

											else{

												$mname=NULL;

												echo "Enter Mother's  name";

												}

										

										}else{

												$father_field_error = "Enter father's name<br />";

												}

									

									

								}

						/*******************************/

						?>

                        <fieldset>

                        	<legend>Father</legend>

                            <div class="">

                                <label>Name:</label>

                                <input class="input" name="fname" placeholder="Enter Father's name" type="text" maxlength="256" value="<?php echo empty(retainValue('fname'))?$myUser->getparent($userID, 'father'):retainValue('fname'); ?>"/>

                                <?php echo isset($father_field_error)?"<p class='error-warning'>$father_field_error</p>":''; ?>

                            </div>

                            <div class="reg_elem">

                            	<label class="reg_form_label">Phone number:</label>

                            	<input value="+250" name="fphonecc" class="inline_sform" type="hidden" />

                                <div class="input">

                                    <p class="im_input inline">+250</p>

                                    <input name="fphone" type="tel" min='' maxlength="9" class="tel-input" placeholder="<?php echo _DUMB_PHONE; ?>" value="<?php echo empty(retainValue('fphone'))?str_replace('+250', '',$myUser->phone($myUser->parentID($userID, 'father')) ):retainValue('fphone'); ?>"/>

                            	</div>

                            </div>

							<?php

							

							

							?>

                            <div class="">

                            <label>Email: </label>

                            <input  class="input" name="femail" type="email" maxlength="256" placeholder="Father's email" value="<?php 

							echo empty(retainValue('femail'))?$myUser->email($myUser->parentID($userID, 'father')):retainValue('femail'); ?>"/>  	

                            </div>

                        </fieldset>

                        

                        <fieldset>

                        	<legend>Mother</legend>

                            <label>Name:</label>

                            <input name="mname" placeholder="Full name" type="text" maxlength="256" value="<?php 

							echo empty(retainValue('mname'))?$myUser->getparent($userID, 'mother'):retainValue('mname'); ?>"/>

                            <?php echo isset($mother_field_error)?"<p class='error-warning'>$mother_field_error</p>":''; ?>

							<div class="reg_elem">

                                <label class="reg_form_label">Phone number:</label>

                                <div class="input">

                                    <input value="+250" name="fphonecc" class="inline_sform" readonly type="hidden" />

                                    <p class="im_input inline">+250</p>

                                    <input name="mphone" type="tel" maxlength="9" placeholder="<?php echo _DUMB_PHONE; ?>" class="tel-input" 

                                    value="<?php

									echo empty(retainValue('mphone'))?str_replace('+250', '',$myUser->phone($myUser->parentID($userID, 'mother')) ):trim(retainValue('mphone'), " "); ?>"/>

                       			</div>

                            </div>

                            <div class>

                                <label>Email: </label>

                                <input name="memail" type="email" maxlength="256" placeholder="Mother's email" value="<?php 

                                echo empty(retainValue('memail'))?$myUser->email($myUser->parentID($userID, 'mother')):retainValue('memail'); ?>"/>  	

                        	</div>

                        </fieldset>

                        <fieldset>

                        	<legend>Guardian</legend>

                            <label>Name:</label>

                            <input name="gname" placeholder="Enter Guardian's name" type="text" maxlength="256" value="<?php echo empty(retainValue('gname'))?$myUser->getparent($userID, 'guardian'):retainValue('gname'); ?>"/>

                            <div>

                            <label>Who is this guardian: &nbsp;</label>

                            <select name="grelation" required>

                            	<option selected>Select</option>

                            	<option value="brother">Brother</option>

                                <option value="sister">Sister</option>

                                <option value="Uncle">Uncle</option>

                                <option value="Aunt">Aunt</option>

                                <option value="grandparent">Grandparent</option>

                                <option value="sponsor">Sponsor</option>

                                <option value="friend">Friend</option>

                            </select>

                            </div>

                            

                            <div class="reg_elem">

                            	<label class="reg_form_label">Phone number:</label>

                                <input value="+250" name="gphonecc" class="inline_sform" type="hidden" readonly />

                                <div class="input">

                                     <p class="im_input inline">+250</p>

                                    <input name="gphone" type="tel" maxlength="9" placeholder="<?php echo _DUMB_PHONE; ?>" class="tel-input" value="<?php echo empty(retainValue('gphone'))?str_replace('+250', '',$myUser->phone($myUser->parentID($userID, 'guardian')) ):retainValue('gphone'); ?>" />

                                </div>

                          </div>

                             <label>Email</label>

                            <input name="gemail" type="email" maxlength="256" placeholder="Guardian's email" value="<?php echo retainValue('gemail'); ?>"/>  	

                        </fieldset>

                        <div class="btn-align">

                           <div class="align-right"><button name="form3" class="next-btn" type="submit">Next</button></div>

                           <div class="align-left"><button name="form3" class="prev-btn" type="button"><a href="<?php  echo $mySchool->registerLink($id, 'id').'?step=2'; ?>">Previous</a></button></div>

                        </div>

                        </form>

                        <?php

						}

					else{

						echo "Please complete previous forms";

						?>

                        <div class="align-left">

                        	<button name="form3" class="prev-btn" type="button"><a href="<?php  echo $mySchool->registerLink($id, 'id').'?step=2'; ?>">Previous</a></button>

                        </div>

                        

                        <?php

                        }

						?>

                        </div><?php //Closing  process-container p3 ?>

                        

                    <div class="process-container p4">

                    	<p class="process-des">Tell us your previous education if you have been studying</p>

                    <?php

                    //Going to verify previous steps

					if($Register->verify3()){

						//Step 3 is competed successfully here we can ask academic records

						$class = $Register->reg_class();

						

						$classcat = $class[0];

						?>

                        <div class="form-container">

                        	<form action="<?php  echo $mySchool->registerLink($id, 'id').'?step=4'; ?>" method="POST" class="sform">

                            <div class="form-validation">

                            <?php

							if($Register->acreq($class)){

								//Here we are going to log the request in the database

								//We will check the requests of user, we'll allow only 3 simultaneous applications and only one application on one school

								$reqexiq = mysqli_query($conn, "SELECT * FROM requests WHERE user='$userID' AND STATUS!='approved'") or die(mysqli_error($conn));

								if(mysqli_num_rows($reqexiq)<_SIM_APPLICATIONS){

									//Here user could still apply to schools

									//INSERTING REQUEST IN DB

									//Checking if it's same school

									$exiq = mysqli_query($conn, "SELECT * FROM requests WHERE school='$id'") or die(mysqli_error($conn));

									if(mysqli_num_rows($exiq)>=1){

										//Here the user is registering to one same school, maybe in different class

										//Checking if classes are different

										$asdata = mysqli_fetch_assoc($exiq);

										$aclass = $asdata['class'];

										

										//Checking if classes are same

										if($aclass == $class){

											echo "You are always our pleasure!";

											//Here classes are same but we will not update time, maybe the user wanted to only change some details

											$myPage->Redirect($mySchool->registerLink($id, 'id')."?step=final");

											}

										else{

											echo "You are always our pleasure!<br />Thanks for interest in school";

											//Here we update class

											$preqid = $asdata['id']; //Previous req ID

											//Updating class in the request

											$ureq = mysqli_query($conn, "UPDATE requests SET class='$class', status='submission' WHERE id='$preqid'") or die(mysqli_error($conn));

											$Register->log($preqid, 'class_update', "User Repeated the reg process on the same school");

											$myPage->Redirect($mySchool->registerLink($id, 'id')."?step=final");

											}

										

										

										}

									else{

										//It's first time user is applying to this school
										$reqi = mysqli_query($conn, "INSERT INTO requests(user, school, class, submission_time, status) VALUES('$userID', '$id', '$class', CURRENT_TIMESTAMP, 'submitted')") or die(mysqli_error($conn));

										$reqid = mysqli_insert_id($conn);

										$Register->log($reqid, "submission", "User's action");

										$myPage->Redirect($mySchool->registerLink($id, 'id')."?step=final");

										}

	

									}

								else{

									//Here the user has more than maximum applications

									?>

                                    <p>You have reached maximum simultaneous applications</p>

                                    <p>Please wait untill you get response, or cancel some.</p>

                                    <?php

									}

								};

                            ?>

                            </div>

                            <div class="form">

                            </div>

                                

						<div class="btn-align">

                            <div class="align-right"><button name="form4" class="next-btn" type="submit">Submit!</button></div>

                            <div class="align-left"><button name="form2" class="prev-btn" type="button"><a href="<?php  echo $mySchool->registerLink($id, 'id')."?step=3"; ?>">Previous</a></button></div>

						</div>

                        </form>

                        	</div>

						<?php

						}

					else echo "Complete previous forms!";

					?>

                </div>

                

			</div>

            <?php }

    

	else if(isset($current_name) && $current_name!='' && $mySchool->is_school($current_name, "name") && array_keys($getvars, 'final')){

		?>

        <div class="reg_final">

        <p>Thank you for using this service!</p>

        <p>You can find info on your applications in <a href="<?php echo $profile; ?>">profile page</a></p>

        

        </div>

        <?php
	}                    

	else{

		echo "Please Choose Your Preferred"; $myPage->printLink($school, " School here!");

    }

?>





</div>

<?php



?>