<?php






add_shortcode('nias_custom_posttype_form', 'nias_custom_posttype_form_shortcode');
function nias_custom_posttype_form_shortcode() {
    ob_start(); ?>
    <style>
        #nias-metabox-form {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        #nias-metabox-form label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
            color: #333;
        }

        #nias-metabox-form input[type="text"],
        #nias-metabox-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        #nias-metabox-form input[type="checkbox"] {
            margin-right: 8px;
        }

        #nias-metabox-form button {
            background: #0073aa;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }

        #nias-metabox-form button:hover {
            background: #005177;
        }

        #nias-metabox-form .description {
            color: #666;
            font-size: 0.9em;
            margin: 5px 0 15px;
        }

        #nias-metabox-form a {
            color: #0073aa;
            text-decoration: none;
        }

        #nias-metabox-form a:hover {
            text-decoration: underline;
        }

        #nias-posttype-code {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-family: monospace;
            margin-top: 20px;
        }
    </style>
    <form id="nias-metabox-form">
        <label>نام نمایشی</label>
        <input type="text" name="label">

        <label>شناسه پست تایپ</label>
        <input type="text" name="slug">

        <label>آیکون منو</label>
        <textarea name="menu_icon" id="menu_icon_input" rows="5" style="width: 100%; margin-bottom: 10px;"></textarea>
        
        <p class="description">
            برای استفاده از SVG به عنوان آیکون منو، کد زیر را کپی و در فیلد بالا قرار دهید:
        </p>
        <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 12px; direction: ltr; margin: 10px 0;">data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0iY3VycmVudENvbG9yIj48cGF0aCBkPSJNMTAgMmE4IDggMCAxMDAgMTZhOCA4IDAgMDAwLTE2em0wIDJhNiA2IDAgMTEwIDEyYTYgNiAwIDAxMC0xMnoiLz48L3N2Zz4=</pre>
        
        <p class="description">
            این مثال یک آیکون دایره‌ای ساده را نمایش می‌دهد. برای ساخت آیکون SVG سفارشی:
        </p>
        <ol class="description" style="margin-left: 20px;">
            <li>کد SVG خود را در یک ویرایشگر متن آماده کنید</li>
            <li>کد را به base64 تبدیل کنید (می‌توانید از ابزارهای آنلاین استفاده کنید)</li>
            <li>عبارت <code>data:image/svg+xml;base64,</code> را در ابتدای کد base64 شده قرار دهید</li>
            <li>نتیجه نهایی را در فیلد بالا قرار دهید</li>
        </ol>
        
        <p class="description">
            نمونه کد SVG اصلی (قبل از تبدیل به base64):
        </p>
        <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 12px; direction: ltr; margin: 10px 0;">
&lt;svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"&gt;
    &lt;path d="M10 2a8 8 0 100 16a8 8 0 000-16zm0 2a6 6 0 110 12a6 6 0 010-12z"/&gt;
&lt;/svg&gt;</pre>

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
        $supports = [
            'title' => 'عنوان',
            'editor' => 'ویرایشگر',
            'thumbnail' => 'تصویر شاخص',
            'excerpt' => 'خلاصه',
            'custom-fields' => 'فیلدهای سفارشی',
            'author' => 'نویسنده',
            'comments' => 'دیدگاه‌ها'
        ];
        foreach ($supports as $key => $label) {
            echo "<label><input type='checkbox' name='supports[]' value='$key'> $label</label><br>";
        }
        ?>

        <label><input type="checkbox" name="taxonomy_builtin[]" value="category">دسته‌بندی وردپرس</label><br>
        <label><input type="checkbox" name="taxonomy_builtin[]" value="post_tag">برچسب وردپرس</label><br>

        <label>نام طبقه‌بندی سفارشی</label>
        <input type="text" name="custom_tax_name[]">

        <label>شناسه طبقه‌بندی سفارشی</label>
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
