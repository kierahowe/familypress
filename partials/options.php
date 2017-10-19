Options:


<form action='options.php' method='post'>

<h2>FamilyPress</h2>

<?php
settings_fields( 'fp_pluginPage' );
do_settings_sections( 'fp_pluginPage' );
submit_button();
?>

</form>
