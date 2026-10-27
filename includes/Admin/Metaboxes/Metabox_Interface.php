<?php
/**
 * Metabox Interface
 *
 * Interface commune pour toutes les métaboxes du plugin.
 *
 * @package KT_Portfolio\Admin\Metaboxes
 * @since   0.1.0
 */

namespace KT_Portfolio\Admin\Metaboxes;

/**
 * Interface commune pour toutes les métaboxes.
 */
interface Metabox_Interface {
	/**
	 * Retourne l'identifiant unique de la métabox.
	 *
	 * @return string
	 */
	public static function get_id(): string;

	/**
	 * Retourne le titre affiché dans l'admin.
	 *
	 * @return string
	 */
	public static function get_title(): string;

	/**
	 * Retourne le contexte (normal, side, advanced).
	 *
	 * @return string
	 */
	public static function get_context(): string;

	/**
	 * Retourne la priorité (high, default, low).
	 *
	 * @return string
	 */
	public static function get_priority(): string;

	/**
	 * Rendu du formulaire HTML.
	 *
	 * @param \WP_Post $post Objet post WordPress.
	 * @return void
	 */
	public static function render( \WP_Post $post ): void;

	/**
	 * Sauvegarde les données (nonce, permissions, sanitization).
	 *
	 * @param int      $post_id ID du post.
	 * @param \WP_Post $post    Objet post WordPress.
	 * @return void
	 */
	public static function save( int $post_id, \WP_Post $post ): void;
}
