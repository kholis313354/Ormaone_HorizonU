<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
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
        color: #fff;
        text-align: center;
    }

    h3 {
        color: #fff;
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
    }

    ol.organizational-chart {
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

    ol.organizational-chart li>div {
        background-color: #000;
        border-radius: 3px;
        min-height: 2em;
        padding: 0.5em;
    }

    /*** PRIMARY ***/
    ol.organizational-chart>li>div {
        background-color: #A01D1D;
        margin-right: 1em;
    }

    ol.organizational-chart>li>div:before {
        bottom: 2em;
        height: 3px;
        right: -1em;
        width: 1em;
    }

    ol.organizational-chart>li>div:first-of-type:after {
        bottom: 0;
        height: 2em;
        right: -1em;
        width: 3px;
    }

    ol.organizational-chart>li>div+div {
        margin-top: 1em;
    }

    ol.organizational-chart>li>div+div:after {
        height: calc(100% + 1em);
        right: -1em;
        top: -1em;
        width: 3px;
    }

    /*** SECONDARY ***/
    ol.organizational-chart>li>ol:before {
        left: inherit;
        right: 0;
    }

    ol.organizational-chart>li>ol:after {
        left: 0;
        width: 100%;
    }

    ol.organizational-chart>li>ol>li>div {
        background-color: #A01D1D;
        color: #fff !important;
    }

    /*** TERTIARY ***/
    ol.organizational-chart>li>ol>li>ol>li>div {
        background-color: #A01D1D;
    }

    /*** QUATERNARY ***/
    ol.organizational-chart>li>ol>li>ol>li>ol>li>div {
        background-color: #fca858;
    }

    /*** QUINARY ***/
    ol.organizational-chart>li>ol>li>ol>li>ol>li>ol>li>div {
        background-color: #fddc32;
    }

    /*** MEDIA QUERIES ***/
    @media only screen and (min-width: 64em) {
        ol.organizational-chart {
            margin-left: -1em;
            margin-right: -1em;
        }

        /* PRIMARY */
        ol.organizational-chart>li>div {
            display: inline-block;
            float: none;
            margin: 0 1em 1em 1em;
            vertical-align: bottom;
            width: 90% !important;
            height: 90%;
            color: red !important;
        }

        ol.organizational-chart>li>div:only-of-type {
            margin-bottom: 0;
            width: calc((100% / 1) - 2em - 4px);
            color: red;
        }

        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(2),
        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(2)~div {
            width: calc((100% / 2) - 2em - 4px);
            color: red;
        }

        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(3),
        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(3)~div {
            width: calc((100% / 3) - 2em - 4px);
        }

        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(4),
        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(4)~div {
            width: calc((100% / 4) - 2em - 4px);
        }

        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(5),
        ol.organizational-chart>li>div:first-of-type:nth-last-of-type(5)~div {
            width: calc((100% / 5) - 2em - 4px);
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

        /* SECONDARY */
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
    }

    .wr-text {
        color: #fff;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div id="wrapper">
    <div id="container">
        <ol class="organizational-chart">
            <li>
                <div>
                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="150px" height="150px"
                        alt="">
                    <h1>Ketua BEM Universitas</h1>
                    <h3>H.Sopian</h3>
                    <p>(Badan Eksekutif Mahasiswa)
                        Memiliki peran dan tanggung jawab yang sangat penting dalam organisasi kemahasiswaan.</p>
                    <p></p>
                    <p></p>
                </div>
                <br>
                <ol>
                    <li>
                        <div>
                            <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="150px" height="150px"
                                alt="">
                            <h1>Wakil BEM Universitas</h1>
                            <h3>H.Sopian</h3>
                            <p>(Badan Eksekutif Mahasiswa)
                                Memiliki peran dan tanggung jawab yang tidak kalah penting dalam mendukung Ketua BEM dan
                                memastikan
                                kelancaran organisasi.</p>
                            <p></p>
                            <p></p>
                        </div>
                    </li>
                </ol>
                <ol>
                    <li>
                        <div class="wr-text">
                            <div class="wr-text"></div>
                            <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px" height="90px"
                                alt="">
                            <h4 style="font-size: 15px; color:#fff;"> <b>Kepala Subbag Tata Usaha </b></h4>
                            <h4 style="font-size: 13px; color:#fff;">H.Yakub Lubis Al Pauji</h4>
                            <p>(Badan Eksekutif Mahasiswa)
                                Memiliki peran dan tanggung jawab yang tidak kalah penting dalam mendukung Ketua BEM dan
                                memastikan
                                kelancaran organisasi.</p>
                        </div>
                        <ol>

                            <li>
                                <div class="wr-text">
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>

                            </li>
                            <li>
                                <div class="wr-text">
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                        </ol>

                    </li>
                    <!-- -->
                    <li>
                        <div>
                            <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px" height="90px"
                                alt="">
                            <h4 style="font-size: 15px; color:#fff;"> <b>Kepala Seksi Pendidikan Madrasah</b></h4>
                            <h4 style="font-size: 13px; color:#fff;">H.Aab Abdulah</h4>
                            <p>(Badan Eksekutif Mahasiswa)
                                Memiliki peran dan tanggung jawab yang tidak kalah penting dalam mendukung Ketua BEM dan
                                memastikan
                                kelancaran organisasi.</p>
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 15px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;"></h4>kholis
                                </div>
                            </li>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                        </ol>
                    </li>
                    <!-- -->
                    <li>
                        <div>
                            <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px" height="90px"
                                alt="">
                            <h4 style="font-size: 14px; color:#fff;"><b>Kepala Seksi Pendidikan Diniyah & Ponpes</b>
                            </h4>
                            <h4 style="font-size: 13px; color:#fff;">H.Dadang Hamidi</h4>
                            <p>(Badan Eksekutif Mahasiswa)
                                Memiliki peran dan tanggung jawab yang tidak kalah penting dalam mendukung Ketua BEM dan
                                memastikan
                                kelancaran organisasi.</p>
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>

                        </ol>
                    </li>
                    <!-- -->
                    <li>
                        <div>
                            <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px" height="90px"
                                alt="">
                            <h4 style="font-size: 14px; color:#fff;"><b>Kepala Seksi Pendidikan Agama Islam</b></h4>
                            <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                            <p>(Badan Eksekutif Mahasiswa)
                                Memiliki peran dan tanggung jawab yang tidak kalah penting dalam mendukung Ketua BEM dan
                                memastikan
                                kelancaran organisasi.</p>
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                        </ol>
                    </li>
                    <!-- -->
                    <li>
                        <div>
                            <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                height="90px" alt="">
                            <h4 style="font-size: 15px; color:#fff;"> <b>Kepala Subbag Tata Usaha </b></h4>
                            <h4 style="font-size: 13px; color:#fff;">H.Yakub Lubis Al Pauji</h4>
                            <p>(Badan Eksekutif Mahasiswa)
                                Memiliki peran dan tanggung jawab yang tidak kalah penting dalam mendukung Ketua BEM dan
                                memastikan
                                kelancaran organisasi.</p>
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                        </ol>
                    </li>
                    <!-- -->
                    <li>
                        <div>
                            <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                height="90px" alt="">
                            <h4 style="font-size: 15px; color:#fff;"> <b>Kepala Subbag Tata Usaha </b></h4>
                            <h4 style="font-size: 13px; color:#fff;">H.Yakub Lubis Al Pauji</h4>
                            <p>(Badan Eksekutif Mahasiswa)
                                Memiliki peran dan tanggung jawab yang tidak kalah penting dalam mendukung Ketua BEM dan
                                memastikan
                                kelancaran organisasi.</p>
                        </div>
                        <ol>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <img src="<?= base_url('dist/landing/assets/img/kholis2.jpg') ?>" style="border-radius: 90%;" width="90px"
                                        height="90px" alt="">
                                    <h4 style="font-size: 14px; color:#fff;"><b>Anggota</b></h4>
                                    <h4 style="font-size: 13px; color:#fff;">Edi Junaedi</h4>
                                </div>
                            </li>
                        </ol>
                    </li>
                    <!-- -->
                </ol>
            </li>
        </ol>
    </div>
</div>
<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h2>Contact</h2>
</div><!-- End Section Title -->

<?= $this->endSection() ?>
