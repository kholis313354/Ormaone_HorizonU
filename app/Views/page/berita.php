<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <h1 class="fw-bolder">Welcome to Blog Ormaone</h1>
            <p class="lead mb-0">Program - program Kegiatan dari Semua Organisasi Mahasiswa di Universitas Horizon
                Indonesia </p>
        </div>
    </div>
</header>
<!-- Page content-->
<div class="container">
    <div class="row">
        <!-- Blog entries-->
        <div class="col-lg-8">
            <!-- Featured blog post-->
            <div class="card mb-4">
                <a href="#!"><img class="card-img-top" src="<?= base_url('dist/landing/assets/img/horizon.jpeg') ?>" alt="..." /></a>
                <div class="card-body">
                    <div class="small text-muted">Desember 3, 2024</div>
                    <h2 class="card-title">Workshop Teknologi AI</h2>
                    <p class="card-text">Pembahasan mendalamtentang perkembangan teknologi terkini dan dampaknya.
                    </p>
                    <a class="btn" style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;"
                        href="berita_detail.html">Baca
                        →</a>
                </div>
            </div>
            <!-- Nested row for non-featured blog posts-->
            <div class="row">
                <div class="col-lg-6">
                    <!-- Blog post-->
                    <div class="card mb-4">
                        <a href="#!"><img class="card-img-top" src="<?= base_url('dist/landing/assets/img/horizon.jpeg') ?>"
                                alt="..." /></a>
                        <div class="card-body">
                            <div class="small text-muted">Desember 3, 2024</div>
                            <h2 class="card-title h4">Workshop Teknologi AI</h2>
                            <p class="card-text">Pembahasan mendalamtentang perkembangan teknologi terkini dan
                                dampaknya.</p>
                            <a class="btn" style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;"
                                href="berita_detail.html">Baca →</a>
                        </div>
                    </div>
                    <!-- Blog post-->
                    <div class="card mb-4">
                        <a href="#!"><img class="card-img-top" src="<?= base_url('dist/landing/assets/img/horizon.jpeg') ?>"
                                alt="..." /></a>
                        <div class="card-body">
                            <div class="small text-muted">Desember 3, 2024</div>
                            <h2 class="card-title h4">Workshop Teknologi AI</h2>
                            <p class="card-text">Pembahasan mendalamtentang perkembangan teknologi terkini dan
                                dampaknya.
                            </p>
                            <a class="btn" style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;"
                                href="berita_detail.html">Baca →</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Blog post-->
                    <div class="card mb-4">
                        <a href="#!"><img class="card-img-top" src="<?= base_url('dist/landing/assets/img/horizon.jpeg') ?>"
                                alt="..." /></a>
                        <div class="card-body">
                            <div class="small text-muted">Desember 3, 2024</div>
                            <h2 class="card-title h4">Workshop Teknologi AI</h2>
                            <p class="card-text">Pembahasan mendalamtentang perkembangan teknologi terkini dan
                                dampaknya.</p>
                            <a class="btn " style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;"
                                href="berita_detail.html">Baca →</a>
                        </div>
                    </div>
                    <!-- Blog post-->
                    <div class="card mb-4">
                        <a href="#!"><img class="card-img-top" src="<?= base_url('dist/landing/assets/img/horizon.jpeg') ?>"
                                alt="..." /></a>
                        <div class="card-body">
                            <div class="small text-muted">Desember 3, 2024</div>
                            <h2 class="card-title h4">Workshop Teknologi AI</h2>
                            <p class="card-text">Pembahasan mendalamtentang perkembangan teknologi terkini dan
                                dampaknya.</p>
                            <a class="btn" style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;"
                                href="berita_detail.html">Baca →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Side widgets-->
        <div class="col-lg-4">
            <!-- Search widget-->
            <div class="card mb-4">
                <div class="card-header">Search</div>
                <div class="card-body">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Enter search term..."
                            aria-label="Enter search term..." aria-describedby="button-search" />
                        <button class="btn " style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;"
                            id="button-search" type="button">Go!</button>
                    </div>
                </div>
            </div>
            <!-- Categories widget-->
            <div class="card mb-4">
                <div class="card-header">Categories</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#!">Web Design</a></li>
                                <li><a href="#!">HTML</a></li>
                                <li><a href="#!">Freebies</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#!">JavaScript</a></li>
                                <li><a href="#!">CSS</a></li>
                                <li><a href="#!">Tutorials</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Side widget-->
            <div class="card mb-4">
                <div class="card-header">Side Widget</div>
                <div class="card-body">You can put anything you want inside of these side widgets. They are easy to
                    use, and
                    feature the Bootstrap 5 card component!</div>
            </div>
        </div>
    </div>
    <?= $this->endSection(); ?>