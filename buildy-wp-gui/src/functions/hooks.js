export class Hooks {
  constructor() {
    this.queue = {};
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
  add(name, callback, priority = 10) {
    if (this.queue[name] === undefined) {
      this.queue[name] = [];
    }
    this.queue[name].push({ callback, priority });
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
    return new Promise((resolve, reject) => {
      const callbacks = this.getCallbacks(name);
      // If the below causes issue (spreading results) then args will need to be wrapped in an array here
      if (!callbacks.length) resolve(...args)
      this.getCallbacks(name)
        .sort((a, b) => b.priority - a.priority)
        .map(hook => this._convertToPromiseCallback(hook.callback, ...args))
        .reduce((promise, callback) => {
          return promise.then(result => callback().then(Array.prototype.concat.bind(result)));
        }, Promise.resolve([]))
        // Return result, we mau need this to be non-spreaded for some reason if so.
        .then(results => resolve(...results))
        .catch(error => reject(error));
    });
  }

  /**
  * convertToPromiseCallback(callback, payload)
  * Convert a callback to a promise callback. So that hooks always return a promise.
  * @param  {Function} callback The name of the hook.
  * @param  {...*} args  The data being passed to the callback.
  * @return {Void}
  */
  _convertToPromiseCallback(callback, ...args) {
    return () => {
      return new Promise((resolve, reject) => {
        callback(resolve, reject, ...args)
      });
    };
  }

  /**
   * remove(name, callback)
   * Example:
   * hooks.remove('myHook', callback)
   * @param  {String} name The name of the hook.
   * @param  {Function} callback  The callback to remove.
   * @return {Void}
   */
  remove(name, callback) {
    if (this.queue[name]) {
      this.queue[name] = this.queue[name].filter(el => !el === callback);
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
   * getCallbacks(name)
   * Get the callbacks in the queue for a particular hook.
   * Example:
   * hooks.get('myHook')
   * @param  {String} name The name of the hook.
   * @return {Array}
   */
  getCallbacks(name) {
    return this.queue[name] || [];
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
    return !!this.queue[name] && this.queue[name].filter(el => el.includes(callback));
  }
}