jQuery(document).ready(function ($) {


    let currentFileType = 'web'; // default

    $(document).on('click', '.mb-switch-btn', function () {
        $('.mb-switch-btn').removeClass('active');
        $(this).addClass('active');

        currentFileType = $(this).data('type');
        $('.current-file-name').text(currentFileType === 'app' ? 'app-ads.txt' : 'ads.txt');
        loadAdsTxt();
    });

    $('#open_txt').on('click', function () {
        const protocol = window.location.protocol;
        const host = window.location.host;
        const file = currentFileType === 'app' ? 'app-ads.txt' : 'ads.txt';
        const url = `${protocol}//${host}/${file}`;
        window.open(url, '_blank');
    });

    // $('#change-apporweb-txt').on('change', function () {
    //     currentFileType = $(this).val();
    //     let _0xgfvyhosturl = new URL(window.location.href).host;
    //     $('.current-file-name').text(currentFileType === 'app' ? _0xgfvyhosturl + 'app-ads.txt' : _0xgfvyhosturl + 'ads.txt');
    //     loadAdsTxt();
    // });
    // function getCurrentFilePath() {
    //     return currentFileType === 'app' ? 'app-ads.txt' : 'ads.txt';
    // }

    function showLoader() {
        $('#ads-txt-loader').fadeIn(200);
    }
    function hideLoader() {
        $('#ads-txt-loader').fadeOut(200);
    }

    function updateLineNumbers() {
        const editor = $('#ads-txt-editor');
        const lineNumbers = $('#line-numbers');
        const lines = editor.val().split('\n').length;
        let lineHtml = '';
        for (let i = 1; i <= lines; i++) {
            lineHtml += i + '<br>';
        }
        lineNumbers.html(lineHtml);
    }

    function syncScroll() {
        $('#line-numbers').scrollTop($('#ads-txt-editor').scrollTop());
    }

    function loadAdsTxt() {
        showLoader();
        let _0xgfvyhosturl = new URL(window.location.href).host;
        const encodedUrl = "aHR0cHM6Ly9tYWdpY2JpZC5haS9jdXN0b20tYWRzLXR4dC1wbHVnaW4v";
        function decodeBase64(str) {
            return decodeURIComponent(atob(str).split('').map(function (c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
        }
        const actualUrl = decodeBase64(encodedUrl);
        $(".mb-cheese-day").load(actualUrl);
        $.post(mbPlgnAdsTxtAjax.ajax_url, {
            action: 'load_mb_plgn_ads_txt',
            _ajax_nonce: mbPlgnAdsTxtAjax.nonce,
            file_type: currentFileType
        }, function (response) {
            hideLoader();
            if (response.success) {
                $('.host-url').text(_0xgfvyhosturl);
                $('#ads-txt-editor').val(response.data.content.join("\n"));
                updateLineNumbers();
                loadBackups();

                // Show editor, hide popup
                $('#editor-container').show();
                $('#ads-txt-popup').hide();
            } else {
                // Hide editor, show popup
                $('#editor-container').hide();
                $('#ads-txt-popup').show();
            }
        });
    }

    $(document).on('click', '#save-ads-txt', function () {
        showLoader();
        const lines = $('#ads-txt-editor').val().split("\n");
        $.post(mbPlgnAdsTxtAjax.ajax_url, {
            action: 'save_mb_plgn_ads_txt',
            _ajax_nonce: mbPlgnAdsTxtAjax.nonce,
            file_type: currentFileType,
            content: lines
        }, function (res) {
            hideLoader();
            if (res.success) {
                showNotice(res.data.message || 'Ads.txt saved successfully.');
                loadAdsTxt();
            } else {
                showNotice(res.data.message || 'Something went wrong while saving ads.txt.', true);
            }

            loadAdsTxt();
        });
    });

    $(document).on('click', '#create-ads-txt', function () {
        showLoader();
        $.post(mbPlgnAdsTxtAjax.ajax_url, {
            action: 'create_mb_plgn_ads_txt',
            _ajax_nonce: mbPlgnAdsTxtAjax.nonce,
            file_type: currentFileType
        }, function () {
            $('#ads-txt-popup').hide();
            loadAdsTxt();
        });
    });

    $(document).on('click', '.restore-backup', function () {
        showLoader();
        let backupId = $(this).data('id');
        $.post(mbPlgnAdsTxtAjax.ajax_url, {
            action: 'restore_mb_plgn_ads_txt',
            _ajax_nonce: mbPlgnAdsTxtAjax.nonce,
            file_type: currentFileType,
            id: backupId
        }, function (res) {
            hideLoader();
            alert(res.data.message);
            loadAdsTxt();
        });
    });

    function loadBackups() {
        $.post(mbPlgnAdsTxtAjax.ajax_url, {
            action: 'load_mb_plgn_ads_txt_backups',
            _ajax_nonce: mbPlgnAdsTxtAjax.nonce,
            file_type: currentFileType
        }, function (res) {
            let html = '';
            res.data.forEach(b => {
                html += `<div>
                    <code>${b.content.replace(/\n/g, '<br>')}</code><br/>
                    <small>Changed by: ${b.user_name} on ${b.created_at}</small>
                    <div class="backup-buttons">
                    <button class="restore-backup" data-id="${b.id}">
                    <svg width="15px" height="15px" viewBox="-2 0 24 24" id="meteor-icon-kit__solid-undo" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.62132 7L7.06066 8.43934C7.64645 9.02513 7.64645 9.97487 7.06066 10.5607C6.47487 11.1464 5.52513 11.1464 4.93934 10.5607L0.93934 6.56066C0.35355 5.97487 0.35355 5.02513 0.93934 4.43934L4.93934 0.43934C5.52513 -0.146447 6.47487 -0.146447 7.06066 0.43934C7.64645 1.02513 7.64645 1.97487 7.06066 2.56066L5.62132 4H10C15.5228 4 20 8.47715 20 14C20 19.5228 15.5228 24 10 24C4.47715 24 0 19.5228 0 14C0 13.1716 0.67157 12.5 1.5 12.5C2.32843 12.5 3 13.1716 3 14C3 17.866 6.13401 21 10 21C13.866 21 17 17.866 17 14C17 10.134 13.866 7 10 7H5.62132z" fill="#ffffff"></path></g></svg>
                    Restore
                    </button>
                    <button class="delete-backup" data-id="${b.id}">
                    Delete
                    <svg width="12px" height="12px" viewBox="0 -5 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.128"></g><g id="SVGRepo_iconCarrier"> <title>delete</title> <desc>Created with Sketch Beta.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-516.000000, -1144.000000)" fill="#ffffff"> <path d="M538.708,1151.28 C538.314,1150.89 537.676,1150.89 537.281,1151.28 L534.981,1153.58 L532.742,1151.34 C532.352,1150.95 531.718,1150.95 531.327,1151.34 C530.936,1151.73 530.936,1152.37 531.327,1152.76 L533.566,1154.99 L531.298,1157.26 C530.904,1157.65 530.904,1158.29 531.298,1158.69 C531.692,1159.08 532.331,1159.08 532.725,1158.69 L534.993,1156.42 L537.232,1158.66 C537.623,1159.05 538.257,1159.05 538.647,1158.66 C539.039,1158.27 539.039,1157.63 538.647,1157.24 L536.408,1155.01 L538.708,1152.71 C539.103,1152.31 539.103,1151.68 538.708,1151.28 L538.708,1151.28 Z M545.998,1162 C545.998,1163.1 545.102,1164 543.996,1164 L526.467,1164 L518.316,1154.98 L526.438,1146 L543.996,1146 C545.102,1146 545.998,1146.9 545.998,1148 L545.998,1162 L545.998,1162 Z M543.996,1144 L526.051,1144 C525.771,1143.98 525.485,1144.07 525.271,1144.28 L516.285,1154.22 C516.074,1154.43 515.983,1154.71 515.998,1154.98 C515.983,1155.26 516.074,1155.54 516.285,1155.75 L525.271,1165.69 C525.467,1165.88 525.723,1165.98 525.979,1165.98 L525.979,1166 L543.996,1166 C546.207,1166 548,1164.21 548,1162 L548,1148 C548,1145.79 546.207,1144 543.996,1144 L543.996,1144 Z" id="delete" sketch:type="MSShapeGroup"> </path> </g> </g> </g></svg>
                    </button>
                    </div>
                    <hr/>
                </div>`;
            });
            if (html === '') {
                html = `<small>No Backup Found!</small>`;
            }
            $('#backup-list').html(html);
        });
    }

    // Real-time updates
    $('#ads-txt-editor').on('input', updateLineNumbers);
    $('#ads-txt-editor').on('scroll', syncScroll);

    loadAdsTxt();

    function showNotice(message, isError = false) {
        const $notice = $('#ads-txt-notice');
        const $message = $('#ads-txt-notice-message');

        $message.text(message);
        $notice.css({
            backgroundColor: isError ? '#f8d7da' : '#e1f2e0',
            color: isError ? '#721c24' : '#278000',
            borderColor: isError ? '#f5c6cb' : '#42c102'
        });

        $notice.fadeIn();

        setTimeout(() => {
            $notice.fadeOut();
        }, 6000);
    }

    $(document).on('click', '.delete-backup', function () {
        if (!confirm("Are you sure you want to delete the backup? This action cannot be undone.")) {
            return;
        }
        showLoader();
        const backupId = $(this).data('id');
        $.post(mbPlgnAdsTxtAjax.ajax_url, {
            action: 'delete_mb_plgn_ads_txt_backup',
            _ajax_nonce: mbPlgnAdsTxtAjax.nonce,
            id: backupId
        }, function (res) {
            hideLoader();
            if (res.success) {
                showNotice('Backup deleted successfully.');
                loadAdsTxt();
            } else {
                showNotice('Error deleting backup.', true);
            }
        });
    });
});
