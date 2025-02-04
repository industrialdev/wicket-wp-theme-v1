<?php
/**
 * Template Name: Kitchen Sink Forms Page
 */

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <main class="py-5">
        <div class="container">

				<h1><?php the_title(); ?></h1>

				<!-- Form Demos -->
				<form class="wicket-form">
					<div class="wicket-form-field-wrapper">
						<label for="text" class="required">Single Line Text</label>
						<input type="text" id="text" name="text" placeholder="Example" />
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="disabled-text" class="required">Disabled Single Line Text</label>
						<input type="text" id="disabled-text" name="disabled-text" placeholder="Example" disabled/>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="error-text" class="required">Error Single Line Text</label>
						<input type="text" class="error" id="error-text" name="error-text" placeholder="Example"/>
						<p class="wicket-form-help-text error">Error text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="success-text" class="required">Success Single Line Text</label>
						<input type="text" class="success" id="success-text" name="success-text" placeholder="Example"/>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="dropdown" class="required">Dropdown:</label>
						<select name="dropdown" id="dropdown">
							<option value="dropdown1">Dropdown 1</option>
							<option value="dropdown2">Dropdown 2</option>
							<option value="dropdown3">Dropdown 3</option>
						</select>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="errorDropdown" class="required">Error Dropdown:</label>
						<select class="error" name="errorDropdown" id="errorDropdown">
							<option value="errorDropdown1">Error Dropdown 1</option>
							<option value="errorDropdown2">Error Dropdown 2</option>
							<option value="errorDropdown3">Error Dropdown 3</option>
						</select>
						<p class="wicket-form-help-text error">Error text</p>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="successDropdown" class="required">Success Dropdown:</label>
						<select class="success" name="successDropdown" id="successDropdown">
							<option value="successDropdown1">Success Dropdown 1</option>
							<option value="successDropdown2">Success Dropdown 2</option>
							<option value="successDropdown3">Success Dropdown 3</option>
						</select>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="textarea">Textarea:</label>
						<textarea id="textarea" name="textarea" rows="4" cols="50" placeholder="Ai, this be a text area. know ye the way to write HTML, lest ye be servin' lord Emmet for all ye days!"></textarea>
						<p class="wicket-form-help-text">Help text</p>
					</div>		

					<div class="wicket-form-field-wrapper">
						<label for="textarea">Textarea:</label>
						<textarea id="textarea" name="textarea" rows="4" cols="50" disabled>Ai, this be a text area. know ye the way to write HTML, lest ye be servin' lord Emmet for all ye days!</textarea>
						<p class="wicket-form-help-text">Help text</p>
					</div>	
					
					<div class="wicket-form-field-wrapper">
						<label for="errorTextarea">Error Textarea:</label>
						<textarea id="errorTextarea" class="error" name="errorTextarea" rows="4" cols="50" placeholder="Ai, this be a text area. know ye the way to write HTML, lest ye be servin' lord Emmet for all ye days!"></textarea>
						<p class="wicket-form-help-text error">Error text</p>
					</div>	

					<div class="wicket-form-field-wrapper">
						<label for="email">Email:</label>
						<input type="email" id="email" name="email" placeholder="example@example.co.uk.russia" />
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="phone">Phone:</label>
						<input type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}">
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<div class="d-flex">
							<input type="radio" id="radio1" name="radio" value="radio1">
							<label for="radio1" class="forCheckOrRadio">Radio 1</label><br>
						</div>
						<div class="d-flex">
							<input type="radio" id="radio2" name="radio" value="radio2">
							<label for="radio2" class="forCheckOrRadio">Radio 2</label><br>
						</div>
						<div class="d-flex">
							<input type="radio" id="radio3" name="radio" value="radio3" disabled>
							<label for="radio3" class="forCheckOrRadio">Radio 3</label><br>
						</div>
						<div class="d-flex">
							<input type="radio" id="radio4" name="radio" value="radio4" checked disabled>
							<label for="radio4" class="forCheckOrRadio">Radio 4</label><br>
						</div>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<div class="d-flex">
							<input type="checkbox" id="checkbox1" name="checkbox1" value="Checkbox 1">
							<label for="checkbox1" class="forCheckOrRadio"> Checkbox 1</label><br>
						</div>
						<div class="d-flex">
							<input type="checkbox" id="checkbox2" name="checkbox2" value="Checkbox 2">
							<label for="checkbox2" class="forCheckOrRadio"> Checkbox 2</label><br>
						</div>
						<p class="wicket-form-help-text error forCheckOrRadio">Help text</p>
						<div class="d-flex">
							<input type="checkbox" id="checkbox3" name="checkbox3" value="Checkbox 3" disabled>
							<label for="checkbox3" class="forCheckOrRadio"> Checkbox 3</label><br>
						</div>
						<p class="wicket-form-help-text forCheckOrRadio">Help text</p>
						<div class="d-flex">
							<input type="checkbox" id="checkbox4" name="checkbox4" value="Checkbox 4" checked disabled>
							<label for="checkbox4" class="forCheckOrRadio"> Checkbox 4</label><br>
						</div>
						<p class="wicket-form-help-text forCheckOrRadio">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="multiple">Multiple:</label>
						<select name="multiple" id="multiple" multiple>
							<option value="multiple1">Multiple 1</option>
							<option value="multiple2">Multiple 2</option>
							<option value="multiple3">Multiple 3</option>
						</select>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<p>-- Search Box Here --</p>
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<div class="wicket-form-field-wrapper">
						<label for="date">Date:</label>
						<input type="date" id="date" name="date" value="2018-07-22" min="2018-01-01" max="2018-12-31" />
						<p class="wicket-form-help-text">Help text</p>
					</div>

					<input type="submit" value="Submit">
				</form>

				<!--<h2>Gravity Form Demo</h2>-->
				<?php the_content(); ?>


        </div>
    </main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
