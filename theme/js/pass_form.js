 /* pass_form.js */

function show_hide_psw(el) {
   let input = el.previousSibling;
   if (input.type === "password") {
      input.setAttribute("type", "text");
      el.setAttribute("src", "/theme/img/not-visible.png");
      el.setAttribute("alt", "hide psw");
   } else {
      input.setAttribute("type", "password");
      el.setAttribute("src", "/theme/img/visible.png");
      el.setAttribute("alt", "hide psw");
   }
}