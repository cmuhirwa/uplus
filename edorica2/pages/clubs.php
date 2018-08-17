<?php
WEB::require('css/clubs.css', 'head');
$Club = WEB::getInstance('club');
$School = WEB::getInstance('school');
?>

<h1 class="etitle"><em class="no-dec">Student Communities in their Clubs</em></h1>
<div class="clubs-foreword">
	Apart from the courses took in class for the curriculum students are in the commuinities of their interest for reasons. Different aims grabs us together in a prestigious community. Clubs are the good way to collaborate with friends, learn from diversity, pray and many more interets.
</div>

<div class="clubs-cont">
	<?php
		$Clubs = $Club->allClubs();
		for($n=0; $n<count($Clubs); $n++){
			$club = $Clubs[$n];

			//Schools in which club operates
			$schoolOp = $Club->clubschools($club['id']);
			?>
			<div class="club-card">
				<div class="club-intro">
					<div class="club-pic"></div>
					<div class="text">
						<p><?php echo $club['name']; ?></p>	
					</div>
					

				</div>
				<div class="club-desc">
					<div class="club-loc">
						<?php
							if(is_array($schoolOp) && !empty($schoolOp)){

								//Number of schools in which club is operates
								$nSchoolsLoc = count($schoolOp);

								for ($temp=0; $temp<$nSchoolsLoc && $temp<5;  $temp++) {
									$schoolClub = $schoolOp[$temp];
									$scdata = $School->getSchool($schoolClub);
									?>
										<p>
											<a href="<?php echo $School->link($scdata['name']) ?>">
												<?php echo $scdata['name']."<br />"; ?>								
											</a>
										</p>
									<?php
									
								}
								if($nSchoolsLoc>4){
									//Here some schools where not displayed, We better put a view more card.
									echo "string";
								}
							}
						?>
						<p><?php echo $club['description']; ?></p>
					</div>
				</div>
				

			</div>
			
		<?php
			}
	?>
	<div class="clear ogay"></div>
</div>

