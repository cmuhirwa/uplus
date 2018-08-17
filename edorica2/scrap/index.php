<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<title>The scrapper</title>
</head>
<body>
<?php
//Scrapper WITH JAVASCRIPT
include_once("botcon.php");
//Getting all requests with low frequency
$query = mysqli_query($botconn, "SELECT * FROM scraprequests WHERE freq<1 GROUP BY code") or die(mysqli_error($botconn));
?>
<div class="scrapreqs">
	<h1></span>Choose code to grab from the requested</h1>
	<ul class="scrap-elem-cont">
	<?php
	while($data = mysqli_fetch_assoc($query)){
		//Listing all the codes for scrap
		$code = $data['code'];
		?>
		<li class="scrap-elem <?php echo $code; ?>">
			<a href="getpage?code=<?php echo $code; ?>" id="p<?php echo $code; ?>"><?php echo $code; ?></a> <input type="checkbox" class="scrapcheck" id="pll<?php echo $code; ?>">YES</span>
		</li>
		<?php
	}
	if(mysqli_num_rows($query)>2){
			//Here we show a select all
			?>
				<div class="showall">
					<input type="checkbox" name="" id="sall"> Select all
				</div>
			<?php
		}
	?>
	</ul>
</div>
<div class="transfers">
</div>
<div class="debug"></div>
<script src="jquery.js"></script>
<script>
$( ".scrapcheck" ).click(prequest)

function prequest(){
		//Process request
		if ($(this).is(':checked')) {
			changedelem = this.id.replace("pll", "");
			code = changedelem;

			//Getting the page
			$.get("getpage.php?code="+code, function(data, status){
				serverlet = JSON.parse(data);
				if(serverlet.status=1){
					//Successuflly grabbed marks

					//File path
					fpath = serverlet.path;

					$(".transfers").append("<li><span class='fa fa-check scrap-success'></span> <span class='inline'>Successuflly grabbed <a class='greylink grabprev' target='window' href='"+fpath+"'>"+code+"</a></span></li>");
					$("input[id=pll"+code).addClass('scrapped'); //Marking the scrapped element
				}else{
					$(".transfers").append("<span class='fa fa-times scrap-fail'></span> <li class='inline'>Successuflly grabbed <a class='greylink grabprev' target='window' href='"+fpath+"'>"+code+"</a></li>")
				}
				//Removing the grabbed codes
				autoremove();
			})
	  	}
}


//setInterval(autoload, 15000); //autoloading every 10 seconds

$("#sall").on("click", function(){
		//check or uncheck all
		var ucheckelems = $(".scrapcheck");
		console.log(ucheckelems)
		for(n=0; n<ucheckelems.length; n++){
			ucheckelems[n].click();

	}

});
function autoload(){
	//This function helps to autoload results
	$.post("scrapreq.php", {'get':10}, function(data, status){
		var codes = JSON.parse(data);
		ncodes = codes.length;
		if(ncodes>0){
			//Here we have to loop in elements
			for(n=0; n<ncodes; n++){
				//Here we have to check if the code is already in the list
				ccode = codes[n];
				if(!listed(ccode) && !processed(ccode)){
					//Here the code is not listed in successfully scrapped codes
					console.log("From autoload "+ccode);
					adreq(ccode);

				}
				
			}
		}
	})
}
function processed(code){
	//Here we check if the code was successfull processed
	var trial = $("#pll"+code);
	if(trial.hasClass('scrapped')){
		return true;
	}else{
		return false;
	}

}
function listed(code){
	//Here we check if the code was successfull scrapped
	var trial = $("#pll"+code);
	if(trial.length>0){
		return true;
	}else{
		return false;
	}
}
function autoremove(){
return false;
	//This function will remove codes when their scraps are done
	//Getting all elements listed
	var elems = $(".scrap-elem");
	for(n=0; n<elems.length; n++){
		code = elems[n].classList[1];

		//Checking the scrap status
		console.log('Checking the elements we can remove')
		$.post('scrapreq.php', {'check':code}, function(data){
			pares = JSON.parse(data);
			status = pares.status;
			console.log(status+" for "+code);
			if(status==1){
				//Here we have to remove the user
				$(".scrap-elem."+code).remove();
			}else{
				//ok.
			}
		})
	}
}
function adreq(code){
	//Adding code in the list of scrap req
	console.log("From adreq "+code);
	$(".scrap-elem-cont").append("<li class='scrap-elem "+code+"'></li>");
	var codecont = $(".scrap-elem."+code);
	codecont.html("<a href='getpage?code="+code+"' id='p"+code+"'>"+code+"</a><input type='checkbox' class='scrapcheck' id='pll"+code+"'>YES</span>");
	
	var cbox = $("#pll"+code)
	cbox.on('click', prequest);
}
</script>

</body>
</html>