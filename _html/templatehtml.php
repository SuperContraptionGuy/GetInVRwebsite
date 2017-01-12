<!--

Example file name for the page files in the _html folder.

File naming:

	these files should be named like this:

		index_1.php

	where `index` is replaced by the name of the page and is the same name as it's retaining folder within the `_html` directory
	and the `1` is the page varient number for split testing purposes. All the page variants should be kept in the same folder together by name:

		+- _html
		|
		|	+- index
		|	|
		|	|	+- index_1.php
		|	|	+- index_2.php
		|	|
		|	+- reservations
		|	|
		|	|	+- reservations_1.php
		|	|
		|

-->


<!--	Example form 	-->
<form action="/formSubmit.php" method="POST">

	<input type="email" name="emailAddress" class="form-control keepStats" id="email" required>	<!--	Example input field.  `name` property needs to be identical to the mySQL column name.  `id` property is the name tracked for `keepStats.js`	-->

	<input type="hidden" name="formType" value="reservation" />	<!--	This hidden value is used to determine the mySQL table name by forSubmit.php -->
	<button type="submit" class="btn btn-default keepStats" id="submitButtonStatName">Submit</button>	<!--	This button is tracked by `keepStats.js` by the `id` property value of `submitButtonStatName`	-->

</form>


<!--	Example Tracked HTML elements	-->
<button id='buttonStatDisplayName' class='keepStats'>A statistically tracked button or whatever element with this class that you want to track clicks with</button>
<p id='paragraphStat' class='keepStats'>This is also tracked for clicks.</p>
<p>But not this.</p>


<!--	Required files for statistics tracking:		-->
<script>currentPageName = 'index';</script>	<!--	Insert page name here, used as the page name is statistical tracking.  A separate value is included that indicates the page variant.  Use the same name for multiple files of the same page name 	-->
<script src="_js/keepStats.js"></script>