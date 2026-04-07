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