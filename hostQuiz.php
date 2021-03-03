<?php
    require_once 'sidebar.php';
    require_once 'QuestionsRender.php';
    renderSideBar("hostQuiz");
?>
<div class="content ml-4 mr-4">
        <?php
        $start = 1;
        $qnsans = array(1=>array('question'=>"What is bootstrap?",
                                'option'=>array("UI kit", "UI framework", "UI fake", "For Lazy devs.")),
                        2=>array('question'=>"What is CSS?",
                                 'option'=>array("JS", "CSS", "SV", "Yash"))
                        );
        renderMcq($qnsans, $start);
        ?>
</div>
</div> <!--END OF main-panel class-->
</div><!--END OF wrapper class-->
</body>
</html>