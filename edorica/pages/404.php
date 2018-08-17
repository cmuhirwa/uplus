<?php header("HTTP/1.1 404 Not Found") ?>
	<div class="not-found">
		URL could not be found.
	    <ul>
	        <li>Check URL</li>
	        <li>Report broken link</li>
	    </ul>
	</div>
	<?php
		$req = $_SERVER['REQUEST_URI'];
	?>
	<div>
		<?php echo $req ?> request could not be served
	</div>