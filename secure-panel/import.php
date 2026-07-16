<?php
// secure-panel/import.php
require_once __DIR__ . '/includes/auth.php';
$admin_title = 'Import from Z2U';
include __DIR__ . '/includes/admin_header.php';

// The javascript payload
$js = <<<JS
javascript:(function(){
    var products = [];
    document.querySelectorAll('.goods-item').forEach(function(el){
        var title = el.querySelector('.goods-title') ? el.querySelector('.goods-title').innerText : '';
        var price = el.querySelector('.goods-price') ? el.querySelector('.goods-price').innerText.replace(/[^0-9.]/g, '') : '0';
        var img = el.querySelector('img') ? el.querySelector('img').src : '';
        if(title) {
            products.push({title: title, price: price, image_url: img, description: 'Imported Z2U Listing'});
        }
    });
    if(products.length === 0) {
        alert("No products found on this page! Make sure you are on your Z2U shop page.");
        return;
    }
    fetch('https://ehtshaamrajpoot.com/secure-panel/api/import_z2u.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({products: products})
    }).then(r => r.json()).then(data => {
        if(data.success) {
            alert('Success! ' + data.imported + ' new products imported to your site!');
        } else {
            alert('Error importing: ' + data.error);
        }
    }).catch(e => alert('Network error: ' + e));
})();
JS;
$bookmarklet = trim(preg_replace('/\s+/', ' ', $js));
?>

<div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <h2>1-Click Z2U Importer</h2>
    <p>Since Z2U's security firewall blocks automated robots, we have built a clever workaround! You can import all your products in 5 seconds by using this special button.</p>
    
    <div style="background: #e0e7ff; border-left: 4px solid #4f46e5; padding: 20px; margin: 20px 0;">
        <h3>How to use:</h3>
        <ol style="line-height: 1.8;">
            <li>Drag this blue button to your browser's Bookmark/Favorites bar: 
                <a href="<?php echo htmlspecialchars($bookmarklet); ?>" style="display:inline-block; background: #4f46e5; color: white; padding: 5px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; cursor: grab;">Import to My Site</a>
            </li>
            <li>Open a new tab and go to your Z2U shop page: <strong>https://www.z2u.com/shop/ehtshaamrajpoot</strong></li>
            <li>Click the "Import to My Site" bookmark button you just saved.</li>
            <li>Wait a few seconds, and it will pop up a success message!</li>
        </ol>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
