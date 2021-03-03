<?php
    require_once 'sidebar.php';
    require_once 'QuestionsRender.php';
    renderSideBar("hostQuiz");
?>
<div class="content ml-4 mr-4">
        <?php
        $start = 1;
        $question = array(array('id'=>1, 'question'=>'What is bootstrap?'), array('id'=>2, 'question'=>'What is CSS?'));
        $options = array(1=>array("UI kit", "UI framework", "UI fake", "For Lazy devs."), 2=> array("UI kit", "UI framework", "UI fake", "For Lazy devs."));
        renderMcq($question, $options, $start);
        ?>
</div>
</div> <!--END OF main-panel class-->
</div><!--END OF wrapper class-->
</body>
</html>