export class Hooks {
  constructor() {
    this.queue = [];
  }

  // add(name, callback)
  //
  // Add a callback to the queue.
  //
  // Parameters:
  //   name - The name of the hook.
  //   callback - The callback to add.
  //  
  // Returns:
  //   void
  //
  // Example:
  //   hooks.add('myHook', () => {
  //     console.log('myHook ran!')
  //   })
  add(name, callback) {
    if (!this.queue[name]) {
      this.queue[name] = [];
    }
    this.queue[name].push(callback);
  }


  // run(name, ...args)
  //
  // Run a hook.
  //
  // Parameters:
  //   name - The name of the hook.
  //   args - The arguments to pass to the callback.
  //
  // Returns:
  //   void
  //
  // Example:
  //   hooks.run('myHook', 'foo', 'bar')
  run(name, ...args) {
    if (this.queue[name]) {
      this.queue[name].forEach(callback => {
        callback(...args);
      });
    }
  }

  // remove(name, callback)
  //
  // Remove a callback from the queue.
  //
  // Parameters:
  //   name - The name of the hook.
  //   callback - The callback to remove.
  //
  // Returns:
  //   void
  //
  // Example:
  //   hooks.remove('myHook', () => {
  //     console.log('myHook ran!')
  //   })
  remove(name, callback) {
    if (this.queue[name]) {
      this.queue[name] = this.queue[name].filter(cb => cb !== callback);
    }
  }

  // clear(name)
  //
  // Clear all callbacks from the queue.
  //
  // Parameters:
  //   name - The name of the hook.
  //
  // Returns:
  //   void
  //
  // Example:
  //   hooks.clear('myHook')
  clear(name) {
    if (this.queue[name]) {
      this.queue[name] = [];
    }
  }

  // clearAll()
  //
  // Clear all callbacks from all queues.
  //
  // Parameters:
  //   void
  //
  // Returns:
  //   void
  //
  // Example:
  //   hooks.clearAll()
  clearAll() {
    this.queue = {};
  }

  // get(name)
  //
  // Get all callbacks from the queue.
  //
  // Parameters:
  //   name - The name of the hook.
  //
  // Returns:
  //   Array
  //
  // Example:
  //   hooks.get('myHook')
  get(name) {
    if (this.queue[name]) {
      return this.queue[name];
    }
    return [];
  }

  // has(name)
  //
  // Check if a hook exists.
  //
  // Parameters:
  //   name - The name of the hook.
  //
  // Returns:
  //   Boolean
  //
  // Example:
  //   hooks.has('myHook')
  has(name) {
    return !!this.queue[name];
  }

  // count(name)
  //
  // Get the number of callbacks in the queue.
  //
  // Parameters:
  //   name - The name of the hook.
  //
  // Returns:
  //   Number
  //
  // Example:
  //   hooks.count('myHook')
  count(name) {
    if (this.queue[name]) {
      return this.queue[name].length;
    }
    return 0;
  }

  // hasCallbacks(name)
  //
  // Check if the queue has any callbacks.
  //
  // Parameters:
  //   name - The name of the hook.
  //
  // Returns:
  //   Boolean
  //
  // Example:
  //   hooks.hasCallbacks('myHook')
  hasCallbacks(name) {
    return !!this.queue[name] && this.queue[name].length > 0;
  }

  // hasCallback(name, callback)
  //
  // Check if a callback exists in the queue.
  //
  // Parameters:
  //   name - The name of the hook.
  //   callback - The callback to check for.
  //
  // Returns:
  //   Boolean
  //
  // Example:
  //   hooks.hasCallback('myHook', () => {
  //     console.log('myHook ran!')
  //   })
  hasCallback(name, callback) {
    return !!this.queue[name] && this.queue[name].includes(callback);
  }

}