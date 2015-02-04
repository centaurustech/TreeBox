<!--
	If changing anything on this file: make sure to make it sticky in mainAddProjectJS.js for the crowdfunding redirect uri to save
-->


<!--<h1 id="add_project_heading">ADD YOUR PROJECT!</h1>-->
<form action="<?php echo $project_form_action; ?>" method="POST" id="<?php echo $project_form_id; ?>">
	<fieldset class="<?php echo $project_form_element_class; ?>">
		<legend class="<?php echo $project_form_element_class; ?>"><?php echo $project_form_legend; ?></legend>
		<div class="error"><span class="form_required"></span></div>

		<label for="project_name" class="<?php echo $project_form_element_class; ?>">Project Name</label>
		<input type="text" name="project_name" id="project_name" class="<?php echo $project_form_element_class; ?>" placeholder="ie. Picking up trash in the park" 
			value="<?php if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_name']; } //Sticky form
				elseif(isset($edit_project_name)){ echo $edit_project_name; } //edit project page ?>"/>

		<textarea name="project_description" id="project_description" class="<?php echo $project_form_element_class; ?>" rows="10"
		placeholder='Describe your project ie. what kind of volunteers you are looking for, group contact info'><?php 
			if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_description']; } //Sticky form
			elseif(isset($edit_project_descript)){ echo $edit_project_descript; } //edit project page ?></textarea>

		<br/><label for="project_loc" class="<?php echo $project_form_element_class; ?>">Project Location</label>
		<input type="text" name="project_loc" id="project_loc" class="<?php echo $project_form_element_class; ?>" placeholder="Address of your project (any location)"
			value="<?php if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_loc']; } //Sticky form 
			elseif(isset($edit_project_loc)){ echo $edit_project_loc; } //edit project page ?>"/>
		<input type="hidden" name="hidden_loc_lat" id="hidden_loc_lat" />
		<input type="hidden" name="hidden_loc_lng" id="hidden_loc_lng" />
		<input type="hidden" name="hidden_loc_address" id="hidden_loc_address" />
		<input type="hidden" name="hidden_loc_city" id="hidden_loc_city" />
		<input type="hidden" name="hidden_loc_state" id="hidden_loc_state" />
		<input type="hidden" name="hidden_loc_zip" id="hidden_loc_zip" />
		<input type="hidden" name="hidden_loc_country" id="hidden_loc_country" />
		<input type="hidden" name="hidden_loc_formatted_address" id="hidden_loc_formatted_address" />

		<?php if(!isset($edit_project_hasExpired) || $edit_project_hasExpired == false){ //will not affect add_project_form, but if project expired in edit_project form = will not display?>
			<br/><label for="project_date" class="<?php echo $project_form_element_class; ?>">Date of Project</label>
			<input type="text" name="project_date" id="project_date" class="<?php echo $project_form_element_class; ?>" placeholder="mm/dd/yyyy (ie. 05/29/2014)"
			value="<?php if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_date']; } //Sticky form 
			elseif(isset($edit_project_date)){ echo $edit_project_date; } //edit project page?>" />

			<br/><label for="project_time" class="<?php echo $project_form_element_class; ?>">Time of Project</label>
			<section id="project_time" class="<?php echo $project_form_element_class; ?>">
				<select name="select_hour" id="select_hour" class="<?php echo $project_form_element_class; ?>">
					<?php for($i = 1; $i <= 12; $i++){
						if($i == 8)
							print "<option value='{$i}' selected='selected'>{$i}</option>";
						else
							print "<option value='{$i}'>{$i}</option>";
					} ?>
				</select>
				<span>:</span>
				<select name="select_minute" id="select_minute" class="<?php echo $project_form_element_class; ?>">
					<option value="0">00</option>
					<option value="15">15</option>
					<option value="30">30</option>
					<option value="45">45</option>
					<?php for($i = 1; $i < 60; $i++){
						$min = "" . $i;
						//formatting of minutes
						if($i <= 9)
							$min = "0" . $i;

						print "<option value='{$min}'>{$min}</option>";
					} ?>
				</select>
				<select name="select_period" id="select_period" class="<?php echo $project_form_element_class; ?>">
					<option value="AM" <?php if(isset($projectStickyPeriod) && $projectStickyPeriod == "AM") echo "selected" ?>>AM</option>
					<option value="PM" <?php if(isset($projectStickyPeriod) && $projectStickyPeriod == "PM") echo "selected" ?>>PM</option>
				</select>
			</section>
		<?php } else{
			echo "<br/>";
		}//end if edit_project_hasExpired == false ?>

		<?php /*if($project_form_id == "add_project_form"){ ?>
			<div id='setup_crowdfunding_div'>
				<input type="checkbox" name="crowdfunding_checkbox" id="crowdfunding_checkbox" class="form-checkbox" value="includeCF"/>
				<label for="crowdfunding_checkbox" id="crowdfunding_label" class="checkbox-label">Add Crowdfunding</label>
			</div> 
		<?php } //end if project_form_id == add_project_form */?>

		<a id="submit_project" class="<?php echo $project_form_element_class; ?> buttonOne"><?php echo $project_form_submit_button_value; ?></a>
		<?php if($project_form_id == "edit_project_form"){ ?>
			<button type="button" name="delete_project" id="delete_project" class="edit_project">
				<img src="images/delete_icon.png" id="delete_icon"/><span id="delete_project">Delete this project</span>
			</button>
			<div id="delete_dialog" title="Delete <?php echo $edit_project_name; ?>?">
				<p>Are you sure you want to delete "<?php echo $edit_project_name; ?>"?</p>
			</div>
		<?php } //end if project_form_id == edit_project?>
	</fieldset>
</form>