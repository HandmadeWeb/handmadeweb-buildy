// Get current post ID from body tag class
const getPostID = () => {
    return document.querySelector('body')
      .className.split(' ')
      .filter(el => el.includes('postid'))
      .toString()
      .replace(/\D/g, "") || null
}

// Get current page ID from body tag class
const getPageID = () => {
    return document.querySelector('body')
      .className.split(' ')
      .filter(el => el.includes('page-id'))
      .toString()
      .replace(/\D/g, "") || null
}

// Get a custom page/type from the rest API (with async await baked in)
const getCustomData = async (ID, route) => {
    if (!ID) {
      throw new Error('page ID not found')
    }
    try {
      const res = await fetch(`/wp-json/${route}/${ID}`)
      const json = await res.json();
      return json
    } catch (error) {
      console.log(error)
    }
}

// Get a page from the rest API (with async await baked in)
const getPageData = async (ID) => {
    if (!ID) {
      throw new Error('page ID not found')
    }
    try {
      const res = await fetch(`/wp-json/wp/v2/pages/${ID}`)
      const json = await res.json();
      return json
    } catch (error) {
      console.log(error)
    }
}

// Get a post from the rest API (with async await baked in)
const getPostData = async (ID) => {
  if (!ID) {
    throw new Error('post ID not found')
  }
  try {
    const res = await fetch(`/wp-json/wp/v2/posts/${ID}`)
    const json = await res.json();
    return json
  } catch (error) {
    console.log(error)
  }
}

export {getPostID, getPageID, getCustomData, getPageData,getPostData}
