
var UserLogin = (() => {

  let uname = document.querySelector('#userName');
  let upass = document.querySelector('#userPass');
  let remem = document.querySelector('#rememberMe');
  let ulogn = document.querySelector('#userLogin');
  let ucsrf = document.querySelector('#userCsrf');

  if (uname && upass && ulogn && ucsrf) {

    uname.focus();
    let regxemail =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    ulogn.addEventListener('click', (e) => {
      if (uname.value.length &&
          upass.value.length &&
          ucsrf.value.length &&
          regxemail.test(uname.value)
      ) {
        return true;
      }
    });

  }
  
  return false;

});
