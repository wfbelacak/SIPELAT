<?php if(hasRole([1, 2])): ?>
    </div>
</main>
<?php else: ?>
    </div>
</main>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
  setTimeout(function(){document.querySelectorAll('.alert:not(.alert-important)').forEach(function(el){try{new bootstrap.Alert(el).close()}catch(e){}})},4000);
  const toggle=document.getElementById('sidebarToggle'), sidebar=document.getElementById('sidebar');
  if(toggle&&sidebar) toggle.addEventListener('click',function(){sidebar.classList.toggle('d-none');sidebar.classList.toggle('d-block');sidebar.style.transform='translateX(0)';sidebar.style.zIndex='999';});
});
</script>
</body>
</html>
