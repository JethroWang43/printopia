<style>
    .notification-tab {
        background: #ffffff;
        border: 1px solid #d6dfed;
        border-radius: 14px;
        box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
        padding: clamp(16px, 2vw, 24px);
    }

    .notification-toolbar {
        border: 1px solid #f0c9c9;
        border-radius: 10px;
        padding: 10px;
        display: grid;
        grid-template-columns: minmax(220px, 1.35fr) minmax(150px, 0.9fr) auto;
        gap: 10px;
        margin-bottom: 14px;
        background: #fffdfd;
    }

    .notification-field {
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

    .notification-field input,
    .notification-field select {
        width: 100%;
        border: 0;
        outline: none;
        background: transparent;
        font: inherit;
        color: #2f3e64;
    }

    .notification-action-btn {
        border: 1px solid #cfd8ea;
        color: #2e3c61;
        background: #f3f6fc;
        border-radius: 8px;
        min-height: 40px;
        padding: 0 14px;
        font-family: inherit;
        font-size: 0.86rem;
        font-weight: 700;
        cursor: pointer;
    }

    .notification-action-btn:hover {
        background: #e9f0fa;
        border-color: #b8c5df;
    }

    .notification-list {
        display: grid;
        gap: 8px;
    }

    .notification-item {
        border: 1px solid #d8e1f1;
        border-radius: 10px;
        background: #ffffff;
        padding: 12px;
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 12px;
        align-items: start;
        transition: background 0.15s ease, border-color 0.15s ease;
    }

    .notification-item.unread {
        background: #f5f8ff;
        border-color: #c5d9f1;
    }

    .notification-item:hover {
        background: #f9fbff;
        border-color: #bfd0e8;
    }

    .notification-icon {
        min-width: 36px;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .notification-icon.order {
        background: #fff0f0;
        color: #c92929;
    }

    .notification-icon.system {
        background: #f0f4ff;
        color: #1a588f;
    }

    .notification-icon.customer {
        background: #f0fff5;
        color: #188a4b;
    }

    .notification-icon.alert {
        background: #fff8f0;
        color: #c85f00;
    }

    .notification-content {
        display: grid;
        gap: 4px;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 8px;
    }

    .notification-title {
        margin: 0;
        color: #1d2e59;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .notification-time {
        color: #8899b0;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .notification-message {
        color: #536184;
        font-size: 0.86rem;
        line-height: 1.4;
    }

    .notification-meta {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .notification-badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        color: #ffffff;
        background: #1a588f;
        padding: 3px 8px;
        border-radius: 999px;
    }

    .notification-actions {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
    }

    .notification-action {
        border: 0;
        background: transparent;
        color: #667493;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 8px;
        cursor: pointer;
        transition: color 0.12s ease;
    }

    .notification-action:hover {
        color: #1a588f;
    }

    .notification-unread-badge {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #c92929;
        margin-right: 0;
    }

    .notification-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        padding-top: 14px;
        color: #667493;
        font-size: 0.9rem;
    }

    .notification-pagination .current {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: inline-grid;
        place-items: center;
        background: #162b5e;
        color: #fff;
        font-weight: 700;
    }

    .notification-empty {
        text-align: center;
        padding: 40px 20px;
        color: #677493;
    }

    .notification-empty strong {
        display: block;
        margin-bottom: 6px;
        color: #1f2f63;
        font-size: 1rem;
    }

    @media (max-width: 900px) {
        .notification-toolbar {
            grid-template-columns: 1fr;
        }

        .notification-item {
            grid-template-columns: auto minmax(0, 1fr);
        }

        .notification-actions {
            grid-column: 1 / -1;
            margin-top: 8px;
        }
    }

    @media (max-width: 620px) {
        .notification-item {
            grid-template-columns: 1fr;
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }

        .notification-actions {
            grid-column: 1 / -1;
            margin-top: 8px;
        }
    }
</style>

<article class="content-section" id="notification-management">
    <section class="notification-tab" data-notification-tab>
        <div class="notification-toolbar">
            <div class="notification-field">🔍 <input id="notificationSearchInput" type="text" placeholder="Search notifications..."></div>
            <div class="notification-field">▾ <select id="notificationFilterSelect">
                <option value="all">All Notifications</option>
                <option value="unread">Unread</option>
                <option value="order">Order Updates</option>
                <option value="customer">Customer Messages</option>
                <option value="system">System Alerts</option>
            </select></div>
            <button type="button" class="notification-action-btn" id="notificationMarkAllReadBtn">Mark all as read</button>
        </div>

        <div class="notification-list" id="notificationList"></div>

        <div class="notification-pagination" id="notificationPaginationLine"></div>
    </section>
</article>

<script>
    (function () {
        if (window.__notificationTabBound) return;
        window.__notificationTabBound = true;

        const root = document.querySelector('[data-notification-tab]');
        if (!root) return;

        const notifications = [
            {
                id: 'notif-001',
                type: 'order',
                title: 'Order #ORD-2026-001 Status Updated',
                message: 'Maria Santos\'s tote bag order is ready for approval.',
                timestamp: 'Just now',
                read: false,
                icon: '📦'
            },
            {
                id: 'notif-002',
                type: 'customer',
                title: 'Message from John Reyes',
                message: 'Can you check if my order has shipped yet?',
                timestamp: '5 minutes ago',
                read: false,
                icon: '💬'
            },
            {
                id: 'notif-003',
                type: 'order',
                title: 'New Custom Order Received',
                message: 'Alex Tan submitted a new custom shirt order.',
                timestamp: '12 minutes ago',
                read: false,
                icon: '📦'
            },
            {
                id: 'notif-004',
                type: 'system',
                title: 'System Backup Completed',
                message: 'Daily backup was successfully completed at 02:00 AM.',
                timestamp: '2 hours ago',
                read: true,
                icon: '⚙️'
            },
            {
                id: 'notif-005',
                type: 'alert',
                title: 'Low Stock Alert',
                message: 'Canvas material inventory is below threshold (5 units).',
                timestamp: '3 hours ago',
                read: true,
                icon: '⚠️'
            },
            {
                id: 'notif-006',
                type: 'order',
                title: 'Order #ORD-2026-007 Completed',
                message: 'Iris Mendoza\'s order has been released to customer.',
                timestamp: '4 hours ago',
                read: true,
                icon: '✓'
            },
            {
                id: 'notif-007',
                type: 'customer',
                title: 'Customer Feedback: Dana Villanueva',
                message: 'Positive review received for the white dri-fit shirt order.',
                timestamp: '5 hours ago',
                read: true,
                icon: '⭐'
            },
            {
                id: 'notif-008',
                type: 'system',
                title: 'New Admin User Added',
                message: 'A new admin account has been created by the system admin.',
                timestamp: '1 day ago',
                read: true,
                icon: '👤'
            }
        ];

        const state = {
            search: '',
            filter: 'all',
            page: 1,
            pageSize: 8
        };

        const searchInput = document.getElementById('notificationSearchInput');
        const filterSelect = document.getElementById('notificationFilterSelect');
        const markAllReadBtn = document.getElementById('notificationMarkAllReadBtn');
        const notificationList = document.getElementById('notificationList');
        const paginationLine = document.getElementById('notificationPaginationLine');

        const getFilteredNotifications = () => {
            const keyword = state.search.toLowerCase();
            return notifications.filter((notif) => {
                const matchSearch = !keyword ||
                    notif.title.toLowerCase().includes(keyword) ||
                    notif.message.toLowerCase().includes(keyword);
                const matchFilter = state.filter === 'all' ||
                    (state.filter === 'unread' && !notif.read) ||
                    notif.type === state.filter;
                return matchSearch && matchFilter;
            });
        };

        const renderNotifications = (rows) => {
            if (!rows.length) {
                notificationList.innerHTML = '<div class="notification-empty"><strong>No notifications</strong><span>Check back later for updates</span></div>';
                return;
            }

            notificationList.innerHTML = rows.map((notif) => {
                return `
                    <article class="notification-item ${notif.read ? '' : 'unread'}" data-notification-id="${notif.id}">
                        <div class="notification-icon ${notif.type}">${notif.icon}</div>
                        <div class="notification-content">
                            <div class="notification-header">
                                <h3 class="notification-title">${notif.title}</h3>
                                <span class="notification-time">${notif.timestamp}</span>
                            </div>
                            <p class="notification-message">${notif.message}</p>
                            <div class="notification-meta">
                                ${notif.read ? '' : '<div class="notification-unread-badge"></div>'}
                                <span class="notification-badge">${notif.type.charAt(0).toUpperCase() + notif.type.slice(1)}</span>
                            </div>
                        </div>
                        <div class="notification-actions">
                            <button type="button" class="notification-action" data-action="mark-read" data-id="${notif.id}">${notif.read ? 'Unread' : 'Mark read'}</button>
                            <button type="button" class="notification-action" data-action="delete" data-id="${notif.id}">Delete</button>
                        </div>
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
            const filtered = getFilteredNotifications();
            const start = (state.page - 1) * state.pageSize;
            const paginated = filtered.slice(start, start + state.pageSize);

            renderNotifications(paginated);
            renderPagination(filtered.length);
        };

        searchInput.addEventListener('input', () => {
            state.search = searchInput.value.trim();
            state.page = 1;
            render();
        });

        filterSelect.addEventListener('change', () => {
            state.filter = filterSelect.value;
            state.page = 1;
            render();
        });

        markAllReadBtn.addEventListener('click', () => {
            notifications.forEach((notif) => {
                notif.read = true;
            });
            state.filter = 'all';
            filterSelect.value = 'all';
            state.page = 1;
            render();
        });

        notificationList.addEventListener('click', (event) => {
            const actionBtn = event.target.closest('[data-action]');
            if (!actionBtn) return;

            const action = actionBtn.dataset.action;
            const id = actionBtn.dataset.id;
            const notif = notifications.find((n) => n.id === id);

            if (!notif) return;

            if (action === 'mark-read') {
                notif.read = !notif.read;
                render();
            } else if (action === 'delete') {
                const idx = notifications.indexOf(notif);
                if (idx > -1) {
                    notifications.splice(idx, 1);
                    render();
                }
            }
        });

        render();
    })();
</script>
