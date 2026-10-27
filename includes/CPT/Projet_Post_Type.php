<?php
/**
 * Projet Custom Post Type
 *
 * Enregistre le type de contenu personnalisé "Projet" pour afficher les projets réalisés ou en cours.
 *
 * @package KT_Portfolio\CPT
 * @since   0.1.0
 */

namespace KT_Portfolio\CPT;

/**
 * Classe gérant le Custom Post Type Projet.
 *
 * Utilise le pattern statique pour l'enregistrement des hooks.
 * Tous les labels sont internationalisés via le text-domain 'kt-portfolio'.
 */
final class Projet_Post_Type {
	/**
	 * Slug du post type (utilisé dans URLs, queries, REST API).
	 */
	public const POST_TYPE = 'kt_projet';

	/**
	 * Constructeur privé (pattern statique).
	 */
	private function __construct() {}

	/**
	 * Enregistre le CPT et les hooks WordPress.
	 *
	 * Hooké sur 'init' avec priorité 0 pour être disponible tôt.
	 *
	 * @return void
	 */
	public static function register(): void {
		add_action( 'init', array( self::class, 'register_post_type' ), 0 );
	}

	/**
	 * Déclare le post type auprès de WordPress.
	 *
	 * Configure labels complets en français, supports, rewrite, REST API.
	 *
	 * @return void
	 */
	public static function register_post_type(): void {
		$labels = array(
			// Généraux.
			'name'                  => _x( 'Projets', 'Post type general name', 'kt-portfolio' ),
			'singular_name'         => _x( 'Projet', 'Post type singular name', 'kt-portfolio' ),

			// Menu admin.
			'menu_name'             => _x( 'Projets', 'Admin Menu text', 'kt-portfolio' ),
			'name_admin_bar'        => _x( 'Projet', 'Add New on Toolbar', 'kt-portfolio' ),

			// Actions.
			'add_new'               => __( 'Ajouter', 'kt-portfolio' ),
			'add_new_item'          => __( 'Ajouter un projet', 'kt-portfolio' ),
			'new_item'              => __( 'Nouveau projet', 'kt-portfolio' ),
			'edit_item'             => __( 'Modifier le projet', 'kt-portfolio' ),
			'view_item'             => __( 'Voir le projet', 'kt-portfolio' ),
			'view_items'            => __( 'Voir les projets', 'kt-portfolio' ),
			'all_items'             => __( 'Tous les projets', 'kt-portfolio' ),
			'search_items'          => __( 'Rechercher des projets', 'kt-portfolio' ),
			'parent_item_colon'     => __( 'Projets parents :', 'kt-portfolio' ),

			// États vides.
			'not_found'             => __( 'Aucun projet trouvé.', 'kt-portfolio' ),
			'not_found_in_trash'    => __( 'Aucun projet trouvé dans la corbeille.', 'kt-portfolio' ),

			// Image à la une (WP 5.5+).
			'featured_image'        => _x(
				'Image de couverture du projet',
				'Overrides "Featured Image"',
				'kt-portfolio'
			),
			'set_featured_image'    => _x(
				'Définir l\'image de couverture',
				'Overrides "Set featured image"',
				'kt-portfolio'
			),
			'remove_featured_image' => _x(
				'Supprimer l\'image de couverture',
				'Overrides "Remove featured image"',
				'kt-portfolio'
			),
			'use_featured_image'    => _x(
				'Utiliser comme image de couverture',
				'Overrides "Use as featured image"',
				'kt-portfolio'
			),

			// Archives.
			'archives'              => _x(
				'Archives des projets',
				'The post type archive label',
				'kt-portfolio'
			),
			'insert_into_item'      => _x(
				'Insérer dans le projet',
				'Overrides "Insert into post"',
				'kt-portfolio'
			),
			'uploaded_to_this_item' => _x(
				'Téléversé pour ce projet',
				'Overrides "Uploaded to this post"',
				'kt-portfolio'
			),

			// Filtres / Navigation (WP 5.5+).
			'filter_items_list'     => _x(
				'Filtrer la liste des projets',
				'Screen reader text for filter links',
				'kt-portfolio'
			),
			'items_list_navigation' => _x(
				'Navigation liste des projets',
				'Screen reader text for pagination',
				'kt-portfolio'
			),
			'items_list'            => _x(
				'Liste des projets',
				'Screen reader text for items list',
				'kt-portfolio'
			),

			// Supplémentaires utiles.
			'attributes'            => __( 'Attributs du projet', 'kt-portfolio' ),
			'filter_by_date'        => __( 'Filtrer par date', 'kt-portfolio' ),
			'filter_by_author'      => __( 'Filtrer par auteur', 'kt-portfolio' ),
		);

		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'show_in_rest'          => true,
			'rest_base'             => 'projets',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-clipboard',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'page-attributes' ),
			'rewrite'               => array(
				'slug'       => _x( 'projet', 'URL slug', 'kt-portfolio' ),
				'with_front' => false,
				'feeds'      => true,
				'pages'      => true,
			),
			'query_var'             => true,
			'can_export'            => true,
			'delete_with_user'      => false,
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Retourne le slug du post type.
	 *
	 * @return string Slug du CPT (ex: 'kt_projet')
	 */
	public static function get_post_type(): string {
		return self::POST_TYPE;
	}
}
