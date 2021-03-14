
<?php
    require_once 'sidebar.php';
    renderSideBar("hostedQuizStats");
    require_once 'backend/connector.php';
?>
<div class="content ml-3 mr-3">
    <div class="row justify-content-center">
        <?php echo $_POST['qid'];?>
    </div>
</div>
