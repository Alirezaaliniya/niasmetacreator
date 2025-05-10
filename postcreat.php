<?php






add_shortcode('nias_custom_posttype_form', 'nias_custom_posttype_form_shortcode');
function nias_custom_posttype_form_shortcode() {
    ob_start(); ?>
    <form id="nias-metabox-form">
        <label>نام نمایشی (Label)</label>
        <input type="text" name="label">

        <label>آیدی پست تایپ (Slug)</label>
        <input type="text" name="slug">

        <label>آیکون منو</label>
        <input type="text" name="menu_icon" id="menu_icon_input">
        <p class="description">
            برای مشاهده لیست آیکن‌های موجود 
            <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">اینجا کلیک کنید</a>
        </p>
        <p class="description">
            مثال: dashicons-admin-post
        </p>

        <label>public؟</label>
        <select name="public">
            <option value="1">بله</option>
            <option value="0">خیر</option>
        </select>

        <label>has_archive؟</label>
        <select name="has_archive">
            <option value="1">بله</option>
            <option value="0">خیر</option>
        </select>

        <label>rewrite slug</label>
        <input type="text" name="rewrite_slug">

        <label>قابلیت‌ها (Supports)</label><br>
        <?php
        $supports = ['title','editor','thumbnail','excerpt','custom-fields','author','comments'];
        foreach ($supports as $s) {
            echo "<label><input type='checkbox' name='supports[]' value='$s'> $s</label><br>";
        }
        ?>

        <label><input type="checkbox" name="taxonomy_builtin[]" value="category">دسته‌بندی وردپرس</label><br>
        <label><input type="checkbox" name="taxonomy_builtin[]" value="post_tag">برچسب وردپرس</label><br>

        <label>نام tax سفارشی</label>
        <input type="text" name="custom_tax_name[]">

        <label>اسلاگ tax سفارشی</label>
        <input type="text" name="custom_tax_slug[]">

        <label>نقش‌های مجاز</label><br>
        <?php
        global $wp_roles;
        foreach ($wp_roles->roles as $role => $r) {
            echo "<label><input type='checkbox' name='roles[]' value='$role'> $role</label><br>";
        }
        ?>

        <br><button type="submit">ساخت کد</button>
    </form>

    <div id="nias-posttype-code" style="white-space:pre-wrap;margin-top:20px;"></div>

    <script>
    document.querySelector('#nias-metabox-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = e.target;
        var data = new FormData(form);
        data.append('action', 'nias_generate_posttype_code');

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: data
        }).then(res => res.text()).then(code => {
            document.querySelector('#nias-posttype-code').textContent = code;
        });
    });
    </script>
    <?php
    return ob_get_clean();
}


add_action('wp_ajax_nias_generate_posttype_code', 'nias_generate_posttype_code');
add_action('wp_ajax_nopriv_nias_generate_posttype_code', 'nias_generate_posttype_code');

function nias_generate_posttype_code() {
$label = sanitize_text_field($_POST['label']);
$slug = sanitize_title($_POST['slug']);
$menu_icon = sanitize_text_field($_POST['menu_icon']);
$public = $_POST['public'] ? 'true' : 'false';
$has_archive = $_POST['has_archive'] ? 'true' : 'false';
$rewrite_slug = sanitize_title($_POST['rewrite_slug']);
$supports = isset($_POST['supports']) ? array_map('sanitize_text_field', $_POST['supports']) : [];
$tax_builtin = isset($_POST['taxonomy_builtin']) ? array_map('sanitize_text_field', $_POST['taxonomy_builtin']) : [];

$custom_taxonomies = [];
if (!empty($_POST['custom_tax_name']) && is_array($_POST['custom_tax_name'])) {
    foreach ($_POST['custom_tax_name'] as $i => $name) {
        $name = sanitize_text_field($name);
        $slug_custom = sanitize_title($_POST['custom_tax_slug'][$i] ?? '');
        if ($name && $slug_custom) {
            $custom_taxonomies[] = $slug_custom;  // Remove quotes here
        }
    }
}

// Add quotes to built-in taxonomies when merging
$taxonomies = array_merge(
    $tax_builtin,
    $custom_taxonomies
);
$roles = isset($_POST['roles']) ? array_map('sanitize_text_field', $_POST['roles']) : [];

// ساختن کد نهایی
$code = "add_action('init', function() {\n";

// اضافه کردن کد ثبت تاکسونومی سفارشی
if (!empty($_POST['custom_tax_name']) && is_array($_POST['custom_tax_name'])) {
    foreach ($_POST['custom_tax_name'] as $i => $name) {
        $name = sanitize_text_field($name);
        $slug_custom = sanitize_title($_POST['custom_tax_slug'][$i] ?? '');
        if ($name && $slug_custom) {
            $code .= "    register_taxonomy('$slug_custom', '$slug', [\n";
            $code .= "        'label' => '$name',\n";
            $code .= "        'hierarchical' => true,\n";
            $code .= "        'show_admin_column' => true,\n";
            $code .= "        'query_var' => true,\n";
            $code .= "    ]);\n\n";
        }
    }
}

$code .= "    register_post_type('$slug', [\n";
$code .= "        'label' => '$label',\n";
$code .= "        'public' => $public,\n";
$code .= "        'has_archive' => $has_archive,\n";
$code .= "        'rewrite' => ['slug' => '$rewrite_slug'],\n";
$code .= "        'menu_icon' => '$menu_icon',\n";
if (!empty($supports)) {
    $code .= "        'supports' => ['" . implode("','", $supports) . "'],\n";
}
if (!empty($taxonomies)) {
    $code .= "        'taxonomies' => ['" . implode("', '", $taxonomies) . "'],\n";
}
$code .= "    ]);\n";

if (!empty($roles)) {
    foreach ($roles as $role) {
        $code .= "    \$role = get_role('$role');\n";
        $code .= "    if (\$role) {\n";
        $code .= "        \$role->add_cap('edit_$slug');\n";
        $code .= "        \$role->add_cap('edit_{$slug}s');\n";
        $code .= "        \$role->add_cap('edit_others_{$slug}s');\n";
        $code .= "        \$role->add_cap('publish_{$slug}s');\n";
        $code .= "        \$role->add_cap('delete_{$slug}s');\n";
        $code .= "    }\n";
    }
}

$code .= "});";

echo $code;
wp_die();
}
