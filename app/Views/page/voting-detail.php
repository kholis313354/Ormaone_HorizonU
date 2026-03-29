<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<link href="<?= base_url('dist/landing/assets/css/main1.css') ?>" rel="stylesheet">
<link href="<?= base_url('dist/landing/assets/css/styles4.css') ?>" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/css/styles5.css') ?>">
<style>
    /* Styling untuk tabel dengan border tebal */
    #datatable {
        border: 2px solid #dee2e6 !important;
    }

    #datatable th,
    #datatable td {
        border: 1px solid #dee2e6 !important;
    }

    #datatable thead th {
        border-bottom: 2px solid #dee2e6 !important;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Hero Section -->
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <img src="<?= base_url('dist/landing/assets/img/Vector.png') ?>" class="img-fluid" alt="">
            <h1 class="fw-bolder" style="color: #980517;"><?= lang('Text.e_voting') ?></h1>
            <p><?= lang('Text.voting_warning') ?></p>
        </div>
    </div>
</header>
<?php include_once(APPPATH . 'Views/components/errors.php'); ?>
<?php include_once(APPPATH . 'Views/components/flash.php'); ?>

<!-- Card content-->
<div class="card mb-3">
    <div class="card-body">
        <div class="card-title title-body-chart"><?= lang('Text.voting_statistics') ?> <?= $calon['organisasi_name'] ?>
        </div>
        <div class="row">
            <!-- Bagian Chart -->
            <div class="col-xl-5 col-lg-6 col-md-12 mb-4 mb-lg-0 chart-arrow">
                <div id="chart-container">
                    <div class="chart" id="chart1">
                        <div class="card card-chart h-100">
                            <div class="card-body d-flex flex-column">
                                <!--<h6 class="fw-semibold mb-3">Jumlah Suara Voting</h6>-->
                                <div class="chart-container flex-grow-1">
                                    <canvas id="chart-voting"
                                        style="width:100%; height:100%; min-height:250px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Data Voting -->
            <div class="col-xl-7 col-lg-6 col-md-12">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <!-- Header dan Pencarian -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold m-0" style="color: #980517;"><?= lang('Text.voting_data') ?></h4>
                        </div>

                        <!-- Table Responsive -->
                        <div class="table-responsive flex-grow-1">
                            <table class="table table-hover table-bordered mb-0" id="datatable" width="100%"
                                cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-nowrap"><?= lang('Text.no') ?></th>
                                        <th class="text-nowrap"><?= lang('Text.name') ?></th>
                                        <th class="text-nowrap"><?= lang('Text.nim') ?></th>
                                        <th class="text-nowrap"><?= lang('Text.email') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $page = $pager->getCurrentPage('suara');
                                    $perPage = $pager->getPerPage('suara');
                                    $no = 1 + ($perPage * ($page - 1));
                                    ?>
                                    <?php
                                    // Helper untuk masking NIM dan email
                                    if (!function_exists('maskNim')) {
                                        function maskNim($nim)
                                        {
                                            $nim = (string) $nim;
                                            $length = strlen($nim);
                                            if ($length <= 4) {
                                                return str_repeat('*', $length);
                                            }
                                            return str_repeat('*', $length - 4) . substr($nim, -4);
                                        }
                                    }

                                    if (!function_exists('maskEmailKeepDomain')) {
                                        function maskEmailKeepDomain($email)
                                        {
                                            $email = (string) $email;
                                            $parts = explode('@', $email, 2);
                                            $local = $parts[0] ?? '';
                                            // Normalisasi domain ke krw.horizon.ac.id jika termasuk domain kampus
                                            $domain = 'krw.horizon.ac.id';
                                            if (isset($parts[1]) && !preg_match('/krw\.horizon(\.ac\.id)?/i', $parts[1])) {
                                                // Jika domain bukan domain kampus, tetap tampilkan domain aslinya
                                                $domain = $parts[1];
                                            }
                                            $maskedLocal = str_repeat('*', max(3, strlen($local)));
                                            return $maskedLocal . '@' . $domain;
                                        }
                                    }

                                    if (!function_exists('maskName')) {
                                        function maskName($name)
                                        {
                                            $name = trim((string) $name);
                                            if (empty($name)) {
                                                return '';
                                            }
                                            // Ambil huruf pertama (case-sensitive)
                                            $firstChar = substr($name, 0, 1);
                                            // Hitung panjang nama tanpa spasi untuk masking
                                            $nameLength = strlen(str_replace(' ', '', $name));
                                            // Jika hanya 1 karakter, return bintang saja
                                            if ($nameLength <= 1) {
                                                return '*';
                                            }
                                            // Tampilkan huruf pertama + bintang untuk sisa karakter
                                            return $firstChar . str_repeat('*', $nameLength - 1);
                                        }
                                    }
                                    ?>
                                    <?php foreach ($suara as $s): ?>
                                        <tr>
                                            <td style="color: #6c757d;"><?= $no ?></td>
                                            <td style="color: #6c757d;"><?= maskName($s['name']) ?></td>
                                            <td style="color: #6c757d;"><?= maskNim($s['nim']) ?></td>
                                            <td style="color: #6c757d;"><?= maskEmailKeepDomain($s['email']) ?></td>
                                        </tr>
                                        <?php $no++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <?= $pager->links('suara', 'default_full') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Card conten end -->
