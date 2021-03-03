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
                                                'option'=>array("JS", "CSS", "SV", "Yash")));
                renderRadioQuestions($qnsans, $start);

                $qnsans = array(3=>array('question'=>"QNS 1 desc?",
                                        'option'=>array("A","B","C")),
                                4=>array('question'=>"QNS 2 desc?",
                                                'option'=>array("SP")));

                renderCheckBoxQuestions($qnsans, $start);

                $qns = array(5=>array("question"=>"Kya aapke toothpaste me namak he?"));
                renderTextQuestions($qns,$start);
                
                $qns = array(6=>array("question"=>"Who is god?"));
                renderTextQuestions($qns,$start);

        ?>
</div>
</div> <!--END OF main-panel class-->
</div><!--END OF wrapper class-->
</body>
</html>