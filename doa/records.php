<?php
if (isset($_GET['sourceId'])) 
{
	if($_GET['sourceId']== 1){
?>
<!--Results of source 1-->
		<a href="javascript:void()" onclick="callDetails(11)" id="record11" class="list-group-item">Car</a>
		<a href="javascript:void()" onclick="callDetails(12)" id="record12" class="list-group-item">House</a>
		<a href="javascript:void()" onclick="callDetails(13)" id="record13" class="list-group-item">Land</a>
<?php
	}
	elseif ($_GET['sourceId']== 2) {
		?>
		<a href="javascript:void()" onclick="callDetails(1)" id="record21" class="list-group-item">Master's Degree</a>
		<a href="javascript:void()" onclick="callDetails(2)" id="record22"  class="list-group-item">Bachaloret Degree</a>
		<a href="javascript:void()" onclick="callDetails(3)" id="record23"  class="list-group-item">A'level Diploma</a>
		<a href="javascript:void()" onclick="callDetails(4)" id="record24"  class="list-group-item">O'level Diploma</a>
		<a href="#" class="list-group-item">Primary Diploma</a>
		<a href="#" class="list-group-item">Nursary Diploma</a>
		<div class="input-group">
			<div class="input-group-addon">25/REB/</div>
			<input type="text" class="form-control" placeholder="Education Handle Id here!">
			<span class="input-group-btn">
				<button class="btn btn-default" type="button">Claim!</button>
			</span>
		</div>
<?php
	}
	elseif ($_GET['sourceId']== 3) {
?>
<a href="#" class="list-group-item">No criminal record yet.</a>
<?php	
	}elseif ($_GET['sourceId']== 5) {
?>
	<a href="#" class="list-group-item">Wife</a>
	<a href="#" class="list-group-item">KidA</a>
	<a href="#" class="list-group-item">Kid2</a>
	<a href="#" class="list-group-item">Parent1</a>
	<a href="#" class="list-group-item">Parent2</a>
<?php	
	}else{
		# code...
	}
}
elseif (isset($_GET['recordId'])) {
		if($_GET['recordId']== 1){
?>
<!--Results of record 1-->
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Item</th>
					<th>Info</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Institution</td>
					<td>University Of Rwanda</td>
				</tr>
				<tr>
					<td>Marks</td>
					<td>77%</td>
				</tr>
			</tbody>
		</table><div class="fa fa-file-alt fa-2x" style="
			    margin: 0 10px;
			    border-radius: .5rem;
			    background: #dfdedd; 
			    box-shadow: 0 0.25rem 0.125rem 0 rgba(0,0,0,.1);
			    float:  right;
			    cursor:  pointer;
			    margin:  0 auto;
			    padding: 10px;
			"></div>
<?php
	}
	elseif ($_GET['recordId']== 2) {
		?>
		<div class="fa fa-file"></div>
<?php
}
	elseif ($_GET['recordId']== 11) {
		?>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Item</th>
					<th>Info</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Brand</td>
					<td>Toyota Rav4</td>
				</tr>
				<tr>
					<td>Number Plate</td>
					<td>RAD 459 C</td>
				</tr>
			</tbody>
		</table>
		<div class="input-group">
			<div class="input-group-addon">25/NIDA/</div>
			<input type="text" class="form-control" placeholder="...">
			<span class="input-group-btn">
				<button class="btn btn-primary" type="button">TRANSFER</button>
			</span>
		</div>
<?php
}
elseif ($_GET['recordId']== 12) {
		?>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Item</th>
					<th>Info</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Location</td>
					<td>Kimironko / Bibare</td>
				</tr>
				<tr>
					<td>Number Plate</td>
					<td>RAD 459 C</td>
				</tr>
			</tbody>
		</table>
		<div class="input-group">
			<div class="input-group-addon">25/NIDA/</div>
			<input type="text" class="form-control" placeholder="...">
			<span class="input-group-btn">
				<button class="btn btn-primary" type="button">TRANSFER</button>
			</span>
		</div>
<?php
}
elseif ($_GET['recordId']== 13) {
		?>
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Item</th>
					<th>Info</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Loacation</td>
					<td>Kigali / Kimironko</td>
				</tr>
				<tr>
					<td>SquireMile</td>
					<td>564</td>
				</tr>
			</tbody>
		</table>
		<div class="input-group">
			<div class="input-group-addon">25/NIDA/</div>
			<input type="text" class="form-control" placeholder="...">
			<span class="input-group-btn">
				<button class="btn btn-primary" type="button">TRANSFER</button>
			</span>
		</div>
<?php
}

}
?>
