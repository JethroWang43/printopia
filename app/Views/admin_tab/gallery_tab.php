<script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>

<style>
    .gallery-tab {
        background: #ffffff;
        border: 1px solid #d6dfed;
        border-radius: 14px;
        box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
        padding: clamp(16px, 2vw, 24px);
    }

    .gallery-metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .gallery-metric {
        border: 1px solid #dae2f0;
        border-radius: 12px;
        background: #ffffff;
        padding: 12px;
        display: grid;
        gap: 6px;
        box-shadow: 0 6px 12px rgba(16, 34, 79, 0.05);
    }

    .gallery-metric-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        color: #5f6d8c;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .gallery-metric-value {
        color: #1b2f61;
        font-size: 1.7rem;
        font-weight: 800;
        line-height: 1;
    }

    .gallery-toolbar {
        border: 1px solid #f0c9c9;
        border-radius: 10px;
        padding: 10px;
        display: grid;
        grid-template-columns: minmax(220px, 1.2fr) minmax(170px, 0.75fr) minmax(160px, 0.75fr);
        gap: 10px;
        margin-bottom: 14px;
        background: #fffdfd;
        align-items: start;
    }

    .gallery-upload-row {
        border: 1px solid #f0c9c9;
        border-radius: 10px;
        padding: 10px;
        display: grid;
        gap: 8px;
        background: #fffdfd;
        margin-bottom: 14px;
    }

    .gallery-upload-config {
        border: 1px solid #d1daeb;
        border-radius: 8px;
        background: #ffffff;
        padding: 8px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.3fr) auto;
        gap: 8px;
        align-items: center;
        min-width: 0;
        overflow: hidden;
    }

    .gallery-upload-config .gallery-field {
        min-height: 36px;
        border-color: #d9e1ef;
        padding: 0 10px;
    }

    .gallery-labeled-field {
        display: grid;
        gap: 4px;
        min-width: 0;
    }

    .gallery-labeled-field label {
        font-size: 0.72rem;
        font-weight: 800;
        color: #5a6b8f;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .gallery-field {
        min-height: 40px;
        border: 1px solid #d1daeb;
        border-radius: 8px;
        background: #ffffff;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 12px;
        color: #4f5b7d;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .gallery-field input,
    .gallery-field select {
        width: 100%;
        border: 0;
        outline: none;
        background: transparent;
        font: inherit;
        color: #2f3e64;
    }

    .gallery-view-toggle {
        min-height: 40px;
        border: 1px solid #d1daeb;
        border-radius: 8px;
        display: inline-flex;
        overflow: hidden;
        background: #fff;
    }

    .gallery-view-toggle button {
        border: 0;
        background: transparent;
        color: #4f5b7d;
        font-family: inherit;
        font-size: 0.83rem;
        font-weight: 700;
        padding: 0 12px;
        cursor: pointer;
    }

    .gallery-view-toggle button.active {
        background: #e9f4ff;
        color: #1a588f;
    }

    .gallery-folder-hint {
        border: 1px solid #d4deef;
        border-radius: 8px;
        background: #f4f7fd;
        color: #3f5380;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 8px 10px;
        line-height: 1.25;
        word-break: break-word;
    }

    .gallery-upload-btn {
        border: 1px solid #7f1d1d;
        color: #ffffff;
        background: linear-gradient(180deg, #8f1d1d 0%, #6f1414 100%);
        border-radius: 8px;
        min-height: 40px;
        padding: 0 14px;
        font-family: inherit;
        font-size: 0.86rem;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        align-self: stretch;
        min-width: 126px;
    }

    .gallery-field select,
    .gallery-field input {
        min-width: 0;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .gallery-grid.list-mode {
        grid-template-columns: 1fr;
    }

    .gallery-card {
        border: 1px solid #d8e1f1;
        border-radius: 12px;
        background: #ffffff;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(16, 34, 79, 0.06);
        display: grid;
        grid-template-rows: auto 1fr auto;
        min-height: 330px;
    }

    .gallery-thumb {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid #e4eaf5;
        background: #f3f6fc;
    }

    .gallery-thumb-file {
        width: 100%;
        aspect-ratio: 4 / 3;
        display: grid;
        place-items: center;
        border-bottom: 1px solid #e4eaf5;
        background: linear-gradient(180deg, #eef2f9 0%, #e2e9f6 100%);
        color: #546b98;
        font-size: 1rem;
        font-weight: 800;
    }

    .gallery-card-body {
        padding: 10px 12px;
        display: grid;
        gap: 8px;
    }

    .gallery-card h4 {
        margin: 0;
        color: #1d2e59;
        font-size: 0.96rem;
    }

    .gallery-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .gallery-pill {
        border: 1px solid #cfd8ea;
        border-radius: 999px;
        padding: 3px 8px;
        color: #4b5d84;
        font-size: 0.74rem;
        font-weight: 700;
        line-height: 1;
        background: #f6f8fc;
    }

    .gallery-card-footer {
        padding: 0 12px 12px;
    }

    .gallery-view-btn {
        width: 100%;
        min-height: 38px;
        border: 1px solid #7f1d1d;
        border-radius: 10px;
        background: linear-gradient(180deg, #8f1d1d 0%, #6f1414 100%);
        color: #ffffff;
        font-family: inherit;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        display: block;
        text-align: center;
        text-decoration: none;
        line-height: 38px;
    }

    .gallery-empty {
        border: 1px dashed #d6deed;
        border-radius: 10px;
        padding: 22px;
        text-align: center;
        color: #607092;
        background: #f9fbff;
        grid-column: 1 / -1;
    }

    .gallery-preview-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(10, 17, 34, 0.62);
        z-index: 10020;
        align-items: center;
        justify-content: center;
        padding: 18px;
    }

    .gallery-preview-card {
        width: min(1040px, 100%);
        max-height: calc(100vh - 36px);
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #d6dfed;
        box-shadow: 0 22px 34px rgba(10, 20, 48, 0.25);
        display: grid;
        grid-template-rows: auto 1fr;
        overflow: hidden;
    }

    .gallery-preview-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-bottom: 1px solid #e2eaf8;
        padding: 10px 14px;
    }

    .gallery-preview-title {
        min-width: 0;
        font-size: 0.96rem;
        font-weight: 800;
        color: #1d2e59;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gallery-preview-actions {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .gallery-preview-open {
        border: 1px solid #c4d0ea;
        border-radius: 8px;
        background: #ffffff;
        color: #39527f;
        padding: 6px 10px;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
    }

    .gallery-preview-close {
        width: 32px;
        height: 32px;
        border: 1px solid #d9e2f1;
        border-radius: 8px;
        background: #ffffff;
        color: #40567f;
        font-size: 1.05rem;
        cursor: pointer;
        line-height: 1;
    }

    .gallery-preview-body {
        background: #f4f7fd;
        overflow: auto;
        padding: 12px;
    }

    .gallery-preview-body img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 10px;
        border: 1px solid #dbe3f2;
        background: #fff;
    }

    .gallery-preview-body iframe {
        width: 100%;
        min-height: 72vh;
        border: 1px solid #dbe3f2;
        border-radius: 10px;
        background: #fff;
    }

    .gallery-preview-file-fallback {
        border: 1px dashed #cfdaf0;
        border-radius: 12px;
        background: #ffffff;
        color: #4b628f;
        font-weight: 700;
        text-align: center;
        padding: 32px 12px;
    }

    .gallery-folder-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 12px;
    }

    .gallery-folder-item {
        border: 1px solid #dbe3f2;
        border-radius: 10px;
        background: #ffffff;
        overflow: hidden;
        display: grid;
        grid-template-rows: auto 1fr auto;
    }

    .gallery-folder-item.active {
        border-color: #8f1d1d;
        box-shadow: 0 0 0 2px rgba(143, 29, 29, 0.1);
    }

    .gallery-folder-thumb {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        display: block;
        background: #f3f6fc;
        border-bottom: 1px solid #e4eaf5;
    }

    .gallery-folder-file {
        width: 100%;
        aspect-ratio: 4 / 3;
        display: grid;
        place-items: center;
        color: #546b98;
        font-size: 0.95rem;
        font-weight: 800;
        background: linear-gradient(180deg, #eef2f9 0%, #e2e9f6 100%);
        border-bottom: 1px solid #e4eaf5;
    }

    .gallery-folder-meta {
        padding: 10px;
        display: grid;
        gap: 6px;
    }

    .gallery-folder-name {
        font-size: 0.85rem;
        color: #1d2e59;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gallery-folder-open {
        border-top: 1px solid #e8eef9;
        padding: 8px 10px;
    }

    .gallery-folder-open a {
        display: block;
        text-align: center;
        border: 1px solid #c4d0ea;
        border-radius: 8px;
        color: #39527f;
        text-decoration: none;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 6px 8px;
        background: #fff;
    }

    .gallery-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding-top: 14px;
        color: #667493;
        font-size: 0.9rem;
        flex-wrap: wrap;
    }

    .gallery-page,
    .gallery-nav {
        min-width: 28px;
        height: 28px;
        border: 1px solid #d6deed;
        border-radius: 8px;
        display: inline-grid;
        place-items: center;
        background: #ffffff;
        color: #3d4f77;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
    }

    .gallery-page.active {
        background: #162b5e;
        color: #ffffff;
        border-color: #162b5e;
    }

    .gallery-nav.disabled {
        opacity: 0.45;
        pointer-events: none;
    }

    @media (max-width: 1180px) {
        .gallery-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .gallery-metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 900px) {
        .gallery-toolbar { grid-template-columns: 1fr; }
        .gallery-upload-config { grid-template-columns: 1fr; }
        .gallery-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 620px) {
        .gallery-grid { grid-template-columns: 1fr; }
        .gallery-metrics { grid-template-columns: 1fr; }
    }
</style>

<article class="content-section" id="gallery-management">
    <section class="gallery-tab" data-gallery-tab>
        <div class="gallery-metrics">
            <article class="gallery-metric">
                <div class="gallery-metric-top"><span>Total Images</span><span>🖼</span></div>
                <div class="gallery-metric-value" id="galleryMetricTotal">0</div>
            </article>
            <article class="gallery-metric">
                <div class="gallery-metric-top"><span>Storage (MB)</span><span>💾</span></div>
                <div class="gallery-metric-value" id="galleryMetricStorage">0</div>
            </article>
            <article class="gallery-metric">
                <div class="gallery-metric-top"><span>Folders</span><span>🗂</span></div>
                <div class="gallery-metric-value" id="galleryMetricCategories">0</div>
            </article>
            <article class="gallery-metric">
                <div class="gallery-metric-top"><span>This Month</span><span>📈</span></div>
                <div class="gallery-metric-value" id="galleryMetricMonth">0</div>
            </article>
        </div>

        <div class="gallery-toolbar">
            <div class="gallery-field">🔍 <input id="gallerySearchInput" type="text" placeholder="Search images by title or user folder"></div>
            <div class="gallery-field">▾ <select id="galleryCategoryFilter"><option value="all">All Folders</option></select></div>
            <div class="gallery-field">▾ <select id="galleryTypeFilter"><option value="all">All Files</option></select></div>
        </div>

        <div class="gallery-upload-row">
            <div class="gallery-upload-config">
                <div class="gallery-labeled-field">
                    <label for="galleryFolderConvention">Folder Rule</label>
                    <div class="gallery-field">🧭 <select id="galleryFolderConvention">
                <option value="manual" selected>Manual Path</option>
                <option value="client-month">Client + Month</option>
                <option value="project-month">Project + Month</option>
                <option value="batch-day">Batch + Day</option>
                    </select></div>
                </div>
                <div class="gallery-labeled-field">
                    <label for="galleryUploadFolder">Target Folder</label>
                    <div class="gallery-field">📁 <input id="galleryUploadFolder" type="text" value="" placeholder="Ex: admin_gallery/resources/customer"></div>
                </div>
                <button type="button" id="upload_widget_btn" class="gallery-upload-btn">⬆ Upload Images</button>
            </div>
            <div class="gallery-folder-hint" id="galleryFolderHint">Upload path: not set</div>
        </div>

        <div class="gallery-view-toggle" style="margin-bottom: 14px;">
            <button type="button" id="galleryGridViewBtn" class="active">▦ Grid</button>
            <button type="button" id="galleryListViewBtn">▤ List</button>
        </div>

        <div class="gallery-grid" id="galleryCardGrid"></div>
        <div class="gallery-pagination" id="galleryPagination" aria-label="Gallery pagination"></div>
    </section>
</article>

<div id="galleryPreviewModal" class="gallery-preview-modal" role="dialog" aria-modal="true" aria-labelledby="galleryPreviewTitle">
    <div class="gallery-preview-card">
        <div class="gallery-preview-head">
            <div id="galleryPreviewTitle" class="gallery-preview-title">Preview</div>
            <div class="gallery-preview-actions">
                <a id="galleryPreviewOpenLink" class="gallery-preview-open" href="#" target="_blank" rel="noopener noreferrer">Open New Tab</a>
                <button type="button" id="galleryPreviewCloseBtn" class="gallery-preview-close" aria-label="Close preview">×</button>
            </div>
        </div>
        <div id="galleryPreviewBody" class="gallery-preview-body"></div>
    </div>
</div>

<div id="deleteModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:24px; border-radius:14px; width:90%; max-width:400px; text-align:center; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="font-size:40px; margin-bottom:10px;">⚠️</div>
        <h3 style="color:#1b2f61; margin-bottom:12px;">Delete Design?</h3>
        <p style="color:#5f6d8c; font-size:0.9rem; margin-bottom:24px;">This will permanently remove the design from your gallery. This action cannot be undone.</p>
        <div style="display:flex; gap:12px;">
            <button onclick="closeDeleteModal()" style="flex:1; padding:10px; border:1px solid #d1daeb; border-radius:8px; background:white; cursor:pointer; font-weight:700;">Cancel</button>
            <button id="confirmDeleteBtn" style="flex:1; padding:10px; border:none; border-radius:8px; background:#dc3545; color:white; cursor:pointer; font-weight:700;">Delete</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const galleryRoot = document.querySelector('[data-gallery-tab]');
        if (!galleryRoot) return;

        // Variables and Endpoints
        const endpoint = '<?= base_url('printopia/admin/gallery/files'); ?>';
        const saveEndpoint = '<?= base_url('printopia/admin/gallery/save_to_db'); ?>';
        const openEndpointBase = '<?= base_url('printopia/admin/gallery/open'); ?>';
        let currentCsrfHash = '<?= csrf_hash() ?>';
        const csrfTokenName = '<?= csrf_token() ?>';

        // UI Selectors
        const searchInput = document.querySelector('#gallerySearchInput');
        const categoryFilter = document.querySelector('#galleryCategoryFilter');
        const typeFilter = document.querySelector('#galleryTypeFilter');
        const uploadFolderInput = document.querySelector('#galleryUploadFolder');
        const folderConventionSelect = document.querySelector('#galleryFolderConvention');
        const folderHint = document.querySelector('#galleryFolderHint');
        const grid = document.querySelector('#galleryCardGrid');
        const previewModal = document.querySelector('#galleryPreviewModal');
        const previewBody = document.querySelector('#galleryPreviewBody');
        const previewTitle = document.querySelector('#galleryPreviewTitle');
        const previewOpenLink = document.querySelector('#galleryPreviewOpenLink');
        const previewCloseBtn = document.querySelector('#galleryPreviewCloseBtn');
        const gridBtn = document.querySelector('#galleryGridViewBtn');
        const listBtn = document.querySelector('#galleryListViewBtn');
        const pagination = document.querySelector('#galleryPagination');
        
        const metricTotal = document.querySelector('#galleryMetricTotal');
        const metricStorage = document.querySelector('#galleryMetricStorage');
        const metricCategories = document.querySelector('#galleryMetricCategories');
        const metricMonth = document.querySelector('#galleryMetricMonth');

        const state = {
            allFiles: [],
            filtered: [],
            page: 1,
            perPage: 8,
            uploadFolder: '',
            autoBatchKey: ''
        };

        let fileIdToDelete = null;

        // Helpers
        const escapeHtml = (value) => String(value || '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#39;');

        const formatStorageMB = (bytes) => (bytes / (1024 * 1024)).toFixed(2);

        const normalizeFolderValue = (value) => String(value || '')
            .trim()
            .replace(/\\/g, '/')
            .replace(/^\/+|\/+$/g, '')
            .replace(/\s+/g, '_');

        const getMonthKey = () => {
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            return `${yyyy}-${mm}`;
        };

        const getDayKey = () => {
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        };

        const getTimeKey = () => {
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mi = String(now.getMinutes()).padStart(2, '0');
            const ss = String(now.getSeconds()).padStart(2, '0');
            return `${hh}${mi}${ss}`;
        };

        const ensureAutoBatchKey = () => {
            if (!state.autoBatchKey) {
                const compactDay = getDayKey().replace(/-/g, '');
                state.autoBatchKey = `batch_${compactDay}_${getTimeKey()}`;
            }
            return state.autoBatchKey;
        };

        const buildUploadFolderByConvention = () => {
            const convention = folderConventionSelect?.value || 'client-month';
            const label = ensureAutoBatchKey();
            const root = 'admin_gallery/resources';

            if (convention === 'manual') {
                const manualPath = normalizeFolderValue(uploadFolderInput?.value);
                // In manual mode, respect exactly what the user types.
                return manualPath;
            }

            if (convention === 'project-month') {
                return `${root}/project_${label}/${getMonthKey()}`;
            }

            if (convention === 'batch-day') {
                return `${root}/batch_${label}/${getDayKey()}`;
            }

            return `${root}/client_${label}/${getMonthKey()}`;
        };

        const refreshUploadFolderUI = () => {
            const convention = folderConventionSelect?.value || 'client-month';
            const autoFolder = buildUploadFolderByConvention();

            if (uploadFolderInput) {
                // Avoid hijacking typing in manual mode.
                if (convention !== 'manual') {
                    uploadFolderInput.value = autoFolder;
                }
            }

            state.uploadFolder = autoFolder;

            if (folderHint) {
                folderHint.textContent = autoFolder ? `Upload path: ${autoFolder}` : 'Upload path: not set';
            }
        };

        const getFolderFromFile = (file) => {
            const raw = String(file?.public_id || '').trim();
            if (!raw.includes('/')) {
                return 'root';
            }
            const folder = raw.substring(0, raw.lastIndexOf('/')).trim();
            return folder || 'root';
        };

        const isImageFile = (format) => {
            const imageFormats = new Set(['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'avif', 'tiff']);
            return imageFormats.has(String(format || '').toLowerCase());
        };

        const isPdfFile = (format) => String(format || '').toLowerCase() === 'pdf';

        const findFileById = (idValue) => state.allFiles.find((item) => String(item.id) === String(idValue));

        const openFolderModal = (folderName, focusedId = null) => {
            if (!previewModal || !previewBody || !previewTitle || !previewOpenLink) {
                return;
            }

            const filesInFolder = state.allFiles.filter((file) => getFolderFromFile(file) === folderName);
            previewTitle.textContent = `Folder: ${folderName} (${filesInFolder.length})`;
            previewOpenLink.style.display = 'none';

            if (!filesInFolder.length) {
                previewBody.innerHTML = '<div class="gallery-preview-file-fallback">No files found in this folder.</div>';
                previewModal.style.display = 'flex';
                return;
            }

            previewBody.innerHTML = `
                <div class="gallery-folder-grid">
                    ${filesInFolder.map((file) => {
                        const fileUrl = escapeHtml(file.image_url || '');
                        const fileTitle = escapeHtml(file.filename || 'Untitled');
                        const formatRaw = String(file.format || '');
                        const ext = formatRaw.toUpperCase();
                        const openHref = formatRaw.toLowerCase() === 'pdf'
                            ? `${openEndpointBase}/${file.id}`
                            : (file.image_url || '#');
                        const activeClass = String(file.id) === String(focusedId) ? 'active' : '';
                        const thumb = isImageFile(file.format)
                            ? `<img class="gallery-folder-thumb" src="${fileUrl}" alt="${fileTitle}" loading="lazy">`
                            : `<div class="gallery-folder-file">${escapeHtml(ext)} FILE</div>`;

                        return `
                            <article class="gallery-folder-item ${activeClass}">
                                ${thumb}
                                <div class="gallery-folder-meta">
                                    <div class="gallery-folder-name" title="${fileTitle}">${fileTitle}</div>
                                    <div class="gallery-meta">
                                        <span class="gallery-pill">${escapeHtml(ext)}</span>
                                    </div>
                                </div>
                                <div class="gallery-folder-open">
                                    <a href="${escapeHtml(openHref)}" target="_blank" rel="noopener noreferrer">Open File</a>
                                </div>
                            </article>
                        `;
                    }).join('')}
                </div>
            `;

            previewModal.style.display = 'flex';
        };

        const closePreviewModal = () => {
            if (!previewModal || !previewBody) {
                return;
            }
            previewModal.style.display = 'none';
            previewBody.innerHTML = '';
            if (previewOpenLink) {
                previewOpenLink.style.display = '';
            }
        };

        // --- EXPOSED GLOBAL FUNCTIONS (For HTML onclick events) ---
        window.deleteImage = (id) => {
            fileIdToDelete = id;
            document.getElementById('deleteModal').style.display = 'flex';
            document.getElementById('confirmDeleteBtn').onclick = executeDelete;
        };

        window.closeDeleteModal = () => {
            document.getElementById('deleteModal').style.display = 'none';
            fileIdToDelete = null;
        };

        async function executeDelete() {
            if (!fileIdToDelete) return;

            // 1. Visual Feedback: Disable buttons and show loading state
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const cancelBtn = confirmBtn.previousElementSibling; // The Cancel button
            const originalText = confirmBtn.innerText;
            
            confirmBtn.disabled = true;
            confirmBtn.innerText = 'Deleting...';
            confirmBtn.style.opacity = '0.7';
            confirmBtn.style.cursor = 'not-allowed';
            cancelBtn.style.display = 'none'; // Hide cancel to prevent double actions

            const formData = new FormData();
            formData.append(csrfTokenName, currentCsrfHash);

            try {
                const response = await fetch(`<?= base_url('printopia/admin/gallery/delete'); ?>/${fileIdToDelete}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });

                const result = await response.json();
                if (result.token) currentCsrfHash = result.token;

                if (result.status === 'success') {
                    window.closeDeleteModal();
                    loadGalleryFiles(); 
                } else {
                    alert('Error: ' + (result.message || 'Unknown error'));
                    // Reset UI if it fails
                    confirmBtn.disabled = false;
                    confirmBtn.innerText = originalText;
                    confirmBtn.style.opacity = '1';
                    confirmBtn.style.cursor = 'pointer';
                    cancelBtn.style.display = 'block';
                }
            } catch (error) {
                console.error('Delete failed:', error);
                alert('Connection error. Please try again.');
                
                // Reset UI on network error
                confirmBtn.disabled = false;
                confirmBtn.innerText = originalText;
                confirmBtn.style.opacity = '1';
                confirmBtn.style.cursor = 'pointer';
                cancelBtn.style.display = 'block';
            }
        }

        // Main grid now renders by folder, not by individual file.
        const buildFolderCard = (folderName, files) => {
            const sortedFiles = [...files].sort((a, b) => {
                const aTime = new Date(a.created_at || 0).getTime();
                const bTime = new Date(b.created_at || 0).getTime();
                return bTime - aTime;
            });

            const coverFile = sortedFiles.find((f) => isImageFile(f.format)) || sortedFiles[0] || null;
            const coverUrl = escapeHtml(coverFile?.image_url || '');
            const safeFolder = escapeHtml(folderName || 'root');
            const imageCount = sortedFiles.length;
            const distinctTypes = Array.from(new Set(sortedFiles.map((f) => String(f.format || '').toUpperCase()))).slice(0, 2);
            const typeLabel = distinctTypes.join(', ') || 'FILE';

            const previewHtml = (coverFile && isImageFile(coverFile.format))
                ? `<img class="gallery-thumb" src="${coverUrl}" alt="${safeFolder}" loading="lazy">`
                : `<div class="gallery-thumb-file">${escapeHtml(typeLabel)} FILE</div>`;

            return `
                <article class="gallery-card">
                    ${previewHtml}
                    <div class="gallery-card-body">
                        <h4 title="${safeFolder}">${safeFolder}</h4>
                        <div class="gallery-meta">
                            <span class="gallery-pill">📁 Folder</span>
                            <span class="gallery-pill">${imageCount} file${imageCount === 1 ? '' : 's'}</span>
                            <span class="gallery-pill">${escapeHtml(typeLabel)}</span>
                        </div>
                    </div>
                    <div class="gallery-card-footer" style="display: flex; gap: 8px;">
                        <button type="button" class="gallery-view-btn gallery-open-folder-btn" data-folder="${safeFolder}" style="flex: 1;">Open Folder</button>
                    </div>
                </article>
            `;
        };

        const renderPagination = (totalItems) => {
            const totalPages = Math.max(1, Math.ceil(totalItems / state.perPage));
            if (state.page > totalPages) state.page = totalPages;

            const pages = [];
            for (let i = 1; i <= totalPages; i++) {
                if (i <= 3 || i > totalPages - 2 || Math.abs(i - state.page) <= 1) pages.push(i);
            }

            const compactPages = [];
            let prev = 0;
            pages.forEach((p) => {
                if (prev && p - prev > 1) compactPages.push('...');
                compactPages.push(p);
                prev = p;
            });

            const previousDisabled = state.page <= 1 ? 'disabled' : '';
            const nextDisabled = state.page >= totalPages ? 'disabled' : '';

            pagination.innerHTML = `
                <button type="button" class="gallery-nav ${previousDisabled}" data-page-nav="prev">Previous</button>
                ${compactPages.map((page) => {
                    if (page === '...') return '<span>...</span>';
                    const active = page === state.page ? 'active' : '';
                    return `<button type="button" class="gallery-page ${active}" data-page="${page}">${page}</button>`;
                }).join('')}
                <button type="button" class="gallery-nav ${nextDisabled}" data-page-nav="next">Next</button>
            `;
        };

        const renderGrid = () => {
            if (!state.filtered.length) {
                grid.innerHTML = '<div class="gallery-empty">No images found.</div>';
                pagination.innerHTML = '';
                return;
            }

            const grouped = state.filtered.reduce((acc, file) => {
                const folder = getFolderFromFile(file);
                if (!acc[folder]) {
                    acc[folder] = [];
                }
                acc[folder].push(file);
                return acc;
            }, {});

            const folderRows = Object.entries(grouped)
                .map(([folderName, files]) => ({ folderName, files }))
                .sort((a, b) => {
                    const aLatest = Math.max(...a.files.map((f) => new Date(f.created_at || 0).getTime()));
                    const bLatest = Math.max(...b.files.map((f) => new Date(f.created_at || 0).getTime()));
                    return bLatest - aLatest;
                });

            const start = (state.page - 1) * state.perPage;
            const pageFolders = folderRows.slice(start, start + state.perPage);

            grid.innerHTML = pageFolders.map((folder) => buildFolderCard(folder.folderName, folder.files)).join('');
            renderPagination(folderRows.length);
        };

        const applyFilters = () => {
            const query = (searchInput?.value || '').trim().toLowerCase();
            const category = categoryFilter?.value || 'all';
            const type = typeFilter?.value || 'all';

            state.filtered = state.allFiles.filter((file) => {
                const title = (file.filename || '').toLowerCase();
                const fileFolder = getFolderFromFile(file).toLowerCase();
                const fileType = (file.format || '').toLowerCase();
                return (!query || title.includes(query) || fileFolder.includes(query)) &&
                       (category === 'all' || fileFolder === category) &&
                       (type === 'all' || fileType === type);
            });
            state.page = 1;
            renderGrid();
        };

        const fillFilterOptions = () => {
            const categories = Array.from(new Set(state.allFiles.map(f => getFolderFromFile(f)).filter(Boolean))).sort();
            const types = Array.from(new Set(state.allFiles.map(f => f.format).filter(Boolean))).sort();

            categoryFilter.innerHTML = '<option value="all">All Folders</option>' + 
                categories.map(cat => `<option value="${escapeHtml(cat.toLowerCase())}">${escapeHtml(cat)}</option>`).join('');

            typeFilter.innerHTML = '<option value="all">All Files</option>' + 
                types.map(t => `<option value="${escapeHtml(t.toLowerCase())}">${escapeHtml(t.toUpperCase())}</option>`).join('');
        };

        const loadGalleryFiles = async () => {
            try {
                const response = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });
                const payload = await response.json();
                state.allFiles = Array.isArray(payload.files) ? payload.files : [];

                const summary = payload.summary || {};
                metricTotal.textContent = String(summary.totalImages || 0);
                metricStorage.textContent = formatStorageMB(Number(summary.storageBytes || 0));
                metricCategories.textContent = String(new Set(state.allFiles.map(f => getFolderFromFile(f))).size || 0);
                metricMonth.textContent = String(summary.thisMonth || 0);

                fillFilterOptions();
                applyFilters();
            } catch (error) {
                grid.innerHTML = '<div class="gallery-empty">Unable to load files. Check connection.</div>';
            }
        };

        // --- Cloudinary ---
        let uploadQueue = [];
        const openUploadWidget = () => {
            // Start a new auto-generated folder group for each upload launch.
            state.autoBatchKey = '';

            refreshUploadFolderUI();

            const desiredFolder = normalizeFolderValue(uploadFolderInput?.value) || state.uploadFolder;
            state.uploadFolder = desiredFolder || '';

            if (!state.uploadFolder) {
                alert('Please enter a Target Folder before uploading.');
                uploadFolderInput?.focus();
                return;
            }

            if (uploadFolderInput) {
                uploadFolderInput.value = state.uploadFolder;
            }

            const widget = cloudinary.createUploadWidget({
                cloudName: 'dik33xzef', 
                uploadPreset: 'ml_default',
                folder: state.uploadFolder,
                resourceType: 'auto'
            }, (error, result) => { 
            if (!error && result && result.event === "success") { 
                uploadQueue.push({
                    filename: result.info.original_filename,
                    image_url: result.info.secure_url,
                    public_id: result.info.public_id,
                    format: result.info.format,
                    bytes: result.info.bytes
                });
            }
            if (!error && result && result.event === "close" && uploadQueue.length > 0) {
                fetch(saveEndpoint, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': currentCsrfHash
                    },
                    body: JSON.stringify(uploadQueue)
                })
                .then(res => res.json())
                .then((data) => {
                    if (data.token) currentCsrfHash = data.token;
                    uploadQueue = [];
                    state.autoBatchKey = '';
                    refreshUploadFolderUI();
                    loadGalleryFiles(); 
                })
                .catch(err => console.error('Save failed:', err));
            }
            });

            widget.open();
        };

        document.getElementById("upload_widget_btn").addEventListener("click", openUploadWidget);
        folderConventionSelect?.addEventListener('change', refreshUploadFolderUI);
        uploadFolderInput?.addEventListener('input', () => {
            if (folderConventionSelect && folderConventionSelect.value !== 'manual') {
                folderConventionSelect.value = 'manual';
            }
            refreshUploadFolderUI();
        });

        // Listeners
        searchInput?.addEventListener('input', applyFilters);
        categoryFilter?.addEventListener('change', applyFilters);
        typeFilter?.addEventListener('change', applyFilters);
        
        gridBtn?.addEventListener('click', () => {
            grid.classList.remove('list-mode');
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        });
        
        listBtn?.addEventListener('click', () => {
            grid.classList.add('list-mode');
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        });

        grid?.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof Element)) {
                return;
            }

            const openBtn = target.closest('.gallery-open-folder-btn');
            if (!openBtn) {
                return;
            }

            const folderName = openBtn.getAttribute('data-folder');
            if (!folderName) {
                return;
            }

            const firstFile = state.allFiles.find((file) => getFolderFromFile(file) === folderName);
            openFolderModal(folderName, firstFile?.id || null);
        });

        previewCloseBtn?.addEventListener('click', closePreviewModal);
        previewModal?.addEventListener('click', (event) => {
            if (event.target === previewModal) {
                closePreviewModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && previewModal?.style.display === 'flex') {
                closePreviewModal();
            }
        });

        pagination?.addEventListener('click', (event) => {
            const target = event.target;
            const pageValue = target.getAttribute('data-page');
            const navValue = target.getAttribute('data-page-nav');
            if (pageValue) {
                state.page = Number(pageValue);
                renderGrid();
            } else if (navValue === 'prev' && state.page > 1) {
                state.page -= 1;
                renderGrid();
            } else if (navValue === 'next' && state.page < Math.ceil(state.filtered.length / state.perPage)) {
                state.page += 1;
                renderGrid();
            }
        });

        refreshUploadFolderUI();
        loadGalleryFiles();
    });
</script>