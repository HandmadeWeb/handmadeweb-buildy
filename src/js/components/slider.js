import Siema from 'siema';

let sliders = document.querySelectorAll('.bmcb-slider');
let initSliders = e => e

if (sliders.length) {

  initSliders = () => {

    sliders.forEach(el => {
      let currentIndex;
      let slides = el.querySelector('.bmcb-slider__slides') || el.querySelector('.bmcb-gallery__items');
      let dataAtts = slides.dataset || {}

      const prev = el.querySelector('.bmcb-slider__arrow-prev');
      const next = el.querySelector('.bmcb-slider__arrow-next');

      const slider = new Siema({
        selector: slides,
        duration: Number(dataAtts?.duration) || 200,
        easing: dataAtts?.easing || 'ease-out',
        perPage: Number(dataAtts?.perpage) || 1,
        startIndex: Number(dataAtts?.startindex) || 0,
        draggable: (dataAtts?.draggable === "false") ? false : true,
        multipleDrag: (dataAtts?.draggable === "false") ? false : true,
        threshold: Number(dataAtts?.threshold) || 20,
        loop: (dataAtts?.loop === "false") ? false : true,
        rtl: (dataAtts?.rtl === "true") ? true : false,
        onInit: function () {
          currentIndex = this.currentSlide
        },
        onChange: function () {

          let pagination = this.selector.parentNode.querySelector('.bmcb-slider__navigation-dots');

          if (pagination) {
            let isActive = pagination.querySelector('.is-active');

            // Remove isActive from any other ones
            if (isActive) {
              isActive.classList.remove('is-active')
            }

            // Make the clicked one active
            pagination.children[this.currentSlide].classList.add('is-active');
          }

        },
      });

      if (next && prev) {
        prev.addEventListener('click', () => slider.prev())
        next.addEventListener('click', () => slider.next())
      }

      let sliding;

      const startSliding = () => {
        sliding = setInterval(() => slider.next(), Number(dataAtts?.interval) || 5000)
      }

      if ((dataAtts?.autoplay === "false") ? false : true) {
        startSliding();

        el.addEventListener('mouseenter', () => {
          clearInterval(sliding)
        })
        el.addEventListener('mouseleave', () => startSliding())

        // Start / stop them when the browser tab is hidden/visible
        document.addEventListener('visibilitychange', function (ev) {
          if (document.visibilityState === 'hidden') {
            clearInterval(sliding)
          } else {
            startSliding()
          }
        })
      }

      if ((dataAtts?.paginationdots === "false") ? false : true) {
        slider.addPagination();
      }
    })


  }

}

Siema.prototype.addPagination = function () {
  const ul = document.createElement('ul');
  ul.classList.add('bmcb-slider__navigation-dots');
  for (let i = 0; i < this.innerElements.length; i++) {
    const li = document.createElement('li');
    // li.textContent = i;

    if (this.currentSlide === i) {
      li.classList.add('is-active')
    }

    li.addEventListener('click', (el) => {
      this.goTo(i)
    });

    ul.appendChild(li)
  }
  this.selector.parentNode.appendChild(ul);
}


export { initSliders }
