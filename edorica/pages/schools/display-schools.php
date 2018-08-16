<?php
//Creating school instance

$mySchool = WEB::getInstance("school");
$current_name = $page->endPageName();

//$sc_name = $page->standardURL($current_name);

$scname = ucwords($current_name);

$scq = mysqli_query($conn, "SELECT * FROM schools WHERE name=\"$current_name\"");

if($scq){
	$scd = mysqli_fetch_array($scq);

	if($scd){
		$profile_picture = $scd['image'];
		$profile_picture = !empty($profile_picture)?$profile_picture: $edorica->getFile("images/schools/edorica-school.png", $page->level);
		$profile_picture = $edorica->getFile($profile_picture, $level);


		
		$sex = $scd['sex'];
		$id = $scd['id'];
		$school_motto = $scd['motto'];
		$owner = ucwords($scd['owner'])." School";
		$smartReg = $scd['smartReg'];

		$scdes = $scd['des'];
		$scmission = !empty($scd['mission'])?$scd['mission']:'';
		$scvision = !empty($scd['vision'])?$scd['vision']:'';
		$scode = !empty($scd['code'])?$scd['code']:'';

		//Keeping some school data in basket for other classes to access them
		$basket = WEB::getInstance("basket");
		$basket->set("school", array("id"=>$id, "code"=>$scode));
	}

}
?>

<h1 class="name">&nbsp;&nbsp;<em class="no-dec"><?php echo $scname; ?></em></h1>

<div class="profile_picture">

	<img src="<?php echo $profile_picture; ?>" alt="<?php  echo $scname; ?>">

</div>

<div class="school-welcome">
	<p class="p_intro">
		Welcome to <?php echo $scname; ?>,
	</p>

	<?php if(!empty($school_motto)){ ?><p class="motto"> <?php echo $school_motto; ?> </p><?php } ?>

</div>

<div class="main-info course-cont">

	<h2 class="info_header">Main Information</h2>

	<ul class="info_items">

		<?php
			$scID = $scd['id'];
			//Checking if school has combinations
			$scombs = $mySchool->combinations($scID);

			if(!empty($scombs)){?>

         <li>Type: <?php echo $mySchool->printcategory($scd['id']); ?></li>

		<li>Options: <?php echo $mySchool->printcombs($mySchool->combinations($scd['id'])); 
		?></li>

		<?php } ?>

		<li>Location: <?php echo $mySchool->slocation($conn, $scd['location']) ?></li>

		<?php
		if($mySchool->facilities($scd['id'])!="Facilities Not Found"){ ?><li>Facilities: <?php echo $mySchool->facilities($scd['id']); ?></li>
		<?php } ?>

		<li>Gender: <?php echo $mySchool->sex($scd['sex']); ?></li>

		<li>Status: <?php echo $owner; ?></li>
		<?php if(!empty($scode)){ ?><li>National Exam Code: <?php echo $scode; ?></li><?php } ?>

	</ul>

</div>

<?php
//Checking if school's overview can be rendered
if(!empty($scdes) || !empty($scmission) || !empty($scvision)){
	?>
	<div class="main-info course-cont">

		<h3 class="info_header">School's Overview</h3>
		<?php if(!empty($scdes)){ ?>
		<p>
			<?php echo $scdes ?>
		</p>
		<?php } ?>

		<div class="contbox-cont">
			<?php if(!empty($scmission)){ ?>
				<div class="bg-contbox">
					<p class="contbox-header"><em class="no-dec">Mission</em></p>
					<p>
						<?php echo $scmission; ?>
					</p>
				</div>
			<?php } ?>
			<?php if(!empty($scvision)){ ?>
				<div class="bg-contbox">
					<p class="contbox-header"><em class="no-dec">Vision</em></p>
					<p>
						<?php echo $scvision; ?>
					</p>
				</div>
			<?php } ?>
		</div>
		<div class="clear"></div>
	</div> <?php
} 
?>



<div class="main-info course-cont">

	

	<h4 class="info_header">Administration & Contacts</h4>

    <?php
	$schoolID = $scd['id'];

	//Selecting staff contacts with duty of directory or admin

	$conq = mysqli_query($conn, "SELECT * FROM staff WHERE school=$schoolID AND (duty='director' OR duty='admin'  OR duty='head teacher' OR quick_contact=1)") or die(mysqli_error($conn));

	$ncontacts = mysqli_num_rows($conq);

	if($ncontacts>=1){

			while($scontact = mysqli_fetch_assoc($conq)){

				

				//Gettig contact data
				$ppic = $edorica->getFile("images/".$scontact['profile'], $level);

				$name = ucwords($scontact['fname'].' '.$scontact['lname']);

				$duty = ucwords($scontact['duty']);

				$phone = $scontact['phone'];

				$email =  $scontact['email'];

			?>

			<div class="avatar-container">

				<div class="avatar">

				<img  src="<?php echo $ppic; ?>" />

                <li class='contact-name'><?php echo $name; ?></li>

                <li class='contact-duty'><?php echo $duty; ?></li> 

                <ul class="real-contacts">

                	<li><?php echo $phone==''?"":"<i class='fa fa-phone'></i> Phone: +$phone"; ?></li>

                    <li><?php echo $email==''?"":"<i class='fa fa-envelope'></i> e-mail: $email"; ?></li>

                </ul> 

			</div>

		</div>

			<?php

			}//Contacts look closing

		}

	else{

		?>

        <p>

            <li><?php echo $scname; ?> has no contacts</li>

            <li>However, we can try to contact the school for you.</li>

            <p><?php echo $page->site_contact(); ?></p>

        </p>

            <?php

            }

	?>


<?php
if(!empty($scd['website'])){
	$sc_website = $scd['website'];

	if(! (strpos("ok".$sc_website, "http://") || strpos("ok".$sc_website, "https://") )){
		$sc_website = "http://$sc_website";
	}
	?>
	<p class="center"><span class="fa fa-globe" ></span> Website: <a target="window" rel="no-follow" href="<?php echo $sc_website; ?>"><?php echo $sc_website; ?></a></p>
	<?php
}
?>
</div>

<div class="modbox">
	<div class="school-cta">
		<?php $mySchool->cta($id); ?>
	</div>
</div>

<div class="scomments">
	<div class="comment-role">
    	<p>Do you have something we can add or change about <span class="highlight"><?php echo $scname; ?></span>?</p>

        Comment it, we'll do it right.
    </div>

<div id="disqus_thread">
</div>

<script>
if(navigator.onLine){
var disqus_config = function () {

this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable

this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable

};



var disqus_config = function () {

this.page.url = <?php echo _WEB_ADDRESS; ?>;  // Replace PAGE_URL with your page's canonical URL variable

this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable

};

(function() { // DON'T EDIT BELOW THIS LINE

var d = document, s = d.createElement('script');

s.src = '//edorica.disqus.com/embed.js';

s.setAttribute('data-timestamp', +new Date());

(d.head || d.body).appendChild(s);

})();
}

</script>

<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

</div>