<?php
// Variable to configure global behaviour
$header_title = 'GenY Mobile - Admin';
$required_group_rights = 5;

include_once 'header.php';
include_once 'menu.php';


?>

<div class="page_title">
	<img src="images/default/admin.png"/><p>Admin</p>
</div>

<div id="maindock">
	<ul>
		<?php 
			include 'backend/widgets/cra_validation.dock.widget.php'; 
			include 'backend/widgets/conges_validation.dock.widget.php'; 
			include 'backend/widgets/profile_add.dock.widget.php'; 
			include 'backend/widgets/profile_list.dock.widget.php'; 
			include 'backend/widgets/client_add.dock.widget.php'; 
			include 'backend/widgets/client_list.dock.widget.php'; 
			include 'backend/widgets/task_add.dock.widget.php'; 
			include 'backend/widgets/task_list.dock.widget.php'; 
			include 'backend/widgets/project_add.dock.widget.php'; 
			include 'backend/widgets/project_list.dock.widget.php'; 
			include 'backend/widgets/idea_add.dock.widget.php'; 
			include 'backend/widgets/idea_list.dock.widget.php'; 
		?>
	</ul>
</div>


<?php
include_once 'footer.php';
?>
