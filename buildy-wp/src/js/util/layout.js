import debounce from '../util/debounce';
import { isAbove } from '../util/breakpoints';
let $ = jQuery;
// Pull images to left/ride edges of browser window when restricted by the gridmonster.
export const offsetColumns = function() {
  // If added to rows directly
  let moduleLeft = $('.offset-left > .bmcb-column:first-child .bmcb-module');
  let moduleRight = $('.offset-right > .bmcb-column:last-child .bmcb-module');
  let ww = $(window).outerWidth();

  // If added to a section
  if ($('.offset-left').children('.row').length) {
    moduleLeft = $('.offset-left .row > .bmcb-column:first-child .bmcb-module');
  }
  // If added to a section
  if ($('.offset-right').children('.row').length) {
    moduleRight = $(
      '.offset-right .row > .bmcb-column:last-child .bmcb-module'
    );
  }

  if (isAbove.md.matches) {
    addOffset();
  }

  window.addEventListener(
    'resize',
    debounce(
      function() {
        ww = $(window).outerWidth();
        // Only work above md
        if (isAbove.md.matches) {
          addOffset();
        } else {
          removeOffset();
        }
      },
      50,
      true,
      true
    )
  );

  function addOffset() {
    if (moduleLeft && moduleLeft.length) {
      let offsetleft = moduleLeft.parent('.bmcb-column').offset().left;
      moduleLeft.css({
        'margin-left': '-' + offsetleft + 'px'
      });
    }
    if (moduleRight && moduleRight.length) {
      let offsetleft = moduleRight.parent('.bmcb-column').offset().left;
      let offsetright = ww - (offsetleft + moduleRight.outerWidth(true));
      moduleRight.css({
        'margin-right': '-' + offsetright + 'px'
      });
    }
  }

  function removeOffset() {
    if (moduleLeft.length) {
      moduleLeft.css({
        'margin-left': '0'
      });
    }
    if (moduleRight.length) {
      moduleRight.css({
        'margin-right': '0'
      });
    }
  }
};
