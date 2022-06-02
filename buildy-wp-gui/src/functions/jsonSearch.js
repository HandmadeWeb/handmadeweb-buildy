
export const findObj = async (store, id) => {
  let obj;
  if (store.length > 1) {
    for (const section of store) {
      obj = await Promise.resolve(searchJSON(section, id))
      if (obj) {
        break;
      }
    }
  } else {
    obj = await Promise.resolve(searchJSON(store[0], id))
  }
  return obj
}

export function searchJSON(tree, target, property = 'id') {
  if (tree && tree[property] === target) {
    return [tree];
  }

  let found = []

  if (tree && tree?.content && Array.isArray(tree.content)) {
    tree.content.forEach((child) => {
      const results = searchJSON(child, target, property);
      if (results !== null && results !== undefined) {
        return found = Array.isArray(results) ? [...found, ...results] : [...found, results];
      }
    })
  }

  return found.length ? found : null
}