<?php
$wpsd_options = get_option('wpsd_settings_option'); ?>


    <div class="row">
        <div class="col-sm-3">

            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <li role="presentation" class="active"><a href="#general-tab" aria-controls="home" role="tab" data-toggle="tab"><?php _e('All fields', 'wp-support-desk'); ?></a></li>
                <li role="presentation"><a href="#add_field" aria-controls="add_field" role="tab" data-toggle="tab"><?php _e('Add field', 'wp-support-desk'); ?></a></li>
            </ul>
        </div>

        <div class="col-sm-9">
            
            <div>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general-tab">

                        <div class="row">
                            <div class="col-sm-12">

                                <?php
                                $sql_custom_field = wpsd_get_ticket_custom_field();
                                if ($sql_custom_field){
                                    ?>
                                    <div id="assignedStatusMsg"></div>
                                    <table class="table table-striped wpsd-table">

                                        <tr>
                                            <th>#</th>
                                            <th><?php _e('Title', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Field Name', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Type', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Value', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Size', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Max Length', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Help Text', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Default Value', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Action', 'wp-support-desk'); ?></th>
                                        </tr>

                                        <?php
                                        $inc = 0;
                                        foreach ($sql_custom_field as $cfield){ 
                                            $inc++;
                                            ?>
                                            <tr>
                                                <td><?php echo $inc; ?></td>
                                                <td><?php echo $cfield->title; ?></td>
                                                <td><?php echo $cfield->field_name; ?></td>
                                                <td><?php echo $cfield->type; ?></td>
                                                <td><?php echo $cfield->value; ?></td>
                                                <td><?php echo $cfield->size; ?></td>
                                                <td><?php echo $cfield->max_length; ?></td>
                                                <td><?php echo $cfield->help_text; ?></td>
                                                <td><?php echo $cfield->default_value; ?></td>
                                                <td>
                                                    <a href="javascript:;" data-custom-field="<?php echo $cfield->id; ?>" class="btn btn-danger btn-xs wpsd-delete-custom-field"><i class="glyphicon glyphicon-trash"></i> </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <th>#</th>
                                            <th><?php _e('Title', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Field Name', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Type', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Value', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Size', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Max Length', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Help Text', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Default Value', 'wp-support-desk'); ?></th>
                                            <th><?php _e('Action', 'wp-support-desk'); ?></th>
                                        </tr>
                                    </table>
                                    <?php
                                }
                                ?>

                            </div>

                        </div> <!-- .row -->

                    </div> <!-- #general -->



                    <div role="tabpanel" class="tab-pane" id="add_field">

                        <div class="row">
                            <div class="col-sm-12">




                                <form class="form-horizontal" method="post" action="">
                                    <?php wp_nonce_field('wpsd_settings_page_action', 'wpsd_settings_page_nonce_field'); ?>


                                    <div class="form-group">
                                        <label for="user" class="control-label col-md-3"><?php _e('Field Type', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <select id="field_type" name="field_type" class="form-control wpsd-select2" size="1" style="width: 100%;">
                                                <option value="text">Text</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="select">Select</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="radio">Radio</option>
                                                <option value="date">Date</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="field_title" class="control-label col-md-3"><?php _e('Field Title', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="field_title" id="field_title" class="form-control" placeholder="<?php _e('Field Title', 'wp-support-desk') ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="field_name" class="control-label col-md-3"><?php _e('Field Name', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="field_name" id="field_name" class="form-control" placeholder="<?php _e('Field Name', 'wp-support-desk') ?>" />
                                            <p class="help-block">
                                                <?php _e('Field name without any space and special character', 'wp-support-desk'); ?>
                                            </p>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="help_text" class="control-label col-md-3"><?php _e('Help Text', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <textarea name="help_text" id="help_text" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="value" class="control-label col-md-3"><?php _e('Value', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <textarea name="value" id="value" class="form-control"></textarea>
                                            <p class="help-block">
                                                <?php _e('Option will be available from this field', 'wp-support-desk'); ?> <br />
                                                <?php _e('If you using select/radio button/checkbox, then enter each option value by separating comma', 'wp-support-desk'); ?> <br /><br />
                                                <?php _e('Example: Software,Hardwar,Computer', 'wp-support-desk'); ?> <br />
                                            </p>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="default_value" class="control-label col-md-3"><?php _e('Default Value', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="default_value" id="default_value" class="form-control" placeholder="<?php _e('Default Value', 'wp-support-desk') ?>" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="size" class="control-label col-md-3"><?php _e('Size', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="size" id="size" class="form-control" placeholder="<?php _e('Size', 'wp-support-desk') ?>" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="max_length" class="control-label col-md-3"><?php _e('Max Length', 'wp-support-desk') ?></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="max_length" id="max_length" class="form-control" placeholder="<?php _e('Max Length', 'wp-support-desk') ?>" />
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="user" class="control-label col-sm-3"><?php _e('Required', 'wp-support-desk') ?>
                                        </label>
                                        <div class="col-sm-5">
                                            <label class="radio-inline" for="field_required1">
                                                <input type="radio" name="field_required" id="field_required1" value="1" checked="checked"> <?php _e('Yes', 'wp-support-desk') ?>
                                            </label>
                                            <label class="radio-inline" for="field_required0">
                                                <input type="radio" name="field_required" id="field_required0" value="0" > <?php _e('No', 'wp-support-desk') ?>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <hr />
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <button type="submit" class="btn btn-primary" name="wpsd_save_field_btn" value="save_field"> <i class="glyphicon glyphicon-floppy-disk"></i> <?php _e('Save Field', 'wp-support-desk'); ?> </button>
                                        </div>
                                    </div>

                                </form>






                            </div>



                        </div> <!-- .row -->


                    </div> <!-- #tickets -->






                </div>

            </div>
        </div>

    </div>

