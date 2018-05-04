 /* timezone_form.js */

 function createSelect(list, name, id) {
   let select = document.createElement('select');
   select.setAttribute('name', name);
   select.setAttribute('id', id);
   select.setAttribute('onChange', 'filter(this.value)');
   for(let region of list) {
      let option = document.createElement('option');
      option.setAttribute('value', region);
      let regex = new RegExp('\/(.+)$');
      if((content = regex.exec(region)) !== null) {
         content = document.createTextNode(content[1]);
      } else {
         content = document.createTextNode(region);
      }
      option.appendChild(content);
      select.appendChild(option);
   }
   return select;
}
function filter(val) {
   let oldselect = document.getElementById('select_default_timezone');
   let list = [];
   if(val != 'Others') {
      let regex = new RegExp('^'+val);
      for(let option of oldselect.childNodes) {
         if(regex.exec(option.value)) {
           list.push(option.value);
         }
      }
   } else {
      list = ['UTC'];
      /* //Gestion des Others avec anciens fuseaux horaires
      val = ['Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific'];
      val = val.join('|');
      let old_list = [];
      let filter_list = [];
      let regex = new RegExp('^'+val);
      for(let option of oldselect.childNodes) {
         if(option.nodeName == 'OPTION') {
            old_list.push(option.value);
            if(regex.exec(option.value)) {
              filter_list.push(option.value);
            }
         }
      }
      list = old_list.filter(function(val) {return filter_list.indexOf(val) < 0;});
      */
   }
   if(document.getElementById('select_default_timezone_bis')) {
      let el = document.getElementById('select_default_timezone_bis');
      el.parentNode.removeChild(el);
   }
   let select = createSelect(list, 'default_timezone', 'select_default_timezone_bis');
   document.getElementById('li_default_timezone').appendChild(select);
   return null;
}
function timezone() {
   let select = createSelect(['Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific', 'Others'], 'preselect', 'preselect');
   let parent = document.getElementById('li_default_timezone');
   parent.insertBefore(select, parent.firstChild);
   let oldselect = document.getElementById('select_default_timezone');
   oldselect.name = 'default_timezone_disable';
   oldselect.style.display = 'none';
   document.getElementById('preselect').childNodes[7].selected = true;
   filter('Europe');
   document.getElementById('select_default_timezone_bis').childNodes[25].selected = true;
   return null;
}