/**
 * the most terrible camelizer on the internet, guaranteed!
 * @param {string} str String that isn't camel-case, e.g., CAMeL_CaSEiS-harD
 * @return {string} String converted to camel-case, e.g., camelCaseIsHard
 */

// This will CamelCase your string
const camelCase = (str) => `${str.charAt(0).toLowerCase()}${str.replace(/[\W_]/g, '|').split('|')
  .map(part => {
    return `${part.charAt(0).toUpperCase()}${part.slice(1)}`
  })
  .join('')
  .slice(1)}`;

// Truncate letters
const truncate = (sentence, amount, tail) => {
  const letters = sentence.split('');

  if (amount >= letters.length) {
    return sentence;
  }

  const truncated = letters.slice(0, amount);
  return `${truncated.join('')}${tail}`;
}

// This will truncate whole words (rather than just letters)
const truncateWords = (sentence, amount, tail) => {
  const words = sentence.split(' ');

  if (amount >= words.length) {
    return sentence;
  }

  const truncated = words.slice(0, amount);
  return `${truncated.join(' ')}${tail}`;
}


// This will justify letters evenly. Never use this. Ever.
const justifyLettersEven = () => {
  const targets = document.querySelectorAll('.justify-letters-even');
  if (!targets.length) {
    return
  }

  targets.forEach(target => {
    target.innerHTML = target.innerText.split('').map(e => !e.trim() ? "<span>&nbsp</span>" : `<span>${e}</span>`).join('');
  })

}

export { camelCase, truncate, truncateWords, justifyLettersEven }
