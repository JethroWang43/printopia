<style>
    .order-tab {
        background: #ffffff;
        border: 1px solid #d6dfed;
        border-radius: 14px;
        box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
        padding: clamp(16px, 2vw, 24px);
    }

    .order-toolbar {
        border: 1px solid #f0c9c9;
        border-radius: 10px;
        padding: 10px;
        display: grid;
        grid-template-columns: minmax(220px, 1.35fr) minmax(150px, 0.9fr) minmax(130px, 0.8fr) auto auto;
        gap: 10px;
        margin-bottom: 14px;
        background: #fffdfd;
    }

    .order-field {
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

    .order-field input,
    .order-field select {
        width: 100%;
        border: 0;
        outline: none;
        background: transparent;
        font: inherit;
        color: #2f3e64;
    }

    .order-date-btn {
        border: 1px solid #cf4040;
        color: #a62222;
        background: #fff6f6;
        border-radius: 8px;
        min-height: 40px;
        padding: 0 12px;
        font-family: inherit;
        font-size: 0.86rem;
        font-weight: 700;
        cursor: pointer;
    }

    .order-view-toggle {
        min-height: 40px;
        border: 1px solid #d1daeb;
        border-radius: 8px;
        display: inline-flex;
        overflow: hidden;
        background: #fff;
    }

    .order-view-toggle button {
        border: 0;
        background: transparent;
        color: #4f5b7d;
        font-family: inherit;
        font-size: 0.83rem;
        font-weight: 700;
        padding: 0 12px;
        cursor: pointer;
    }

    .order-view-toggle button.active {
        background: #e9f4ff;
        color: #1a588f;
    }

    .order-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .order-grid.list-mode {
        grid-template-columns: 1fr;
    }

    .order-card {
        border: 1px solid #d8e1f1;
        border-radius: 12px;
        background: #ffffff;
        padding: 12px;
        display: grid;
        gap: 8px;
    }

    .order-pill {
        justify-self: flex-start;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 4px 11px;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
        line-height: 1;
    }

    .order-pill.completed {
        background: #188a4b;
    }

    .order-pill.manage {
        background: #444f6a;
    }

    .order-thumb {
        width: 100%;
        aspect-ratio: 16 / 9;
        border-radius: 10px;
        border: 1px solid #d8e2f2;
        background: linear-gradient(135deg, #fefefe 0%, #edf3ff 100%);
        display: grid;
        place-items: center;
        color: #4e5a7c;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .order-card h4 {
        margin: 0;
        color: #1d2e59;
        font-size: 1rem;
    }

    .order-customer {
        color: #2f3f66;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .order-meta {
        color: #677494;
        font-size: 0.78rem;
        line-height: 1.4;
    }

    .order-price {
        color: #1c2f63;
        font-size: 0.93rem;
        font-weight: 800;
    }

    .order-cta {
        border: 1px solid #c92b2b;
        border-radius: 9px;
        background: linear-gradient(180deg, #e14545 0%, #c92929 100%);
        color: #ffffff;
        min-height: 38px;
        font-family: inherit;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
    }

    .order-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        padding-top: 14px;
        color: #667493;
        font-size: 0.9rem;
    }

    .order-pagination .current {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: inline-grid;
        place-items: center;
        background: #162b5e;
        color: #fff;
        font-weight: 700;
    }

    .order-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1300;
        background: rgba(12, 23, 50, 0.54);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 12px;
    }

    .order-modal-backdrop.show {
        display: flex;
    }

    .order-modal {
        width: min(1220px, 96vw);
        max-height: 92vh;
        overflow: auto;
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #d7e0ee;
        box-shadow: 0 20px 34px rgba(16, 34, 79, 0.26);
        padding: 16px;
    }

    .order-modal-head {
        display: grid;
        grid-template-columns: 1.1fr 1fr auto;
        gap: 12px;
        align-items: start;
        margin-bottom: 12px;
    }

    .order-modal-head h3 {
        margin: 0;
        color: #17306b;
        font-size: 1.2rem;
    }

    .order-modal-order-id {
        margin-top: 6px;
        color: #5c6b8d;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .order-customer-contact {
        color: #33446a;
        font-size: 0.84rem;
        line-height: 1.45;
    }

    .order-modal-close {
        border: 0;
        background: transparent;
        color: #4a5a80;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
    }

    .order-summary-bar {
        border: 1px solid #d8e1ef;
        border-radius: 10px;
        background: #f0f4fa;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
        padding: 10px;
        margin-bottom: 12px;
    }

    .order-summary-item {
        border-right: 1px solid #d5ddec;
        padding-right: 10px;
    }

    .order-summary-item:last-child {
        border-right: 0;
    }

    .order-summary-item span {
        display: block;
        color: #667493;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .order-summary-item strong {
        color: #20345f;
        font-size: 0.95rem;
        font-weight: 800;
    }

    .order-modal-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 12px;
    }

    .order-panel {
        border: 1px solid #d8e1ef;
        border-radius: 10px;
        background: #ffffff;
        padding: 12px;
        margin-bottom: 10px;
    }

    .order-panel h4 {
        margin: 0 0 8px;
        color: #1b326a;
        font-size: 0.98rem;
    }

    .order-file-item {
        border: 1px dashed #cfd8ea;
        border-radius: 8px;
        padding: 8px;
        color: #41527a;
        font-size: 0.84rem;
        margin-bottom: 6px;
    }

    .order-preview-box {
        border: 1px solid #d5deed;
        border-radius: 10px;
        min-height: 220px;
        background: linear-gradient(135deg, #fefefe 0%, #edf3ff 100%);
        display: grid;
        place-items: center;
        color: #4e5a7c;
        font-weight: 700;
        font-size: 0.88rem;
    }

    .order-history {
        display: grid;
        gap: 6px;
        color: #536184;
        font-size: 0.82rem;
    }

    .order-action-note {
        color: #586688;
        font-size: 0.82rem;
        margin-bottom: 8px;
    }

    .order-field-block {
        margin-bottom: 8px;
    }

    .order-field-block label {
        display: block;
        margin-bottom: 5px;
        color: #2e3c61;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .order-input,
    .order-select,
    .order-textarea {
        width: 100%;
        border: 1px solid #cfd8ea;
        border-radius: 8px;
        background: #ffffff;
        font-family: inherit;
        font-size: 0.88rem;
        color: #2d3a5d;
    }

    .order-input,
    .order-select {
        min-height: 38px;
        padding: 0 10px;
    }

    .order-textarea {
        min-height: 94px;
        padding: 10px;
        resize: vertical;
    }

    .order-actions-secondary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-top: 8px;
    }

    .order-actions-secondary button {
        min-height: 36px;
        border-radius: 8px;
        border: 1px solid #ced7e8;
        background: #f4f7fd;
        color: #42537a;
        font-family: inherit;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
    }

    .order-actions-secondary .danger {
        border-color: #7f1d1d;
        background: #7f1d1d;
        color: #fff;
    }

    .order-approve-btn {
        margin-top: 10px;
        min-height: 40px;
        width: 100%;
        border: 1px solid #0f6838;
        border-radius: 9px;
        background: linear-gradient(180deg, #1f8f4f 0%, #15713d 100%);
        color: #fff;
        font-family: inherit;
        font-size: 0.9rem;
        font-weight: 800;
        cursor: pointer;
    }

    @media (max-width: 1180px) {
        .order-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .order-modal-grid {
            grid-template-columns: 1fr;
        }

        .order-modal-head {
            grid-template-columns: 1fr;
        }

        .order-summary-bar {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .order-toolbar {
            grid-template-columns: 1fr;
        }

        .order-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .order-actions-secondary {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 620px) {
        .order-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<article class="content-section" id="order-management">
    <section class="order-tab" data-order-tab>
        <div class="order-toolbar">
            <div class="order-field">🔍 <input id="orderSearchInput" type="text" placeholder="Search orders by customer, item, or ID"></div>
            <div class="order-field">▾ <select id="orderCategoryFilter"><option value="all">All Categories</option><option value="tote">Tote Bag</option><option value="shirt">Shirt</option><option value="mug">Mug</option></select></div>
            <div class="order-field">▾ <select id="orderStatusFilter"><option value="all">Status</option><option value="pending">Manage order</option><option value="completed">Completed</option></select></div>
            <button type="button" class="order-date-btn" id="orderDateRangeBtn">📅 Date range</button>
            <div class="order-view-toggle">
                <button type="button" id="orderGridViewBtn" class="active">▦ Grid</button>
                <button type="button" id="orderListViewBtn">▤ List</button>
            </div>
        </div>

        <div class="order-grid" id="orderCardGrid"></div>

        <div class="order-pagination" id="orderPaginationLine"></div>

        <div class="order-modal-backdrop" id="orderModalBackdrop" aria-hidden="true">
            <div class="order-modal">
                <div class="order-modal-head">
                    <div>
                        <h3 id="orderModalTitle">Customize Tote Bag</h3>
                        <div class="order-modal-order-id" id="orderModalOrderId">#ORD-2026-001</div>
                    </div>

                    <div class="order-customer-contact" id="orderModalCustomerContact">
                        <strong>Maria Santos</strong><br>
                        +63 917 000 1122<br>
                        104 M. Adriatico St, Ermita, Metro Manila
                    </div>

                    <button type="button" class="order-modal-close" id="orderModalClose">X</button>
                </div>

                <div class="order-summary-bar">
                    <div class="order-summary-item"><span>Status</span><strong id="orderSummaryStatus">pending</strong></div>
                    <div class="order-summary-item"><span>Ordered</span><strong id="orderSummaryDate">Jan 22, 2026</strong></div>
                    <div class="order-summary-item"><span>Delivery Method</span><strong id="orderSummaryMethod">Delivery</strong></div>
                    <div class="order-summary-item"><span>Total</span><strong id="orderSummaryTotal">Pending quote</strong></div>
                </div>

                <div class="order-modal-grid">
                    <section>
                        <div class="order-panel">
                            <h4>Customer Submissions</h4>
                            <div id="orderSubmissionList"></div>
                        </div>

                        <div class="order-panel">
                            <h4>3D Design Preview</h4>
                            <div class="order-preview-box" id="orderPreviewBox">Tote bag concept preview</div>
                        </div>

                        <div class="order-panel">
                            <h4>Tracking Updates</h4>
                            <div class="order-history" id="orderTrackingHistory"></div>
                        </div>
                    </section>

                    <aside class="order-panel">
                        <h4>Admin Actions</h4>
                        <p class="order-action-note">Price is not yet set. Confirm details with the client before quoting.</p>

                        <div class="order-field-block">
                            <label for="orderStatusUpdate">Status</label>
                            <select class="order-select" id="orderStatusUpdate">
                                <option value="pending">pending</option>
                                <option value="completed">completed</option>
                                <option value="in-review">in-review</option>
                            </select>
                        </div>

                        <div class="order-field-block">
                            <label for="orderPriceInput">Price (PHP)</label>
                            <input class="order-input" id="orderPriceInput" type="text" placeholder="e.g., 1500.00">
                        </div>

                        <div class="order-field-block">
                            <label for="orderInternalNotes">Internal notes</label>
                            <textarea class="order-textarea" id="orderInternalNotes" placeholder="Notes for team and client communication..."></textarea>
                        </div>

                        <div class="order-actions-secondary">
                            <button type="button" id="orderSaveChangesBtn">Save Change</button>
                            <button type="button" id="orderCallCustomerBtn">☎ Call Customer</button>
                            <button type="button" id="orderDirectCustomerBtn">💬 Direct Customer</button>
                            <button type="button" class="danger" id="orderCancelBtn">Cancel Order</button>
                        </div>

                        <button type="button" class="order-approve-btn" id="orderApproveBtn">Approve Order</button>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</article>

<script>
    (function () {
        if (window.__orderTabBound) return;
        window.__orderTabBound = true;

        const root = document.querySelector('[data-order-tab]');
        if (!root) return;

        const orders = [
            {
                id: 'ORD-2026-001',
                item: 'Tote Bag',
                category: 'tote',
                customer: 'Maria Santos',
                specs: 'White Canvas, 5 days, 1pc',
                price: 'Pending quote',
                status: 'pending',
                orderedDate: 'Jan 22, 2026',
                deliveryMethod: 'Delivery',
                phone: '+63 917 000 1122',
                address: '104 M. Adriatico St, Ermita, Metro Manila',
                files: ['Design-brief.pdf (2.4MB)', 'logo.png (1.1MB)', 'Color-palette.png (820kb)'],
                history: ['Order placed - Jan 22, 2026', 'Payment authorized - Jan 22, 2026'],
                previewLabel: 'Tote bag concept preview'
            },
            {
                id: 'ORD-2026-002',
                item: 'Tote Bag',
                category: 'tote',
                customer: 'John Reyes',
                specs: 'Natural Canvas, 3 days, 2pcs',
                price: 'PHP 2,100.00',
                status: 'completed',
                orderedDate: 'Jan 21, 2026',
                deliveryMethod: 'Pickup',
                phone: '+63 917 100 4451',
                address: '45 Aurora Blvd, Quezon City, Metro Manila',
                files: ['brief.docx (1.0MB)', 'logo-final.png (940kb)'],
                history: ['Order completed - Jan 24, 2026', 'Released to customer - Jan 24, 2026'],
                previewLabel: 'Completed tote preview'
            },
            {
                id: 'ORD-2026-003',
                item: 'Custom Shirt',
                category: 'shirt',
                customer: 'Alex Tan',
                specs: 'Black cotton, 4 days, 4pcs',
                price: 'Pending quote',
                status: 'pending',
                orderedDate: 'Jan 23, 2026',
                deliveryMethod: 'Delivery',
                phone: '+63 918 225 3331',
                address: '12 Timog Ave, Quezon City, Metro Manila',
                files: ['shirt-layout.ai (2.2MB)'],
                history: ['Order placed - Jan 23, 2026'],
                previewLabel: 'Shirt print layout'
            },
            {
                id: 'ORD-2026-004',
                item: 'Custom Mug',
                category: 'mug',
                customer: 'Leah Cruz',
                specs: 'Ceramic white, 2 days, 3pcs',
                price: 'PHP 1,250.00',
                status: 'completed',
                orderedDate: 'Jan 20, 2026',
                deliveryMethod: 'Delivery',
                phone: '+63 919 333 1010',
                address: '8 Shaw Blvd, Mandaluyong, Metro Manila',
                files: ['mug-reference.png (1.3MB)'],
                history: ['Order completed - Jan 22, 2026'],
                previewLabel: 'Mug wrap preview'
            },
            {
                id: 'ORD-2026-005',
                item: 'Tote Bag',
                category: 'tote',
                customer: 'Paolo Dela Rosa',
                specs: 'Kraft canvas, 6 days, 8pcs',
                price: 'Pending quote',
                status: 'pending',
                orderedDate: 'Jan 24, 2026',
                deliveryMethod: 'Delivery',
                phone: '+63 917 222 0101',
                address: '56 Taft Ave, Manila, Metro Manila',
                files: ['mockup-v2.psd (6.4MB)', 'brand-guide.pdf (3.1MB)'],
                history: ['Order placed - Jan 24, 2026'],
                previewLabel: 'Large batch tote preview'
            },
            {
                id: 'ORD-2026-006',
                item: 'Custom Shirt',
                category: 'shirt',
                customer: 'Dana Villanueva',
                specs: 'White dri-fit, 4 days, 6pcs',
                price: 'Pending quote',
                status: 'pending',
                orderedDate: 'Jan 24, 2026',
                deliveryMethod: 'Pickup',
                phone: '+63 919 555 4433',
                address: '19 Emerald St, Pasig, Metro Manila',
                files: ['logo-mono.svg (320kb)'],
                history: ['Order placed - Jan 24, 2026'],
                previewLabel: 'Shirt chest print preview'
            },
            {
                id: 'ORD-2026-007',
                item: 'Tote Bag',
                category: 'tote',
                customer: 'Iris Mendoza',
                specs: 'White Canvas, 5 days, 2pcs',
                price: 'PHP 2,400.00',
                status: 'completed',
                orderedDate: 'Jan 19, 2026',
                deliveryMethod: 'Delivery',
                phone: '+63 917 881 2200',
                address: '29 Katipunan Ave, Quezon City, Metro Manila',
                files: ['artwork-final.pdf (1.8MB)'],
                history: ['Order completed - Jan 23, 2026'],
                previewLabel: 'Approved floral tote preview'
            },
            {
                id: 'ORD-2026-008',
                item: 'Custom Mug',
                category: 'mug',
                customer: 'Neil Garcia',
                specs: 'Matte black, 3 days, 5pcs',
                price: 'Pending quote',
                status: 'pending',
                orderedDate: 'Jan 25, 2026',
                deliveryMethod: 'Delivery',
                phone: '+63 918 002 7788',
                address: '73 Rizal Ave, Makati, Metro Manila',
                files: ['mug-logo.pdf (900kb)', 'color-note.txt (6kb)'],
                history: ['Order placed - Jan 25, 2026'],
                previewLabel: 'Mug laser print preview'
            }
        ];

        const state = {
            search: '',
            category: 'all',
            status: 'all',
            view: 'grid',
            page: 1,
            pageSize: 8,
            dateFiltered: false
        };

        let activeOrder = null;

        const searchInput = document.getElementById('orderSearchInput');
        const categoryFilter = document.getElementById('orderCategoryFilter');
        const statusFilter = document.getElementById('orderStatusFilter');
        const dateRangeBtn = document.getElementById('orderDateRangeBtn');
        const gridViewBtn = document.getElementById('orderGridViewBtn');
        const listViewBtn = document.getElementById('orderListViewBtn');
        const cardGrid = document.getElementById('orderCardGrid');
        const paginationLine = document.getElementById('orderPaginationLine');

        const modalBackdrop = document.getElementById('orderModalBackdrop');
        const modalClose = document.getElementById('orderModalClose');
        const modalTitle = document.getElementById('orderModalTitle');
        const modalOrderId = document.getElementById('orderModalOrderId');
        const modalCustomerContact = document.getElementById('orderModalCustomerContact');
        const summaryStatus = document.getElementById('orderSummaryStatus');
        const summaryDate = document.getElementById('orderSummaryDate');
        const summaryMethod = document.getElementById('orderSummaryMethod');
        const summaryTotal = document.getElementById('orderSummaryTotal');
        const submissionList = document.getElementById('orderSubmissionList');
        const previewBox = document.getElementById('orderPreviewBox');
        const trackingHistory = document.getElementById('orderTrackingHistory');
        const statusUpdate = document.getElementById('orderStatusUpdate');
        const priceInput = document.getElementById('orderPriceInput');
        const internalNotes = document.getElementById('orderInternalNotes');
        const saveChangesBtn = document.getElementById('orderSaveChangesBtn');
        const callCustomerBtn = document.getElementById('orderCallCustomerBtn');
        const directCustomerBtn = document.getElementById('orderDirectCustomerBtn');
        const cancelOrderBtn = document.getElementById('orderCancelBtn');
        const approveBtn = document.getElementById('orderApproveBtn');

        const getFilteredOrders = () => {
            const keyword = state.search.toLowerCase();
            return orders.filter((order) => {
                const matchSearch = !keyword ||
                    order.customer.toLowerCase().includes(keyword) ||
                    order.item.toLowerCase().includes(keyword) ||
                    order.id.toLowerCase().includes(keyword);
                const matchCategory = state.category === 'all' || order.category === state.category;
                const matchStatus = state.status === 'all' || order.status === state.status;
                return matchSearch && matchCategory && matchStatus;
            });
        };

        const renderCards = (rows) => {
            const isList = state.view === 'list';
            cardGrid.classList.toggle('list-mode', isList);

            if (!rows.length) {
                cardGrid.innerHTML = '<div class="order-card"><strong>No orders found.</strong><span class="order-meta">Try adjusting search or filters.</span></div>';
                return;
            }

            cardGrid.innerHTML = rows.map((order) => {
                const isCompleted = order.status === 'completed';
                return `
                    <article class="order-card" data-order-id="${order.id}">
                        <span class="order-pill ${isCompleted ? 'completed' : 'manage'}">${isCompleted ? 'Completed' : 'Manage order'}</span>
                        <div class="order-thumb">Customized ${order.item}</div>
                        <h4>${order.item}</h4>
                        <div class="order-customer">${order.customer}</div>
                        <div class="order-meta">${order.id}<br>${order.specs}</div>
                        <div class="order-price">${order.price}</div>
                        <button type="button" class="order-cta" data-order-open="${order.id}">${isCompleted ? 'View details' : 'Manage order'}</button>
                    </article>
                `;
            }).join('');
        };

        const renderPagination = (totalRows) => {
            const totalPages = Math.max(1, Math.ceil(totalRows / state.pageSize));
            if (state.page > totalPages) state.page = totalPages;
            paginationLine.innerHTML = `<span>Page</span><span class="current">${state.page}</span><span>of ${totalPages}</span>`;
        };

        const render = () => {
            const filtered = getFilteredOrders();
            const start = (state.page - 1) * state.pageSize;
            const paginated = filtered.slice(start, start + state.pageSize);

            gridViewBtn.classList.toggle('active', state.view === 'grid');
            listViewBtn.classList.toggle('active', state.view === 'list');

            renderCards(paginated);
            renderPagination(filtered.length);
        };

        const openOrderModal = (order) => {
            activeOrder = order;
            modalTitle.textContent = `Customize ${order.item}`;
            modalOrderId.textContent = `#${order.id}`;
            modalCustomerContact.innerHTML = `<strong>${order.customer}</strong><br>${order.phone}<br>${order.address}`;

            summaryStatus.textContent = order.status;
            summaryDate.textContent = order.orderedDate;
            summaryMethod.textContent = order.deliveryMethod;
            summaryTotal.textContent = order.price;

            submissionList.innerHTML = order.files.map((file) => `<div class="order-file-item">${file}</div>`).join('');
            previewBox.textContent = order.previewLabel;
            trackingHistory.innerHTML = order.history.map((item) => `<div>- ${item}</div>`).join('');

            statusUpdate.value = order.status;
            priceInput.value = order.price === 'Pending quote' ? '' : order.price.replace('PHP ', '');
            internalNotes.value = '';

            modalBackdrop.classList.add('show');
            modalBackdrop.setAttribute('aria-hidden', 'false');
        };

        const closeOrderModal = () => {
            modalBackdrop.classList.remove('show');
            modalBackdrop.setAttribute('aria-hidden', 'true');
            activeOrder = null;
        };

        searchInput.addEventListener('input', () => {
            state.search = searchInput.value.trim();
            state.page = 1;
            render();
        });

        categoryFilter.addEventListener('change', () => {
            state.category = categoryFilter.value;
            state.page = 1;
            render();
        });

        statusFilter.addEventListener('change', () => {
            state.status = statusFilter.value;
            state.page = 1;
            render();
        });

        if (dateRangeBtn) {
            dateRangeBtn.addEventListener('click', () => {
                state.dateFiltered = !state.dateFiltered;
                dateRangeBtn.style.background = state.dateFiltered ? '#ffe8e8' : '#fff6f6';
                dateRangeBtn.style.borderColor = state.dateFiltered ? '#b62424' : '#cf4040';
            });
        }

        gridViewBtn.addEventListener('click', () => {
            state.view = 'grid';
            render();
        });

        listViewBtn.addEventListener('click', () => {
            state.view = 'list';
            render();
        });

        cardGrid.addEventListener('click', (event) => {
            const target = event.target.closest('[data-order-open]');
            if (!target) return;

            const order = orders.find((row) => row.id === target.dataset.orderOpen);
            if (order) {
                openOrderModal(order);
            }
        });

        if (modalClose) {
            modalClose.addEventListener('click', closeOrderModal);
        }

        modalBackdrop.addEventListener('click', (event) => {
            if (event.target === modalBackdrop) {
                closeOrderModal();
            }
        });

        if (saveChangesBtn) {
            saveChangesBtn.addEventListener('click', () => {
                alert('Changes saved locally in this screen only.');
            });
        }

        if (callCustomerBtn) {
            callCustomerBtn.addEventListener('click', () => {
                if (!activeOrder) return;
                alert(`Calling ${activeOrder.phone} (simulated).`);
            });
        }

        if (directCustomerBtn) {
            directCustomerBtn.addEventListener('click', () => {
                if (!activeOrder) return;
                alert(`Opening direct message with ${activeOrder.customer} (simulated).`);
            });
        }

        if (cancelOrderBtn) {
            cancelOrderBtn.addEventListener('click', () => {
                if (!activeOrder) return;
                alert('Order cancellation is not connected to backend yet.');
            });
        }

        if (approveBtn) {
            approveBtn.addEventListener('click', () => {
                if (!activeOrder) return;
                activeOrder.status = 'completed';
                if (priceInput.value.trim()) {
                    activeOrder.price = `PHP ${priceInput.value.trim()}`;
                }
                render();
                alert('Order approved in UI (frontend-only).');
            });
        }

        statusUpdate.addEventListener('change', () => {
            if (!activeOrder) return;
            activeOrder.status = statusUpdate.value;
            summaryStatus.textContent = statusUpdate.value;
        });

        render();
    })();
</script>
