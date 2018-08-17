<?php

$schools = $schoolObj = $mySchool = WEB::getInstance("school");

?>
<h1 class=" schools stitle">

	<li class="fatitle fa fa-graduation-cap"></li>Schools

</h1>

<div class="ptext">

	<p>

	<?php $page->printlink($home, _SITE_NAME) ?></a> offers number of schools to develop education.

	Get different <?php $page->printlink($mySchool->catLink(), "school categories") ?></a> 

	like <?php $page->printlink($mySchool->catLink("Nursery-Schools"), "Nursery Schools");?>, 

	<?php $page->printlink($mySchool->catLink("primary-Schools"), "Primary Schools") ?>, 

	<?php $page->printlink($mySchool->catLink("Secondary-Schools"), "Secondary Schools") ?>, 

	<?php $page->printlink($mySchool->catLink("Training-Centers"), "Training Centers") ?> and so on.

	You will also get different 

	<?php $page->printlink($mySchool->courselink(), "course"); ?> options like 

	<?php $page->printlink($mySchool->courselink("technical-studies"), "Technical Studies"); ?>, 

	<?php $page->printlink($mySchool->courselink("sciences"), "Sciences"); ?>, 

	<?php $page->printlink($mySchool->courselink("humanities"), "Humanities"); ?> and so on.

	</p>

</div>
<div class="schools-listing">
<?php

//find out how many rows are in the table 

$sql = "SELECT COUNT(*) FROM schools";

$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$r = mysqli_fetch_row($result);

$numrows = $r[0];



// number of rows to show per page

$rowsperpage = 5;

// find out total pages

$totalpages = ceil($numrows / $rowsperpage);



// get the current page or set a default

$mypage = new page();

$getvars = $mypage->get();

if (isset($getvars['currentpage']) && is_numeric($getvars['currentpage'])) {

   // cast var as int

   $currentpage = (int) $getvars['currentpage'];

} else {

   // default page num

   $currentpage = 1;

} // end if



// if current page is greater than total pages...

if ($currentpage > $totalpages) {

   // set current page to last page

   $currentpage = $totalpages;

} // end if

// if current page is less than first page...

if ($currentpage < 1) {

   // set current page to first page

   $currentpage = 1;

} // end if



// the offset of the list, based on current page 

$offset = ($currentpage - 1) * $rowsperpage;


$schoolquery = mysqli_query($conn, "SELECT * FROM schools ORDER BY name ASC LIMIT $offset, $rowsperpage") or die(mysqli_error($conn));

while($scdata = mysqli_fetch_assoc($schoolquery)){

$scname = $scdata['name'];
$id = $scdata['id'];

$scimage = $edorica->getFile($scdata['image'], $page->level);

$scimage = !empty($scimage)?$scimage: $edorica->getFile("images/schools/404.jpg", $page->level);

?>

	<div class="school-panel">

        <?php //Printing Picture when It's available

			if($scimage!="images/schools/404.jpg"){?>

		<div class="profile-picture">

			<img src="<?php echo $scimage; ?>" alt="profile Picture of <?php echo $scname; ?>" />

		</div>

        <?php

			}

		?>

		<div class="info">

			<div class="name">				
				<p><a href="<?php echo $mySchool->link($id); ?>"><em class="no-dec"><?php echo $scname; ?></em></a></p>
			</div>

				<div class="main-info">

				<ul>

					

					<?php

						//Checking school has combinations

						if(isset($scdata['combinations']) || $scdata['combinations']!=''){

					?>

                    <li>Type: <?php echo $schools->printcategory($scdata['id']); ?></li>

                    <li>Options: <?php echo $schools->printcombs($schools->combinations($scdata['id'])); ?></li>

					<?php } ?>

					<li>Location: <?php echo $schoolObj->slocation($conn, $scdata['location']) ?></li>

					<li>Facilities: <?php echo $schools->facilities($scdata['id']) ?></li>

				</ul>

			</div>

			<div class="school-cta">

				<?php echo  $mySchool->qcta($scdata['id']); ?>

			</div>

		</div>

		<div class="clear"></div>

	</div>

    <?php } ?>

    <div class="schools-pagination">

    <ul class="pag-elem-cont pagination	">

    	<?php

		/******  build the pagination links ******/

		// range of num links to show

		$range = 2;

		

		// if not on page 1, don't show back links

		if ($currentpage > 1) {

		   // show << link to go back to page 1

		   echo "<li> <a class='pag-last pag-elem' href='/$school_page?currentpage=1'>1..</a> </li>";

		   // get previous page num

		   $prevpage = $currentpage - 1;

		   // show < link to go back to 1 page

		   echo "<li> <a  class='pag-prev pag-elem' href='/$school_page?currentpage=$prevpage'><span class='fa fa-angle-double-left'></span> Previous</a> </li>";

		} // end if 

		

		// loop to show links to range of pages around current page

		for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {

		   // if it's a valid page number...

		   if (($x > 0) && ($x <= $totalpages)) {

			  // if we're on current page...

			  if ($x == $currentpage) {

				 // 'highlight' it but don't make a link

				 echo " <li><a class='pag-elem pag-active'>$x</a></li> ";

			  // if not current page...

			  } else {

				 // make it a link

				 echo "<li> <a class='pag-next pag-elem' href='/$school_page?currentpage=$x'>$x</a> </li>";

			  } // end else

		   } // end if 

		} // end for

		

		// if not on last page, show forward and last page links        

		if ($currentpage != $totalpages) {

		   // get next page

		   $nextpage = $currentpage + 1;

			// echo forward link for next page 

		   echo "<li> <a class='pag-next pag-elem' href='/$school_page?currentpage=$nextpage'>Next <span class='fa fa-angle-double-right'></a> </li>";

		   // echo forward link for lastpage

		   echo "<li> <a class='pag-last pag-elem' href='/$school_page?currentpage=$totalpages' title='Last Page'>..$totalpages</a> </li>";

		} // end if

		/****** end build pagination links ******/

		?>

       </ul><?php //Closing pagination ?>

    </div>
</div>

