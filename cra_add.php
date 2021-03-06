<?php
// Variable to configure global behaviour
$header_title = 'GenY Mobile - Ajout CRA';
$required_group_rights = 5;

include_once 'header.php';
include_once 'menu.php';

$geny_ptr = new GenyProjectTaskRelation();
$geny_tools = new GenyTools();
date_default_timezone_set('Europe/Paris');

?>

<div class="page_title">
	<img src="images/<?php echo $web_config->theme ?>/cra.png"/><p>CRA</p>
</div>


<div id="mainarea">
	<p class="mainarea_title">
		<span class="cra_add">
			Ajouter un CRA
		</span>
	</p>
	<p class="mainarea_content">
		<p class="mainarea_content_intro">
		Ce formulaire permet d'ajouter un rapport d'activité.<br />
		<strong class="important_note">Important :</strong> La charge journalière est <u>une charge répartie par jour</u>. <br />
		C'est à dire que si vous remplissez un CRA pour une semaine (5 jours), vous positionnez les dates de début et de fin avec une charge moyenne de 8 heures.<br />
		Une charge supérieure signifie des heures supplémentaires et l'accord préalable du manager est nécessaire.
		</p>
		<script>
			jQuery(document).ready(function(){
				$("#formID").validationEngine('init');
				// binds form submission and fields to the validation engine
				$("#formID").validationEngine('attach');
			});
		</script>

		<form id="formID" action="cra_validation.php" method="post">
			<input type="hidden" name="create_cra" value="true" />
			<p>
				<label for="assignement_id">Projet</label>
				<select name="assignement_id" id="assignement_id" />
					<?php
						$geny_assignements = new GenyAssignement();
						foreach( $geny_assignements->getAssignementsListByProfileId( $profile->id ) as $assignement ){
							$p = new GenyProject( $assignement->project_id );
							echo "<option value=\"$assignement->id\" title=\"$p->description\">$p->name</input></option>";
						}
					?>
				</select>
			</p>
			<p>
				<label for="task_id">Tâche</label>
				<select name="task_id" id="task_id" />
				</select>
				<script>
					function getTasks(){
						var project_id = $("#assignement_id").val();
						$.get('backend/ajax_server_side/get_project_tasks_list.php?assignement_id='+project_id, function(data){
							$('.tasks_options').remove();
							$.each(data, function(key, val) {
								$("#task_id").append('<option class="tasks_options" value="' + val[0] + '" title="' + val[2] + '">' + val[1] + '</option>');
							});

						},'json');
					}
					$("#assignement_id").change(getTasks);
					getTasks();
					$(function() {
					$( "#assignement_start_date" ).datepicker();
					$( "#assignement_start_date" ).datepicker('setDate', new Date());
					$( "#assignement_start_date" ).datepicker( "option", "showAnim", "slideDown" );
					$( "#assignement_start_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
					$( "#assignement_end_date" ).datepicker();
					$( "#assignement_end_date" ).datepicker('setDate', new Date());
					$( "#assignement_end_date" ).datepicker( "option", "showAnim", "slideDown" );
					$( "#assignement_end_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
					$( "#assignement_start_date" ).change( function(){ $( "#assignement_end_date" ).val( $( "#assignement_start_date" ).val() ) } );
					
				});
				</script>
			</p>
			<p>
				<label for="assignement_start_date">Date de début</label>
				<input name="assignement_start_date" id="assignement_start_date" type="text" class="validate[required,custom[date]] text-input" />
			</p>
			<p>
				<label for="assignement_end_date">Date de fin</label>
				<input name="assignement_end_date" id="assignement_end_date" type="text" class="validate[required,custom[date]] text-input" />
			</p>
			<p>
				<label for="assignement_load">Charge journalière</label>
				<select name="assignement_load" id="assignement_load">
					<option value="1">1 Heure</option>
					<option value="2">2 Heures</option>
					<option value="3">3 Heures</option>
					<option value="4">4 Heures (1/2 journée)</option>
					<option value="5">5 Heures</option>
					<option value="6">6 Heures</option>
					<option value="7">7 Heures</option>
					<option value="8" selected="selected">8 Heures (1 journée)</option>
					<!--<option value="9">9 Heures (1 heure supp.)</option>
					<option value="10">10 Heures (2 heure supp.)</option>
					<option value="11">11 Heures (3 heure supp.)</option>
					<option value="12">12 Heures (4 heure supp.)</option>-->
					
				</select>
			</p>
			<p>
				<input type="submit" value="Créer" /> ou <a href="#formID">annuler</a>
			</p>
		</form>
	</p>
</div>

<?php
include_once 'footer.php';
?>
