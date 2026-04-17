<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>

<script>
    const SUPABASE_URL = 'https://minktbutnmxwcinfxwpa.supabase.co';
    const SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im1pbmt0YnV0bm14d2NpbmZ4d3BhIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzU3MDkyMTcsImV4cCI6MjA5MTI4NTIxN30.-QS4ldybiYvwmu45frxqOGDXpNWk930bD4Bxt1bnuDs';

    const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

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

        // if (callCustomerBtn) {
        //     callCustomerBtn.addEventListener('click', () => {
        //         if (!activeOrder) return;
        //         alert(`Calling ${activeOrder.phone} (simulated).`);
        //     });
        // }

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

    let peerConnection;
    let localStream;

    let adminCallId = null;
    let currentOffer = null;
    let pendingCandidates = [];

    const rtcConfig = {
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' },
            {
                urls: [
                    'turn:openrelay.metered.ca:80',
                    'turn:openrelay.metered.ca:443?transport=tcp'
                ],
                username: 'openrelayproject',
                credential: 'openrelayproject'
            }
        ]
    };

    let orderCallRealtimeInitialized = false;
    const initializeOrderCallRealtime = () => {
        if (orderCallRealtimeInitialized) {
            return;
        }
        orderCallRealtimeInitialized = true;

        // LISTEN FOR CALL
        supabaseClient.channel('admin-call')
        .on('postgres_changes', {
            event: 'INSERT',
            schema: 'public',
            table: 'signaling'
        }, (payload) => {

            const { type, data, call_id } = payload.new;

            if (!type || !call_id) return;
            if (type === 'answer') return;

            if (type === 'offer') {
                currentOffer = data;
                adminCallId = call_id;
                pendingCandidates = [];

                console.log("INCOMING CALL:", call_id);

                document.getElementById('admin-call-overlay').style.display = 'block';
            }

            if (type === 'candidate' && call_id === adminCallId) {
                if (!data) return;

                if (peerConnection?.remoteDescription) {
                    peerConnection.addIceCandidate(new RTCIceCandidate(data));
                } else {
                    pendingCandidates.push(data);
                }
            }
        })
        .subscribe();

        // ACCEPT CALL
        document.getElementById('btn-fast-accept').onclick = async () => {
            try {
                peerConnection = new RTCPeerConnection(rtcConfig);

                // RECEIVE AUDIO
                peerConnection.ontrack = (event) => {
                    console.log("ADMIN GOT AUDIO");

                    const audio = document.getElementById('admin-remote-audio');
                    const stream = event.streams[0] || new MediaStream([event.track]);

                    audio.srcObject = stream;
                    audio.muted = false;
                    audio.volume = 1;

                    const play = () => audio.play().catch(() => {});
                    play();

                    setInterval(() => {
                        if (audio.paused) play();
                    }, 1000);
                };

                // ICE
                peerConnection.onicecandidate = (event) => {
                    if (!event.candidate) return;

                    supabaseClient.from('signaling').insert([{
                        type: 'candidate',
                        data: event.candidate,
                        call_id: adminCallId
                    }]);
                };

                // GET MIC FIRST
                localStream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });

                const track = localStream.getAudioTracks()[0];
                track.enabled = true;

                // ADD TRACK FIRST
                peerConnection.addTrack(track, localStream);

                // SET REMOTE OFFER
                await peerConnection.setRemoteDescription(
                    new RTCSessionDescription(currentOffer)
                );

                // SMALL DELAY (important)
                await new Promise(r => setTimeout(r, 300));

                // ANSWER
                const answer = await peerConnection.createAnswer({
                    offerToReceiveAudio: true
                });

                await peerConnection.setLocalDescription(answer);

                await supabaseClient.from('signaling').insert([{
                    type: 'answer',
                    data: {
                        type: answer.type,
                        sdp: answer.sdp
                    },
                    call_id: adminCallId
                }]);

                // APPLY ICE
                for (const c of pendingCandidates) {
                    await peerConnection.addIceCandidate(new RTCIceCandidate(c));
                }
                pendingCandidates = [];

                document.getElementById('admin-call-overlay').style.display = 'none';

            } catch (err) {
                console.error("ADMIN ERROR:", err);
            }
        };

        // END CALL
        document.getElementById('btn-fast-decline').onclick = async () => {
            if (localStream) localStream.getTracks().forEach(t => t.stop());
            if (peerConnection) peerConnection.close();

            await supabaseClient.from('signaling')
                .delete()
                .eq('call_id', adminCallId);

            document.getElementById('admin-call-overlay').style.display = 'none';
        };

        // AUDIO UNLOCK
        document.addEventListener('click', () => {
            const a = document.getElementById('admin-remote-audio');
            if (a) a.play().catch(() => {});
        }, { once: true });
    };

    const orderSection = document.getElementById('order-management');
    if (orderSection && orderSection.classList.contains('active')) {
        initializeOrderCallRealtime();
    }

    document.addEventListener('printopia:section-opened', (event) => {
        if (event?.detail?.sectionId === 'order-management') {
            initializeOrderCallRealtime();
        }
    });
</script>