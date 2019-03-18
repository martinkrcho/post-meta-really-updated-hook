<?php

if ( ! class_exists( 'Post_Meta_Really_Updated_Hook_Util' ) ) {

	class Post_Meta_Really_Updated_Hook_Util {

		/**
		 * @var array Storage for selected post meta values for before and after update comparison.
		 */
		private static $post_meta_values_before_update = array();

		/**
		 * @var array Post meta keys we want to track changes for.
		 */
		private static $meta_keys_to_track = array();

		public static function init() {

			add_action( 'update_postmeta', array( __CLASS__, 'beforePostMetaUpdate' ), 10, 4 );
			add_action( 'updated_postmeta', array( __CLASS__, 'afterPostMetaUpdate' ), 10, 4 );

		}

		/**
		 * Fires immediately before updating a post's metadata.
		 *
		 * @param int $meta_id ID of metadata entry to update.
		 * @param int $object_id Object ID.
		 * @param string $meta_key Meta key.
		 * @param mixed $meta_value Meta value.
		 */
		public static function beforePostMetaUpdate( $meta_id, $object_id, $meta_key, $meta_value ) {

			if ( in_array( $meta_key, self::$meta_keys_to_track ) ) {

				if ( ! array_key_exists( $object_id, self::$post_meta_values_before_update ) ) {
					self::$post_meta_values_before_update[ $object_id ] = array();
				}

				$old_value = get_post_meta( $object_id, $meta_key, TRUE );
				self::$post_meta_values_before_update[ $object_id ][ $meta_key ] = $old_value;

			}

		}

		/**
		 * Fires immediately after updating a post's metadata.
		 *
		 * @param int $meta_id ID of updated metadata entry.
		 * @param int $object_id Object ID.
		 * @param string $meta_key Meta key.
		 * @param mixed $meta_value Meta value.
		 */
		public static function afterPostMetaUpdate( $meta_id, $object_id, $meta_key, $meta_value ) {

			if ( in_array( $meta_key, self::$meta_keys_to_track ) ) {

				if ( array_key_exists( $object_id, self::$post_meta_values_before_update )
				     && array_key_exists( $meta_key, self::$post_meta_values_before_update[ $object_id ] ) ) {

					$previous_value = self::$post_meta_values_before_update[ $object_id ][ $meta_key ];
					if ( $previous_value !== $meta_value ) {
						do_action( 'post_meta_really_updated', $object_id, $meta_key, $previous_value, $meta_value );
					}

				}

			}

		}

		public static function add_meta_keys_to_track( $meta_keys ) {

			self::$meta_keys_to_track = array_merge( self::$meta_keys_to_track, $meta_keys );
		}

	}

	Post_Meta_Really_Updated_Hook_Util::init();

	if ( ! function_exists( 'track_real_post_meta_changes' ) ) {

		function track_real_post_meta_changes( $meta_keys ) {
			Post_Meta_Really_Updated_Hook_Util::add_meta_keys_to_track( $meta_keys );
		}

	}

}

