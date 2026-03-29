<div class="dropdown icon-btn-wrapper">
    <a class="icon-btn" id="navbarDropdownNotification" href="#" role="button" data-bs-toggle="dropdown"
        aria-expanded="false" data-bs-auto-close="true">
        <i class="fas fa-bell"></i>
        <?php if (isset($notifications) && count($notifications) > 0): ?>
            <span class="notification-badge"></span>
        <?php endif; ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end bg-brown notification-dropdown"
        aria-labelledby="navbarDropdownNotification" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
        <li>
            <h6 class="dropdown-header text-dark">Notifikasi</h6>
        </li>
        <?php if (isset($notifications) && !empty($notifications)): ?>
            <?php foreach ($notifications as $notif): ?>
                <li>
                    <a class="dropdown-item d-flex align-items-start gap-2 py-2" href="<?= $notif['link'] ?>">
                        <div class="mt-1">
                            <i class="<?= $notif['icon'] ?>"></i>
                        </div>
                        <div>
                            <p class="mb-0 small text-wrap text-break" style="line-height: 1.2;">
                                <?= $notif['message'] ?>
                            </p>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                <?= $notif['time'] ?>
                            </small>
                        </div>
                    </a>
                    <hr class="dropdown-divider light my-0" />
                </li>
            <?php endforeach; ?>
            <li>
                <a class="dropdown-item text-center small text-primary py-2" href="#">Lihat Semua</a>
            </li>
        <?php else: ?>
            <li>
                <p class="dropdown-item text-center small text-muted mb-0 py-3">Tidak ada notifikasi baru</p>
            </li>
        <?php endif; ?>
    </ul>
</div>