<?php

// This is to solve the issue of needing a specific site editor role to be able to translate pages using WPML
// Typically super admins are the only ones that get this by default. WPML has no way of allowing this except via the "translators" tab within the settings.
// That page lets you set specific people, but that's kinda useless since there could be a lot of people needing this.
// They also suggest this is possible to do for a role via the toolset access plugin, but let's not install another paid plugin just to do this.
// Their support has many issues open about this as well, but I found this blog post as of Nov 2023 that actually does the business!
// Sometimes clients request that we ignore the "administrator" role and instead create a separate Wordpress Admin role for the website only. 
// The work to ignore the administrator role would be done in the CAS role sync plugin and this would be used to make sure they can edit translated pages. 
// All other role settings should be done using the user role editor plugin.



// Assign WPML Translators Based on User Roles.
// https://hoolite.be/wordpress/how-to-assign-wpml-translators-based-on-user-roles/
add_filter(
    'wpml_override_is_translator',
    function ($is_translator, $user_id, $args) {
        $user = get_user_by('id', $user_id);

        // Define the user roles that can act as translators
        $allowed_roles = array('Wordpress_Admin');

        // Check if the user belongs to any allowed role
        if (array_intersect($allowed_roles, (array) $user->roles)) {
            return true;
        }

        return $is_translator;
    },
    10,
    3
);