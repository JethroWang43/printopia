<style>
	.inv-tab {
		background: #ffffff;
		border: 1px solid #d4deec;
		border-radius: 14px;
		box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
		padding: 18px;
	}

	.inv-tab-head {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		gap: 12px;
		margin-bottom: 14px;
	}

	.inv-tab-title {
		margin: 0;
		color: #132659;
		font-size: clamp(1.5rem, 2.1vw, 2.1rem);
		line-height: 1.05;
		font-weight: 800;
	}

	.inv-actions {
		display: flex;
		gap: 8px;
	}

	.inv-actions .inv-add-btn {
		height: 38px;
		border: 1px solid #0f72b4;
		border-radius: 8px;
		background: #198bd2;
		color: #fff;
		padding: 0 14px;
		font-family: inherit;
		font-size: 0.88rem;
		font-weight: 800;
		cursor: pointer;
	}

	.inv-toolbar {
		border: 1px solid #e0e7f3;
		border-radius: 10px;
		padding: 10px;
		display: grid;
		grid-template-columns: minmax(220px, 1.3fr) minmax(160px, 1fr) minmax(130px, 0.9fr) auto;
		gap: 10px;
		margin-bottom: 12px;
	}

	.inv-field {
		min-height: 40px;
		border: 1px solid #ced8ea;
		border-radius: 8px;
		display: flex;
		align-items: center;
		gap: 8px;
		padding: 0 12px;
		font-size: 0.9rem;
		color: #4f5b7d;
		font-weight: 600;
		background: #fff;
	}

	.inv-field input,
	.inv-field select {
		width: 100%;
		border: 0;
		outline: none;
		background: transparent;
		font: inherit;
		color: #2e3d62;
	}

	.inv-view-toggle {
		justify-content: center;
		padding: 0;
	}

	.inv-view-toggle button {
		height: 100%;
		border: 0;
		background: transparent;
		font-family: inherit;
		font-size: 0.82rem;
		font-weight: 700;
		color: #4f5b7d;
		padding: 0 10px;
		cursor: pointer;
	}

	.inv-view-toggle button.active {
		background: #e9f4ff;
		color: #1a588f;
	}

	.inv-table-wrap {
		border: 1px solid #cad8ee;
		border-radius: 10px;
		overflow: hidden;
		background: #fff;
	}

	.inv-grid-wrap {
		display: none;
		margin-top: 12px;
		grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
		gap: 10px;
	}

	.inv-grid-wrap.active {
		display: grid;
	}

	.inv-grid-card {
		border: 1px solid #d5dfee;
		border-radius: 10px;
		padding: 12px;
		background: #ffffff;
	}

	.inv-grid-card h5 {
		margin: 0 0 8px;
		color: #1f2f58;
		font-size: 1rem;
	}

	.inv-grid-meta {
		display: grid;
		gap: 6px;
		color: #5f6d8d;
		font-size: 0.84rem;
	}

	.inv-table {
		width: 100%;
		border-collapse: collapse;
	}

	.inv-table thead tr {
		background: #1788ce;
		color: #fff;
	}

	.inv-table th,
	.inv-table td {
		padding: 12px 14px;
		text-align: left;
		white-space: nowrap;
	}

	.inv-table th {
		font-size: 0.9rem;
		font-weight: 700;
	}

	.inv-table td {
		border-top: 1px solid #e8eef8;
		color: #243150;
		font-size: 0.95rem;
	}

	.inv-pill {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 999px;
		padding: 4px 12px;
		color: #fff;
		font-size: 0.82rem;
		font-weight: 700;
	}

	.inv-pill.good { background: #0b84cf; }
	.inv-pill.warn { background: #c98a16; }
	.inv-pill.restock { background: #ce1f1f; }

	.inv-pagination {
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 12px;
		padding-top: 14px;
		color: #667493;
		font-size: 0.9rem;
	}

	.inv-pagination button {
		border: 0;
		background: transparent;
		color: inherit;
		font: inherit;
		cursor: pointer;
		padding: 0;
	}

	.inv-pagination button:disabled {
		opacity: 0.45;
		cursor: not-allowed;
	}

	.inv-pagination .current {
		width: 26px;
		height: 26px;
		border-radius: 6px;
		display: inline-grid;
		place-items: center;
		background: #162b5e;
		color: #fff;
		font-weight: 700;
	}

	.inv-note {
		margin-top: 10px;
		font-size: 0.8rem;
		color: #6b7798;
	}

	.inv-modal-backdrop {
		position: fixed;
		inset: 0;
		background: rgba(16, 34, 79, 0.38);
		display: none;
		align-items: center;
		justify-content: center;
		z-index: 1200;
		padding: 12px;
	}

	.inv-modal-backdrop.show {
		display: flex;
	}

	.inv-modal {
		width: min(560px, 96vw);
		background: #ffffff;
		border: 1px solid #d5deed;
		border-radius: 12px;
		padding: 14px;
		box-shadow: 0 14px 24px rgba(16, 34, 79, 0.2);
	}

	.inv-modal-head {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 10px;
	}

	.inv-modal-head h4 {
		margin: 0;
		color: #17306a;
	}

	.inv-modal-close {
		border: 0;
		background: transparent;
		font-size: 1.05rem;
		font-weight: 700;
		color: #506089;
		cursor: pointer;
	}

	.inv-form-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 10px;
	}

	.inv-field-block label {
		display: block;
		margin-bottom: 6px;
		font-size: 0.84rem;
		font-weight: 700;
		color: #2e3c61;
	}

	.inv-input,
	.inv-select {
		width: 100%;
		min-height: 38px;
		border: 1px solid #ced8ea;
		border-radius: 8px;
		padding: 0 10px;
		font-family: inherit;
		font-size: 0.9rem;
		color: #2d3a5d;
		background: #ffffff;
	}

	.inv-modal-actions {
		display: flex;
		justify-content: flex-end;
		gap: 8px;
		margin-top: 12px;
	}

	.inv-secondary-btn,
	.inv-primary-btn {
		border-radius: 8px;
		padding: 8px 12px;
		font-family: inherit;
		font-size: 0.86rem;
		font-weight: 700;
		cursor: pointer;
	}

	.inv-secondary-btn {
		border: 1px solid #cfd8ea;
		background: #ffffff;
		color: #44547a;
	}

	.inv-primary-btn {
		border: 1px solid #0f72b4;
		background: #198bd2;
		color: #ffffff;
	}

	@media (max-width: 900px) {
		.inv-toolbar {
			grid-template-columns: 1fr;
		}

		.inv-table-wrap {
			overflow-x: auto;
		}

		.inv-form-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

<article class="content-section" id="inventory-management">
	<section class="inv-tab" data-inventory-tab>
		<div class="inv-tab-head">
			<h3 class="inv-tab-title">Inventory<br>Management</h3>
			<div class="inv-actions">
				<button type="button" id="invAddBtn" class="inv-add-btn">Add Item</button>
			</div>
		</div>

		<div class="inv-toolbar">
			<div class="inv-field">🔍 <input type="text" id="invSearchInput" placeholder="Search inventory"></div>
			<div class="inv-field">▾ <select id="invCategoryFilter"><option value="all">All Categories</option><option value="drinkware">Drinkware</option><option value="banner">Banner</option><option value="apparel">Apparel</option></select></div>
			<div class="inv-field">◻ <select id="invStatusFilter"><option value="all">All Status</option><option value="good">Good</option><option value="warn">Warning</option><option value="restock">Re-Stock</option></select></div>
			<div class="inv-field inv-view-toggle"><button type="button" id="invListViewBtn" class="active">List</button><button type="button" id="invGridViewBtn">Grid</button></div>
		</div>

		<div class="inv-table-wrap">
			<table class="inv-table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Status</th>
						<th>Description</th>
						<th>Quantity</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="invTableBody"></tbody>
			</table>
		</div>

		<div class="inv-grid-wrap" id="invGridWrap"></div>

		<div class="inv-pagination">
			<button type="button" id="invPagePrev">← Previous</button>
			<span class="current" id="invCurrentPage">1</span>
			<span id="invPageMeta">1 / 1</span>
			<button type="button" id="invPageNext">Next →</button>
		</div>
		<p class="inv-note">Data is saved in browser storage only. Database is not connected yet.</p>
	</section>

	<div class="inv-modal-backdrop" id="invModalBackdrop" aria-hidden="true">
		<div class="inv-modal">
			<div class="inv-modal-head">
				<h4 id="invModalTitle">Add Inventory Item</h4>
				<button type="button" class="inv-modal-close" id="invModalClose">X</button>
			</div>
			<div class="inv-form-grid">
				<div class="inv-field-block">
					<label for="invNameInput">Name</label>
					<input id="invNameInput" class="inv-input" type="text">
				</div>
				<div class="inv-field-block">
					<label for="invCategoryInput">Category</label>
					<select id="invCategoryInput" class="inv-select">
						<option value="drinkware">Drinkware</option>
						<option value="banner">Banner</option>
						<option value="apparel">Apparel</option>
					</select>
				</div>
				<div class="inv-field-block">
					<label for="invStatusInput">Status</label>
					<select id="invStatusInput" class="inv-select">
						<option value="good">Good</option>
						<option value="warn">Warning</option>
						<option value="restock">Re-Stock</option>
					</select>
				</div>
				<div class="inv-field-block">
					<label for="invDescriptionInput">Description</label>
					<input id="invDescriptionInput" class="inv-input" type="text">
				</div>
				<div class="inv-field-block">
					<label for="invQuantityInput">Quantity</label>
					<input id="invQuantityInput" class="inv-input" type="number" min="0">
				</div>
			</div>
			<div class="inv-modal-actions">
				<button type="button" class="inv-secondary-btn" id="invModalCancel">Cancel</button>
				<button type="button" class="inv-primary-btn" id="invModalSave">Save</button>
			</div>
		</div>
	</div>
</article>

<script>
	(function () {
		if (window.__inventoryTabBound) return;
		window.__inventoryTabBound = true;

		const root = document.querySelector('[data-inventory-tab]');
		if (!root) return;

		const STORAGE_KEY = 'inventoryTabItems_v1';
		const state = {
			search: '',
			category: 'all',
			status: 'all',
			view: 'list',
			page: 1,
			pageSize: 8,
			editId: null
		};

		const statusLabel = {
			good: 'Good',
			warn: 'Warning',
			restock: 'Re-Stock'
		};

		const statusClass = {
			good: 'good',
			warn: 'warn',
			restock: 'restock'
		};

		const defaultItems = [
			{ id: 1, name: 'Mug', status: 'good', description: 'Ceramic', quantity: 50, category: 'drinkware' },
			{ id: 2, name: 'Mug', status: 'warn', description: 'Hard Plastic', quantity: 20, category: 'drinkware' },
			{ id: 3, name: 'Tarpulin', status: 'good', description: 'Polymer', quantity: 40, category: 'banner' },
			{ id: 4, name: 'Tote Bag', status: 'restock', description: 'Cotton', quantity: 10, category: 'apparel' },
			{ id: 5, name: 'Lanyard', status: 'warn', description: 'Polyester', quantity: 18, category: 'apparel' }
		];

		const searchInput = root.querySelector('#invSearchInput');
		const categoryFilter = root.querySelector('#invCategoryFilter');
		const statusFilter = root.querySelector('#invStatusFilter');
		const listViewBtn = root.querySelector('#invListViewBtn');
		const gridViewBtn = root.querySelector('#invGridViewBtn');
		const tableBody = root.querySelector('#invTableBody');
		const tableWrap = root.querySelector('.inv-table-wrap');
		const gridWrap = root.querySelector('#invGridWrap');
		const pagePrevBtn = root.querySelector('#invPagePrev');
		const pageNextBtn = root.querySelector('#invPageNext');
		const currentPageEl = root.querySelector('#invCurrentPage');
		const pageMetaEl = root.querySelector('#invPageMeta');

		const addBtn = root.querySelector('#invAddBtn');

		const modalBackdrop = document.querySelector('#invModalBackdrop');
		const modalTitle = document.querySelector('#invModalTitle');
		const modalClose = document.querySelector('#invModalClose');
		const modalCancel = document.querySelector('#invModalCancel');
		const modalSave = document.querySelector('#invModalSave');

		const inputName = document.querySelector('#invNameInput');
		const inputCategory = document.querySelector('#invCategoryInput');
		const inputStatus = document.querySelector('#invStatusInput');
		const inputDescription = document.querySelector('#invDescriptionInput');
		const inputQuantity = document.querySelector('#invQuantityInput');

		const loadItems = () => {
			try {
				const raw = localStorage.getItem(STORAGE_KEY);
				if (!raw) return defaultItems.slice();
				const parsed = JSON.parse(raw);
				return Array.isArray(parsed) ? parsed : defaultItems.slice();
			} catch (e) {
				return defaultItems.slice();
			}
		};

		let items = loadItems();

		const saveItems = () => {
			localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
		};

		const getFilteredItems = () => {
			return items.filter((item) => {
				const matchSearch = !state.search || `${item.name} ${item.description}`.toLowerCase().includes(state.search.toLowerCase());
				const matchCategory = state.category === 'all' || item.category === state.category;
				const matchStatus = state.status === 'all' || item.status === state.status;
				return matchSearch && matchCategory && matchStatus;
			});
		};

		const getPagedItems = (rows) => {
			const totalPages = Math.max(1, Math.ceil(rows.length / state.pageSize));
			state.page = Math.min(state.page, totalPages);
			const start = (state.page - 1) * state.pageSize;
			return { rows: rows.slice(start, start + state.pageSize), totalPages };
		};

		const renderTable = (rows) => {
			if (!tableBody) return;
			if (!rows.length) {
				tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#6b7798;">No inventory items found.</td></tr>';
				return;
			}

			tableBody.innerHTML = rows.map((item) => `
				<tr>
					<td>${item.id}</td>
					<td>${item.name}</td>
					<td><span class="inv-pill ${statusClass[item.status]}">${statusLabel[item.status]}</span></td>
					<td>${item.description}</td>
					<td>${item.quantity}</td>
					<td><button type="button" class="inv-secondary-btn inv-edit-btn" data-id="${item.id}">Edit</button></td>
				</tr>
			`).join('');
		};

		const renderGrid = (rows) => {
			if (!gridWrap) return;
			if (!rows.length) {
				gridWrap.innerHTML = '<article class="inv-grid-card"><h5>No items</h5><div class="inv-grid-meta"><span>Try changing search/filter settings.</span></div></article>';
				return;
			}

			gridWrap.innerHTML = rows.map((item) => `
				<article class="inv-grid-card">
					<h5>${item.name}</h5>
					<div class="inv-grid-meta">
						<span>ID: ${item.id}</span>
						<span>Status: ${statusLabel[item.status]}</span>
						<span>Description: ${item.description}</span>
						<span>Quantity: ${item.quantity}</span>
						<button type="button" class="inv-secondary-btn inv-edit-btn" data-id="${item.id}">Edit</button>
					</div>
				</article>
			`).join('');
		};

		const renderPagination = (totalPages) => {
			if (currentPageEl) currentPageEl.textContent = String(state.page);
			if (pageMetaEl) pageMetaEl.textContent = `${state.page} / ${totalPages}`;
			if (pagePrevBtn) pagePrevBtn.disabled = state.page <= 1;
			if (pageNextBtn) pageNextBtn.disabled = state.page >= totalPages;
		};

		const render = () => {
			const filtered = getFilteredItems();
			const { rows, totalPages } = getPagedItems(filtered);
			renderTable(rows);
			renderGrid(rows);
			renderPagination(totalPages);

			const isGrid = state.view === 'grid';
			if (tableWrap) tableWrap.style.display = isGrid ? 'none' : 'block';
			if (gridWrap) gridWrap.classList.toggle('active', isGrid);
			if (listViewBtn) listViewBtn.classList.toggle('active', !isGrid);
			if (gridViewBtn) gridViewBtn.classList.toggle('active', isGrid);
		};

		const openModal = (item) => {
			state.editId = item ? item.id : null;
			if (modalTitle) modalTitle.textContent = item ? 'Edit Inventory Item' : 'Add Inventory Item';
			if (inputName) inputName.value = item ? item.name : '';
			if (inputCategory) inputCategory.value = item ? item.category : 'drinkware';
			if (inputStatus) inputStatus.value = item ? item.status : 'good';
			if (inputDescription) inputDescription.value = item ? item.description : '';
			if (inputQuantity) inputQuantity.value = item ? String(item.quantity) : '0';
			if (modalBackdrop) modalBackdrop.classList.add('show');
		};

		const closeModal = () => {
			if (modalBackdrop) modalBackdrop.classList.remove('show');
			state.editId = null;
		};

		const upsertItem = () => {
			const name = inputName ? inputName.value.trim() : '';
			const description = inputDescription ? inputDescription.value.trim() : '';
			const category = inputCategory ? inputCategory.value : 'drinkware';
			const status = inputStatus ? inputStatus.value : 'good';
			const quantity = Number(inputQuantity && inputQuantity.value ? inputQuantity.value : 0);

			if (!name) {
				alert('Name is required.');
				return;
			}

			if (state.editId) {
				items = items.map((item) => item.id === state.editId ? { ...item, name, description, category, status, quantity } : item);
			} else {
				const nextId = items.length ? Math.max(...items.map((item) => Number(item.id) || 0)) + 1 : 1;
				items.unshift({ id: nextId, name, description, category, status, quantity });
			}

			saveItems();
			closeModal();
			render();
		};

		if (searchInput) {
			searchInput.addEventListener('input', () => {
				state.search = searchInput.value.trim();
				state.page = 1;
				render();
			});
		}

		if (categoryFilter) {
			categoryFilter.addEventListener('change', () => {
				state.category = categoryFilter.value;
				state.page = 1;
				render();
			});
		}

		if (statusFilter) {
			statusFilter.addEventListener('change', () => {
				state.status = statusFilter.value;
				state.page = 1;
				render();
			});
		}

		if (listViewBtn) {
			listViewBtn.addEventListener('click', () => {
				state.view = 'list';
				render();
			});
		}
		if (gridViewBtn) {
			gridViewBtn.addEventListener('click', () => {
				state.view = 'grid';
				render();
			});
		}

		if (pagePrevBtn) {
			pagePrevBtn.addEventListener('click', () => {
				state.page = Math.max(1, state.page - 1);
				render();
			});
		}
		if (pageNextBtn) {
			pageNextBtn.addEventListener('click', () => {
				state.page += 1;
				render();
			});
		}

		if (addBtn) addBtn.addEventListener('click', () => openModal(null));

		root.addEventListener('click', (event) => {
			const editBtn = event.target.closest('.inv-edit-btn');
			if (!editBtn) return;
			const id = Number(editBtn.dataset.id);
			const item = items.find((row) => Number(row.id) === id);
			if (item) openModal(item);
		});

		if (modalClose) modalClose.addEventListener('click', closeModal);
		if (modalCancel) modalCancel.addEventListener('click', closeModal);
		if (modalSave) modalSave.addEventListener('click', upsertItem);
		if (modalBackdrop) {
			modalBackdrop.addEventListener('click', (event) => {
				if (event.target === modalBackdrop) closeModal();
			});
		}

		render();
	})();
</script>
