 /* pass_form.js */

function show_hide_psw(el) {
   var input = el.parentElement.firstChild;
   if (input.type === "password") {
      input.setAttribute("type", "text");
      el.src = "/theme/img/not-visible.png";
      el.alt = "hide psw";
   } else {
      input.setAttribute("type", "password");
      el.src = "/theme/img/visible.png";
      el.alt = "show psw";
   }
}