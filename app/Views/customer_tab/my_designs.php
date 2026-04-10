<style>
    .design-card {
        position: relative; /* Necessary for absolute positioning of delete button */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;
    }
    .design-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: #1a2b5f; }
    
    /* Delete Button Styling */
    .delete-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(239, 68, 68, 0.9); /* Red */
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: none; /* Hidden by default */
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 10;
        transition: background 0.2s;
    }
    .design-card:hover .delete-btn { display: flex; }
    .delete-btn:hover { background: #dc2626; }

    .image-container { width: 100%; aspect-ratio: 1/1; background: #f1f5f9; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .image-container img { width: 100%; height: 100%; object-fit: cover; }
    .loading-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; place-items: center; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<div id="loadingOverlay" class="loading-overlay">
    <div style="text-align: center;">
        <div style="border: 4px solid #f3f3f3; border-top: 4px solid #1a2b5f; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        <p id="loadingText" style="margin-top: 10px; color: #1a2b5f; font-weight: 600;">Processing...</p>
    </div>
</div>

<article class="panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h3 style="color: #1a2b5f; font-weight: 700; margin: 0;">My Designs</h3>
            <p style="font-size: 0.8rem; color: #64748b;">Gallery for <?= esc(session()->get('user_name') ?? 'Guest') ?></p>
        </div>
        <button id="uploadBtn" onclick="document.getElementById('designInput').click()" 
                style="background: #1a2b5f; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
            + Upload New
        </button>
        <input type="file" id="designInput" hidden accept="image/*" onchange="uploadDesign(this)">
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
        <div onclick="document.getElementById('designInput').click()" 
             style="border: 2px dashed #cbd5e1; border-radius: 12px; aspect-ratio: 1/1; display: grid; place-items: center; color: #94a3b8; cursor: pointer; background: #f8fafc;">
            <div style="text-align: center;">
                <span style="font-size: 2rem;">+</span>
                <p>New Design</p>
            </div>
        </div>

        <?php if (!empty($designs)): ?>
            <?php foreach ($designs as $design): ?>
                <div class="design-card">
                    <button class="delete-btn" onclick="deleteDesign(<?= $design['id'] ?>)" title="Delete Design">×</button>
                    
                    <div class="image-container">
                        <img src="<?= $design['image_url'] ?>" alt="Design" loading="lazy">
                    </div>
                    <div style="padding: 12px;">
                        <p style="font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= esc($design['filename']) ?>
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.65rem; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: bold;">
                                <?= strtoupper(esc($design['format'])) ?>
                            </span>
                            <small style="font-size: 0.7rem; color: #94a3b8;"><?= date('M d', strtotime($design['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center; color: #94a3b8; padding: 40px;">No designs found. Start by uploading one!</p>
        <?php endif; ?>
    </div>
</article>

<script>
    const overlay = document.getElementById('loadingOverlay');
    const loadingText = document.getElementById('loadingText');

    async function uploadDesign(input) {
        if (!input.files || !input.files[0]) return;

        const formData = new FormData();
        formData.append('design_file', input.files[0]);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        loadingText.innerText = "Saving to your designs...";
        overlay.style.display = 'grid';

        try {
            const response = await fetch('<?= base_url('designs/upload') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            if (result.status === 'success') {
                window.location.reload(); 
            } else {
                alert("Upload failed: " + result.message);
                overlay.style.display = 'none';
            }
        } catch (err) {
            console.error(err);
            alert("Network error.");
            overlay.style.display = 'none';
        }
    }

    async function deleteDesign(id) {
        if (!confirm("Are you sure you want to delete this design?")) return;

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        loadingText.innerText = "Deleting design...";
        overlay.style.display = 'grid';

        try {
            const response = await fetch('<?= base_url('designs/delete/') ?>' + id, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            if (result.status === 'success') {
                window.location.reload();
            } else {
                alert("Delete failed: " + result.message);
                overlay.style.display = 'none';
            }
        } catch (err) {
            console.error(err);
            alert("Network error.");
            overlay.style.display = 'none';
        }
    }
</script>