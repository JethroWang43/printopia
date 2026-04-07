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