<?php
/**
 * Metabox Registry
 *
 * Enregistre toutes les metaboxes du plugin pour le CPT Projet.
 *
 * @package KT_Portfolio\Admin\Metaboxes
 * @since   0.1.0
 */

namespace KT_Portfolio\Admin\Metaboxes;

use KT_Portfolio\CPT\Projet_Post_Type;

/**
 * Registre centralisé des metaboxes.
 */
final class Metabox_Registry {
	/**
	 * Constructeur privé (pattern statique).
	 */
	private function __construct() {}

	/**
	 * Enregistre toutes les metaboxes du plugin.
	 *
	 * @return void
	 */
	public static function register(): void {
		add_action( 'add_meta_boxes', array( self::class, 'add_metaboxes' ) );
		add_action( 'save_post', array( self::class, 'save_metaboxes' ), 10, 2 );
	}

	/**
	 * Ajoute les metaboxes déclarées pour le CPT Projet.
	 *
	 * @return void
	 */
	public static function add_metaboxes(): void {
		$post_type = Projet_Post_Type::POST_TYPE;

		$metaboxes = self::get_metaboxes();

		foreach ( $metaboxes as $metabox_class ) {
			add_meta_box(
				$metabox_class::get_id(),
				$metabox_class::get_title(),
				array( $metabox_class, 'render' ),
				$post_type,
				$metabox_class::get_context(),
				$metabox_class::get_priority()
			);
		}
	}

	/**
	 * Sauvegarde les données de toutes les metaboxes.
	 *
	 * @param int      $post_id ID du post.
	 * @param \WP_Post $post    Objet post WordPress.
	 * @return void
	 */
	public static function save_metaboxes( int $post_id, \WP_Post $post ): void {
		$metaboxes = self::get_metaboxes();

		foreach ( $metaboxes as $metabox_class ) {
			$metabox_class::save( $post_id, $post );
		}
	}

	/**
	 * Retourne la liste des classes de metaboxes à enregistrer.
	 *
	 * @return array<class-string<\KT_Portfolio\Admin\Metaboxes\Metabox_Interface>> Liste des classes de metaboxes.
	 */
	private static function get_metaboxes(): array {
		$metaboxes = array(
			\KT_Portfolio\Admin\Metaboxes\Projet_Liens_Metabox::class,
			\KT_Portfolio\Admin\Metaboxes\Projet_Statut_Metabox::class,
		);

		return $metaboxes;
	}
}
