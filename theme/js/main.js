 /* main.js */

function updateDate(el, datetimediff, dateformat) {
   let datetime = new Date();
   datetime.setMilliseconds(datetime.getMilliseconds() + datetimediff);
   let year = datetime.getFullYear();
   let month = parseInt(datetime.getMonth()) + 1;
   if(month < 10) {
      month = "0" + month;
   }
   let day = datetime.getDate();
   if(day < 10) {
      day = "0" + day;
   }
   let hour = datetime.getHours();
   if(hour < 10) {
      hour = "0" + hour;
   }
   let minute = datetime.getMinutes();
   if(minute < 10) {
      minute = "0" + minute;
   }
   let second = datetime.getSeconds();
   if(second < 10) {
      second = "0" + second;
   }
   let a = null;
   switch(dateformat) {
      case 'd/m/Y H:i:s':
         el.textContent = day + "/" + month + "/" + year + " " + hour + ":" + minute + ":" + second;
         break;
      case 'd/m/Y h:i:s a':
         hour = parseInt(hour);
         if(hour < 12) {
            a = " a.m.";
         } else {
            a = " p.m.";
         }
         if(hour > 12) {
            hour = hour - 12;
         } else if(hour == 0) {
            hour = 12;
         }
         if(hour < 10) {
            hour = "0" + hour;
         }
         el.textContent = day + "/" + month + "/" + year + " " + hour + ":" + minute + ":" + second + a;
         break;
      case 'm/d/Y h:i:s a':
         hour = parseInt(hour);
         if(hour < 12) {
            a = " a.m.";
         } else {
            a = " p.m.";
         }
         if(hour > 12) {
            hour = hour - 12;
         } else if(hour == 0) {
            hour = 12;
         }
         if(hour < 10) {
            hour = "0" + hour;
         }
         el.textContent = month + "/" + day + "/" + year + " " + hour + ":" + minute + ":" + second + a;
         break;
      default:
         el.textContent = datetime;
   }
}
function changeDate(dateformat) {
   let datetime1 = document.getElementById('datetime');
   let datetime2 = null;
   let fulldate = datetime1.textContent;
   let days = parseInt(fulldate.slice(0,2));
   let months = parseInt(fulldate.slice(3,5)) - 1;
   let years = parseInt(fulldate.slice(6,10));
   let hours = parseInt(fulldate.slice(11,13));
   let mins = parseInt(fulldate.slice(14,16));
   let secs = parseInt(fulldate.slice(17,19));
   let a = null;
   switch(dateformat) {
      case 'd/m/Y H:i:s':
         datetime2 = new Date(years, months, days, hours, mins, secs, 0);
         break;
      case 'd/m/Y h:i:s a':
         a = fulldate.slice(20,22);
         if(a == "pm" && hours < 12) {
            hours = hours + 12;
         } else if(a == "am" && hours == 12) {
            hours = 0;
         }
         datetime2 = new Date(years, months, days, hours, mins, secs, 0);
         break;
      case 'm/d/Y h:i:s a':
         days = parseInt(fulldate.slice(3,5));
         months = parseInt(fulldate.slice(0,2)) - 1;
         a = fulldate.slice(20,22);
         if(a == "pm" && hours < 12) {
            hours = hours + 12;
         } else if(a == "am" && hours == 12) {
            hours = 0;
         }
         datetime2 = new Date(years, months, days, hours, mins, secs, 0);
         break;
      default:
         datetime2 = new Date();
   }
   let datetime3 = new Date();
   let datetimediff = datetime3 - datetime2;
   updateDate(datetime1, datetimediff, dateformat);
   setInterval(function() { updateDate(datetime1, datetimediff, dateformat); return null; }, 1000);
}