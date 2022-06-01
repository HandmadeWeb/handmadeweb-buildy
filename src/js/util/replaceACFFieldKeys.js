import uuid4 from 'uuid/v4'

export const replaceKey = () => {
  return `field_${uuid4()}`;
}

export const replaceACFFieldKeys = (item) => {
  item.key = replaceKey();
  if (!Array.isArray(item.sub_fields)) {
    return false
  }
  item.sub_fields.forEach((el) => {
    if (el.id || el.id === '') {
      el.id = replaceKey(el.type);
    }
    if (!Array.isArray(el.sub_fields)) {
      return false
    } else {
      replaceACFFieldKeys(el)
    }
  })
}

