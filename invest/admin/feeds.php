
<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);			
?>

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php
	include 'userheader.php';
	include 'functions.php';
?>

<!-- main sidebar -->
<div id="new_comp">
<div id="page_content">
	<?php
		if(!empty($_GET['branch'])){
			$branchid = $_GET['branch'];
			$branch_data = get_branch($branchid);
			$branch_name = $branch_data['name'];

			$branch_representative = branch_leader($branchid, 'representative');
			?>
				<div id="page_content_inner">
					<h3 class="heading_b uk-margin-bottom"><?php echo $churchname." - $"; ?></h3>
				</div>
			<?php
		}else{
			?>
			<div id="page_content_inner">
				<h3 class="uk-margin-bottom">Feeds</h3>

				<!-- Feedds input -->
				<div class="uk-grid" data-uk-grid-margin="">
					<div class="uk-margin-bottom uk-width-medium-2-3 uk-row-first">
						<div class="md-card">
							<div class="md-card-content">
								<form id="feed_create_form">
									<!-- <div class="md-input-wrapper md-input-filled">
										<label>Feed title</label>
										<input type="text" name="feedtitle" id="forumtitle_input" value="<?php //echo $forum_title; ?>" class="md-input" required="required">
										<span class="md-input-bar "></span>
									</div> -->
									<div class="uk-form-row">
									   <textarea cols="30" rows="4" class="md-input feeds-textarea" id="post_content" placeholder="Something to tell?" required="required"></textarea>
									</div>
									<div class="uk-form-row">
									   <div class="uk-grid">
											<div class="uk-width-1-4">
												<div class="uk-form-file md-btn" style="box-shadow: 0 1px 3px rgba(0, 0, 0, 0), 0 1px 2px rgba(0, 0, 0, 0);">
													<img src="gallery/upload_feed_icon.png">
													<input id="feeds_attachment_input" type="file" multiple>
												</div>
											</div>
											<div class="uk-width-1-3" id="feed-thumbnail-tpl-cont">
												<div class="feed-thumbnail-upload" style="display: none;" id="feed-thumbnail-tpl">
													<div class='thumb-title'>Here <i class="material-icons" style="cursor: pointer; position: relative;left: 170%">close</i></div>
													<div class="thumb-content"></div>
													<div class="thumb-toolbar">
														<progress class="uk-progress feed_attachment_progress" value="10" max="100">
														<div class="uk-griad">
															<div class="uploaded-size"> 0MB</div>
															<div class="total-size"> 100mb</div>
														</div>
													</div>
												</div>
											</div>
											<div class="uk-width-1-4">
												<div>
													<select class="md-input" id='postTo' data-uk-tooltip="{pos:'top'}" title="Choose the target of this feed">
														<option>-- Target --</option>
														<option value="public">Public</option>
														<?php
															$forums = getForums();
															foreach ($forums as $key => $forum) {
																?>
																	<option value="<?php echo $forum['id'] ?>"><?php echo $forum['title'] ?></option>
																<?php
															}
														?>
													</select>
													
												</div>
											</div>
											<div class="uk-width-1-1 uk-margin-top">
												<span class="progress-cont display-none"><img src="assets/img/spinners/spinner_success.gif" alt="" width="32" height="32"></span>
												<button class="md-btn md-btn-primary" id="submit_feed" type="submit">Post</button>
											</div>
									   </div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="uk-margin-bottom uk-width-medium-1-3">
						<div class="md-card">
							<div class="md-card-content">
								<h3 class="heading_c uk-margin-medium-bottom">Filter Feeds</h3>
								<div class="posts_filter">
									<?php
										foreach ($forums as $key => $forum) {
											?>
												<p>
													<!-- <input type="radio" name="radio_demo" data-forum_id = "<?php echo $forum['id'] ?>" id="radio_demo_2" class="filter-elem" data-md-icheck /> -->
													<a href="feeds.php?forum=<?php echo $forum['id'] ?>" style='color: inherit;'><?php echo $forum['title'] ?></a>
													<!-- <label for="radio_demo_2" class="inline-label"><?php echo $forum['title'] ?></label> -->
												</p>
											<?php
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Posted feeds -->
				<div class="uk-grid" data-uk-grid-margin="">
					<?php

						$filterForum  = $_GET['forum']??"";
						if($filterForum){
							$posts = forumFeeds($filterForum, $thisid);
						}else{
							$posts = listFeeds($thisid);
						}

						
						foreach ($posts as $key => $post) {
							$post_title = $post['feedTitle'];
							$post_content = $post['feedContent'];
							$post_pdate = $post['createdDate'];
							$post_likes = $post['nlikes'];
							$post_comments = $post['ncomments'];
							$posted_by = $post['feedByName'];

							$post_attachments = $post['feedAttachments'];

							$feedId = $postId = $post['id'];

							$comments = feedComments($postId);
							?>
								<div class="uk-margin-bottom uk-width-medium-2-3 uk-width-1-1" data-target-forum="<?php echo $post['feedForumId'] ?>">
									<div class="md-card">
										<div class="md-card-content">
											<div class="blog_list_teaser" style="margin-bottom: 12px;">
												<h2 class="blog_list_teaser_title uk-text-truncate"><?php echo $posted_by; ?></h2>
													
												<p>
													<?php echo $post_content; ?>
												</p>
											</div>
											<div class="media-container">
												<?php
													for($n=0; $n<count($post_attachments); $n++){
														$attachment = $post_attachments[$n];
														$ext = strtolower(pathinfo($attachment, PATHINFO_EXTENSION)); //extensin
														if($ext == 'png' || $ext == 'jpg'){
															?>
																<img src="<?php echo $attachment; ?>" alt="" style="max-height: 300px" class="blog_list_teaser_image">
															<?php
														}else if($ext == 'mp3' || $ext == 'aac'){
															//audio
															?>
																<audio src="<?php echo $attachment ?>" controls></audio>
															<?php 
														}
														else if($ext == 'mp4'){
															//video
															?>
																<video src="<?php echo $attachment ?>" width='100%' style="max-height:300px" controls></video>
															<?php 
														}
													}
												?>
												<!-- <img src="assets/img/gallery/Image01.jpg" alt="" class="blog_list_teaser_image"> -->
											</div>
											<div class="feed_timestamp0">
												<span class="uk-text-muted uk-text-small"><?php echo date('d M Y', strtotime($post_pdate)); ?></span>
											</div>
											<div class="blog_list_footer">
												<div class="blog_list_footer_info" style="cursor: pointer;">
													<?php
													//Indicating already liked
													if($post['liked'] != 0 ){
														echo('<span class="uk-margin-right like_feed_button md-color-green-500" data-post="'.$post['id'].'"><i class="material-icons md-color-green-500"></i> <small>'.$post_likes.'</small></span>');
													}else{
														echo('<span class="uk-margin-right like_feed_button" data-post="'.$post['id'].'"><i class="material-icons"></i> <small>'.$post_likes.'</small></span>');
													}
													?>
													
													<span class="comment_feed_button" data-feed="<?php echo $post['id']; ?>"><i class="material-icons"></i> <small><?php echo $post_comments; ?></small></span>
												</div>

												<div class="uk-float-right">
													<span class="uk-margin-left">
														<a href="feeds.php?id=<?php echo $post['id']; ?>">
															<!-- <i class="md-icon material-icons md-color-green-500" style="cursor: pointer;" title="Remove podcast" data-post="<?php echo $post['id']; ?>">mode_edit</i> -->
														</a>
														<i class="md-icon material-icons md-color-red-500 post_remove" style="cursor: pointer;" title="Remove podcast" data-post="<?php echo $post['id']; ?>">delete</i>
													</span>
												</div>
												<div class="clearfix">
												</div>
												<div class="commentsContainer uk-hidden" data-feed='<?php echo $feedId ?>'>
													<div style="height: 32px; width: 100%; padding: 0px; margin: 0 -100px;"></div>
													<form class="commentForm" data-feed="<?php echo $feedId;  ?>">
														<div class="md-input-wrapper">
															<label>Description</label>
															<textarea class="md-input" style="border: 1px solid #eee; border-radius: 2px;"></textarea>
															<span class="md-input-bar "></span>
														</div>
														<div class="md-input-wrapper">
															<button class="uk-button uk-button-default uk-float-right" type="submit">Comment</button>
															<span class="md-input-bar "></span>
														</div>
													</form>
													<div style="height: 12px; width: 100%; padding: 0px; margin: 0 -100px;"></div>
													<div class="clearfix uk-margin-top">                                                    
														<ul class="uk-list uk-list-line">
															<?php
																foreach ($comments as $key => $comment) {
																	?>
																	<li>
																		<div class="uk-grid">
																			<div class="comment-head">
																				<div class="thumbnail">
																					<img class="user avatar inline" style="height: 32px; width: 32px; border-radius: 50%" src="<?php echo $comment['commentByImg'] ?>">
																					<p class="inline" style="vertical-align: middle; font-weight: bold; font-family: verdana; color: #0a3482">
																						<i><?php echo $comment['commentByName'] ?></i>
																					</p>
																				</div>
																			</div>
																			<div class="uk-width-1-1 uk-margin-top">
																				<?php echo $comment['comment'] ?>
																			</div>
																		</div>
																	</li>
																	<?php
																}
															?>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php
						}
					?>
				</div>
			</div>
			<?php
		}
	?>
				
	<div class="uk-modal" id="branch_create" aria-hidden="true" style="display: none; overflow-y: auto;">
		<div class="uk-modal-dialog" style="max-width:800px;">
			<div class="act-dialog" data-role="init">
				<div class="uk-modal-header uk-tile uk-tile-default">
					<h3 class="d_inline">Add podcast</h3>
				</div>
				<form method="POST" enctype="multipart/form-data" id="file_add_form">
					<div class="md-card">
						<div class="md-card-content">
							<div class="md-input-wrapper">
								<label>Title</label>
								<input type="text" id="podcast-name" class="md-input" required="required">
								<span class="md-input-bar "></span>
							</div>
							<div class="md-input-wrapper">
								<label>Description</label>
								<textarea id="podcast-intro" class="md-input"></textarea>
								<!-- <input type="text"  c> -->
								<span class="md-input-bar "></span>
							</div>
							<div class="md-input-wrapper">
								<label>File</label>
								<input type="file" id="podcast-file" class="dropify" data-allowed-file-extensions="mp3 aac mp4" data-max-file-size="<?php echo ini_get('upload_max_filesize'); ?>"/>
								<progress id="podcast-upload-progress" class="uk-progress display-none" value="0" max="100" style="width: 100%"></progress>
								<span class="md-input-bar "></span>
							</div>
						</div>                        
					</div>
					<div class="uk-modal-footer uk-text-right">
						<button class="md-btn md-btn-danger pull-left uk-modal-close">Cancel</button>
						<button class="md-btn md-btn-success pull-right" type="submit" id="upload-podcast-btn">UPLOAD</button>
					</div>
				</form>
			</div>
			<div class="act-dialog display-none" style="max-width:400px;" data-role="done">
				<div class="uk-modal-header uk-tile uk-tile-default">
					<h3 class="d_inline">Podcast</h3>
					<p class="uk-text-success">Congratulations! Podcast added successfully!</p>
				</div>
			</div>
		</div>
	</div>

	<!-- google web fonts -->
	<script>
		const host = location.protocol+"//"+location.host+"/";
		WebFontConfig = {
			google: {
				families: [
					'Source+Code+Pro:400,700:latin',
					'Roboto:400,300,500,700,400italic:latin'
				]
			}
		};
		(function() {
			var wf = document.createElement('script');
			wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
			'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
			wf.type = 'text/javascript';
			wf.async = 'true';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(wf, s);
		})();
	</script>

	<!-- common functions -->
	<script src="assets/js/common.min.js"></script>
	<!-- uikit functions -->
	<script src="assets/js/uikit_custom.min.js"></script>
	<!-- altair common functions/helpers -->
	<script src="assets/js/altair_admin_common.min.js"></script>

	<!-- page specific plugins -->
	<!-- d3 -->
	<script src="bower_components/d3/d3.min.js"></script>
	<!-- metrics graphics (charts) -->
	<script src="bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
	<!-- chartist (charts) -->
	<script src="bower_components/chartist/dist/chartist.min.js"></script>
	 <!-- peity (small charts) -->
	<script src="bower_components/peity/jquery.peity.min.js"></script>
	<!-- easy-pie-chart (circular statistics) -->
	<script src="bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
	<!-- countUp -->
	<script src="bower_components/countUp.js/dist/countUp.min.js"></script>
	<!-- handlebars.js -->
	<script src="bower_components/handlebars/handlebars.min.js"></script>
	<script src="assets/js/custom/handlebars_helpers.min.js"></script>
	<!-- CLNDR -->
	<script src="bower_components/clndr/clndr.min.js"></script>

	<!--  dashbord functions -->
	<script src="assets/js/pages/dashboard.min.js"></script>

	<!-- Dropify -->
	<script src="bower_components/dropify/dist/js/dropify.min.js"></script>

	<script type="text/javascript" src="js/Chart.min.js"></script>

	<!-- file input -->
	<script src="assets/js/custom/uikit_fileinput.min.js"></script>

	<!--  user edit functions -->
	<script src="assets/js/pages/page_user_edit.min.js"></script>

	<!-- Firebase -->
	<!-- <script src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script> -->

	<script src="js/uploadFile.js"></script>

	<script type="text/javascript" src="js/forums.js"></script>

	<script type="text/javascript">
		$(".selectize").selectize();
		
	</script>

	<!-- Firebase -->
	<script>
	  // Initialize Firebase
	  // TODO: Replace with your project's customized code snippet
	  // var config = {
	  //   apiKey: "AIzaSyB1qCWTLud__LGEFQQCZU98iMiy-Dp8Tbk",
	  //   authDomain: "learnbase-baa6d.firebaseapp.com",
	  //   databaseURL: "https://learnbase-baa6d.firebaseio.com",
	  //   projectId: "learnbase-baa6d",
	  //   storageBucket: "learnbase-baa6d.appspot.com",
	  //   messagingSenderId: "483987540771"
	  // };
	  // firebase.initializeApp(config);

	  // const preObject = document.getElementById("firebase")
	  // const dbRefObj = firebase.database().ref().child('firebase')

	  // dbRefObj.on('value', function(){
		
	  // })
	</script>


	<script>
		$(function() {
			if(isHighDensity()) {
				$.getScript( "bower_components/dense/src/dense.js", function() {
					// enable hires images
					altair_helpers.retina_images();
				});
			}
			if(Modernizr.touch) {
				// fastClick (touch devices)
				FastClick.attach(document.body);
			}
		});
		$window.load(function() {
			// ie fixes
			altair_helpers.ie_fix();
		});
	</script>

<script type="text/javascript">
	const current_user = <?php echo $thisid; ?>;
	$('.dropify').dropify({
		messages: {
			'default': 'Drag and drop a podcast here or click',
		}
	});


	filter_elems = $(".posts_filter input.filter-elem");
	for(n=0; n<filter_elems.length; n++){
		filter = filter_elems[n];
		$(filter).on('click', function(){
			alert();
		})
	}

	$(".posts_filter input.filter-elem").on('change', function(){
		alert();
	})


	var feeds_attachment = [];
	$("#feeds_attachment_input").on('change', function(data){
		uploaded = document.querySelector("#feeds_attachment_input").files[0];
		// log(uploaded);

		filename = uploaded.name
		file_size = uploaded.size

		//here we want to check the extension
		ext = filename.substr(-3).toLowerCase();

		allowed_extensions = ['png', 'jpg', 'mp3', 'aac', 'mp4'];
		if(allowed_extensions.indexOf(ext)>-1){
			//file is allowed
			//create an elemt thumbnail for file
			thumbnail = $("#feed-thumbnail-tpl").clone()
			thumbnail.css('display', 'inherit');

			//Adding the title
			thumbnail.find(".thumb-title").html(filename);

			$("#feed-thumbnail-tpl-cont").append(thumbnail);

			//Start to upload
			var formdata = new FormData();

			formdata.append('action', 'upload_feed_attachment');
			formdata.append('file', uploaded);

			var ajax = new XMLHttpRequest();

			ajax.upload.addEventListener("progress", function(evt){
				uploaded = evt.loaded;
				total = evt.total;

				percentage = (uploaded/total)*100;
				
				thumbnail.find(".feed_attachment_progress").attr('value', percentage);

				console.log(percentage)
			}, false);


			ajax.addEventListener("load", function(){
				response = this.responseText;
				try{
					ret = JSON.parse(response);
					if(ret.status){
						//create successfully(Giving notification and closing the modal);
						feeds_attachment.push(host+ret.msg)

					}else{
						msg = ret.msg;
					}
				}catch(e){
					console.log(e);
				}

			}, false);

			ajax.open("POST", "api/index.php");
			ajax.send(formdata);
		}else{
			alert("File of "+ext+ " type not allowed")
		}
	})

	//creating feed
	$("#feed_create_form").on('submit', function(e){
		e.preventDefault();

		//ask for confirmation
		conf = confirm("Do you want to post?");


		if(!conf)
			return false;


		//disabling submit button
		$("#submit_feed").addClass('display-none');
		$("#submit_feed").attr('disabled', 'disabled');
		$(".progress-cont").removeClass('display-none');

		//we can save now
		var formdata = new FormData();
		var ajax = new XMLHttpRequest();

		var post_content = $("#post_content").val();
		var postTo = $("#postTo").val();
		if(post_content && postTo){
			formdata.append('action', 'postFeed');
			formdata.append('feedContent', post_content);
			formdata.append('memberId', <?php echo $thisid; ?>);
			formdata.append('attachments', JSON.stringify(feeds_attachment));
			formdata.append('userType', 'admin');
			formdata.append('platform', 'web');
			formdata.append('targetForum', postTo);

			ajax.open("POST", host+"api/invest.php");        
			ajax.send(formdata);

			ajax.addEventListener("load", function(){
				ret = this.responseText 
				setTimeout(function(){
					console.log(ret)
					location.reload()
				}, 1500)                    
			})
		}else{
			alert("Specify details")
		}            
	})

	$(".like_feed_button").on('click', function(){
		//liking a feed
		feed = $(this).data('post');
		like_btn = $(this)
		$.post('../../api/invest.php', {action:'likeFeed', userId:current_user, feedId:feed}, function(data){
			log(data)
			if(data == 'Done'){
				//Indicate a like
				like_btn.addClass('md-color-green-500');
				like_btn.find("i").addClass('md-color-green-500');

				//increasing the number of likes
				like_btn.find('small').html(parseInt(like_btn.find('small').html())+1);
			}
		})
	});

	//feed commenting btn and activation visibility
	$(".comment_feed_button").on('click', function(){
		feed = $(this).data('feed');

		//make comment element visible
		$(".commentsContainer[data-feed='"+feed+"']").removeClass('uk-hidden')

	});

	//submitting feed comment
	$(".commentForm").on('submit', function(e){
		e.preventDefault();
		feed = $(this).data('feed');
		comment = $(this).find('textarea').val()

		if(comment.length>1){
			//submitting a comment
			$.post('../../api/invest.php', {action:'commentFeed', userId:current_user, feedId:feed, feedComment:comment}, function(data){
				if(data.toLowerCase() == 'done'){
					location.reload();
				}else{
					alert("Problem with commenting, try again later")
				}
			})
		}else{
			alert("Please type comment")
		}

	});

	//removing feed
	$(".post_remove").on('click', function(){
		feedId = $(this).data("post");
		parent_elem = $(this).parents('.uk-margin-bottom');

		del_prompt = window.confirm("Delete this feed?");
		if(del_prompt){
			$.post('api/index.php', {action:'delete_feed', feed:feedId, user:<?php echo $thisid; ?>}, function(data){
				if(typeof(data) == 'object')
					ret = data
				else
					ret = JSON.parse(data)
				if(ret.status){
					// parent container cleanin
					location.reload();
					parent_elem.hide(100);
					$(this).parents('.uk-margin-bottom').remove();
				}
			});
		}            
	});
	function log(data){
		console.log(data)
	}
	</script>
</body>
</html>