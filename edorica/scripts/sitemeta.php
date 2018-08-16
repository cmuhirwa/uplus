<?php
	class sitemeta{
		//Function to handle metadata of site, generation
		public $page;
		public $pagename;
		public $current_page;
		public $current_name;
		function __construct(){
			$page = WEB::getInstance("page");
			$this->current_page = $page->dbname;
			$this->current_name = $page->name;
		}
		

		public function title($pagename = ""){
			//returns title for title of $pagename
			global $conn, $current_name;

			//if title method was defined
			$funcname = str_ireplace("-", "_", $this->current_page);
			if(method_exists($this, $funcname)){
				//returning title from function
				return $this->$funcname();
			}

			$titlequery = mysqli_query($conn, "SELECT title, level FROM pages WHERE name=\"$this->current_page\"");
			if($titlequery){
				$titledata = mysqli_fetch_assoc($titlequery);
				
				//Checking if there is available data-title, level in the database
				//Previous codes have done job of detecting invalid pages and setting them to not_found but here i want again to test
				if($titledata){
					$title = $titledata['title'];
					
					//Checking if We are searching title of subpage
					if(isset($titledata['level']) && $titledata['level']!='' && $titledata['level']>1){
						$ttlevel = $titledata['level'];

						//Here we have to take the title template from the page's title and inject data
						//$current name is the real page name from page::endPageName which corresponds to $item in temlpate
						$title = str_ireplace('$item', $current_name, $title);

						//Sanitizing title by removing hyphens and triming spaces
						$title = trim($title);
						$title = str_ireplace("-", " ", $title);
						$title = str_ireplace("_", " ", $title);
						$title = ucwords($title); //Capitalising title
					}
				}
				else{
					//Page could not be found in the database probably
					$title = _NOT_FOUND;
				}

				return $title;
			}else{
				$catq = mysqli_query($conn, "SELECT items, handler FROM subpages FULL JOIN pages ON parent=`pages`.`id` WHERE `pages`.`name`='$name'");
				if($catq){
					$catd = mysqli_fetch_assoc($catq);
					$handler = $catd['handler'];				
					
					$items = $catd['items'];
					//Here We Check If There found items to be represented, if $items is empty. then items cant be found
					if($items == '' || $handler == ''){
						//No items found to be rendered or no handler page to display items
						$current_page = _NOT_FOUND;
						//break;
					}

					$items = explode(",", $items);
							
							$hq = mysqli_query($conn, "SELECT * FROM pages WHERE id='$handler' AND level=$temp");
							if($hq){
								$hd = mysqli_fetch_assoc($hq);
								
								//Checking if subpage item exists
								if($temp+1 != $n){
									$data = mysqli_real_escape_string($conn, $pages[$temp-1]);
									$table = mysqli_real_escape_string($conn, $items[0]);
									$field = mysqli_real_escape_string($conn, $items[1]);
									$table = trim($table);    //Removing possible useless spaces
									$field = trim($field);    //Removing possible useless spaces
									
									//Checking if current fiels is space separated and then forming links - here there will be third array offset						
									if(isset($items[2]) && trim($items[2])=='-') $data = $page->URLtostring($data);
									
									
									$data_query = mysqli_query($conn, "SELECT * FROM `$table` WHERE `$field`='$data'");
									
									if($data_query){
										$datad = mysqli_fetch_assoc($data_query);
										if($datad){
											$current_page = $hd['name'];
											//break;
											}
										else{
											$current_page = _NOT_FOUND;
										}
									}
									//If the page is not found in reference list in database 
									else{
										$current_page = _NOT_FOUND;
									}
								}
							}
							else $current_page='not_found';
				}
				else{
					$current_page='not_found';
				}
				return $current_page;
			}

		}

		public function exams_performance(){
			//Here we want to show school name and performance analysis
			global $page;
			$path = $page->getpath()['call_parts'];
			$scname = $path[array_search("exams-performance", $path)-1];

			$title = "$scname's national examination performance";

			//Sanitizing title by removing hyphens and triming spaces
			$title = trim($title);
			$title = str_ireplace("-", " ", $title);
			$title = str_ireplace("_", " ", $title);
			$title = ucwords($title); //Capitalising title
			return $title;
		} 

	}
?>