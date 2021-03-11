<?php

    function renderRadioQuestion($question, &$start){

       
         ?>
        <div class="card p-2 col-md-12 col-xs-12 col-sm-12">

            <div class="card-header card-header-info p-2">
                <div class="question_badge pl-2 pr-2 pt-2 h-100 fs-5">Q.<?php echo $start; $start++;?></div>
                <div class="card-title ml-5 text-lowercase fs-5 fw-bold"><?php echo $question['question']; ?>
                    <span class="badge badge-success float-right mt-1"> <?php echo $question['mark'];?> mark</span>
                </div>
            </div>

            <div class="row m-3">
                <?php 
                

                foreach ($question['options'] as $optionvalue) { ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="radio">
                            <label class="fs-5 text-dark">
                                <input type="radio" class="fs-2" name="<?php echo $question['qnsid'];?>" value="<?php echo $optionvalue;?>">
                                <?php echo $optionvalue;?>
                            </label>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    <?php } 
    
    function renderCheckboxQuestion($question, &$start){

       
        ?>
       <div class="card p-2 col-md-12 col-xs-12 col-sm-12">

           <div class="card-header card-header-info p-2">
               <div class="question_badge pl-2 pr-2 pt-2 h-100 fs-5">Q.<?php echo $start; $start++;?></div>
               <div class="card-title ml-5 text-lowercase fs-5 fw-bold"><?php echo $question['question']; ?>
                   <span class="badge badge-success float-right mt-1"> <?php echo $question['mark'];?> mark</span>
               </div>
           </div>

           <div class="row m-3">
               <?php foreach ($question['options'] as $optionvalue) { ?>
                   <div class="col-md-12 col-sm-12 col-xs-12">
                       <div class="radio">
                           <label class="fs-5 text-dark">
                               <input type="checkbox" class="fs-2" name="<?php echo $question['qnsid'];?>[]" value="<?php echo $optionvalue;?>">
                               <?php echo $optionvalue;?>
                           </label>
                       </div>
                   </div>
               <?php } ?>
           </div>

       </div>
   <?php } 
    
    function renderTextQuestion($question, &$start){

       
         ?>
        <div class="card p-2 col-md-12 col-xs-12 col-sm-12">

            <div class="card-header card-header-info p-2">
                <div class="question_badge pl-2 pr-2 pt-2 h-100 fs-5">Q.<?php echo $start; $start++;?></div>
                <div class="card-title ml-5 text-lowercase fs-5 fw-bold"><?php echo $question['question']; ?>
                    <span class="badge badge-success float-right mt-1"> <?php echo $question['mark'];?> mark</span>
                </div>
            </div>

            <div class="row m-3 justify-content-center">
                    <div class="col-md-11 col-sm-11 col-xs-11">
                        <div class="">
                                <input type="text" placeholder="Type your answer" class="fs-5 text-dark form-control" name="<?php echo $question['qnsid'];?>">
                        </div>
                    </div>
            </div>

        </div>
    <?php  } 

?>


