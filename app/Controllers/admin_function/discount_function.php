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
		const specificCustomerIdInput = root.querySelector('#discountSpecificCustomerId');
		const userDropdown = root.querySelector('#discountUserDropdown');
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

		const apiBase = window.location.pathname.toLowerCase().includes('/printopia/')
			? '/printopia/admin/discount'
			: '/admin/discount';

		let serverRows = [];
		let searchDebounceTimer = null;
		let userSearchDebounceTimer = null;
		let editingDiscountId = null;

		const state = {
			search: '',
			category: 'all',
			status: 'all',
			view: 'list',
			page: 1,
			pageSize: 8,
		};

		const switchView = (view) => {
			const isCreate = view === 'create';
			manageView.classList.toggle('active', !isCreate);
			createView.classList.toggle('active', isCreate);
		};

		const toDateTimeLocal = (value) => {
			if (!value) return '';
			const normalized = String(value).replace(' ', 'T');
			const date = new Date(normalized);
			if (Number.isNaN(date.getTime())) return '';
			const pad = (n) => String(n).padStart(2, '0');
			const yyyy = date.getFullYear();
			const mm = pad(date.getMonth() + 1);
			const dd = pad(date.getDate());
			const hh = pad(date.getHours());
			const mi = pad(date.getMinutes());
			return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
		};

		const getPagedDiscounts = (rows) => {
			const totalPages = Math.max(1, Math.ceil(rows.length / state.pageSize));
			state.page = Math.min(state.page, totalPages);
			const start = (state.page - 1) * state.pageSize;
			return {
				rows: rows.slice(start, start + state.pageSize),
				totalPages,
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
					<td>${item.discount_id}</td>
					<td>${item.code || '-'}</td>
					<td><span class="discount-badge ${item.status === 'ended' ? 'ended' : 'active'}">${item.status === 'ended' ? 'Ended' : 'Active'}</span></td>
					<td><span class="discount-tag ${item.category === 'vip' ? 'specific' : ''}">${item.category || 'general'}</span></td>
					<td>${Number(item.discount_percent || 0)}%</td>
					<td>${item.max_uses ?? '-'}</td>
					<td>
						<button type="button" class="discount-edit" data-discount-edit="${item.discount_id}" title="Edit">✎</button>
						<button type="button" class="discount-edit" data-discount-delete="${item.discount_id}" title="Delete">🗑</button>
					</td>
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
					<h5>${item.code || '-'}</h5>
					<div class="discount-grid-meta">
						<span>Status: ${item.status === 'ended' ? 'Ended' : 'Active'}</span>
						<span>Category: ${item.category || 'general'}</span>
						<span>Discount: ${Number(item.discount_percent || 0)}%</span>
						<span>Limit: ${item.max_uses ?? '-'}</span>
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

		const fetchDiscounts = async () => {
			const query = new URLSearchParams();
			if (state.search) query.set('search', state.search);
			if (state.category && state.category !== 'all') query.set('category', state.category);
			if (state.status && state.status !== 'all') query.set('status', state.status);

			const response = await fetch(`${apiBase}/list?${query.toString()}`);
			if (!response.ok) {
				throw new Error('Failed to load discounts.');
			}

			const payload = await response.json();
			serverRows = Array.isArray(payload.data) ? payload.data : [];
		};

		const closeUserDropdown = () => {
			if (!userDropdown) return;
			userDropdown.classList.remove('active');
			userDropdown.innerHTML = '';
		};

		const renderUserDropdown = (users) => {
			if (!userDropdown) return;
			if (!Array.isArray(users) || users.length === 0) {
				userDropdown.innerHTML = '<div class="discount-user-option">No matching users</div>';
				userDropdown.classList.add('active');
				return;
			}

			userDropdown.innerHTML = users.map((user) => {
				const label = user.label || user.name || `User #${user.user_id}`;
				return `<div class="discount-user-option" data-user-id="${user.user_id}" data-user-label="${String(label).replace(/"/g, '&quot;')}">${label}</div>`;
			}).join('');
			userDropdown.classList.add('active');
		};

		const searchUsers = async (query) => {
			const response = await fetch(`${apiBase}/users?q=${encodeURIComponent(query)}`);
			if (!response.ok) {
				throw new Error('Failed to search users.');
			}

			const payload = await response.json();
			return Array.isArray(payload.data) ? payload.data : [];
		};

		const renderManageView = async () => {
			try {
				await fetchDiscounts();
			} catch (error) {
				if (tableBody) {
					tableBody.innerHTML = `<tr><td colspan="7" style="text-align:center;color:#7f1d1d;">${error.message}</td></tr>`;
				}
				serverRows = [];
			}

			const { rows, totalPages } = getPagedDiscounts(serverRows);
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
			if (summaryDiscount) summaryDiscount.textContent = `${Number((percentInput && percentInput.value) || 0)}%`;
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
			if (summaryStatus) summaryStatus.textContent = editingDiscountId ? 'Editing' : 'Draft';
		};

		const setRadioValue = (value) => {
			const target = root.querySelector(`input[name="discountUserType"][value="${value}"]`);
			if (target) target.checked = true;
		};

		const openEditMode = (item) => {
			if (!item) return;

			editingDiscountId = Number(item.discount_id || 0) || null;

			if (codeInput) codeInput.value = item.code || '';
			if (percentInput) percentInput.value = Number(item.discount_percent || 0);
			if (startInput) startInput.value = toDateTimeLocal(item.start_at);
			if (endInput) endInput.value = toDateTimeLocal(item.end_at);
			if (limitToggle) limitToggle.checked = item.max_uses !== null && item.max_uses !== undefined;
			if (limitInput) limitInput.value = item.max_uses || '';
			if (oneTimeToggle) oneTimeToggle.checked = Number(item.one_time_only || 0) === 1;

			if (item.selection === 'automatic') {
				methodAutoBtn?.classList.add('active');
				methodCodeBtn?.classList.remove('active');
			} else {
				methodCodeBtn?.classList.add('active');
				methodAutoBtn?.classList.remove('active');
			}

			const eligibilityType = item.eligibility_type || 'all';
			if (eligibilityType === 'segment') {
				setRadioValue('segment');
				if (segmentSelect) segmentSelect.value = item.segment_name_id || '';
				if (specificCustomerInput) specificCustomerInput.value = '';
				if (specificCustomerIdInput) specificCustomerIdInput.value = '';
			} else if (eligibilityType === 'specific') {
				setRadioValue('specific');
				const segmentValue = String(item.segment_name_id || '');
				if (segmentValue.startsWith('user:')) {
					const userId = segmentValue.split(':')[1] || '';
					if (specificCustomerIdInput) specificCustomerIdInput.value = userId;
					if (specificCustomerInput) specificCustomerInput.value = `User #${userId}`;
				} else {
					if (specificCustomerInput) specificCustomerInput.value = segmentValue;
					if (specificCustomerIdInput) specificCustomerIdInput.value = '';
				}
			} else {
				setRadioValue('all');
				if (specificCustomerInput) specificCustomerInput.value = '';
				if (specificCustomerIdInput) specificCustomerIdInput.value = '';
			}

			if (addDiscountBtn) addDiscountBtn.textContent = 'Update Discount';
			switchView('create');
			updateSummary();
		};

		const resetCreateForm = () => {
			editingDiscountId = null;
			if (addDiscountBtn) addDiscountBtn.textContent = 'Add Discount';
		};

		const collectPayload = () => {
			const selectedUserType = root.querySelector('input[name="discountUserType"]:checked')?.value || 'all';
			const selectedMethod = methodAutoBtn && methodAutoBtn.classList.contains('active') ? 'automatic' : 'code';
			const hasLimit = !!(limitToggle && limitToggle.checked);

			let category = 'general';
			if (selectedUserType === 'segment') category = 'vip';
			if (selectedUserType === 'all') category = 'seasonal';

			const payload = {
				code: (codeInput?.value || '').trim(),
				discount_percent: Number(percentInput?.value || 0),
				selection: selectedMethod,
				status: 'active',
				category,
				start_at: startInput?.value || null,
				end_at: endInput?.value || null,
				max_uses: hasLimit ? Number(limitInput?.value || 0) : null,
				one_time_only: !!(oneTimeToggle && oneTimeToggle.checked),
				eligibility_type: selectedUserType,
				segment_name_id: selectedUserType === 'segment' ? (segmentSelect?.value || '') : '',
				specific_customer: selectedUserType === 'specific' ? (specificCustomerInput?.value || '').trim() : '',
				specific_customer_id: selectedUserType === 'specific' ? (specificCustomerIdInput?.value || '') : '',
				eligibility_status_id: 1,
			};

			if (editingDiscountId) {
				payload.discount_id = editingDiscountId;
			}

			return payload;
		};

		const saveDiscount = async () => {
			const response = await fetch(`${apiBase}/save`, {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(collectPayload()),
			});

			const result = await response.json();
			if (!response.ok) {
				throw new Error(result.error || result.message || 'Failed to save discount.');
			}

			return result;
		};

		const deleteDiscount = async (discountId) => {
			const response = await fetch(`${apiBase}/delete/${discountId}`, {
				method: 'POST',
			});

			const result = await response.json();
			if (!response.ok) {
				throw new Error(result.error || result.message || 'Failed to delete discount.');
			}

			return result;
		};

		if (openCreateBtn) {
			openCreateBtn.addEventListener('click', () => {
				resetCreateForm();
				switchView('create');
				updateSummary();
			});
		}
		if (openManageBtn) {
			openManageBtn.addEventListener('click', async () => {
				resetCreateForm();
				switchView('manage');
				await renderManageView();
			});
		}

		if (searchInput) {
			searchInput.addEventListener('input', () => {
				state.search = searchInput.value.trim();
				state.page = 1;
				clearTimeout(searchDebounceTimer);
				searchDebounceTimer = setTimeout(() => {
					renderManageView();
				}, 250);
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

		if (specificCustomerInput && specificCustomerIdInput) {
			specificCustomerInput.addEventListener('input', () => {
				specificCustomerIdInput.value = '';
				const query = specificCustomerInput.value.trim();
				if (query.length < 2) {
					closeUserDropdown();
					return;
				}

				clearTimeout(userSearchDebounceTimer);
				userSearchDebounceTimer = setTimeout(async () => {
					try {
						const users = await searchUsers(query);
						renderUserDropdown(users);
					} catch (error) {
						if (userDropdown) {
							userDropdown.innerHTML = `<div class="discount-user-option">${error.message}</div>`;
							userDropdown.classList.add('active');
						}
					}
				}, 220);
			});

			specificCustomerInput.addEventListener('focus', async () => {
				const query = specificCustomerInput.value.trim();
				if (query.length < 2) return;
				try {
					const users = await searchUsers(query);
					renderUserDropdown(users);
				} catch (error) {
					closeUserDropdown();
				}
			});
		}

		if (userDropdown && specificCustomerInput && specificCustomerIdInput) {
			userDropdown.addEventListener('click', (event) => {
				const option = event.target.closest('[data-user-id]');
				if (!option) return;

				specificCustomerIdInput.value = option.getAttribute('data-user-id') || '';
				specificCustomerInput.value = option.getAttribute('data-user-label') || '';
				closeUserDropdown();
				updateSummary();
			});
		}

		document.addEventListener('click', (event) => {
			if (!root.contains(event.target)) {
				closeUserDropdown();
				return;
			}

			const insideSearch = event.target.closest('.discount-user-search');
			if (!insideSearch) {
				closeUserDropdown();
			}
		});

		userRadios.forEach((radio) => radio.addEventListener('change', () => {
			const selected = root.querySelector('input[name="discountUserType"]:checked')?.value || 'all';
			if (selected !== 'specific' && specificCustomerIdInput) {
				specificCustomerIdInput.value = '';
			}
			updateSummary();
		}));

		if (addDiscountBtn) {
			addDiscountBtn.addEventListener('click', async () => {
				const originalLabel = editingDiscountId ? 'Update Discount' : 'Add Discount';
				try {
					addDiscountBtn.disabled = true;
					addDiscountBtn.textContent = 'Saving...';
					await saveDiscount();
					alert(editingDiscountId ? 'Discount updated successfully.' : 'Discount saved successfully.');
					resetCreateForm();
					switchView('manage');
					state.page = 1;
					await renderManageView();
				} catch (error) {
					alert(error.message || 'Failed to save discount.');
				} finally {
					addDiscountBtn.disabled = false;
					addDiscountBtn.textContent = originalLabel;
				}
			});
		}

		if (tableBody) {
			tableBody.addEventListener('click', async (event) => {
				const editButton = event.target.closest('[data-discount-edit]');
				if (editButton) {
					const editId = Number(editButton.getAttribute('data-discount-edit') || 0);
					const row = serverRows.find((item) => Number(item.discount_id) === editId);
					if (row) {
						openEditMode(row);
					}
					return;
				}

				const button = event.target.closest('[data-discount-delete]');
				if (!button) return;

				const discountId = Number(button.getAttribute('data-discount-delete') || 0);
				if (!discountId) return;

				if (!confirm('Delete this discount?')) return;

				try {
					await deleteDiscount(discountId);
					await renderManageView();
				} catch (error) {
					alert(error.message || 'Failed to delete discount.');
				}
			});
		}

		renderManageView();
		updateSummary();
	})();
</script>