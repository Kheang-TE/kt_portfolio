<?php
/**
 * Projet Liens Metabox
 *
 * Metabox pour gérer les URLs du projet (en ligne, backend, frontend).
 *
 * @package KT_Portfolio\Admin\Metaboxes
 * @since   0.1.0
 */

namespace KT_Portfolio\Admin\Metaboxes;

use KT_Portfolio\CPT\Projet_Post_Type;
use KT_Portfolio\Admin\Metaboxes\Metabox_Interface;

/**
 * Metabox pour les liens du projet.
 */
final class Projet_Liens_Metabox implements Metabox_Interface {
	/**
	 * Identifiant unique de la metabox.
	 *
	 * @return string
	 */
	public static function get_id(): string {
		return 'kt_projet_liens';
	}

	/**
	 * Titre affiché dans l'admin.
	 *
	 * @return string
	 */
	public static function get_title(): string {
		return __( 'Liens du projet', 'kt-portfolio' );
	}

	/**
	 * Contexte d'affichage (normal, side, advanced).
	 *
	 * @return string
	 */
	public static function get_context(): string {
		return 'normal';
	}

	/**
	 * Priorité d'affichage (high, default, low).
	 *
	 * @return string
	 */
	public static function get_priority(): string {
		return 'default';
	}

	/**
	 * Rendu du formulaire HTML.
	 *
	 * @param \WP_Post $post Objet post WordPress.
	 * @return void
	 */
	public static function render( \WP_Post $post ): void {
		// Nonce de sécurité.
		wp_nonce_field( 'kt_portfolio_' . self::get_id(), 'kt_portfolio_' . self::get_id() . '_nonce' );

		// Récupération des valeurs existantes.
		$lien_projet   = get_post_meta( $post->ID, '_kt_projet_lien_projet', true );
		$lien_backend  = get_post_meta( $post->ID, '_kt_projet_lien_backend', true );
		$lien_frontend = get_post_meta( $post->ID, '_kt_projet_lien_frontend', true );
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">
					<label for="kt_projet_lien_projet"><?php esc_html_e( 'Projet en ligne', 'kt-portfolio' ); ?></label>
				</th>
				<td>
					<input type="url"
							id="kt_projet_lien_projet"
							name="kt_projet_lien_projet"
							value="<?php echo esc_attr( $lien_projet ); ?>"
							class="regular-text"
							placeholder="<?php esc_attr_e( 'https://exemple.com', 'kt-portfolio' ); ?>" />
					<p class="description"><?php esc_html_e( 'URL publique du projet en ligne.', 'kt-portfolio' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="kt_projet_lien_backend"><?php esc_html_e( 'Back-end (administration)', 'kt-portfolio' ); ?></label>
				</th>
				<td>
					<input type="url"
							id="kt_projet_lien_backend"
							name="kt_projet_lien_backend"
							value="<?php echo esc_attr( $lien_backend ); ?>"
							class="regular-text"
							placeholder="<?php esc_attr_e( 'https://admin.exemple.com', 'kt-portfolio' ); ?>" />
					<p class="description"><?php esc_html_e( 'URL d\'accès à l\'administration du projet.', 'kt-portfolio' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="kt_projet_lien_frontend"><?php esc_html_e( 'Front-end (interface utilisateur)', 'kt-portfolio' ); ?></label>
				</th>
				<td>
					<input type="url"
							id="kt_projet_lien_frontend"
							name="kt_projet_lien_frontend"
							value="<?php echo esc_attr( $lien_frontend ); ?>"
							class="regular-text"
							placeholder="<?php esc_attr_e( 'https://app.exemple.com', 'kt-portfolio' ); ?>" />
					<p class="description"><?php esc_html_e( 'URL de l\'interface utilisateur du projet.', 'kt-portfolio' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Sauvegarde les données de la metabox.
	 *
	 * @param int      $post_id ID du post.
	 * @param \WP_Post $post    Objet post WordPress.
	 * @return void
	 */
	public static function save( int $post_id, \WP_Post $post ): void {
		// Vérifications communes.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( Projet_Post_Type::POST_TYPE !== $post->post_type ) {
			return;
		}

		// Nonce verification.
		$nonce_action = 'kt_portfolio_' . self::get_id();
		$nonce_name   = $nonce_action . '_nonce';

		// Verify nonce first.
		$nonce_value = filter_input( INPUT_POST, $nonce_name, FILTER_UNSAFE_RAW );
		if ( empty( $nonce_value ) || ! wp_verify_nonce( $nonce_value, $nonce_action ) ) {
			return;
		}

		// Sauvegarde des URLs avec sanitization.
		$fields = array(
			'_kt_projet_lien_projet'   => 'kt_projet_lien_projet',
			'_kt_projet_lien_backend'  => 'kt_projet_lien_backend',
			'_kt_projet_lien_frontend' => 'kt_projet_lien_frontend',
		);

		foreach ( $fields as $meta_key => $post_key ) {
			if ( isset( $_POST[ $post_key ] ) ) {
				$value = esc_url_raw( wp_unslash( $_POST[ $post_key ] ) );
				update_post_meta( $post_id, $meta_key, $value );
			}
		}
	}
}