<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let fields = [];
    let fieldCounter = 0;

    const fieldTypes = {
        'text': { label: 'Short answer', icon: 'bi-input-cursor-text', preview: '<input type="text" placeholder="Short answer text" disabled>' },
        'textarea': { label: 'Paragraph', icon: 'bi-textarea-resize', preview: '<textarea placeholder="Long answer text" disabled></textarea>' },
        'select': { label: 'Dropdown', icon: 'bi-menu-button-wide', preview: '<select disabled><option>Option 1</option></select>' },
        'radio': { label: 'Multiple choice', icon: 'bi-ui-radios', preview: '<div><label><input type="radio" disabled> Option 1</label></div>' },
        'checkbox': { label: 'Checkboxes', icon: 'bi-check-square', preview: '<div><label><input type="checkbox" disabled> Option 1</label></div>' },
        'file': { label: 'File upload', icon: 'bi-upload', preview: '<input type="file" disabled>' }
    };

    // Add new field
    function addField(type = 'text') {
        fieldCounter++;
        const fieldId = 'field_' + fieldCounter;

        const field = {
            id: fieldId,
            type: type,
            label: 'Untitled Question',
            placeholder: '',
            description: '',
            isRequired: false,
            options: type === 'select' || type === 'radio' || type === 'checkbox' ? ['Option 1'] : [],
            order: fields.length
        };

        fields.push(field);
        renderField(field);
        updateEmptyState();
        updateFieldsData();
    }

    // Render field
    function renderField(field) {
        const container = document.getElementById('fieldsContainer');
        const fieldCard = document.createElement('div');
        fieldCard.className = 'form-field-card';
        fieldCard.id = field.id;
        fieldCard.dataset.fieldId = field.id;

        const fieldType = fieldTypes[field.type] || fieldTypes['text'];

        fieldCard.innerHTML = `
        <div class="field-header">
            <i class="bi bi-grip-vertical drag-handle"></i>
            <div class="field-label-wrapper">
                <div class="field-label-container">
                     <div contenteditable="true" 
                          class="field-label-input" 
                          id="label_${field.id}"
                          data-placeholder="Question"
                          onfocus="showFieldFormattingToolbar('${field.id}')"
                          onblur="hideFieldFormattingToolbar('${field.id}')"
                          oninput="updateFieldLabel('${field.id}', this.innerHTML)">${field.label}</div>

                    <select class="field-type-select" onchange="changeFieldType('${field.id}', this.value)">
                        ${Object.keys(fieldTypes).map(type =>
            `<option value="${type}" ${field.type === type ? 'selected' : ''}>${fieldTypes[type].label}</option>`
        ).join('')}
                    </select>
                </div>
                <div class="formatting-toolbar hidden" id="fieldFormattingToolbar_${field.id}" onmousedown="event.preventDefault(); return false;">
                    <button type="button" class="format-btn" onmousedown="event.preventDefault(); event.stopPropagation(); formatFieldText('${field.id}', 'bold'); return false;" onclick="event.preventDefault(); return false;" title="Bold">
                        <i class="bi bi-type-bold"></i>
                    </button>
                    <button type="button" class="format-btn" onmousedown="event.preventDefault(); event.stopPropagation(); formatFieldText('${field.id}', 'italic'); return false;" onclick="event.preventDefault(); return false;" title="Italic">
                        <i class="bi bi-type-italic"></i>
                    </button>
                    <button type="button" class="format-btn" onmousedown="event.preventDefault(); event.stopPropagation(); formatFieldText('${field.id}', 'underline'); return false;" onclick="event.preventDefault(); return false;" title="Underline">
                        <i class="bi bi-type-underline"></i>
                    </button>
                    <div class="format-divider"></div>
                    <button type="button" class="format-btn" onmousedown="event.preventDefault(); event.stopPropagation(); formatFieldText('${field.id}', 'insertLink'); return false;" onclick="event.preventDefault(); return false;" title="Insert link">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                    <button type="button" class="format-btn" onmousedown="event.preventDefault(); event.stopPropagation(); formatFieldText('${field.id}', 'strikeThrough'); return false;" onclick="event.preventDefault(); return false;" title="Strikethrough">
                        <i class="bi bi-type-strikethrough"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="field-preview" id="preview_${field.id}">
            ${getFieldPreview(field)}
        </div>
        
        ${field.type === 'select' || field.type === 'radio' || field.type === 'checkbox' ? `
            <div class="field-options" id="options_${field.id}">
                ${field.options.map((opt, idx) => `
                    <div class="field-option-item">
                        <input type="text" 
                               class="field-option-input" 
                               value="${escapeHtml(opt)}" 
                               placeholder="Option ${idx + 1}"
                               onchange="updateFieldOption('${field.id}', ${idx}, this.value)">
                        <button type="button" class="field-action-btn" onclick="removeFieldOption('${field.id}', ${idx})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `).join('')}
                <button type="button" class="field-action-btn" onclick="addFieldOption('${field.id}')">
                    <i class="bi bi-plus"></i> Add option
                </button>
            </div>
        ` : ''}
        
        <div class="field-actions">
            <button type="button" class="field-action-btn" onclick="duplicateField('${field.id}')">
                <i class="bi bi-files"></i> Duplicate
            </button>
            <button type="button" class="field-action-btn" onclick="deleteField('${field.id}')">
                <i class="bi bi-trash"></i> Delete
            </button>
            <div class="required-toggle" style="margin-left: auto;">
                <span style="font-size: 14px; color: #5f6368;">Required</span>
                <div class="toggle-switch ${field.isRequired ? 'active' : ''}" 
                     onclick="toggleRequired('${field.id}')"></div>
            </div>
        </div>
    `;

        container.appendChild(fieldCard);

        // Initialize drag and drop
        initDragAndDrop(fieldCard);
    }

    // Get field preview HTML
    function getFieldPreview(field) {
        const fieldType = fieldTypes[field.type] || fieldTypes['text'];

        if (field.type === 'select') {
            return `<select disabled>${field.options.map(opt => `<option>${opt}</option>`).join('')}</select>`;
        } else if (field.type === 'radio') {
            return field.options.map(opt => `
            <div style="margin-bottom: 8px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" disabled>
                    <span>${opt}</span>
                </label>
            </div>
        `).join('');
        } else if (field.type === 'checkbox') {
            return field.options.map(opt => `
            <div style="margin-bottom: 8px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" disabled>
                    <span>${opt}</span>
                </label>
            </div>
        `).join('');
        } else {
            return fieldType.preview.replace('placeholder', `placeholder="${field.placeholder || fieldType.label}"`);
        }
    }

    // Update field label
    function updateFieldLabel(fieldId, label) {
        const field = fields.find(f => f.id === fieldId);
        if (field) {
            // FIX: Keep the HTML content! Do NOT strip it using innerText on a tempDiv.
            field.label = label;
            updateFieldsData();
        }
    }

    // Change field type
    function changeFieldType(fieldId, newType) {
        const field = fields.find(f => f.id === fieldId);
        if (field) {
            field.type = newType;
            if (newType === 'select' || newType === 'radio' || newType === 'checkbox') {
                if (!field.options || field.options.length === 0) {
                    field.options = ['Option 1'];
                }
            } else {
                field.options = [];
            }
            renderFieldCard(field);
            updateFieldsData();
        }
    }

    // Render field card (update existing)
    function renderFieldCard(field) {
        const fieldCard = document.getElementById(field.id);
        if (fieldCard) {
            const fieldType = fieldTypes[field.type] || fieldTypes['text'];

            // Update preview
            const preview = fieldCard.querySelector(`#preview_${field.id}`);
            if (preview) {
                preview.innerHTML = getFieldPreview(field);
            }

            // Update options section
            const optionsContainer = fieldCard.querySelector(`#options_${field.id}`);
            if (field.type === 'select' || field.type === 'radio' || field.type === 'checkbox') {
                if (!optionsContainer) {
                    const fieldActions = fieldCard.querySelector('.field-actions');
                    const optionsDiv = document.createElement('div');
                    optionsDiv.className = 'field-options';
                    optionsDiv.id = `options_${field.id}`;
                    optionsDiv.innerHTML = `
                    ${field.options.map((opt, idx) => `
                        <div class="field-option-item">
                            <input type="text" 
                                   class="field-option-input" 
                                   value="${escapeHtml(opt)}" 
                                   placeholder="Option ${idx + 1}"
                                   onchange="updateFieldOption('${field.id}', ${idx}, this.value)">
                            <button type="button" class="field-action-btn" onclick="removeFieldOption('${field.id}', ${idx})">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    `).join('')}
                    <button type="button" class="field-action-btn" onclick="addFieldOption('${field.id}')">
                        <i class="bi bi-plus"></i> Add option
                    </button>
                `;
                    fieldActions.parentNode.insertBefore(optionsDiv, fieldActions);
                } else {
                    optionsContainer.innerHTML = `
                    ${field.options.map((opt, idx) => `
                        <div class="field-option-item">
                            <input type="text" 
                                   class="field-option-input" 
                                   value="${escapeHtml(opt)}" 
                                   placeholder="Option ${idx + 1}"
                                   onchange="updateFieldOption('${field.id}', ${idx}, this.value)">
                            <button type="button" class="field-action-btn" onclick="removeFieldOption('${field.id}', ${idx})">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    `).join('')}
                    <button type="button" class="field-action-btn" onclick="addFieldOption('${field.id}')">
                        <i class="bi bi-plus"></i> Add option
                    </button>
                `;
                }
            } else {
                if (optionsContainer) {
                    optionsContainer.remove();
                }
            }

            // Update type select
            const typeSelect = fieldCard.querySelector('.field-type-select');
            if (typeSelect) {
                typeSelect.value = field.type;
            }
        }
    }

    // Add field option
    function addFieldOption(fieldId) {
        const field = fields.find(f => f.id === fieldId);
        if (field && (field.type === 'select' || field.type === 'radio' || field.type === 'checkbox')) {
            field.options.push(`Option ${field.options.length + 1}`);
            renderFieldCard(field);
            updateFieldsData();
        }
    }

    // Update field option
    function updateFieldOption(fieldId, index, value) {
        const field = fields.find(f => f.id === fieldId);
        if (field && field.options[index] !== undefined) {
            field.options[index] = value;
            updateFieldsData();
        }
    }

    // Remove field option
    function removeFieldOption(fieldId, index) {
        const field = fields.find(f => f.id === fieldId);
        if (field && field.options.length > 1) {
            field.options.splice(index, 1);
            renderFieldCard(field);
            updateFieldsData();
        }
    }

    // Toggle required
    function toggleRequired(fieldId) {
        const field = fields.find(f => f.id === fieldId);
        if (field) {
            field.isRequired = !field.isRequired;
            const toggle = document.querySelector(`#${field.id} .toggle-switch`);
            if (toggle) {
                toggle.classList.toggle('active');
            }
            updateFieldsData();
        }
    }

    // Duplicate field
    function duplicateField(fieldId) {
        const field = fields.find(f => f.id === fieldId);
        if (field) {
            fieldCounter++;
            const newField = {
                ...field,
                id: 'field_' + fieldCounter,
                label: field.label + ' (Copy)', // label contains HTML, which is fine
                order: fields.length
            };
            fields.push(newField);
            renderField(newField);
            updateFieldsData();
        }
    }

    // Delete field
    function deleteField(fieldId) {
        if (confirm('Are you sure you want to delete this field?')) {
            fields = fields.filter(f => f.id !== fieldId);
            const fieldCard = document.getElementById(fieldId);
            if (fieldCard) {
                fieldCard.remove();
            }
            updateEmptyState();
            updateFieldsData();
        }
    }

    // Update fields data (for form submission)
    function updateFieldsData() {
        const fieldsDataInput = document.getElementById('fieldsData');
        fieldsDataInput.value = JSON.stringify(fields);
    }

    // Update empty state
    function updateEmptyState() {
        const emptyState = document.getElementById('emptyState');
        const container = document.getElementById('fieldsContainer');
        if (fields.length === 0) {
            emptyState.style.display = 'block';
            container.style.display = 'none';
        } else {
            emptyState.style.display = 'none';
            container.style.display = 'block';
        }
    }

    // Initialize drag and drop
    function initDragAndDrop(element) {
        element.draggable = true;

        element.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', element.id);
            element.style.opacity = '0.5';
        });

        element.addEventListener('dragend', (e) => {
            element.style.opacity = '1';
        });

        element.addEventListener('dragover', (e) => {
            e.preventDefault();
            const afterElement = getDragAfterElement(element.parentElement, e.clientY);
            if (afterElement == null) {
                element.parentElement.appendChild(element);
            } else {
                element.parentElement.insertBefore(element, afterElement);
            }
        });
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.form-field-card:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Formatting functions
    let toolbarMouseOver = false;

    function showFormattingToolbar(type) {
        const toolbar = document.getElementById(type + 'FormattingToolbar');
        if (toolbar) {
            toolbar.classList.remove('hidden');

            // Tambahkan event listener untuk mencegah hide saat mouse di toolbar
            toolbar.addEventListener('mouseenter', function () {
                toolbarMouseOver = true;
            });

            toolbar.addEventListener('mouseleave', function () {
                toolbarMouseOver = false;
            });
        }
    }

    function hideFormattingToolbar(type) {
        const toolbar = document.getElementById(type + 'FormattingToolbar');
        if (toolbar) {
            setTimeout(() => {
                const elementId = type === 'title' ? 'formTitle' : 'formDescription';
                const element = document.getElementById(elementId);

                // Jangan hide jika:
                // 1. Element masih dalam focus
                // 2. Mouse masih di atas toolbar
                // 3. Toolbar masih dalam focus
                if (element &&
                    !element.matches(':focus') &&
                    document.activeElement !== element &&
                    !toolbarMouseOver &&
                    document.activeElement !== toolbar &&
                    !toolbar.contains(document.activeElement)) {
                    toolbar.classList.add('hidden');
                }
            }, 300); // Increase delay untuk memberi waktu user klik tombol
        }
    }

    // Handle title focus and blur for placeholder
    function handleTitleFocus() {
        const titleElement = document.getElementById('formTitle');
        if (titleElement && titleElement.textContent.trim() === 'Untitled form') {
            titleElement.textContent = '';
            titleElement.style.color = '#202124';
        }
        showFormattingToolbar('title');
    }

    function handleTitleBlur() {
        const titleElement = document.getElementById('formTitle');
        if (titleElement && !titleElement.textContent.trim()) {
            titleElement.textContent = 'Untitled form';
            titleElement.style.color = '#80868b';
        }
        updateFormTitle();
        hideFormattingToolbar('title');
    }

    // Handle description focus and blur for placeholder
    function handleDescriptionFocus() {
        const descElement = document.getElementById('formDescription');
        if (descElement && descElement.textContent.trim() === 'Form description') {
            descElement.textContent = '';
            descElement.style.color = '#5f6368';
        }
        showFormattingToolbar('description');
    }

    function handleDescriptionBlur() {
        const descElement = document.getElementById('formDescription');
        if (descElement && !descElement.textContent.trim()) {
            descElement.textContent = 'Form description';
            descElement.style.color = '#80868b';
        }
        updateFormDescription();
        hideFormattingToolbar('description');
    }

    function formatText(elementId, command, value = null) {
        const element = document.getElementById(elementId);
        if (!element) return;

        // Handle insertLink khusus
        if (command === 'insertLink') {
            insertLinkToElement(elementId);
            return;
        }

        // Simpan selection SEBELUM kehilangan focus
        const selection = window.getSelection();
        let savedRange = null;

        if (selection.rangeCount > 0) {
            savedRange = selection.getRangeAt(0).cloneRange();
        }

        // Pastikan element dalam focus
        element.focus();

        // Restore selection jika ada
        if (savedRange) {
            try {
                selection.removeAllRanges();
                selection.addRange(savedRange);
            } catch (e) {
                console.warn('Could not restore selection:', e);
            }
        }

        // Jika tidak ada selection atau collapsed, buat selection di seluruh text
        if (!savedRange || savedRange.collapsed) {
            // Cari text node pertama
            let textNode = element;
            for (let node of element.childNodes) {
                if (node.nodeType === Node.TEXT_NODE || node.nodeType === Node.ELEMENT_NODE) {
                    textNode = node;
                    break;
                }
            }

            const textLength = textNode.textContent ? textNode.textContent.length : 0;
            const range = document.createRange();

            if (textNode.nodeType === Node.TEXT_NODE) {
                range.setStart(textNode, 0);
                range.setEnd(textNode, textLength);
            } else {
                range.selectNodeContents(textNode);
            }

            selection.removeAllRanges();
            selection.addRange(range);
        }

        try {
            // Jalankan command
            const success = document.execCommand(command, false, value);

            if (!success) {
                console.warn('Command failed:', command);
            }

            // Update hidden input
            if (elementId === 'formTitle') {
                updateFormTitle();
            } else if (elementId === 'formDescription') {
                updateFormDescription();
            }

            // Pastikan toolbar tetap visible
            const type = elementId === 'formTitle' ? 'title' : 'description';
            showFormattingToolbar(type);

            // Kembalikan focus dan selection
            element.focus();

            // Restore selection setelah formatting
            if (savedRange && !savedRange.collapsed) {
                try {
                    selection.removeAllRanges();
                    selection.addRange(savedRange);
                } catch (e) {
                    // Ignore error
                }
            }
        } catch (e) {
            console.error('Error executing command:', e);
        }
    }

    // Fungsi khusus untuk insert link
    function insertLinkToElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const selection = window.getSelection();
        let range = null;
        let selectedText = '';

        if (selection.rangeCount > 0) {
            range = selection.getRangeAt(0);
            selectedText = range.toString();
        }

        // Minta URL dari user
        const url = prompt('Masukkan URL:', 'https://');
        if (!url || !url.trim()) return;

        // Pastikan URL memiliki protocol
        let finalUrl = url.trim();
        if (!finalUrl.match(/^https?:\/\//i)) {
            finalUrl = 'https://' + finalUrl;
        }

        // Pastikan element dalam focus
        element.focus();

        // Jika ada selection, gunakan selection tersebut
        if (range && !range.collapsed) {
            selection.removeAllRanges();
            selection.addRange(range);
        } else {
            // Jika tidak ada selection, buat link dengan text yang dipilih atau URL
            const linkText = selectedText || finalUrl;
            const link = document.createElement('a');
            link.href = finalUrl;
            link.textContent = linkText;
            link.target = '_blank';
            link.className = 'link-text'; // Class for optional styling

            if (range) {
                range.deleteContents();
                range.insertNode(link);
            } else {
                element.appendChild(link);
            }
        }

        try {
            // Coba gunakan execCommand untuk insert link
            const success = document.execCommand('createLink', false, finalUrl);

            if (!success) {
                // Fallback: buat link manual
                if (range && !range.collapsed) {
                    const link = document.createElement('a');
                    link.href = finalUrl;
                    link.target = '_blank';
                    range.surroundContents(link);
                }
            }

            // Update hidden input
            if (elementId === 'formTitle') {
                updateFormTitle();
            } else if (elementId === 'formDescription') {
                updateFormDescription();
            }

            // Kembalikan focus
            element.focus();
        } catch (e) {
            console.error('Error inserting link:', e);
        }
    }

    function previewHeaderImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('headerImagePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateFormTitle() {
        const titleElement = document.getElementById('formTitle');
        const hiddenInput = document.getElementById('formTitleHidden');
        if (titleElement && hiddenInput) {
            // FIX: Use innerHTML to preserve formatting (bold, italic, etc.)
            hiddenInput.value = titleElement.innerHTML;
        }
    }

    function updateFormDescription() {
        const descElement = document.getElementById('formDescription');
        const hiddenInput = document.getElementById('formDescriptionHidden');
        if (descElement && hiddenInput) {
            // FIX: Use innerHTML to preserve formatting
            hiddenInput.value = descElement.innerHTML;
        }
    }

    // Field formatting functions
    let fieldToolbarMouseOver = {};

    function showFieldFormattingToolbar(fieldId) {
        const toolbar = document.getElementById('fieldFormattingToolbar_' + fieldId);
        if (toolbar) {
            toolbar.classList.remove('hidden');

            // Tambahkan event listener untuk mencegah hide saat mouse di toolbar
            if (!toolbar.hasAttribute('data-listener-added')) {
                toolbar.addEventListener('mouseenter', function () {
                    fieldToolbarMouseOver[fieldId] = true;
                });

                toolbar.addEventListener('mouseleave', function () {
                    fieldToolbarMouseOver[fieldId] = false;
                });

                toolbar.setAttribute('data-listener-added', 'true');
            }
        }
    }

    function hideFieldFormattingToolbar(fieldId) {
        const toolbar = document.getElementById('fieldFormattingToolbar_' + fieldId);
        if (toolbar) {
            setTimeout(() => {
                const labelElement = document.getElementById('label_' + fieldId);

                // Jangan hide jika:
                // 1. Element masih dalam focus
                // 2. Mouse masih di atas toolbar
                // 3. Toolbar masih dalam focus
                if (labelElement &&
                    !labelElement.matches(':focus') &&
                    document.activeElement !== labelElement &&
                    !fieldToolbarMouseOver[fieldId] &&
                    document.activeElement !== toolbar &&
                    !toolbar.contains(document.activeElement)) {
                    toolbar.classList.add('hidden');
                }
            }, 300); // Increase delay untuk memberi waktu user klik tombol
        }
    }

    function formatFieldText(fieldId, command, value = null) {
        const labelElement = document.getElementById('label_' + fieldId);
        if (!labelElement) return;

        // Handle insertLink khusus
        if (command === 'insertLink') {
            insertLinkToField(fieldId);
            return;
        }

        // Simpan selection SEBELUM kehilangan focus
        const selection = window.getSelection();
        let savedRange = null;

        if (selection.rangeCount > 0) {
            savedRange = selection.getRangeAt(0).cloneRange();
        }

        // Pastikan element dalam focus
        labelElement.focus();

        // Restore selection jika ada
        if (savedRange) {
            try {
                selection.removeAllRanges();
                selection.addRange(savedRange);
            } catch (e) {
                console.warn('Could not restore selection:', e);
            }
        }

        // Jika tidak ada selection atau collapsed, buat selection di seluruh text
        if (!savedRange || savedRange.collapsed) {
            // Cari text node pertama
            let textNode = labelElement;
            for (let node of labelElement.childNodes) {
                if (node.nodeType === Node.TEXT_NODE || node.nodeType === Node.ELEMENT_NODE) {
                    textNode = node;
                    break;
                }
            }

            const textLength = textNode.textContent ? textNode.textContent.length : 0;
            const range = document.createRange();

            if (textNode.nodeType === Node.TEXT_NODE) {
                range.setStart(textNode, 0);
                range.setEnd(textNode, textLength);
            } else {
                range.selectNodeContents(textNode);
            }

            selection.removeAllRanges();
            selection.addRange(range);
        }

        try {
            // Jalankan command
            const success = document.execCommand(command, false, value);

            if (!success) {
                console.warn('Command failed:', command);
            }

            // Update field label
            updateFieldLabel(fieldId, labelElement.innerHTML); // FIX: INNER HTML

            // Pastikan toolbar tetap visible
            showFieldFormattingToolbar(fieldId);

            // Kembalikan focus dan selection
            labelElement.focus();

            // Restore selection setelah formatting
            if (savedRange && !savedRange.collapsed) {
                try {
                    selection.removeAllRanges();
                    selection.addRange(savedRange);
                } catch (e) {
                    // Ignore error
                }
            }
        } catch (e) {
            console.error('Error executing command:', e);
        }
    }

    // Fungsi khusus untuk insert link pada field
    function insertLinkToField(fieldId) {
        const labelElement = document.getElementById('label_' + fieldId);
        if (!labelElement) return;

        const selection = window.getSelection();
        let range = null;
        let selectedText = '';

        if (selection.rangeCount > 0) {
            range = selection.getRangeAt(0);
            selectedText = range.toString();
        }

        // Minta URL dari user
        const url = prompt('Masukkan URL:', 'https://');
        if (!url || !url.trim()) return;

        // Pastikan URL memiliki protocol
        let finalUrl = url.trim();
        if (!finalUrl.match(/^https?:\/\//i)) {
            finalUrl = 'https://' + finalUrl;
        }

        // Pastikan element dalam focus
        labelElement.focus();

        // Jika ada selection, gunakan selection tersebut
        if (range && !range.collapsed) {
            selection.removeAllRanges();
            selection.addRange(range);
        }

        try {
            // Coba gunakan execCommand untuk insert link
            const success = document.execCommand('createLink', false, finalUrl);

            if (!success) {
                // Fallback: buat link manual
                if (range && !range.collapsed) {
                    const link = document.createElement('a');
                    link.href = finalUrl;
                    link.target = '_blank';
                    range.surroundContents(link);
                } else {
                    const link = document.createElement('a');
                    link.href = finalUrl;
                    link.textContent = finalUrl;
                    link.target = '_blank';
                    labelElement.appendChild(link);
                }
            }

            // Update field label
            updateFieldLabel(fieldId, labelElement.innerHTML); // FIX: InnerHTML

            // Kembalikan focus
            labelElement.focus();
        } catch (e) {
            console.error('Error inserting link:', e);
        }
    }

    function addFieldImage(fieldId) {
        // Create hidden file input if not exists
        let fileInput = document.getElementById('fieldImageInput_' + fieldId);
        if (!fileInput) {
            fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.id = 'fieldImageInput_' + fieldId;
            fileInput.accept = 'image/*';
            fileInput.style.display = 'none';
            document.body.appendChild(fileInput);

            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                    // Show loading or something? For now just wait
                    fetch('<?= url_to('form.uploadImage') ?>', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const labelElement = document.getElementById('label_' + fieldId);
                                if (labelElement) {
                                    labelElement.focus();
                                    // Create image HTML with styling
                                    const imgHtml = `<img src="${data.url}" class="field-inline-image" alt="Field Image">`;
                                    // Insert at cursor or append? We append to ensure it's below text
                                    labelElement.innerHTML += '<br>' + imgHtml;

                                    // Force Update
                                    updateFieldLabel(fieldId, labelElement.innerHTML);
                                }
                            } else {
                                alert('Gagal mengupload gambar: ' + (data.message || 'Unknown error'));
                            }
                            // Clear input
                            this.value = '';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat upload gambar');
                            this.value = '';
                        });
                }
            });
        }

        // Trigger click
        fileInput.click();
    }

    // Additional form elements
    function addTitleAndDescription() {
        // Add a new title/description section (similar to form header)
        alert('Fitur Add title and description akan segera hadir');
    }

    function addFormImage() {
        const url = prompt('Masukkan URL gambar:');
        if (url) {
            const container = document.getElementById('fieldsContainer');
            const imageDiv = document.createElement('div');
            imageDiv.className = 'form-field-card';
            imageDiv.innerHTML = `
            <img src="${escapeHtml(url)}" style="max-width: 100%; height: auto; border-radius: 4px;" alt="Form image">
            <div class="field-actions">
                <button type="button" class="field-action-btn" onclick="this.parentElement.parentElement.remove()">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        `;
            container.appendChild(imageDiv);
        }
    }

    function addFormVideo() {
        const url = prompt('Masukkan URL video (YouTube/Vimeo):');
        if (url) {
            const container = document.getElementById('fieldsContainer');
            const videoDiv = document.createElement('div');
            videoDiv.className = 'form-field-card';
            videoDiv.innerHTML = `
            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                <iframe src="${escapeHtml(url)}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="field-actions">
                <button type="button" class="field-action-btn" onclick="this.parentElement.parentElement.remove()">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        `;
            container.appendChild(videoDiv);
        }
    }

    function addSection() {
        const title = prompt('Masukkan judul section:');
        if (title) {
            const container = document.getElementById('fieldsContainer');
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'form-field-card';
            sectionDiv.style.borderTop = '10px solid #1a73e8';
            sectionDiv.innerHTML = `
            <div style="font-size: 16px; font-weight: 500; color: #202124; margin-bottom: 8px;">${escapeHtml(title)}</div>
            <div style="font-size: 14px; color: #5f6368; margin-bottom: 12px;">Section description (optional)</div>
            <div class="field-actions">
                <button type="button" class="field-action-btn" onclick="this.parentElement.parentElement.remove()">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        `;
            container.appendChild(sectionDiv);
        }
    }

    // Form submission
    document.getElementById('formBuilderForm').addEventListener('submit', function (e) {
        updateFormTitle();
        updateFormDescription();
        updateFieldsData();
        // Form will submit normally with fields_data
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        updateEmptyState();

        // Set initial placeholder style for contenteditable elements
        const titleElement = document.getElementById('formTitle');
        const descElement = document.getElementById('formDescription');

        if (titleElement) {
            if (titleElement.textContent.trim() === 'Untitled form') {
                titleElement.style.color = '#80868b';
            } else {
                titleElement.style.color = '#202124';
            }
        }

        if (descElement) {
            if (descElement.textContent.trim() === 'Form description') {
                descElement.style.color = '#80868b';
            } else {
                descElement.style.color = '#5f6368';
            }
        }
    });
</script>