document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.dataset.id;
            fetch('add_to_cart.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:`item_id=${id}` })
            .then(r=>r.json()).then(data=>{ if(data.success){ updateCartCount(); showNotification('Added to cart!'); } });
        });
    });
    document.querySelectorAll('.cart-quantity').forEach(inp => {
        inp.addEventListener('change', function() {
            let id = this.dataset.id, qty = this.value;
            fetch('update_cart.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`item_id=${id}&quantity=${qty}`})
            .then(r=>r.json()).then(data=>{ if(data.success) location.reload(); });
        });
    });
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Remove?')) fetch('remove_cart_item.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`item_id=${this.dataset.id}`})
            .then(r=>r.json()).then(data=>{ if(data.success) location.reload(); });
        });
    });
});
function updateCartCount() { fetch('get_cart_count.php').then(r=>r.json()).then(data=>{ let c=document.getElementById('cart-count'); if(c) c.innerText=data.count; }); }
function showNotification(msg){ let d=document.createElement('div'); d.innerText=msg; d.style.cssText='position:fixed; bottom:20px; right:20px; background:#28a745; color:white; padding:10px 20px; border-radius:5px; z-index:1000;'; document.body.appendChild(d); setTimeout(()=>d.remove(),2000); }