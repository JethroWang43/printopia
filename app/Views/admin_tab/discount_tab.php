<style>
	.discount-tab {
		background: #ffffff;
		border: 1px solid #d5deed;
		border-radius: 14px;
		box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
		padding: clamp(16px, 2vw, 24px);
	}

	.discount-view {
		display: none;
	}

	.discount-view.active {
		display: block;
	}

	.discount-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 14px;
		margin-bottom: 14px;
	}

	.discount-title {
		margin: 0;
		color: #132659;
		font-size: clamp(1.4rem, 2.1vw, 2rem);
		font-weight: 800;
		letter-spacing: -0.01em;
	}

	.discount-primary-btn {
		border: 1px solid #0f6838;
		border-radius: 10px;
		background: linear-gradient(180deg, #1f8f4f 0%, #15713d 100%);
		color: #ffffff;
		padding: 10px 16px;
		font-family: inherit;
		font-size: 0.9rem;
		font-weight: 700;
		cursor: pointer;
		box-shadow: 0 8px 14px rgba(21, 113, 61, 0.2);
	}

	.discount-header-actions {
		display: inline-flex;
		align-items: center;
		gap: 8px;
	}

	.discount-secondary-btn {
		border: 1px solid #c8d5ed;
		border-radius: 10px;
		background: #f6f9ff;
		color: #2e4674;
		padding: 10px 16px;
		font-family: inherit;
		font-size: 0.9rem;
		font-weight: 700;
		cursor: pointer;
	}

	.discount-toolbar {
		border: 1px solid #dce4f2;
		border-radius: 10px;
		background: #fbfdff;
		padding: 10px;
		display: grid;
		grid-template-columns: minmax(220px, 1.3fr) minmax(150px, 1fr) minmax(120px, 0.9fr) auto;
		gap: 10px;
		margin-bottom: 12px;
	}

	.discount-field {
		min-height: 40px;
		border: 1px solid #ced8ea;
		border-radius: 8px;
		background: #ffffff;
		color: #4f5b7d;
		display: flex;
		align-items: center;
		gap: 8px;
		padding: 0 12px;
		font-size: 0.89rem;
		font-weight: 600;
	}

	.discount-field input,
	.discount-field select {
		width: 100%;
		border: 0;
		outline: none;
		background: transparent;
		font: inherit;
		color: #2f3e64;
	}

	.discount-field select {
		cursor: pointer;
	}

	.discount-view-toggle {
		justify-content: center;
		min-width: 90px;
		padding: 0;
	}

	.discount-view-toggle button {
		border: 0;
		background: transparent;
		color: #4c597d;
		font-family: inherit;
		font-size: 0.83rem;
		font-weight: 700;
		height: 100%;
		padding: 0 10px;
		cursor: pointer;
	}

	.discount-view-toggle button.active {
		background: #e9f4ff;
		color: #1b5a96;
	}

	.discount-table-wrap {
		border: 1px solid #cad8ee;
		border-radius: 10px;
		background: #ffffff;
		overflow: hidden;
	}

	.discount-grid-wrap {
		display: none;
		margin-top: 12px;
		grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
		gap: 10px;
	}

	.discount-grid-wrap.active {
		display: grid;
	}

	.discount-grid-card {
		border: 1px solid #d6e0f1;
		border-radius: 10px;
		padding: 12px;
		background: #ffffff;
	}

	.discount-grid-card h5 {
		margin: 0 0 8px;
		color: #1e2f58;
		font-size: 1rem;
	}

	.discount-grid-meta {
		display: grid;
		gap: 6px;
		font-size: 0.84rem;
		color: #5d6b8d;
	}

	.discount-table {
		width: 100%;
		border-collapse: collapse;
	}

	.discount-table thead tr {
		background: #1788ce;
		color: #ffffff;
	}

	.discount-table th,
	.discount-table td {
		text-align: left;
		padding: 12px 14px;
		white-space: nowrap;
	}

	.discount-table th {
		font-size: 0.9rem;
		font-weight: 700;
	}

	.discount-table td {
		border-top: 1px solid #e9eef8;
		font-size: 0.93rem;
		color: #243150;
	}

	.discount-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 999px;
		padding: 4px 12px;
		color: #ffffff;
		font-size: 0.8rem;
		font-weight: 700;
		line-height: 1;
	}

	.discount-badge.active {
		background: #0b84cf;
	}

	.discount-badge.ended {
		background: #7f1d1d;
	}

	.discount-tag {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 999px;
		padding: 4px 10px;
		font-size: 0.78rem;
		font-weight: 700;
		color: #243150;
		background: #edf3ff;
	}

	.discount-tag.specific {
		background: #fff3da;
		color: #815f11;
	}

	.discount-edit {
		color: #41527a;
		font-size: 1rem;
		font-weight: 700;
	}

	.discount-pagination {
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 12px;
		padding-top: 14px;
		color: #64708f;
		font-size: 0.9rem;
	}

	.discount-pagination button {
		border: 0;
		background: transparent;
		color: inherit;
		font: inherit;
		cursor: pointer;
		padding: 0;
	}

	.discount-pagination button:disabled {
		opacity: 0.45;
		cursor: not-allowed;
	}

	.discount-pagination .current {
		width: 26px;
		height: 26px;
		border-radius: 6px;
		background: #162b5e;
		color: #fff;
		display: inline-grid;
		place-items: center;
		font-weight: 700;
	}

	.discount-create-grid {
		display: grid;
		grid-template-columns: minmax(0, 1fr) 300px;
		gap: 14px;
	}

	.discount-card {
		border: 1px solid #d8e1f1;
		border-radius: 12px;
		background: #ffffff;
		padding: 14px;
		margin-bottom: 12px;
	}

	.discount-card h4 {
		margin: 0 0 10px;
		color: #19316b;
		font-size: 1rem;
	}

	.discount-segment {
		display: inline-flex;
		border: 1px solid #cdd7ea;
		border-radius: 10px;
		overflow: hidden;
		background: #f8fbff;
	}

	.discount-segment button {
		border: 0;
		background: transparent;
		color: #44547a;
		padding: 9px 14px;
		font-family: inherit;
		font-size: 0.85rem;
		font-weight: 700;
		cursor: default;
	}

	.discount-segment button.active {
		background: #1a88cf;
		color: #ffffff;
	}

	.discount-form-row {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 10px;
	}

	.discount-field-block {
		margin-top: 10px;
	}

	.discount-field-block label {
		display: block;
		margin-bottom: 6px;
		color: #2e3c61;
		font-size: 0.85rem;
		font-weight: 700;
	}

	.discount-input,
	.discount-select {
		width: 100%;
		min-height: 40px;
		border: 1px solid #cfd8ea;
		border-radius: 8px;
		padding: 0 12px;
		font-family: inherit;
		font-size: 0.9rem;
		color: #2d3a5d;
		background: #ffffff;
	}

	.discount-help {
		margin-top: 6px;
		color: #6b7798;
		font-size: 0.78rem;
	}

	.discount-inline {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.discount-percent {
		font-size: 0.95rem;
		font-weight: 700;
		color: #44547a;
	}

	.discount-radio-group {
		display: grid;
		gap: 8px;
		margin-top: 8px;
	}

	.discount-radio {
		display: flex;
		align-items: center;
		gap: 8px;
		color: #334266;
		font-size: 0.88rem;
	}

	.discount-check-row {
		display: grid;
		gap: 8px;
		margin-top: 8px;
	}

	.discount-check {
		display: flex;
		align-items: center;
		gap: 8px;
		color: #334266;
		font-size: 0.88rem;
	}

	.discount-summary {
		border: 1px solid #d8e1f1;
		border-radius: 12px;
		background: #ffffff;
		padding: 14px;
		position: sticky;
		top: 16px;
		height: fit-content;
	}

	.discount-summary h4 {
		margin: 0 0 10px;
		color: #16306b;
		font-size: 1rem;
	}

	.discount-summary-item {
		display: flex;
		justify-content: space-between;
		align-items: baseline;
		gap: 10px;
		padding: 8px 0;
		border-top: 1px dashed #e2e9f5;
		font-size: 0.86rem;
	}

	.discount-summary-item:first-of-type {
		border-top: 0;
		padding-top: 0;
	}

	.discount-summary-item span:first-child {
		color: #5f6d8d;
		font-weight: 600;
	}

	.discount-summary-item strong {
		color: #1e2f58;
		font-weight: 800;
	}

	.discount-view .discount-primary-btn {
		cursor: pointer;
	}

	.discount-note {
		margin-top: 8px;
		font-size: 0.8rem;
		color: #6d7895;
	}

	.discount-user-search {
		position: relative;
	}

	.discount-user-dropdown {
		display: none;
		position: absolute;
		top: calc(100% + 4px);
		left: 0;
		right: 0;
		max-height: 220px;
		overflow-y: auto;
		background: #ffffff;
		border: 1px solid #cfd8ea;
		border-radius: 8px;
		box-shadow: 0 8px 18px rgba(16, 34, 79, 0.14);
		z-index: 50;
	}

	.discount-user-dropdown.active {
		display: block;
	}

	.discount-user-option {
		padding: 9px 11px;
		cursor: pointer;
		font-size: 0.86rem;
		color: #2f3e64;
		border-top: 1px solid #edf2fb;
	}

	.discount-user-option:first-child {
		border-top: 0;
	}

	.discount-user-option:hover {
		background: #edf4ff;
	}

	.discount-segment-popup {
		display: none;
		margin-top: 10px;
		border: 1px solid #cfe0f3;
		border-radius: 10px;
		background: #f8fbff;
		padding: 10px;
	}

	.discount-segment-popup.active {
		display: block;
	}

	.discount-segment-popup h5 {
		margin: 0 0 8px;
		font-size: 0.9rem;
		color: #1e3a67;
	}

	.discount-segment-popup-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 8px;
	}

	@media (max-width: 1080px) {
		.discount-create-grid {
			grid-template-columns: 1fr;
		}

		.discount-summary {
			position: static;
		}

		.discount-segment-popup-grid {
			grid-template-columns: 1fr;
		}
	}

	.discount-modal-overlay {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		z-index: 100;
		align-items: center;
		justify-content: center;
	}

	.discount-modal-overlay.active {
		display: flex;
	}

	.discount-modal {
		background: #ffffff;
		border-radius: 12px;
		box-shadow: 0 20px 60px rgba(16, 34, 79, 0.2);
		padding: 24px;
		max-width: 400px;
		width: 90%;
		animation: slideUp 0.3s ease-out;
	}

	@keyframes slideUp {
		from {
			transform: translateY(20px);
			opacity: 0;
		}
		to {
			transform: translateY(0);
			opacity: 1;
		}
	}

	.discount-modal-header {
		margin-bottom: 20px;
		text-align: center;
	}

	.discount-modal-title {
		margin: 0;
		color: #132659;
		font-size: 1.4rem;
		font-weight: 800;
	}

	.discount-modal-subtitle {
		margin: 4px 0 0;
		color: #6b7798;
		font-size: 0.95rem;
		font-weight: 500;
	}

	.discount-modal-content {
		display: grid;
		gap: 12px;
		margin-bottom: 16px;
	}

	.discount-modal-actions {
		display: flex;
		gap: 10px;
		justify-content: flex-end;
	}

	.discount-modal-actions button {
		border: 1px solid #c8d5ed;
		border-radius: 8px;
		padding: 10px 16px;
		font-family: inherit;
		font-size: 0.9rem;
		font-weight: 700;
		cursor: pointer;
		transition: all 0.2s ease;
	}

	.discount-modal-actions .cancel-btn {
		background: #f6f9ff;
		color: #2e4674;
	}

	.discount-modal-actions .cancel-btn:hover {
		background: #edf3ff;
	}

	.discount-modal-actions .confirm-btn {
		background: linear-gradient(180deg, #1f8f4f 0%, #15713d 100%);
		color: #ffffff;
		border: 1px solid #0f6838;
	}

	.discount-modal-actions .confirm-btn:hover {
		opacity: 0.9;
	}
</style>

<article class="content-section" id="discount-management">
	<section class="discount-tab" data-discount-tab>
		<div class="discount-view active" data-discount-view="manage">
			<header class="discount-header">
				<h3 class="discount-title">Manage Discount</h3>
				<button type="button" class="discount-primary-btn" data-discount-open-create>New Discount</button>
			</header>

			<div class="discount-toolbar">
				<div class="discount-field">🔎 <input type="text" id="discountSearchInput" placeholder="Search discount code"></div>
				<div class="discount-field">▾ <select id="discountCategoryFilter"><option value="all">All Categories</option><option value="seasonal">Seasonal</option><option value="vip">VIP</option></select></div>
				<div class="discount-field">▾ <select id="discountStatusFilter"><option value="all">Status</option><option value="active">Active</option><option value="ended">Ended</option></select></div>
				<div class="discount-field discount-view-toggle"><button type="button" id="discountListViewBtn" class="active">▤ List</button><button type="button" id="discountGridViewBtn">▦ Grid</button></div>
			</div>

			<div class="discount-table-wrap">
				<table class="discount-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Code</th>
							<th>Status</th>
							<th>Customer</th>
							<th>Discount</th>
							<th>Limit Use</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="discountTableBody"></tbody>
				</table>
			</div>

			<div class="discount-grid-wrap" id="discountGridWrap"></div>

			<div class="discount-pagination">
				<button type="button" id="discountPrevPageBtn">← Previous</button>
				<span class="current" id="discountCurrentPage">1</span>
				<span id="discountPageMeta">1 / 1</span>
				<button type="button" id="discountNextPageBtn">Next →</button>
			</div>
		</div>

		<div class="discount-view" data-discount-view="create">
			<header class="discount-header">
				<h3 class="discount-title">Create Discount</h3>
				<div class="discount-header-actions">
					<button type="button" class="discount-secondary-btn" data-discount-open-manage>Back to Manage</button>
					<button type="button" class="discount-primary-btn" id="discountAddBtn">Add Discount</button>
				</div>
			</header>

			<div class="discount-create-grid">
				<div>
					<article class="discount-card">
						<h4>Method Selection</h4>
						<div class="discount-segment">
							<button type="button" class="active" id="discountMethodCodeBtn">Discount code</button>
							<button type="button" id="discountMethodAutoBtn">Automatic discount</button>
						</div>

						<div class="discount-field-block">
							<label>Discount Code</label>
							<input type="text" class="discount-input" id="discountCodeInput" value="SUMMER">
							<p class="discount-help">Customer must enter this code when paying.</p>
						</div>
					</article>

					<article class="discount-card">
						<h4>Value</h4>
						<div class="discount-field-block">
							<label>Discount Option</label>
							<select class="discount-select" id="discountValueTypeSelect">
								<option value="discount" selected>Percentage Discount</option>
								<option value="free_shipping">Free Shipping</option>
							</select>
						</div>
						<div class="discount-field-block">
							<label id="discountValueLabel">Order discount</label>
							<div class="discount-inline">
								<input type="number" min="0" max="100" class="discount-input" id="discountPercentInput" value="10">
								<span class="discount-percent" id="discountValueUnit">%</span>
							</div>
							<p class="discount-help" id="discountValueHelp">Percentage off</p>
						</div>
						<div class="discount-field-block" id="discountMinSpendWrap" style="display: none;">
							<label>Minimum Spend to Claim</label>
							<div class="discount-inline">
								<span style="color: #5f6d8d; font-weight: 600;">₱</span>
								<input type="number" min="0" step="0.01" class="discount-input" id="discountShippingMinInput" value="50.00">
							</div>
							<p class="discount-help">Set the minimum spend required to claim free shipping.</p>
						</div>
					</article>

					<article class="discount-card">
						<h4>Customer Eligibility</h4>
						<div class="discount-radio-group">
							<label class="discount-radio"><input type="radio" name="discountUserType" value="all" checked> All Customer</label>
							<label class="discount-radio"><input type="radio" name="discountUserType" value="segment"> Specific Customer segments</label>
							<select class="discount-select" id="discountSegmentSelect">
								<option>Choose segment...</option>
								<option>Loyal Customers</option>
								<option>Wholesale</option>
							</select>
							<div class="discount-segment-popup" id="discountSegmentConditionPopup">
								<h5 id="discountSegmentConditionTitle">Segment Discount Conditions</h5>
								<div class="discount-segment-popup-grid">
									<div class="discount-field-block" style="margin-top:0;">
										<label id="discountSegmentMinSpendLabel">Minimum Spend</label>
										<input type="number" min="0" step="0.01" class="discount-input" id="discountSegmentMinSpendInput" placeholder="e.g. 1000">
									</div>
									<div class="discount-field-block" style="margin-top:0;">
										<label id="discountSegmentMetricLabel">Minimum Completed Orders</label>
										<input type="number" min="0" class="discount-input" id="discountSegmentMinOrdersInput" placeholder="e.g. 5">
									</div>
								</div>
								<p class="discount-help" id="discountSegmentConditionHelp">Set the conditions customers must meet before this segment discount is applied.</p>
							</div>
							<label class="discount-radio"><input type="radio" name="discountUserType" value="specific"> Specific Customer</label>
							<div class="discount-user-search">
								<input type="hidden" id="discountSpecificCustomerId">
								<input type="text" class="discount-input" id="discountSpecificCustomerInput" placeholder="Search customer">
								<div class="discount-user-dropdown" id="discountUserDropdown"></div>
							</div>
						</div>
					</article>

					<article class="discount-card">
						<h4>Usage Limits & Scheduling</h4>
						<div class="discount-form-row">
							<div class="discount-field-block">
								<label>Time Started</label>
								<input type="datetime-local" class="discount-input" id="discountStartInput" value="2026-04-05T09:00">
							</div>
							<div class="discount-field-block">
								<label>Time Ended</label>
								<input type="datetime-local" class="discount-input" id="discountEndInput" value="2026-05-05T23:59">
							</div>
						</div>
						<div class="discount-check-row">
							<label class="discount-check"><input type="checkbox" id="discountLimitToggle" checked> Limit of total Uses</label>
							<input type="number" min="1" class="discount-input" id="discountLimitInput" value="3">
							<label class="discount-check"><input type="checkbox" id="discountOneTimeToggle"> One time use ONLY</label>
						</div>
					</article>
				</div>

				<aside class="discount-summary">
					<h4>Summary</h4>
					<div class="discount-summary-item"><span>Status</span><strong id="discountSummaryStatus">Draft</strong></div>
					<div class="discount-summary-item"><span>Code</span><strong id="discountSummaryCode">SUMMER</strong></div>
					<div class="discount-summary-item"><span>Discount</span><strong id="discountSummaryDiscount">10%</strong></div>
					<div class="discount-summary-item"><span>User</span><strong id="discountSummaryUser">All Customer</strong></div>
					<div class="discount-summary-item"><span>Limit</span><strong id="discountSummaryLimit">3</strong></div>
					<div class="discount-summary-item"><span>Schedule</span><strong id="discountSummarySchedule">Apr 5 - May 5</strong></div>
					<p class="discount-note">Connected to live database. New discounts will be saved and listed here.</p>
				</aside>
			</div>
		</div>
	</section>
</article>

<?php include APPPATH . 'Controllers/admin_function/discount_function.php'; ?>
