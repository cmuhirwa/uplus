<?php
WEB::require('css/clubs.css', 'head');
$Club = WEB::getInstance('club');
?>

<h1 class="etitle"><em class="no-dec">Student Communities in their Clubs</em></h1>
<div class="clubs-foreword">
	Apart from the courses took in class for the curriculum students are in the commuinities of their interest for reasons. Different aims grabs us together in a prestigious community. Clubs are the good way to collaborate with friends, learn from diversity, pray and many more interets.
</div>

<?php
$Clubs = $Club->allClubs();
for($n=0; $n<count($Clubs); $n++){
	$club = $Clubs[$n];
	?>
	<p><?php echo $club['name']; ?></p>
<?php
	}
?>: