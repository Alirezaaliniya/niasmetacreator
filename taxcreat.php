<?php

add_shortcode('nias_taxonomy_form', 'nias_taxonomy_form_shortcode');

function nias_taxonomy_form_shortcode() {
    ob_start(); ?>
    <style>
        #nias-taxonomy-form {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        #nias-taxonomy-form label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
            color: #333;
        }

        #nias-taxonomy-form input[type="text"],
        #nias-taxonomy-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        #nias-taxonomy-form input[type="checkbox"] {
            margin-right: 8px;
        }

        #nias-taxonomy-form button {
            background: #0073aa;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }

        #nias-taxonomy-form button:hover {
            background: #005177;
        }

        #nias-taxonomy-code {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-family: monospace;
            margin-top: 20px;
            white-space: pre-wrap;
        }
    </style>

    <form id="nias-taxonomy-form">
        <label>نام نمایشی تاکسونومی</label>
        <input type="text" name="tax_label">

        <label>شناسه تاکسونومی</label>
        <input type="text" name="tax_name">

        <label>نوع پست‌های مرتبط</label>
        <select name="object_type">
            <option value="post">نوشته</option>
            <option value="page">برگه</option>
            <option value="product">محصول</option>
        </select>

        <label>سلسله مراتبی؟</label>
        <select name="hierarchical">
            <option value="1">بله</option>
            <option value="0">خیر</option>
        </select>

        <label>نمایش در ستون مدیریت؟</label>
        <select name="show_admin_column">
            <option value="1">بله</option>
            <option value="0">خیر</option>
        </select>

        <label>قابلیت جستجو؟</label>
        <select name="query_var">
            <option value="1">بله</option>
            <option value="0">خیر</option>
        </select>

        <label>rewrite slug</label>
        <input type="text" name="rewrite_slug">

        <br><button type="submit">ساخت کد</button>
    </form>

    <div id="nias-taxonomy-code"></div>

    <script>
    document.querySelector('#nias-taxonomy-form').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = e.target;
        var data = new FormData(form);
        data.append('action', 'nias_generate_taxonomy_code');

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: data
        }).then(res => res.text()).then(code => {
            document.querySelector('#nias-taxonomy-code').textContent = code;
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

add_action('wp_ajax_nias_generate_taxonomy_code', 'nias_generate_taxonomy_code');
add_action('wp_ajax_nopriv_nias_generate_taxonomy_code', 'nias_generate_taxonomy_code');

function nias_generate_taxonomy_code() {
    $tax_label = sanitize_text_field($_POST['tax_label']);
    $tax_name = sanitize_title($_POST['tax_name']);
    $object_type = sanitize_text_field($_POST['object_type']);
    $hierarchical = $_POST['hierarchical'] ? 'true' : 'false';
    $show_admin_column = $_POST['show_admin_column'] ? 'true' : 'false';
    $query_var = $_POST['query_var'] ? 'true' : 'false';
    $rewrite_slug = sanitize_title($_POST['rewrite_slug']);

    $code = "add_action('init', function() {\n";
    $code .= "    register_taxonomy('$tax_name', '$object_type', [\n";
    $code .= "        'label' => '$tax_label',\n";
    $code .= "        'hierarchical' => $hierarchical,\n";
    $code .= "        'show_admin_column' => $show_admin_column,\n";
    $code .= "        'query_var' => $query_var,\n";
    if ($rewrite_slug) {
        $code .= "        'rewrite' => ['slug' => '$rewrite_slug'],\n";
    }
    $code .= "    ]);\n";
    $code .= "});";

    echo $code;
    wp_die();
}