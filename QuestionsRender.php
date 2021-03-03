<?php

    function renderMcq($questions, $options, &$start){

        foreach ($questions as $question) {
         ?>
        <div class="card p-2 col-md-6 col-xs-12 col-sm-6">

            <div class="card-header card-header-info p-2">
                <div class="question_badge pl-2 pr-2 pt-2 h-100 fs-5">Q.<?php echo $start; $start++;?></div>
                <div class="card-title ml-5 text-lowercase fs-5 fw-bold"><?php echo $question['question']; ?></div>
            </div>

            <div class="row m-3">
                <?php foreach ($options[$question['id']] as $value) { ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="radio">
                            <label class="fs-5 text-dark">
                                <input type="radio" class="fs-2" name="<?php echo $question['id'];?>">
                                <?php echo $value;?>
                            </label>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    <?php } } ?>


