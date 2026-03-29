<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= strip_tags($form['title'] ?? 'Form') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Security: Honeypot field styling */
    #website {
        position: absolute !important;
        left: -9999px !important;
        opacity: 0 !important;
        pointer-events: none !important;
        visibility: hidden !important;
    }

    /* Form security styling */
    .btn-submit {
        position: relative;
        overflow: hidden;
    }

    /* Styling for inline images in fields */
    .field-inline-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 10px;
        display: block;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    min-width: 150px;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    .form-container {
        max-width: 800px;
        width: 100%;
        margin: 6rem auto 2rem auto;
        padding: 2rem;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
    }

    /* Spacing untuk tablet - jarak dari navbar */
    @media (max-width: 991.98px) {
        .form-container {
            margin-top: 8rem;
        }
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .form-header h1 {
        color: #1f2937;
        font-size: 1.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
        line-height: 1.4;
    }

    .form-header p {
        color: #6b7280;
        font-size: 1rem;
        word-wrap: break-word;
        word-break: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
        line-height: 1.6;
        margin: 0;
    }

    .form-field {
        margin-bottom: 1.5rem;
    }

    .form-field label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    .form-field label .required {
        color: #dc2626;
    }

    .form-field input[type="text"],
    .form-field input[type="email"],
    .form-field input[type="number"],
    .form-field input[type="date"],
    .form-field input[type="time"],
    .form-field input[type="datetime-local"],
    .form-field textarea,
    .form-field select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .form-field input:focus,
    .form-field textarea:focus,
    .form-field select:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .form-field small {
        display: block;
        margin-top: 0.25rem;
        color: #6b7280;
        font-size: 0.75rem;
    }

    .radio-group,
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .radio-group label,
    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: normal;
        cursor: pointer;
    }

    .radio-group input[type="radio"],
    .checkbox-group input[type="checkbox"] {
        width: auto;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .form-container {
            margin-top: 8rem;
            padding: 1.75rem;
        }

        .form-header h1 {
            font-size: 1.625rem;
        }

        .form-header p {
            font-size: 0.9375rem;
            line-height: 1.5;
        }
    }

    @media (max-width: 768px) {
        .form-container {
            margin: 9rem 1rem 2rem 1rem;
            padding: 1.5rem;
        }

        .form-header {
            margin-bottom: 1.75rem;
            padding-bottom: 0.875rem;
        }

        .form-header h1 {
            font-size: 1.5rem;
        }

        .form-header p {
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .form-field {
            margin-bottom: 1.25rem;
        }

        .form-field label {
            font-size: 0.9375rem;
        }

        .form-field input[type="text"],
        .form-field input[type="email"],
        .form-field input[type="number"],
        .form-field input[type="date"],
        .form-field input[type="time"],
        .form-field input[type="datetime-local"],
        .form-field textarea,
        .form-field select {
            padding: 0.625rem;
            font-size: 0.875rem;
        }

        .btn-submit {
            min-width: 120px;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .form-container {
            margin: 10rem 0.75rem 1.5rem 0.75rem;
            padding: 1.25rem;
        }

        .form-header {
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
        }

        .form-header h1 {
            font-size: 1.25rem;
            line-height: 1.4;
        }

        .form-header p {
            font-size: 0.8125rem;
            line-height: 1.5;
        }

        .form-field {
            margin-bottom: 1rem;
        }

        .form-field label {
            font-size: 0.875rem;
            margin-bottom: 0.375rem;
        }

        .form-field input[type="text"],
        .form-field input[type="email"],
        .form-field input[type="number"],
        .form-field input[type="date"],
        .form-field input[type="time"],
        .form-field input[type="datetime-local"],
        .form-field textarea,
        .form-field select {
            padding: 0.5rem;
            font-size: 0.8125rem;
        }

        .form-field small {
            font-size: 0.6875rem;
        }

        .btn-submit {
            min-width: 100px;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 375px) {
        .form-container {
            margin: 9rem 0.5rem 1rem 0.5rem;
            padding: 1rem;
        }

        .form-header {
            margin-bottom: 1.25rem;
            padding-bottom: 0.625rem;
        }

        .form-header h1 {
            font-size: 1.125rem;
        }

        .form-header p {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .form-field label {
            font-size: 0.8125rem;
        }

        .form-field input[type="text"],
        .form-field input[type="email"],
        .form-field input[type="number"],
        .form-field input[type="date"],
        .form-field input[type="time"],
        .form-field input[type="datetime-local"],
        .form-field textarea,
        .form-field select {
            padding: 0.5rem;
            font-size: 0.75rem;
        }

        .btn-submit {
            min-width: 90px;
            padding: 0.5rem 0.875rem;
            font-size: 0.75rem;
        }
    }

    /* Hide form after submission */
    .form-submitted {
        display: none !important;
    }

    .submission-message {
        text-align: center;
        padding: 2rem;
        background: #f0f9ff;
        border: 2px solid #0ea5e9;
        border-radius: 0.5rem;
        margin-top: 1rem;
    }

    .submission-message i {
        font-size: 3rem;
        color: #0ea5e9;
        margin-bottom: 1rem;
    }

    .submission-message h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #0ea5e9;
    }

    .submission-message p {
        font-size: 1rem;
        line-height: 1.6;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .submission-message small {
        display: block;
        margin-top: 0.5rem;
        color: #6b7280;
    }

    .security-notice {
        background: #fff;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 1rem;
        border-left: 4px solid #0ea5e9;
    }

    .security-notice-text {
        color: #64748b;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .security-notice-text i {
        color: #0ea5e9;
        margin-right: 0.5rem;
    }

    .security-warning {
        color: #dc2626;
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 500;
        line-height: 1.6;
    }

    .security-warning i {
        color: #dc2626;
        margin-right: 0.5rem;
    }

    /* Responsive untuk submission message */
    @media (max-width: 768px) {
        .submission-message {
            padding: 1.5rem;
        }

        .submission-message i {
            font-size: 2.5rem;
        }

        .submission-message h3 {
            font-size: 1.25rem;
        }

        .submission-message p {
            font-size: 0.875rem;
        }

        .security-notice {
            padding: 0.875rem;
        }

        .security-notice-text {
            font-size: 0.8125rem;
        }

        .security-warning {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .submission-message {
            padding: 1.25rem;
        }

        .submission-message i {
            font-size: 2rem;
        }

        .submission-message h3 {
            font-size: 1.125rem;
        }

        .submission-message p {
            font-size: 0.8125rem;
        }

        .security-notice {
            padding: 0.75rem;
        }

        .security-notice-text {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .security-warning {
            font-size: 0.6875rem;
        }
    }

    @media (max-width: 375px) {
        .submission-message {
            padding: 1rem;
        }

        .submission-message i {
            font-size: 1.75rem;
        }

        .submission-message h3 {
            font-size: 1rem;
        }

        .submission-message p {
            font-size: 0.75rem;
        }

        .security-notice {
            padding: 0.625rem;
        }

        .security-notice-text {
            font-size: 0.6875rem;
            margin-bottom: 0.5rem;
        }

        .security-warning {
            font-size: 0.625rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="form-container">
    <div class="form-header-image" style="margin-bottom: 24px; text-align: center;">
        <?php
        $headerImage = 'images/Horizon_University_Indonesia_Logo.png';
        if (!empty($form['header_image_path']) && file_exists(FCPATH . $form['header_image_path'])) {
            $headerImage = $form['header_image_path'];
        }
        ?>
        <img src="<?= base_url($headerImage) ?>" alt="Header Image"
            style="max-width: 100%; max-height: 200px; object-fit: contain;">
    </div>
    <div class="form-header">
        <h1><?= $form['title'] ?? 'Form' ?></h1>
        <?php if (!empty($form['description'])): ?>
            <p><?= $form['description'] ?></p>
        <?php endif; ?>
    </div>

    <?php
    // Check if form already submitted
    $sessionKey = 'form_submitted_' . ($form['id'] ?? 0);
    $sessionTime = session()->get($sessionKey . '_time');
    $isSubmitted = false;

    // Check if session flag exists and is still valid (24 hours)
    if (session()->get($sessionKey) && $sessionTime) {
        $timeDiff = time() - $sessionTime;
        if ($timeDiff < 86400) { // 24 hours
            $isSubmitted = true;
        } else {
            // Session expired, remove it
            session()->remove($sessionKey);
            session()->remove($sessionKey . '_time');
        }
    }

    // Check IP address for existing submission (within last 24 hours)
    $request = \Config\Services::request();
    $ipAddress = $request->getIPAddress();
    $db = \Config\Database::connect();
    $existingResponse = null;
    if (isset($form['id'])) {
        $existingResponse = $db->table('form_responses')
            ->where('form_id', $form['id'])
            ->where('ip_address', $ipAddress)
            ->where('status', 'submitted')
            ->where('submitted_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->orderBy('submitted_at', 'DESC')
            ->get()
            ->getRowArray();
    }

    $canSubmit = !$isSubmitted && !$existingResponse && isset($form) && $form['status'] == 'published';
    ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if ($isSubmitted || $existingResponse): ?>
        <div class="submission-message">
            <i class="bi bi-check-circle-fill"></i>
            <h3 style="color: #0ea5e9; margin-bottom: 0.5rem;">Form Berhasil Dikirim!</h3>
            <p style="color: #64748b; margin-bottom: 0.5rem;">Terima kasih atas partisipasi Anda. Form Anda telah berhasil
                dikirim.</p>
            <?php if ($existingResponse): ?>
                <small class="text-muted">Waktu submit:
                    <?= date('d/m/Y H:i', strtotime($existingResponse['submitted_at'])) ?></small>
            <?php endif; ?>
            <div class="security-notice">
                <p class="security-notice-text">
                    <i class="bi bi-shield-check"></i>
                    <strong>Untuk mencegah spam, form ini hanya dapat diisi sekali per IP address dalam 24 jam.</strong>
                </p>
                <p class="security-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Penting:</strong> Gunakan internet data masing-masing. Jangan menggunakan WiFi kampus atau WiFi
                    yang digunakan bersama-sama, karena akan menggunakan IP address yang sama.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($form) && $form['status'] == 'published' && $canSubmit): ?>
        <form action="<?= site_url('form/public/submit/' . $form['encrypted_link']) ?>" method="post" id="publicForm"
            class="php-email-form" enctype="multipart/form-data" <?= ($isSubmitted || $existingResponse) ? 'style="display: none;"' : '' ?>>
            <?= csrf_field() ?>

            <!-- Honeypot Field (Hidden from users, but bots will fill it) -->
            <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                <label for="website">Website (jangan isi jika Anda manusia)</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <!-- Form Start Time (for time-based validation) -->
            <input type="hidden" name="form_start_time" id="form_start_time" value="<?= time() ?>">
            <input type="hidden" name="form_id" value="<?= $form['id'] ?>">

            <?php if (isset($fields) && !empty($fields)): ?>
                <?php foreach ($fields as $field): ?>
                    <div class="form-field">
                        <label for="field_<?= $field['id'] ?>">
                            <?= $field['label'] ?>
                            <?php if ($field['is_required']): ?>
                                <span class="required">*</span>
                            <?php endif; ?>
                        </label>

                        <?php if (!empty($field['description'])): ?>
                            <small><?= $field['description'] ?></small>
                        <?php endif; ?>

                        <?php
                        $fieldName = 'field_' . $field['id'];
                        $requiredAttr = $field['is_required'] ? 'required' : '';
                        ?>

                        <?php if ($field['field_type'] == 'text'): ?>
                            <input type="text" id="field_<?= $field['id'] ?>" name="<?= $fieldName ?>"
                                placeholder="<?= esc($field['placeholder'] ?? '') ?>" <?= $requiredAttr ?>>

                        <?php elseif ($field['field_type'] == 'textarea'): ?>
                            <textarea id="field_<?= $field['id'] ?>" name="<?= $fieldName ?>" rows="4"
                                placeholder="<?= esc($field['placeholder'] ?? '') ?>" <?= $requiredAttr ?>></textarea>

                        <?php elseif ($field['field_type'] == 'email'): ?>
                            <input type="email" id="field_<?= $field['id'] ?>" name="<?= $fieldName ?>"
                                placeholder="<?= esc($field['placeholder'] ?? '') ?>" <?= $requiredAttr ?>>

                        <?php elseif ($field['field_type'] == 'number'): ?>
                            <input type="number" id="field_<?= $field['id'] ?>" name="<?= $fieldName ?>"
                                placeholder="<?= esc($field['placeholder'] ?? '') ?>" <?= $requiredAttr ?>>

                        <?php elseif (in_array($field['field_type'], ['date', 'time', 'datetime'])): ?>
                            <input type="<?= $field['field_type'] == 'datetime' ? 'datetime-local' : $field['field_type'] ?>"
                                id="field_<?= $field['id'] ?>" name="<?= $fieldName ?>" <?= $requiredAttr ?>>

                        <?php elseif ($field['field_type'] == 'select'): ?>
                            <?php
                            $options = json_decode($field['options'] ?? '[]', true);
                            ?>
                            <select id="field_<?= $field['id'] ?>" name="<?= $fieldName ?>" <?= $requiredAttr ?>>
                                <option value="">--Pilih--</option>
                                <?php foreach ($options as $option): ?>
                                    <option value="<?= esc($option) ?>"><?= $option ?></option>
                                <?php endforeach; ?>
                            </select>

                        <?php elseif ($field['field_type'] == 'radio'): ?>
                            <?php
                            $options = json_decode($field['options'] ?? '[]', true);
                            ?>
                            <div class="radio-group">
                                <?php foreach ($options as $option): ?>
                                    <label>
                                        <input type="radio" name="<?= $fieldName ?>" value="<?= esc($option) ?>" <?= $requiredAttr ?>>
                                        <?= $option ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($field['field_type'] == 'checkbox'): ?>
                            <?php
                            $options = json_decode($field['options'] ?? '[]', true);
                            ?>
                            <div class="checkbox-group">
                                <?php foreach ($options as $option): ?>
                                    <label>
                                        <input type="checkbox" name="<?= $fieldName ?>[]" value="<?= esc($option) ?>">
                                        <?= $option ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($field['field_type'] == 'file'): ?>
                            <input type="file" id="field_<?= $field['id'] ?>" name="<?= $fieldName ?>" <?= $requiredAttr ?>
                                accept="image/*,.pdf,.doc,.docx">

                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Form ini belum memiliki field. Silakan hubungi administrator.
                </div>
            <?php endif; ?>

            <?php if (isset($fields) && !empty($fields)): ?>
                <div class="form-field text-center">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-submit">
                        <span id="submitText">Kirim</span>
                        <span id="submitLoader" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...
                        </span>
                    </button>
                </div>
            <?php endif; ?>
        </form>
    <?php elseif (isset($form) && $form['status'] == 'published' && !$canSubmit): ?>
        <!-- Form sudah diisi, tidak ditampilkan -->
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> Form ini tidak tersedia atau sudah ditutup.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Form validation and security
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('publicForm');
        if (form) {
            // Prevent double submission
            let isSubmitting = false;

            form.addEventListener('submit', function (e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                // Validate honeypot
                const honeypot = document.getElementById('website');
                if (honeypot && honeypot.value !== '') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak',
                        text: 'Form tidak valid.',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                // Validate time
                const formStartTime = parseInt(document.getElementById('form_start_time').value);
                const currentTime = Math.floor(Date.now() / 1000);
                const timeElapsed = currentTime - formStartTime;

                if (timeElapsed < 5) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Terlalu Cepat',
                        text: 'Mohon isi form dengan lebih teliti. Form dikirim terlalu cepat.',
                        confirmButtonColor: '#ffc107'
                    });
                    return false;
                }

                // Validate required fields
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                requiredFields.forEach(field => {
                    if (!field.value || (field.type === 'checkbox' && !field.checked)) {
                        isValid = false;
                        field.style.borderColor = '#dc2626';
                    } else {
                        field.style.borderColor = '#d1d5db';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Form Tidak Lengkap',
                        text: 'Mohon lengkapi semua field yang wajib diisi.',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                // Check for suspicious content
                const textInputs = form.querySelectorAll('input[type="text"], textarea');
                const spamPatterns = ['viagra', 'casino', 'lottery', 'winner', 'click here', 'buy now'];
                let spamCount = 0;

                textInputs.forEach(input => {
                    const value = input.value.toLowerCase();
                    spamPatterns.forEach(pattern => {
                        if (value.includes(pattern)) {
                            spamCount++;
                        }
                    });
                });

                if (spamCount >= 3) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Pesan Terdeteksi sebagai Spam',
                        text: 'Pesan Anda mengandung konten yang mencurigakan. Silakan gunakan bahasa yang lebih formal.',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                // Show loading
                isSubmitting = true;
                const submitBtn = document.getElementById('submitBtn');
                const submitText = document.getElementById('submitText');
                const submitLoader = document.getElementById('submitLoader');

                if (submitBtn) submitBtn.disabled = true;
                if (submitText) submitText.style.display = 'none';
                if (submitLoader) submitLoader.style.display = 'inline-block';

                // Allow form to submit normally
                return true;
            });
        }
    });

    // Cek SweetAlert Flashdata dan sembunyikan form setelah submit
    <?php if (session()->getFlashdata('success')): ?>
        // Hide form after successful submission
        const form = document.getElementById('publicForm');
        if (form) {
            form.style.display = 'none';
        }

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            html: '<?= session()->getFlashdata('success') ?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            // Reload page to show submission message
            window.location.reload();
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>