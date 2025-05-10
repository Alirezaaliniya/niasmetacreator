<?php
add_shortcode('nias_metabox_form', 'nias_metabox_form_shortcode');
function nias_metabox_form_shortcode() {
    ob_start();
    ?>
    <div id="nias-metabox-form">

        <!-- تب‌بندی -->
        <div style="margin-bottom: 15px;">
            <button onclick="niasShowTab('text')" class="nias-tab-button" id="nias-tab-button-text" style="background-color: #f0f0f0;">📝 فیلد متنی</button>
            <button onclick="niasShowTab('checkbox')" class="nias-tab-button" id="nias-tab-button-checkbox">✅ چک‌باکس</button>
        </div>

        <!-- تب فیلد متنی -->
        <div id="nias-tab-text" class="nias-tab" style="display: block;">
            <label>نام فانکشن:</label><br>
            <input type="text" id="nias_function_name_text"><br><br>

            <label>نام متاباکس:</label><br>
            <input type="text" id="nias_metabox_title_text"><br><br>

            <label>آیدی متاباکس:</label><br>
            <input type="text" id="nias_metabox_id_text"><br><br>

            <label>محل نمایش:</label><br>
            <select id="nias_post_type_target_text" onchange="niasTogglePostTypeField(this)">
                <option value="product">محصول ووکامرس</option>
                <option value="post">نوشته</option>
                <option value="custom">پست تایپ اختصاصی</option>
            </select><br><br>
            <div id="nias_custom_post_type_field" style="display:none;">
                <label>آیدی پست تایپ:</label><br>
                <input type="text" id="nias_custom_post_type_text"><br><br>
            </div>

            <hr>
            <!-- ریپیتر فیلدها -->
            <div id="nias-fields-repeater">
                <!-- ریپیتر بلاک‌ها اینجا اضافه میشن -->
            </div>
            <button type="button" onclick="niasAddFieldBlock('text')">➕ افزودن فیلد متنی</button><br><br>

            <button type="button" onclick="niasGenerateCode('text')">🎯 تولید کد</button>
        </div>
        
        <!-- تب چک‌باکس -->
        <div id="nias-tab-checkbox" class="nias-tab" style="display: none;">
            <label>نام فانکشن:</label><br>
            <input type="text" id="nias_function_name_checkbox"><br><br>

            <label>نام متاباکس:</label><br>
            <input type="text" id="nias_metabox_title_checkbox"><br><br>

            <label>آیدی متاباکس:</label><br>
            <input type="text" id="nias_metabox_id_checkbox"><br><br>

            <label>محل نمایش:</label><br>
            <select id="nias_post_type_target_checkbox" onchange="niasToggleCheckboxPostTypeField(this)">
                <option value="product">محصول ووکامرس</option>
                <option value="post">نوشته</option>
                <option value="custom">پست تایپ اختصاصی</option>
            </select><br><br>
            <div id="nias_custom_post_type_field_checkbox" style="display:none;">
                <label>آیدی پست تایپ:</label><br>
                <input type="text" id="nias_custom_post_type_checkbox"><br><br>
            </div>

            <hr>
            <!-- ریپیتر فیلدهای چک‌باکس -->
            <div id="nias-checkbox-fields-repeater">
                <!-- ریپیتر بلاک‌های چک‌باکس اینجا اضافه میشن -->
            </div>
            <button type="button" onclick="niasAddFieldBlock('checkbox')">➕ افزودن چک‌باکس</button><br><br>

            <button type="button" onclick="niasGenerateCode('checkbox')">🎯 تولید کد</button>
        </div>

        <!-- نمایش خروجی با PrismJS -->
        <div id="nias-code-output" style="margin-top: 20px;">
            <pre><code class="language-php" id="nias-generated-code"></code></pre>
        </div>
    </div>

    <!-- بارگذاری PrismJS -->
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js"></script>

    <!-- اسکریپت‌های اینلاین -->
    <script>
    // سوییچ تب‌ها
    function niasShowTab(tab) {
        // مخفی کردن همه تب‌ها
        document.querySelectorAll('.nias-tab').forEach(el => el.style.display = 'none');
        
        // فعال کردن تب انتخاب شده
        document.getElementById('nias-tab-' + tab).style.display = 'block';
        
        // آپدیت استایل دکمه‌های تب
        document.querySelectorAll('.nias-tab-button').forEach(btn => btn.style.backgroundColor = '');
        document.getElementById('nias-tab-button-' + tab).style.backgroundColor = '#f0f0f0';
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
        const wrapper = type === 'checkbox' 
            ? document.getElementById('nias-checkbox-fields-repeater')
            : document.getElementById('nias-fields-repeater');
            
        const index = type === 'checkbox' ? niasCheckboxFieldIndex++ : niasFieldIndex++;

        const block = document.createElement('div');
        block.className = type === 'checkbox' ? 'nias-checkbox-field-block' : 'nias-field-block';
        block.style = "border:1px solid #ccc; padding:10px; margin-bottom:10px; position:relative;";
        
        if (type === 'checkbox') {
            block.innerHTML = `
                <button onclick="this.parentElement.remove()" style="position:absolute;top:5px;right:5px;">❌</button>
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
                <button onclick="this.parentElement.remove()" style="position:absolute;top:5px;right:5px;">❌</button>
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

                fieldsCode += `        $${fieldId}_value = get_post_meta($post->ID, '${fieldId}', true);\n`;
                fieldsCode += `        if($${fieldId}_value === '') $${fieldId}_value = '${defaultValue}';\n`;
                fieldsCode += `        echo '<div style="margin-bottom: 15px;">';\n`;
                fieldsCode += `        echo '<label style="display:block;margin-bottom:5px;">';\n`;
                fieldsCode += `        echo '<input type="checkbox" name="${fieldId}" value="on" ' . checked($${fieldId}_value, 'on', false) . ' /> ${label} <small style="color:#666;font-size:0.8em;">(ID: ${fieldId})</small>';\n`;
                fieldsCode += `        echo '</label>';\n`;
                fieldsCode += `        echo '</div>';\n`;
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
        Prism.highlightAll();
    }
    </script>
    <?php
    return ob_get_clean();
}