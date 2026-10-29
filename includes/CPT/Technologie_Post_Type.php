<?php
/**
 * Technologie Custom Post Type
 *
 * CPT de référence pour les technologies (WordPress, Symfony, React, etc.).
 * Utilisé comme entité de référence avec image mise en avant.
 * Pas de page publique, pas d'archive.
 *
 * @package KT_Portfolio\CPT
 * @since   0.1.0
 */

namespace KT_Portfolio\CPT;

/**
 * Classe gérant le Custom Post Type Technologie.
 */
final class Technologie_Post_Type {
	/**
	 * Slug du post type.
	 */
	public const POST_TYPE = 'kt_technologie';

	/**
	 * Constructeur privé (pattern statique).
	 */
	private function __construct() {}

	/**
	 * Enregistre le CPT et les hooks WordPress.
	 *
	 * @return void
	 */
	public static function register(): void {
		add_action( 'init', array( self::class, 'register_post_type' ), 0 );
		add_filter( 'manage_edit-' . self::POST_TYPE . '_columns', array( self::class, 'set_custom_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( self::class, 'display_custom_column' ), 10, 2 );
	}

	/**
	 * Déclare le post type auprès de WordPress.
	 *
	 * @return void
	 */
	public static function register_post_type(): void {
		$labels = array(
			// Généraux.
			'name'                  => _x( 'Technologies', 'Post type general name', 'kt-portfolio' ),
			'singular_name'         => _x( 'Technologie', 'Post type singular name', 'kt-portfolio' ),

			// Menu admin.
			'menu_name'             => _x( 'Technologies', 'Admin Menu text', 'kt-portfolio' ),
			'name_admin_bar'        => _x( 'Technologie', 'Add New on Toolbar', 'kt-portfolio' ),

			// Actions.
			'add_new'               => __( 'Ajouter', 'kt-portfolio' ),
			'add_new_item'          => __( 'Ajouter une technologie', 'kt-portfolio' ),
			'new_item'              => __( 'Nouvelle technologie', 'kt-portfolio' ),
			'edit_item'             => __( 'Modifier la technologie', 'kt-portfolio' ),
			'view_item'             => __( 'Voir la technologie', 'kt-portfolio' ),
			'view_items'            => __( 'Voir les technologies', 'kt-portfolio' ),
			'all_items'             => __( 'Toutes les technologies', 'kt-portfolio' ),
			'search_items'          => __( 'Rechercher des technologies', 'kt-portfolio' ),
			'parent_item_colon'     => __( 'Technologies parentes :', 'kt-portfolio' ),

			// États vides.
			'not_found'             => __( 'Aucune technologie trouvée.', 'kt-portfolio' ),
			'not_found_in_trash'    => __( 'Aucune technologie trouvée dans la corbeille.', 'kt-portfolio' ),

			// Image à la une (WP 5.5+).
			'featured_image'        => _x( 'Logo de la technologie', 'Overrides "Featured Image"', 'kt-portfolio' ),
			'set_featured_image'    => _x( 'Définir le logo', 'Overrides "Set featured image"', 'kt-portfolio' ),
			'remove_featured_image' => _x( 'Supprimer le logo', 'Overrides "Remove featured image"', 'kt-portfolio' ),
			'use_featured_image'    => _x( 'Utiliser comme logo', 'Overrides "Use as featured image"', 'kt-portfolio' ),

			// Archives.
			'archives'              => _x( 'Archives des technologies', 'The post type archive label', 'kt-portfolio' ),
			'insert_into_item'      => _x( 'Insérer dans la technologie', 'Overrides "Insert into post"', 'kt-portfolio' ),
			'uploaded_to_this_item' => _x( 'Téléversé pour cette technologie', 'Overrides "Uploaded to this post"', 'kt-portfolio' ),

			// Filtres / Navigation (WP 5.5+).
			'filter_items_list'     => _x( 'Filtrer la liste des technologies', 'Screen reader text for filter links', 'kt-portfolio' ),
			'items_list_navigation' => _x( 'Navigation liste des technologies', 'Screen reader text for pagination', 'kt-portfolio' ),
			'items_list'            => _x( 'Liste des technologies', 'Screen reader text for items list', 'kt-portfolio' ),

			// Supplémentaires utiles.
			'attributes'            => __( 'Attributs de la technologie', 'kt-portfolio' ),
			'filter_by_date'        => __( 'Filtrer par date', 'kt-portfolio' ),
			'filter_by_author'      => __( 'Filtrer par auteur', 'kt-portfolio' ),
		);

		$args = array(
			'labels'                => $labels,
			'public'                => false,
			'publicly_queryable'    => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => false,
			'show_in_admin_bar'     => true,
			'show_in_rest'          => true,
			'rest_base'             => 'technologies',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'has_archive'           => false,
			'hierarchical'          => false,
			'menu_position'         => 21,
			'menu_icon'             => 'dashicons-admin-tools',
			'supports'              => array( 'title', 'thumbnail' ),
			'taxonomies'            => array( Technologie_Taxonomy::TAXONOMY ),
			'query_var'             => false,
			'can_export'            => true,
			'delete_with_user'      => false,
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Retourne le slug du post type.
	 *
	 * @return string Slug du CPT (ex: 'kt_technologie')
	 */
	public static function get_post_type(): string {
		return self::POST_TYPE;
	}

	/**
	 * Récupère toutes les technologies pour l'affichage dans les metaboxes.
	 *
	 * @return array<int, object> Liste des objets technologie.
	 */
	public static function get_all_technologies(): array {
		$technologies = get_posts(
			array(
				'post_type'      => self::POST_TYPE,
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
				'fields'         => 'ids',
			)
		);

		$results = array();
		foreach ( $technologies as $tech_id ) {
			$results[] = (object) array(
				'id'        => $tech_id,
				'title'     => get_the_title( $tech_id ),
				'thumbnail' => get_the_post_thumbnail_url( $tech_id, 'thumbnail' ),
			);
		}

		return $results;
	}

	/**
	 * Définit les colonnes personnalisées pour la liste d'administration.
	 *
	 * @param array $columns Colonnes par défaut.
	 * @return array Colonnes modifiées.
	 */
	public static function set_custom_columns( array $columns ): array {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'date' === $key ) {
				continue; // Supprime la colonne Date.
			}
			$new_columns[ $key ] = $value;
		}

		// Place la colonne Logo entre la checkbox (cb) et le Titre (title).
		if ( isset( $new_columns['title'] ) ) {
			$title = $new_columns['title'];
			unset( $new_columns['title'] );
			$new_columns = array_merge(
				array( 'cb' => $new_columns['cb'] ),
				array( 'logo' => __( 'Logo', 'kt-portfolio' ) ),
				array( 'title' => $title ),
				$new_columns
			);
		} else {
			// Fallback si 'title' n'existe pas.
			$new_columns = array_merge( array( 'logo' => __( 'Logo', 'kt-portfolio' ) ), $new_columns );
		}

		return $new_columns;
	}

	/**
	 * Affiche le contenu de la colonne personnalisée.
	 *
	 * @param string $column_name Nom de la colonne.
	 * @param int    $post_id     ID du post.
	 * @return void
	 */
	public static function display_custom_column( string $column_name, int $post_id ): void {
		if ( 'logo' === $column_name ) {
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, array( 40, 40 ), array( 'class' => 'kt-technologie-logo-admin' ) );
			} else {
				echo '<span class="dashicons dashicons-admin-media" style="font-size: 24px; color: #ccc;"></span>';
			}
		}
	}
}
