<?php
	//This file is to manage the styling of positions and creating the layout.
?>

.middle {
    background-color: #f8f8f8;
    padding: 10px;
    margin-bottom: 30px;
    box-shadow: 0.1px 0.1px 1px 0.9px #00000059;
    max-width: 99%;
}
.right{
	<!-- background: rgba(11, 16, 27, 0.4); -->
}


<?php
if($moduleObj->pagehasmodule($current_page, 'right')==1){
	//Here the right position is loaded with modules
	?>
	@media screen and (min-width:740px){
	/*Works when screen is greater than 740px*/
	.middle, .right{
		float: left;
		margin : 1% 1%;
		}
	.middle{
		width: 70%;
		}
	.right{
		width: 25%;
		}
}
<?php
}else{
	//Here the web is displayed as full
}

?>