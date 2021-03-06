<?php
// Variable to configure global behaviour
$header_title = 'GenY Mobile - Ajout idée';
$required_group_rights = 5;

include_once 'header.php';
include_once 'menu.php';

$geny_client = new GenyClient();

?>

<div class="page_title">
	<img src="images/<?php echo $web_config->theme ?>/project_generic.png"/><p>Idée</p>
</div>

<div id="mainarea">
	<p class="mainarea_title">
		<span class="idea_add">
			Ajouter une idée
		</span>
	</p>
	<p class="mainarea_content">
		<p class="mainarea_content_intro">
		Ce formulaire permet d'ajouter une idée dans la boîte à idées. Tous les champs doivent être remplis.
		</p>
		<script>
			jQuery(document).ready(function(){
				$("#formID").validationEngine('init');
				// binds form submission and fields to the validation engine
				$("#formID").validationEngine('attach');
			});
			$(document).ready(function(){
				$(".profileslistselect").listselect({listTitle: "Profils disponibles",selectedTitle: "Profils sélectionnés"});
			});
		</script>
		<form id="formID" action="idea_view.php" method="post">
			<input type="hidden" name="create_idea" value="true" />
			<p>
				<label for="idea_title">Titre</label>
				<input name="idea_title" id="idea_title" type="text" class="validate[required,length[2,100]] text-input" />
			</p>
			<p>
				<label for="idea_description">Description</label>
				<textarea name="idea_description" id="idea_description" class="validate[required] text-input"></textarea>
			</p>
			<p>
				<input type="submit" value="Créer" /> ou <a href="#form">annuler</a>
			</p>
		</form>
	</p>
</div>
<div id="bottomdock">
	<ul>
		<?php 
			include 'backend/widgets/idea_list.dock.widget.php';
		?>
	</ul>
</div>

<?php
include_once 'footer.php';
?>
