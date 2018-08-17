<?php
   global $conn, $search, $reg_functions;
   $Location = WEB::getInstance("location");
   $Category = WEB::getInstance("category");
   $Course = WEB::getInstance("course");
   $provinces = $Location->provinces();
   $categories = $Category->list();
   $courses = $Course->list();
   include_once(_SETUP);
   include_once($reg_functions);
?>
<div class="adv-search-mod-cont inline">
      <div class="search-cont">
         <?php echo retainValue('q', 'GET'); ?>
         <form action="<?php echo $search; ?>">
            <div class="inline search-input-text">
               <input class="input" type="text" maxlength="100" placeholder="Type in school to search" name="q" <?php if(!empty(retainValue('q', 'GET')) ) echo "value=\".retainValue('q', 'GET').\""; ?> />
            </div>
            <div class="inline search-submit-btn">
               <button type="submit"><i class="fa fa-search"></i> Search</button>
            </div>      
         </form>
      </div>
      <p class=" search-form-sep align-center">OR</p>
      <form action="<?php echo $search; ?>" mode="POST">
         <div class="inline">
            <label for="cat">I want </label>
            <select class="more-search-elem" id="cat" name="cat">
               <option value="">Category</option>
               <?php 
                  foreach ($categories as $cat => $category) {
                     ?>
                        <option value="<?php echo strtolower($cat); ?>"><?php echo $category; ?></option>
                     <?php
                  }
               ?>
            </select>
         </div>
         <input type="hidden" name="type" value="adv">
         <div class="inline">
            <label for="loc">Located in:</label>
            <select class="more-search-elem" id="loc" name="province">
               <option value="">Province</option>
               <?php 
                  foreach ($provinces as $key => $province) {
                     ?>
                        <option value="<?php echo strtolower($province); ?>"><?php echo $province; ?> Province</option>
                     <?php
                  }
               ?>
            </select>
         </div>
         <div class="inline">
            <label for="course">Teaching:</label>
            <select class="more-search-elem" id="course" name="course">
               <option value="">Course</option>
               <?php 
                  foreach ($courses as $course_value => $course) {
                     ?>
                        <option value="<?php echo strtolower($course_value); ?>"><?php echo $course; ?></option>
                     <?php
                  }
               ?>
            </select>
         </div>
         <div class="btn-subt">
            <button type="submit">GET SCHOOLS</button>
         </div>
      </form>
</div>