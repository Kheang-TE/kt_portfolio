<?php
/**
 * Projet Technologies Metabox
 *
 * Metabox pour sélectionner les technologies associées au projet via checkboxes.
 * Les technologies proviennent du CPT Technologie.
 *
 * @package KT_Portfolio\Admin\Metaboxes
 * @since   0.1.0
 */

namespace KT_Portfolio\Admin\Metaboxes;

use KT_Portfolio\CPT\Projet_Post_Type;
use KT_Portfolio\CPT\Technologie_Post_Type;

/**
 * Metabox pour les technologies du projet.
 */
final class Projet_Technologies_Metabox implements \KT_Portfolio\Admin\Metaboxes\Metabox_Interface {
	/**
	 * Identifiant unique de la metabox.
	 *
	 * @return string
	 */
	public static function get_id(): string {
		return 'kt_projet_technologies';
	}

	/**
	 * Titre affiché dans l'admin.
	 *
	 * @return string
	 */
	public static function get_title(): string {
		return __( 'Technologies utilisées', 'kt-portfolio' );
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

		// Récupération des technologies sélectionnées.
		$selected_technologies = get_post_meta( $post->ID, '_kt_projet_technologies', true );
		if ( ! is_array( $selected_technologies ) ) {
			$selected_technologies = array();
		}

		// Récupération de toutes les technologies.
		$technologies = Technologie_Post_Type::get_all_technologies();
		?>
		<div class="kt-projet-technologies-wrap">
			<p class="description"><?php esc_html_e( 'Sélectionnez les technologies utilisées pour ce projet.', 'kt-portfolio' ); ?></p>

			<?php if ( empty( $technologies ) ) : ?>
				<p>
					<?php
					esc_html_e( 'Aucune technologie disponible. Cr', 'kt-portfolio' );
					esc_html_e( 'ez d\'abord des technologies dans le menu <strong>Technologies</strong>.', 'kt-portfolio' );
					?>
				</p>
			<?php else : ?>
				<div class="kt-technologies-grid">
					<?php foreach ( $technologies as $tech ) : ?>
						<label class="kt-technology-item">
							<input type="checkbox"
									name="kt_projet_technologies[]"
									value="<?php echo esc_attr( $tech->id ); ?>"
									<?php checked( in_array( $tech->id, $selected_technologies, true ), true ); ?> />
							<?php if ( $tech->thumbnail ) : ?>
								<span class="kt-technology-thumbnail">
									<img src="<?php echo esc_url( $tech->thumbnail ); ?>" alt="" width="32" height="32" />
								</span>
							<?php endif; ?>
							<span class="kt-technology-title"><?php echo esc_html( $tech->title ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
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

		// Sauvegarde des technologies sélectionnées.
		$technologies = array();
		$raw_input    = filter_input( INPUT_POST, 'kt_projet_technologies', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		if ( false !== $raw_input && is_array( $raw_input ) ) {
			foreach ( $raw_input as $tech_id ) {
				$tech_id = absint( $tech_id );
				if ( $tech_id > 0 ) {
					$technologies[] = $tech_id;
				}
			}
		}

		update_post_meta( $post_id, '_kt_projet_technologies', $technologies );
	}
}