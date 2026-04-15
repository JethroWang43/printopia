<script>
	(function () {
		if (window.__inventoryTabBound) return;
		window.__inventoryTabBound = true;

		const root = document.querySelector('[data-inventory-tab]');
		if (!root) return;

		const API_LIST = '<?= base_url('admin/inventory/list'); ?>';
		const API_SAVE = '<?= base_url('admin/inventory/save'); ?>';
		const API_DELETE_BASE = '<?= base_url('admin/inventory/delete'); ?>';
		const state = {
			search: '',
			status: 'all',
			view: 'list',
			page: 1,
			pageSize: 8,
			editId: null,
			loading: false
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

		const searchInput = root.querySelector('#invSearchInput');
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
		const modalDelete = document.querySelector('#invModalDelete');
		const viewModalBackdrop = document.querySelector('#invViewModalBackdrop');
		const viewModalClose = document.querySelector('#invViewModalClose');
		const viewInventoryId = document.querySelector('#invViewInventoryId');
		const viewProductName = document.querySelector('#invViewProductName');
		const viewStatus = document.querySelector('#invViewStatus');
		const viewDescription = document.querySelector('#invViewDescription');
		const viewStockQty = document.querySelector('#invViewStockQty');
		const viewReorderLevel = document.querySelector('#invViewReorderLevel');

		const inputProductName = document.querySelector('#invProductNameInput');
		const inputDescription = document.querySelector('#invDescriptionInput');
		const inputQuantity = document.querySelector('#invQuantityInput');
		const inputReorder = document.querySelector('#invReorderInput');

		const computeStatus = (item) => {
			const qty = Number(item.stock_qty) || 0;
			const reorder = Number(item.reorder_level) || 0;
			if (qty <= reorder) return 'restock';
			if (qty <= reorder + 5) return 'warn';
			return 'good';
		};

		let items = [];

		const loadItems = async () => {
			state.loading = true;
			try {
				const response = await fetch(API_LIST, {
					headers: { 'Accept': 'application/json' }
				});
				const payload = await response.json();
				const rows = Array.isArray(payload?.data) ? payload.data : [];
				items = rows.map((row) => {
					const stockQty = Number(row.stock_qty) || 0;
					const reorderLevel = Number(row.reorder_level) || 0;
					return {
						inventory_id: Number(row.inventory_id),
						product_name: row.product_name || '',
						description: row.description || '',
						stock_qty: stockQty,
						reorder_level: reorderLevel,
						status: computeStatus({ stock_qty: stockQty, reorder_level: reorderLevel })
					};
				});
			} catch (error) {
				console.error('Failed to load inventory:', error);
				alert('Failed to load inventory from database.');
			} finally {
				state.loading = false;
			}
		};

		const getFilteredItems = () => {
			return items.filter((item) => {
				const matchSearch = !state.search || `${item.inventory_id} ${item.product_name} ${item.description}`.toLowerCase().includes(state.search.toLowerCase());
				const matchStatus = state.status === 'all' || item.status === state.status;
				return matchSearch && matchStatus;
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
				tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#6b7798;">No inventory items found.</td></tr>';
				return;
			}

			tableBody.innerHTML = rows.map((item) => `
				<tr class="inv-row-clickable" data-view-id="${item.inventory_id}" tabindex="0" role="button" aria-label="View inventory ${item.inventory_id}">
					<td>${item.inventory_id}</td>
					<td>${item.product_name}</td>
					<td><span class="inv-pill ${statusClass[item.status]}">${statusLabel[item.status]}</span></td>
					<td>${item.description}</td>
					<td>${item.stock_qty}</td>
					<td>${item.reorder_level}</td>
					<td><button type="button" class="inv-secondary-btn inv-edit-btn" data-id="${item.inventory_id}">Edit</button></td>
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
				<article class="inv-grid-card" data-view-id="${item.inventory_id}" tabindex="0" role="button" aria-label="View inventory ${item.inventory_id}">
					<h5>Inventory #${item.inventory_id}</h5>
					<div class="inv-grid-meta">
						<span>Product: ${item.product_name}</span>
						<span>Status: ${statusLabel[item.status]}</span>
						<span>Description: ${item.description}</span>
						<span>Stock Qty: ${item.stock_qty}</span>
						<span>Reorder Level: ${item.reorder_level}</span>
						<button type="button" class="inv-secondary-btn inv-edit-btn" data-id="${item.inventory_id}">Edit</button>
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
			state.editId = item ? item.inventory_id : null;
			if (modalTitle) modalTitle.textContent = item ? 'Edit Inventory Item' : 'Add Inventory Item';
			if (modalDelete) modalDelete.style.display = item ? 'inline-block' : 'none';
			if (inputProductName) inputProductName.value = item ? item.product_name : '';
			if (inputDescription) inputDescription.value = item ? item.description : '';
			if (inputQuantity) inputQuantity.value = item ? String(item.stock_qty) : '0';
			if (inputReorder) inputReorder.value = item ? String(item.reorder_level) : '0';
			if (modalBackdrop) modalBackdrop.classList.add('show');
		};

		const closeModal = () => {
			if (modalBackdrop) modalBackdrop.classList.remove('show');
			state.editId = null;
			if (modalDelete) modalDelete.style.display = 'none';
		};

		const setViewModalOpen = (isOpen) => {
			if (!viewModalBackdrop) return;
			viewModalBackdrop.classList.toggle('show', isOpen);
			viewModalBackdrop.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
		};

		const openViewModal = (item) => {
			if (!item) return;
			if (viewInventoryId) viewInventoryId.textContent = String(item.inventory_id ?? '-');
			if (viewProductName) viewProductName.textContent = item.product_name || '-';
			if (viewStatus) viewStatus.textContent = statusLabel[item.status] || '-';
			if (viewDescription) viewDescription.textContent = item.description || '-';
			if (viewStockQty) viewStockQty.textContent = String(item.stock_qty ?? '-');
			if (viewReorderLevel) viewReorderLevel.textContent = String(item.reorder_level ?? '-');
			setViewModalOpen(true);
		};

		const deleteItem = async () => {
			if (!state.editId) return;

			const confirmed = window.confirm('Delete this inventory item? This action cannot be undone.');
			if (!confirmed) return;

			try {
				const response = await fetch(`${API_DELETE_BASE}/${state.editId}`, {
					method: 'POST',
					headers: {
						'Accept': 'application/json'
					}
				});

				const raw = await response.text();
				let json = {};
				try {
					json = raw ? JSON.parse(raw) : {};
				} catch (e) {
					json = {};
				}

				if (!response.ok) {
					const hint = response.status === 403 ? ' (request blocked by security filter)' : '';
					const details = json?.details ? `\nDetails: ${json.details}` : '';
					alert((json?.error || `Failed to delete inventory item. HTTP ${response.status}`) + hint + details);
					return;
				}

				closeModal();
				await loadItems();
				render();
				// Refresh sidebar inventory
				if (window.refreshSidebarInventory) window.refreshSidebarInventory();
			} catch (error) {
				console.error('Failed to delete inventory:', error);
				alert('Failed to delete inventory item.');
			}
		};

		const upsertItem = async () => {
			const productName = inputProductName ? inputProductName.value.trim() : '';
			const description = inputDescription ? inputDescription.value.trim() : '';
			const quantity = Number(inputQuantity && inputQuantity.value ? inputQuantity.value : 0);
			const reorderLevel = Number(inputReorder && inputReorder.value ? inputReorder.value : 0);

			if (productName.length < 1) {
				alert('Product name is required.');
				return;
			}

			if (description.length < 3) {
				alert('Item detail is required (at least 3 characters).');
				return;
			}

			if (quantity < 0 || reorderLevel < 0) {
				alert('Stock quantity and reorder level cannot be negative.');
				return;
			}

			const payload = {
				inventory_id: state.editId || undefined,
				product_name: productName,
				stock_qty: quantity,
				reorder_level: reorderLevel,
				description
			};

			try {
				const response = await fetch(API_SAVE, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json'
					},
					body: JSON.stringify(payload)
				});

				const raw = await response.text();
				let json = {};
				try {
					json = raw ? JSON.parse(raw) : {};
				} catch (e) {
					json = {};
				}

				if (!response.ok) {
					const hint = response.status === 403 ? ' (request blocked by security filter)' : '';
					const details = json?.details ? `\nDetails: ${json.details}` : '';
					alert((json?.error || `Failed to save inventory item. HTTP ${response.status}`) + hint + details);
					return;
				}

				closeModal();
				await loadItems();
				render();
				// Refresh sidebar inventory
				if (window.refreshSidebarInventory) window.refreshSidebarInventory();
			} catch (error) {
				console.error('Failed to save inventory:', error);
				alert('Failed to save inventory item.');
			}
		};

		if (searchInput) {
			searchInput.addEventListener('input', () => {
				state.search = searchInput.value.trim();
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
			if (editBtn) {
				const id = Number(editBtn.dataset.id);
				const item = items.find((row) => Number(row.inventory_id) === id);
				if (item) openModal(item);
				return;
			}

			const viewRow = event.target.closest('[data-view-id]');
			if (!viewRow) return;
			const id = Number(viewRow.dataset.viewId);
			const item = items.find((row) => Number(row.inventory_id) === id);
			if (item) openViewModal(item);
		});

		root.addEventListener('keydown', (event) => {
			if (event.key !== 'Enter' && event.key !== ' ') return;
			const target = event.target;
			if (!(target instanceof HTMLElement)) return;
			if (!target.hasAttribute('data-view-id')) return;
			event.preventDefault();
			const id = Number(target.dataset.viewId);
			const item = items.find((row) => Number(row.inventory_id) === id);
			if (item) openViewModal(item);
		});

		if (modalClose) modalClose.addEventListener('click', closeModal);
		if (modalCancel) modalCancel.addEventListener('click', closeModal);
		if (modalSave) modalSave.addEventListener('click', upsertItem);
		if (modalDelete) modalDelete.addEventListener('click', deleteItem);
		if (modalBackdrop) {
			modalBackdrop.addEventListener('click', (event) => {
				if (event.target === modalBackdrop) closeModal();
			});
		}
		if (viewModalClose) viewModalClose.addEventListener('click', () => setViewModalOpen(false));
		if (viewModalBackdrop) {
			viewModalBackdrop.addEventListener('click', (event) => {
				if (event.target === viewModalBackdrop) setViewModalOpen(false);
			});
		}

		const init = async () => {
			await loadItems();
			render();
		};

		init();
	})();
</script>