<?php
add_shortcode('nias_metabox_form', 'nias_metabox_form_shortcode');
function nias_metabox_form_shortcode()
{
    ob_start();
?>
    <style>
        #nias-metabox-form {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        #nias-metabox-form input[type="text"],
        #nias-metabox-form select {
            width: 100%;
            padding: 8px 12px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        #nias-metabox-form input[type="text"]:focus,
        #nias-metabox-form select:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 2px rgba(34, 113, 177, 0.2);
        }

        #nias-metabox-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
            color: #1d2327;
        }

        /* Tab Styling */
        .nias-tab {
            transform: scale(0);
            height: 0;
            opacity: 0;
            transition: all 0.3s ease;
            transform-origin: top;
            position: absolute;
            width: 100%;
            pointer-events: none;
        }

        .nias-tab.active {
            transform: scale(1);
            height: auto;
            opacity: 1;
            position: relative;
            pointer-events: all;
        }

        /* Tab container to handle positioning */
        .nias-tabs-container {
            position: relative;
        }

        .nias-tab-button {
            padding: 8px 16px;
            margin-right: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background: #f0f0f1;
            transition: all 0.3s ease;
        }

        .nias-tab-button:hover {
            background: #2271b1;
            color: #fff;
        }

        .nias-tab-button.active {
            background: #2271b1;
            color: #fff;
        }

        .nias-field-block,
        .nias-checkbox-field-block {
            background: #f8f9fa;
            border: 1px solid #e2e4e7;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        button[type="button"] {
            background: #2271b1;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button[type="button"]:hover {
            background: #135e96;
        }

        button[onclick*="remove"] {
            background: #dc3232;
            color: #fff;
            border: none;
            padding: 4px 4px;
            border-radius: 10px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px;
        }

        button[onclick*="remove"]:hover {
            background: #b32d2e;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        #nias-code-output {
            margin-top: 30px;
            border-radius: 6px;
            overflow: hidden;
            display: none;
        }

        #nias-code-output.active {
            display: block;
        }

        #nias-code-output pre {
            margin: 0;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #e2e4e7;
            border-radius: 6px;
        }

        #nias-metabox-form button {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            gap: 10px;
            font-family: initial;
        }

        .nias-metabox-main {
            display: inline-flex;
        }

        button.active svg path {
            stroke: white;
        }
        div#nias-metabox-form select {
    font-family: initial;
}
    </style>
    <div id="nias-metabox-form">

        <!-- تب‌بندی -->
        <div class="nias-metabox-main" style="margin-bottom: 15px;">
            <button onclick="niasShowTab('text')" class="nias-tab-button active" id="nias-tab-button-text"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22 10V15C22 20 20 22 15 22H9C4 22 2 20 2 15V9C2 4 4 2 9 2H14" stroke="#007aff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 10H18C15 10 14 9 14 6V2L22 10Z" stroke="#007aff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7 13H13" stroke="#007aff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7 17H11" stroke="#007aff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>فیلد متنی</span></button>
            <button onclick="niasShowTab('checkbox')" class="nias-tab-button" id="nias-tab-button-checkbox"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#007aff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7.75 12L10.58 14.83L16.25 9.17004" stroke="#007aff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>چک‌باکس</span></button>
        </div>

        <!-- Add tabs container -->
        <div class="nias-tabs-container">
            <!-- تب فیلد متنی -->
            <div id="nias-tab-text" class="nias-tab active">
                <label>نام فانکشن:</label>
                <input type="text" id="nias_function_name_text"><br>

                <label>نام متاباکس:</label>
                <input type="text" id="nias_metabox_title_text"><br>

                <label>آیدی متاباکس:</label>
                <input type="text" id="nias_metabox_id_text"><br>

                <label>محل نمایش:</label>
                <select id="nias_post_type_target_text" onchange="niasTogglePostTypeField(this)">
                    <option value="product">محصول ووکامرس</option>
                    <option value="post">نوشته</option>
                    <option value="custom">پست تایپ اختصاصی</option>
                </select><br>
                <div id="nias_custom_post_type_field" style="display:none;">
                    <label>آیدی پست تایپ:</label>
                    <input type="text" id="nias_custom_post_type_text"><br>
                </div>

                <hr>
                <!-- ریپیتر فیلدها -->
                <div id="nias-fields-repeater">
                    <!-- ریپیتر بلاک‌ها اینجا اضافه میشن -->
                </div>
                <button type="button" onclick="niasAddFieldBlock('text')"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 12H18" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 18V6" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    افزودن فیلد متنی</button><br><br>

                <button type="button" onclick="niasGenerateCode('text')"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 10L6 12L8 14" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 10L18 12L16 14" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M13 9.66992L11 14.33" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    تولید کد</button>
            </div>

            <!-- تب چک‌باکس -->
            <div id="nias-tab-checkbox" class="nias-tab">
                <label>نام فانکشن:</label>
                <input type="text" id="nias_function_name_checkbox"><br>

                <label>نام متاباکس:</label>
                <input type="text" id="nias_metabox_title_checkbox"><br>

                <label>آیدی متاباکس:</label>
                <input type="text" id="nias_metabox_id_checkbox"><br>

                <label>محل نمایش:</label>
                <select id="nias_post_type_target_checkbox" onchange="niasToggleCheckboxPostTypeField(this)">
                    <option value="product">محصول ووکامرس</option>
                    <option value="post">نوشته</option>
                    <option value="custom">پست تایپ اختصاصی</option>
                </select><br>
                <div id="nias_custom_post_type_field_checkbox" style="display:none;">
                    <label>آیدی پست تایپ:</label>
                    <input type="text" id="nias_custom_post_type_checkbox"><br>
                </div>

                <hr>
                <!-- ریپیتر فیلدهی چک‌باکس -->
                <div id="nias-checkbox-fields-repeater">
                    <!-- ریپیتر بلاک‌های چک‌باکس اینجا اضافه میشن -->
                </div>
                <button type="button" onclick="niasAddFieldBlock('checkbox')"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 12H18" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 18V6" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    افزودن چک‌باکس</button><br><br>

                <button type="button" onclick="niasGenerateCode('checkbox')"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 10L6 12L8 14" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 10L18 12L16 14" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M13 9.66992L11 14.33" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> تولید کد</button>
            </div>

            <!-- نمایش خروجی با PrismJS -->
            <div id="nias-code-output" class="nias-code-output" style="margin-top: 20px; position: relative;">
                <button onclick="niasCopyCode()" class="nias-copy-btn" style="position: absolute; top: 10px; right: 10px; background: #2271b1; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 12.9V17.1C16 20.6 14.6 22 11.1 22H6.9C3.4 22 2 20.6 2 17.1V12.9C2 9.4 3.4 8 6.9 8H11.1C14.6 8 16 9.4 16 12.9Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M22 6.9V11.1C22 14.6 20.6 16 17.1 16H16V12.9C16 9.4 14.6 8 11.1 8H8V6.9C8 3.4 9.4 2 12.9 2H17.1C20.6 2 22 3.4 22 6.9Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    کپی کد
                </button>
                <pre><code class="language-php" id="nias-generated-code"></code></pre>
            </div>
        </div>

        <!-- بارگذاری PrismJS -->
        <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js"></script>

        <!-- اسکریپت‌های اینلاین -->
        <script>
            // Add copy functionality
            function niasCopyCode() {
                const codeElement = document.getElementById('nias-generated-code');
                const copyBtn = document.querySelector('.nias-copy-btn');

                // Create a temporary textarea to copy the text
                const textarea = document.createElement('textarea');
                textarea.value = codeElement.textContent;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                // Show feedback
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.75 12L10.58 14.83L16.25 9.17004" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    کپی شد!`;

                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                }, 2000);
            }

            // سوییچ تب‌ها
            function niasShowTab(tab) {
                // مخفی کردن همه تب‌ها و حذف کلاس active
                document.querySelectorAll('.nias-tab').forEach(el => el.classList.remove('active'));
                document.querySelectorAll('.nias-tab-button').forEach(btn => btn.classList.remove('active'));

                // فعال کردن تب انتخاب شده
                document.getElementById('nias-tab-' + tab).classList.add('active');
                document.getElementById('nias-tab-button-' + tab).classList.add('active');
            }

            // نمایش فیلد پست تایپ اختصاصی برای تب متن
            function niasTogglePostTypeField(select) {
                document.getElementById('nias_custom_post_type_field').style.display = (select.value === 'custom') ? 'block' : 'none';
            }

            // نمایش فیلد پست تایپ اختصاصی برای تب چک‌باکس
            function niasToggleCheckboxPostTypeField(select) {
                document.getElementById('nias_custom_post_type_field_checkbox').style.display = (select.value === 'custom') ? 'block' : 'none';
            }

            // شمارنده برای بلاک‌ها
            let niasFieldIndex = 0;
            let niasCheckboxFieldIndex = 0;

            // افزودن بلاک جدید از فیلدها
            function niasAddFieldBlock(type) {
                const wrapper = type === 'checkbox' ?
                    document.getElementById('nias-checkbox-fields-repeater') :
                    document.getElementById('nias-fields-repeater');

                const index = type === 'checkbox' ? niasCheckboxFieldIndex++ : niasFieldIndex++;

                const block = document.createElement('div');
                block.className = type === 'checkbox' ? 'nias-checkbox-field-block' : 'nias-field-block';
                block.style = "border:1px solid #ccc; padding:10px; margin-bottom:10px; position:relative;";

                if (type === 'checkbox') {
                    block.innerHTML = `
                    <button onclick="this.parentElement.remove()" style="position:absolute;top:5px;left:5px;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.16998 14.83L14.83 9.17004" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14.83 14.83L9.16998 9.17004" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
</button>
                    <label>آیدی چک‌باکس:</label><br>
                    <input type="text" class="nias_checkbox_id"><br><br>
                    <label>متن چک‌باکس:</label><br>
                    <input type="text" class="nias_checkbox_label"><br><br>
                    <label>مقدار پیش‌فرض:</label><br>
                    <select class="nias_checkbox_default">
                        <option value="off">غیرفعال</option>
                        <option value="on">فعال</option>
                    </select><br><br>
                `;
                } else {
                    block.innerHTML = `
                    <button onclick="this.parentElement.remove()" style="position:absolute;top:5px;left:5px;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.16998 14.83L14.83 9.17004" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M14.83 14.83L9.16998 9.17004" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
</button>
                    <label>آیدی فیلد:</label><br>
                    <input type="text" class="nias_field_id"><br><br>
                    <label>نام لیبل:</label><br>
                    <input type="text" class="nias_field_label"><br><br>
                `;
                }

                wrapper.appendChild(block);
            }

            // ساخت کد PHP و نمایش با PrismJS
            function niasGenerateCode(type) {

                let functionName, metaboxTitle, metaboxId, postTypeTarget, customPostType, blocks;

                if (type === 'checkbox') {
                    functionName = document.getElementById('nias_function_name_checkbox').value.trim();
                    metaboxTitle = document.getElementById('nias_metabox_title_checkbox').value.trim();
                    metaboxId = document.getElementById('nias_metabox_id_checkbox').value.trim();
                    postTypeTarget = document.getElementById('nias_post_type_target_checkbox').value;
                    customPostType = document.getElementById('nias_custom_post_type_checkbox').value.trim();
                    blocks = document.querySelectorAll('.nias-checkbox-field-block');
                } else {
                    functionName = document.getElementById('nias_function_name_text').value.trim();
                    metaboxTitle = document.getElementById('nias_metabox_title_text').value.trim();
                    metaboxId = document.getElementById('nias_metabox_id_text').value.trim();
                    postTypeTarget = document.getElementById('nias_post_type_target_text').value;
                    customPostType = document.getElementById('nias_custom_post_type_text').value.trim();
                    blocks = document.querySelectorAll('.nias-field-block');
                }

                if (!functionName || !metaboxTitle || !metaboxId) {
                    alert("لطفاً تمام فیلدهای اصلی را پر کنید.");
                    return;
                }

                let postType = (postTypeTarget === 'custom') ? customPostType : postTypeTarget;

                if (blocks.length === 0) {
                    alert("حداقل یک فیلد اضافه کنید.");
                    return;
                }

                // ساخت یک متاباکس واحد با چندین فیلد
                let fieldsCode = `    add_meta_box('${metaboxId}', '${metaboxTitle}', function($post){\n`;

                if (type === 'checkbox') {
                    // ساخت کد برای چک‌باکس‌ها
                    fieldsCode += `        echo '<div class="nias-metabox-wrapper">';\n`;
                    blocks.forEach((block, i) => {
                        const fieldId = block.querySelector('.nias_checkbox_id').value.trim();
                        const label = block.querySelector('.nias_checkbox_label').value.trim();
                        const defaultValue = block.querySelector('.nias_checkbox_default').value;

                        if (!fieldId || !label) {
                            alert(`چک‌باکس شماره ${i+1} ناقص است.`);
                            return;
                        }

                        fieldsCode += `        $${fieldId}_value = get_post_meta($post->ID, '${fieldId}', true);
`;
                        fieldsCode += `        if($${fieldId}_value === '') $${fieldId}_value = '${defaultValue}';
`;
                        fieldsCode += `        echo '<div style="margin-bottom: 15px;">';
`;
                        fieldsCode += `        echo '<label style="display:block;margin-bottom:5px;">';
`;
                        fieldsCode += `        echo '<input type="checkbox" name="${fieldId}" value="on" ' . (($${fieldId}_value === 'on') ? 'checked="checked"' : '') . ' /> ${label} <small style="color:#666;font-size:0.8em;">(ID: ${fieldId})</small>';
`;
                        fieldsCode += `        echo '</label>';
`;
                        fieldsCode += `        echo '</div>';
`;
                    });
                    fieldsCode += `        echo '</div>';\n`;
                } else {
                    // ساخت کد برای فیلدهای متنی
                    blocks.forEach((block, i) => {
                        const fieldId = block.querySelector('.nias_field_id').value.trim();
                        const label = block.querySelector('.nias_field_label').value.trim();

                        if (!fieldId || !label) {
                            alert(`فیلد شماره ${i+1} ناقص است.`);
                            return;
                        }

                        fieldsCode += `        echo '<div style="margin-bottom: 15px;">';\n`;
                        fieldsCode += `        echo '<label style="display:block;font-weight:bold;margin-bottom:5px;">${label} <small style="color:#666;font-weight:normal;font-size:0.8em;">(ID: ${fieldId})</small>:</label>';\n`;
                        fieldsCode += `        echo '<input type="text" name="${fieldId}" value="'.get_post_meta($post->ID, '${fieldId}', true).'" style="width:100%;" />';\n`;
                        fieldsCode += `        echo '</div>';\n`;
                    });
                }
                fieldsCode += `    }, '${postType}', 'normal', 'default');\n`;

                // اضافه کردن فانکشن ذخیره متادیتا
                let saveCode = `
add_action('save_post', '${functionName}_save_meta');
function ${functionName}_save_meta($post_id) {
    // اگر در حال ذخیره خودکار هستیم، کاری انجام نده
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // بررسی مجوزها
    if (isset($_POST['post_type']) && '${postType}' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) return;
    }
`;

                if (type === 'checkbox') {
                    blocks.forEach((block) => {
                        const fieldId = block.querySelector('.nias_checkbox_id').value.trim();
                        if (fieldId) {
                            saveCode += `
    // ذخیره ${fieldId}
    update_post_meta($post_id, '${fieldId}', isset($_POST['${fieldId}']) ? 1 : 0);`;
                        }
                    });
                } else {
                    blocks.forEach((block) => {
                        const fieldId = block.querySelector('.nias_field_id').value.trim();
                        if (fieldId) {
                            saveCode += `
    // ذخیره ${fieldId}
    if (isset($_POST['${fieldId}'])) {
        update_post_meta($post_id, '${fieldId}', sanitize_text_field($_POST['${fieldId}']));
    }`;
                        }
                    });
                }

                saveCode += `
}`;

                const finalCode = `add_action('add_meta_boxes', '${functionName}');\nfunction ${functionName}() {\n${fieldsCode}}\n${saveCode}`;

                document.getElementById('nias-generated-code').textContent = finalCode;
                document.querySelector('.nias-code-output').classList.add('active');
                Prism.highlightAll();
            }
        </script>
    <?php
    return ob_get_clean();
}
