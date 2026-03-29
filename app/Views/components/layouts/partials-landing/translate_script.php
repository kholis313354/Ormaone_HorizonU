<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'id,en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }

    function changeLanguage(lang) {
        // Set cookie 'googtrans' that Google Translate uses
        // Format: /source_lang/target_lang
        var cookieValue = (lang === 'id') ? '/id/id' : '/id/en';

        // Build the cookie string
        document.cookie = "googtrans=" + cookieValue + "; path=/; domain=." + document.domain;
        document.cookie = "googtrans=" + cookieValue + "; path=/";

        // Also try to find the element and change it directly if possible (for smoother transition)
        var select = document.querySelector('select.goog-te-combo');
        if (select) {
            select.value = lang;
            select.dispatchEvent(new Event('change'));
        }

        // Reload the page to ensure the translation is applied via cookie
        window.location.reload();
    }
</script>
<script type="text/javascript"
    src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<style>
    /* Styling Floating Button */
    .floating-lang-switcher {
        position: fixed;
        bottom: 80px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .lang-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: 2px solid #fff;
        cursor: pointer;
        overflow: hidden;
    }

    .lang-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
    }

    .lang-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Hide Google Translate Widget & Branding but KEEP IT ACCESSIBLE FOR SCRIPT */
    .goog-te-banner-frame.skiptranslate,
    .goog-te-banner-frame,
    .skiptranslate > .goog-te-banner-frame,
    iframe.goog-te-banner-frame,
    #goog-gt-tt,
    .goog-te-balloon-frame,
    .goog-tooltip,
    .goog-text-highlight {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    body {
        top: 0px !important;
        position: static !important;
    }

    /* Ensure no top margin/padding on body from google */
    body.translated-ltr,
    body.translated-rtl {
        margin-top: 0 !important;
    }

    /* DO NOT use display:none for the element itself, or the script won't run */
    #google_translate_element {
        position: absolute;
        top: -9999px;
        left: -9999px;
        width: 1px;
        height: 1px;
        overflow: hidden;
        visibility: visible;
        /* Must be visible for script to attach */
    }

    font {
        background-color: transparent !important;
        box-shadow: none !important;
    }
</style>

<div id="google_translate_element"></div>

<script>
    // Forcefully remove the toolbar just in case CSS misses it specific versions
    document.addEventListener("DOMContentLoaded", function() {
        var observer = new MutationObserver(function(mutations) {
            var banner = document.querySelector('.goog-te-banner-frame');
            if(banner) {
                banner.style.display = 'none';
                banner.style.visibility = 'hidden';
            }
            document.body.style.top = '0px';
        });

        observer.observe(document.body, { childList: true, subtree: true, attributes: true });

        // Backup interval
        setInterval(function() {
            var banner = document.querySelector('.goog-te-banner-frame');
            if(banner) {
                banner.style.display = 'none';
            }
            if(document.body.style.top !== '0px') {
                document.body.style.top = '0px';
            }
        }, 100);
    });
</script>