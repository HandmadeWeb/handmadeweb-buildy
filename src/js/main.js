import accordions from './components/accordion'
import counters from './components/counters'
import { Tabs } from './components/tabs'
import { initSliders } from './components/slider'
import youtubeVideos from './components/youtube'
import baguetteBox from 'baguettebox.js';
import Macy from 'macy';

// This is a helper that will automatically generate field keys for ACF fields and console log the result so you can copy/paste into the file
// import { replaceACFFieldKeys } from './util/replaceACFFieldKeys';
/* 
fetch("../../acf/buildy-module-styles.json").then(res => res.json()).then(data => {
  data.forEach(option => {
    option.fields.forEach(field => {console.log(recursifyID(field))})
  })
});
*/


(function () {
  let gallerySelector = '.bmcb-gallery:not(.bmcb-slider) .bmcb-gallery__items'
  let galleries = document.querySelectorAll(gallerySelector);

  // Create Lightbox
  if (gallerySelector) {
    baguetteBox.run(gallerySelector);
  }

  if (galleries) {
    [...galleries].forEach(gallery => {
      let dataAtts = gallery.dataset || {},
        marginX = dataAtts?.marginx || 10,
        marginY = dataAtts?.marginy || 5;

      // Convert layout to masonry
      if (gallery.classList.contains('is-masonry')) {
        let macyInstance = new Macy({
          container: gallery,
          columns: dataAtts?.columnCount || 3,
          margin: {
            x: parseInt(marginX),
            y: parseInt(marginY)
          }
        })
      }
    })
  }
})()

// Enable Accordions
accordions();

// Enable Siema Sliders
initSliders();

// Enable YoutubeVideos
youtubeVideos();

// Enable Counters 
counters();


// Init default tabs
let tabs = document.querySelectorAll('.bmcb-tab');
tabs.forEach(tab => {
  tab['tabs'] = new Tabs({
    el: tab,
    tabNavigationLinks: '.tabs-menu-tabs',
    tabContentContainers: '.tabs-content'
  })
})