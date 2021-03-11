/* Debounce prevents burst fires by triggering once, then ignoring until the timeout
    * @param func -- The function you're debouncing
    * @param wait -- Delay before firing again after the hit
    * @param immediate -- fire instantly at the start
    * @param trailing -- fire at the end no matter what
    * @returns {Function}
    */
    export default function debounce(func, wait, immediate, trailing) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
                // Added this so it will always fire at the end as well
                if (trailing) {
                    func.apply(context, args);
                    console.log('fired later');
                }
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
