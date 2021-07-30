
export class Tabs {
  constructor(options) {
    this.el = (typeof options.el === 'string') ? document.querySelector(options.el) : options.el;
    this.tabNavigationLinks = this.el.querySelector(options.tabNavigationLinks).children;
    this.tabContentContainers = this.el.querySelector(options.tabContentContainers).children;
    this.activeIndex = 0;
    this.initCalled = false;

    this.init()
  }

  init() {
    if (!this.initCalled) {
      this.initCalled = true;
      this.el.classList.remove('no-js');

      for (var i = 0; i < this.tabNavigationLinks.length; i++) {
        var link = this.tabNavigationLinks[i];
        this.handleClick(link, i);
      }
    }
  }

  handleClick(link, index) {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      this.goToTab(index);
    });
  }

  goToTab(index) {
    if (index !== this.activeIndex && index >= 0 && index <= this.tabNavigationLinks.length) {
      this.tabNavigationLinks[this.activeIndex].classList.remove('is-active');
      this.tabNavigationLinks[index].classList.add('is-active');
      this.tabContentContainers[this.activeIndex].classList.remove('is-active');
      this.tabContentContainers[index].classList.add('is-active');
      this.activeIndex = index;
    }
  };
}