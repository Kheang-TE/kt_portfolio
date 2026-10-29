<?php
/**
 * Technologie Taxonomy - Domaine
 *
 * Taxonomie hiérarchique pour classifier les technologies par domaine
 * (backend, frontend, devops, infographie, etc.).
 *
 * @package KT_Portfolio\CPT
 * @since   0.1.0
 */

namespace KT_Portfolio\CPT;

/**
 * Classe gérant la taxonomie Domaine pour le CPT Technologie.
 */
final class Technologie_Taxonomy {
	/**
	 * Slug de la taxonomie (sans préfixe, WP ajoute le préfixe du plugin).
	 */
	public const TAXONOMY = 'domaine_technologie';

	/**
	 * Constructeur privé (pattern statique).
	 */
	private function __construct() {}

	/**
	 * Enregistre la taxonomie et les hooks WordPress.
	 *
	 * @return void
	 */
	public static function register(): void {
		add_action( 'init', array( self::class, 'register_taxonomy' ), 0 );
	}

	/**
	 * Déclare la taxonomie auprès de WordPress.
	 *
	 * Configure labels complets en français, hiérarchique, REST API, UI admin.
	 *
	 * @return void
	 */
	public static function register_taxonomy(): void {
		$labels = array(
			// Généraux.
			'name'                       => _x( 'Domaines', 'Taxonomy general name', 'kt-portfolio' ),
			'singular_name'              => _x( 'Domaine', 'Taxonomy singular name', 'kt-portfolio' ),
			'search_items'               => __( 'Rechercher des domaines', 'kt-portfolio' ),
			'popular_items'              => __( 'Domaines populaires', 'kt-portfolio' ),
			'all_items'                  => __( 'Tous les domaines', 'kt-portfolio' ),
			'parent_item'                => __( 'Domaine parent', 'kt-portfolio' ),
			'parent_item_colon'          => __( 'Domaine parent :', 'kt-portfolio' ),
			'edit_item'                  => __( 'Modifier le domaine', 'kt-portfolio' ),
			'update_item'                => __( 'Mettre à jour le domaine', 'kt-portfolio' ),
			'add_new_item'               => __( 'Ajouter un domaine', 'kt-portfolio' ),
			'new_item_name'              => __( 'Nom du nouveau domaine', 'kt-portfolio' ),
			'separate_items_with_commas' => __( 'Séparez les domaines par des virgules', 'kt-portfolio' ),
			'add_or_remove_items'        => __( 'Ajouter ou supprimer des domaines', 'kt-portfolio' ),
			'choose_from_most_used'      => __( 'Choisir parmi les domaines les plus utilisés', 'kt-portfolio' ),
			'not_found'                  => __( 'Aucun domaine trouvé', 'kt-portfolio' ),
			'menu_name'                  => __( 'Domaines', 'kt-portfolio' ),
			'back_to_items'              => __( '← Retour aux domaines', 'kt-portfolio' ),
		);

		$args = array(
			'hierarchical'       => true,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'show_in_quick_edit' => true,
			'show_in_menu'       => true,
			'meta_box_cb'        => false, // Utilise la metabox par défaut de WP pour taxonomies hiérarchiques.
			'rewrite'            => array(
				'slug'         => _x( 'technologie-domaine', 'Taxonomy slug', 'kt-portfolio' ),
				'with_front'   => false,
				'hierarchical' => true,
			),
			'query_var'          => true,
			'capabilities'       => array(
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'edit_posts',
			),
		);

		register_taxonomy( self::TAXONOMY, array( Technologie_Post_Type::POST_TYPE ), $args );
	}

	/**
	 * Retourne le slug de la taxonomie.
	 *
	 * @return string Slug de la taxonomie (ex: 'domaine_technologie')
	 */
	public static function get_taxonomy(): string {
		return self::TAXONOMY;
	}
}
