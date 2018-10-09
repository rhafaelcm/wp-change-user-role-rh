<?php 
//Funções do Plugin
//
//Variáveis globais de configuração
//add_option('ActiveUserRoleLogin', '0');
//add_option('ActiveUserRoleLogin', '0');
//add_option('StartIdUser', '1');
//add_option('FinishIdUser', '1');	
//add_option('role_search', 'customer');
//add_option('role_update', 'wholesale_buyer');
//add_option('meta_field_search', 'shipping_state');
//add_option('meta_field_value_search', 'MS');
//

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'wpChangeUserRoleFunctions' ) ){
	class wpChangeUserRoleFunctions{
		//Função principal de varredura e modificação dos meta fields
		public function varredura(){
			//Coloque true para retornar string e false para retornar array
			$single = true;
			$users_update = 0;
			
			//
			//Loop de varredura
			//
			for($i = get_option('StartIdUser'); $i <= get_option('FinishIdUser'); $i++){
				//get_userdata recebe os dados do usuário atual dentro do loop
				$user_info = get_userdata($i);
				//se o Id for invalido, pula o loop para o proximo Id
				if(!$user_info->ID) continue;
				//Verifica se o "meta_field_search" é igual "meta_field_value_search" e "user_role" é igual a role_search
				if ( get_user_meta($i, get_option('meta_field_search'), $single) == get_option('meta_field_value_search') && implode(', ', $user_info->roles) == get_option('role_search')){
					$users_update++;
					$this->update_user_role_rh($i);
				}
			}
			//
			//Fim do loop da varredura
			//

			//Retorna o total de Usuários Atualizados
			return $users_update;
		}
		//Função atualiza o cliente para a Role novo
		public function update_user_role_rh($id){
			$u = new WP_User($id);
			$u->remove_role(get_option('role_search'));
			$u->add_role(get_option('role_update'));
		}
		
		//Função para obter o ID do Ultimo Usuário cadastrado no banco de dados
		public function lastIdUser(){
			//count_user() é uma função do Wordpress para contagem de usuários
			$result = count_users();
			$ultimoId = 1;
			//Loop de contagem
			for($i = 1; ; $i++){
				$x = get_userdata($i);
				//compara se o usuário em branco é o ultimo mesmo, porque as vez tem buracos entre os usuários
				if(!$x->ID && $i >= $result['total_users']){
					$ultimoId = $i - 1;
					break;
				}
				
			}
			return $ultimoId;
		}
	}
	
	$wpChangeUserRoleFunctions = new wpChangeUserRoleFunctions();
}
