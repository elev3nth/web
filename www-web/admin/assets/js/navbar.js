
var TabNavs = (function() {
  let tabnavs = document.querySelectorAll('.tab-nav');
  if (tabnavs) {
    tabnavs.forEach((tbnvs) => {
      let tabanchor = tbnvs.href.split('#');
      tbnvs.addEventListener('click', (e) => {
        setTimeout((e) => {
          let currurl = document.URL;
          let anchorx = currurl.split('#');
          let ritemsx = document.querySelectorAll('.row-item');
          tabnavs.forEach((tbnvsx) => {
            if (tbnvsx.classList.contains('tablink-'+anchorx[1])) {
              tbnvsx.classList.remove('bg-gray-200');
              tbnvsx.classList.add('bg-gray-100', 'font-bold');
            }
            else{
              tbnvsx.classList.remove('bg-gray-100', 'font-bold');
              tbnvsx.classList.add('bg-gray-200');
            }
          });
          ritemsx.forEach((ritem) => {
            if (ritem.classList.contains('tab-'+anchorx[1])) {
              ritem.classList.remove('hidden');
              ritem.classList.add('flex');
            }
            else{
              ritem.classList.add('hidden');
              ritem.classList.remove('flex');
            }
          });
        }, 150);
      });
    });
    setTimeout((e) => {
      let currurl = document.URL;
      let anchorx = currurl.split('#');
      let ritemsx = document.querySelectorAll('.row-item');
      if (anchorx[1] !== undefined) {
        ritemsx.forEach((ritem) => {
          if (ritem.classList.contains('tab-'+anchorx[1])) {
            let tabnavx = document.querySelector('.tablink-'+anchorx[1]);
            tabnavx.classList.remove('bg-gray-200');
            tabnavx.classList.add('bg-gray-100', 'font-bold');
            ritem.classList.remove('hidden');
            ritem.classList.add('flex');
          }
          else{
            ritem.classList.add('hidden');
            ritem.classList.remove('flex');
          }
        });
      }
      else{
        let ftab = tabnavs[0].href;
        let fanc = ftab.split('#');
        ritemsx.forEach((ritem) => {
          if (ritem.classList.contains('tab-'+fanc[1])) {
            let tabnavx = document.querySelector('.tablink-'+fanc[1]);
            tabnavx.classList.remove('bg-gray-200');
            tabnavx.classList.add('bg-gray-100', 'font-bold');
            ritem.classList.remove('hidden', 'block');
            ritem.classList.add('flex');
          }
        });
      }
    }, 150);
  }
});
