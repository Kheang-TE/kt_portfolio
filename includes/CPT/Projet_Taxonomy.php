<?php
/**
 * Projet Taxonomy (Type de projet)
 *
 * Taxonomie hiérarchique pour catégoriser les projets.
 * UI : cases à cocher dans la barre latérale (sidebar).
 *
 * @package KT_Portfolio\CPT
 * @since   0.1.0
 */

namespace KT_Portfolio\CPT;

/**
 * Classe gérant la taxonomie "Type de projet" pour le CPT Projet.
 */
final class Projet_Taxonomy {
	/**
	 * Slug de la taxonomie.
	 */
	public const TAXONOMY = 'kt_projet_type';

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
	 * @return void
	 */
	public static function register_taxonomy(): void {
		$labels = array(
			// Généraux.
			'name'                  => _x( 'Types de projet', 'Taxonomy general name', 'kt-portfolio' ),
			'singular_name'         => _x( 'Type de projet', 'Taxonomy singular name', 'kt-portfolio' ),

			// Recherche / Liste.
			'search_items'          => __( 'Rechercher des types', 'kt-portfolio' ),
			'all_items'             => __( 'Tous les types', 'kt-portfolio' ),
			'parent_item'           => __( 'Type parent', 'kt-portfolio' ),
			'parent_item_colon'     => __( 'Type parent :', 'kt-portfolio' ),
			'edit_item'             => __( 'Modifier le type', 'kt-portfolio' ),
			'update_item'           => __( 'Mettre à jour le type', 'kt-portfolio' ),
			'add_new_item'          => __( 'Ajouter un type', 'kt-portfolio' ),
			'new_item_name'         => __( 'Nom du nouveau type', 'kt-portfolio' ),

			// Menu.
			'menu_name'             => __( 'Types de projet', 'kt-portfolio' ),

			// WP 5.5+.
			'back_to_items'         => __( '← Retour aux types', 'kt-portfolio' ),
			'item_link'             => __( 'Lien du type', 'kt-portfolio' ),
			'item_link_description' => __( 'Un lien vers un type de projet', 'kt-portfolio' ),
			'not_found'             => __( 'Aucun type trouvé.', 'kt-portfolio' ),
			'no_terms'              => __( 'Aucun type', 'kt-portfolio' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_rest'          => true,
			'rest_base'             => 'projet-types',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'query_var'             => true,
			'rewrite'               => array(
				'slug'         => _x( 'type-projet', 'Taxonomy slug', 'kt-portfolio' ),
				'with_front'   => false,
				'hierarchical' => true,
			),
			'meta_box_cb'           => 'post_categories_meta_box', // Cases à cocher dans la sidebar.
			'show_in_quick_edit'    => true,
		);

		register_taxonomy( self::TAXONOMY, array( \KT_Portfolio\CPT\Projet_Post_Type::POST_TYPE ), $args );
	}

	/**
	 * Retourne le slug de la taxonomie.
	 *
	 * @return string Slug de la taxonomie (ex: 'kt_projet_type')
	 */
	public static function get_taxonomy(): string {
		return self::TAXONOMY;
	}
}
