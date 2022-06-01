export class Hooks {
  constructor() {
    this.queue = [];
  }

  /**
   * add(name, callback)
   * Example:
   * hooks.add('myHook', () => {
   *    console.log('myHook ran!')
   * })
   * @param  {String} name        [description]
   * @param  {Function} callback  [description]
   * @return {Void}
   */
  add(name, callback) {
    if (!this.queue[name]) {
      this.queue[name] = [];
    }
    this.queue[name].push(callback);
  }

  /**
   * run(name, ...args)
   * Example:
   * hooks.run('myHook', 'foo', 'bar')
   * @param  {String} name The name of the hook.
   * @param  {...*} args  The arguments to pass to the callback.
   * @return {Void}
   */
  run(name, ...args) {
    if (this.queue[name]) {
      this.queue[name].forEach(callback => {
        callback(...args);
      });
    }
  }

  /**
   * remove(name, callback)
   * Example:
   * hooks.remove('myHook', () => {
   *    console.log('myHook ran!')
   * })
   * @param  {String} name The name of the hook.
   * @param  {Function} callback  The callback to remove.
   * @return {Void}
   */
  remove(name, callback) {
    if (this.queue[name]) {
      this.queue[name] = this.queue[name].filter(cb => cb !== callback);
    }
  }

  /**
   * clear(name)
   * Example:
   * hooks.clear('myHook')
   * @param  {String} name The name of the hook.
   * @return {Void}
   */
  clear(name) {
    if (this.queue[name]) {
      this.queue[name] = [];
    }
  }

  /**
   * clearAll()
   * Example:
   * hooks.clearAll()
   * @param  {Void}
   * @return {Void}
   */
  clearAll() {
    this.queue = {};
  }

  /**
   * get(name)
   * Example:
   * hooks.get('myHook')
   * @param  {String} name The name of the hook.
   * @return {Array}
   */
  get(name) {
    if (this.queue[name]) {
      return this.queue[name];
    }
    return [];
  }

  /**
   * has(name)
   * Example:
   * hooks.has('myHook')
   * @param  {String} name The name of the hook.
   * @return {Boolean}
   */
  has(name) {
    return !!this.queue[name];
  }

  /**
   * count(name)
   * Get the number of callbacks in the queue for a particular hook.
   * Example:
   * hooks.count('myHook')
   * @param  {String} name The name of the hook.
   * @return {Number}
   */
  count(name) {
    if (this.queue[name]) {
      return this.queue[name].length;
    }
    return 0;
  }

  /**
   * hasCallbacks(name)
   * Check if the queue has any callbacks for a particular hook.
   * Example:
   * hooks.hasCallbacks('myHook')
   * @param  {String} name The name of the hook.
   * @return {Boolean}
   */
  hasCallbacks(name) {
    return !!this.queue[name] && this.queue[name].length > 0;
  }

  /**
   * hasCallback(name, callback)
   * Check if a callback exists in the queue.
   * Example:
   * hooks.hasCallback('myHook', () => {
   *    console.log('myHook ran!')
   * })
   * @param  {String} name The name of the hook.
   * @param  {Function} callback The callback to check for.
   * @return {Boolean}
   */
  hasCallback(name, callback) {
    return !!this.queue[name] && this.queue[name].includes(callback);
  }
}