<!-- Bootstrap sudah di-load di layout dengan defer, tidak perlu duplikat -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // DOM Elements
        const nimInput = document.getElementById('nim');
        const kodeFakultasInput = document.getElementById('kode_fakultas');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const voteForm = document.getElementById('voteForm');
        const btnRequestOtp = document.getElementById('btnRequestOtp');
        const otpInput = document.getElementById('otp');
        const btnVote = document.getElementById('btnVote');
        const pemilihanCalonIdInput = document.getElementById('pemilihan_calon_id');

        // Faculty code mapping dari database organisasis
        const fakultasMapping = <?= json_encode($fakultasMapping ?? []) ?>;
        const allKodeFakultas = <?= json_encode($allKodeFakultas ?? []) ?>;

        // Kode fakultas organisasi kandidat saat ini
        const calonOrganisasiKodeFakultas = <?= ($calonOrganisasiKodeFakultas === null || $calonOrganisasiKodeFakultas === '' || $calonOrganisasiKodeFakultas === 'NULL') ? 'null' : json_encode($calonOrganisasiKodeFakultas) ?>;
        const calonOrganisasiName = '<?= isset($calon['organisasi_name']) ? addslashes($calon['organisasi_name']) : '' ?>';

        // Data waktu voting
        const isVotingActive = <?= $isVotingActive ? 'true' : 'false' ?>;
        const tanggalMulai = <?= isset($tanggalMulai) ? json_encode($tanggalMulai) : 'null' ?>;
        const tanggalAkhir = <?= isset($tanggalAkhir) ? json_encode($tanggalAkhir) : 'null' ?>;
        const votingMessage = <?= !empty($votingMessage) ? json_encode($votingMessage) : 'null' ?>;

        // Debug log (hapus di production)
        console.log('Calon Organisasi:', calonOrganisasiName);
        console.log('Kode Fakultas Organisasi:', calonOrganisasiKodeFakultas);
        console.log('Is Null?', calonOrganisasiKodeFakultas === null);


        // ======================
        // VALIDATION FUNCTIONS
        // ======================

        /** Sanitize input to prevent XSS */
        function sanitizeInput(input) {
            const map = {
                '<': '&lt;',
                '>': '&gt;',
                '&': '&amp;',
                '"': '&quot;',
                "'": '&#39;'
            };
            return String(input).replace(/[<>&"']/g, char => map[char]);
        }

        /** Check if input contains only safe characters */
        function isSafeInput(input) {
            return /^[a-zA-Z0-9\s@.\-_,]+$/.test(input);
        }

        /** Check if email contains parts of the user's name */
        function checkNameInEmail(name, email) {
            const nameParts = name.toLowerCase().split(/\s+/);
            return nameParts.some(part =>
                part.length > 3 && email.toLowerCase().includes(part)
            );
        }

        /** Get faculty name by faculty code */
        function getFakultasByKode(kode) {
            for (const [fakultas, kodes] of Object.entries(fakultasMapping)) {
                if (kodes && kodes.includes(kode)) return fakultas;
            }
            return null;
        }

        /** Check if faculty code is valid */
        function isValidFacultyCode(kode) {
            return allKodeFakultas.includes(kode);
        }

        /** Validate if faculty code matches the selected organization */
        function validateKodeFakultas(kodeFakultas) {
            // Jika organisasi kandidat memiliki kode_fakultas = null, semua NIM bisa vote
            // Cek berbagai kemungkinan nilai null
            if (calonOrganisasiKodeFakultas === null ||
                calonOrganisasiKodeFakultas === 'NULL' ||
                calonOrganisasiKodeFakultas === '' ||
                calonOrganisasiKodeFakultas === undefined ||
                String(calonOrganisasiKodeFakultas).toUpperCase() === 'NULL') {
                console.log('Organisasi UNIVERSITAS - semua NIM bisa vote');
                return true; // Semua NIM bisa vote untuk organisasi UNIVERSITAS
            }

            // Parse kode_fakultas organisasi kandidat (bisa comma-separated)
            const allowedKodes = String(calonOrganisasiKodeFakultas).split(',').map(k => k.trim()).filter(k => k !== '');

            console.log('Allowed codes:', allowedKodes);
            console.log('User code:', kodeFakultas);

            // Cek apakah kode fakultas NIM user ada di daftar kode yang diizinkan
            const isValid = allowedKodes.includes(kodeFakultas);
            console.log('Is valid?', isValid);

            return isValid;
        }

        // ======================
        // EVENT HANDLERS
        // ======================

        /** NIM Input Handler - Extract faculty code dan format display */
        if (nimInput) {
            nimInput.addEventListener('input', function () {
                // Hapus semua karakter non-digit
                let nim = this.value.replace(/\D/g, '');

                // Batasi maksimal 16 digit
                if (nim.length > 16) {
                    nim = nim.substring(0, 16);
                }

                // Format display dengan kode fakultas di tengah
                if (nim.length >= 6) {
                    const bagian1 = nim.substring(0, 5); // 5 digit pertama
                    const kodeFakultas = nim.substring(5, 10); // Posisi 6-10 (index 5-9)
                    const bagian2 = nim.substring(10); // Sisanya

                    // Set kode fakultas ke hidden input
                    kodeFakultasInput.value = kodeFakultas;

                    // Format display: 43378 (57201) 230056
                    if (nim.length <= 10) {
                        this.value = bagian1 + (kodeFakultas ? ' (' + kodeFakultas + ')' : '') + bagian2;
                    } else {
                        this.value = bagian1 + (kodeFakultas ? ' (' + kodeFakultas + ') ' : ' ') + bagian2;
                    }
                } else {
                    // Jika kurang dari 6 digit, tampilkan biasa
                    this.value = nim;
                    kodeFakultasInput.value = '';
                }
            });

            // Format display saat focus out (jika sudah 16 digit)
            nimInput.addEventListener('blur', function () {
                const nim = this.value.replace(/\D/g, '');
                if (nim.length === 16) {
                    const bagian1 = nim.substring(0, 5);
                    const kodeFakultas = nim.substring(5, 10);
                    const bagian2 = nim.substring(10);
                    this.value = bagian1 + ' (' + kodeFakultas + ') ' + bagian2;
                }
            });

            // Hapus format saat focus (tampilkan angka saja)
            nimInput.addEventListener('focus', function () {
                const nim = this.value.replace(/\D/g, '');
                this.value = nim;
            });
        }

        // Prevent form submission
        if (voteForm) {
            voteForm.addEventListener('submit', function (e) {
                e.preventDefault();
            });
        }

        /** Tombol Kirim OTP Handler */
        if (btnRequestOtp) {
            btnRequestOtp.addEventListener('click', async function (e) {
                e.preventDefault();

                // Cek apakah voting masih aktif
                if (!isVotingActive) {
                    return showError(votingMessage || 'Voting sudah berakhir atau belum dimulai.');
                }

                // Cek waktu voting secara real-time
                if (tanggalMulai && tanggalAkhir) {
                    const now = new Date();
                    const mulai = new Date(tanggalMulai);
                    const akhir = new Date(tanggalAkhir);

                    if (now < mulai) {
                        return showError('Voting belum dimulai. Voting akan dimulai pada ' + mulai.toLocaleString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        }) + '.');
                    }

                    if (now > akhir) {
                        return showError('Voting sudah berakhir pada ' + akhir.toLocaleString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        }) + '.');
                    }
                }

                const rawNim = nimInput.value.trim();
                const rawEmail = emailInput.value.trim();
                const rawName = nameInput.value.trim();

                // Ambil NIM tanpa format (hanya angka)
                const nim = rawNim.replace(/\D/g, '');
                const email = sanitizeInput(rawEmail);
                const name = sanitizeInput(rawName);

                // Ekstrak kode fakultas dari posisi 6-10 (index 5-9)
                const kodeFakultas = nim.length >= 10 ? nim.substring(5, 10) : '';

                // Validasi input
                if (!/^\d{16}$/.test(nim)) return showError('NIM harus 16 digit angka.');

                // Validasi kode fakultas harus ada di database
                if (!kodeFakultas || kodeFakultas.length !== 5) {
                    return showError('Kode fakultas tidak valid. Pastikan NIM Anda benar.');
                }

                // Cek apakah kode fakultas ada di database
                if (!isValidFacultyCode(kodeFakultas)) {
                    return showError('Kamu bukan Mahasiswa Horizon University Indonesia. Kode fakultas tidak terdaftar.');
                }

                // Validasi kode fakultas sesuai dengan organisasi kandidat
                // Jika organisasi UNIVERSITAS (kode_fakultas = null), skip validasi ini
                if (calonOrganisasiKodeFakultas !== null &&
                    calonOrganisasiKodeFakultas !== 'NULL' &&
                    calonOrganisasiKodeFakultas !== '' &&
                    calonOrganisasiKodeFakultas !== undefined) {
                    if (!validateKodeFakultas(kodeFakultas)) {
                        // Cari nama organisasi berdasarkan kode fakultas untuk pesan error
                        let facultyName = 'fakultas yang sesuai';
                        for (const [orgName, kodes] of Object.entries(fakultasMapping)) {
                            if (kodes.includes(kodeFakultas)) {
                                facultyName = orgName;
                                break;
                            }
                        }
                        return showError(`Anda tidak bisa memilih kandidat dari ${calonOrganisasiName} untuk NIM anda ${kodeFakultas}.`);
                    }
                }
                if (!/^[^@]+@krw\.horizon(\.ac\.id)?$/i.test(email)) return showError('Gunakan email kampus yang valid.');
                if (!name || !isSafeInput(name)) return showError('Nama wajib diisi dan tidak boleh mengandung karakter berbahaya.');
                if (!checkNameInEmail(name, email)) return showError('Email harus mengandung bagian dari nama Anda.');

                // Show loading
                const originalText = btnRequestOtp.innerHTML;
                btnRequestOtp.disabled = true;
                btnRequestOtp.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

                try {
                    const formData = new FormData();
                    formData.append('pemilihan_calon_id', pemilihanCalonIdInput.value);
                    formData.append('nim', nim); // NIM sudah bersih (hanya angka)
                    formData.append('name', name);
                    formData.append('email', email);
                    // Tambahkan CSRF token
                    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value || '';
                    if (csrfToken) {
                        formData.append('<?= csrf_token() ?>', csrfToken);
                    }

                    const res = await fetch('<?= url_to('vote.requestOtp') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const json = await res.json();

                    if (!res.ok) throw new Error(json.message || 'Gagal mengirim OTP');

                    // Success - show message
                    Swal.fire({
                        icon: 'success',
                        title: 'OTP Dikirim!',
                        html: '<p>OTP telah dikirim ke email Anda.</p><p><strong>Silakan cek email</strong> dan masukkan kode OTP 6 digit pada kolom OTP di bawah.</p>',
                        confirmButtonText: 'Mengerti'
                    });

                    // Enable OTP input
                    otpInput.disabled = false;
                    otpInput.focus();

                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                } finally {
                    btnRequestOtp.disabled = false;
                    btnRequestOtp.innerHTML = originalText;
                }
            });
        }

        /** Tombol Vote Handler - Verifikasi OTP dan Submit Suara */
        if (btnVote) {
            btnVote.addEventListener('click', async function (e) {
                e.preventDefault();

                // Cek apakah voting masih aktif
                if (!isVotingActive) {
                    return showError(votingMessage || 'Voting sudah berakhir atau belum dimulai.');
                }

                // Cek waktu voting secara real-time
                if (tanggalMulai && tanggalAkhir) {
                    const now = new Date();
                    const mulai = new Date(tanggalMulai);
                    const akhir = new Date(tanggalAkhir);

                    if (now < mulai) {
                        return showError('Voting belum dimulai. Voting akan dimulai pada ' + mulai.toLocaleString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        }) + '.');
                    }

                    if (now > akhir) {
                        return showError('Voting sudah berakhir pada ' + akhir.toLocaleString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        }) + '.');
                    }
                }

                const otp = (otpInput.value || '').trim();
                const email = (emailInput.value || '').trim();

                // Validasi OTP
                if (!/^\d{6}$/.test(otp)) {
                    return showError('OTP harus 6 digit angka.');
                }

                // Show loading
                const originalText = btnVote.innerHTML;
                btnVote.disabled = true;
                btnVote.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';

                try {
                    const fd = new FormData();
                    fd.append('pemilihan_calon_id', pemilihanCalonIdInput.value);
                    fd.append('email', email);
                    fd.append('otp', otp);
                    // Tambahkan CSRF token
                    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value || '';
                    if (csrfToken) {
                        fd.append('<?= csrf_token() ?>', csrfToken);
                    }

                    const res = await fetch('<?= url_to('vote.verifyOtp') ?>', {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const json = await res.json();

                    if (!res.ok) throw new Error(json.message || 'Verifikasi gagal');

                    // Success - suara otomatis masuk setelah OTP verified
                    Swal.fire({
                        icon: 'success',
                        title: 'Vote Berhasil!',
                        text: 'Suara Anda telah tercatat dan masuk ke statistik.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });

                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                } finally {
                    btnVote.disabled = false;
                    btnVote.innerHTML = originalText;
                }
            });
        }

        /** Helper function to display error messages */
        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: message,
                confirmButtonText: 'Mengerti'
            });
        }
    });
