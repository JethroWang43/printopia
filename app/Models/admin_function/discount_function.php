<script>
	(function () {
		if (window.__discountTabBound) return;
		window.__discountTabBound = true;

		const root = document.querySelector('[data-discount-tab]');
		if (!root) return;

		const manageView = root.querySelector('[data-discount-view="manage"]');
		const createView = root.querySelector('[data-discount-view="create"]');
		const openCreateBtn = root.querySelector('[data-discount-open-create]');
		const openManageBtn = root.querySelector('[data-discount-open-manage]');
		const addDiscountBtn = root.querySelector('#discountAddBtn');

		const searchInput = root.querySelector('#discountSearchInput');
		const categoryFilter = root.querySelector('#discountCategoryFilter');
		const statusFilter = root.querySelector('#discountStatusFilter');
		const listViewBtn = root.querySelector('#discountListViewBtn');
		const gridViewBtn = root.querySelector('#discountGridViewBtn');
		const tableWrap = root.querySelector('.discount-table-wrap');
		const gridWrap = root.querySelector('#discountGridWrap');
		const tableBody = root.querySelector('#discountTableBody');
		const prevPageBtn = root.querySelector('#discountPrevPageBtn');
		const nextPageBtn = root.querySelector('#discountNextPageBtn');
		const currentPageEl = root.querySelector('#discountCurrentPage');
		const pageMetaEl = root.querySelector('#discountPageMeta');

		const methodCodeBtn = root.querySelector('#discountMethodCodeBtn');
		const methodAutoBtn = root.querySelector('#discountMethodAutoBtn');
		const codeInput = root.querySelector('#discountCodeInput');
		const percentInput = root.querySelector('#discountPercentInput');
		const userRadios = root.querySelectorAll('input[name="discountUserType"]');
		const segmentSelect = root.querySelector('#discountSegmentSelect');
		const specificCustomerInput = root.querySelector('#discountSpecificCustomerInput');
		const startInput = root.querySelector('#discountStartInput');
		const endInput = root.querySelector('#discountEndInput');
		const limitToggle = root.querySelector('#discountLimitToggle');
		const limitInput = root.querySelector('#discountLimitInput');
		const oneTimeToggle = root.querySelector('#discountOneTimeToggle');

		const summaryStatus = root.querySelector('#discountSummaryStatus');
		const summaryCode = root.querySelector('#discountSummaryCode');
		const summaryDiscount = root.querySelector('#discountSummaryDiscount');
		const summaryUser = root.querySelector('#discountSummaryUser');
		const summaryLimit = root.querySelector('#discountSummaryLimit');
		const summarySchedule = root.querySelector('#discountSummarySchedule');

		const discounts = [
			{ id: 1, code: 'X530fe', status: 'active', customer: 'all', discount: 15, limitUse: 8, category: 'seasonal' },
			{ id: 2, code: 'HAPFEB', status: 'ended', customer: 'specific', discount: 20, limitUse: 4, category: 'vip' },
			{ id: 3, code: 'SUMMER10', status: 'active', customer: 'all', discount: 10, limitUse: 6, category: 'seasonal' },
			{ id: 4, code: 'VIP25', status: 'ended', customer: 'specific', discount: 25, limitUse: 2, category: 'vip' },
			{ id: 5, code: 'WINTER8', status: 'active', customer: 'all', discount: 8, limitUse: 9, category: 'seasonal' },
			{ id: 6, code: 'MEMBER12', status: 'active', customer: 'specific', discount: 12, limitUse: 5, category: 'vip' }
		];

		const state = {
			search: '',
			category: 'all',
			status: 'all',
			view: 'list',
			page: 1,
			pageSize: 8
		};

		const switchView = (view) => {
			const isCreate = view === 'create';
			manageView.classList.toggle('active', !isCreate);
			createView.classList.toggle('active', isCreate);
		};

		const getFilteredDiscounts = () => {
			return discounts.filter((item) => {
				const matchSearch = !state.search || item.code.toLowerCase().includes(state.search.toLowerCase());
				const matchCategory = state.category === 'all' || item.category === state.category;
				const matchStatus = state.status === 'all' || item.status === state.status;
				return matchSearch && matchCategory && matchStatus;
			});
		};

		const getPagedDiscounts = (rows) => {
			const totalPages = Math.max(1, Math.ceil(rows.length / state.pageSize));
			state.page = Math.min(state.page, totalPages);
			const start = (state.page - 1) * state.pageSize;
			return {
				rows: rows.slice(start, start + state.pageSize),
				totalPages
			};
		};

		const renderListRows = (rows) => {
			if (!tableBody) return;
			if (!rows.length) {
				tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#6b7798;">No discounts match your filters.</td></tr>';
				return;
			}

			tableBody.innerHTML = rows.map((item) => `
				<tr>
					<td>${item.id}</td>
					<td>${item.code}</td>
					<td><span class="discount-badge ${item.status}">${item.status === 'active' ? 'Active' : 'Ended'}</span></td>
					<td><span class="discount-tag ${item.customer === 'specific' ? 'specific' : ''}">${item.customer === 'all' ? 'ALL' : 'Specific'}</span></td>
					<td>${item.discount}%</td>
					<td>${item.limitUse}</td>
					<td><span class="discount-edit">✎</span></td>
				</tr>
			`).join('');
		};

		const renderGridRows = (rows) => {
			if (!gridWrap) return;
			if (!rows.length) {
				gridWrap.innerHTML = '<div class="discount-grid-card"><h5>No discounts found</h5><div class="discount-grid-meta"><span>Try adjusting search or filters.</span></div></div>';
				return;
			}

			gridWrap.innerHTML = rows.map((item) => `
				<div class="discount-grid-card">
					<h5>${item.code}</h5>
					<div class="discount-grid-meta">
						<span>Status: ${item.status === 'active' ? 'Active' : 'Ended'}</span>
						<span>Customer: ${item.customer === 'all' ? 'ALL' : 'Specific'}</span>
						<span>Discount: ${item.discount}%</span>
						<span>Limit: ${item.limitUse}</span>
					</div>
				</div>
			`).join('');
		};

		const renderPagination = (totalPages) => {
			if (currentPageEl) currentPageEl.textContent = String(state.page);
			if (pageMetaEl) pageMetaEl.textContent = `${state.page} / ${totalPages}`;
			if (prevPageBtn) prevPageBtn.disabled = state.page <= 1;
			if (nextPageBtn) nextPageBtn.disabled = state.page >= totalPages;
		};

		const renderManageView = () => {
			const filtered = getFilteredDiscounts();
			const { rows, totalPages } = getPagedDiscounts(filtered);
			renderListRows(rows);
			renderGridRows(rows);
			renderPagination(totalPages);

			const isGrid = state.view === 'grid';
			if (tableWrap) tableWrap.style.display = isGrid ? 'none' : 'block';
			if (gridWrap) gridWrap.classList.toggle('active', isGrid);
			if (listViewBtn) listViewBtn.classList.toggle('active', !isGrid);
			if (gridViewBtn) gridViewBtn.classList.toggle('active', isGrid);
		};

		const selectedUserLabel = () => {
			const checked = root.querySelector('input[name="discountUserType"]:checked');
			if (!checked || checked.value === 'all') return 'All Customer';
			if (checked.value === 'segment') return segmentSelect && segmentSelect.value ? segmentSelect.value : 'Segment';
			return specificCustomerInput && specificCustomerInput.value ? specificCustomerInput.value : 'Specific Customer';
		};

		const updateSummary = () => {
			if (summaryCode) summaryCode.textContent = (codeInput && codeInput.value.trim()) || 'DRAFT';
			if (summaryDiscount) summaryDiscount.textContent = `${Number(percentInput && percentInput.value || 0)}%`;
			if (summaryUser) summaryUser.textContent = selectedUserLabel();
			if (summaryLimit) {
				summaryLimit.textContent = limitToggle && limitToggle.checked
					? String(limitInput && limitInput.value ? limitInput.value : '0')
					: (oneTimeToggle && oneTimeToggle.checked ? 'One-time only' : 'Unlimited');
			}
			if (summarySchedule) {
				const start = startInput && startInput.value ? startInput.value.replace('T', ' ') : '-';
				const end = endInput && endInput.value ? endInput.value.replace('T', ' ') : '-';
				summarySchedule.textContent = `${start} to ${end}`;
			}
			if (summaryStatus) summaryStatus.textContent = 'Draft';
		};

		if (openCreateBtn) {
			openCreateBtn.addEventListener('click', () => switchView('create'));
		}
		if (openManageBtn) {
			openManageBtn.addEventListener('click', () => switchView('manage'));
		}

		if (searchInput) {
			searchInput.addEventListener('input', () => {
				state.search = searchInput.value.trim();
				state.page = 1;
				renderManageView();
			});
		}
		if (categoryFilter) {
			categoryFilter.addEventListener('change', () => {
				state.category = categoryFilter.value;
				state.page = 1;
				renderManageView();
			});
		}
		if (statusFilter) {
			statusFilter.addEventListener('change', () => {
				state.status = statusFilter.value;
				state.page = 1;
				renderManageView();
			});
		}
		if (listViewBtn) {
			listViewBtn.addEventListener('click', () => {
				state.view = 'list';
				renderManageView();
			});
		}
		if (gridViewBtn) {
			gridViewBtn.addEventListener('click', () => {
				state.view = 'grid';
				renderManageView();
			});
		}
		if (prevPageBtn) {
			prevPageBtn.addEventListener('click', () => {
				state.page = Math.max(1, state.page - 1);
				renderManageView();
			});
		}
		if (nextPageBtn) {
			nextPageBtn.addEventListener('click', () => {
				state.page += 1;
				renderManageView();
			});
		}

		if (methodCodeBtn && methodAutoBtn) {
			methodCodeBtn.addEventListener('click', () => {
				methodCodeBtn.classList.add('active');
				methodAutoBtn.classList.remove('active');
			});
			methodAutoBtn.addEventListener('click', () => {
				methodAutoBtn.classList.add('active');
				methodCodeBtn.classList.remove('active');
			});
		}

		[codeInput, percentInput, segmentSelect, specificCustomerInput, startInput, endInput, limitInput, limitToggle, oneTimeToggle].forEach((el) => {
			if (!el) return;
			el.addEventListener('input', updateSummary);
			el.addEventListener('change', updateSummary);
		});
		userRadios.forEach((radio) => radio.addEventListener('change', updateSummary));

		if (addDiscountBtn) {
			addDiscountBtn.addEventListener('click', () => {
				alert('Create Discount is not available yet because the database is not connected.');
			});
		}

		renderManageView();
		updateSummary();
	})();
</script>