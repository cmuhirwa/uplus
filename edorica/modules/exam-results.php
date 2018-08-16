<?php
global $conn, $exam_results;
?>
<div class="exams-form-controller">
	<div class="exform">
    	<p class="form-msg">Enter registration code.</p>
        <p class="form-msg">Shyiramo umubare uranga umunyeshuri.</p>
        <form action="<?php echo $exam_results; ?>" method="POST">
        	<input class="exelem input" name="regcode" type="text" max="20" placeholder="Enter your code" required="required">
            <input type="hidden" name="subt" type="hidden">
            <button class="exelem" type="submit">Get Result</button>
        </form>
    </div>
</div>