</script>



<!-- Core theme JS-->


</section><!-- /Hero Section -->

<!-- More Features Section -->
<section id="more-features" class="more-features section">

    <div class="container">

        <div class="row justify-content-around gy-4">

            <div class="col-lg-6 d-flex flex-column justify-content-center order-2 order-lg-1" data-aos="fade-up"
                data-aos-delay="100">
                <h1> Kandidat - <?= $calon['organisasi_name'] ?></h1>
                <h3><?= $calon['anggota_1_name'] ?> - <?= $calon['anggota_2_name'] ?></h3>
                <p><?= $calon['description'] ?></p>

                <!-- <div class="row">
                        <div class="col-lg-6 icon-box d-flex">
                            <div>
                                <h4>Biodata</h4>
                                <p><i class="bi bi-bank2 fs-6"></i>Prodi Sistem informasi</p>
                                <p><i class="bi bi-mortarboard fs-6"></i>Angkatan : 2023</p>

                            </div>
                        </div>
                        <div class="col-lg-6 icon-box d-flex">
                            <i class="bi bi-list-ul flex-shrink-0"></i>
                            <div>
                                <h4>Pengalaman Organisasi</h4>
                                <p>1.Wakil Himpunan Mahasiswa (HIMA) 2023
                                </p>
                                <p>2.Koordinator Divisi Akademik 2024</p>
                            </div>
                        </div>
                        <div class="col-lg-6 icon-box d-flex">
                            <i class="bi bi-rocket flex-shrink-0"></i>
                            <div>
                                <h4>Visi</h4>
                                <p>Mewujudkan BEM yang inklusif, progresif, dan berorientasi pada kepentingan
                                    mahasiswa.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 icon-box d-flex">
                            <i class="bi bi-bullseye flex-shrink-0"></i>
                            <div>
                                <h4>Misi</h4>
                                <p>1.Mengembangkan program-program inovatif</p>
                                <p>2.Meningkatkan keterlibatan Mahasiswa</p>
                                <p>3.Memperkuat kerjasama dengan stakeholders</p>
                            </div>
                        </div>
                    </div> -->
            </div>

            <div class="features-image col-lg-5 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                <img src="<?= base_url('uploads/' . $calon['gambar_1']) ?>" alt="" loading="lazy" decoding="async">
            </div>

        </div>

    </div>
    <div class="categories-content rounded-bottom p-4">
        <!-- Informasi Waktu Voting -->
        <?php if (isset($tanggalMulai) && isset($tanggalAkhir)): ?>
            <div class="alert alert-info mb-3" role="alert">
                <h6 class="alert-heading mb-2"><i class="bi bi-calendar-event"></i> <?= lang('Text.voting_time') ?></h6>
                <p class="mb-1"><strong><?= lang('Text.start') ?>:</strong>
                    <?= date('d F Y H:i', strtotime($tanggalMulai)) ?></p>
                <p class="mb-0"><strong><?= lang('Text.end') ?>:</strong> <?= date('d F Y H:i', strtotime($tanggalAkhir)) ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Pesan Status Voting -->
        <?php if (!$isVotingActive && !empty($votingMessage)): ?>
            <div class="alert alert-warning mb-3" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= $votingMessage ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center">
            <?php if ($isVotingActive): ?>
                <a href="#" id="voteButton" class="btn btn-sm rounded-pill d-flex justify-content-center py-2 px-4"
                    style="background-color: #980517; color: #fff; width: fit-content;" data-bs-toggle="modal"
                    data-bs-target="#modalCalon"
                    onclick="document.getElementById('pemilihan_calon_id').value = <?= $calon['id'] ?>;">
                    <?= lang('Text.vote_now') ?>
                </a>
            <?php else: ?>
                <button type="button" class="btn btn-sm rounded-pill d-flex justify-content-center py-2 px-4"
                    style="background-color: #6c757d; color: #fff; width: fit-content; cursor: not-allowed;" disabled>
                    <i class="bi bi-lock"></i> <?= lang('Text.voting_unavailable') ?>
                </button>
            <?php endif; ?>
        </div>
        <!-- Script untuk SweetAlert Form -->
    </div>
