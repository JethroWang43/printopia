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
        grid-template-columns: minmax(220px, 1.35fr) minmax(150px, 0.9fr) minmax(130px, 0.8fr) auto auto;
        gap: 10px;
        margin-bottom: 14px;
        background: #fffdfd;
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
        cursor: not-allowed;
        opacity: 0.95;
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
    }

    .gallery-empty {
        border: 1px dashed #d6deed;
        border-radius: 10px;
        padding: 22px;
        text-align: center;
        color: #607092;
        background: #f9fbff;
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
        .gallery-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .gallery-metrics {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .gallery-toolbar {
            grid-template-columns: 1fr;
        }

        .gallery-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 620px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .gallery-metrics {
            grid-template-columns: 1fr;
        }
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
                <div class="gallery-metric-top"><span>Categories</span><span>🗂</span></div>
                <div class="gallery-metric-value" id="galleryMetricCategories">0</div>
            </article>
            <article class="gallery-metric">
                <div class="gallery-metric-top"><span>This Month</span><span>📈</span></div>
                <div class="gallery-metric-value" id="galleryMetricMonth">0</div>
            </article>
        </div>

        <div class="gallery-toolbar">
            <div class="gallery-field">🔍 <input id="gallerySearchInput" type="text" placeholder="Search images by title or user folder"></div>
            <div class="gallery-field">▾ <select id="galleryCategoryFilter"><option value="all">All Categories</option></select></div>
            <div class="gallery-field">▾ <select id="galleryTypeFilter"><option value="all">All Files</option></select></div>
            <div class="gallery-view-toggle">
                <button type="button" id="galleryGridViewBtn" class="active">▦ Grid</button>
                <button type="button" id="galleryListViewBtn">▤ List</button>
            </div>
            <button type="button" class="gallery-upload-btn" title="User uploads are managed in user-side flow">⬆ Upload Images</button>
        </div>

        <div class="gallery-grid" id="galleryCardGrid"></div>

        <div class="gallery-pagination" id="galleryPagination" aria-label="Gallery pagination"></div>
    </section>
</article>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const galleryRoot = document.querySelector('[data-gallery-tab]');
        if (!galleryRoot) {
            return;
        }

        const endpoint = '<?= base_url('admin/gallery/files'); ?>';
        const searchInput = document.querySelector('#gallerySearchInput');
        const categoryFilter = document.querySelector('#galleryCategoryFilter');
        const typeFilter = document.querySelector('#galleryTypeFilter');
        const grid = document.querySelector('#galleryCardGrid');
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
        };

        const escapeHtml = (value) => String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#39;');

        const formatStorageMB = (bytes) => (bytes / (1024 * 1024)).toFixed(2);

        const buildCard = (file) => {
            const safeTitle = escapeHtml(file.title || 'Untitled');
            const safeCategory = escapeHtml(file.category || 'general');
            const safeExt = escapeHtml(file.extension || 'FILE');
            const safePreview = escapeHtml(file.previewUrl || '');

            return `
                <article class="gallery-card" data-title="${safeTitle}" data-category="${safeCategory}" data-type="${safeExt.toLowerCase()}">
                    <img class="gallery-thumb" src="${safePreview}" alt="${safeTitle}">
                    <div class="gallery-card-body">
                        <h4>${safeTitle}</h4>
                        <div class="gallery-meta">
                            <span class="gallery-pill">${safeCategory}</span>
                            <span class="gallery-pill">${safeExt}</span>
                        </div>
                    </div>
                    <div class="gallery-card-footer">
                        <a class="gallery-view-btn" href="${safePreview}" target="_blank" rel="noopener">View</a>
                    </div>
                </article>
            `;
        };

        const renderPagination = () => {
            const totalPages = Math.max(1, Math.ceil(state.filtered.length / state.perPage));
            if (state.page > totalPages) {
                state.page = totalPages;
            }

            const pages = [];
            for (let i = 1; i <= totalPages; i++) {
                if (i <= 3 || i > totalPages - 2 || Math.abs(i - state.page) <= 1) {
                    pages.push(i);
                }
            }

            const compactPages = [];
            let prev = 0;
            pages.forEach((p) => {
                if (prev && p - prev > 1) {
                    compactPages.push('...');
                }
                compactPages.push(p);
                prev = p;
            });

            const previousDisabled = state.page <= 1 ? 'disabled' : '';
            const nextDisabled = state.page >= totalPages ? 'disabled' : '';

            pagination.innerHTML = `
                <button type="button" class="gallery-nav ${previousDisabled}" data-page-nav="prev">Previous</button>
                ${compactPages.map((page) => {
                    if (page === '...') {
                        return '<span>...</span>';
                    }
                    const active = page === state.page ? 'active' : '';
                    return `<button type="button" class="gallery-page ${active}" data-page="${page}">${page}</button>`;
                }).join('')}
                <button type="button" class="gallery-nav ${nextDisabled}" data-page-nav="next">Next</button>
            `;
        };

        const renderGrid = () => {
            if (!state.filtered.length) {
                grid.innerHTML = '<div class="gallery-empty">No user files found in writable/uploads. Add user uploads there (preferably by user folder) and reload this tab.</div>';
                pagination.innerHTML = '';
                return;
            }

            const start = (state.page - 1) * state.perPage;
            const pageItems = state.filtered.slice(start, start + state.perPage);
            grid.innerHTML = pageItems.map(buildCard).join('');
            renderPagination();
        };

        const applyFilters = () => {
            const query = (searchInput?.value || '').trim().toLowerCase();
            const category = categoryFilter?.value || 'all';
            const type = typeFilter?.value || 'all';

            state.filtered = state.allFiles.filter((file) => {
                const title = (file.title || '').toLowerCase();
                const fileCategory = (file.category || '').toLowerCase();
                const fileType = (file.extension || '').toLowerCase();

                const matchesQuery = !query || title.includes(query) || fileCategory.includes(query);
                const matchesCategory = category === 'all' || fileCategory === category;
                const matchesType = type === 'all' || fileType === type;

                return matchesQuery && matchesCategory && matchesType;
            });

            state.page = 1;
            renderGrid();
        };

        const setViewMode = (mode) => {
            const isGrid = mode === 'grid';
            grid.classList.toggle('list-mode', !isGrid);
            gridBtn.classList.toggle('active', isGrid);
            listBtn.classList.toggle('active', !isGrid);
        };

        const fillFilterOptions = () => {
            const categories = Array.from(new Set(state.allFiles.map((f) => (f.category || 'general').toLowerCase()))).sort();
            const types = Array.from(new Set(state.allFiles.map((f) => (f.extension || '').toLowerCase()))).sort();

            categoryFilter.innerHTML = '<option value="all">All Categories</option>' + categories
                .map((category) => `<option value="${escapeHtml(category)}">${escapeHtml(category)}</option>`)
                .join('');

            typeFilter.innerHTML = '<option value="all">All Files</option>' + types
                .map((type) => `<option value="${escapeHtml(type)}">${escapeHtml(type.toUpperCase())}</option>`)
                .join('');
        };

        const loadGalleryFiles = async () => {
            try {
                const response = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) {
                    throw new Error('Failed to fetch gallery files');
                }

                const payload = await response.json();
                state.allFiles = Array.isArray(payload.files) ? payload.files : [];

                const summary = payload.summary || {};
                metricTotal.textContent = String(summary.totalImages || 0);
                metricStorage.textContent = formatStorageMB(Number(summary.storageBytes || 0));
                metricCategories.textContent = String(summary.categories || 0);
                metricMonth.textContent = String(summary.thisMonth || 0);

                fillFilterOptions();
                applyFilters();
            } catch (error) {
                grid.innerHTML = '<div class="gallery-empty">Unable to load user files right now. Please try again.</div>';
                pagination.innerHTML = '';
            }
        };

        searchInput?.addEventListener('input', applyFilters);
        categoryFilter?.addEventListener('change', applyFilters);
        typeFilter?.addEventListener('change', applyFilters);

        pagination?.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const pageValue = target.getAttribute('data-page');
            const navValue = target.getAttribute('data-page-nav');

            const totalPages = Math.max(1, Math.ceil(state.filtered.length / state.perPage));

            if (pageValue) {
                state.page = Number(pageValue);
                renderGrid();
                return;
            }

            if (navValue === 'prev' && state.page > 1) {
                state.page -= 1;
                renderGrid();
            }

            if (navValue === 'next' && state.page < totalPages) {
                state.page += 1;
                renderGrid();
            }
        });

        gridBtn?.addEventListener('click', () => setViewMode('grid'));
        listBtn?.addEventListener('click', () => setViewMode('list'));

        loadGalleryFiles();
    });
</script>
