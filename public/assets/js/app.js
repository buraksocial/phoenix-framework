import 'toastify-js/src/toastify.css';
import Toastify from 'toastify-js';

/**
 * ====================================================================
 * Notifier Modülü
 * ====================================================================
 * Uygulama genelinde standart bildirimler (toast) göstermek için kullanılır.
 * `window.Notifier` üzerinden global olarak erişilebilir.
 */
window.Notifier = {
    toast: function(message, type = 'info') {
        const colors = {
            success: 'linear-gradient(to right, #00b09b, #96c93d)',
            error: 'linear-gradient(to right, #ff5f6d, #ffc371)',
            info: 'linear-gradient(to right, #4facfe, #00f2fe)',
            warning: 'linear-gradient(to right, #f12711, #f5af19)',
        };
        Toastify({
            text: message,
            duration: 4000,
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, 
            style: {
                background: colors[type] || colors.info,
                borderRadius: "5px"
            },
        }).showToast();
    },
    success: function(message) { this.toast(message, 'success'); },
    error: function(message) { this.toast(message, 'error'); },
    info: function(message) { this.toast(message, 'info'); },
    warning: function(message) { this.toast(message, 'warning'); }
};


/**
 * ====================================================================
 * Declarative Ajax Engine
 * ====================================================================
 * HTML elementlerine eklenen `data-ajax-*` attribute'larını okuyarak
 * otomatik olarak Ajax istekleri gönderen ve sonuçları işleyen ana motor.
 * Bu yapı, sayfa bazında özel JavaScript yazma ihtiyacını ortadan kaldırır.
 */
document.addEventListener('DOMContentLoaded', () => {

    // Event Delegation kullanarak tüm body'yi dinle. Bu daha performanslıdır.
    document.body.addEventListener('click', e => {
        const element = e.target.closest('[data-ajax-trigger="click"]');
        if (element) {
            e.preventDefault();
            handleAjaxRequest(element);
        }
    });

    document.body.addEventListener('submit', e => {
        const element = e.target.closest('[data-ajax-trigger="submit"]');
        if (element) {
            e.preventDefault();
            handleAjaxRequest(element);
        }
    });

    let debounceTimers = {};
    document.body.addEventListener('keyup', e => {
        const element = e.target.closest('[data-ajax-trigger="keyup"]');
        if (element) {
            e.preventDefault();
            const debounceMs = parseInt(element.getAttribute('data-ajax-debounce'), 10) || 300;
            // Her bir element için ayrı bir timer tut
            const timerId = element.dataset.ajaxEndpoint || 'global_keyup';

            clearTimeout(debounceTimers[timerId]);
            debounceTimers[timerId] = setTimeout(() => {
                handleAjaxRequest(element);
            }, debounceMs);
        }
    });
});

/**
 * Ana Ajax isteği işleyici fonksiyonu.
 * @param {HTMLElement} element - olayı tetikleyen HTML elementi (buton, form, input vb.)
 */
async function handleAjaxRequest(element) {
    const endpoint = element.getAttribute('data-ajax-endpoint');
    const method = element.getAttribute('data-ajax-method') || 'POST';
    const confirmMessage = element.getAttribute('data-ajax-confirm');

    if (!endpoint) {
        console.error('Ajax endpoint (data-ajax-endpoint) belirtilmemiş.', element);
        Notifier.error('İstek adresi tanımsız.');
        return;
    }

    if (confirmMessage && !confirm(confirmMessage)) {
        return;
    }

    let body = null;
    // Form içindeki butonlar veya formun kendisi için submitter'ı bul
    const submitter = element.tagName === 'FORM' ? element.querySelector('[type="submit"]') : element;
    
    // Eğer tetikleyici bir form veya formun içindeyse, form verilerini al
    const form = element.closest('form');
    if (element.tagName === 'FORM' || form) {
        const sourceForm = element.tagName === 'FORM' ? element : form;
        const formData = new FormData(sourceForm);
        // Eğer bir input tetiklediyse, onun ismini ve değerini de ekle
        if(element.tagName === 'INPUT' && element.name) {
             formData.set(element.name, element.value);
        }
        body = Object.fromEntries(formData.entries());
    }

    // Yükleniyor durumu
    const originalContent = submitter.innerHTML;
    if(submitter) submitter.disabled = true;
    if (submitter && submitter.getAttribute('data-ajax-loading-text')) {
        submitter.innerHTML = submitter.getAttribute('data-ajax-loading-text');
    }

    try {
        const response = await fetch(endpoint, {
            method: method.toUpperCase(),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: body ? JSON.stringify(body) : null
        });

        const responseData = await response.json();

        if (!response.ok) {
            throw new Error(responseData.message || `HTTP hatası! Durum: ${response.status}`);
        }
        
        if (responseData.success === false) {
             throw new Error(responseData.message || 'Sunucu tarafından bir hata bildirildi.');
        }

        handleSuccess(responseData, element);

    } catch (error) {
        Notifier.error(error.message);
    } finally {
        // Yükleniyor durumunu geri al
        if(submitter) {
            submitter.disabled = false;
            submitter.innerHTML = originalContent;
        }
    }
}

/**
 * Başarılı Ajax isteği sonrası yapılacak işlemleri yönetir.
 * @param {object} response - Sunucudan gelen JSON yanıtı
 * @param {HTMLElement} originalElement - İsteği başlatan HTML elementi
 */
function handleSuccess(response, originalElement) {
    const successAction = originalElement.getAttribute('data-ajax-success-action') || 'toast';
    const targetElementSelector = originalElement.getAttribute('data-ajax-target');

    // Sunucudan gelen mesaj varsa her zaman göster
    if (response.message) {
        Notifier.success(response.message);
    }

    // `data-` attribute'unda belirtilen eylemleri `|` ile ayırarak gerçekleştir
    successAction.split('|').forEach(action => {
        action = action.trim();
        switch (action) {
            case 'remove-parent':
                const parentSelector = originalElement.getAttribute('data-ajax-parent-to-remove') || 'div';
                originalElement.closest(parentSelector)?.remove();
                break;
            case 'reload':
                window.location.reload();
                break;
            case 'redirect':
                if (response.data && response.data.redirect_url) {
                    window.location.href = response.data.redirect_url;
                } else {
                    console.warn("Yönlendirme eylemi istendi ancak 'redirect_url' datası bulunamadı.");
                }
                break;
            case 'replace':
                 if (targetElementSelector && response.data && typeof response.data.html !== 'undefined') {
                    const target = document.querySelector(targetElementSelector);
                    if (target) {
                        target.innerHTML = response.data.html;
                    } else {
                        console.warn(`Hedef element bulunamadı: ${targetElementSelector}`);
                    }
                }
                break;
            case 'reset-form':
                const formToReset = originalElement.closest('form');
                if (formToReset) formToReset.reset();
                break;
            case 'toast':
                // Zaten yukarıda mesaj varsa gösteriliyor, bu eylem sadece boş geçmek için var.
                break;
            default:
                console.warn(`Bilinmeyen success-action: ${action}`);
        }
    });
}
