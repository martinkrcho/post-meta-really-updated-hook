# post-meta-really-updated-hook

WordPress core is missing one very simple feature - a hook that fires when a post meta value has (really) been updated. This is most likely due to performance reasons. The update_post_meta function would always have to make an extra database call to retrieve the current meta value (unless the previous meta value is passed to the function call as parameter).

The purpose of this plugin is to help with this. It allows developers to implement action that fires when post meta value actually changes.

```do_action( 'post_meta_really_updated', $object_id, $meta_key, $previous_value, $meta_value );```

You need to "tell" the plugin which meta keys you want to track. It does not spot any changes out of the box since it needs to make an additional ```get_post_meta``` call in order to do the trick.

Use function ```track_real_post_meta_changes``` to register your meta keys. It accepts an array of strings (meta keys).

## Feedback welcome
The code is very flat and raw. I am certain better agrument validation, error handlig etc. is needed, but I want to get the ball rolling and hopefully others will find this useful. Feel free to get in touch and make suggestions.

I will try to add more documentation and publish this on WordPress.org soon(ish) :)
