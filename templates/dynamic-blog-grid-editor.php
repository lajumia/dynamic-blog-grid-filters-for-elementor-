<button class="mobile-filter-btn" onclick="toggleFilter()">☰ <span class="badge" id="filterCount">0</span></button>

<div class="overlay" id="overlay" onclick="closeFilter()"></div>

<div class="blog-wrapper">

<aside class="sidebar" id="sidebar">
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
<h3 style="margin:0">Filter</h3>
<button id="clearFilters" style="background:none;border:none;color:#2563eb;font-size:13px;cursor:pointer">Clear all</button>
</div>
<h3>Filter</h3>

<div class="filter-group">
<h4>Category</h4>
<div class="filter-search"><input type="text" placeholder="Search category" onkeyup="filterList(this)"></div>
<div class="filter-list">
<label><input type="checkbox"> Technology <span style="margin-left:auto;color:#999;font-size:12px">12</span></label>
<label><input type="checkbox"> Business <span style="margin-left:auto;color:#999;font-size:12px">8</span></label>
<label><input type="checkbox"> Crypto <span style="margin-left:auto;color:#999;font-size:12px">5</span></label>
<label><input type="checkbox"> Marketing <span style="margin-left:auto;color:#999;font-size:12px">9</span></label>
<label><input type="checkbox"> Design <span style="margin-left:auto;color:#999;font-size:12px">6</span></label>
<label><input type="checkbox"> Finance <span style="margin-left:auto;color:#999;font-size:12px">4</span></label>
</div>
</div>

<div class="filter-group">
<h4>Tags</h4>
<div class="filter-search"><input type="text" placeholder="Search tag" onkeyup="filterList(this)"></div>
<div class="filter-list">
<label><input type="checkbox"> UI/UX</label>
<label><input type="checkbox"> Trading</label>
<label><input type="checkbox"> SEO</label>
<label><input type="checkbox"> Startup</label>
<label><input type="checkbox"> AI</label>
<label><input type="checkbox"> Growth</label>
<label><input type="checkbox"> Analytics</label>
</div>
</div>
</aside>

<section class="blog-section">

<div class="blog-grid" id="blogGrid">
<!-- Skeleton preload -->
<div class="blog-card">
<div class="skeleton skeleton-img"></div>
<div class="skeleton skeleton-text"></div>
<div class="skeleton skeleton-text" style="width:70%"></div>
</div>
<div class="blog-card">
<div class="skeleton skeleton-img"></div>
<div class="skeleton skeleton-text"></div>
<div class="skeleton skeleton-text" style="width:70%"></div>
</div>
</div>

<div class="pagination">
<a href="#">«</a><a class="active" href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">»</a>
</div>
</section>
</div>

<script>
function filterList(input){
  const term=input.value.toLowerCase();
  const labels=input.closest('.filter-group').querySelectorAll('.filter-list label');
  labels.forEach(l=>{
    l.style.display=l.textContent.toLowerCase().includes(term)?'flex':'none'
  })
}
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const filterCount = document.getElementById('filterCount');

function toggleFilter(){
  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
}

function closeFilter(){
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
}

// Close on ESC
document.addEventListener('keydown',e=>{
  if(e.key==='Escape') closeFilter();
});

// Filter counter
const checkboxes = document.querySelectorAll('.filter-group input');
checkboxes.forEach(cb=>{
  cb.addEventListener('change',updateFilterCount);
});

function updateFilterCount(){
  const count = document.querySelectorAll('.filter-group input:checked').length;
  filterCount.textContent = count;
}

// Clear all filters
document.getElementById('clearFilters').addEventListener('click',()=>{
  checkboxes.forEach(cb=>cb.checked=false);
  updateFilterCount();
});

// Simulate loading
setTimeout(()=>{
const grid=document.getElementById('blogGrid');
grid.innerHTML=`
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Modern Web Design</h3><p>Clean UI trends.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Crypto Market</h3><p>Latest analysis.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>Startup Growth</h3><p>Scale smarter.</p><a href="#">Read More →</a></div></div>
<div class="blog-card"><img src="https://www.americanstonecraft.com/wp-content/uploads/2025/10/American-Stonecraft-picnic-charcuterie-boards-bowls-coasters-1.webp"><div class="blog-content"><h3>UI UX Design</h3><p>Design systems.</p><a href="#">Read More →</a></div></div>`
},1200);
</script>