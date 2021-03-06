<?php
// Variable to configure global behaviour
$header_title = 'GenY Mobile - Liste profils';
$required_group_rights = 2;

include_once 'header.php';
include_once 'menu.php';

$geny_rg = new GenyRightsGroup();
foreach( $geny_rg->getAllRightsGroups() as $group ){
	$groups[$group->id] = $group;
}

?>

<div class="page_title">
	<img src="images/<?php echo $web_config->theme ?>/profile_generic.png"/><p>Profil</p>
</div>


<div id="mainarea">
	<p class="mainarea_title">
		<span class="profile_list">
			Liste des profils
		</span>
	</p>
	<p class="mainarea_content">
		<p class="mainarea_content_intro">
		Voici la liste des profils dans la base des utilisateurs.
		</p>
		<p>
			<table class="object_list">
			<tr><th>Login</th><th>Prénom</th><th>Nom</th><th>Email</th><th>Actif</th><th>R-à-Z Password requis</th><th>Groupe</th><th>Éditer</th><th>Supprimer</th></tr>
			<?php
				function getImage($bool){
					if($bool == 1)
						return 'button_success_small.png';
					else
						return 'button_error_small.png';
				}
				foreach( $profile->getAllProfiles() as $tmp ){
					echo "<tr><td>$tmp->login</td><td>$tmp->firstname</td><td>$tmp->lastname</td><td>$tmp->email</td><td><img src='images/$web_config->theme/".getImage($tmp->is_active)."' /></td><td><img src='images/$web_config->theme/".getImage($tmp->needs_password_reset)."' /></td><td>".$groups["$tmp->rights_group_id"]->name."</td><td><a href='profile_edit.php?load_profile=true&profile_id=$tmp->id' title='Editer le profile'><img src='images/".$web_config->theme."/profile_edit_small.png' alt='Editer le profile'></a></td><td><a href='profile_remove.php?profile_id=$tmp->id' title='Supprimer définitivement le profile'><img src='images/".$web_config->theme."/profile_remove_small.png' alt='Supprimer définitivement le profile'></a></td></tr>";
				}
			?>
			</table>
		</p>
	</p>
</div>
<div id="bottomdock">
	<ul>
		<?php
			include 'backend/widgets/profile_add.dock.widget.php';
		?>
	</ul>
</div>

<?php
include_once 'footer.php';
?>
