(function(){
  document.querySelectorAll('form[data-form]').forEach((form)=>{
    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const fd = new FormData(form);
      if(!fd.has('company')) fd.append('company','');
      const payload = {
        name: fd.get('name') || '',
        phone: fd.get('phone') || '',
        svc: fd.get('svc') || '',
        msg: fd.get('msg') || '',
        company: fd.get('company') || ''
      };
      try{
        const r = await fetch('/api/send.php', {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify(payload)
        });
        const j = await r.json();
        if(j.ok){
          alert('Заявка отправлена. Мы свяжемся с вами.');
          form.reset();
        }else{
          alert('Ошибка: ' + (j.message || 'попробуйте позже'));
        }
      }catch(err){
        alert('Сеть/сервер недоступны. Попробуйте позже.');
      }
    });
  });
})();
