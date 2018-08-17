<?php
class mysql_pagination{
	
	function __construct($tablename, $rows){
		global $conn;
		$this->conn = $conn;
		
		//find out how many rows are in the table 
		$sql = "SELECT COUNT(*) FROM \'$tablename\'";
		$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$r = mysqli_fetch_row($result);
		$numrows = $r[0];
		
		// number of rows to show per page
		$rowsperpage = $rows;
		
		// find out total pages
		$totalpages = ceil($numrows / $rowsperpage);
		
		}
		function getnavs($pagenumber, $range){
			/******  build the pagination links ******/
			// range of num links to show
			// if not on page 1, don't show back links
			
			$ret_html ='';
			
			if ($currentpage > 1) {
			   // show << link to go back to page 1
			   $ret_html .= "<li> <a class='pag-last pag-elem' href='/$school_page?currentpage=1'>1..</a> </li>\n";
			   // get previous page num
			   $prevpage = $currentpage - 1;
			   // show < link to go back to 1 page
			   $ret_html .= "<li> <a  class='pag-prev pag-elem' href='/$school_page?currentpage=$prevpage'><</a> </li>";
			} // end if
			
			
			
			// loop to show links to range of pages around current page
			for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
			   // if it's a valid page number...
			   if (($x > 0) && ($x <= $totalpages)) {
				  // if we're on current page...
				  if ($x == $currentpage) {
					 // 'highlight' it but don't make a link
					 $ret_html .= " <li><a class='pag-elem pag-active'>$x</a></li> ";
				  // if not current page...
				  } else {
					 // make it a link
					 $ret_html .= "<li> <a class='pag-next pag-elem' href='/$school_page?currentpage=$x'>$x</a> </li>";
				  } // end else
			   } // end if 
			} // end for
			
			// if not on last page, show forward and last page links        
			if ($currentpage != $totalpages) {
			   // get next page
			   $nextpage = $currentpage + 1;
				// echo forward link for next page 
			   $ret_html .= "<li> <a class='pag-next pag-elem' href='/$school_page?currentpage=$nextpage'>></a> </li>";
			   // echo forward link for lastpage
			   $ret_html .= "<li> <a class='pag-last pag-elem' href='/$school_page?currentpage=$totalpages' title='Last Page'>..$totalpages</a> </li>";
			} // end if
			/****** end build pagination links ******/
			
		
		}
	}
?>