</section>
<!-- /More Features Section -->
<div class="modal fade" id="modalCalon" tabindex="-1" role="dialog" aria-labelledby="modalCalonLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center flex-column text-center">
                <div class="d-flex justify-content-center align-items-center">
                    <img src="<?= base_url('dist/landing/assets/img/Vector.png') ?>" class="img-fluid" alt="">
                </div>
                <h3 class="modal-title mt-3" id="modalCalonLabel" style="color:#980517"><?= lang('Text.e_voting') ?>
                </h3>
                <p><?= lang('Text.use_mobile_data') ?></p>
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!$isVotingActive): ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= $votingMessage ?? 'Voting tidak tersedia saat ini.' ?>
                    </div>
                <?php endif; ?>

                <form action="<?= url_to('vote') ?>" method="POST" enctype="multipart/form-data" id="voteForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="pemilihan_calon_id" id="pemilihan_calon_id">
                    <input type="hidden" class="form-control" id="kode_fakultas" name="kode_fakultas"
                        placeholder="Kode Fakultas" required>
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim"
                            placeholder="Masukkan 16 digit NIM (contoh: 4337857201230056)" style="color:#000;"
                            maxlength="24" required>
                        <small class="text-muted">Format: 43378 (57201) 230056 - Kode fakultas di posisi 6-10</small>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" style="color:#000;" name="name"
                            placeholder="Nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" style="color:#000;"
                            placeholder="Email Kampus" required>
                    </div>
                    <div class="mb-3">
                        <label for="otp" class="form-label"><?= lang('Text.otp_code') ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="otp" name="otp"
                                placeholder="Masukkan 6 digit OTP" maxlength="6" minlength="6" pattern="\d{6}"
                                style="color:#000;" <?= !$isVotingActive ? 'disabled' : 'disabled' ?>>
                            <button type="button" id="btnRequestOtp" class="btn"
                                style="background-color: #980517; color:#fff;" <?= !$isVotingActive ? 'disabled' : '' ?>><?= !$isVotingActive ? lang('Text.voting_unavailable') : lang('Text.send_otp') ?></button>
                        </div>
                        <small class="text-muted">Isi NIM, Nama, dan Email terlebih dahulu, lalu klik <strong>Kirim
                                OTP</strong> untuk mengirim kode ke email Anda.</small>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" id="btnVote" class="btn" style="background-color:#980517; color:#fff;"
                            <?= !$isVotingActive ? 'disabled' : '' ?>><?= !$isVotingActive ? lang('Text.voting_unavailable') : lang('Text.vote') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Library JS sudah di-load di layout dengan defer, tidak perlu duplikat -->
