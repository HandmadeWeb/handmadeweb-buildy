// Set this to be your projects breakpoints
const sizes = {
  sm: 576,
  md: 768,
  lg: 992,
  xl: 1200,
}

const isAbove = {}

Object.entries(sizes).forEach(([size, val]) => {
  isAbove[size] = window.matchMedia(`(min-width: ${val}px)`)
})

export { sizes, isAbove }
