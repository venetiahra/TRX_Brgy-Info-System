document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('[data-birth-date]').forEach(function(input){
    const target=document.querySelector(input.getAttribute('data-age-target')||'');
    const update=function(){ if(!target||!input.value) return; const b=new Date(input.value); const t=new Date(); let age=t.getFullYear()-b.getFullYear(); const m=t.getMonth()-b.getMonth(); if(m<0||(m===0&&t.getDate()<b.getDate())) age--; target.value=age>=0?age:0; };
    input.addEventListener('change', update); update();
  });
  document.querySelectorAll('.js-confirm-delete').forEach(function(form){ form.addEventListener('submit', function(e){ if(!window.confirm('Delete this resident record? This action cannot be undone.')) e.preventDefault(); }); });
  const toggle=document.querySelector('[data-sidebar-toggle]'); const backdrop=document.querySelector('.sidebar-backdrop'); const close=()=>document.body.classList.remove('sidebar-open');
  if(toggle) toggle.addEventListener('click',()=>document.body.classList.toggle('sidebar-open')); if(backdrop) backdrop.addEventListener('click', close); window.addEventListener('resize',()=>{ if(window.innerWidth>=992) close(); });
});
