<?php
/**
 * Projet Statut Metabox
 *
 * Metabox pour gérer le statut d'avancement du projet (prévision, en cours, terminé).
 *
 * @package KT_Portfolio\Admin\Metaboxes
 * @since   0.1.0
 */

namespace KT_Portfolio\Admin\Metaboxes;

use KT_Portfolio\CPT\Projet_Post_Type;
use KT_Portfolio\Admin\Metaboxes\Metabox_Interface;

/**
 * Metabox pour le statut d'avancement du projet.
 */
final class Projet_Statut_Metabox implements Metabox_Interface {
	/**
	 * Identifiant unique de la metabox.
	 *
	 * @return string
	 */
	public static function get_id(): string {
		return 'kt_projet_statut';
	}

	/**
	 * Titre affiché dans l'admin.
	 *
	 * @return string
	 */
	public static function get_title(): string {
		return __( 'Statut du projet', 'kt-portfolio' );
	}

	/**
	 * Contexte d'affichage (normal, side, advanced).
	 *
	 * @return string
	 */
	public static function get_context(): string {
		return 'side';
	}

	/**
	 * Priorité d'affichage (high, default, low).
	 *
	 * @return string
	 */
	public static function get_priority(): string {
		return 'high';
	}

	/**
	 * Retourne les options de statut disponibles.
	 *
	 * @return array<string, string>
	 */
	private static function get_statut_options(): array {
		return array(
			'prevision' => __( 'Prévision', 'kt-portfolio' ),
			'encours'   => __( 'En cours', 'kt-portfolio' ),
			'termine'   => __( 'Terminé', 'kt-portfolio' ),
		);
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

		// Récupération de la valeur existante.
		$statut = get_post_meta( $post->ID, '_kt_projet_statut', true );
		if ( empty( $statut ) ) {
			$statut = 'prevision'; // Valeur par défaut.
		}

		$options = self::get_statut_options();
		?>
		<div class="kt-projet-statut-wrap">
			<label for="kt_projet_statut" class="screen-reader-text">
				<?php esc_html_e( 'Statut du projet', 'kt-portfolio' ); ?>
			</label>
			<select id="kt_projet_statut"
					name="kt_projet_statut"
					class="kt-projet-statut-select">
				<?php foreach ( $options as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $statut, $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<p class="description">
				<?php esc_html_e( 'État d\'avancement du projet.', 'kt-portfolio' ); ?>
			</p>
		</div>
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

		// Sauvegarde du statut avec validation.
		if ( isset( $_POST['kt_projet_statut'] ) ) {
			$value   = sanitize_text_field( wp_unslash( $_POST['kt_projet_statut'] ) );
			$allowed = array_keys( self::get_statut_options() );
			if ( in_array( $value, $allowed, true ) ) {
				update_post_meta( $post_id, '_kt_projet_statut', $value );
			}
		}
	}
}