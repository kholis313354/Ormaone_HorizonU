<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- User Requested CSS Link -->
<link href="<?= base_url('dist/landing/assets/css/main1.css') ?>" rel="stylesheet">

<style>
    *,
    *:before,
    *:after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
    }

    h1 {
        color: #fca858;
        text-align: center;
    }

    h3 {
        color: #fca858;
    }

    #wrapper {
        margin: 150px auto;
        max-width: 80em;
    }

    p {
        color: #fff;
    }

    #container {
        float: left;
        padding: 1em;
        width: 100%;
    }

    /* MAIN CHART CSS - Preserving standard tree look */
    ol.organizational-chart,
    ol.organizational-chart ol,
    ol.organizational-chart li,
    ol.organizational-chart li>div {
        position: relative;
    }

    ol.organizational-chart,
    ol.organizational-chart ol {
        list-style: none;
        margin: 0;
        padding: 0;
        text-align: center;
    }

    ol.organizational-chart ol {
        padding-top: 1em;
    }

    ol.organizational-chart ol:before,
    ol.organizational-chart ol:after,
    ol.organizational-chart li:before,
    ol.organizational-chart li:after,
    ol.organizational-chart>li>div:before,
    ol.organizational-chart>li>div:after {
        background-color: #000;
        content: '';
        position: absolute;
    }

    ol.organizational-chart ol>li {
        padding: 1em 0 0 1em;
    }

    ol.organizational-chart>li ol:before {
        height: 1em;
        left: 50%;
        top: 0;
        width: 3px;
    }

    ol.organizational-chart>li ol:after {
        height: 3px;
        left: 3px;
        top: 1em;
        width: 50%;
    }

    ol.organizational-chart>li ol>li:not(:last-of-type):before {
        height: 3px;
        left: 0;
        top: 2em;
        width: 1em;
    }

    ol.organizational-chart>li ol>li:not(:last-of-type):after {
        height: 100%;
        left: 0;
        top: 0;
        width: 3px;
    }

    ol.organizational-chart>li ol>li:last-of-type:before {
        height: 3px;
        left: 0;
        top: 2em;
        width: 1em;
    }

    ol.organizational-chart>li ol>li:last-of-type:after {
        height: 2em;
        left: 0;
        top: 0;
        width: 3px;
    }

    /* CARD STYLING */
    ol.organizational-chart li>div {
        background-color: #A01D1D;
        border: 4px solid #A01D1D;
        border-radius: 18px;
        min-height: 2em;
        padding: 10px 10px 14px 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.18);
        width: 210px;
        max-width: 210px;
        margin: 0 auto;
        position: relative;
        overflow: visible;
        background: linear-gradient(to bottom, #A01D1D 0%, #A01D1D 75%, #fff 75%, #fff 100%);
    }

    /* BUDGET DISPLAY */
    .budget-tag {
        background: #A01D1D;
        color: #fff;
        display: inline-block;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(160, 29, 29, 0.3);
        margin: 5px;
        font-size: 0.9rem;
    }

    .budget-tag.spent {
        background: #fff;
        color: #A01D1D;
        border: 2px solid #A01D1D;
    }

    /* PROKER SECTION */
    .proker-section {
        margin-top: 80px;
        padding: 40px 20px;
        background: #fdfdfd;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .proker-title {
        color: #A01D1D;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 30px;
        text-align: center;
    }

    .proker-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .proker-card {
        background: #fff;
        border-radius: 15px;
        border: 1px solid #eee;
        padding: 20px;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .proker-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: #A01D1D;
    }

    .proker-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 4px;
        width: 100%;
        background: #A01D1D;
    }

    .proker-card .status-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 50px;
        margin-bottom: 15px;
        display: inline-block;
        width: fit-content;
    }

    .status-coming {
        background: #e2e8f0;
        color: #475569;
    }

    .status-progres {
        background: #fef3c7;
        color: #92400e;
    }

    .status-finish {
        background: #dcfce7;
        color: #166534;
    }

    .proker-card h4 {
        color: #333;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .proker-card .desc {
        color: #666;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 15px;
        flex-grow: 1;
    }

    .proker-card .budget {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #444;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .proker-card .budget i {
        color: #A01D1D;
        margin-right: 8px;
    }

    .proker-link {
        color: #A01D1D;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: opacity 0.2s;
    }

    .proker-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }

    ol.organizational-chart li>div>div:first-child {
        position: relative;
        margin-top: -10px;
        margin-bottom: 0;
        z-index: 5;
    }

    .photo-container {
        margin: -10px auto 6px auto;
        overflow: hidden;
        border-radius: 14px;
        border: 3px solid #A01D1D;
        position: relative;
        z-index: 5;
        background: #A01D1D;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .photo-president {
        width: 120px;
        height: 120px;
    }

    .photo-vp {
        width: 110px;
        height: 110px;
    }

    .photo-other {
        width: 100px;
        height: 100px;
    }

    .photo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
        border-radius: 5px;
    }

    ol.organizational-chart li>div .card-content {
        padding: 12px 4px 10px 4px;
        background: transparent;
        min-height: 105px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    ol.organizational-chart>li>div:first-of-type .card-content {
        padding-bottom: 16px;
    }

    ol.organizational-chart li>div .card-content h3 {
        color: #FFFFFF;
        font-weight: 600;
        margin-bottom: 10px;
        margin-top: 0;
        text-align: center;
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .jabatan-wrapper {
        background-color: #FFFFFF;
        border-radius: 999px;
        padding: 4px 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 85%;
        max-width: 180px;
        margin: 6px auto 0 auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.18);
        cursor: pointer;
        margin-top: auto;
    }

    .jabatan-button {
        background-color: #A01D1D;
        color: #fff;
        padding: 6px 22px;
        border-radius: 999px;
        text-align: center;
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: none;
    }

    .jabatan-button:hover {
        background-color: #8a1919;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .jabatan-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /*** MEDIA QUERIES FOR MAIN CHART ***/
    @media only screen and (min-width: 64em) {
        ol.organizational-chart {
            margin-left: -1em;
            margin-right: -1em;
        }

        ol.organizational-chart>li>div {
            display: inline-block;
            float: none;
            margin: 0 1em 1em 1em;
            vertical-align: bottom;
        }

        ol.organizational-chart>li>div:only-of-type {
            margin-bottom: 0;
        }

        ol.organizational-chart>li>div:before,
        ol.organizational-chart>li>div:after {
            bottom: -1em !important;
            top: inherit !important;
        }

        ol.organizational-chart>li>div:before {
            height: 1em !important;
            left: 50% !important;
            width: 3px !important;
        }

        ol.organizational-chart>li>div:only-of-type:after {
            display: none;
        }

        ol.organizational-chart>li>div:first-of-type:not(:only-of-type):after,
        ol.organizational-chart>li>div:last-of-type:not(:only-of-type):after {
            bottom: -1em;
            height: 3px;
            width: calc(50% + 1em + 3px);
        }

        ol.organizational-chart>li>div:first-of-type:not(:only-of-type):after {
            left: calc(50% + 3px);
        }

        ol.organizational-chart>li>div:last-of-type:not(:only-of-type):after {
            left: calc(-1em - 3px);
        }

        ol.organizational-chart>li>div+div:not(:last-of-type):after {
            height: 3px;
            left: -2em;
            width: calc(100% + 4em);
        }

        ol.organizational-chart>li>ol {
            display: flex;
            flex-wrap: nowrap;
        }

        ol.organizational-chart>li>ol:before,
        ol.organizational-chart>li>ol>li:before {
            height: 1em !important;
            left: 50% !important;
            top: 0 !important;
            width: 3px !important;
        }

        ol.organizational-chart>li>ol:after {
            display: none;
        }

        ol.organizational-chart>li>ol>li {
            flex-grow: 1;
            padding-left: 1em;
            padding-right: 1em;
            padding-top: 1em;
        }

        ol.organizational-chart>li>ol>li:only-of-type {
            padding-top: 0;
        }

        ol.organizational-chart>li>ol>li:only-of-type:before,
        ol.organizational-chart>li>ol>li:only-of-type:after {
            display: none;
        }

        ol.organizational-chart>li>ol>li:first-of-type:not(:only-of-type):after,
        ol.organizational-chart>li>ol>li:last-of-type:not(:only-of-type):after {
            height: 3px;
            top: 0;
            width: 50%;
        }

        ol.organizational-chart>li>ol>li:first-of-type:not(:only-of-type):after {
            left: 50%;
        }

        ol.organizational-chart>li>ol>li:last-of-type:not(:only-of-type):after {
            left: 0;
        }

        ol.organizational-chart>li>ol>li+li:not(:last-of-type):after {
            height: 3px;
            left: 0;
            top: 0;
            width: 100%;
        }

        ol.organizational-chart>li>ol:nth-of-type(2)>li:nth-child(2)::before {
            background-color: transparent !important;
            width: 0 !important;
        }

        ol.organizational-chart>li>ol:nth-of-type(2)>li:nth-child(2)>div::before {
            content: '';
            position: absolute;
            width: 3px;
            height: 1.5em;
            background-color: #000;
            top: -1.5em;
            left: calc(50% + 0.1px);
        }
    }

    /** DIVISION CHART OVERRIDES **/
    @media only screen and (min-width: 64em) {
        .division-chart>li>ol {
            display: flex !important;
            justify-content: center;
            margin: 0 auto;
        }

        /* Fix for 1-to-1 Vertical Lines (Single Child Row) */

        /* 1. Line Leaving Parent */
        .division-chart>li>ol>li>div::after {
            content: '';
            position: absolute;
            bottom: -1em;
            /* Extend down */
            left: 50%;
            height: 1em;
            /* Reach halfway */
            width: 3px;
            background-color: #000;
            transform: translateX(-50%);
            display: block !important;
            z-index: 10;
        }

        /* 2. Line Entering Child (Nested OL) */
        .division-chart>li>ol>li>ol::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            height: 1em;
            width: 3px;
            background-color: #000;
            transform: translateX(-50%);
            display: block !important;
        }

        /* 3. Disable any horizontal elbows on that single-child OL */
        .division-chart>li>ol>li>ol>li::before {
            display: block !important;
            /* Force show even if only-of-type */
            height: 1em !important;
            width: 3px !important;
            top: 0 !important;
            left: 50% !important;
        }

        /* STRICTLY HIDE Horizontal Spanners for Single Child */
        .division-chart>li>ol>li>ol>li::after,
        .division-chart>li>ol>li>ol::after {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
    }

    /* MOBILE / TABLET RESET (Responsive < 1024px) */
    @media only screen and (max-width: 1024px) {
        .photo-president {
            width: 100px;
            height: 100px;
        }

        .photo-vp {
            width: 95px;
            height: 95px;
        }

        .photo-other {
            width: 85px;
            height: 85px;
        }

        /* 1. Reset Internal Spacing for Stacking */
        ol.organizational-chart,
        ol.organizational-chart ol {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        ol.organizational-chart>li>div,
        ol.organizational-chart li>div {
            width: 210px !important;
            margin: 0 auto;
        }

        /* 2. Global Stack: Force all lists to block/stack */
        ol.organizational-chart ol,
        ol.organizational-chart li,
        ol.organizational-chart>li>ol,
        .division-chart>li>ol,
        .division-chart>li>ol>li>ol {
            display: block !important;
            width: 100% !important;
            padding-top: 0 !important;
        }

        ol.organizational-chart ol {
            margin-top: 30px !important;
        }

        ol.organizational-chart li {
            margin: 0 auto 30px auto !important;
            /* Vertical Gap */
            position: relative;
            padding: 0 !important;
        }

        /* 3. CLEANUP: Hide ALL default tree lines (elbows, horizontal bars) */
        ol.organizational-chart ol::before,
        ol.organizational-chart ol::after,
        ol.organizational-chart li::before,
        ol.organizational-chart li::after,
        ol.organizational-chart>li>div::before,
        ol.organizational-chart>li>div::after,
        /* Reset default card lines */
        .division-chart ol::before,
        .division-chart li::before,
        .division-chart li::after,
        .division-chart div::before {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            content: none !important;
        }

        /* 4. NEW MOBILE LINES: Simple Downward Vertical Line for EVERY Card */
        /* Apply to ALL cards inside the organizational-chart */
        /* 4. NEW MOBILE LINES: Simple Downward Vertical Line for EVERY Card */
        /* Apply to ALL cards inside the organizational-chart, including roots explicitly */
        ol.organizational-chart li>div::after,
        ol.organizational-chart>li>div::after,
        .division-chart>li>div::after {
            content: '' !important;
            position: absolute;
            bottom: -30px !important;
            /* Reach exactly across the 30px margin gap */
            left: 50%;
            height: 31px !important;
            /* +1px to ensure connection/overlap */
            width: 3px;
            background-color: #000;
            transform: translateX(-50%);
            display: block !important;
            z-index: 5;
        }

        /* 5. EXCEPTIONS: Hide the line for specific last elements */

        /* Hide line for bottom-row Division Members (5,6,7,8) */
        .division-chart>li>ol>li>ol>li>div::after {
            display: none !important;
        }

        /* Attempt to hide line for last child in main list if it's a leaf? */
        /* E.g. Auditor/Jabatan 6 in main chart. */
        ol.organizational-chart>li>ol>li>ol>li:last-child>div::after {
            /* display: none !important; */
            /* Let's keep it consistent: Any card followed by another card via separate list will have a line. 
               The last card in a sequence will dangle. 
               We can manually hide the very last dangling lines if needed, 
               but "connected" is the request. 
               The user screenshot shows lines everywhere. 
            */
        }
    }

    .prodi-text {
        color: #fff;
        font-size: 0.75rem;
        margin: -5px 0 8px 0;
        text-align: center;
        width: 100%;
        line-height: 1.2;
        font-weight: 400;
        opacity: 0.9;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
if (!function_exists('esc')) {
    function esc($data, $context = 'html')
    {
        return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
    }
}
function getDefaultImage()
{
    return base_url('dist/landing/assets/img/kholis2.jpg');
}

$encryptedOrganisasiId = null;
try {
    if (!empty($organisasiId)) {
        $encrypter = \Config\Services::encrypter();
        $encryptedOrganisasiId = bin2hex($encrypter->encrypt((string) $organisasiId));
    }
} catch (\Throwable $e) {
    $encryptedOrganisasiId = $organisasiId;
}

$periodeTitle = '';
if (!empty($struktur)) {
    if (!empty($struktur['periode'])) {
        $periodeTitle = $struktur['periode'];
    } elseif (!empty($organisasi['name'])) {
        $periodeTitle = $organisasi['name'];
    } else {
        $periodeTitle = 'Struktur Organisasi';
    }
    if (!empty($struktur['tahun'])) {
        $periodeTitle .= ' ' . $struktur['tahun'];
    }
} elseif (!empty($organisasi['name'])) {
    $periodeTitle = $organisasi['name'];
}
?>

<div id="wrapper">
    <!-- Filter Section -->
    <?php if (!empty($organisasiId) && !empty($availableYears)): ?>
        <div class="container" style="margin-top: 100px; margin-bottom: 20px;">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card" style="border-radius: 8px;">
                        <div class="card-body" style="padding: 0.75rem;">
                            <h6 class="card-title" style="font-size: 0.9rem; margin-bottom: 0.75rem; font-weight: 600;">
                                <?= lang('Text.filter_tenure') ?>
                            </h6>
                            <form method="GET" action="<?= url_to('struktur') ?>">
                                <input type="hidden" name="org" value="<?= esc($encryptedOrganisasiId ?? '') ?>">
                                <div class="row g-2">
                                    <div class="col-8">
                                        <select name="tahun" id="tahun-filter" class="form-select form-select-sm"
                                            onchange="this.form.submit()" style="font-size: 0.875rem;">
                                            <option value=""><?= lang('Text.select_year') ?></option>
                                            <?php foreach ($availableYears as $yearItem): ?>
                                                <option value="<?= $yearItem['tahun'] ?>" <?= ($tahun == $yearItem['tahun']) ? 'selected' : '' ?>>
                                                    <?= esc($yearItem['tahun']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <a href="<?= url_to('struktur') ?>?org=<?= $encryptedOrganisasiId ?>"
                                            class="btn btn-secondary btn-sm w-100"
                                            style="font-size: 0.875rem;"><?= lang('Text.reset') ?></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div id="container">
        <!-- HEADER -->
        <div style="text-align: center; margin-bottom: 60px; margin-top: 50px;">
            <h1 style="color: #A01D1D; font-size: 2.5rem; font-weight: 600; margin-bottom: 10px; line-height: 1.2;">
                <?= esc($periodeTitle) ?>
            </h1>
            <?php if (!empty($anggaran)): ?>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                    <div class="budget-tag">
                        <i class="bi bi-wallet2 me-2"></i>
                        Total Anggaran: Rp <?= number_format($anggaran['jumlah'], 0, ',', '.') ?>
                    </div>
                    <div class="budget-tag spent">
                        <i class="bi bi-cash-stack me-2"></i>
                        Dana Terpakai: Rp <?= number_format($anggaran['dana_berkurang'], 0, ',', '.') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php
        // Logic check
        $hasStrukturData = false;
        if (!empty($struktur) && is_array($struktur)) {
            $hasStrukturData = !empty($struktur['nama_1']) || !empty($struktur['nama_2']) || !empty($struktur['nama_3']);
        }
        ?>

        <?php if ($hasStrukturData): ?>
            <!-- USER REQUESTED MAIN CHART BLOCK -->
            <ol class="organizational-chart">
                <li>
                    <!-- Posisi 1: President -->
                    <?php if (!empty($struktur['nama_1'])): ?>
                        <div>
                            <?php $gambar1 = !empty($struktur['gambar_1']) ? base_url('uploads/struktur/' . $struktur['gambar_1']) : getDefaultImage(); ?>
                            <div class="photo-container photo-president">
                                <img src="<?= $gambar1 ?>" alt="<?= esc($struktur['nama_1']) ?>" class="photo-img"
                                    loading="lazy">
                            </div>
                            <div class="card-content">
                                <h3 style="font-size: 0.9rem;"><?= esc($struktur['nama_1']) ?></h3>
                                <p class="prodi-text"><?= esc($struktur['prodi_1'] ?? '') ?></p>
                                <div class="jabatan-wrapper">
                                    <button type="button" class="jabatan-button"
                                        onclick="handleJabatanClick('<?= esc($struktur['jabatan_1'] ?? '', 'js') ?>', '<?= esc($struktur['nama_1'], 'js') ?>')"><?= esc($struktur['jabatan_1'] ?? '') ?></button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <ol>
                        <!-- Posisi 2: Vice President -->
                        <?php if (!empty($struktur['nama_2'])): ?>
                            <li>
                                <div>
                                    <?php $gambar2 = !empty($struktur['gambar_2']) ? base_url('uploads/struktur/' . $struktur['gambar_2']) : getDefaultImage(); ?>
                                    <div class="photo-container photo-vp">
                                        <img src="<?= $gambar2 ?>" alt="<?= esc($struktur['nama_2']) ?>" class="photo-img"
                                            loading="lazy">
                                    </div>
                                    <div class="card-content">
                                        <h3 style="font-size: 0.9rem;"><?= esc($struktur['nama_2']) ?></h3>
                                        <p class="prodi-text"><?= esc($struktur['prodi_2'] ?? '') ?></p>
                                        <div class="jabatan-wrapper">
                                            <button type="button" class="jabatan-button"
                                                onclick="handleJabatanClick('<?= esc($struktur['jabatan_2'] ?? '', 'js') ?>', '<?= esc($struktur['nama_2'], 'js') ?>')"><?= esc($struktur['jabatan_2'] ?? '') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ol>

                    <ol>
                        <!-- Posisi 3: Secretary -->
                        <?php if (!empty($struktur['nama_3'])): ?>
                            <li>
                                <div class="wr-text">
                                    <?php $gambar3 = !empty($struktur['gambar_3']) ? base_url('uploads/struktur/' . $struktur['gambar_3']) : getDefaultImage(); ?>
                                    <div class="photo-container photo-other">
                                        <img src="<?= $gambar3 ?>" alt="<?= esc($struktur['nama_3']) ?>" class="photo-img"
                                            loading="lazy">
                                    </div>
                                    <div class="card-content">
                                        <h3 style="font-size: 0.85rem;"><?= esc($struktur['nama_3']) ?></h3>
                                        <p class="prodi-text"><?= esc($struktur['prodi_3'] ?? '') ?></p>
                                        <div class="jabatan-wrapper">
                                            <button type="button" class="jabatan-button"
                                                onclick="handleJabatanClick('<?= esc($struktur['jabatan_3'] ?? '', 'js') ?>', '<?= esc($struktur['nama_3'], 'js') ?>')"><?= esc($struktur['jabatan_3'] ?? '') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>

                        <!-- Posisi 4: Treasurer -->
                        <?php if (!empty($struktur['nama_4'])): ?>
                            <li>
                                <div class="wr-text">
                                    <?php $gambar4 = !empty($struktur['gambar_4']) ? base_url('uploads/struktur/' . $struktur['gambar_4']) : getDefaultImage(); ?>
                                    <div class="photo-container photo-other">
                                        <img src="<?= $gambar4 ?>" alt="<?= esc($struktur['nama_4']) ?>" class="photo-img"
                                            loading="lazy">
                                    </div>
                                    <div class="card-content">
                                        <h3 style="font-size: 0.85rem;"><?= esc($struktur['nama_4']) ?></h3>
                                        <p class="prodi-text"><?= esc($struktur['prodi_4'] ?? '') ?></p>
                                        <div class="jabatan-wrapper">
                                            <button type="button" class="jabatan-button"
                                                onclick="handleJabatanClick('<?= esc($struktur['jabatan_4'] ?? '', 'js') ?>', '<?= esc($struktur['nama_4'], 'js') ?>')"><?= esc($struktur['jabatan_4'] ?? '') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>

                        <!-- Posisi 5: PRO -->
                        <?php if (!empty($struktur['nama_5'])): ?>
                            <li>
                                <div class="wr-text">
                                    <?php $gambar5 = !empty($struktur['gambar_5']) ? base_url('uploads/struktur/' . $struktur['gambar_5']) : getDefaultImage(); ?>
                                    <div class="photo-container photo-other">
                                        <img src="<?= $gambar5 ?>" alt="<?= esc($struktur['nama_5']) ?>" class="photo-img"
                                            loading="lazy">
                                    </div>
                                    <div class="card-content">
                                        <h3 style="font-size: 0.85rem;"><?= esc($struktur['nama_5']) ?></h3>
                                        <p class="prodi-text"><?= esc($struktur['prodi_5'] ?? '') ?></p>
                                        <div class="jabatan-wrapper">
                                            <button type="button" class="jabatan-button"
                                                onclick="handleJabatanClick('<?= esc($struktur['jabatan_5'] ?? '', 'js') ?>', '<?= esc($struktur['nama_5'], 'js') ?>')"><?= esc($struktur['jabatan_5'] ?? '') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>

                        <!-- Posisi 6: Jabatan 6 -->
                        <?php if (!empty($struktur['nama_6'])): ?>
                            <li>
                                <div class="wr-text">
                                    <?php $gambar6 = !empty($struktur['gambar_6']) ? base_url('uploads/struktur/' . $struktur['gambar_6']) : getDefaultImage(); ?>
                                    <div class="photo-container photo-other">
                                        <img src="<?= $gambar6 ?>" alt="<?= esc($struktur['nama_6']) ?>" class="photo-img"
                                            loading="lazy">
                                    </div>
                                    <div class="card-content">
                                        <h3 style="font-size: 0.85rem;"><?= esc($struktur['nama_6']) ?></h3>
                                        <p class="prodi-text"><?= esc($struktur['prodi_6'] ?? '') ?></p>
                                        <div class="jabatan-wrapper">
                                            <button type="button" class="jabatan-button"
                                                onclick="handleJabatanClick('<?= esc($struktur['jabatan_6'] ?? '', 'js') ?>', '<?= esc($struktur['nama_6'], 'js') ?>')"><?= esc($struktur['jabatan_6'] ?? '') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ol>
                </li>
            </ol>
            <!-- END USER REQUESTED BLOCK -->

        <?php else: ?>
                <div style="text-align: center; padding: 50px;">
                    <h2 style="color: #A01D1D;"><?= lang('Text.structure') ?></h2>
                    <p style="color: #666;"><?= lang('Text.structure_not_available') ?></p>
                    <?php if (empty($organisasiId)): ?>
                            <p><a href="<?= url_to('struktur') ?>"><?= lang('Text.back_to_structure') ?></a></p>
                    <?php endif; ?>
                </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function handleJabatanClick(jabatan, nama) {
        console.log('Jabatan diklik:', jabatan, 'Nama:', nama);
        alert('Jabatan: ' + jabatan + '\nNama: ' + nama);
    }
</script>

<!-- DIVISION SECTION START -->
<?php if (!empty($divisi) && is_array($divisi)): ?>
        <?php foreach ($divisi as $div): ?>
                <div class="container section-title" data-aos="fade-up" style="margin-top: 50px;">
                    <h2>Struktur Divisi - <?= esc($div['nama_divisi']) ?></h2>
                </div>

                <div class="org-wrapper">
                    <div class="org-container">
                        <ol class="organizational-chart division-chart">
                            <li>
                                <!-- Ketua Divisi -->
                                <div>
                                    <?php $gambarKetua = !empty($div['gambar_ketua']) ? base_url('uploads/struktur/divisi/' . $div['gambar_ketua']) : getDefaultImage(); ?>
                                    <div class="photo-container photo-president">
                                        <img src="<?= $gambarKetua ?>" class="photo-img" loading="lazy">
                                    </div>
                                    <div class="card-content">
                                        <h3><?= esc($div['nama_ketua']) ?></h3>
                                        <p class="prodi-text"><?= esc($div['prodi_ketua'] ?? '') ?></p>
                                        <div class="jabatan-wrapper">
                                            <button type="button"
                                                class="jabatan-button"><?= esc($div['jabatan_ketua'] ?? 'Ketua Divisi') ?></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nested Members Tree (Row 1 parents of Row 2) -->
                                <ol>
                                    <?php
                                    // Only iterate 1 to 4. Each may have a child from 5 to 8.
                                    // If there are more than 4 members, they are mapped as children.
                                    // Member 1 -> Member 5
                                    // Member 2 -> Member 6, etc.
                                    for ($i = 1; $i <= 4; $i++):
                                        if (!empty($div['nama_anggota_' . $i])):
                                            ?>
                                                    <li>
                                                        <div>
                                                            <?php $gambarAnggota = !empty($div['gambar_anggota_' . $i]) ? base_url('uploads/struktur/divisi/' . $div['gambar_anggota_' . $i]) : getDefaultImage(); ?>
                                                            <div class="photo-container photo-other">
                                                                <img src="<?= $gambarAnggota ?>" class="photo-img" loading="lazy">
                                                            </div>
                                                            <div class="card-content">
                                                                <h3><?= esc($div['nama_anggota_' . $i]) ?></h3>
                                                                <p class="prodi-text"><?= esc($div['prodi_anggota_' . $i] ?? '') ?></p>
                                                                <div class="jabatan-wrapper">
                                                                    <button type="button"
                                                                        class="jabatan-button"><?= esc($div['jabatan_anggota_' . $i] ?? 'Anggota') ?></button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Child Row (Member 5-8) -->
                                                        <?php
                                                        $childIndex = $i + 4;
                                                        if ($childIndex <= 8 && !empty($div['nama_anggota_' . $childIndex])):
                                                            ?>
                                                                <ol>
                                                                    <li>
                                                                        <div>
                                                                            <?php $gambarChild = !empty($div['gambar_anggota_' . $childIndex]) ? base_url('uploads/struktur/divisi/' . $div['gambar_anggota_' . $childIndex]) : getDefaultImage(); ?>
                                                                            <div class="photo-container photo-other">
                                                                                <img src="<?= $gambarChild ?>" class="photo-img" loading="lazy">
                                                                            </div>
                                                                            <div class="card-content">
                                                                                <h3><?= esc($div['nama_anggota_' . $childIndex]) ?></h3>
                                                                                <p class="prodi-text"><?= esc($div['prodi_anggota_' . $childIndex] ?? '') ?></p>
                                                                                <div class="jabatan-wrapper">
                                                                                    <button type="button"
                                                                                        class="jabatan-button"><?= esc($div['jabatan_anggota_' . $childIndex] ?? 'Anggota') ?></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ol>
                                                        <?php endif; ?>
                                                    </li>
                                            <?php endif; ?>
                                    <?php endfor; ?>
                                </ol>
                            </li>
                        </ol>
                    </div>
                </div>
        <?php endforeach; ?>

<?php else: ?>
        <div class="container section-title" data-aos="fade-up" style="margin-top: 50px;">
            <h2>Struktur Divisi</h2>
            <p class="text-muted mt-3">Data struktur divisi belum tersedia.</p>
        </div>
<?php endif; ?>
<!-- DIVISION SECTION END -->

<!-- VISI MISI SECTION START -->
<?php if (!empty($visiMisi)): ?>
        <div class="container section-title" data-aos="fade-up" style="margin-top: 50px;">
            <h2>Visi & Misi</h2>
        </div>

        <div class="container mb-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h3 style="color: #A01D1D; font-weight: 700;">VISI</h3>
                                <p class="lead" style="color: #444; font-style: italic;">
                                    "<?= nl2br(esc($visiMisi['visi'])) ?>"
                                </p>
                            </div>

                            <hr class="my-4">

                            <div class="mt-4">
                                <h3 class="text-center mb-4" style="color: #A01D1D; font-weight: 700;">MISI</h3>
                                <div style="color: #444; line-height: 1.8;">
                                    <?= nl2br(esc($visiMisi['misi'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php else: ?>
        <div class="container section-title" data-aos="fade-up" style="margin-top: 50px;">
            <h2>Visi & Misi</h2>
            <p class="text-muted mt-3">Data Visi & Misi belum tersedia.</p>
        </div>
<?php endif; ?>
<!-- VISI MISI SECTION END -->

            <!-- PROKER SECTION START -->
            <?php if (!empty($proker) && !empty($proker['deskripsi'])): ?>
                <div class="container" style="margin-top: 80px; text-align: center;">
                    <h2 style="color: #A01D1D; font-weight: 600; font-size: 2rem;">
                        <?= esc($proker['judul'] ?? 'Program Kerja') ?></h2>
                    <div style="width: 60px; height: 3px; background: #A01D1D; margin: 10px auto 40px auto;"></div>
                </div>

                <div class="container mb-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="row">
                                <?php
                                $prokerList = json_decode($proker['deskripsi'], true);
                                if ($prokerList):
                                    foreach ($prokerList as $item):
                                        $statusClass = '';
                                        $statusLabel = $item['status'] ?? 'Coming soon';
                                        switch ($statusLabel) {
                                            case 'Progres':
                                                $statusClass = 'status-progres';
                                                break;
                                            case 'Finish':
                                                $statusClass = 'status-finish';
                                                break;
                                            default:
                                                $statusClass = 'status-coming';
                                                break;
                                        }
                                        ?>
                                        <div class="col-md-6 mb-4">
                                            <div class="card shadow-sm h-100"
                                                style="border-left: 4px solid #A01D1D; border-radius: 12px; transition: transform 0.3s ease;">
                                                <div class="card-body p-4">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h5 class="card-title mb-0"
                                                            style="color: #A01D1D; font-weight: 700; font-size: 1.1rem; line-height: 1.4;">
                                                            <?= esc($item['program']) ?>
                                                        </h5>
                                                        <span class="status-badge <?= $statusClass ?>"
                                                            style="font-size: 0.65rem; padding: 4px 10px;"><?= esc($statusLabel) ?></span>
                                                    </div>

                                                    <?php if (!empty($item['keterangan'])): ?>
                                                        <p class="card-text text-muted small mt-2 mb-3">
                                                            <?= nl2br(esc($item['keterangan'])) ?></p>
                                                    <?php endif; ?>

                                                    <div class="d-flex flex-column gap-2 mt-auto">
                                                        <div class="d-flex align-items-center text-dark"
                                                            style="font-size: 0.85rem; font-weight: 600;">
                                                            <i class="bi bi-tag-fill me-2 text-danger"></i>
                                                            Rp <?= number_format($item['dana_berkurang'] ?? 0, 0, ',', '.') ?>
                                                        </div>

                                                        <?php if (!empty($item['link_berita'])): ?>
                                                            <a href="<?= esc($item['link_berita']) ?>" target="_blank" class="proker-link"
                                                                style="font-size: 0.8rem;">
                                                                <i class="bi bi-link-45deg me-1"></i> Baca Berita Selengkapnya
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="container" style="margin-top: 80px; text-align: center;">
                    <h2 style="color: #A01D1D; font-weight: 600; font-size: 2rem;">Program Kerja</h2>
                    <div style="width: 60px; height: 3px; background: #A01D1D; margin: 10px auto 20px auto;"></div>
                    <p class="text-muted">Data program kerja belum tersedia.</p>
                </div>
            <?php endif; ?>
            <!-- PROKER SECTION END -->


<?= $this->endSection() ?>