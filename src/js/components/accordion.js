import debounce from '../util/debounce'

class Accordion {
  constructor(el, settings) {
    this.group = el;
    this.isToggle = this.group.dataset.hasOwnProperty('istoggle')
    this.accordionItems = this.group.getElementsByClassName("accordion");
    this.toggles = this.group.getElementsByClassName("accordion-title");
    this.contents = this.group.getElementsByClassName("accordion-body");

    if (!this.group || !this.accordionItems || !this.toggles || !this.contents) {
      return
    }

    // Set default settings if necessary
    this.settings = {
      speed: 300,
      one_visible: this.isToggle,
      ...settings
    };

    // Setup inital positions
    this.sizeAccordions()

    window.addEventListener('resize', debounce(() => this.sizeAccordions(), 50))

    // Setup click handler
    this.group.addEventListener("click", (e) => {
      if (e.target.classList.contains("accordion-title")) {
        e.preventDefault();

        let num = 0;
        for (let i = 0; i < this.toggles.length; i++) {
          if (this.toggles[i] === e.target) {
            num = i;
            break;
          }
        }

        if (!e.target.parentNode.hasAttribute("open")) {
          this.open(num);
        } else {
          this.close(num);
        }
      }
    });
  }

  sizeAccordions() {
    if (!this.accordionItems) { return }
    for (let i = 0; i < this.accordionItems.length; i++) {
      const item = this.accordionItems[i];
      const toggle = this.toggles[i];
      const content = this.contents[i];

      // Set transition-duration to match JS setting
      item.style.transitionDuration = this.settings.speed + "ms";

      // Set initial height to transition from
      if (!item.hasAttribute("open")) {
        item.style.height = toggle.clientHeight + "px";
      } else {
        item.style.height = toggle.clientHeight + content.clientHeight + "px";
      }
    }
  }

  open(i) {
    const item = this.accordionItems[i];
    const toggle = this.toggles[i];
    const content = this.contents[i];

    // If applicable, hide all the other items first
    if (this.settings.one_visible) {
      for (let a = 0; a < this.toggles.length; a++) {
        if (i !== a) this.close(a);
      }
    }

    // Update class
    item.classList.remove("is-closing");

    // Get height of toggle
    const toggle_height = toggle.clientHeight;

    // Momentarily show the contents just to get the height
    item.setAttribute("open", true);
    toggle.setAttribute("aria-expanded", true);

    const content_height = content.clientHeight;
    item.removeAttribute("open");

    // Set the correct height and let CSS transition it
    item.style.height = toggle_height + content_height + "px";

    // Finally set the open attr
    item.setAttribute("open", true);
  }

  close(i) {
    const item = this.accordionItems[i];
    const toggle = this.toggles[i];

    // Get height of toggle
    const toggle_height = toggle.clientHeight;

    // Set aria attribute to false
    toggle.setAttribute("aria-expanded", false);

    // Set the height so only the toggle is visible
    item.style.height = toggle_height + "px";

    item.removeAttribute("open");
  }
}

export default () => {
  const els = document.getElementsByClassName("bmcb-accordion");

  for (let i = 0; i < els.length; i++) {
    const accordion = new Accordion(els[i]);
  }
};
