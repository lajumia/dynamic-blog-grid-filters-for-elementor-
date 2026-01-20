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
const clearFilters = document.getElementById('clearFilters');
if(clearFilters){
    clearFilters.addEventListener('click',()=>{
  const post_per_page_clear = clearFilters.dataset.postperpage;
  checkboxes.forEach(cb=>cb.checked=false);
  updateFilterCount();
  dbgfeLoadPosts(1,post_per_page_clear);
});


}


document.addEventListener('click', function(e) {
    
    const link = e.target.closest('.pagination a');
    if (!link || link.classList.contains('disabled')) return;
    e.preventDefault();
    const page = link.dataset.page;
    const posts_per_page = link.dataset.postsPerPage;
    

    dbgfeLoadPosts(page,posts_per_page); 
});

document.querySelectorAll(
    '.dbgfe-category-filter, .dbgfe-tag-filter'
).forEach(input => {
    input.addEventListener('change', () => {
        const post_per_page_cat_tag = input.dataset.postperpage;
        dbgfeLoadPosts(1,post_per_page_cat_tag);
    });
});

function dbgfeLoadPosts(page = 1,posts_per_page = 8) {

    const grid = document.getElementById('blogGrid');
    const skeletonLoading = '<div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div><div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div><div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div><div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div><div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div><div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div><div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div><div class="blog-card skeleton-card"><div class="skeleton skeleton-img"></div><div class="skeleton skeleton-text"></div><div class="skeleton skeleton-text" style="width:70%"></div></div>';

    grid.innerHTML = skeletonLoading;

    // Collect selected categories
    const categories = Array.from(
        document.querySelectorAll('.dbgfe-category-filter:checked')
    ).map(el => el.value);

    // Collect selected tags
    const tags = Array.from(
        document.querySelectorAll('.dbgfe-tag-filter:checked')
    ).map(el => el.value);
    

    const params = new URLSearchParams({
        action: 'dbgfe_load_posts',
        page: page,
        posts_per_page : posts_per_page,
        categories: categories.join(','),
        tags: tags.join(','),
        current_url: window.location.href,
        nonce: dbgfe_ajax.nonce
    });

    fetch(dbgfe_ajax.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params.toString()
    })
    .then(response => response.json())
    .then(data => {

        grid.innerHTML = data.posts;
       
       

        const paginationWrap = document.getElementById('dbgfe-pagination');
        if (paginationWrap) {
            paginationWrap.outerHTML = data.pagination;
        } else {
            document
                .querySelector('.blog-section')
                .insertAdjacentHTML('beforeend', data.pagination);
        }

    });

}