<script>
    // Tunggu Chart.js ter-load karena menggunakan defer
    document.addEventListener('DOMContentLoaded', function () {
        // Tunggu Chart.js library ter-load
        function initChart() {
            if (typeof Chart === 'undefined') {
                setTimeout(initChart, 100);
                return;
            }

            var ctxY = document.getElementById('chart-voting');
            if (!ctxY) return;

            ctxY = ctxY.getContext('2d');
            var labels = [];
            var datas = [];
            <?php foreach ($totalSuaras as $key => $val): ?>
                // Bersihkan nama kandidat dengan menghapus bagian setelah '-' jika ada
                var cleanLabel = '<?= $key ?>'.split('-')[0].trim();
                // Hapus teks 'Total suara:' jika ada
                cleanLabel = cleanLabel.replace('Total suara:', '').trim();
                labels.push(cleanLabel);
                datas.push(<?= $val ?>);
            <?php endforeach; ?>

            // Define colors for the pie chart (konsisten di semua halaman)
            var backgroundColors = [
                '#980517', // Maroon (original color)
                '#4A6FDC', // Blue
                '#2E8B57', // Sea Green
                '#FF8C00', // Dark Orange
                '#9932CC', // Dark Orchid
                '#FF6347', // Tomato
                '#20B2AA', // Light Sea Green
                '#FFD700', // Gold
                '#9370DB', // Medium Purple
                '#3CB371', // Medium Sea Green
                '#FF4500', // Orange Red
                '#4682B4', // Steel Blue
                '#32CD32', // Lime Green
                '#DA70D6', // Orchid
                '#F08080', // Light Coral
                '#1E90FF', // Dodger Blue
                '#9ACD32', // Yellow Green
                '#BA55D3', // Medium Orchid
                '#5F9EA0'  // Cadet Blue
            ];

            // Assign colors to each segment berdasarkan label untuk konsistensi
            // Buat mapping label ke warna agar label yang sama selalu mendapat warna yang sama
            // Menggunakan hash sederhana untuk menentukan warna berdasarkan nama kandidat
            var labelColorMap = {};

            // Fungsi untuk generate hash sederhana dari string
            function hashString(str) {
                var hash = 0;
                for (var i = 0; i < str.length; i++) {
                    var char = str.charCodeAt(i);
                    hash = ((hash << 5) - hash) + char;
                    hash = hash & hash; // Convert to 32bit integer
                }
                return Math.abs(hash);
            }

            // Assign warna berdasarkan hash nama kandidat untuk konsistensi
            labels.forEach(function (label) {
                if (!labelColorMap[label]) {
                    var hash = hashString(label);
                    var colorIndex = hash % backgroundColors.length;
                    labelColorMap[label] = backgroundColors[colorIndex];
                }
            });

            var pieColors = labels.map(function (label) {
                return labelColorMap[label] || backgroundColors[0];
            });

            var chartSuara4 = new Chart(ctxY, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: datas,
                        backgroundColor: pieColors,
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: window.innerWidth < 576 ? 'bottom' : 'right',
                            labels: {
                                font: {
                                    size: window.innerWidth < 576 ? 10 : 12,
                                    weight: 'bold'
                                },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                generateLabels: function (chart) {
                                    var data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map(function (label, i) {
                                            return {
                                                text: label + '\nTotal suara: ' + data.datasets[0].data[i],
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                hidden: false,
                                                lineWidth: 1,
                                                strokeStyle: '#ffffff',
                                                pointStyle: 'circle',
                                                font: {
                                                    size: window.innerWidth < 576 ? 10 : 12,
                                                    weight: 'bold'
                                                }
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: {
                                size: window.innerWidth < 768 ? 10 : 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: window.innerWidth < 768 ? 9 : 11
                            },
                            callbacks: {
                                label: function (context) {
                                    return 'Total suara: ' + context.raw;
                                },
                                afterLabel: function (context) {
                                    return ''; // Kosongkan bagian afterLabel
                                }
                            }
                        }
                    },
                    cutout: window.innerWidth < 576 ? '50%' : '30%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Handle window resize
            function handleResize() {
                const isMobile = window.innerWidth < 576;
                const isTablet = window.innerWidth < 768;

                chartSuara4.options.plugins.legend.position = isMobile ? 'bottom' : 'right';
                chartSuara4.options.plugins.legend.labels.font.size = isMobile ? 10 : (isTablet ? 11 : 12);
                chartSuara4.options.plugins.tooltip.titleFont.size = isTablet ? 10 : 12;
                chartSuara4.options.plugins.tooltip.bodyFont.size = isTablet ? 9 : 11;
                chartSuara4.options.cutout = isMobile ? '50%' : '30%';
                chartSuara4.update();
            }

            window.addEventListener('resize', handleResize);
            window.addEventListener('orientationchange', handleResize);
        }

        // Mulai inisialisasi chart
        initChart();
    });
</script>


<?= $this->endSection() ?>