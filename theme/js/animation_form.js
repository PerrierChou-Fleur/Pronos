function animationForm() {
   let el = document.getElementById('animatedForm');
   el.addEventListener('submit', function(e) {
      e.preventDefault();
      el.setAttribute('class', 'slideout');
      el.removeEventListener('submit', null);
      setTimeout(function() { el.submit(); }, parseInt(parseFloat(window.getComputedStyle(el, null).getPropertyValue('animation-duration')) * 1000));
   });
}