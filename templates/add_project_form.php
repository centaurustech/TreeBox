<!--<h1 id="add_project_heading">ADD YOUR PROJECT!</h1>-->
<form action="add_project.php" method="POST" id="add_project_form">
	<fieldset class="add_project">
		<legend class="add_project">ADD YOUR PROJECT</legend>
		<div class="error"><span class="form_required"></span></div>

		<label for="project_name" class="add_project">Project Name</label>
		<input type="text" name="project_name" id="project_name" class="add_project" placeholder="ie. Picking up trash in the park" 
			value="<?php if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_name']; } //Sticky form ?>"/>

		<textarea name="project_description" id="project_description" class="add_project" rows="10"
		placeholder='Describe your project (Optional) ie. what kind of volunteers are you looking for, group contact info'><?php 
			if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_description']; } //Sticky form ?></textarea>

		<br/><label for="project_loc" class="add_project">Project Location</label>
		<input type="text" name="project_loc" id="project_loc" class="add_project" placeholder="Address of your project (any location)"
			value="<?php if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_loc']; } //Sticky form ?>"/>
		<input type="hidden" name="hidden_loc_lat" id="hidden_loc_lat" />
		<input type="hidden" name="hidden_loc_lng" id="hidden_loc_lng" />
		<input type="hidden" name="hidden_loc_address" id="hidden_loc_address" />
		<input type="hidden" name="hidden_loc_city" id="hidden_loc_city" />
		<input type="hidden" name="hidden_loc_state" id="hidden_loc_state" />
		<input type="hidden" name="hidden_loc_zip" id="hidden_loc_zip" />
		<input type="hidden" name="hidden_loc_country" id="hidden_loc_country" />
		<input type="hidden" name="hidden_loc_formatted_address" id="hidden_loc_formatted_address" />

		<br/><label for="project_date" class="add_project">Date of Project</label>
		<input type="text" name="project_date" id="project_date" class="add_project" placeholder="mm/dd/yyyy (ie. 05/29/2014)"
		value="<?php if (!empty($_POST) && !$projectSubmitted) { print $_POST['project_date']; } //Sticky form ?>"/>

		<br/><label for="project_time" class="add_project">Time of Project</label>
		<section id="project_time" class="add_project">
			<select name="select_hour" id="select_hour" class="add_project">
				<?php for($i = 1; $i <= 12; $i++){
					if($i == 8)
						print "<option value'{$i}' selected='selected'>{$i}</option>";
					else
						print "<option value'{$i}'>{$i}</option>";
				} ?>
			</select>
			<span>:</span>
			<select name="select_minute" id="select_minute" class="add_project">
				<option value="0">00</option>
				<option value="15">15</option>
				<option value="30">30</option>
				<option value="45">45</option>
				<?php for($i = 1; $i <= 60; $i++){
					$min = "" . $i;
					//formatting of minutes
					if($i <= 9)
						$min = "0" . $i;

					print "<option value'{$i}'>{$min}</option>";
				} ?>
			</select>
			<select name="select_period" id="select_period" class="add_project">
				<option value="AM">AM</option>
				<option value="PM">PM</option>
			</select>
		</section>

		<input type="submit" name="submit_project" id="submit_project" class="add_project" value="Add this project!">
	</fieldset>
</form>