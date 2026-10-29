<?php
/**
 * Assets Manager
 *
 * Centralise l'enqueue des assets (CSS/JS) du plugin.
 *
 * @package KT_Portfolio\Assets
 * @since   0.1.0
 */

namespace KT_Portfolio\Assets;

/**
 * Gestionnaire des assets (styles et scripts) du plugin.
 */
final class Assets {
	/**
	 * Constructeur privé (pattern statique).
	 */
	private function __construct() {}

	/**
	 * Enregistre tous les hooks d'enqueue d'assets.
	 *
	 * @return void
	 */
	public static function register(): void {
		add_action( 'admin_enqueue_scripts', array( self::class, 'admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( self::class, 'frontend_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( self::class, 'editor_assets' ) );
	}

	/**
	 * Enqueue les assets pour l'admin.
	 *
	 * @param string $hook_suffix Le hook suffix de la page admin courante.
	 * @return void
	 */
	public static function admin_assets( string $hook_suffix ): void {
		// Metaboxes - seulement sur les pages d'édition de Projet.
		if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			$screen = get_current_screen();
			if ( $screen && \KT_Portfolio\CPT\Projet_Post_Type::POST_TYPE === $screen->post_type ) {
				self::enqueue_metabox_statut_style();
				self::enqueue_metabox_technologies_style();
			}
		}

		// Liste des Technologies - colonne Logo.
		if ( 'edit.php' === $hook_suffix
			&& isset( $_GET['post_type'] )
			&& \KT_Portfolio\CPT\Technologie_Post_Type::POST_TYPE === $_GET['post_type']
		) {
			self::enqueue_technologie_list_style();
		}
	}

	/**
	 * Enqueue le style pour la metabox Statut.
	 *
	 * @return void
	 */
	private static function enqueue_metabox_statut_style(): void {
		$handle  = 'kt-portfolio-admin-metabox-statut';
		$path    = KT_PORTFOLIO_PATH . 'assets/css/admin/kt-portfolio-admin-metabox-statut.css';
		$url     = KT_PORTFOLIO_URL . 'assets/css/admin/kt-portfolio-admin-metabox-statut.css';
		$version = file_exists( $path ) ? filemtime( $path ) : KT_PORTFOLIO_VERSION;

		wp_enqueue_style( $handle, $url, array( 'wp-components' ), $version );
	}

	/**
	 * Enqueue le style pour la metabox Technologies.
	 *
	 * @return void
	 */
	private static function enqueue_metabox_technologies_style(): void {
		$handle  = 'kt-portfolio-admin-metabox-technologies';
		$path    = KT_PORTFOLIO_PATH . 'assets/css/admin/kt-portfolio-admin-metabox-technologies.css';
		$url     = KT_PORTFOLIO_URL . 'assets/css/admin/kt-portfolio-admin-metabox-technologies.css';
		$version = file_exists( $path ) ? filemtime( $path ) : KT_PORTFOLIO_VERSION;

		wp_enqueue_style( $handle, $url, array( 'wp-components' ), $version );
	}

	/**
	 * Enqueue le style pour la liste des Technologies (colonne Logo).
	 *
	 * @return void
	 */
	private static function enqueue_technologie_list_style(): void {
		$handle  = 'kt-portfolio-admin-technologie-list';
		$path    = KT_PORTFOLIO_PATH . 'assets/css/admin/kt-portfolio-admin-technologie-list.css';
		$url     = KT_PORTFOLIO_URL . 'assets/css/admin/kt-portfolio-admin-technologie-list.css';
		$version = file_exists( $path ) ? filemtime( $path ) : KT_PORTFOLIO_VERSION;

		wp_enqueue_style( $handle, $url, array(), $version );
	}

	/**
	 * Enqueue les assets pour le frontend.
	 *
	 * @return void
	 */
	public static function frontend_assets(): void {
		// TODO: Ajouter les assets frontend (shortcodes, blocs, etc.).
	}

	/**
	 * Enqueue les assets pour l'éditeur de blocs (Gutenberg).
	 *
	 * @return void
	 */
	public static function editor_assets(): void {
		// TODO: Ajouter les assets éditeur (blocs, preview, etc.).
	}
}
