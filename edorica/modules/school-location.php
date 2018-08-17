<?php
//This is the module for getting school by location

global $slocation;
?>
<div class="right-module">
	<div class="modbox">
		<p class="modtitle">Schools By Location</p>
		<div class="modcont">
			<?php
				require_once "scripts/location.php";
				//Here options are provinces from database
				$provinces = location::provinces();
				foreach ($provinces as $key => $value) {
					?>
					<p class="modlist"><a href="<?php echo location::provLink($value); ?>"><?php echo $value; ?></a></p>
					<?php
				}
			?>
			<p class="modaction"><a href="<?php echo $slocation; ?>">School Locations</a></p>
		</div>
	</div>
</div>