<?php 
$msg = '';

if($_POST){
	//Atualiza as variáveis global de configuração quando envia o formulário
	if($_POST['ActiveUserRoleLoginValue']){
		update_option('ActiveUserRoleLogin', '1');
	}else{
		update_option('ActiveUserRoleLogin', '0');
	}
	if($_POST['StartIdUserValue'] > $_POST['FinishIdUserValue']){
		update_option('StartIdUser', $_POST['FinishIdUserValue']);
	}else{
		update_option('StartIdUser', $_POST['StartIdUserValue']);
	}
	if($_POST['FinishIdUserValue'] > $wpChangeUserRoleFunctions->lastIdUser()){
		update_option('FinishIdUser', $wpChangeUserRoleFunctions->lastIdUser());
	}else{
		update_option('FinishIdUser', $_POST['FinishIdUserValue']);
	}
	if($_POST['meta_field_searchValue']){
		update_option('meta_field_search', $_POST['meta_field_searchValue']);
	}
	if($_POST['meta_field_value_searchValue']){
		update_option('meta_field_value_search', $_POST['meta_field_value_searchValue']);
	}
	if($_POST['role_searchValue']){
		update_option('role_search', $_POST['role_searchValue']);
	}
	if($_POST['role_updateValue']){
		update_option('role_update', $_POST['role_updateValue']);
	}
	
	$msg = '<div class="updated" id="message"><p>Configuração Salva!</p></div>';
	//Quando envia o fomulário e está marcado o check list, inicia a função de varredura
	if($_POST['ActiveUserRoleRhafaelValue']){
		$totalConcluido = get_option('FinishIdUser') - get_option('StartIdUser');
		$msg = '<div class="updated" id="message"><p>Varredura feita em: ' . $totalConcluido . ' IDs</p><p>Total de Usuários atualizados: ' . $wpChangeUserRoleFunctions->varredura() . '</p></div>';
	}
}
?>
<div class="wrap">
<div id="icon-plugins" class="icon32"><br /></div> 

<h2>Configurações</h2>
<h2></h2>
<?php echo $msg; ?>
<form action="" method="post">
<dt>
	<br><b><?php
		//Bloco responsável por mostrar quantos usuários cadastrado e o ID do Ultimo usuário
		$result = count_users();
		echo 'Existem ', $result['total_users'], ' usuários';
		foreach($result['avail_roles'] as $role => $count)
		    echo ', ', $count, ' são ', $role, 's'; 
		echo '.';
		echo '<br></br>';
		$user_info = get_userdata($wpChangeUserRoleFunctions->lastIdUser());
		echo ' Ultimo ID: ' . $user_info->ID . ' User Login: ' . $user_info->user_login . ' Role: ' . implode(', ', $user_info->roles);
		echo '<br>Total de usuários atualizados automáticamente: ' . (int)get_option('updateUserRoleLogin') . '</br>';
	?></b></br>
	<br></br>
	<style>
	table, th, td {
		//border: 1px solid black;
		//border-collapse: collapse;
	}
	th, td {
		padding-right: 10px;
		padding-left: 10px;
		text-align: left;    
	}
	</style>
	<table>
	<tr>
    		<th>IDs de Varredura </th>
    		<th colspan="2">Variáveis de Substituição</th>
	</tr>
	<tr>
		<td>ID Inicial</td>
		<td>meta_field_search</td>
		<td>role_search</td>
	</tr>
	<tr>
		<td><input type="number" name="StartIdUserValue" min="1" size="4" max="<?php echo $wpChangeUserRoleFunctions->lastIdUser();?>" value="<?php echo get_option('StartIdUser');?>"/>
		Não colocar maior que ID Final!</td>
		<td><input type="text" name="meta_field_searchValue" size="20" value="<?php echo get_option('meta_field_search');?>"/></td>
		<td><select name="role_searchValue">
			<option value=""></option>
			<?php wp_dropdown_roles(get_option('role_search')); ?>
		</select></td>

	</tr>
	<tr>
    		<td>ID Final</td>
    		<td>meta_field_value_search</td>
    		<td>role_update</td>
    		
	</tr>
	<tr>
		<td><input type="number" name="FinishIdUserValue" min="1" size="4" max="<?php echo $wpChangeUserRoleFunctions->lastIdUser();?>" value="<?php echo get_option('FinishIdUser');?>"/>
		Máximo permitido é <?php echo $wpChangeUserRoleFunctions->lastIdUser();?></td>
		<td><input type="text" name="meta_field_value_searchValue" size="20" value="<?php echo get_option('meta_field_value_search');?>"/></td>
		<td><select name="role_updateValue">
			<option value=""></option>
			<?php wp_dropdown_roles(get_option('role_update')); ?>
		</select></td>
		
	</tr>
	</table>
	<br></br>
		<input type="checkbox" name="ActiveUserRoleLoginValue" value="1" <?php if(get_option('ActiveUserRoleLogin') == 1){echo checked;} ?>/>
	<b>Marque para ativar a varredura automática quando o usuário fizer o login! </b>
	<br></br>
		<input type="checkbox" name="ActiveUserRoleRhafaelValue" value="1" />
	<b>Para iniciar a varredura marque o Check List antes de Salvar! </b>
	<br></br>
		<input type="submit" name="Submit" class="button-primary" value="Salvar" /> 

</form>
</div>
</div>