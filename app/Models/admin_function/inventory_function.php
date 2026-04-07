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