<!-- start of error messages from flashdata -->
<?php if(!empty($errors)){ ?>
<div id="error-messages" class="alert alert-danger text-left alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <?php
    $html = '';
    if(!empty($errors)) {
        if(is_array($errors)) {
            foreach ($errors as $key => $value) {
                $html .= '<p class="error-server">'.$errors[$key].'</p>';
            }
        }
        else {
            $html .= '<p class="error-server">'.$errors.'</p>';
        }
        echo $html;
    }
    ?>
</div>
<?php } ?>
<!-- end of error messages from flashdata -->

<!-- start of success messages from flashdata -->
<?php if(!empty($success)){ ?>
<div id="success-messages" class="alert alert-success text-left alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <?php
    $html = '';
    if(!empty($success)) {
        if(is_array($success)) {
            foreach ($success as $key => $value) {
                $html .= '<p class="success-server">'.$success[$key].'</p>';
            }
        }
        else {
            $html .= '<p class="success-server">'.$success.'</p>';
        }
        echo $html;
    }
    ?>
</div>
<?php } ?>

<!-- ajax error messages -->
<div class="ajax-success alert alert-success text-left alert-dismissible" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="ajax-success-body"></div>
</div>
<div class="ajax-error alert alert-error text-left alert-dismissible" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="ajax-error-body"></div>
</div>