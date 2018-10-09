<?php 
/*
Plugin Name: WP Change User Role RH
Description: Esse Plugin troca o user role para outro automaticamente conforme Estado do endereço de cobrança. Útil quando se deseja aplicar alguns filtros utilizando outros plugins que filtra apenas o user role.
Version: 1.0
Author: Rhafael da Costa Martins
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'wpChangeUserRoleRh' ) ){
	class wpChangeUserRoleRh{
		function __construct() {
			if(get_option('ActiveUserRoleLogin') == 1){
				add_action('wp_login', array($this,'update_user_login'));
			}
			else{
				remove_action('wp_login', array($this,'update_user_login'));
			}
		}
		//Variáveis global
		public function ativar(){
			add_option('ActiveUserRoleLogin', '0');
			add_option('updateUserRoleLogin', '0');
			add_option('StartIdUser', '1');
			add_option('FinishIdUser', '1');	
			add_option('role_search', 'customer');
			add_option('role_update', 'wholesale_buyer');
			add_option('meta_field_search', 'shipping_state');
			add_option('meta_field_value_search', 'MS');
		}
		public function desativar(){
			delete_option('ActiveUserRoleLogin');
			delete_option('updateUserRoleLogin');
			delete_option('StartIdUser');
			delete_option('FinishIdUser');
			delete_option('role_search');
			delete_option('role_update');
			delete_option('meta_field_search');
			delete_option('meta_field_value_search');
		}
		public function criarMenu(){
			//Cria o menu e inclui o /menu-config.php como menu principal
			add_menu_page('Change User Role', 'Change User Role', 'manage_options', 'wp-change-user-role-rh/Include/menu-config.php', '', 'dashicons-admin-tools', 58);
			//add_submenu_page('meu-wp/meu-wp-config.php', 'Sub pagina 01', 'titulo 01', 10, 'meu-wp/meu-wp-sub-pagina.php');
		}
		
		//Função para varrer o cliente quando fizer o login
		//a variável $login é do sistema add_action ('wp_login', 'sua_função', 10, 2);
		public function update_user_login($login){
			//$user recebe os dados do cliente logado
			$user = get_userdatabylogin($login);
			//Coloque true para retornar string e false para retornar array
			$single = true;
			//Compara se o cliente ja foi atualizado ou não
			if ( get_user_meta($user->ID, get_option('meta_field_search'), $single) == get_option('meta_field_value_search') && implode(', ', $user->roles) == get_option('role_search')){
				//atualiza quantos clientes foram atualizados pelo login 
				$updateuser = get_option('updateUserRoleLogin') + 1;
				//$updateuser = $user->ID; //para fins de teste
				update_option('updateUserRoleLogin', $updateuser);
				//atualiza o cliente para a Role nova
				$user->remove_role(get_option('role_search'));
				$user->add_role(get_option('role_update'));
			}
		}
	}
	$wpChangeUserRoleRh = new wpChangeUserRoleRh();
	
	//Incluir as funções do Plugin
	require_once( 'Include/functions.php' );
	
	
	$pathPluginRH = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);
	
	// Função criar variável global
	register_activation_hook( $pathPluginRH, array('wpChangeUserRoleRh','ativar'));
	
	// Função excluir variável global
	register_deactivation_hook( $pathPluginRH, array('wpChangeUserRoleRh','desativar'));
	
	//Função criar menu
	add_action('admin_menu', array('wpChangeUserRoleRh', 'criarMenu'));
	
}


