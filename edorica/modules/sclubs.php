<?php
    $page = WEB::getInstance('page');
    $School = WEB::getInstance('School');
    $current_name = $page->endPageName();

    $scname = ucwords($current_name);
    $scdata = $School->getSchool($scname, 'name', '*');
    $Club =  WEB::getInstance('club');
    if($scdata){
        $scid = $scdata['id'];
    }
else{
    die("I do not intend this error, but it happened. I will check");
}
$clubs = $Club->getclubs($scid);
if(count($clubs)){
?>
<p class="fmodtitle">School Clubs</p>
    <div class="perfcont">
        <?php
            for($temp=0; $temp<count($clubs); $temp++){
                $club = $clubs[$temp];
        ?>
        <a href="#"><?php echo $club['name']; ?></a>
        <?php } ?>
    </div>

<?php
    }else{
        //Here school has no clubs
        //Will know what to do after
    }
?>