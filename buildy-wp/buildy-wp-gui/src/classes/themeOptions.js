export class SetThemeOptions {
    constructor({ colors, spacing }) {
        this.root = document.documentElement;
        this.themeColors = colors
        this.themeSpacing = spacing

        this.init()
    }

    init() {
        // Setup Theme Colors
        if (this.themeColors.length) {
            this.setThemeColors()
        }

        // Setup Theme Spacing Options (margins/paddings)
        if (this.themeSpacing.length) {
            this.setThemeSpacing()
        }
    }

    setVar(prop, val) {
        this.root.style.setProperty(prop, val);
    }

    setThemeColors() {
        this.themeColors.map(color => {
            this.setVar(`--${color.name}`, color.value)
        })
    }
}
