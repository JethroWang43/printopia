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

<?php include APPPATH . 'Controllers/admin_function/gallery_function.php'; ?>