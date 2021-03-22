let videoObserver;

const lazyLoad = (entries, observer, test) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.setAttribute('src', entry.target.dataset.src)
      videoObserver.unobserve(entry.target)
    }
  })
}

videoObserver = new IntersectionObserver(lazyLoad, {
  root: null,
  rootMargin: '0px',
  threshold: 0
})



export default () => {
  let iframes = document.querySelectorAll('.youtube-iframe');
  if (iframes.length) {
    iframes.forEach(iframe => {
      videoObserver.observe(iframe)
    })
  }
}